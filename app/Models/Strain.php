<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Strain extends Model
{
  use HasFactory;

  protected $table = 'strains';

  protected $fillable = [
    'name'
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25)
  {
    $query = Strain::query();

    // Join
    $query->join('$tags', '$tags.id', '=', 'strains.tag_id');
    $query->join('strain_properties', 'strain_properties.strain_id', '=', 'strains.id');
    $query->join('properties', 'properties.id', '=', 'strain_properties.property_id');

    // Where
    $query->where('strains.name', 'ilike', '%' . $search . '%');
    $query->orWhere('$tags.name', 'ilike', '%' . $search . '%');
    $query->orWhere('strain_properties.value', 'ilike', '%' . $search . '%');
    $query->orWhere('properties.name', 'ilike', '%' . $search . '%');

    // Order
    $query->orderBy($order['by'] ?: 'strains.name', $order['dir'] ?: 'desc');

    // Limit
    $query->limit($limit);

    return $query->get();
  }

  /**
   * Pictures
   */
  public function pictures()
  {
    return $this->belongsToMany(Picture::class, 'strain_pictures', 'strain_id', 'picture_id');
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
    return $this->belongsToMany(Plant::class, 'plants', 'strain_id', 'id');
  }

  /*************
   * Templates *
   *************/

  /**
   * Template tags
   */
  public function templateTags()
  {
    return view('layouts.strain.tags', ['strain' => $this]);
  }

  /**
   * Template properties
   */
  public function templateProperties()
  {
    return view('layouts.strain.properties', ['strain' => $this]);
  }

  /**
   * Template tags styling attribute
   */
  public static function tagsStyle($strains)
  {
    $tags = [];
    if (!$strains->isEmpty()) foreach ($strains as $strain) foreach ($strain->tags as $tag) $tags[$tag->id] = sprintf('.tag-%s { background-color:%s }', $tag->id, $tag->color);

    return implode(' ', $tags);
  }

  /**
   * Template properties styling attribute
   */
  public static function propertiesStyle($strains)
  {
    $properties = [];
    if (!$strains->isEmpty()) foreach ($strains as $strain) foreach ($strain->properties as $property) $properties[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);

    return implode(' ', $properties);
  }
}
