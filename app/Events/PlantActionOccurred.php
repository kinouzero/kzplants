<?php

namespace App\Events;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlantActionOccurred
{
    use Dispatchable;
    use SerializesModels;

    public Plant $plant;

    public ?User $user;

    public array $payload;

    public function __construct(Plant $plant, ?User $user, array $payload)
    {
        $this->plant = $plant;
        $this->user = $user;
        $this->payload = $payload;
    }
}
