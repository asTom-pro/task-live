import React from 'react';
import { usePage } from '@inertiajs/react';
import Header from '@/Components/Header';
import SideBarProfile from '@/Components/SideBarProfile';
import { UserProfilePageProps } from '@/types';

const FollowPage: React.FC = () => {
  const { auth, ziggy, followingUsers, followers, followingUserNum, followedUserNum, isMyPage, user } = usePage<UserProfilePageProps>().props;

  const renderUsers = (users: UserProfilePageProps['user'][]) => {
    if (!users || users.length === 0) {
      return <p>ユーザーが見つかりません。</p>;
    }

    return users.map((followUser) => (
      <div key={followUser.id} className="p-4 border-b last:border-b-0">
        <h4 className="text-xl font-semibold">{followUser.name}</h4>
      </div>
    ));
  };

  return (
    <>
      <div>
        <Header auth={auth} ziggy={ziggy} />
      </div>
      <div className="bg-slate-100 p-10 min-h-screen">
        <div className="max-w-screen-lg mx-auto lg:flex gap-5 justify-between">
          <div className="w-full lg:w-3/4 bg-white p-6 rounded-lg shadow-md">
            <div className="user-profile">
              <h1 className="text-2xl font-bold">
                {isMyPage ? "マイページ" : `${user.name}さんのフォロー一覧`}
              </h1>
              <div className="text-center mt-8">
                <p className="text-4xl">総学習時間</p>
                <div className="flex justify-center items-center">
                  <span className="text-6xl mx-2">{followingUserNum}</span>
                  <span className="text-2xl">人</span>
                </div>
              </div>
              <div className="mt-8">
                <h2 className="text-xl font-bold">フォローしているユーザー</h2>
                {renderUsers(followingUsers)}
              </div>
              <div className="mt-8">
                <h2 className="text-xl font-bold">フォロワー</h2>
                {renderUsers(followers)}
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
    </>
  );
};

export default FollowPage;
