<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-white">
                ✏️ Edit Dokumen
            </h2>
            <a href="{{ route('documents.index') }}"
               class="flex items-center text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 px-4 py-2 rounded-lg shadow transition duration-300">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-100 dark:border-gray-700">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-dokumen">
                @csrf
                @method('PUT')

                {{-- Jenis Dokumen --}}
                <div class="space-y-1">
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Jenis Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Jenis...</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('type', $document->type) == $type ? 'selected' : '' }}>
                                {{ strtoupper($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Dokumen --}}
                <div class="space-y-1">
                    <label for="document_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tanggal Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="document_date" id="document_date" required
                           value="{{ old('document_date', $document->document_date) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Nomor Dokumen --}}
                <div class="space-y-1">
                    <label for="document_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nomor Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="document_number" name="document_number"
                        class="w-full rounded-lg border-gray-200 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white p-3"
                        value="{{ old('document_number', $document->document_number) }}"
                        placeholder="Akan otomatis terisi setelah pilih jenis & tanggal, tapi bisa diedit">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Bisa diedit jika ingin custom. Format: jenis-0001-tahun (contoh: sop-0001-2025)
                    </p>
                </div>

                {{-- Departemen --}}
                <div class="space-y-1">
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Departemen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="department" id="department" required
                           value="{{ old('department', $document->department) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Nama Dokumen --}}
                <div class="space-y-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $document->name) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Contoh: SOP Pengelolaan Keuangan">
                </div>

                {{-- File Upload --}}
                <div class="space-y-1">
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        File Dokumen (Kosongkan jika tidak ingin mengubah)
                    </label>
                    <input type="file" name="file" id="file" accept=".pdf,.doc,.docx"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-600 dark:file:text-gray-200 dark:hover:file:bg-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Format file: PDF, DOC, DOCX (Maks. 10MB)
                    </p>
                </div>

                {{-- Status --}}
                <div class="space-y-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="berlaku" {{ old('status', $document->status) == 'berlaku' ? 'selected' : '' }}>Berlaku</option>
                        <option value="tidak_berlaku" {{ old('status', $document->status) == 'tidak_berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold rounded-lg shadow transition duration-300">
                        Update Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
