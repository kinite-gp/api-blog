<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function edit_one(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "text" => ["required"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "unsuccessfully validate",
                "errors" => $validator->errors()
            ]);
        }

        $comment = Comment::find($id);
        $comment->update([
            "text" => $request->input("text"),
        ]);

        return response()->json([
            "message" => "successfully edit"
        ]);
    }

    public function create_one(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "post_id" => ["required"],
            "text" => ["required"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "unsuccessfully validate",
                "errors" => $validator->errors()
            ]);
        }

        $comment = Comment::create([
            "post_id" => $request->input("post_id"),
            "comment_author_id" => Auth::id(),
            "text" => $request->input("text")
        ]);

        return response()->json([
            "message" => "successfully create"
        ]);
    }
}
