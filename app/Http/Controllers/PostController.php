<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

use DashX;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::with('user');

        if($request->has('offset')) {
            $posts->skip($request->offset);
        }

        if($request->has('limit')) {
            $posts->take($request->limit);
        }

        return response()->json([
            'posts' => $posts->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
        ]);

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'text' => $request->text
        ]);

        return response()->json([
            'message' => 'Successfully created post.',
            'post' => $post
        ]);
    }

    /**
     * Toggle bookmark for the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function toggleBookmark(Post $post)
    {
        $bookmark = $post->bookmarks()->where('user_id', auth()->user()->id)->first();

        if($bookmark) {
            $post->bookmarks()->detach(auth()->user()->id);
            DashX::track(
                'Post Bookmarked',
                auth()->user()->id,
                $bookmark->pivot->toArray()
            );
        } else {
            $post->bookmarks()->attach(auth()->user()->id);

            $bookmark = $post->bookmarks()->where('user_id', auth()->user()->id)->first();

            DashX::track(
                'Post Unbookmarked',
                auth()->user()->id,
                $bookmark->pivot->toArray()
            );
        }

        return response()->json([]);
    }

    /**
     * Get the bookmarked posts of user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getBookmarkedPosts(Request $request)
    {
        $posts = Post::with('user')->whereHas('bookmarks', function($query) {
            $query->where('user_id', auth()->user()->id);
        });

        if($request->has('offset')) {
            $posts->skip($request->offset);
        }

        if($request->has('limit')) {
            $posts->take($request->limit);
        }

        return response()->json([
            'bookmarks' => $posts->get()
        ]);
    }
}
