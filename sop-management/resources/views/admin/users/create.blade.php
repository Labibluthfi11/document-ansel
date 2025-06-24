<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 leading-tight flex items-center gap-2 animate-fade-in">
            👤 {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 transition duration-300 ease-in-out animate-slide-up">

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow-md animate-fade-in-down">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg shadow-md animate-fade-in-down">
                        @foreach($errors->all() as $error)
                            <p>❌ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    {{-- Name Input --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition"
                            required
                        >
                    </div>

                    {{-- Email Input --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email User</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition"
                            required
                        >
                    </div>

                    {{-- Submit Button --}}
                    <div class="text-right">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition transform hover:scale-105 duration-200">
                            ➕ Tambah User & Kirim Link Reset
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    
</x-app-layout>
