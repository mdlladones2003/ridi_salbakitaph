<?php

use App\Http\Controllers\{
    CommunityController,
    ProfileController,
    ReportController,
    CheckInController,
    PostController,
    CommentController,
    ReactionController,
    HelpOfferController,
    VerificationController,
    AlertController,
    BadgeController
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    ReportController as AdminReportController,
    AlertController as AdminAlertController,
    UserController as AdminUserController,
    BarangayController as AdminBarangayController,
    DisasterUpdateController as AdminDisasterUpdateController,
    BadgeController as AdminBadgeController
};
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Community
    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::get('/community/map', [CommunityController::class, 'map'])->name('community.map');
    Route::get('/community/awareness', [CommunityController::class, 'awareness'])->name('community.awareness');
    Route::get('/community/search', [CommunityController::class, 'search'])->name('community.search');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Reports
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/community/reports/{report}', [ReportController::class, 'show'])->name('community.reports.show');

    // Verifications
    Route::post('/community/{report}/verify', [VerificationController::class, 'store'])->name('reports.verify');

    // Check-ins
    Route::post('/check-in', [CheckInController::class, 'store'])->name('check-in.store');

    // Posts
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('posts/{post_id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post_id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Reactions
    Route::post('/posts/{post}/reactions', [ReactionController::class, 'store'])->name('reactions.store');

    // Help Offers
    Route::post('/help-offers', [HelpOfferController::class, 'store'])->name('help-offers.store');
    Route::patch('/help-offers/{helpOffer}/toggle', [HelpOfferController::class, 'toggleAvailability'])->name('help-offers.toggle');

    // Alerts
    Route::get('/community/alerts/archive', [AlertController::class, 'archive'])->name('community.alerts.archive');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/reputation', [AdminUserController::class, 'updateReputation'])->name('users.reputation');

    // Badges Management
    Route::get('/badges', [AdminBadgeController::class, 'index'])->name('badges.index');
    Route::post('/badges', [AdminBadgeController::class, 'store'])->name('badges.store');
    Route::delete('/badges/{badge}', [AdminBadgeController::class, 'destroy'])->name('badges.destroy');
    Route::post('/badges/auto-award', [AdminBadgeController::class, 'autoAward'])->name('badges.auto-award');

    // Disaster Updates Management
    Route::get('/disasters', [AdminDisasterUpdateController::class, 'index'])->name('disasters.index');
    Route::get('/disasters/create', [AdminDisasterUpdateController::class, 'create'])->name('disasters.create');
    Route::post('/disasters', [AdminDisasterUpdateController::class, 'store'])->name('disasters.store');
    Route::get('/disasters/{disaster}', [AdminDisasterUpdateController::class, 'show'])->name('disasters.show');
    Route::get('/disasters/{disaster}/edit', [AdminDisasterUpdateController::class, 'edit'])->name('disasters.edit');
    Route::put('/disasters/{disaster}', [AdminDisasterUpdateController::class, 'update'])->name('disasters.update');
    Route::delete('/disasters/{disaster}', [AdminDisasterUpdateController::class, 'destroy'])->name('disasters.destroy');

    // Reports Management
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::delete('/reports/{report}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
    Route::post('/reports/bulk-action', [AdminReportController::class, 'bulkAction'])->name('reports.bulk-action');

    // Alerts Management
    Route::get('/alerts', [AdminAlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AdminAlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [AdminAlertController::class, 'store'])->name('alerts.store');
    Route::patch('/alerts/{alert}/toggle', [AdminAlertController::class, 'toggleActive'])->name('alerts.toggle');
    Route::delete('/alerts/{alert}', [AdminAlertController::class, 'destroy'])->name('alerts.destroy');

    // Barangays Management
    Route::get('/barangays', [AdminBarangayController::class, 'index'])->name('barangays.index');
    Route::get('/barangays/create', [AdminBarangayController::class, 'create'])->name('barangays.create');
    Route::post('/barangays', [AdminBarangayController::class, 'store'])->name('barangays.store');
    Route::get('/barangays/{barangay}', [AdminBarangayController::class, 'show'])->name('barangays.show');
    Route::get('/barangays/{barangay}/edit', [AdminBarangayController::class, 'edit'])->name('barangays.edit');
    Route::put('/barangays/{barangay}', [AdminBarangayController::class, 'update'])->name('barangays.update');
    Route::delete('/barangays/{barangay}', [AdminBarangayController::class, 'destroy'])->name('barangays.destroy');
    Route::post('/barangays/fetch-coordinates', [AdminBarangayController::class, 'fetchCoordinates'])->name('barangays.fetch-coordinates');

});


require __DIR__.'/auth.php';
