<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExternalPlantController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\StatutController;
use App\Http\Controllers\StrainController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Logout
Route::get('/logout', function () {
    return view('auth.login');
})->name('logout');

// Auth
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->middleware('throttle:10,1');
    Route::get('/login/oidc', 'oidcRedirect')->name('login.oidc');
    Route::get('/login/oidc/callback', 'oidcCallback')->name('login.oidc.callback');
    Route::post('/logout', 'logout');
});

// Healthcheck
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware(['auth'])->group(function () {
    // Upload
    Route::post('/upload/pictures', [UploadController::class, 'pictures'])->name('upload.pictures')->middleware('throttle:20,1');
    Route::get('/picture/{id}', [PictureController::class, 'src'])->name('picture.src');

    // Dashboard
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/today', [DashboardController::class, 'today'])->name('dashboard.today');
    Route::get('/chart/{type}', [DashboardController::class, 'getChart'])->name('chart');

    // Theme
    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');

    // Dashboard views
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');
    Route::get('/dashboard/{id}/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::get('/dashboard/{id}/detail', [DashboardController::class, 'edit'])->name('dashboard.detail');

    // Dashboard actions
    Route::post('/switch', [DashboardController::class, 'switch'])->name('switch');
    Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/{id}', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::delete('/dashboard/{id}/destroy', [DashboardController::class, 'destroy'])->name('dashboard.destroy');
    Route::post('/dashboard/{id}/default', [DashboardController::class, 'default'])->name('dashboard.default');

    Route::middleware(['admin'])->group(function () {
        // User views
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::get('/user/{id}/detail', [UserController::class, 'detail'])->name('user.detail');

        // User actions
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');

        // Preference views
        Route::get('/preference', [PreferenceController::class, 'index'])->name('preference.index');
        Route::get('/preference/create', [PreferenceController::class, 'create'])->name('preference.create');
        Route::get('/preference/{id}/edit', [PreferenceController::class, 'edit'])->name('preference.edit');

        // Preference actions
        Route::post('/preference', [PreferenceController::class, 'store'])->name('preference.store');
        Route::post('/preference/{id}', [PreferenceController::class, 'update'])->name('preference.update');
        Route::delete('/preference/{id}/destroy', [PreferenceController::class, 'destroy'])->name('preference.destroy');

        // Checklist items (for item form)
        Route::post('/checklist/{id}/items', [ChecklistController::class, 'getItems'])->name('checklist.get.items');

        // Stage views
        Route::get('/stage', [StageController::class, 'index'])->name('stage.index');
        Route::get('/stage/create', [StageController::class, 'create'])->name('stage.create');
        Route::get('/stage/{id}/edit', [StageController::class, 'edit'])->name('stage.edit');

        // Stage actions
        Route::post('/stage', [StageController::class, 'store'])->name('stage.store');
        Route::post('/stage/{id}', [StageController::class, 'update'])->name('stage.update');
        Route::delete('/stage/{id}/destroy', [StageController::class, 'destroy'])->name('stage.destroy');

        // Item views
        Route::get('/item', [ItemController::class, 'index'])->name('item.index');
        Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');
        Route::get('/item/{id}/edit', [ItemController::class, 'edit'])->name('item.edit');

        // Item actions
        Route::post('/item', [ItemController::class, 'store'])->name('item.store');
        Route::post('/item/{id}', [ItemController::class, 'update'])->name('item.update');
        Route::delete('/item/{id}/destroy', [ItemController::class, 'destroy'])->name('item.destroy');

        // Statut views
        Route::get('/statut', [StatutController::class, 'index'])->name('statut.index');
        Route::get('/statut/create', [StatutController::class, 'create'])->name('statut.create');
        Route::get('/statut/{id}/edit', [StatutController::class, 'edit'])->name('statut.edit');

        // Statut actions
        Route::post('/statut', [StatutController::class, 'store'])->name('statut.store');
        Route::post('/statut/{id}', [StatutController::class, 'update'])->name('statut.update');
        Route::delete('/statut/{id}/destroy', [StatutController::class, 'destroy'])->name('statut.destroy');

        // Tag views
        Route::get('/tag', [TagController::class, 'index'])->name('tag.index');
        Route::get('/tag/create', [TagController::class, 'create'])->name('tag.create');
        Route::get('/tag/{id}/edit', [TagController::class, 'edit'])->name('tag.edit');

        // Tag actions
        Route::post('/tag', [TagController::class, 'store'])->name('tag.store');
        Route::post('/tag/{id}', [TagController::class, 'update'])->name('tag.update');
        Route::delete('/tag/{id}/destroy', [TagController::class, 'destroy'])->name('tag.destroy');

        // Property views
        Route::get('/property', [PropertyController::class, 'index'])->name('property.index');
        Route::get('/property/create', [PropertyController::class, 'create'])->name('property.create');
        Route::get('/property/{id}/edit', [PropertyController::class, 'edit'])->name('property.edit');

        // Property actions
        Route::post('/property', [PropertyController::class, 'store'])->name('property.store');
        Route::post('/property/{id}', [PropertyController::class, 'update'])->name('property.update');
        Route::delete('/property/{id}/destroy', [PropertyController::class, 'destroy'])->name('property.destroy');

        // Notification views
        Route::get('/notification', [NotificationController::class, 'index'])->name('notification.index');
        Route::get('/notification/create', [NotificationController::class, 'create'])->name('notification.create');
        Route::get('/notification/{id}/edit', [NotificationController::class, 'edit'])->name('notification.edit');

        // Notification actions
        Route::post('/notification', [NotificationController::class, 'store'])->name('notification.store');
        Route::post('/notification/{id}', [NotificationController::class, 'update'])->name('notification.update');
        Route::delete('/notification/{id}/destroy', [NotificationController::class, 'destroy'])->name('notification.destroy');
    });

    // Strain views
    Route::get('/strain', [StrainController::class, 'index'])->name('strain.index');
    Route::get('/strain/create', [StrainController::class, 'create'])->name('strain.create');
    Route::get('/strain/{id}/edit', [StrainController::class, 'edit'])->name('strain.edit');
    Route::get('/strain/{id}/pictures', [StrainController::class, 'pictures'])->name('strain.pictures');

    // Strain actions
    Route::post('/strain', [StrainController::class, 'store'])->name('strain.store');
    Route::post('/strain/{id}', [StrainController::class, 'update'])->name('strain.update');
    Route::delete('/strain/{id}/destroy', [StrainController::class, 'destroy'])->name('strain.destroy');
    Route::post('/strain/{id}/add/{objectType}/{objectId}', [StrainController::class, 'addToStrain'])->name('strain.add');
    Route::post('/strain/{id}/remove/{objectType}/{objectId}', [StrainController::class, 'removeFromStrain'])->name('strain.remove');

    // Plant views
    Route::get('/plant', [PlantController::class, 'index'])->name('plant.index');
    Route::get('/plant/create', [PlantController::class, 'create'])->name('plant.create');
    Route::get('/plant/{id}/edit', [PlantController::class, 'edit'])->name('plant.edit');
    Route::get('/plant/{id}/detail', [PlantController::class, 'detail'])->name('plant.detail');
    Route::get('/plant/{id}/stages', [PlantController::class, 'stages'])->name('plant.stages');
    Route::get('/plant/{id}/pictures', [PlantController::class, 'pictures'])->name('plant.pictures');
    Route::get('/plant/{id}/compare', [PlantController::class, 'compare'])->name('plant.compare');

    // Plant actions
    Route::post('/plant', [PlantController::class, 'store'])->name('plant.store');
    Route::post('/plant/{id}', [PlantController::class, 'update'])->name('plant.update');
    Route::delete('/plant/{id}/destroy', [PlantController::class, 'destroy'])->name('plant.destroy');
    Route::post('/plant/{id}/add/{objectType}/{objectId}', [PlantController::class, 'addToPlant'])->name('plant.add');
    Route::post('/plant/{id}/remove/{objectType}/{objectId}', [PlantController::class, 'removeFromPlant'])->name('plant.remove');
    Route::post('/plant/{id}/item/due/save', [PlantController::class, 'itemDueSave'])->name('item.due.save');
    Route::post('/plant/{id}/item/due/remove', [PlantController::class, 'itemDueRemove'])->name('item.due.remove');
    Route::post('/plant/{id}/item/toggle', [PlantController::class, 'itemToggle'])->name('item.toggle');
    Route::post('/plant/{id}/water', [PlantController::class, 'water'])->name('water');
    Route::post('/plant/{id}/water/chem', [PlantController::class, 'waterChemical'])->name('water.chem');
    Route::post('/plant/{id}/comment/new', [PlantController::class, 'commentAdd'])->name('comment.new');
    Route::post('/plant/{id}/comment/edit', [PlantController::class, 'commentEdit'])->name('comment.edit');
    Route::post('/plant/{id}/comment/remove', [PlantController::class, 'commentRemove'])->name('comment.remove');

    // API Rest
    Route::get('/api/notifications', [ApiController::class, 'notifications']);
    Route::get('/api/external/plants', [ExternalPlantController::class, 'search'])->name('api.external.plants');
});
