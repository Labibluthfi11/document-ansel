<x-app-layout>
    <div class="min-h-screen flex flex-col bg-gradient-to-b from-blue-50 to-white text-gray-800">

        {{-- Hero Section --}}
        <section class="flex-1 flex flex-col-reverse md:flex-row items-center px-6 md:px-16 py-32 md:py-24 gap-10 mt-16">
            <div class="md:w-1/2 space-y-6">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Kelola <span class="text-blue-600">Dokumen</span> Perusahaan Anda <br> Lebih Cepat & Aman
                </h1>
                <p class="text-lg text-gray-600">
                    Sistem pengelolaan dokumen modern berbasis cloud untuk PT. Ansel. Akses mudah, aman, dan cepat di mana saja.
                </p>
                <div class="space-x-3">
                    <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow">
                        🚀 Mulai Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-3 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition">
                        Sudah Punya Akun
                    </a>
                </div>
            </div>
           
        </section>



        {{-- Footer --}}
        <footer class="bg-white text-center text-gray-500 py-6 text-sm border-t">
            © {{ date('Y') }} <strong class="text-blue-600">PT.AnselMudaBerkarya</strong> - Semua Hak Dilindungi.
        </footer>

    </div>
</x-app-layout>
