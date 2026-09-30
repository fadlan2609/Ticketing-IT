<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfficeInfoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SlaConfigController;
use App\Http\Controllers\TicketActionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Amanah Service Desk
|--------------------------------------------------------------------------
*/

// =========================================================
// PUBLIC ROUTES
// =========================================================

Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================
// AUTHENTICATED ROUTES
// =========================================================
Route::middleware(['auth'])->group(function () {

    // ===================== DASHBOARD =====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===================== INFORMASI KANTOR =====================
    Route::prefix('informasi')->name('office-info.')->group(function () {
        Route::get('/', [OfficeInfoController::class, 'index'])->name('index');
        Route::get('create', [OfficeInfoController::class, 'create'])->name('create');
        Route::post('/', [OfficeInfoController::class, 'store'])->name('store');
        Route::get('{officeInfo}', [OfficeInfoController::class, 'show'])->name('show');
        Route::get('{officeInfo}/edit', [OfficeInfoController::class, 'edit'])->name('edit');
        Route::put('{officeInfo}', [OfficeInfoController::class, 'update'])->name('update');
        Route::delete('{officeInfo}', [OfficeInfoController::class, 'destroy'])->name('destroy');
    });

    // ===================== NOTIFIKASI =====================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('api/unread-count', [NotificationController::class, 'unreadCount'])->name('api.unread-count');
        Route::get('api/latest', [NotificationController::class, 'latest'])->name('api.latest');
    });

    // ===================== TIKET =====================
    Route::resource('tickets', TicketController::class);

    Route::prefix('tickets/{ticket}')->name('tickets.')->group(function () {
        Route::post('assign', [TicketActionController::class, 'assign'])->name('assign');
        Route::post('take', [TicketActionController::class, 'take'])->name('take');
        Route::post('status', [TicketActionController::class, 'updateStatus'])->name('status');
        Route::post('comment', [TicketActionController::class, 'comment'])->name('comment');
        Route::post('reopen', [TicketActionController::class, 'reopen'])->name('reopen');

        Route::delete('attachments/{attachment}', [TicketActionController::class, 'deleteAttachment'])
            ->name('attachments.delete');
        Route::get('attachments/{attachment}/download', [TicketActionController::class, 'downloadAttachment'])
            ->name('attachments.download');
    });

    // ===================== KNOWLEDGE BASE =====================
    Route::prefix('kb')->name('kb.')->group(function () {
        Route::get('/', [KnowledgeBaseController::class, 'index'])->name('index');
        Route::get('create', [KnowledgeBaseController::class, 'create'])->name('create');
        Route::post('/', [KnowledgeBaseController::class, 'store'])->name('store');
        Route::get('{article}', [KnowledgeBaseController::class, 'show'])->name('show');
        Route::get('{article}/edit', [KnowledgeBaseController::class, 'edit'])->name('edit');
        Route::put('{article}', [KnowledgeBaseController::class, 'update'])->name('update');
        Route::delete('{article}', [KnowledgeBaseController::class, 'destroy'])->name('destroy');
        Route::post('{article}/rate', [KnowledgeBaseController::class, 'rate'])->name('rate');
    });

    // ===================== PROFIL USER =====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    // =========================================================
    // SUPER ADMIN ONLY
    // =========================================================
    Route::middleware('role:Super Admin')->group(function () {

        // Manajemen User
        Route::resource('users', UserController::class);

        // ========== CABANG ==========
        Route::resource('branches', BranchController::class);

        // ========== DIVISI ==========
        Route::resource('divisions', DivisionController::class);

        // Pengaturan SLA
        Route::prefix('settings/sla')->name('settings.sla.')->group(function () {
            Route::get('/', [SlaConfigController::class, 'index'])->name('index');
            Route::put('/', [SlaConfigController::class, 'update'])->name('update');
            Route::put('/working-hours', [SlaConfigController::class, 'updateWorkingHours'])
                ->name('working-hours');
            Route::post('/holidays', [SlaConfigController::class, 'storeHoliday'])
                ->name('holidays.store');
            Route::delete('/holidays/{holiday}', [SlaConfigController::class, 'destroyHoliday'])
                ->name('holidays.destroy');
        });

        // ========== PENGATURAN PERUSAHAAN ==========
        Route::prefix('settings/company')->name('settings.company.')->group(function () {
            Route::get('/', [CompanySettingController::class, 'edit'])->name('edit');
            Route::put('/', [CompanySettingController::class, 'update'])->name('update');
            Route::delete('/logo/{type}', [CompanySettingController::class, 'destroyLogo'])
                ->name('logo.destroy');
        });
    });

    // =========================================================
    // SUPER ADMIN, SUPERVISOR, AUDITOR
    // =========================================================
    Route::middleware('role:Super Admin|Supervisor|Auditor')
        ->prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
            Route::get('export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
        });
});

// =========================================================
// AUTH ROUTES (Breeze)
// =========================================================
require __DIR__.'/auth.php';