<x-app-layout>
    <div class="py-8 md:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1 flex items-center gap-2">
                        📝 <span>Log Aktivitas</span>
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base">Riwayat aktivitas sistem oleh semua user.</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold text-xs md:text-base shadow">
                        Total Log: {{ $activityLogs->total() }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl overflow-hidden ring-1 ring-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs md:text-sm text-gray-700">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-200 via-purple-200 to-yellow-100">
                                <th class="py-3 px-3 md:px-4 text-left font-bold">Waktu</th>
                                <th class="py-3 px-3 md:px-4 text-left font-bold">User</th>
                                <th class="py-3 px-3 md:px-4 text-left font-bold">Aksi</th>
                                <th class="py-3 px-3 md:px-4 text-left font-bold">Deskripsi</th>
                                <th class="py-3 px-3 md:px-4 text-left font-bold">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLogs as $log)
                                <tr class="hover:bg-yellow-50 transition">
                                    <td class="py-3 px-3 md:px-4 border-b border-gray-100 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1">
                                            <svg fill="none" viewBox="0 0 24 24" class="w-4 h-4 text-blue-400"><path stroke="currentColor" stroke-width="1.5" d="M12 6v6l3 3"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/></svg>
                                            {{-- Jam WIB, format lengkap --}}
                                            {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y H:i:s') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 md:px-4 border-b border-gray-100 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-200 to-purple-200 flex items-center justify-center text-lg font-bold text-purple-700 shadow">
                                                {{ strtoupper(substr($log->user->name ?? '-', 0, 1)) }}
                                            </span>
                                            <span>
                                                <span class="font-bold">{{ $log->user->name ?? '-' }}</span>
                                                <span class="block text-xs text-gray-400">{{ $log->user->email ?? '' }}</span>
                                            </span>
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 md:px-4 border-b border-gray-100 whitespace-nowrap">
                                        <span class="inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold text-xs shadow">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 md:px-4 border-b border-gray-100">
                                        <span class="block text-gray-700">{{ $log->description }}</span>
                                    </td>
                                    <td class="py-3 px-3 md:px-4 border-b border-gray-100 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 text-gray-500">
                                            <svg fill="none" viewBox="0 0 24 24" class="w-4 h-4"><path stroke="currentColor" stroke-width="1.5" d="M17 7h.01M12 17.5C7.305 17.5 3.5 13.695 3.5 9c0-1.236.25-2.416.703-3.486a1.109 1.109 0 0 1 1.314-.661c2.09.651 4.308.971 6.483.971 2.175 0 4.393-.32 6.483-.971a1.109 1.109 0 0 1 1.314.661A8.477 8.477 0 0 1 20.5 9c0 4.695-3.805 8.5-8.5 8.5Z"/></svg>
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
            </div>

            <div class="mt-6 flex justify-center">
                {{ $activityLogs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
