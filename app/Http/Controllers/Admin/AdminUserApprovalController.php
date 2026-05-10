<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserRegistrationApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminUserApprovalController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->where('status', 'pending')->paginate(10);
        return view('admin.user-approval.index', compact('users'));
    }

    public function approve(User $user)
    {
        if ($user->status !== 'pending') {
            return redirect()->back()->with('error', 'User tidak dalam status pending.');
        }

        $user->update(['status' => 'approved']);

        Mail::to($user->email)->send(new UserRegistrationApproved($user));

        return redirect()->route('admin.approvals.index')->with('success', 'User berhasil disetujui. Email notifikasi telah dikirim.');
    }
}
