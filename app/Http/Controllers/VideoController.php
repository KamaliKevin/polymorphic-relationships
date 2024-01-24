<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoStoreRequest;
use App\Http\Requests\VideoUpdateRequest;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $videos = Video::all();

        return view('video.index', compact('videos'));
    }

    public function create(Request $request): View
    {
        return view('video.create');
    }

    public function store(VideoStoreRequest $request): RedirectResponse
    {
        $video = Video::create($request->validated());

        $request->session()->flash('video.id', $video->id);

        return redirect()->route('video.index');
    }

    public function show(Request $request, Video $video): View
    {
        return view('video.show', compact('video'));
    }

    public function edit(Request $request, Video $video): View
    {
        return view('video.edit', compact('video'));
    }

    public function update(VideoUpdateRequest $request, Video $video): RedirectResponse
    {
        $video->update($request->validated());

        $request->session()->flash('video.id', $video->id);

        return redirect()->route('video.index');
    }

    public function destroy(Request $request, Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('video.index');
    }
}
