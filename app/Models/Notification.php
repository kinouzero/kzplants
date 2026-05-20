<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'name',
        'description',
        'configuration',
    ];

    /**
     * Users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users', 'notification_id', 'user_id')->withPivot('creator', 'active');
    }

    /**
     * Config
     */
    public function config()
    {
        return json_decode($this->configuration ?? '[]', true) ?: [];
    }

    /**
     * Creator
     */
    public function creator()
    {
        return $this->users()->wherePivot('creator', true)->first();
    }
}
