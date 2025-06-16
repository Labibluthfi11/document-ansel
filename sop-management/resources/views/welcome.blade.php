<x-app-layout>
    <div class="min-h-screen flex flex-col bg-gradient-to-b from-blue-50 to-white text-gray-800">

        {{-- Hero Section --}}
        <section class="flex-1 flex flex-col-reverse md:flex-row items-center px-5 sm:px-8 md:px-16 py-16 md:py-24 gap-10 mt-14 md:mt-20">
            <div class="md:w-1/2 w-full space-y-6">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Kelola <span class="text-blue-600">Dokumen</span> Perusahaan Anda<br>
                    Lebih Cepat &amp; Aman
                </h1>
                <p class="text-base sm:text-lg text-gray-600">
                    Sistem pengelolaan dokumen modern berbasis cloud untuk PT. Ansel.<br>
                    Akses mudah, aman, dan cepat di mana saja.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-5 mt-4">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow transform hover:-translate-y-1 hover:shadow-lg text-base sm:text-lg">
                        🚀 <span class="ml-2">Mulai Sekarang</span>
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center px-6 py-3 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition hover:border-blue-700 text-base sm:text-lg">
                        Sudah Punya Akun
                    </a>
                </div>
            </div>
            {{-- Optional: Hero Image --}}
            <div class="md:w-1/2 w-full flex justify-center md:justify-end mb-10 md:mb-0">
                <img src="https://www.svgrepo.com/show/452091/office-archive.svg"
                    alt="Document Hero" class="w-44 sm:w-60 md:w-80 drop-shadow-xl opacity-90">
            </div>
        </section>

        {{-- Footer --}}
        <footer class="bg-white text-center text-gray-500 py-6 text-sm border-t">
            © {{ date('Y') }} <strong class="text-blue-600">PT.AnselMudaBerkarya</strong> - Semua Hak Dilindungi.
        </footer>

    </div>
</x-app-layout>
