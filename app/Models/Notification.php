<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

  protected $table = 'notifications';

  protected $fillable = [
    'name',
    'description',
    'configuration'
  ];

  /**
   * Users
   */
  public function users() {
    return $this->belongsToMany(User::class, 'notification_users', 'user_id', 'notification_id')->withPivot('creator', 'active');
  }

  /**
   * Config
   */
  public function config() {
    return json_decode($this->configuration);
  }

  /**
   * Creator
   */
  public function creator() {
    return $this->users()->wherePivot('creator', true)->first();
  }
}
