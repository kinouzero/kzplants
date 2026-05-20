<?php

namespace App\Listeners;

use App\Events\PlantActionOccurred;
use App\Models\PlantHistory;

class LogPlantHistory
{
    public function handle(PlantActionOccurred $event): void
    {
        if (! $event->user) {
            return;
        }

        PlantHistory::record($event->plant, $event->user, $event->payload);
    }
}
