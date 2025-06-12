<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-brown-800 leading-tight">📚 Daftar Dokumen</h2>
            @if(auth()->user()->role == 'admin')
                <a href="{{ route('documents.create') }}"
                   class="bg-amber-500 hover:bg-amber-600 text-brown-900 font-semibold py-2 px-4 rounded-lg shadow-md border border-amber-600">
                    ➕ Tambah Dokumen
                </a>
            @endif
        </div>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <form method="GET" class="flex flex-wrap gap-2 mb-4">
            <input type="text" name="department" placeholder="Cari Departemen" value="{{ request('department') }}" class="rounded-lg border-gray-300 p-2">
            <input type="text" name="document_number" placeholder="Cari Nomor Dokumen" value="{{ request('document_number') }}" class="rounded-lg border-gray-300 p-2">

            <select name="type" class="rounded-lg border-gray-300 p-2">
                <option value="">-- Semua Jenis Dokumen --</option>
                @foreach(['sop', 'wi', 'form', 'internal memo', 'skm', 'manual book', 'opl'] as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>

            <select name="status" class="rounded-lg border-gray-300 p-2">
                <option value="">-- Semua Status --</option>
                <option value="berlaku" {{ request('status') == 'berlaku' ? 'selected' : '' }}>✅ Berlaku</option>
                <option value="tidak_berlaku" {{ request('status') == 'tidak_berlaku' ? 'selected' : '' }}>❌ Tidak Berlaku</option>
            </select>

            <input type="date" name="document_date" value="{{ request('document_date') }}" class="rounded-lg border-gray-300 p-2">

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">🔍 Cari</button>
            <a href="{{ route('documents.index') }}" class="bg-gray-300 px-3 py-2 rounded-lg">♻️ Reset</a>
        </form>

        <!-- Table -->
        <div class="bg-white shadow-lg rounded-xl overflow-x-auto border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
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
                        <tr class="hover:bg-amber-50 transition">
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
                                    <a href="{{ route('documents.download', $doc->id) }}" class="bg-amber-500 hover:bg-amber-600 text-brown-900 px-3 py-1 rounded-md text-xs font-bold shadow-sm border border-amber-700">📥 Download</a>
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

        <div class="mt-4">
            {{ $documents->links() }}
        </div>
    </div>
</x-app-layout>
