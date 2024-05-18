<?php

namespace App\Http\Controllers;

use App\Models\RoomComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoomCommentController extends Controller

{


    public function index($roomId)
    {
        $comments = RoomComment::where('room_id', $roomId)->with('user')->get();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'room_id' => 'required|integer|exists:rooms,id',
        ]);

        $user_id = Auth::check() ? Auth::id() : null;
        $comment = new RoomComment();
        $comment->comment = $request->comment;
        $comment->room_id = $request->room_id;
        $comment->user_id = $user_id;
        $comment->save();

        // コメントが保存された後、すべてのコメントを返す
        $comments = RoomComment::where('room_id', $request->room_id)->with('user')->get();



        return response()->json($comments);
    }
}
