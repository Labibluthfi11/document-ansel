<nav class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Brand --}}
            <div class="flex items-center min-w-0 gap-2">
                <img src="{{ asset('images/logo/ansel.jpg') }}"
                    alt="Logo AMB"
                    class="h-9 w-9 rounded-md object-cover shadow-sm flex-shrink-0" />
                <span class="text-lg font-bold text-gray-800 truncate">PT. Ansel Muda Berkarya</span>
            </div>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-6">
                <form action="{{ route('dashboard') }}" method="GET">
                    <button type="submit"
                            class="relative px-4 py-2 rounded-lg text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 font-medium transition-all duration-200 text-sm {{ request()->routeIs('dashboard') ? 'text-indigo-700 bg-indigo-50 font-semibold' : '' }}">
                        Dashboard
                    </button>
                </form>
            </div>

            {{-- Right Profile/Action --}}
            <div class="flex items-center gap-2">
                @auth
                    <span class="hidden sm:block text-base font-medium text-gray-700 truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                    <div class="relative group">
                        <button class="w-10 h-10 rounded-full overflow-hidden border-2 border-indigo-500 focus:outline-none">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/profile-photos/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-lg font-semibold text-indigo-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </button>
                        {{-- Desktop Profile Dropdown --}}
                        <div class="hidden md:block absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transform transition-all duration-200 z-30">
                            <div class="py-1">
                                <form action="{{ route('profile.edit') }}" method="GET">
                                    <button type="submit"
                                            class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition">
                                        Profile
                                    </button>
                                </form>
                                <hr class="my-1 border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Hamburger --}}
                <button id="mobile-menu-button" class="md:hidden ml-2 text-gray-600 hover:text-indigo-700 focus:outline-none transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="md:hidden px-4 pb-4 pt-2 bg-white border-b border-gray-200 shadow transition-all duration-200 hidden">
        <form action="{{ route('dashboard') }}" method="GET">
            <button type="submit"
                    class="flex items-center w-full px-4 py-2 text-base text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                Dashboard
            </button>
        </form>
        @auth
            @if(auth()->user()->role === 'admin')
                <form action="{{ route('documents.create') }}" method="GET">
                    <button type="submit"
                            class="flex items-center w-full px-4 py-2 text-base text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        Upload Dokumen
                    </button>
                </form>
            @endif
            <form action="{{ route('profile.edit') }}" method="GET">
                <button type="submit"
                        class="flex items-center w-full px-4 py-2 text-base text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition">
                    Profile
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center w-full px-4 py-2 text-base text-red-600 hover:bg-red-50 transition"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </button>
            </form>
        @endauth
    </div>
</nav>

<script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
    // Optional: close menu when click outside
    document.addEventListener('click', function(event) {
        if (!btn.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
