<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function following($id)
    {
        $authUser = Auth::user();
        $user = User::with('following')->findOrFail($id);
        $isMyPage = $authUser && $authUser->id == $user->id;
        $followingUsers = $user->following;
        $followingUserNum = $followingUsers->count();

        return Inertia::render('FollowPage', [
            'user' => $user,
            'isMyPage' => $isMyPage,
            'followingUsers' => $followingUsers,
            'followingUserNum' => $followingUserNum,
            'followedUserNum' => $user->followers()->count(),
        ]);
    }

    public function followers($id)
    {
        $authUser = Auth::user();
        $user = User::with('followers')->findOrFail($id);
        $isMyPage = $authUser && $authUser->id == $user->id;
        $followers = $user->followers;
        $followedUserNum = $followers->count();

        return Inertia::render('FollowPage', [
            'user' => $user,
            'isMyPage' => $isMyPage,
            'followers' => $followers,
            'followingUserNum' => $user->following()->count(),
            'followedUserNum' => $followedUserNum,
        ]);
    }


    public function follow(Request $request, $id)
    {
        $authUser = Auth::user();
        $userToFollow = User::findOrFail($id);

        if ($authUser && !$authUser->following->contains($userToFollow->id)) {
            $authUser->following()->attach($userToFollow->id);
        }

        return redirect()->back();
    }

    public function unfollow(Request $request, $id)
    {
        $authUser = Auth::user();
        $userToUnfollow = User::findOrFail($id);

        if ($authUser && $authUser->following->contains($userToUnfollow->id)) {
            $authUser->following()->detach($userToUnfollow->id);
        }

        return redirect()->back();
    }

}
