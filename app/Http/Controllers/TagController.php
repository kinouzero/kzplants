<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tag;

class TagController extends Controller
{
    // Views
    public function index()
    {
        $tags = Tag::all();
        return view('tag.index', compact('tags'));
    }

    public function create()
    {
        $tag = null;
        return view('tag.create', compact('tag'));
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('tag.edit', compact('tag'));
    }

    public function detail($id)
    {
        $tag = Tag::findOrFail($id);
        return view('tag.detail', compact('tag'));
    }

    // Actions
    public function store(Request $request)
    {

        $tag           = new Tag();
        $validatedData = $request->validate(['name' => 'required|string']);
        $tag->name     = $validatedData['name'];
        $tag->save();

        return redirect()->route('tag.index')->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->update($request->all());
        return redirect()->route('tag.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return redirect()->route('tag.index')->with('success', 'Tag deleted successfully.');
    }
}
