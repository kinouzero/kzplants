<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model {
  use HasFactory;

  protected $table = 'properties';

  protected $fillable = [
    'name'
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25) {
    $query = Property::query();

    // Where
    $query->where('properties.name', 'ilike', '%' . $search . '%');

    // Order
    $query->orderBy($order['by'] ?: 'properties.name', $order['dir'] ?: 'desc');

    // Limit
    $query->limit($limit);

    return $query->get();
  }
}
