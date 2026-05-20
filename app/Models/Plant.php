<?php

namespace App\Models;

use App\Models\Pivots\PlantItem;
use App\Models\Pivots\PlantProperty;
use App\Models\Pivots\PlantStage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'plants';

    protected $fillable = [
        'name',
        'strain_id',
        'created_by',
    ];

    /**
     * Dashboards
     */
    public function dashboards()
    {
        return $this->belongsToMany(Dashboard::class, 'dashboard_plants', 'plant_id', 'dashboard_id');
    }

    /**
     * Strain
     */
    public function strain()
    {
        return $this->belongsTo(Strain::class, 'strain_id');
    }

    /**
     * Statut
     */
    public function statut()
    {
        return $this->belongsTo(Statut::class, 'statut_id');
    }

    /**
     * Pictures
     */
    public function pictures()
    {
        return $this->belongsToMany(Picture::class, 'plant_pictures', 'plant_id', 'picture_id')->withPivot('default');
    }

    /**
     * Default picture
     */
    public function defaultPicture()
    {
        return $this->pictures()->wherePivot('default', true)->first() ?: $this->strain->defaultPicture();
    }

    /**
     * Properties
     */
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'plant_properties', 'plant_id', 'property_id')->using(PlantProperty::class)->withPivot('value');
    }

    /**
     * Tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'plant_tags', 'plant_id', 'tag_id');
    }

    /**
     * Stages
     */
    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'plant_stages', 'plant_id', 'stage_id')
            ->using(PlantStage::class)
            ->withPivot('initial')
            ->orderBy('stages.order');
    }

    /**
     * Initial stage
     */
    public function firstStage()
    {
        return $this->stages()->wherePivot('initial', true)->first();
    }

    /**
     * Current stage
     */
    public function currentStage()
    {
        foreach ($this->stages as $stage) {
            if (! $stage->checklist || ! $stage->checklist->isCompleted($this)) {
                return $stage;
            }
        }

        return null;
    }

    /**
     * Current checklist
     */
    public function currentChecklist()
    {
        $stage = $this->currentStage();

        return $stage ? $stage->checklist : null;
    }

    /**
     * Last checklist
     */
    public function lastChecklist()
    {
        $lastStage = $this->stages()->orderBy('order', 'desc')->first();

        return $lastStage ? $lastStage->checklist : null;
    }

    /**
     * Checklist items
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'plant_items', 'plant_id', 'item_id')->using(PlantItem::class)->withPivot('due', 'checked', 'flush');
    }

    /**
     * Current item
     */
    public function currentItem()
    {
        if (! $this->currentChecklist()) {
            return null;
        }

        return ($current = $this->currentChecklist()
            ->items()
            ->whereIn('parent_id', $this->items()->wherePivotNotNull('checked')->get()->pluck('id'))
            ->get()
            ->last()) ? $this->items() && $this->items()->where('item_id', $current->id)->first() : $this->currentChecklist()->items()->first();
    }

    /**
     * Checked items
     */
    public function checkedItems()
    {
        return $this->items()->wherePivotNotNull('checked');
    }

    /**
     * Waterings
     */
    public function waterings()
    {
        return $this->hasMany(Watering::class, 'plant_id')->orderBy('created_at', 'desc');
    }

    /**
     * Last watering chemical
     */
    public function lastWateringChemical()
    {
        return $this->waterings->first() ? $this->waterings->first()->chemical : false;
    }

    public function nextWateringChemical()
    {
        $next = ! $this->lastWateringChemical();
        if (($current = $this->currentItem()) && $current->pivot && $current->pivot->flush) {
            $next = false;
        }

        return $next;
    }

    /**
     * Count water with chemical
     */
    public function waterWithChemical()
    {
        return $this->waterings()->where('chemical', true)->count();
    }

    /**
     * Count water without chemical
     */
    public function waterWithoutChemical()
    {
        return $this->waterings()->where('chemical', false)->count();
    }

    /**
     * Comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'plant_id');
    }

    /**
     * Preferences
     */
    public function preferences()
    {
        return $this->belongsToMany(Preference::class, 'plant_preferences', 'plant_id', 'preference_id')->withPivot('value');
    }

    /**
     * History
     */
    public function history()
    {
        return $this->hasMany(PlantHistory::class, 'plant_id')->orderBy('created_at', 'desc');
    }

    public function preferenceValue(string $key): ?string
    {
        $preference = Preference::where('key', $key)->first();
        if (! $preference) {
            return null;
        }

        $pivot = $this->preferences()->where('preference_id', $preference->id)->first();

        return $pivot ? $pivot->pivot->value : null;
    }

    public function scheduleStageDueDates(): void
    {
        $stages = $this->stages()->with('checklist.items')->orderBy('order')->get();
        if ($stages->isEmpty()) {
            return;
        }

        $tz = auth()->user() ? User::getUserTimezone(auth()->user()) : config('app.timezone');
        $start = Carbon::parse($this->created_at, $tz)->startOfDay();

        $offsetDays = 0;
        foreach ($stages as $stage) {
            if (! $stage->checklist) {
                $offsetDays += max(0, (int) $stage->interval_stage_days);

                continue;
            }

            $intervalItemDays = max(0, (int) $stage->interval_item_days);
            $item = $stage->checklist->firstItem();
            while ($item) {
                $existing = $this->items()->where('item_id', $item->id)->first();
                if (! $existing || ! $existing->pivot || ! $existing->pivot->due) {
                    $due = $start->copy()->addDays($offsetDays);
                    $this->items()->syncWithoutDetaching([$item->id => ['due' => $due]]);
                }

                $offsetDays += $intervalItemDays;
                $item = $item->child;
            }

            $offsetDays += max(0, (int) $stage->interval_stage_days);
        }
    }
}
