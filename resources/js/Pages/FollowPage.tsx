import React, {useEffect} from 'react';
import { usePage, Head } from '@inertiajs/react';
import Header from '@/Components/Header';
import SideBarProfile from '@/Components/SideBarProfile';
import { UserProfilePageProps } from '@/types';
import BaseLayout from '@/Layouts/BaseLayout';


const FollowPage: React.FC = () => {

  const title = 'フォロー一覧';
  useEffect(() => {
    document.title = title;
  }, [title]);

  const { auth, ziggy, followingUsers, followers, followingUserNum, followedUserNum, isMyPage, user, totalRoomTime, url } = usePage<UserProfilePageProps>().props;

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
      <Head title={title} />
      <BaseLayout auth={auth} ziggy={ziggy}>
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
                    <span className="text-6xl mx-2">{totalRoomTime | 0}</span>
                    <span className="text-2xl">時間</span>
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
      </BaseLayout>
    </>
  );
};

export default FollowPage;
