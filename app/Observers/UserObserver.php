<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserObserver
{
    /**
     * Dipanggil ketika model selesai diupdate
     */
    public function updated(User $user): void
    {
        // Jangan trigger notifikasi jika user sendiri yang admin
        if ($user->role === 'admin') {
            return;
        }

        $changes = $user->getChanges();
        unset($changes['updated_at']); // Jangan notifikasi untuk timestamp update

        if (empty($changes)) {
            return;
        }

        // Dapatkan semua admin
        $admins = User::where('role', 'admin')->get();

        // Prevent duplicate notification - cek apakah sudah ada notif dalam 5 detik terakhir
        $recentNotification = Notification::where('user_id', $user->id)
            ->where('type', 'update')
            ->where('created_at', '>', now()->subSeconds(5))
            ->first();

        if ($recentNotification) {
            return; // Skip kalau baru saja ada notifikasi
        }

        foreach ($admins as $admin) {
            // Ambil nilai lama dari original attributes
            $changedFields = array_keys($changes);
            $oldValues = $user->getOriginal();
            
            $message = sprintf(
                '%s (%s) telah mengupdate data',
                $user->name,
                $user->plat_nomor ?? 'N/A'
            );

            Notification::create([
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'title' => 'Update Data User',
                'message' => $message,
                'type' => 'update',
                'data' => [
                    'model' => 'User',
                    'model_id' => $user->id,
                    'table_name' => 'Users',
                    'changed_fields' => $changedFields,
                    'old_values' => array_intersect_key($oldValues, $changes),
                    'new_values' => $changes,
                ],
            ]);
        }
    }

    /**
     * Dipanggil ketika model baru dibuat
     */
    public function created(User $user): void
    {
        if ($user->role === 'admin') {
            return;
        }

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'title' => 'User Baru',
                'message' => sprintf('%s (%s) telah mendaftar', $user->name, $user->plat_nomor ?? 'N/A'),
                'type' => 'create',
                'data' => [
                    'model' => 'User',
                    'model_id' => $user->id,
                    'table_name' => 'Users',
                ],
            ]);
        }
    }
}
