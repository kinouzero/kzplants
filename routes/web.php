<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\StatutController;
use App\Http\Controllers\StrainController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;

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
  Route::post('/login', 'login');
  Route::post('/logout', 'logout');
});

// Forgot view
Route::get('forgot', [ForgotController::class, 'view'])->name('forgot');
// Send email
Route::post('forgot', [ForgotController::class, 'send'])->name('forgot.email');

// Reset view
Route::get('reset/{token}', [ResetController::class, 'view'])->name('password.reset');
// Reset action
Route::post('reset', [ResetController::class, 'store'])->name('password.store');

// Register view
Route::get('register', [RegisterController::class, 'view'])->name('register');
// Register action
Route::post('register', [RegisterController::class, 'store'])->name('register.store');

Route::middleware(['auth'])->group(function () {
  // Upload
  Route::post('/upload/pictures', [UploadController::class, 'pictures'])->name('upload.pictures');
  Route::get('/picture/{id}', [PictureController::class, 'src'])->name('picture.src');

  // Dashboard
  Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
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
  Route::get('/plant/{id}/checklists', [PlantController::class, 'checklists'])->name('plant.checklists');
  Route::get('/plant/{id}/pictures', [PlantController::class, 'pictures'])->name('plant.pictures');

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

  // Notification views
  Route::get('/notification', [NotificationController::class, 'index'])->name('notification.index');
  Route::get('/notification/create', [NotificationController::class, 'create'])->name('notification.create');
  Route::get('/notification/{id}/edit', [NotificationController::class, 'edit'])->name('notification.edit');

  // Notification actions
  Route::post('/notification', [NotificationController::class, 'store'])->name('notification.store');
  Route::post('/notification/{id}', [NotificationController::class, 'update'])->name('notification.update');
  Route::delete('/notification/{id}/destroy', [NotificationController::class, 'destroy'])->name('notification.destroy');

  // API Rest
  Route::get('/api/notifications', [ApiController::class, 'notifications']);
});
