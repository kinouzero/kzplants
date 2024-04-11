<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model {

  protected $table = 'pictures';

  protected $fillable = [
    'name',
    'content'
  ];

  public function formatContent($path) {
    return sprintf('0x%s', bin2hex(file_get_contents($path)));
  }

  public function getContent() {
    return hex2bin(substr(stream_get_contents($this->content), 2));
  }

  public function ext() {
    return pathinfo($this->name, PATHINFO_EXTENSION);
  }

  public static function upload($file) {
    $name = $file->getClientOriginalName();
    $file->move(public_path('uploads'), $name);

    $picture          = new Picture();
    $picture->name    = $name;
    $picture->content = $picture->formatContent(public_path(sprintf('uploads/%s', $picture->name)));
    $picture->save();

    unlink(public_path(sprintf('uploads/%s', $name)));

    return $picture;
  }

  /**
   * Template timeline
   */
  public function templateTimeline($content = null, $info = null) {
    return view('template.timeline.item', ['info' => $info, 'content' => $content, 'class' => null]);
  }
}
