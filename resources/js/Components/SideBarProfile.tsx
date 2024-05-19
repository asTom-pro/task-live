import React, { useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import { UserProfilePageProps } from '@/types';
import usersample from '@/Pages/img/user-sample.svg';
import LoginModal from '@/Components/LoginModal';

interface SideBarProfileProps {
  user: UserProfilePageProps['user'];
  isMyPage: boolean;
  followingUserNum: number;
  followedUserNum: number;
  authUserId: number | null;
}

const SideBarProfile: React.FC<SideBarProfileProps> = ({ user, isMyPage, followingUserNum, followedUserNum, authUserId }) => {
  const [isLoginModalOpen, setIsLoginModalOpen] = useState(false);
  const isFollowing = user.followers?.some(follower => follower.id === authUserId) ?? false;
  const { post } = useForm();

  const handleFollowToggle = () => {
    if (authUserId === null) {
      setIsLoginModalOpen(true);
    } else {
      if (isFollowing) {
        post(route('unfollow', { id: user.id }));
      } else {
        post(route('follow', { id: user.id }));
      }
    }
  };

  return (
    <div className="sub-bar-profile text-center">
      <img src={user.profile_img || usersample} alt="" className="w-36 h-36 rounded-full mx-auto object-cover mt-5" />
      <p className="text-xl font-bold mt-4">{user.name}</p>
      <div className="flex gap-2 justify-center mt-4 text-center text-sm">
        <Link href={`/user/${user.id}/following`} className="block">フォロー<span className="follow-num text-lg">{followingUserNum}</span></Link>
        <Link href={`/user/${user.id}/followers`} className="block">フォロワー<span className="follower-num text-lg">{followedUserNum}</span></Link>
      </div>
      <div className="user-description mt-4">
        <p className="description text-xs">{user.profile_text}</p>
      </div>
      <div className="btn-container mt-4">
        {isMyPage ? (
          <>
            <Link className="btn-profile block bg-slate-500 text-white py-2 px-4 rounded w-full" href="/profile/edit">プロフィール編集</Link>
            <ul className="sub-bar-links mt-4">
              <li className="sub-bar-link">
                <Link href="/withdraw-confirm" className="block text-slate-500">退会</Link>
              </li>
            </ul>
          </>
        ) : (
          <div className="follow-members">
            <button
              className={`btn-follow bg-slate-500 text-white py-2 px-4 rounded w-full ${isFollowing ? 'btn-active' : ''}`}
              onClick={handleFollowToggle}
            >
              {isFollowing ? 'フォロー中' : 'フォロー'}
            </button>
          </div>
        )}
      </div>
      <LoginModal isOpen={isLoginModalOpen} onClose={() => setIsLoginModalOpen(false)} />
    </div>
  );
};

export default SideBarProfile;
