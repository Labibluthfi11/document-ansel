<x-app-layout>
    <div class="min-h-screen flex flex-col bg-gradient-to-b from-blue-50 to-white text-gray-800 text-center px-4 relative overflow-hidden">

        {{-- Moving Bubbles Animation --}}
        <div class="absolute inset-0 -z-10 opacity-20">
            <div class="absolute w-96 h-96 bg-blue-300 rounded-full blur-3xl animate-bubble1"></div>
            <div class="absolute w-80 h-80 bg-purple-300 rounded-full blur-3xl animate-bubble2"></div>
            <div class="absolute w-72 h-72 bg-indigo-300 rounded-full blur-3xl animate-bubble3"></div>
        </div>

        {{-- Hero Section --}}
        <div class="flex-1 flex flex-col items-center justify-center py-16 md:py-24 animate-fade-in-up">

            <h1 class="text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-indigo-500 drop-shadow mb-6 animate-glitch">
                404
            </h1>

            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 animate-fade-in">
                Ngapain Kamu Kesini, Sayaangkuu? 😅
            </h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-xl mb-8 animate-fade-in delay-200">
                Disini nggak ada apa-apa loh… Balik yuk, aku kangen kamu 🥺💙
            </p>

            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 animate-fade-in delay-300">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white rounded-lg font-semibold shadow-lg hover:scale-105 hover:shadow-xl hover:brightness-110 transition transform duration-300 text-base sm:text-lg relative overflow-hidden group">
                    🏠 <span class="ml-2">Balik ke Beranda yuk</span>
                    <span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-20 transition duration-300 animate-pulse"></span>
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 hover:border-blue-700 transition hover:scale-105 text-base sm:text-lg duration-300">
                    🔑 <span class="ml-2">Login dulu Sayaang</span>
                </a>
            </div>

        </div>

        {{-- Footer --}}
        <footer class="bg-white text-center text-gray-500 py-5 text-sm border-t animate-fade-in delay-500">
            © {{ date('Y') }} <strong class="text-blue-600">PT.AnselMudaBerkarya</strong> - Semua Hak Dilindungi.
        </footer>

        {{-- Custom Animations --}}
        <style>
            @keyframes fade-in-up {
                0% { opacity: 0; transform: translateY(20px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fade-in-up 1s ease-out both;
            }
            @keyframes fade-in {
                0% { opacity: 0; }
                100% { opacity: 1; }
            }
            .animate-fade-in {
                animation: fade-in 1.2s ease forwards;
            }
            @keyframes bubble1 {
                0%, 100% { transform: translate(-10%, -10%); }
                50% { transform: translate(10%, 10%); }
            }
            .animate-bubble1 {
                animation: bubble1 12s ease-in-out infinite;
                top: 20%; left: 30%;
            }
            @keyframes bubble2 {
                0%, 100% { transform: translate(10%, -5%); }
                50% { transform: translate(-10%, 5%); }
            }
            .animate-bubble2 {
                animation: bubble2 15s ease-in-out infinite;
                top: 60%; left: 65%;
            }
            @keyframes bubble3 {
                0%, 100% { transform: translate(-5%, 5%); }
                50% { transform: translate(5%, -5%); }
            }
            .animate-bubble3 {
                animation: bubble3 18s ease-in-out infinite;
                top: 10%; left: 10%;
            }
            @keyframes glitch {
                0% { text-shadow: 2px 2px red, -2px -2px blue; }
                25% { text-shadow: -2px 2px lime, 2px -2px cyan; }
                50% { text-shadow: 2px -2px yellow, -2px 2px magenta; }
                75% { text-shadow: -2px -2px purple, 2px 2px orange; }
                100% { text-shadow: 2px 2px red, -2px -2px blue; }
            }
            .animate-glitch {
                animation: glitch 2.5s infinite;
            }
            .delay-200 { animation-delay: 0.2s; }
            .delay-300 { animation-delay: 0.3s; }
            .delay-500 { animation-delay: 0.5s; }
        </style>

    </div>
</x-app-layout>
