<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- ✅ Tambahan Upload Foto Profil --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Foto Profil</h2>
                            <p class="mt-1 text-sm text-gray-600">Unggah foto profil baru.</p>
                        </header>

                        <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf

                            <input type="file" name="photo" required>
                            @error('photo') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                            <div class="mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Simpan Foto
                                </button>
                            </div>
                        </form>

                        @if (Auth::user()->profile_photo)
                            <div class="mt-4">
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Foto Profil" class="w-20 h-20 rounded-full object-cover">
                            </div>
                        @endif
                    </section>
                </div>
            </div>

            {{-- ✅ Sudah ada sebelumnya: Update Profile Information --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- ✅ Sudah ada sebelumnya: Update Password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- ✅ Sudah ada sebelumnya: Delete User --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
