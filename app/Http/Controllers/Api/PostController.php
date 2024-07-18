<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index() {
        $posts = Post::with(['writer:id,username,firstname,lastname', 'comments'])->get();
        // return response()->json([
        //     'message' => 'success',
        //     'data' => $posts
        // ], 200);

        return PostResource::collection($posts);
    }

    public function show($id) {
        $posts = Post::with(['writer:id,firstname,lastname', 'comments'])->findOrFail($id);

        return new PostDetailResource($posts);
    }

    public function store(Request $request) {
        // return $request->file;

        $validated = $request->validate([
            'title' => 'required',
            'content_post' => 'required',
        ]);

        $image = null;
        if($request->file) {
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName.'.'.$extension;

            Storage::putFileAs('image', $request->file, $image);
            $validated['image'] = $image;
        }

        $validated['author'] = auth()->user()->id;
        $validated['created_at'] = Carbon::now();

        $data = Post::create($validated);

        return new PostDetailResource($data->loadMissing('writer:id,username,firstname,lastname'));
    }

    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);

        if (auth()->user()->id == $post->author) {
            $validated = $request->validate([
                'title' => 'required',
                'content_post' => 'required',
            ]);
    
            $validated['author'] = auth()->user()->id;
            $validated['created_at'] = Carbon::now();
    
            $post->update($request->all());
    
            return new PostDetailResource($post->loadMissing('writer:id,username,firstname,lastname'));
        }

        return response()->json(['message' => 'Data not found']);
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);

        if (auth()->user()->id == $post->author) {
            $post->delete();

            return response()->json(['message' => 'Post deleted successfully']);
        }
        return response()->json(['message' => 'Data not found']);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
