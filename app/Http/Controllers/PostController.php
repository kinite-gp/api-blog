<?php

namespace App\Http\Controllers;

use App\Models\Paragraph;
use App\Models\Post;
use http\Client\Curl\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use phpseclib3\File\ASN1\Maps\NameConstraints;

class PostController extends Controller
{
    public function show_one($id): \Illuminate\Http\JsonResponse
    {
        $post = Post::find($id);


        return response()->json([
            "id" => $id,
            "title" => $post->title,
            "author" => $post->author->name,
            "paragraphs" => $post->paragraphs,
            "comments" => $post->comments
        ]);
    }

    public function edit_one(Request $request , $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "title" => ["required"],
            "paragraphs" => ["required"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "unsuccessfully validate",
                "errors" => $validator->errors()
            ]);
        }

        $post = Post::find($id);

        $post->update([
            "title" => $request->input("title"),
        ]);

        $paragraphsdata = $request->input("paragraphs", []);
//        dd($paragraphsdata);

        foreach ($paragraphsdata as $paragraph) {
            $p = Paragraph::find($paragraph["id"]);
            $p->update([
                "headline" => $paragraph["headline"],
                "text" => $paragraph["text"],
            ]);
        }

        return response()->json([
            "message" => "successfully edit"
        ]);

    }

    public function create_one(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            "title" => ["required"],
            "paragraphs" => ["required"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "unsuccessfully validate",
                "errors" => $validator->errors()
            ]);
        }

//        dd($request->input("paragraphs"));

        $post = Post::create([
            "title" => $request->input("title"),
            "author_id" => Auth::id(),
        ]);

        foreach ($request->input("paragraphs") as $paragraph) {
            $p = Paragraph::create([
                "post_id" => $post->id,
                "headline" => $paragraph["headline"],
                "text" => $paragraph["text"]
            ]);
        }

        return response()->json([
            "message" => "successfully create"
        ]);

    }


    public function post_list($ordering)
    {
        $posts_list = [];
        if ($ordering === "newer") {
            $posts = Post::orderBy('created_at', 'desc')->get();
        } elseif ($ordering === "older") {
            $posts = Post::orderBy('created_at', 'asc')->get();
        } else {
            return response()->json([
                "message" => "Sorting is not correct"
            ]);
        }

        foreach ($posts as $post) {
            $posts_list[] = [
                "title" => $post->title,
                "author" => $post->author->name,
                "paragraphs" => $post->paragraphs,
                "comments" => $post->comments,
            ];
        }

        return response()->json($posts_list);
    }

    public function post_take($ordering, $number)
    {
        $posts_list = [];

        if ($ordering === "newer") {
            $posts = Post::orderBy('created_at', 'desc')->take($number)->get();
        } elseif ($ordering === "older") {
            $posts = Post::orderBy('created_at', 'asc')->take($number)->get();
        } else {
            return response()->json([
                "message" => "Sorting is not correct"
            ]);
        }

        foreach ($posts as $post) {
            $posts_list[] = [
                "title" => $post->title,
                "author" => $post->author->name,
                "paragraphs" => $post->paragraphs,
                "comments" => $post->comments,
            ];
        }

        return response()->json($posts_list);
    }
}
