<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return Response::successJson($posts, '');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        if($image = $request->file('image')) {
            $imageName = 'posts/' . time() . $image->getClientOriginalName();
            $image->storeAs('public/posts', $imageName);
        }

        $post = Post::create([
            'title' => $request->title,
            'image' => $imageName ?? null,
            'description' => $request->description,
            'user_id' => $request->user()->id
        ]);

        return Response::successJson($post, 'Post Successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->comments->map(function ($comment) {
            return $comment['user'] = $comment->user;
        });

        $data = [
            "post" => $post,
            "comments" => $post->comments,
        ];
        return Response::successJson($data, '');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if($image = $request->file('image')) {
            $imageName = 'posts/' . time() . $image->getClientOriginalName();
            $image->storeAs('public/posts', $imageName);
        }

        $post->update([
            'title' => $request->title,
            'image' => $imageName ?? $post->image,
            'description' => $request->description,
            'user_id' => $request->user()->id
        ]);

        return Response::successJson($post, 'Post Successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        if($request->user()->is_admin == 1) {
            if($post->image && Storage::disk('public')->exists('posts/' . $post->image)) {
                Storage::disk('public')->delete('posts/' . $post->image);
            }

            $post->delete();
            return Response::successJson([], 'Post Successfully deleted.');
        }
        return Response::errorJson([], 'Unauthorised.');
    }
}
