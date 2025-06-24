<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'view_user_list',
            'description' => 'Melihat daftar user',
            'ip_address' => request()->ip(),
        ]);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        $allowedEmails = [
            'development@anselmudaberkarya.com',
            'contact@anselmudaberkarya.com',
            'official@anselmudaberkarya.com',
            'hr@anselmudaberkarya.com',
            'plantmanager@anselmudaberkarya.com',
            'procurementppic@anselmudaberkarya.com',
            'warehouselogistic@anselmudaberkarya.com',
            'production@anselmudaberkarya.com',
            'financeaccounting@anselmudaberkarya.com',
            'rndofficer@anselmudaberkarya.com',
        ];

        if (!in_array(strtolower($request->email), $allowedEmails)) {
            return back()->withErrors(['email' => 'Email tidak diizinkan.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make(Str::random(12)),
            'role' => 'user'
        ]);

        $status = Password::sendResetLink(['email' => $user->email]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_user',
            'description' => "Menambahkan user baru: {$user->email}",
            'ip_address' => request()->ip(),
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dibuat dan link reset password telah dikirim.');
        }

        return redirect()->route('admin.users.index')
            ->with('warning', 'User berhasil dibuat, tapi gagal mengirim email reset password.');
    }

    public function sendResetPassword($id)
    {
        $user = User::findOrFail($id);
        $status = Password::sendResetLink(['email' => $user->email]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'send_reset_password',
            'description' => "Mengirim link reset password ke user: {$user->email}",
            'ip_address' => request()->ip(),
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password sudah dikirim ke email user: ' . $user->email);
        } else {
            return back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
        }
    }

    public function makeAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'admin') {
            $user->role = 'admin';
            $user->save();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'make_admin',
                'description' => "Mengubah role user {$user->email} menjadi admin",
                'ip_address' => request()->ip(),
            ]);
        }
        return back()->with('success', 'User berhasil dijadikan admin.');
    }

    public function makeUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'user') {
            $user->role = 'user';
            $user->save();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'make_user',
                'description' => "Mengubah role user {$user->email} menjadi user biasa",
                'ip_address' => request()->ip(),
            ]);
        }
        return back()->with('success', 'Role user berhasil diubah menjadi user.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return back()->withErrors(['error' => 'Kamu tidak bisa menghapus akun sendiri!']);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_user',
            'description' => "Menghapus user {$user->email}",
            'ip_address' => request()->ip(),
        ]);

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function activityLog()
    {
        $activityLogs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('asu.activity-log', compact('activityLogs'));
    }

    /**
     * Hapus semua log aktivitas (fitur baru).
     */
    public function purgeActivityLog()
    {
        ActivityLog::truncate();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'purge_log',
            'description' => "Menghapus semua log aktivitas",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.activity-log')
            ->with('success', 'Semua log aktivitas berhasil dihapus.');
    }
}
