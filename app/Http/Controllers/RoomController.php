<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\RoomTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    protected $logController;

    public function __construct(LogController $logController)
    {
        $this->logController = $logController;
    }

    public function create(): Response
    {
        return Inertia::render('MakeRoom');
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'room_name' => 'required|string|max:20',
            'tag' => 'nullable|string|max:20',
            'set_time_hour' => 'nullable|integer|min:0|max:23',
            'set_time_minute' => 'nullable|integer|min:0|max:59',
        ], [
            'set_time_hour.required_without_all' => 'set_time_hourかset_time_minuteのどちらかは必須です。',
            'set_time_minute.required_without_all' => 'set_time_minuteかset_time_hourのどちらかは必須です。',
        ]);

        $validator->sometimes('set_time_hour', 'required_without:set_time_minute', function ($input) {
            return !$input->set_time_hour && !$input->set_time_minute;
        });

        $validator->sometimes('set_time_minute', 'required_without:set_time_hour', function ($input) {
            return !$input->set_time_hour && !$input->set_time_minute;
        });

        $validated = $validator->validate();

        $user_id = $request->user() ? $request->user()->id : null;
        $session_id = session()->getId();
        $time_limit = ($validated['set_time_hour'] ?? 0) * 3600 + ($validated['set_time_minute'] ?? 0) * 60;

        DB::transaction(function () use ($validated, $time_limit, $user_id, $session_id) {
            $room = Room::create([
                'name' => $validated['room_name'],
                'time_limit' => $time_limit,
                'user_id' => $user_id,
                'session_id' => $session_id, // Assuming you have a session_id column in rooms table
            ]);

            if (!empty($validated['tag'])) {
                $tags = explode(' ', str_replace('　', ' ', $validated['tag']));
                $tags = array_unique(array_filter($tags, 'trim'));

                foreach ($tags as $tagName) {
                    $tag = RoomTag::firstOrCreate(['name' => $tagName]);
                    $room->tags()->attach($tag->id);
                }
            }

            session(['created_room_id' => $room->id]);
        });

        return redirect()->route('room.show', session('created_room_id'));
    }

    public function show($id): Response
    {
        $room = Room::with('tags', 'user')->findOrFail($id);
        $user = auth()->user();
        $uri = request()->path();
        $ipaddress = request()->ip();

        if ($user) {
            $room->users()->syncWithoutDetaching($user->id);
        } else {
            $sessionId = session()->getId();
            DB::table('room_users')->updateOrInsert(
                ['room_id' => $room->id, 'session_id' => $sessionId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->logController->setLogs($uri, $ipaddress);

        $page_title = $room->name;

        return Inertia::render('RoomShow', ['room' => $room, 'title' => $page_title]);
    }

    public function getUserCount(Request $request, $id)
    {
        $uri = $request->path();

        if (substr($uri, -11) === '/user-count') {
            $uri = substr($uri, 0, -11);
        }

  
        $userCount = DB::table('logs')
            ->select(DB::raw('COUNT(DISTINCT ipaddress) as cnt'))
            ->where('uri', $uri)
            ->where('updated_at', '>', DB::raw('CURRENT_TIMESTAMP + interval -1 minute'))
            ->value('cnt');

        return response()->json(['user_count' => $userCount]);
    }
}
