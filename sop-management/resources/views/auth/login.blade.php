<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT Ansel Muda Berkarya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 1s ease-out;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-800 via-blue-600 to-blue-900 flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 fade-in-up">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo/ansel.jpg') }}" alt="Logo AMB" class="h-16 w-auto rounded-lg">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Selamat Datang</h2>
        <p class="text-center text-gray-500 mb-6">Login ke akun PT Ansel Muda Berkarya</p>

        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- Form Login Biasa --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" required autofocus
                    class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan email">
                @error('email')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan password">
                @error('password')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 focus:ring-blue-500">
                    <span>Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
                @endif
            </div>

            <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-300 ease-in-out">
                Masuk
            </button>
        </form>

        {{-- Garis Pemisah --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-white px-2 text-gray-500">Atau masuk dengan</span>
            </div>
        </div>

        {{-- Tombol Login Google & GitHub --}}
        <div class="space-y-3">
            <a href="{{ route('auth.redirect', 'google') }}"
                class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                Masuk dengan Google
            </a>
            <a href="{{ route('auth.redirect', 'github') }}"
                class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-900 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path
                        d="M12 0C5.37 0 0 5.373 0 12c0 5.303 3.438 9.8 8.207 11.387.6.113.793-.26.793-.577v-2.17c-3.338.727-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.834 2.806 1.304 3.492.997.107-.775.418-1.305.76-1.605-2.665-.303-5.466-1.334-5.466-5.931 0-1.31.467-2.381 1.235-3.221-.124-.303-.536-1.522.117-3.176 0 0 1.008-.322 3.3 1.23A11.525 11.525 0 0112 5.802c1.02.005 2.047.138 3.006.404 2.29-1.552 3.296-1.23 3.296-1.23.655 1.654.243 2.873.12 3.176.77.84 1.232 1.911 1.232 3.221 0 4.609-2.803 5.624-5.475 5.921.43.372.823 1.102.823 2.222v3.293c0 .32.192.694.8.576C20.565 21.796 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                </svg>
                Masuk dengan GitHub
            </a>
        </div>

        <p class="mt-6 text-center text-gray-500 text-sm">© {{ date('Y') }} PT Ansel Muda Berkarya</p>
    </div>

</body>

</html>
