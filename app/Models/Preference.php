<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
  use HasFactory;

  protected $table = 'preferences';

  protected $fillable = [
    'name',
    'checklist_id'
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25)
  {
    $query = Property::query();

    // Where
    $query->where('preferences.name', 'ilike', '%' . $search . '%');

    // Order
    $query->orderBy($order['by'] ?: 'preferences.name', $order['dir'] ?: 'desc');

    // Limit
    $query->limit($limit);

    return $query->get();
  }
}
