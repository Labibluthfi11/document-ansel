<<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-bold text-xl sm:text-2xl text-brown-800 leading-tight">📚 Daftar Dokumen</h2>
            @if(auth()->user()->role == 'admin')
                <a href="{{ route('documents.create') }}"
                   class="bg-amber-500 hover:bg-amber-600 text-brown-900 font-semibold py-2 px-4 rounded-lg shadow-md border border-amber-600 transition text-sm sm:text-base">
                    ➕ Tambah Dokumen
                </a>
            @endif
        </div>
    </x-slot>

    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" class="flex flex-wrap gap-2 mb-4 overflow-x-auto">
            <input type="text" name="department" placeholder="Cari Departemen" value="{{ request('department') }}" class="rounded-lg border-gray-300 p-2 text-xs sm:text-base min-w-[120px]">
            <input type="text" name="document_number" placeholder="Cari Nomor Dokumen" value="{{ request('document_number') }}" class="rounded-lg border-gray-300 p-2 text-xs sm:text-base min-w-[120px]">
            <select name="type" class="rounded-lg border-gray-300 p-2 text-xs sm:text-base min-w-[120px]">
                <option value="">-- Semua Jenis Dokumen --</option>
                @foreach(['sop', 'wi', 'form', 'internal memo', 'skm', 'manual book', 'opl'] as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border-gray-300 p-2 text-xs sm:text-base min-w-[120px]">
                <option value="">-- Semua Status --</option>
                <option value="berlaku" {{ request('status') == 'berlaku' ? 'selected' : '' }}>✅ Berlaku</option>
                <option value="tidak_berlaku" {{ request('status') == 'tidak_berlaku' ? 'selected' : '' }}>❌ Tidak Berlaku</option>
            </select>
            <input type="date" name="document_date" value="{{ request('document_date') }}" class="rounded-lg border-gray-300 p-2 text-xs sm:text-base min-w-[120px]">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs sm:text-base">🔍 Cari</button>
            <a href="{{ route('documents.index') }}" class="bg-gray-300 px-3 py-2 rounded-lg text-xs sm:text-base">♻️ Reset</a>
        </form>

        <div class="bg-white shadow-lg rounded-xl overflow-x-auto border border-gray-100 hidden sm:block">
            <table class="min-w-full divide-y divide-gray-200 text-sm animate-fade-in">
                <thead class="bg-gradient-to-r from-amber-600 to-amber-400 text-brown-900 uppercase font-bold tracking-wide text-xs">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Departemen</th>
                        <th class="p-3 text-left">Nomor Dokumen</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Jenis Dokumen</th>
                        <th class="p-3 text-left">Nama Dokumen</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-amber-50 transition duration-300 ease-in-out">
                            <td class="px-3 py-2">{{ $loop->iteration + ($documents->currentPage()-1)*$documents->perPage() }}</td>
                            <td class="px-3 py-2">{{ $doc->department }}</td>
                            <td class="px-3 py-2">{{ $doc->document_number }}</td>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d F Y') }}</td>
                            <td class="px-3 py-2 font-medium capitalize">{{ $doc->type }}</td>
                            <td class="px-3 py-2">{{ $doc->name }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full
                                    {{ $doc->status == 'berlaku' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($doc->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-2 flex gap-2 flex-wrap">
                                @if($doc->status == 'berlaku')
                                    <a href="{{ route('documents.preview', $doc->id) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 px-3 py-1 rounded-md text-xs font-bold border border-blue-300">👁️ Lihat</a>
                                @endif
                                @if(auth()->user()->role == 'admin')
                                    <a href="{{ route('documents.edit', $doc->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-xs font-bold border border-blue-700">✏️ Edit</a>
                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin hapus dokumen ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs font-bold border border-red-700">🗑️ Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-3 py-4 text-center text-gray-500">Tidak ada data ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="block sm:hidden space-y-4">
            @forelse($documents as $i => $doc)
                <div class="bg-white shadow rounded-xl border border-amber-100 p-4 flex flex-col gap-2 animate-fade-in">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="text-lg font-bold text-amber-700">{{ $i + 1 + ($documents->currentPage()-1)*$documents->perPage() }}.</div>
                        <div class="font-semibold text-brown-900 text-base">{{ $doc->name }}</div>
                        <span class="ml-auto px-2 py-1 text-xs font-semibold rounded-full
                            {{ $doc->status == 'berlaku' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($doc->status) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-700 mb-2">
                        <div><span class="font-semibold">Departemen:</span> {{ $doc->department }}</div>
                        <div><span class="font-semibold">No. Dokumen:</span> {{ $doc->document_number }}</div>
                        <div><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d F Y') }}</div>
                        <div><span class="font-semibold">Jenis:</span> {{ ucfirst($doc->type) }}</div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($doc->status == 'berlaku')
                            <a href="{{ route('documents.preview', $doc->id) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 px-3 py-1 rounded-md text-xs font-bold border border-blue-300">👁️ Lihat</a>
                        @endif
                        @if(auth()->user()->role == 'admin')
                            <a href="{{ route('documents.edit', $doc->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-xs font-bold border border-blue-700">✏️ Edit</a>
                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin hapus dokumen ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs font-bold border border-red-700">🗑️ Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Tidak ada data ditemukan.</p>
            @endforelse
        </div>

        <div class="mt-6 flex justify-center">
            {{ $documents->links() }}
        </div>
    </div>
</x-app-layout>
