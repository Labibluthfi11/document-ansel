<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan daftar notifikasi dengan filter (read/unread + action)
     */
    public function index(Request $request)
    {
        $notifications = auth()->user()->notifications();

        // Filter berdasarkan status dibaca
        if ($request->status === 'unread') {
            $notifications->whereNull('read_at');
        } elseif ($request->status === 'read') {
            $notifications->whereNotNull('read_at');
        }

        // Filter berdasarkan tipe aksi (create, update, delete)
        if ($request->filled('action')) {
            $notifications->where('data->action', $request->action);
        }

        $notifications = $notifications->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Tandai semua notifikasi sebagai dibaca
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Hapus satu notifikasi
     */
    public function destroy($id)
    {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Bulk hapus beberapa notifikasi
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        auth()->user()->notifications()->whereIn('id', $ids)->delete();

        return back()->with('success', 'Beberapa notifikasi berhasil dihapus.');
    }
}
