<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EndedTask;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{

    public function index(Request $request)
    {
        $user_id = $request->query('user_id');
        $tasks = EndedTask::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ended_task' => 'required|string|max:400',
            'room_id' => 'required|integer|exists:rooms,id',
        ]);

        $user_id = Auth::id();

        EndedTask::create([
            'user_id' => $user_id,
            'room_id' => $validated['room_id'],
            'ended_task' => $validated['ended_task'],
        ]);

        return redirect()->route('user.mypage')->with('success_msg', 'タスクを保存しました!');
    }
}
