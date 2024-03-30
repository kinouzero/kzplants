<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\StatutController;
use App\Http\Controllers\StrainController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Models\Preference;

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Login
Route::get('/logout', function () {
    return view('auth.login');
})->name('logout');

// Auth
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/status/chart', [DashboardController::class, 'getStatusChart'])->name('status.chart');
    Route::get('/water/chart', [DashboardController::class, 'getWateringsChart'])->name('water.chart');

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

    // Checklist views
    Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist.index');
    Route::get('/checklist/create', [ChecklistController::class, 'create'])->name('checklist.create');
    Route::get('/checklist/{id}/edit', [ChecklistController::class, 'edit'])->name('checklist.edit');
    Route::get('/checklist/{id}/detail', [ChecklistController::class, 'edit'])->name('checklist.detail');

    // Checklist actions
    Route::post('/checklist', [ChecklistController::class, 'store'])->name('checklist.store');
    Route::post('/checklist/{id}', [ChecklistController::class, 'update'])->name('checklist.update');
    Route::delete('/checklist/{id}/destroy', [ChecklistController::class, 'destroy'])->name('checklist.destroy');
    Route::post('/checklist/{id}/items', [ChecklistController::class, 'getItems'])->name('checklist.get.items');

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

    // Strain views
    Route::get('/strain', [StrainController::class, 'index'])->name('strain.index');
    Route::get('/strain/create', [StrainController::class, 'create'])->name('strain.create');
    Route::get('/strain/{id}/edit', [StrainController::class, 'edit'])->name('strain.edit');
    Route::get('/strain/{id}/detail', [StrainController::class, 'detail'])->name('strain.detail');

    // Strain actions
    Route::post('/strain', [StrainController::class, 'store'])->name('strain.store');
    Route::post('/strain/{id}', [StrainController::class, 'update'])->name('strain.update');
    Route::delete('/strain/{id}/destroy', [StrainController::class, 'destroy'])->name('strain.destroy');

    // Plant views
    Route::get('/plant', [PlantController::class, 'index'])->name('plant.index');
    Route::get('/plant/create', [PlantController::class, 'create'])->name('plant.create');
    Route::get('/plant/{id}/edit', [PlantController::class, 'edit'])->name('plant.edit');
    Route::get('/plant/{id}/detail', [PlantController::class, 'detail'])->name('plant.detail');
    Route::get('/plant/{id}/add/list', [PlantController::class, 'addChecklist'])->name('plant.add.list');

    // Plant actions
    Route::post('/plant', [PlantController::class, 'store'])->name('plant.store');
    Route::post('/plant/{id}', [PlantController::class, 'update'])->name('plant.update');
    Route::delete('/plant/{id}/destroy', [PlantController::class, 'destroy'])->name('plant.destroy');
    Route::post('/plant/{id}/add/{objectType}/{objectId}', [PlantController::class, 'addToPlant'])->name('plant.add');
    Route::post('/plant/{id}/remove/{objectType}/{objectId}', [PlantController::class, 'removeFromPlant'])->name('plant.remove');
    Route::post('/plant/{id}/item/save/due', [PlantController::class, 'saveDue'])->name('item.save.due');
    Route::post('/plant/{id}/item/remove/due', [PlantController::class, 'removeDue'])->name('item.remove.due');
    Route::post('/plant/{id}/item/toggle/checked', [PlantController::class, 'toggleChecked'])->name('item.toggle.checked');
    Route::post('/plant/{id}/water/wo/chem', [PlantController::class, 'waterWithoutChemical'])->name('water.wo.chem');
    Route::post('/plant/{id}/water/w/chem', [PlantController::class, 'waterWithChemical'])->name('water.w.chem');
});
