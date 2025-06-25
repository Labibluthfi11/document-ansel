<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-bold mb-4">Notifikasi</h2>

        <div class="flex justify-between items-center mb-4">
            <form action="{{ route('notifications.index') }}" method="GET" class="flex items-center gap-2">
                {{-- Filter Status --}}
                <select name="status" onchange="this.form.submit()" class="border-gray-300 rounded px-3 py-1 text-sm">
                    <option value="">Semua Status</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>

                {{-- Filter Aksi --}}
                <select name="action" onchange="this.form.submit()" class="border-gray-300 rounded px-3 py-1 text-sm">
                    <option value="">Semua Aksi</option>
                    <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Tambah</option>
                    <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Edit</option>
                    <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Hapus</option>
                </select>
            </form>

            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit" class="text-sm bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>

        {{-- BULK DELETE --}}
        <form method="POST" action="{{ route('notifications.bulkDelete') }}">
            @csrf
            @method('DELETE')

            <div class="flex items-center justify-between mb-2">
                <button type="submit" class="bg-red-600 text-white text-sm px-3 py-1 rounded hover:bg-red-700">
                    Hapus yang Dipilih
                </button>
                <div class="flex gap-2 text-xs text-gray-600">
                    <button type="button" onclick="toggleCheckboxes(true)" class="underline">Pilih Semua</button>
                    <button type="button" onclick="toggleCheckboxes(false)" class="underline">Batal Pilih</button>
                </div>
            </div>

            <div class="bg-white shadow rounded divide-y">
                @forelse($notifications as $notification)
                    @php
                        $action = $notification->data['action'] ?? '';
                        $badgeColor = match($action) {
                            'create' => 'bg-blue-100 text-blue-700',
                            'update' => 'bg-yellow-100 text-yellow-800',
                            'delete' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-600'
                        };
                    @endphp
                    <div class="p-4 flex items-start gap-4 {{ is_null($notification->read_at) ? 'bg-indigo-50' : '' }}">
                        <input type="checkbox" name="ids[]" value="{{ $notification->id }}" class="mt-1 checkbox-item">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="font-semibold">{{ $notification->data['title'] ?? 'Judul Tidak Ada' }}</h3>
                                @if($action)
                                    <span class="text-xs px-2 py-0.5 rounded {{ $badgeColor }}">
                                        {{ ucfirst($action) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">{{ $notification->data['body'] ?? 'Tidak ada isi notifikasi' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                            @csrf
