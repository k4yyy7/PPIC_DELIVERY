<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataUserController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\DriverItemController;
use App\Http\Controllers\Admin\ArmadaItemController;
use App\Http\Controllers\Admin\DokumentController;
use App\Http\Controllers\Admin\EnvironmentController;
use App\Http\Controllers\Admin\SafetyController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DailyReportController;


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    // Admin landing/dashboard
    Route::get('/dashboard-admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    // Data User
    Route::get('/datauser', [DataUserController::class, 'index'])->name('datauser.index');
    Route::get('/datauser/create', [DataUserController::class, 'create'])->name('datauser.create');
    Route::post('/datauser', [DataUserController::class, 'store'])->name('datauser.store');
    Route::get('/datauser/{id}/edit', [DataUserController::class, 'edit'])->name('datauser.edit');
    Route::put('/datauser/{id}', [DataUserController::class, 'update'])->name('datauser.update');
    Route::delete('/datauser/{id}', [DataUserController::class, 'destroy'])->name('datauser.destroy');
    
    // Cars (resourceful routes)
    Route::resource('cars', CarController::class)->except(['show']);
    

    // Driver (CRUD murni nama driver)
    Route::resource('drivers', App\Http\Controllers\Admin\DriverController::class)->except(['show']);

    // Driver Items (resourceful routes)
    Route::resource('driver-items', DriverItemController::class)->except(['show']);

    // Armada Items (resourceful routes)
    Route::resource('armada-items', ArmadaItemController::class)->except(['show']);

    // Dokument (resourceful routes)
    Route::resource('dokument', DokumentController::class)->except(['show']);

    // Environment (resourceful routes)
    Route::resource('environment', EnvironmentController::class)->except(['show']);

    // Safety (resourceful routes)
    Route::resource('safety', SafetyController::class)->except(['show']);
    
    // Notification routes
    Route::get('/notifications/get', [NotificationController::class, 'getNotifications'])->name('admin.notifications.get');
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('admin.notifications.count');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('admin.notifications.delete');
    Route::delete('/notifications', [NotificationController::class, 'deleteAllNotifications'])->name('admin.notifications.delete-all');
    
    // Daily Reports - Monthly Recap
    Route::get('/reports/monthly', [DashboardController::class, 'monthlyReports'])->name('admin.reports.monthly');
    Route::get('/reports/chart', [DashboardController::class, 'dailyReportsChart'])->name('admin.reports.chart');
    Route::get('/reports/summary-chart', [DashboardController::class, 'summaryChart'])->name('admin.reports.summary-chart');
});

// User routes
use App\Http\Controllers\User\ActiveDriverController;
Route::prefix('user')->middleware(['auth', 'isUser'])->group(function () {
    Route::get('/dashboard-user', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/daily', [DailyReportController::class, 'index'])->name('user.daily.index');
    Route::get('/daily/driver', [DailyReportController::class, 'driverIndex'])->name('user.daily.driver');
    Route::get('/daily/armada', [DailyReportController::class, 'armadaIndex'])->name('user.daily.armada');
    Route::get('/daily/dokument', [DailyReportController::class, 'dokumenIndex'])->name('user.daily.dokument');
    Route::get('/daily/environment', [DailyReportController::class, 'environmentIndex'])->name('user.daily.environment');
    Route::get('/daily/safety', [DailyReportController::class, 'safetyIndex'])->name('user.daily.safety');
    Route::post('/daily', [DailyReportController::class, 'store'])->name('user.daily.store');

    // Pilih driver aktif harian
    Route::get('/driver/active', [ActiveDriverController::class, 'index'])->name('user.driver.active');
    Route::post('/driver/active', [ActiveDriverController::class, 'store'])->name('user.driver.active.store');
    Route::post('/daily/multiple', [DailyReportController::class, 'storeMultiple'])->name('user.daily.store-multiple');
    Route::get('/daily/history', [DailyReportController::class, 'history'])->name('user.daily.history');
});

// Public / authenticated user home
Route::get('/home', [HomeController::class, 'index'])->name('home');

