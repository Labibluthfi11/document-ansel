<x-app-layout>
    <div class="py-6 md:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 space-y-7 md:space-y-8">

            <div class="bg-white shadow-xl rounded-2xl p-4 md:p-8">
                <h2 class="text-lg md:text-2xl font-bold mb-4 md:mb-6 flex items-center gap-2">
                    👥 <span>Manajemen User</span>
                </h2>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-xl text-sm md:text-base">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-xl text-sm md:text-base">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- TABLE MODE (desktop/tablet) --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-2xl overflow-hidden shadow text-xs md:text-base">
                        <thead class="bg-gradient-to-r from-blue-100 to-purple-100">
                            <tr>
                                <th class="py-2 px-3 md:py-3 md:px-4 font-bold text-gray-700 border-b">#</th>
                                <th class="py-2 px-3 md:py-3 md:px-4 font-bold text-gray-700 border-b">Nama</th>
                                <th class="py-2 px-3 md:py-3 md:px-4 font-bold text-gray-700 border-b">Email</th>
                                <th class="py-2 px-3 md:py-3 md:px-4 font-bold text-gray-700 border-b">Role</th>
                                <th class="py-2 px-3 md:py-3 md:px-4 font-bold text-gray-700 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $i => $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-2 px-3 md:py-2 md:px-4 border-b text-center">{{ $i + 1 }}</td>
                                    <td class="py-2 px-3 md:py-2 md:px-4 border-b">{{ $user->name }}</td>
                                    <td class="py-2 px-3 md:py-2 md:px-4 border-b">{{ $user->email }}</td>
                                    <td class="py-2 px-3 md:py-2 md:px-4 border-b text-center">
                                        <span class="inline-block px-3 py-1 rounded-full {{ $user->role === 'admin' ? 'bg-purple-200 text-purple-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 md:py-2 md:px-4 border-b text-center">
                                        @if(auth()->id() !== $user->id)
                                            <div class="flex flex-wrap items-center gap-2 justify-center">
                                                {{-- Tombol Jadikan Admin/User --}}
                                                @if($user->role !== 'admin')
                                                    <form method="POST" action="{{ route('admin.users.makeAdmin', $user->id) }}">
                                                        @csrf
                                                        <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition font-medium shadow-sm text-xs md:text-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                                            Admin
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.users.makeUser', $user->id) }}">
                                                        @csrf
                                                        <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-gray-500 text-white rounded-xl hover:bg-gray-700 transition font-medium shadow-sm text-xs md:text-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 12H6"/></svg>
                                                            User
                                                        </button>
                                                    </form>
                                                @endif
                                                {{-- Tombol Kirim Reset Password --}}
                                                <form method="POST" action="{{ route('admin.sendResetPassword', $user->id) }}">
                                                    @csrf
                                                    <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium shadow-sm text-xs md:text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8"/><path d="M21 8v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8"/><rect width="18" height="14" x="3" y="6" rx="2"/></svg>
                                                        Reset
                                                    </button>
                                                </form>
                                                {{-- Tombol Hapus User --}}
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium shadow-sm text-xs md:text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7"/><path d="M9 7V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v3"/><path d="M4 7h16"/></svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Ini Kamu</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($users->isEmpty())
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-400">Belum ada user.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- CARD MODE (mobile, <sm) --}}
                <div class="block sm:hidden space-y-4">
                    @forelse($users as $i => $user)
                        <div class="rounded-xl border border-gray-200 bg-white shadow p-4 flex flex-col gap-2">
                            <div class="flex items-center gap-3">
                                <div class="text-lg font-bold text-blue-600">{{ $i + 1 }}.</div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                                <div class="ml-auto">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs {{ $user->role === 'admin' ? 'bg-purple-200 text-purple-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @if(auth()->id() !== $user->id)
                                    {{-- Jadikan Admin/User --}}
                                    @if($user->role !== 'admin')
                                        <form method="POST" action="{{ route('admin.users.makeAdmin', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium shadow-sm text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                                Admin
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.makeUser', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-700 transition font-medium shadow-sm text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 12H6"/></svg>
                                                User
                                            </button>
                                        </form>
                                    @endif
                                    {{-- Reset Password --}}
                                    <form method="POST" action="{{ route('admin.sendResetPassword', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm text-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8"/><path d="M21 8v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8"/><rect width="18" height="14" x="3" y="6" rx="2"/></svg>
                                            Reset
                                        </button>
                                    </form>
                                    {{-- Hapus User --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-sm text-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7"/><path d="M9 7V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v3"/><path d="M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic text-xs">Ini Kamu</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl bg-white border border-gray-200 text-center text-gray-400 py-6">
                            Belum ada user.
                        </div>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold shadow hover:bg-blue-700 transition text-base">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
