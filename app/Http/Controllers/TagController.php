<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Tag::class);
        $tags = Tag::all();

        return view('tag.index', compact('tags'));
    }

    public function create()
    {
        $this->authorize('create', Tag::class);
        $tag = null;

        $title = __('ui.create_new', ['item' => __('ui.tag')]);

        return view('tag.edit', compact('tag', 'title'));
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('update', $tag);

        $title = __('ui.edit_item', ['item' => __('ui.tag'), 'name' => $tag->name]);

        return view('tag.edit', compact('tag', 'title'));
    }

    public function detail($id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('view', $tag);

        return view('tag.detail', compact('tag'));
    }

    // Actions
    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);
        $tag = new Tag;

        $validatedData = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
        ]);

        $tag->fill($validatedData)->save();

        return back()->with('success', __('ui.created_success', ['item' => __('ui.tag')]));
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('update', $tag);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
        ]);
        $tag->update($validatedData);

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.tag')]));
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('delete', $tag);
        $tag->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.tag')]));
    }
}
