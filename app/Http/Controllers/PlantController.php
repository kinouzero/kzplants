<?php

namespace App\Http\Controllers;

use App\Events\PlantActionOccurred;
use App\Http\Requests\PlantStoreRequest;
use App\Http\Requests\PlantUpdateRequest;
use App\Models\Comment;
use App\Models\Dashboard;
use App\Models\Item;
use App\Models\Picture;
use App\Models\Plant;
use App\Models\Preference;
use App\Models\Property;
use App\Models\Stage;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\Tag;
use App\Models\User;
use App\Presenters\PlantPresenter;
use App\Services\PlantService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlantController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Plant::class);
        $plants = Plant::with(['strain.tags', 'strain.properties', 'tags', 'properties'])->get();
        $strains = Strain::all();
        $style = ! $plants->isEmpty() ? sprintf('<style>%s</style>', implode(' ', [PlantPresenter::tagsStyle($plants), PlantPresenter::propertiesStyle($plants)])) : '';

        return view('plant.index', compact('plants', 'strains', 'style'));
    }

    public function create()
    {
        $this->authorize('create', Plant::class);
        $plant = null;
        $dashboards = Dashboard::all();
        $strains = Strain::all();
        $tags = Tag::all();
        $properties = Property::all();
        $preferences = Preference::all();

        $title = __('ui.create_new', ['item' => __('ui.plant')]);

        $currentDashboard = Dashboard::getCurrentDashboard();
        $options_dashboards = $options_strains = $options_tags = $options_properties = [];
        foreach ($dashboards as $_dashboard) {
            $options_dashboards[] = view('template.form.select.option', ['value' => $_dashboard->id, 'title' => $_dashboard->name, 'selected' => $currentDashboard && $currentDashboard->id === $_dashboard->id]);
        }
        foreach ($strains as $strain) {
            $options_strains[] = view('template.form.select.option', ['value' => $strain->id, 'title' => $strain->name, 'selected' => false]);
        }
        foreach ($tags as $tag) {
            $options_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => false]);
        }
        foreach ($properties as $property) {
            $options_properties[] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);
        }

        $template_preferences = [];
        foreach ($preferences as $preference) {
            $options_preferences = [];
            if (($options = $preference->options()) && array_key_exists('props', $options)) {
                foreach ($options['props'] as $k => $v) {
                    $options_preferences[] = view('template.form.select.option', ['value' => $k, 'title' => $v, 'selected' => false]);
                }
            }

            if ($preference->type === 'checklist') {
                $template_preferences[] = view('template.form.floating', [
                    'type' => 'select',
                    'id' => sprintf('preference-%s', $preference->id),
                    'name' => sprintf(
                        'preferences[%s]%s',
                        $preference->id,
                        $options && array_key_exists('multiple', $options) ? '[]' : ''
                    ),
                    'label' => $preference->name,
                    'options' => implode('', $options_preferences),
                    'placeholder' => 'Select',
                    'class' => ['parent' => 'mb-3', 'input' => 'select2'],
                    'extra' => ['input' => $options && array_key_exists('multiple', $options) ? 'multiple' : ''],
                ]);
            } else {
                $template_preferences[] = view('template.form.floating', [
                    'type' => $preference->type,
                    'id' => sprintf('preference-%s', $preference->id),
                    'name' => sprintf('preferences[%s]', $preference->id),
                    'label' => $preference->name,
                    'value' => '',
                    'class' => ['parent' => 'mb-3'],
                    'extra' => null,
                ]);
            }
        }

        $template_properties = [
            view('template.property.form.row', ['id' => null, 'value' => '', 'options' => $options_properties]),
            view('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => __('ui.no_property_yet')]),
        ];

        return view('plant.edit', compact('plant', 'options_dashboards', 'options_strains', 'options_tags', 'template_properties', 'template_preferences', 'title'));
    }

    public function edit($id)
    {
        $plant = Plant::with(['dashboards', 'strain', 'tags', 'properties', 'preferences'])->findOrFail($id);
        $this->authorize('update', $plant);
        $dashboards = Dashboard::all();
        $strains = Strain::all();
        $tags = Tag::all();
        $properties = Property::all();
        $preferences = Preference::all();

        $title = __('ui.edit_item', ['item' => __('ui.plant'), 'name' => $plant->name]);

        $alert = null;
        $options_dashboards = $options_strains = $options_tags = $options_properties = $value_properties = [];
        foreach ($dashboards as $_dashboard) {
            $options_dashboards[] = view('template.form.select.option', ['value' => $_dashboard->id, 'title' => $_dashboard->name, 'selected' => $plant->dashboards()->where('dashboard_id', $_dashboard->id)->exists()]);
        }
        foreach ($strains as $strain) {
            $options_strains[] = view('template.form.select.option', ['value' => $strain->id, 'title' => $strain->name, 'selected' => $plant->strain->id === $strain->id]);
        }
        foreach ($tags as $tag) {
            $options_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => $plant->tags->contains('id', $tag->id)]);
        }
        foreach ($properties as $property) {
            $options_properties['clone'][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);
            $value_properties['clone'] = null;
            if ($plant && $plant->properties->count() > 0) {
                foreach ($plant->properties as $plantProperty) {
                    $options_properties[$plantProperty->pivot->property_id][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => $plantProperty->pivot->property_id === $property->id]);
                    $value_properties[$plantProperty->pivot->property_id] = $plantProperty->pivot->value;
                }
            } else {
                $alert = view('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => __('ui.no_property_yet')]);
            }
        }

        $template_properties = [];
        foreach ($options_properties as $k => $options) {
            $template_properties[] = view('template.property.form.row', [
                'id' => $k === 'clone' ? null : Str::uuid(),
                'value' => $value_properties[$k] ?: '',
                'options' => $options,
            ]);
        }

        if ($alert) {
            $template_properties[] = $alert;
        }

        $template_preferences = [];
        foreach ($preferences as $preference) {
            $plantPref = $plant ? $plant->preferences()->where('preference_id', $preference->id)->first() : null;

            $options_preferences = [];
            $plantPrefValue = $plantPref ? $plantPref->pivot->value : null;
            $decoded = null;
            if ($plantPrefValue && is_string($plantPrefValue)) {
                $decoded = json_decode($plantPrefValue, true);
            }
            if (($options = $preference->options()) && array_key_exists('props', $options)) {
                foreach ($options['props'] as $k => $v) {
                    $selected = false;
                    if (is_array($decoded)) {
                        $selected = in_array($k, $decoded, true);
                    } else {
                        $selected = $plantPref && $plantPref->pivot->value == $k;
                    }
                    $options_preferences[] = view('template.form.select.option', ['value' => $k, 'title' => $v, 'selected' => $selected]);
                }
            }

            if ($preference->type === 'checklist') {
                $template_preferences[] = view('template.form.floating', [
                    'type' => 'select',
                    'id' => sprintf('preference-%s', $preference->id),
                    'name' => sprintf(
                        'preferences[%s]%s',
                        $preference->id,
                        $options && array_key_exists('multiple', $options) ? '[]' : ''
                    ),
                    'label' => $preference->name,
                    'options' => implode('', $options_preferences),
                    'placeholder' => 'Select',
                    'class' => ['parent' => 'mb-3', 'input' => 'select2'],
                    'extra' => ['input' => $options && array_key_exists('multiple', $options) ? 'multiple' : ''],
                ]);
            } else {
                $template_preferences[] = view('template.form.floating', [
                    'type' => 'text',
                    'id' => sprintf('preference-%s', $preference->id),
                    'name' => sprintf('preferences[%s]', $preference->id),
                    'label' => $preference->name,
                    'value' => $plantPref ? $plantPref->pivot->value : '',
                    'class' => ['parent' => 'mb-3'],
                    'extra' => null,
                ]);
            }
        }

        return view('plant.edit', compact('plant', 'options_dashboards', 'options_strains', 'options_tags', 'template_properties', 'template_preferences', 'title'));
    }

    public function detail($id)
    {
        $plant = Plant::with(['dashboards', 'strain.tags', 'strain.properties', 'tags', 'properties', 'stages.checklist', 'items', 'waterings', 'comments', 'pictures', 'statut', 'history'])->findOrFail($id);
        $this->authorize('view', $plant);
        $style = $plant ? sprintf('<style>%s</style>', implode(' ', [PlantPresenter::tagsStyle([$plant]), PlantPresenter::propertiesStyle([$plant])])) : '';

        return view('plant.detail', compact('plant', 'style'));
    }

    public function stages($id)
    {
        $plant = Plant::with(['stages.checklist'])->findOrFail($id);
        $this->authorize('view', $plant);
        $stages = Stage::with('checklist')->orderBy('order')->get();
        $initial = $plant->stages()->wherePivot('initial', true)->first();

        return view('plant.checklists', compact('plant', 'stages', 'initial'));
    }

    public function pictures($id)
    {
        $plant = Plant::with(['pictures'])->findOrFail($id);
        $this->authorize('view', $plant);

        return view('plant.pictures', compact('plant'));
    }

    public function compare($id)
    {
        $plant = Plant::with(['pictures'])->findOrFail($id);
        $this->authorize('view', $plant);

        return view('plant.compare', compact('plant'));
    }

    // Actions
    public function store(PlantStoreRequest $request, PlantService $plantService)
    {
        $this->authorize('create', Plant::class);
        $plantService->create(array_merge($request->validated(), [
            'plant_photo' => $request->file('plant_photo'),
        ]));

        return back()->with('success', __('ui.created_success', ['item' => __('ui.plant')]));
    }

    public function update(PlantUpdateRequest $request, $id, PlantService $plantService)
    {
        $plant = Plant::findOrFail($id);
        $this->authorize('update', $plant);
        $plantService->update($plant, $request->validated());

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.plant')]));
    }

    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $this->authorize('delete', $plant);
        $plant->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.plant')]));
    }

    public function addToPlant(Request $request, $id, $objectType, $object_id)
    {
        $plant = Plant::findOrFail($id);
        if ($objectType === 'stage' && $stage = Stage::findOrFail($object_id)) {
            $plant->stages()->attach($stage, ['initial' => false]);
            event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'checklist_add', 'checklist_id' => $stage->checklist_id]));
            $plant->scheduleStageDueDates();

            return back()->with('success', __('ui.checklist_added'));
        }
        if ($objectType === 'initial' && $stage = Stage::findOrFail($object_id)) {
            $plant->stages()->updateExistingPivot($plant->stages()->pluck('id'), ['initial' => false]);
            $plant->stages()->updateExistingPivot($stage->id, ['initial' => true]);
            event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'checklist_initial_set', 'checklist_id' => $stage->checklist_id]));
            $plant->scheduleStageDueDates();

            return back()->with('success', __('ui.first_checklist_added'));
        }
        if ($objectType === 'flush' && $item = Item::findOrFail($object_id)) {
            $plant->items()->syncWithoutDetaching([$item->id => ['flush' => true]]);

            return back()->with('success', __('ui.flush_added'));
        }
        if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
            $plant->pictures()->updateExistingPivot($plant->pictures()->pluck('id'), ['default' => false]);
            $plant->pictures()->updateExistingPivot($picture->id, ['default' => true]);

            return back()->with('success', __('ui.default_picture_added'));
        }
    }

    public function removeFromPlant(Request $request, $id, $objectType, $object_id)
    {
        $plant = Plant::findOrFail($id);
        if ($objectType === 'stage' && $stage = Stage::findOrFail($object_id)) {
            $plant->stages()->detach($stage);
            event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'checklist_remove', 'checklist_id' => $stage->checklist_id]));

            return back()->with('success', __('ui.checklist_removed'));
        }
        if ($objectType === 'initial' && $stage = Stage::findOrFail($object_id)) {
            $plant->stages()->updateExistingPivot($stage->id, ['initial' => false]);
            event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'checklist_initial_remove', 'checklist_id' => $stage->checklist_id]));

            return back()->with('success', __('ui.first_checklist_removed'));
        }
        if ($objectType === 'flush' && $item = Item::findOrFail($object_id)) {
            $plant->items()->syncWithoutDetaching([$item->id => ['flush' => false]]);

            return back()->with('success', __('ui.flush_removed'));
        }
        if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
            $plant->pictures()->updateExistingPivot($picture->id, ['default' => false]);

            return back()->with('success', __('ui.default_picture_removed'));
        }
    }

    public function itemDueSave(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->items()->syncWithoutDetaching([$request->item_id => ['due' => $request->due]]);
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'item_due', 'item_id' => $request->item_id, 'due' => $request->due]));

        return back()->with('success', __('ui.due_date_saved'));
    }

    public function itemDueRemove(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->items()->syncWithoutDetaching([$request->item_id => ['due' => null]]);
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'item_due_removed', 'item_id' => $request->item_id]));

        return back()->with('success', __('ui.due_date_removed'));
    }

    public function itemToggle(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->items()->syncWithoutDetaching([$request->item_id => ['checked' => $request->checked === 'false' ? now(User::getUserTimezone(auth()->user())) : null]]);
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'item_toggle', 'item_id' => $request->item_id, 'checked' => $request->checked === 'false']));

        // Update statut
        $plant->statut_id = Statut::getStatut($plant)->id;
        $plant->save();

        return back()->with('success', __('ui.checklist_item_status_updated'));
    }

    public function water(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->waterings()->create(['chemical' => false]);
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'watering', 'chemical' => false]));

        return back()->with('success', __('ui.watering_without_chemical'));
    }

    public function waterChemical(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->waterings()->create(['chemical' => true]);
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'watering', 'chemical' => true]));

        return back()->with('success', __('ui.watering_with_chemical'));
    }

    public function commentAdd(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);

        $comment = new Comment;
        $comment->plant_id = $plant->id;
        $comment->author_id = auth()->user()->id;
        $comment->value = $request->comment;
        $comment->save();
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'comment_add', 'comment_id' => $comment->id]));

        return back()->with('success', __('ui.comment_added'));
    }

    public function commentEdit(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $comment = $plant->comments()->findOrFail($request->comment_id);
        $comment->value = $request->comment;
        $comment->save();
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'comment_edit', 'comment_id' => $comment->id]));

        return back()->with('success', __('ui.comment_edited'));
    }

    public function commentRemove(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $comment = $plant->comments()->findOrFail($request->comment_id);
        $comment->delete();
        event(new PlantActionOccurred($plant, auth()->user(), ['type' => 'comment_remove', 'comment_id' => $comment->id]));

        return back()->with('success', __('ui.comment_removed'));
    }
}
