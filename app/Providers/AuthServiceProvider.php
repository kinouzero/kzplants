<?php

namespace App\Providers;

use App\Models\Checklist;
use App\Models\Dashboard;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Plant;
use App\Models\Preference;
use App\Models\Property;
use App\Models\Stage;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\Tag;
use App\Models\User;
use App\Policies\ChecklistPolicy;
use App\Policies\DashboardPolicy;
use App\Policies\ItemPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PlantPolicy;
use App\Policies\PreferencePolicy;
use App\Policies\PropertyPolicy;
use App\Policies\StagePolicy;
use App\Policies\StatutPolicy;
use App\Policies\StrainPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Plant::class => PlantPolicy::class,
        Dashboard::class => DashboardPolicy::class,
        Strain::class => StrainPolicy::class,
        Tag::class => TagPolicy::class,
        Property::class => PropertyPolicy::class,
        Checklist::class => ChecklistPolicy::class,
        Stage::class => StagePolicy::class,
        Item::class => ItemPolicy::class,
        Statut::class => StatutPolicy::class,
        Notification::class => NotificationPolicy::class,
        Preference::class => PreferencePolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
