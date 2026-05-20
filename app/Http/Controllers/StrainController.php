<?php

namespace App\Http\Controllers;

use App\Http\Requests\StrainStoreRequest;
use App\Http\Requests\StrainUpdateRequest;
use App\Models\Picture;
use App\Models\Property;
use App\Models\Strain;
use App\Models\Tag;
use App\Services\StrainService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StrainController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Strain::class);
        $strains = Strain::with(['tags', 'properties'])->get();

        return view('strain.index', compact('strains'));
    }

    public function create()
    {
        $this->authorize('create', Strain::class);
        $strain = null;
        $tags = Tag::all();
        $properties = Property::all();

        $title = __('ui.create_new', ['item' => __('ui.strain')]);

        $options_tags = $options_properties = [];
        foreach ($tags as $tag) {
            $options_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => false]);
        }
        foreach ($properties as $property) {
            $options_properties[] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);
        }

        $template_properties = [view('template.property.form.row', [
            'id' => null,
            'value' => '',
            'options' => $options_properties,
        ])];

        return view('strain.edit', compact('strain', 'options_tags', 'template_properties', 'title'));
    }

    public function edit($id)
    {
        $strain = Strain::findOrFail($id);
        $this->authorize('update', $strain);
        $tags = Tag::all();
        $properties = Property::all();

        $title = __('ui.edit_item', ['item' => __('ui.strain'), 'name' => $strain->name]);

        $options_tags = $options_properties = $value_properties = [];
        foreach ($tags as $tag) {
            $options_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => $strain->tags->contains('id', $tag->id)]);
        }
        foreach ($properties as $property) {
            $options_properties['clone'][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);
            $value_properties['clone'] = null;
            foreach ($strain->properties as $strainProperty) {
                $options_properties[$strainProperty->pivot->property_id][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => $strainProperty->pivot->property_id === $property->id]);
                $value_properties[$strainProperty->pivot->property_id] = $strainProperty->pivot->value;
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

        return view('strain.edit', compact('strain', 'options_tags', 'template_properties', 'title'));
    }

    public function pictures($id)
    {
        $strain = Strain::findOrFail($id);
        $this->authorize('view', $strain);

        return view('strain.pictures', compact('strain'));
    }

    // Actions
    public function store(StrainStoreRequest $request, StrainService $strainService)
    {
        $this->authorize('create', Strain::class);
        $strainService->create($request->validated());

        return back()->with('success', __('ui.created_success', ['item' => __('ui.strain')]));
    }

    public function update(StrainUpdateRequest $request, $id, StrainService $strainService)
    {
        $strain = Strain::findOrFail($id);
        $this->authorize('update', $strain);
        $strainService->update($strain, $request->validated());

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.strain')]));
    }

    public function destroy($id)
    {
        $strain = Strain::findOrFail($id);
        $this->authorize('delete', $strain);
        $strain->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.strain')]));
    }

    public function addToStrain(Request $request, $id, $objectType, $object_id)
    {
        $strain = Strain::findOrFail($id);
        if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
            $strain->pictures()->updateExistingPivot($strain->pictures()->pluck('id'), ['default' => false]);
            $strain->pictures()->updateExistingPivot($picture->id, ['default' => true]);

            return back()->with('success', __('ui.default_picture_added'));
        }
    }

    public function removeFromStrain(Request $request, $id, $objectType, $object_id)
    {
        $strain = Strain::findOrFail($id);
        if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
            $strain->pictures()->updateExistingPivot($picture->id, ['default' => false]);

            return back()->with('success', __('ui.default_picture_removed'));
        }
    }
}
