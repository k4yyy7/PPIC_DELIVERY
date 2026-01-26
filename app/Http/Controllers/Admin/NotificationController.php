<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnreadCount()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return response()->json(['count' => 0]);
        }

        $count = $user->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    public function getNotifications(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return response()->json(['notifications' => []]);
        }

        $limit = $request->get('limit', 10);
        $allNotifications = $user->notifications()
            ->with('user')
            ->latest()
            ->take($limit * 10) // Ambil lebih banyak untuk grouping
            ->get();

        // Group by user_id dan tanggal (semua aksi user dalam 1 hari = 1 notifikasi)
        $grouped = [];
        foreach ($allNotifications as $notification) {
            $userId = $notification->user_id;
            $date = $notification->created_at->toDateString();
            $key = "{$date}_{$userId}";

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id' => $notification->id,
                    'user_id' => $userId,
                    'user_name' => $notification->user->name,
                    'user_plat_nomor' => $notification->user->plat_nomor,
                    'is_unread' => $notification->isUnread(),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_full' => $notification->created_at->format('d M Y H:i'),
                    'tables' => [],
                    'types' => [],
                    'count' => 0,
                    'notification_ids' => [],
                ];
            }
            
            // Kumpulkan semua tabel yang di-update
            $tableName = $notification->data['table_name'] ?? 'Unknown';
            if (!in_array($tableName, $grouped[$key]['tables'])) {
                $grouped[$key]['tables'][] = $tableName;
            }
            
            // Kumpulkan semua tipe aksi
            if (!in_array($notification->type, $grouped[$key]['types'])) {
                $grouped[$key]['types'][] = $notification->type;
            }
            
            $grouped[$key]['count']++;
            $grouped[$key]['notification_ids'][] = $notification->id;
            
            // Update unread status (kalau ada 1 aja yang unread, maka dianggap unread)
            if ($notification->isUnread()) {
                $grouped[$key]['is_unread'] = true;
            }
        }

        // Format message untuk setiap group
        foreach ($grouped as $key => &$group) {
            $actions = [];
            
            // Build action message
            if (in_array('create', $group['types'])) {
                $actions[] = 'membuat data';
            }
            if (in_array('update', $group['types'])) {
                $actions[] = 'mengupdate data';
            }
            if (in_array('delete', $group['types'])) {
                $actions[] = 'menghapus data';
            }
            
            $actionText = implode(' dan ', $actions);
            $tableText = implode(', ', $group['tables']);
            
            // Format nama user dengan plat nomor di depan
            $userDisplay = $group['user_plat_nomor'] 
                ? sprintf('%s (%s)', $group['user_plat_nomor'], $group['user_name'])
                : $group['user_name'];
            
            $group['message'] = sprintf(
                '%s telah %s',
                $userDisplay,
                $actionText
            );
            
            $group['table_name'] = $tableText;
            $group['title'] = $userDisplay; // Set title dengan user display
            
            // Set type berdasarkan aksi terbanyak atau yang pertama
            $group['type'] = $group['types'][0];
        }

        $notifications = array_values(array_slice($grouped, 0, $limit));

        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead(Request $request)
    {
        $notification = Notification::find($request->id);

        if (!$notification || $notification->admin_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return response()->json(['success' => false]);
        }

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function deleteNotification(Request $request)
    {
        $notification = Notification::find($request->id);

        if (!$notification || $notification->admin_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function deleteAllNotifications()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return response()->json(['success' => false]);
        }

        $user->notifications()->delete();

        return response()->json(['success' => true]);
    }
}
