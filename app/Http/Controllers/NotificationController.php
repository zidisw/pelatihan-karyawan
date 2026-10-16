<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Menandai satu notifikasi sebagai sudah dibaca (PRD Bab 12).
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        if ((int) $notification->user_id !== (int) auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if ($request->filled('redirect_to')) {
            return redirect($request->input('redirect_to'));
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Menandai seluruh notifikasi milik user yang sedang login sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Seluruh notifikasi telah ditandai sudah dibaca.');
    }
}
