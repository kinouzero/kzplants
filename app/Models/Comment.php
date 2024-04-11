<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

  protected $table = 'plant_comments';

  protected $fillable = [
    'plant_id',
    'value',
    'author_id'
  ];

  /**
   * Plant
   */
  public function plant() {
    return $this->belongsTo(Plant::class, 'plant_id');
  }

  /**
   * Author
   */
  public function author() {
    return $this->belongsTo(User::class, 'author_id');
  }

  /**
   * Template timeline
   */
  public function templateTimeline($content = null, $info = null) {
    return view('template.timeline.item', ['info' => $info, 'content' => $content, 'class' => null]);
  }
}
