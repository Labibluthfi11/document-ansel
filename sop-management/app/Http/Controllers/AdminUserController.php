<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\ActivityLog;
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

        // Log aktivitas melihat daftar user (hanya admin)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'view_user_list',
            'description' => 'Melihat daftar user',
            'ip_address' => request()->ip(),
        ]);

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

        // Log aktivitas kirim reset password
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'send_reset_password',
            'description' => "Mengirim link reset password ke user: {$user->email}",
            'ip_address' => request()->ip(),
        ]);

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

            // Log aktivitas upgrade user jadi admin
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'make_admin',
                'description' => "Mengubah role user {$user->email} menjadi admin",
                'ip_address' => request()->ip(),
            ]);
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

            // Log aktivitas downgrade admin jadi user
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'make_user',
                'description' => "Mengubah role user {$user->email} menjadi user biasa",
                'ip_address' => request()->ip(),
            ]);
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

        // Log aktivitas hapus user
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_user',
            'description' => "Menghapus user {$user->email}",
            'ip_address' => request()->ip(),
        ]);

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * Tampilkan log aktivitas untuk admin.
     */
    public function activityLog()
    {
        $activityLogs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(25);

        // GANTI PATH VIEW SESUAI FOLDER BARU
        return view('asu.activity-log', compact('activityLogs'));
    }
}
