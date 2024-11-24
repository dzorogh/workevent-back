<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $page = Page::where('path', $request->path)->firstOrFail();

        $page->load('metadata');
        
        return new PageResource($page);
    }
} 