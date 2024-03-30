<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  use HasFactory;

  protected $table = "roles";

  protected $fillable = [
    'name'
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25) {
    $query = Property::query();

    // Where
    $query->where('roles.name', 'ilike', '%' . $search . '%');

    // Order
    $query->orderBy($order['by'] ?: 'roles.name', $order['dir'] ?: 'desc');

    // Limit
    $query->limit($limit);

    return $query->get();
  }
}
