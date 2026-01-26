<?php

namespace App\Observers;

use App\Models\DailyReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DailyReportObserver
{
    public function updated(DailyReport $report): void
    {
        // Dapatkan user yang melakukan update
        if (!Auth::check() || Auth::user()->role === 'admin') {
            return;
        }

        $user = Auth::user();

        $changes = $report->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        // Prevent duplicate notification - cek apakah sudah ada notif dalam 5 detik terakhir
        $recentNotification = Notification::where('user_id', $user->id)
            ->where('type', 'update')
            ->where('created_at', '>', now()->subSeconds(5))
            ->first();

        if ($recentNotification) {
            return; // Skip kalau baru saja ada notifikasi
        }

        $admins = User::where('role', 'admin')->get();
        $changedFields = array_keys($changes);

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'title' => 'Update Daily Report',
                'message' => sprintf(
                    '%s (%s) telah mengupdate laporan harian',
                    $user->name,
                    $user->plat_nomor ?? 'N/A'
                ),
                'type' => 'update',
                'data' => [
                    'model' => 'DailyReport',
                    'model_id' => $report->id,
                    'table_name' => 'Daily Reports',
                    'changed_fields' => $changedFields,
                    'old_values' => array_intersect_key($report->getOriginal(), $changes),
                    'new_values' => $changes,
                ],
            ]);
        }
    }

    public function created(DailyReport $report): void
    {
        if (!Auth::check() || Auth::user()->role === 'admin') {
            return;
        }

        $user = Auth::user();

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'title' => 'Daily Report Baru',
                'message' => sprintf(
                    '%s (%s) telah membuat laporan harian',
                    $user->name,
                    $user->plat_nomor ?? 'N/A'
                ),
                'type' => 'create',
                'data' => [
                    'model' => 'DailyReport',
                    'model_id' => $report->id,
                    'table_name' => 'Daily Reports',
                ],
            ]);
        }
    }
}
