<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;

use App\Http\Resources\PostResource;
use App\Http\Resources\PostLikeResource;
use App\Models\PostLike;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            return PostResource::collection($request->user()->getPosts);
        } catch (Exception $e) {
            return response()->json();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|max:2048|min:2'
        ]);

        $post = $request->user()->addPost($request->get('text'));

        return new PostResource($post);
    }

    public function like(Request $request, $id)
    {
        return response()->json([
            'data' => [
                'successful' => $request->user()->togglePostLike($id)
            ]
        ]);
    }

    public function liked(Request $request, $id)
    {
        return response()->json([
            'data' => [
                'liked' => $request->user()->isPostLiked($id)
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|max:2048|min:2'
        ]);

        $newText = $request->get('text');

        $post = Post::findOrFail($id);
        $post->text = $newText;
        $post->save();
        return new PostResource($post);
    }

    public function destroy(Request $request, $id)
    {
        $response = ['data' => ['successful' => false]];

        if ($request->user()->isPostAuthor($id)) {
            Post::find($id)->delete();
            $response['data']['successful'] = true;
        }

        return response()->json($response);
    }
}
