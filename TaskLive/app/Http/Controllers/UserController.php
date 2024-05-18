<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function show($id)
    {
        $authUser = Auth::user();
        $user = User::with('followers')->findOrFail($id); 
        $isMyPage = $authUser && $authUser->id == $user->id;
        $authUserId = $authUser ? $authUser->id : null;
        $userRooms = Room::where('user_id', $id)->get();
        $joinedRoomIds = $user->joinedRooms()->pluck('rooms.id')->toArray();
        $uniqueJoinedRoomIds = array_diff($joinedRoomIds, $userRooms->pluck('id')->toArray());
        $uniqueJoinedRooms = Room::whereIn('id', $uniqueJoinedRoomIds)->get();

        return Inertia::render('UserProfile', [
            'user' => $user,
            'isMyPage' => $isMyPage,
            'userRooms' => $userRooms,
            'joinedRooms' => $uniqueJoinedRooms,
            'totalRoomTime' => $this->calculateTotalRoomTime($userRooms, $uniqueJoinedRooms),
            'followingUserNum' => $user->following()->count(),
            'followedUserNum' => $user->followers()->count(),
            'authUserId' => $authUserId,
        ]);
    }

    public function myPage()
    {
        $user = Auth::user();
        return $this->show($user->id);
    }

    private function calculateTotalRoomTime($userRooms, $uniqueJoinedRooms)
    {
        $totalRoomTime = $userRooms->sum('time_limit');
        $totalRoomTime += $uniqueJoinedRooms->sum('time_limit');
        return number_format($totalRoomTime / 3600, 2);
    }

    public function showFollowPage($id)
    {
        $authUser = Auth::user();
        $user = User::with('following', 'followers')->findOrFail($id);
        $isMyPage = $authUser && $authUser->id == $user->id;
        $followingUsers = $user->following()->get();
        $followingUserNum = $followingUsers->count();
        $followedUserNum = $user->followers()->count();

        return Inertia::render('FollowPage', [
            'user' => $user,
            'isMyPage' => $isMyPage,
            'followingUsers' => $followingUsers,
            'followingUserNum' => $followingUserNum,
            'followedUserNum' => $followedUserNum,
        ]);
    }

    public function editProfile()
    {
        $user = Auth::user();
        return Inertia::render('EditProfile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());
    
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'profile_text' => 'nullable|string|max:140',
            'prof_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($request->hasFile('prof_img')) {
            $file = $request->file('prof_img');
            $path = $file->store('public/profile_images');
            $validatedData['profile_img'] = Storage::url($path);
        }
    
        $result = $user->update($validatedData);
    
        if ($result) {
            return redirect()->route('user.mypage')->with('success_msg', 'プロフィールを更新しました！');
        } else {
            return redirect()->route('user.mypage')->with('error_msg', 'プロフィールの更新に失敗しました。');
        }
    }
    public function withdrawConfirm()
    {
        return Inertia::render('WithdrawConfirm');
    }

    public function withdraw()
    {
        $user = User::find(Auth::id());
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success_msg', '退会が完了しました。');
    }

}
