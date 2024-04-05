<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model {

  protected $table = 'preferences';

  protected $fillable = [
    'key',
    'name',
    'type',
    'description',
    'options'
  ];

  public function options() {
    return json_decode($this->options, true);
  }
}
