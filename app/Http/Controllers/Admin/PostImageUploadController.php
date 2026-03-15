<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostImageUploadController extends Controller
{
    /**
     * Upload an image for use in the blog post editor.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'], // 2MB max
        ], [], ['image' => 'image file']);

        $file = $request->file('image');
        $name = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('posts', $name, 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ]);
    }
}
