<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'name',
        'color'
    ];

    /**
     * Search
     */
    public function search($search, $order = [], $limit = 25)
    {
        $query = Tag::query();

        // Where
        $query->where('tags.name', 'ilike', '%' . $search . '%');

        // Order
        $query->orderBy($order['by'] ?: 'tags.name', $order['dir'] ?: 'desc');

        // Limit
        $query->limit($limit);

        return $query->get();
    }
}
