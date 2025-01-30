<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ThemeManagementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriberDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SubscriberThemeController;

Route::get('/', function () {
    return view('welcome');
});

// Public routes
Route::get('/Auth', [AuthController::class, 'index'])->name('Auth');
Auth::routes();

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('public.home');
    })->name('hometest');

    // Theme routes
    Route::get('/mes_themes', [ThemeController::class, 'myThemes'])->name('mes_themes');
    Route::get('/themes', [ThemeController::class, 'allThemes'])->name('themes');
    Route::post('/themes/{theme}/subscribe', [ThemeController::class, 'subscribe'])->name('themes.subscribe');
    Route::post('/themes/{theme}/unsubscribe', [ThemeController::class, 'unsubscribe'])->name('themes.unsubscribe');
    
    // Article routes
    Route::get('/themes/{theme}/articles', [ArticleController::class, 'themeArticles'])->name('articles.theme')->middleware('auth');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show')->middleware('auth');
    Route::post('/articles/{article}/rate', [ArticleController::class, 'rate'])->name('articles.rate')->middleware('auth');
    Route::post('/articles/{article}/comment', [ArticleController::class, 'comment'])->name('articles.comment')->middleware('auth');
    
    // Comment moderation routes
    Route::post('/comments/{comment}/toggle-visibility', [CommentController::class, 'toggleVisibility'])
        ->name('comments.toggle-visibility')
        ->middleware('auth');
    Route::delete('/comments/{comment}', [CommentController::class, 'delete'])
        ->name('comments.delete')
        ->middleware('auth');
    Route::post('/comments/{comment}/report', [CommentController::class, 'report'])
        ->name('comments.report')
        ->middleware('auth');
    Route::post('/comments/{comment}/unreport', [CommentController::class, 'unreport'])
        ->name('comments.unreport')
        ->middleware('auth');
    
    // Theme Management routes
    Route::get('/themes/{theme}/manage', [ThemeManagementController::class, 'manageTheme'])->name('themes.manage');
    Route::post('/articles/{article}/status', [ThemeManagementController::class, 'updateArticleStatus'])->name('articles.status');
    Route::delete('/articles/{article}', [ThemeManagementController::class, 'deleteArticle'])->name('articles.delete');
    Route::delete('/themes/{theme}/subscribers/{subscriber}', [ThemeManagementController::class, 'removeSubscriber'])->name('themes.remove_subscriber');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/magasines', function () {
        return view('public.magasines');
    })->name('magasines');

    // Subscriber Dashboard Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/subscriber/dashboard', [SubscriberDashboardController::class, 'index'])->name('subscriber.dashboard');
        Route::post('/subscriber/propose-article', [SubscriberDashboardController::class, 'proposeArticle'])->name('subscriber.propose-article');
        Route::delete('/subscriber/comments/{comment}', [SubscriberDashboardController::class, 'deleteComment'])->name('subscriber.delete-comment');

        // Subscriber Theme Proposal Routes
        Route::get('/subscriber/themes/propose', [SubscriberThemeController::class, 'create'])->name('subscriber.themes.create');
        Route::post('/subscriber/themes/propose', [SubscriberThemeController::class, 'propose'])->name('subscriber.themes.propose');
    });
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Remove the old dash_board route since we have a new admin dashboard
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Theme Management
    Route::get('/admin/themes', [AdminDashboardController::class, 'themes'])->name('admin.themes');
    Route::post('/admin/themes', [AdminDashboardController::class, 'store'])->name('admin.themes.store');
    Route::put('/admin/themes/{theme}', [AdminDashboardController::class, 'update'])->name('admin.themes.update');
    Route::delete('/admin/themes/{theme}', [AdminDashboardController::class, 'destroy'])->name('admin.themes.delete');
    Route::get('/admin/themes/{theme}', [AdminDashboardController::class, 'show'])->name('admin.themes.details');
    
    // User Management
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.delete');
    
    // Article Management
    Route::get('/admin/articles', [AdminDashboardController::class, 'articles'])->name('admin.articles');
    Route::post('/admin/articles/{article}/toggle', [AdminDashboardController::class, 'toggleArticle'])->name('admin.articles.toggle');
    
    // Numero Management
    Route::get('/admin/numeros', [AdminDashboardController::class, 'numeros'])->name('admin.numeros');
    Route::post('/admin/numeros/{numero}/toggle', [AdminDashboardController::class, 'toggleNumero'])->name('admin.numeros.toggle');
    
    // Theme Management
    Route::get('/admin/themes', [AdminDashboardController::class, 'themes'])->name('admin.themes');
    Route::put('/admin/themes/{theme}/toggle', [AdminDashboardController::class, 'toggleTheme'])->name('admin.themes.toggle');
});
