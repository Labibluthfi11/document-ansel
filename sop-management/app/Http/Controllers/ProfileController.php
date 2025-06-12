<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Validasi tambahan untuk foto
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ]);

            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::delete('public/profile-photos/' . $user->profile_photo_path);
            }

            // Simpan foto baru
            $filename = uniqid() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('public/profile-photos', $filename);

            $validated['profile_photo_path'] = $filename;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Hapus foto profil jika ada
        if ($user->profile_photo_path) {
            Storage::delete('public/profile-photos/' . $user->profile_photo_path);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
