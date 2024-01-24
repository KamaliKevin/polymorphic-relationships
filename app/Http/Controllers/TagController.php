<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $tags = Tag::all();

        return view('tag.index', compact('tags'));
    }

    public function create(Request $request): View
    {
        return view('tag.create');
    }

    public function store(TagStoreRequest $request): RedirectResponse
    {
        $tag = Tag::create($request->validated());

        $request->session()->flash('tag.id', $tag->id);

        return redirect()->route('tag.index');
    }

    public function show(Request $request, Tag $tag): View
    {
        return view('tag.show', compact('tag'));
    }

    public function edit(Request $request, Tag $tag): View
    {
        return view('tag.edit', compact('tag'));
    }

    public function update(TagUpdateRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        $request->session()->flash('tag.id', $tag->id);

        return redirect()->route('tag.index');
    }

    public function destroy(Request $request, Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('tag.index');
    }
}
