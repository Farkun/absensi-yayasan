<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function getUnreadCount()
    {
        // Hitung jumlah notifikasi yang belum dibaca untuk pengguna yang sedang login
        $unreadCount = count(Auth::guard('user')->user()->unreadNotifications);

        return response()->json(['unread_count' => $unreadCount]);
    }
    public function markAsRead($id)
    {
        // Cari notifikasi berdasarkan ID (termasuk yang sudah dibaca)
        $notification = Auth::guard('user')->user()->notifications->where('id', $id)->first();

        // Jika notifikasi ditemukan
        if ($notification) {
            // Tandai sebagai dibaca jika belum dibaca
            if (!$notification->read_at) {
                $notification->markAsRead();
            }

            // Ambil URL dari data notifikasi atau gunakan default '/login'
            $redirectUrl = $notification->data['url'] ?? '/login';

            // Arahkan pengguna ke URL yang ditentukan
            return redirect($redirectUrl);
        }

        // Jika notifikasi tidak ditemukan, arahkan ke halaman default
        return redirect('/login');
    }
    public function history()
    {
        $user = Auth::guard('user')->user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10); // Mengambil semua notifikasi user
        return view('historinotif.index', compact('notifications'));
    }
}
