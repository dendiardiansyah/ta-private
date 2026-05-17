<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserRegistrationApproved;
use App\Mail\UserRegistrationRejected;
use Illuminate\Support\Facades\Mail;

class AdminUserApprovalController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->where('status', 'pending')
            ->whereHas('roles', function ($query) {
                $query->whereNotIn('name', ['user', 'nasabah']);
            })
            ->paginate(10);
        return view('admin.user-approval.index', compact('users'));
    }

    public function approve(User $user)
    {
        if ($user->status !== 'pending') {
            return redirect()->back()->with('error', 'User tidak dalam status pending.');
        }

        $user->update(['status' => 'approved']);

        $user->load('roles');
        Mail::to($user->email)->send(new UserRegistrationApproved($user));

        return redirect()->route('admin.approvals.index')->with('success', 'User berhasil disetujui. Email notifikasi telah dikirim.');
    }

    public function reject(User $user)
    {
        if ($user->status !== 'pending') {
            return redirect()->back()->with('error', 'User tidak dalam status pending.');
        }

        $user->update(['status' => 'rejected']);

        $user->load('roles');
        Mail::to($user->email)->send(new UserRegistrationRejected($user));

        return redirect()->route('admin.approvals.index')->with('success', 'User berhasil ditolak. Email notifikasi telah dikirim.');
    }
}
