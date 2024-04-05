<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Provider\Node\StaticNodeProvider;

class Dashboard extends Model {

  protected $table = 'dashboards';

  protected $fillable = [
    'name',
    'description',
    'color'
  ];

  /**
   * Plants
   */
  public function plants() {
    return $this->belongsToMany(Plant::class, 'dashboard_plants', 'dashboard_id', 'plant_id');
  }

  /**
   * Users
   */
  public function users() {
    return $this->belongsToMany(User::class, 'dashboard_users', 'dashboard_id', 'user_id')->withPivot('default', 'creator');
  }

  /**
   * Creator
   */
  public function creator() {
    return $this->users()->wherePivot('creator', true)->first();
  }

  /**
   * Default
   */
  public static function getDefault($user_id) {
    return self::find(DB::table('dashboard_users')->select('dashboard_id')->where('user_id', $user_id)->where('default', true)->first()->dashboard_id);
  }

  /**
   * Current dashboard
   */
  public static function getCurrentDashboard() {
    if (!session('dashboard_id')) session()->put('dashboard_id', self::getDefault(auth()->user()->id)->id);
    return session('dashboard_id') ? Dashboard::findOrFail(session('dashboard_id')) : null;
  }

  public static function templateSwitch() {
    $options = [];
    foreach (auth()->user()->dashboards as $board) $options[] = view('template.form.select.option', ['value' => $board->id, 'title' => $board->name, 'selected' => $board->id === session('dashboard_id')]);

    return view('template.form.floating', [
      'type' => 'select',
      'class' => ['input' => 'select2'],
      'id' => 'dashboard',
      'name' => 'dashboard',
      'extra' => ['input' => 'required autofocus'],
      'options' => implode('', $options),
      'label' => 'Dashboard',
      'placeholder' => 'Switch dashboard'
    ]);
  }
}
