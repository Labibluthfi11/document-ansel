<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-0 justify-between items-start sm:items-center">
            <h2 class="font-bold text-lg sm:text-xl text-brown-800 leading-tight mb-2 sm:mb-0">
                👁️ Preview Dokumen: {{ $doc->name }}
            </h2>
            <a href="{{ route('documents.index') }}"
               class="bg-gray-200 px-3 py-2 rounded text-gray-700 text-sm font-semibold hover:bg-gray-300 w-full sm:w-auto text-center">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>
    <div class="w-full max-w-3xl mx-auto px-2 sm:px-4 py-4 sm:py-6">
        <div class="rounded-xl bg-white border border-amber-200 shadow p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6 mb-4 text-xs sm:text-base">
                <div class="flex-1 space-y-1">
                    <div><span class="font-semibold">Departemen:</span> {{ $doc->department }}</div>
                    <div><span class="font-semibold">Nomor:</span> {{ $doc->document_number }}</div>
                    <div><span class="font-semibold">Jenis:</span> {{ ucfirst($doc->type) }}</div>
                    <div><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}</div>
                </div>
                <div class="mt-2 sm:mt-0">
                    <span class="px-2 py-1 rounded text-xs sm:text-sm {{ $doc->status == 'berlaku' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($doc->status) }}
                    </span>
                </div>
            </div>
            <div class="w-full rounded-lg overflow-hidden border border-gray-200" style="height: 60vh; min-height: 350px; max-height: 70vh;">
                <iframe
                    src="{{ route('documents.stream', $doc->id) }}"
                    width="100%"
                    height="100%"
                    style="min-height:350px; border:none; display:block;"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>
            <div class="mt-3 text-xs text-gray-600 text-center">
                Untuk mencetak dokumen, gunakan tombol print pada toolbar PDF di atas dokumen.
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 640px) {
            /* Responsive tweaks for mobile */
            .rounded-xl { border-radius: 1rem; }
            .p-4 { padding: 1rem; }
        }
    </style>
</x-app-layout>
