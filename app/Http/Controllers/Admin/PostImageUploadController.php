<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostImageUploadController extends Controller
{
    /**
     * Upload an image for use in the blog post editor.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'], // 5MB max (large screenshots from the editor)
        ], [], ['image' => 'image file']);

        $file = $request->file('image');
        $name = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('posts', $name, 'public');

        if (! Storage::disk('public')->exists($path)) {
            return response()->json([
                'message' => __('The file was not found on disk after upload. Run `php artisan storage:link` and confirm the public disk is writable.'),
            ], 422);
        }

        return response()->json([
            'url' => asset(Storage::url($path)),
        ]);
    }
}
