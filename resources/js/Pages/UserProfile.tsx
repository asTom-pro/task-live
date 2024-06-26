import React, { useEffect } from 'react';
import { usePage, Head } from '@inertiajs/react';
import { UserProfilePageProps } from '@/types';
import Header from '@/Components/Header';
import SideBarProfile from '@/Components/SideBarProfile';
import TaskList from '@/Components/TaskList';
import BaseLayout from '@/Layouts/BaseLayout';

const UserProfile: React.FC = () => {
  const title = 'ユーザーページ';
  useEffect(() => {
    document.title = title;
  }, [title]);
  const { auth, ziggy, rooms, tags, user, isMyPage, userRooms, joinedRooms, totalRoomTime, followingUserNum, followedUserNum, url } = usePage<UserProfilePageProps>().props;

  const formatTime = (seconds: number) => {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    return `${hours}時間 ${minutes}分`;
  };

  return (
    <>
      <Head title={title} />
      <BaseLayout auth={auth} ziggy={ziggy}>
        <div className="bg-slate-100 p-10 min-h-screen">
          <div className="max-w-screen-lg mx-auto lg:flex gap-5 justify-between">
            <div className="w-full lg:w-3/4 bg-white p-6 rounded-lg shadow-md">
              <div className="user-profile">
                <div>
                  {isMyPage && <TaskList authUserId={auth.user?.id ?? null} />}
                </div>
                <div className="text-center mt-8">
                  <p className="text-4xl">総学習時間</p>
                  <div className="flex justify-center items-center">
                    <span className="text-6xl mx-2">{totalRoomTime}</span>
                    <span className="text-2xl">時間</span>
                  </div>
                </div>

                <div className="mt-8">
                  <p className="text-2xl text-center">
                    今まで作成した部屋
                    <span className="text-3xl border border-black px-2 py-1 float-right">
                      {userRooms.length}部屋
                    </span>
                  </p>
                  <div className="mt-4">
                    {userRooms.map((room) => (
                      <div key={room.id} className="p-4 border-b last:border-b-0">
                        <h4 className="text-xl font-semibold">{room.name}</h4>
                        <p>制限時間: {formatTime(room.time_limit)}</p>
                      </div>
                    ))}
                  </div>
                </div>

                <div className="mt-8">
                  <p className="text-2xl text-center">
                    今まで参加した部屋
                    <span className="text-3xl border border-black px-2 py-1 float-right">
                      {joinedRooms.length}部屋
                    </span>
                  </p>
                  <div className="mt-4">
                    {joinedRooms.map((room) => (
                      <div key={room.id} className="p-4 border-b last:border-b-0">
                        <h4 className="text-xl font-semibold">{room.name}</h4>
                        <p>制限時間: {formatTime(room.time_limit)}</p>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </div>
            <div className="w-full lg:w-1/4 bg-white p-6 rounded-lg shadow-md mt-5 lg:mt-0">
              <SideBarProfile 
                user={user} 
                isMyPage={isMyPage} 
                followingUserNum={followingUserNum} 
                followedUserNum={followedUserNum} 
                authUserId={auth.user?.id ?? null}
              />
            </div>
          </div>
        </div>
      </BaseLayout>
    </>
  );
};

export default UserProfile;
