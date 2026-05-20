<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Checklist extends Model
{
    protected $table = 'checklists';

    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * Parents
     */
    public function parents()
    {
        return $this->belongsToMany(Checklist::class, 'checklist_parents', 'checklist_id', 'parent_id');
    }

    /**
     * Children
     */
    public function children()
    {
        return $this->belongsToMany(Checklist::class, 'checklist_parents', 'parent_id', 'checklist_id');
    }

    public function tree()
    {

        $tree = [];

        $this->children()->each(function ($child) use (&$tree) {
            $tree[] = $child;
            $tree = array_merge($tree, $child->tree());
        });

        return $tree;
    }

    /**
     * Items
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'checklist_id', 'id');
    }

    public function stage()
    {
        return $this->hasOne(Stage::class, 'checklist_id');
    }

    /**
     * First item
     */
    public function firstItem()
    {
        return $this->items()->where('parent_id', null)->first();
    }

    /**
     * Checklist is started
     */
    public function isStarted($plant)
    {
        return DB::table('plant_items')->select()->where('plant_id', $plant->id)->whereIn('item_id', $this->items()->pluck('id'))->whereNotNull('checked')->count() > 0;
    }

    /**
     * Checklist is completed
     */
    public function isCompleted($plant)
    {
        return DB::table('plant_items')->select()->where('plant_id', $plant->id)->whereIn('item_id', $this->items()->pluck('id'))->whereNotNull('checked')->count() === $this->items()->count();
    }
}
