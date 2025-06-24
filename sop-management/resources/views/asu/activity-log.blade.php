<x-app-layout>
    <div class="py-8 md:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1 flex items-center gap-2">
                        📝 <span>Log Aktivitas</span>
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base">Riwayat aktivitas sistem oleh semua user.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold text-xs md:text-sm shadow">
                        Total Log: {{ $activityLogs->total() }}
                    </span>
                    <form method="POST" action="{{ route('admin.activity-log.purge') }}" onsubmit="return confirm('Yakin ingin menghapus semua log aktivitas?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-800 text-xs md:text-sm font-bold py-1 px-3 rounded-lg border border-red-300 shadow transition">
                            🗑️ Hapus Semua Log
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl overflow-x-auto ring-1 ring-gray-200 animate-fade-in">
                <table class="min-w-full text-xs md:text-sm text-gray-700">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-200 via-purple-200 to-yellow-100">
                            <th class="py-3 px-4 text-left font-bold">Waktu</th>
                            <th class="py-3 px-4 text-left font-bold">User</th>
                            <th class="py-3 px-4 text-left font-bold">Aksi</th>
                            <th class="py-3 px-4 text-left font-bold">Deskripsi</th>
                            <th class="py-3 px-4 text-left font-bold">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLogs as $log)
                            <tr class="hover:bg-yellow-50 transition duration-200 ease-in-out">
                                <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1">
                                        <svg fill="none" viewBox="0 0 24 24" class="w-4 h-4 text-blue-400">
                                            <path stroke="currentColor" stroke-width="1.5" d="M12 6v6l3 3"/>
                                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/>
                                        </svg>
                                        {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y H:i:s') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-200 to-purple-200 flex items-center justify-center text-lg font-bold text-purple-700 shadow">
                                            {{ strtoupper(substr($log->user->name ?? '-', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $log->user->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $log->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">
                                    <span class="inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold text-xs shadow">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-100">
                                    <span class="block text-gray-700">{{ $log->description }}</span>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 text-gray-500">
                                        <svg fill="none" viewBox="0 0 24 24" class="w-4 h-4">
                                            <path stroke="currentColor" stroke-width="1.5" d="M17 7h.01M12 17.5C7.305 17.5 3.5 13.695 3.5 9c0-1.236.25-2.416.703-3.486a1.109 1.109 0 0 1 1.314-.661c2.09.651 4.308.971 6.483.971 2.175 0 4.393-.32 6.483-.971a1.109 1.109 0 0 1 1.314.661A8.477 8.477 0 0 1 20.5 9c0 4.695-3.805 8.5-8.5 8.5Z"/>
                                        </svg>
                                        {{ $log->ip_address }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-400 py-8">Belum ada aktivitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center">
                {{ $activityLogs->links() }}
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.4s ease-out;
        }
    </style>
</x-app-layout>
