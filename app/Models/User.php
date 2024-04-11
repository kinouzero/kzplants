<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable {
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  /**
   * Roles
   */
  public function roles() {
    return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
  }

  /**
   * Preferences
   */
  public function preferences() {
    return $this->belongsToMany(Preference::class, 'user_preferences', 'user_id', 'preference_id')->withPivot('value');
  }

  /**
   * Dashboards
   */
  public function dashboards() {
    return $this->belongsToMany(Dashboard::class, 'dashboard_users', 'user_id', 'dashboard_id')->withPivot('default');
  }

  /**
   * Notifications
   */
  public function notifications() {
    return $this->belongsToMany(Notification::class, 'notification_users', 'user_id', 'notification_id')->withPivot('creator', 'active');
  }

  /**
   * Check if the user is an admin
   */
  public function isAdmin() {
    return $this->roles()->where('name', 'admin')->exists();
  }

  /**
   * Get user timezone
   */
  public static function getUserTimezone($user) {
    $preference = Preference::where('key', 'timezone')->first();
    return ($preference && $user && $userPref = $user->preferences()->where('id', $preference->id)->first()) ? $userPref->pivot->value : config('app.timezone');
  }

  /**
   * Get user language
   */
  public static function getUserLanguage($user) {
    $preference = Preference::where('key', 'language')->first();
    return ($preference && $user && $userPref = $user->preferences()->where('id', $preference->id)->first()) ? $userPref->pivot->value : config('app.locale');
  }

  /**
   * Get user page length
   */
  public static function getUserTableLength($user) {
    $preference = Preference::where('key', 'table-length')->first();
    return ($preference && $user && $userPref = $user->preferences()->where('id', $preference->id)->first()) ? $userPref->pivot->value : 25;
  }

  /**
   * Get user theme
   */
  public static function getUserTheme($user) {
    $preference = Preference::where('key', 'theme')->first();
    return ($preference && $user && $userPref = $user->preferences()->where('id', $preference->id)->first()) ? $userPref->pivot->value : env('APP_THEME', 'light');
  }

  public static function getTheme() {
    return session('theme') ?: self::getUserTheme(auth()->user());
  }
}
