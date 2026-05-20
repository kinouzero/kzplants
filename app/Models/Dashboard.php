<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dashboard extends Model
{
    use HasFactory;

    protected $table = 'dashboards';

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    /**
     * Plants
     */
    public function plants()
    {
        return $this->belongsToMany(Plant::class, 'dashboard_plants', 'dashboard_id', 'plant_id');
    }

    /**
     * Users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'dashboard_users', 'dashboard_id', 'user_id')->withPivot('default', 'creator');
    }

    /**
     * Creator
     */
    public function creator()
    {
        return $this->users()->wherePivot('creator', true)->first();
    }

    /**
     * Default
     */
    public static function getDefault($user_id)
    {
        $default = DB::table('dashboard_users')->select('dashboard_id')->where('user_id', $user_id)->where('default', true)->first();
        if ($default) {
            return self::find($default->dashboard_id);
        }

        $fallback = DB::table('dashboard_users')->select('dashboard_id')->where('user_id', $user_id)->first();

        return $fallback ? self::find($fallback->dashboard_id) : null;
    }

    /**
     * Current dashboard
     */
    public static function getCurrentDashboard()
    {
        if (! session('dashboard_id') && ($default = self::getDefault(auth()->user()->id))) {
            session()->put('dashboard_id', $default->id);
        }

        return session('dashboard_id') ? Dashboard::findOrFail(session('dashboard_id')) : null;
    }
}
