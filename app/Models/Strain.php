<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Strain extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'strains';

    protected $fillable = [
        'name',
        'external_source',
        'external_id',
    ];

    /**
     * Pictures
     */
    public function pictures()
    {
        return $this->belongsToMany(Picture::class, 'strain_pictures', 'strain_id', 'picture_id')->withPivot('default');
    }

    /**
     * Default picture
     */
    public function defaultPicture()
    {
        return $this->pictures()->wherePivot('default', true)->first();
    }

    /**
     * Properties
     */
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'strain_properties', 'strain_id', 'property_id')->withPivot('value');
    }

    /**
     * Tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'strain_tags', 'strain_id', 'tag_id');
    }

    /**
     * Plants
     */
    public function plants()
    {
        return $this->hasMany(Plant::class, 'strain_id');
    }
}
