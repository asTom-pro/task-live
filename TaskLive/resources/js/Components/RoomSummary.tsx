import React, { useEffect, useState } from 'react';
import logo from '../Pages/img/logo.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';
import { usePage, Link } from '@inertiajs/react';
import { PageProps, Room } from '@/types';
import usersample from '@/Pages/img/user-sample.svg';
import Clock from '@/Components/Clock';
import styles from '../../css/components/_roomsummaryclock.module.css';

interface RoomSummaryProps {
  rooms: Room[];
}

const RoomSummary: React.FC<RoomSummaryProps> = ({ rooms }) => {
  const { auth } = usePage<PageProps>().props;

  if (!rooms || rooms.length === 0) {
    return <div className='text-center'>Loading...</div>; // データが読み込まれるまでの間、ローディング表示を行う
  }

  const calculateRemainTime = (durationInSeconds: number, createdAt: string) => {
    const now = new Date();
    const createdDate = new Date(createdAt);
    const elapsedTimeInMinutes = Math.floor((now.getTime() - createdDate.getTime()) / 1000 / 60); // 経過時間を分単位で計算
    const durationInMinutes = durationInSeconds / 60; // 秒単位のdurationを分単位に変換
    return Math.max(0, durationInMinutes - elapsedTimeInMinutes);
  };

  if (!auth || !auth.user) {
    return <div className='text-center'>ユーザー情報が取得できませんでした。</div>;
  }

  // 現在のユーザーが作成した部屋をフィルタリング
  const userRooms = auth?.user ? rooms.filter(room => room.user && room.user.id === auth.user?.id) : [];

  // 残り時間のある部屋をフィルタリング
  const activeRooms = userRooms.filter(room => calculateRemainTime(Number(room.time_limit), room.created_at) > 0);

  return (
    <div className="w-full p-10">
      {activeRooms.length > 0 && (
        <div className="mb-5">
          <p className="font-semibold">作成した部屋がまだ開いています</p>
          {activeRooms.map(room => {
            const remainTime = calculateRemainTime(Number(room.time_limit), room.created_at);
            return (
              <Link key={room.id} href={`/room/${room.id}`} className="block mt-3">
                <div className="bg-opacity-10 border border-gray-200 box-border px-6 py-3 transition duration-100 flex justify-between items-center bg-gray-50 hover:bg-gray-100">
                  <div className="w-full">
                    <h2 className="font-bold text-5xl min-h-100 box-border w-full">{room.name}</h2>
                    <div className='flex justify-between items-end mt-10'>
                      <div>
                        現在<span className="text-5xl mx-3">3</span>人
                      </div>
                      <div>
                        {room.tags && room.tags.map(tag => (
                          <Link key={tag.id} className="text-sm mr-3 box-border px-3 py-1 bg-slate-500 text-white rounded-lg inline-block" href={`/tags/${tag.id}`}>
                            {tag.name}
                          </Link>
                        ))}
                      </div>
                      <div className="flex items-center">
                        {room.user ? (
                          <Link href={`/user/${room.user.id}`} className="flex items-center">
                            <img src={room.user.profile_img || usersample} alt={`${room.user.name}のプロフィール画像`} className="h-12 w-12 rounded-full mr-1 object-cover" />
                            <span className="user-name">{room.user.name || '名称未設定'}</span>
                          </Link>
                        ) : (
                          <div className="flex items-center">
                            <img src={usersample} alt="ゲストのプロフィール画像" className="h-12 w-12 rounded-full mr-1" />
                            <span className="user-name">ゲスト</span>
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                  <div className='ml-3'>
                    <Clock duration={Number(room.time_limit)} created_at={String(room.created_at)} clockWidth={styles.clock} />
                    <div className="ml-3 text-center">
                      <span className="text-2xl mt-3">{remainTime}</span>/{room.time_limit / 60}分
                    </div>
                  </div>
                </div>
              </Link>
            );
          })}
        </div>
      )}
      <p className="font-semibold">参加する部屋を選択</p>
      {rooms.map(room => {
        const remainTime = calculateRemainTime(Number(room.time_limit), room.created_at);
        return (
          <Link key={room.id} href={`/room/${room.id}`} className="block mt-3">
            <div className="bg-opacity-10 border border-gray-200 box-border px-6 py-3 transition duration-100 flex justify-between items-center bg-gray-50 hover:bg-gray-100">
              <div className="w-full">
                <h2 className="font-bold text-5xl min-h-100 box-border w-full">{room.name}</h2>
                <div className='flex justify-between items-end mt-10'>
                  <div>
                    現在<span className="text-5xl mx-3">2</span>人
                  </div>
                  <div>
                    {room.tags && room.tags.map(tag => (
                      <Link key={tag.id} className="text-sm mr-3 box-border px-3 py-1 bg-slate-500 text-white rounded-lg inline-block" href={`/tags/${tag.id}`}>
                        {tag.name}
                      </Link>
                    ))}
                  </div>
                  <div className="flex items-center">
                    {room.user ? (
                      <Link href={`/user/${room.user.id}`} className="flex items-center">
                        <img src={room.user.profile_img || usersample} alt={`${room.user.name}のプロフィール画像`} className="h-12 w-12 rounded-full mr-1 object-cover" />
                        <span className="user-name">{room.user.name || '名称未設定'}</span>
                      </Link>
                    ) : (
                      <div className="flex items-center">
                        <img src={usersample} alt="ゲストのプロフィール画像" className="h-12 w-12 rounded-full mr-1" />
                        <span className="user-name">ゲスト</span>
                      </div>
                    )}
                  </div>
                </div>
              </div>
              <div className='ml-3'>
                <Clock duration={Number(room.time_limit)} created_at={String(room.created_at)} clockWidth={styles.clock} />
                <div className="ml-3 text-center">
                  <span className="text-2xl mt-3">{remainTime}</span>/{room.time_limit / 60}分
                </div>
              </div>
            </div>
          </Link>
        );
      })}
    </div>
  );
};

export default RoomSummary;
