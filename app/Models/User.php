<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
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
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  /**
   * Roles
   */
  public function roles()
  {
    return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
  }

  /**
   * Preferences
   */
  public function preferences()
  {
    return $this->belongsToMany(Preference::class, 'user_preferences', 'user_id', 'preference_id')->withPivot('value');
  }

  /**
   * Check if the user is an admin
   */
  public function isAdmin()
  {
    return $this->roles()->where('name', 'admin')->exists();
  }

  /**
   * Get user timezone
   */
  public static function getTimezone($user)
  {
    $preference = Preference::where('name', 'ilike', 'timezone')->first();
    return ($userPref = $user->preferences()->where('id', $preference->id)->first()) ? $userPref->pivot->value : config('app.timezone');
  }

  /**
   * Get user language
   */
  public static function getLanguage($user)
  {
    $preference = Preference::where('name', 'ilike', 'language')->first();
    return ($userPref = $user->preferences()->where('id', $preference->id)->first()) ? $userPref->pivot->value : config('app.locale');
  }

  /**
   * Get user page length
   */
  public static function getTableLength($user)
  {
    $preference = Preference::where('name', 'ilike', 'table%length')->first();
    return ($userPref = $user->preferences()->where('id', $preference->id)->first()) ? $userPref->pivot->value : 25;
  }
}
