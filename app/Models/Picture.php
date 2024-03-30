<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model {
  use HasFactory;

  protected $table = 'pictures';
  protected $fillable = [
    'name',
    'path'
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25) {
    $query = Picture::query();

    // Where
    $query->where('pictures.name', 'ilike', '%' . $search . '%');

    // Order
    $query->orderBy($order['by'] ?: 'pictures.name', $order['dir'] ?: 'desc');

    // Limit
    $query->limit($limit);

    return $query->get();
  }
}
