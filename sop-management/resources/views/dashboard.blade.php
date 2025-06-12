<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome Card --}}
            <div class="bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-500 text-white shadow-xl rounded-2xl p-8 flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h3>
                    <p class="text-lg">Login sebagai <span class="font-bold">{{ auth()->user()->role }}</span></p>
                </div>
                <img src="https://www.svgrepo.com/show/452091/office-archive.svg"
                     class="h-20 hidden md:block opacity-80 drop-shadow-lg" alt="Document Illustration"/>
            </div>

            {{-- Info Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl">
                    <div class="bg-blue-100 p-4 rounded-full text-3xl">📁</div>
                    <div>
                        <h4 class="font-bold text-gray-700 mb-1">Dokumen Anda</h4>
                        <p class="text-gray-500 text-sm">Ayo lihat SOP-nya sekarang.</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl">
                    <div class="bg-green-100 p-4 rounded-full text-3xl">👤</div>
                    <div>
                        <h4 class="font-bold text-gray-700 mb-1">Profil</h4>
                        <p class="text-gray-500 text-sm">Lihat dan ubah data profil Anda.</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl">
                    <div class="bg-red-100 p-4 rounded-full text-3xl">🚪</div>
                    <div>
                        <h4 class="font-bold text-gray-700 mb-1">Logout</h4>
                        <p class="text-gray-500 text-sm">Keluar dari akun Anda dengan aman.</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-4 mt-2">
                <a href="{{ route('documents.index') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-xl font-semibold shadow hover:bg-green-700 transition">
                    📄 <span class="ml-2">Lihat Dokumen</span>
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('documents.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold shadow hover:bg-blue-700 transition">
                        📤 <span class="ml-2">Tambah Dokumen</span>
                    </a>
                @endif
            </div>

            {{-- Statistik Dokumen Keseluruhan --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">📊 <span>Statistik Dokumen</span></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="rounded-xl bg-blue-50 p-6 flex flex-col items-center">
                        <div class="text-4xl font-bold text-blue-500 mb-2">{{ $totalDocument }}</div>
                        <div class="text-gray-700 font-semibold">Total Dokumen</div>
                    </div>
                    <div class="rounded-xl bg-green-50 p-6 flex flex-col items-center">
                        <div class="text-4xl font-bold text-green-500 mb-2">{{ $totalBerlaku }}</div>
                        <div class="text-green-700 font-semibold">Berlaku</div>
                    </div>
                    <div class="rounded-xl bg-red-50 p-6 flex flex-col items-center">
                        <div class="text-4xl font-bold text-red-500 mb-2">{{ $totalTidakBerlaku }}</div>
                        <div class="text-red-700 font-semibold">Tidak Berlaku</div>
                    </div>
                    <div class="rounded-xl bg-yellow-50 p-6 flex flex-col items-center">
                        <div class="text-lg font-bold text-yellow-600 mb-2">Per Jenis Dokumen</div>
                        <div class="flex flex-wrap justify-center gap-1">
                            @foreach($jenisStats as $jenis => $jumlah)
                                <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-bold shadow-sm mb-1">
                                    {{ strtoupper($jenis) }}: {{ $jumlah }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Dokumen per Departemen & Jenis --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">🏢 <span>Statistik per Departemen & Jenis</span></h2>
                @if(empty($departmentStats))
                    <p class="text-gray-500">Belum ada dokumen yang diunggah.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-2xl overflow-hidden shadow">
                            <thead class="bg-gradient-to-r from-blue-100 to-purple-100">
                                <tr>
                                    <th class="py-3 px-4 font-bold text-gray-700 border-b">Departemen</th>
                                    <th class="py-3 px-4 font-bold text-gray-700 border-b">Total</th>
                                    <th class="py-3 px-4 font-bold text-green-600 border-b">Berlaku ✅</th>
                                    <th class="py-3 px-4 font-bold text-red-600 border-b">Tidak Berlaku ❌</th>
                                    @foreach($jenisList as $jenis)
                                        <th class="py-3 px-4 font-bold border-b" style="color:#a16207">{{ strtoupper($jenis) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentStats as $dept => $stat)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-2 px-4 font-semibold text-gray-800 border-b bg-gray-50">{{ $dept }}</td>
                                        <td class="py-2 px-4 text-blue-500 font-bold text-center border-b">{{ $stat['total'] }}</td>
                                        <td class="py-2 px-4 text-green-600 font-bold text-center border-b">{{ $stat['berlaku'] }}</td>
                                        <td class="py-2 px-4 text-red-600 font-bold text-center border-b">{{ $stat['tidak_berlaku'] }}</td>
                                        @foreach($jenisList as $jenis)
                                            <td class="py-2 px-4 text-center border-b">
                                                <span class="inline-block px-2 py-1 rounded-full {{ $stat[$jenis] > 0 ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-100 text-gray-400' }}">
                                                    {{ $stat[$jenis] }}
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
