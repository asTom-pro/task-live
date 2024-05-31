<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\Room;
use App\Models\RoomTag;


class HomeController extends Controller
{
    public function index(Request $request):Response
    {

        $search = $request->input('search');
        $tag = $request->input('tag');

        $query = Room::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($tag) {
            $query->whereHas('tags', function ($query) use ($tag) {
                $query->where('name', $tag);
            });
        }
        $rooms = $query->with([
            'tags',
            'user',
            'users' => function ($query) {
                $query->select(['id','profile_img']);
            }
        ])->orderBy('created_at', 'desc')->get();

        $rooms->each(function($room) {
            $room->is_room_expired = $this->calculateIsRoomExpired($room->time_limit, $room->created_at);
        });
        
        $tags = RoomTag::all();

        return Inertia::render('Top', [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => [
                'location' => $request->url(),
            ],
            'rooms' => $rooms,
            'tags' => $tags,
        ]);
    }

    private function calculateIsRoomExpired($durationInSeconds, $createdAt)
    {
        $now = new \DateTime();
        $createdDate = new \DateTime($createdAt);
        $elapsedTimeInSeconds = ($now->getTimestamp() - $createdDate->getTimestamp());

        return $durationInSeconds - $elapsedTimeInSeconds <= 0;
    }
}


