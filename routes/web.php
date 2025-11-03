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
    BarangayController,
    EvacuationCenterController,
    AlertController,
    BadgeController,
    DisasterUpdateController,
    EvacuationRouteController
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    ReportController as AdminReportController,
    AlertController as AdminAlertController,
    UserController as AdminUserController,
    BarangayController as AdminBarangayController,
    EvacuationCenterController as AdminEvacuationCenterController,
    DisasterUpdateController as AdminDisasterUpdateController,
    BadgeController as AdminBadgeController,
    EvacuationRouteController as AdminEvacuationRouteController
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
    Route::get('/community/posts', [CommunityController::class, 'posts'])->name('community.posts');
    Route::get('/community/map', [CommunityController::class, 'map'])->name('community.map');
    Route::get('/community/alerts', [CommunityController::class, 'alerts'])->name('community.alerts');
    Route::get('/community/search', [CommunityController::class, 'search'])->name('community.search');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reports
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/community/reports/{report}', [ReportController::class, 'show'])->name('community.reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Verifications
    Route::post('/reports/{report}/verify', [VerificationController::class, 'store'])->name('reports.verify');

    // Check-ins
    Route::post('/check-in', [CheckInController::class, 'store'])->name('check-in.store');
    Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');

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
    Route::get('/help-offers', [HelpOfferController::class, 'index'])->name('help-offers.index');
    Route::get('/help-offers/create', [HelpOfferController::class, 'create'])->name('help-offers.create');
    Route::post('/help-offers', [HelpOfferController::class, 'store'])->name('help-offers.store');
    Route::delete('/help-offers/{helpOffer}', [HelpOfferController::class, 'destroy'])->name('help-offers.destroy');
    Route::patch('/help-offers/{helpOffer}/toggle', [HelpOfferController::class, 'toggleAvailability'])->name('help-offers.toggle');

    // Alerts
    Route::get('/community/alerts/archive', [AlertController::class, 'archive'])->name('community.alerts.archive');
    Route::get('/community/alerts/{alert}', [AlertController::class, 'show'])->name('community.alerts.show');

    // Badges & Leaderboard
    Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');
    Route::get('/leaderboard', [BadgeController::class, 'leaderboard'])->name('badges.leaderboard');

    // Disaster Updates
    Route::get('/disasters', [DisasterUpdateController::class, 'index'])->name('disasters.index');
    Route::get('/disasters/type/{type}', [DisasterUpdateController::class, 'byType'])->name('disasters.by-type');
    Route::get('/disasters/{disaster}', [DisasterUpdateController::class, 'show'])->name('disasters.show');

    // Evacuation Routes
    Route::get('/evacuation-routes', [EvacuationRouteController::class, 'index'])->name('evacuation-routes.index');
    Route::get('/evacuation-routes/map', [EvacuationRouteController::class, 'map'])->name('evacuation-routes.map');
    Route::get('/evacuation-routes/barangay/{barangay}', [EvacuationRouteController::class, 'byBarangay'])->name('evacuation-routes.barangay');
    Route::get('/evacuation-routes/{evacuationRoute}', [EvacuationRouteController::class, 'show'])->name('evacuation-routes.show');

    // Barangays
    Route::get('/barangays', [BarangayController::class, 'index'])->name('barangays.index');
    Route::get('/barangays/{barangay}', [BarangayController::class, 'show'])->name('barangays.show');

    // Evacuation Centers
    Route::get('/evacuation-centers', [EvacuationCenterController::class, 'index'])->name('evacuation-centers.index');
    Route::get('/evacuation-centers/{evacuationCenter}', [EvacuationCenterController::class, 'show'])->name('evacuation-centers.show');

    // Force Logout Route
    Route::post('/force-logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/')->with('status', 'You have been logged out by the system.');
    })->name('force-logout')->middleware('auth');
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

    // Disaster Updates Management (verb-based routes replacing resource)
    Route::get('/disasters', [AdminDisasterUpdateController::class, 'index'])->name('disasters.index');
    Route::get('/disasters/create', [AdminDisasterUpdateController::class, 'create'])->name('disasters.create');
    Route::post('/disasters', [AdminDisasterUpdateController::class, 'store'])->name('disasters.store');
    Route::get('/disasters/{disaster}', [AdminDisasterUpdateController::class, 'show'])->name('disasters.show');
    Route::get('/disasters/{disaster}/edit', [AdminDisasterUpdateController::class, 'edit'])->name('disasters.edit');
    Route::put('/disasters/{disaster}', [AdminDisasterUpdateController::class, 'update'])->name('disasters.update');
    Route::delete('/disasters/{disaster}', [AdminDisasterUpdateController::class, 'destroy'])->name('disasters.destroy');

    // Reports Management (verb-based routes)
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

    // Barangays Management (verb-based routes replacing resource)
    Route::get('/barangays', [AdminBarangayController::class, 'index'])->name('barangays.index');
    Route::get('/barangays/create', [AdminBarangayController::class, 'create'])->name('barangays.create');
    Route::post('/barangays', [AdminBarangayController::class, 'store'])->name('barangays.store');
    Route::get('/barangays/{barangay}', [AdminBarangayController::class, 'show'])->name('barangays.show');
    Route::get('/barangays/{barangay}/edit', [AdminBarangayController::class, 'edit'])->name('barangays.edit');
    Route::put('/barangays/{barangay}', [AdminBarangayController::class, 'update'])->name('barangays.update');
    Route::delete('/barangays/{barangay}', [AdminBarangayController::class, 'destroy'])->name('barangays.destroy');

    // Evacuation Centers Management (verb-based routes)
    Route::get('/evacuation-centers', [AdminEvacuationCenterController::class, 'index'])->name('evacuation-centers.index');
    Route::get('/evacuation-centers/create', [AdminEvacuationCenterController::class, 'create'])->name('evacuation-centers.create');
    Route::post('/evacuation-centers', [AdminEvacuationCenterController::class, 'store'])->name('evacuation-centers.store');
    Route::get('/evacuation-centers/{evacuationCenter}', [AdminEvacuationCenterController::class, 'show'])->name('evacuation-centers.show');
    Route::get('/evacuation-centers/{evacuationCenter}/edit', [AdminEvacuationCenterController::class, 'edit'])->name('evacuation-centers.edit');
    Route::put('/evacuation-centers/{evacuationCenter}', [AdminEvacuationCenterController::class, 'update'])->name('evacuation-centers.update');
    Route::delete('/evacuation-centers/{evacuationCenter}', [AdminEvacuationCenterController::class, 'destroy'])->name('evacuation-centers.destroy');
    Route::patch('/evacuation-centers/{evacuationCenter}/occupancy', [AdminEvacuationCenterController::class, 'updateOccupancy'])->name('evacuation-centers.occupancy');

    // Evacuation Routes Management (verb-based routes)
    Route::get('/evacuation-routes', [AdminEvacuationRouteController::class, 'index'])->name('evacuation-routes.index');
    Route::get('/evacuation-routes/create', [AdminEvacuationRouteController::class, 'create'])->name('evacuation-routes.create');
    Route::post('/evacuation-routes', [AdminEvacuationRouteController::class, 'store'])->name('evacuation-routes.store');
    Route::get('/evacuation-routes/{evacuationRoute}', [AdminEvacuationRouteController::class, 'show'])->name('evacuation-routes.show');
    Route::get('/evacuation-routes/{evacuationRoute}/edit', [AdminEvacuationRouteController::class, 'edit'])->name('evacuation-routes.edit');
    Route::put('/evacuation-routes/{evacuationRoute}', [AdminEvacuationRouteController::class, 'update'])->name('evacuation-routes.update');
    Route::delete('/evacuation-routes/{evacuationRoute}', [AdminEvacuationRouteController::class, 'destroy'])->name('evacuation-routes.destroy');
    Route::patch('/evacuation-routes/{evacuationRoute}/toggle', [AdminEvacuationRouteController::class, 'toggleActive'])->name('evacuation-routes.toggle');
});


require __DIR__.'/auth.php';
