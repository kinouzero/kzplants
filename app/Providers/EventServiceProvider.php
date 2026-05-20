<?php

namespace App\Providers;

use App\Events\PlantActionOccurred;
use App\Listeners\LogPlantHistory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PlantActionOccurred::class => [
            LogPlantHistory::class,
        ],
    ];
}
