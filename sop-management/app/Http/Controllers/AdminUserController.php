<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Kirimkan link reset password ke email user.
     */
    public function sendResetPassword($id)
    {
        $user = User::findOrFail($id);

        // Kirim link reset ke email user
        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password sudah dikirim ke email user: '.$user->email);
        } else {
            return back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
        }
    }

    /**
     * Jadikan user menjadi admin.
     */
    public function makeAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'admin') {
            $user->role = 'admin';
            $user->save();
        }
        return back()->with('success', 'User berhasil dijadikan admin.');
    }

    /**
     * Turunkan admin jadi user biasa.
     */
    public function makeUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'user') {
            $user->role = 'user';
            $user->save();
        }
        return back()->with('success', 'Role user berhasil diubah menjadi user.');
    }

    /**
     * Hapus user (admin tidak bisa hapus dirinya sendiri).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Tidak boleh hapus diri sendiri
        if ($user->id == auth()->id()) {
            return back()->withErrors(['error' => 'Kamu tidak bisa menghapus akun sendiri!']);
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
