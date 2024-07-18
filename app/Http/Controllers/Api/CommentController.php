<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'post_id' => 'required',
            'comment_content' => 'required'
        ]);

        $validated['author'] = auth()->user()->id;
        $validated['created_at'] = Carbon::now();

        $data = Comment::create($validated);

        return new CommentResource($data->loadMissing('comments:id,username,firstname,lastname'));
    }

    public function update(Request $request, $id) {
        $comment = Comment::findOrFail($id);

        if($comment->author == auth()->user()->id) {
            $validated = $request->validate([
                'comment_content' => 'required'
            ]);
    
            $validated['author'] = auth()->user()->id;
            $validated['created_at'] = Carbon::now();
    
            $comment->update($request->only('comment_content'));
    
            return new CommentResource($comment->loadMissing('comments:id,username,firstname,lastname'));
        }

        return response()->json(['message' => 'Data not found'], 404);
    }
}
