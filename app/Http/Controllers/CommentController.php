<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        $post->comments->map(function ($comment) {
            return $comment['user'] = $comment->user;
        });
        return Response::successJson($post->comments, '');
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
    public function store(Post $post, CommentRequest $request)
    {
        $comment = Comment::create([
            'text' => $request->text,
            'post_id' => $post->id,
            'author_id' => $request->user()->id,
        ]);
        
        return Response::successJson($comment, 'Comment Successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, CommentRequest $request, Comment $comment)
    {
        if($request->user()->comments->pluck('id')->contains($comment->id)) {
            $comment->update([
                'text' => $request->text,
                'post_id' => $post->id,
                'author_id' => $request->user()->id,
            ]);
            
            return Response::successJson($comment, 'Comment Successfully updated.');
        }
        return Response::errorJson([], 'Unauthorised.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, CommentRequest $request, Comment $comment)
    {
        if($request->user()->comments->pluck('id')->contains($comment->id)) {
            $comment->delete();
            return Response::successJson([], 'Comment Successfully deleted.');
        }
        return Response::errorJson([], 'Unauthorised.');
    }
}
