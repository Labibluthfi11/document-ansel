<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password - PT Ansel Muda Berkarya</title>
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

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Konfirmasi Password</h2>
        <p class="text-center text-gray-500 mb-6">
            Ini area yang aman. Silakan masukkan password kamu untuk melanjutkan.
        </p>

        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan password">
                @error('password')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-300 ease-in-out">
                Konfirmasi
            </button>
        </form>

        <div class="mt-6 flex justify-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Kembali ke login
            </a>
        </div>

        <p class="mt-6 text-center text-gray-500 text-sm">© {{ date('Y') }} PT Ansel Muda Berkarya</p>
    </div>

</body>

</html>
