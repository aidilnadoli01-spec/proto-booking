<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAntreanController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\DisplayAntreanController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PasienProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\TvDisplayController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

// TV Display Routes (Public - tanpa auth)
Route::get('/tv-display', [TvDisplayController::class, 'index'])->name('tv.display');
Route::get('/api/tv-queue', [TvDisplayController::class, 'getQueueData'])->name('api.tv.queue');

// Queue API & Display
Route::get('/display/antrean', [QueueController::class, 'index'])->name('display.antrean');
Route::get('/api/queue/current', [QueueController::class, 'getCurrentQueue']);
Route::get('/api/queue/next', [QueueController::class, 'getNextQueue']);
Route::post('/api/queue/call', [QueueController::class, 'callQueue']);
Route::post('/api/queue/done', [QueueController::class, 'completeQueue']);
Route::post('/api/queue/add', [QueueController::class, 'addDummyQueue']); // For testing

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::get('/jadwal-dokter', [JadwalDokterController::class, 'index'])->name('jadwal.index');

    Route::get('/booking-saya', [BookingController::class, 'index'])->middleware('role:user')->name('booking.index');
    Route::get('/booking-antrean', [BookingController::class, 'create'])->middleware('role:user')->name('booking.create');
    Route::post('/booking-antrean', [BookingController::class, 'store'])->middleware(['role:user', 'throttle:10,1'])->name('booking.store');
    Route::patch('/booking-saya/{pendaftaran}/batal', [BookingController::class, 'cancel'])->middleware(['role:user', 'throttle:10,1'])->name('booking.cancel');
    Route::get('/booking-saya/{pendaftaran}/reschedule', [BookingController::class, 'rescheduleForm'])->middleware('role:user')->name('booking.reschedule.form');
    Route::patch('/booking-saya/{pendaftaran}/reschedule', [BookingController::class, 'reschedule'])->middleware(['role:user', 'throttle:10,1'])->name('booking.reschedule');
    Route::get('/pasien/profil', [PasienProfileController::class, 'edit'])->middleware('role:user')->name('pasien.profile.edit');
    Route::patch('/pasien/profil', [PasienProfileController::class, 'update'])->middleware('role:user')->name('pasien.profile.update');

    Route::middleware('role:admin,super_admin')->group(function () {
        Route::get('/admin/dokter', [DokterController::class, 'index'])->name('dokter.index');
        Route::get('/admin/antrean-harian', [DashboardController::class, 'adminQueue'])->name('admin.queue');
        Route::get('/admin/audit-log', [AuditLogController::class, 'index'])->name('audit.index');
        Route::post('/admin/antrean-harian/panggil-berikutnya', [AdminAntreanController::class, 'callNext'])->middleware('throttle:30,1')->name('admin.queue.call_next');
        Route::post('/api/tv-display/call-next', [TvDisplayController::class, 'callNext'])->middleware('throttle:30,1')->name('api.tv.call_next');
        Route::patch('/admin/antrean/{antrean}/status', [AdminAntreanController::class, 'updateStatus'])->middleware('throttle:60,1')->name('admin.queue.update_status');
        Route::post('/admin/dokter', [DokterController::class, 'store'])->name('dokter.store');
        Route::patch('/admin/dokter/{dokter}', [DokterController::class, 'update'])->name('dokter.update');
        Route::delete('/admin/dokter/{dokter}', [DokterController::class, 'destroy'])->name('dokter.destroy');
        Route::post('/admin/jadwal', [JadwalDokterController::class, 'store'])->name('jadwal.store');
        Route::patch('/admin/jadwal/{jadwal}', [JadwalDokterController::class, 'update'])->name('jadwal.update');
        Route::delete('/admin/jadwal/{jadwal}', [JadwalDokterController::class, 'destroy'])->name('jadwal.destroy');
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/super-admin/users', [UserManagementController::class, 'index'])->name('super.users');
        Route::post('/super-admin/users', [UserManagementController::class, 'store'])->name('super.users.store');
        Route::patch('/super-admin/users/{user}', [UserManagementController::class, 'update'])->name('super.users.update');
        Route::delete('/super-admin/users/{user}', [UserManagementController::class, 'destroy'])->name('super.users.destroy');
        Route::patch('/super-admin/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('super.users.toggle_status');
        
        Route::get('/super-admin/roles', [RoleController::class, 'index'])->name('super.roles');
        Route::post('/super-admin/roles', [RoleController::class, 'store'])->name('super.roles.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
