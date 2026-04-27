<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UpcycleHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-element {
            background-image: url('{{ asset('image/black.png') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-50 bg-element min-h-screen text-gray-800 relative">

    <nav class="w-full py-6 px-8 md:px-16 flex justify-between items-center backdrop-blur-sm">
        <div class="flex items-center">
            <img class="h-10" src="{{ asset('image/logomain.png') }}" alt="Logo UpcycleHub">
        </div>

        <div class="flex gap-6 items-center font-medium">
            @auth
                <a href="{{ route('dashboard') }}" class="hover:text-[#0e6e36] transition-colors">Dasbor</a>
            @else
                <button onclick="openModal('login')"
                    class="hover:text-[#0e6e36] hover:scale-105 transition-all">Masuk</button>
                <span class="text-gray-400 pointer-events-none">|</span>
                <button onclick="openModal('register')"
                    class="bg-[#0e6e36] text-white px-5 py-2 rounded-full hover:bg-green-800 hover:scale-105 transition-all shadow-md">Daftar</button>
            @endauth
        </div>
    </nav>

    <main class="w-full px-8 md:px-16 flex flex-col md:flex-row items-center justify-between min-h-[80vh]">
        <div class="w-full md:w-1/2 space-y-6 z-10">
            <h1 class="text-6xl md:text-7xl font-extrabold text-[#0e6e36] tracking-tight">UPYCLEHUB</h1>
            <h2 class="text-2xl md:text-3xl font-medium leading-snug text-gray-700">
                “Hijaukan Bumi, Hemat Energi, <br /> Mulai dari Sekarang!”
            </h2>
            <button onclick="openModal('register')"
                class="group relative flex items-center gap-3 bg-[#0e6e36] text-white px-6 py-3 rounded-full font-semibold shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 hover:bg-green-800 mt-8">
                <span>Mulai Jelajahi</span>
                <svg viewBox="0 0 14 15" fill="none" class="w-4 h-4 group-hover:translate-x-1 transition-transform">
                    <path
                        d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"
                        fill="currentColor"></path>
                </svg>
            </button>
        </div>

        <div class="w-full md:w-1/2 flex justify-center md:justify-end mt-12 md:mt-0 z-10">
            <img src="{{ asset('image/animasi.png') }}" alt="Ilustrasi Daur Ulang"
                class="w-full max-w-lg object-contain drop-shadow-2xl">
        </div>
    </main>

    <div id="authModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300 items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300 relative"
            id="modalContent">

            <button onclick="closeModal()"
                class="absolute top-5 right-5 text-gray-400 hover:text-gray-700 transition-colors bg-gray-100 hover:bg-gray-200 p-2 rounded-full z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div class="p-8 pt-10">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Selamat Datang</h3>
                    <p class="text-sm text-gray-500">Silakan masuk atau buat akun baru.</p>
                </div>

                <div class="bg-gray-100 p-1.5 rounded-2xl flex mb-8">
                    <button id="tab-login" onclick="switchTab('login')"
                        class="w-1/2 py-2.5 rounded-xl text-sm font-bold text-[#0e6e36] bg-white shadow-sm transition-all">Masuk</button>
                    <button id="tab-register" onclick="switchTab('register')"
                        class="w-1/2 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-700 transition-all">Daftar</button>
                </div>

                <form id="form-login" action="{{ route('login') }}" method="POST" class="space-y-5 block">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Email /
                            Username</label>
                        <input type="text" name="email"
                            class="w-full px-3 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0e6e36]/30 focus:border-[#0e6e36] transition-all outline-none text-gray-800"
                            required placeholder="nama@email.com">
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Kata
                                Sandi</label>
                            <a href="#" class="text-xs font-semibold text-[#0e6e36] hover:underline">Lupa sandi?</a>
                        </div>
                        <input type="password" name="password"
                            class="w-full px-3 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0e6e36]/30 focus:border-[#0e6e36] transition-all outline-none text-gray-800"
                            required placeholder="••••••••">
                    </div>

                    <button type="submit"
                        class="w-full bg-[#0e6e36] text-white py-3.5 rounded-xl font-bold tracking-wide hover:bg-green-800 hover:shadow-lg hover:-translate-y-0.5 transition-all mt-6">Masuk
                        Sekarang</button>
                </form>

                <form id="form-register" action="{{ route('register') }}" method="POST" class="space-y-5 hidden">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Nama
                            Lengkap</label>
                        <input type="text" name="name"
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0e6e36]/30 focus:border-[#0e6e36] transition-all outline-none text-gray-800"
                            required placeholder="John Doe">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat
                            Email</label>
                        <input type="email" name="email"
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0e6e36]/30 focus:border-[#0e6e36] transition-all outline-none text-gray-800"
                            required placeholder="nama@email.com">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Kata Sandi</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0e6e36]/30 focus:border-[#0e6e36] transition-all outline-none text-gray-800"
                            required placeholder="Minimal 8 karakter">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Konfirmasi Kata
                            Sandi</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#0e6e36]/30 focus:border-[#0e6e36] transition-all outline-none text-gray-800"
                            required placeholder="Ulangi kata sandi">
                    </div>

                    <button type="submit"
                        class="w-full bg-[#0e6e36] text-white py-3.5 rounded-xl font-bold tracking-wide hover:bg-green-800 hover:shadow-lg hover:-translate-y-0.5 transition-all mt-6">Buat
                        Akun Baru</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('authModal');
        const modalContent = document.getElementById('modalContent');
        const formLogin = document.getElementById('form-login');
        const formRegister = document.getElementById('form-register');
        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');

        function openModal(type) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);
            switchTab(type);
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        function switchTab(type) {
            // Style classes untuk tab yang aktif
            const activeClasses = ['bg-white', 'text-[#0e6e36]', 'shadow-sm'];
            const inactiveClasses = ['text-gray-500'];

            if (type === 'login') {
                formLogin.classList.remove('hidden');
                formRegister.classList.add('hidden');

                tabLogin.classList.add(...activeClasses);
                tabLogin.classList.remove(...inactiveClasses);

                tabRegister.classList.add(...inactiveClasses);
                tabRegister.classList.remove(...activeClasses);
            } else {
                formRegister.classList.remove('hidden');
                formLogin.classList.add('hidden');

                tabRegister.classList.add(...activeClasses);
                tabRegister.classList.remove(...inactiveClasses);

                tabLogin.classList.add(...inactiveClasses);
                tabLogin.classList.remove(...activeClasses);
            }
        }

        // If user hits /login or /register, we redirect to /?auth=...
        // Auto-open the correct tab for better UX.
        (function bootAuthModalFromQuery() {
            const auth = new URLSearchParams(window.location.search).get('auth');
            if (auth === 'login' || auth === 'register') {
                openModal(auth);
            }
        })();
    </script>

    @if ($errors->any())
        <script>
            // Auto-open modal and show SweetAlert when login/register validation fails.
            const initialTab = @json(old('name') ? 'register' : 'login');
            openModal(initialTab);

            const errors = @json($errors->all());
            function escapeHtml(unsafe) {
                return String(unsafe)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            let errorHtml = '<ul style="text-align:left; padding-left: 18px; margin: 0;">';
            for (let i = 0; i < errors.length; i++) {
                errorHtml += '<li>' + escapeHtml(errors[i]) + '</li>';
            }
            errorHtml += '</ul>';

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: errorHtml,
                confirmButtonText: 'Oke',
            });
        </script>
    @endif
</body>

</html>