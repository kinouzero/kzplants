<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strain extends Model {

  protected $table = 'strains';

  protected $fillable = [
    'name'
  ];

  /**
   * Pictures
   */
  public function pictures() {
    return $this->belongsToMany(Picture::class, 'strain_pictures', 'strain_id', 'picture_id')->withPivot('default');
  }

  /**
   * Default picture
   */
  public function defaultPicture() {
    return $this->pictures()->wherePivot('default', true)->first();
  }

  /**
   * Properties
   */
  public function properties() {
    return $this->belongsToMany(Property::class, 'strain_properties', 'strain_id', 'property_id')->withPivot('value');
  }

  /**
   * Tags
   */
  public function tags() {
    return $this->belongsToMany(Tag::class, 'strain_tags', 'strain_id', 'tag_id');
  }

  /**
   * Plants
   */
  public function plants() {
    return $this->belongsToMany(Plant::class, 'plants', 'strain_id', 'id');
  }

  /*************
   * Templates *
   *************/

  /**
   * Template tags
   */
  public function templateTags() {
    return view('template.strain.tags', ['strain' => $this]);
  }

  /**
   * Template properties
   */
  public function templateProperties() {
    return view('template.strain.properties', ['strain' => $this]);
  }

  /**
   * Template tags styling attribute
   */
  public static function tagsStyle($strains) {
    $tags = [];
    if (!$strains->isEmpty()) foreach ($strains as $strain) foreach ($strain->tags as $tag) $tags[$tag->id] = sprintf('.tag-%s { background-color:%s }', $tag->id, $tag->color);

    return implode(' ', $tags);
  }

  /**
   * Template properties styling attribute
   */
  public static function propertiesStyle($strains) {
    $properties = [];
    if (!$strains->isEmpty()) foreach ($strains as $strain) foreach ($strain->properties as $property) $properties[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);

    return implode(' ', $properties);
  }
}
