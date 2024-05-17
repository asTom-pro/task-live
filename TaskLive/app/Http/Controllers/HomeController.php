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

        $rooms = $query->with(['tags', 'user'])->orderBy('created_at', 'desc')->get();
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
}
