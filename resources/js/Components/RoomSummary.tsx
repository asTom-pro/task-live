import React, { useEffect, useState } from 'react';
import { usePage, Link } from '@inertiajs/react';
import { PageProps, Room } from '@/types';
import usersample from '@/Pages/img/user-sample.svg';
import Clock from '@/Components/Clock';
import axios from 'axios';
import styles from '../../css/components/_roomsummaryclock.module.css';

interface RoomSummaryProps {
  rooms: Room[];
  onTagSearch: (tag: string) => void;
}

const RoomSummary: React.FC<RoomSummaryProps> = ({ rooms, onTagSearch }) => {
  const { auth } = usePage<PageProps>().props;
  const [userCounts, setUserCounts] = useState<{ [roomId: number]: number }>({});

  useEffect(() => {
    const fetchUserCounts = async () => {
      const counts: { [roomId: number]: number } = {};
      for (const room of rooms) {
        try {
          const response = await axios.get(`/room/${room.id}/user-count`);
          counts[room.id] = response.data.user_count;
        } catch (error) {
          console.error('Error fetching user count:', error);
        }
      }
      setUserCounts(counts);
    };

    fetchUserCounts();
    const interval = setInterval(fetchUserCounts, 10000);

    return () => clearInterval(interval);
  }, [rooms]);

  if (!rooms || rooms.length === 0) {
    return <div className='text-center'>Loading...</div>;
  }

  const calculateRemainTime = (durationInSeconds: number, createdAt: string) => {
    const now = new Date();
    const createdDate = new Date(createdAt);
    const elapsedTimeInMinutes = Math.floor((now.getTime() - createdDate.getTime()) / 1000 / 60);
    const durationInMinutes = durationInSeconds / 60;
    return Math.max(0, durationInMinutes - elapsedTimeInMinutes);
  };

  const userRooms = auth?.user ? rooms.filter(room => room.user && room.user.id === auth.user?.id) : [];
  const activeRooms = userRooms.filter(room => calculateRemainTime(Number(room.time_limit), room.created_at) > 0);

  return (
    <div className="w-full p-10">
      {auth?.user && activeRooms.length > 0 && (
        <div className="mb-5">
          <p className="font-semibold">作成した部屋がまだ開いています</p>
          {activeRooms.map(room => {
            const remainTime = calculateRemainTime(Number(room.time_limit), room.created_at);
            const userCount = userCounts[room.id] || 0;
            return (
              <Link key={room.id} href={`/room/${room.id}`} className="block mt-3">
                <div className="bg-opacity-10 border border-gray-200 box-border px-6 py-3 transition duration-100 flex justify-between items-center bg-gray-50 hover:bg-gray-100">
                  <div className="w-full">
                    <h2 className="font-bold text-5xl min-h-100 box-border w-full">{room.name}</h2>
                    <div className='flex justify-between items-end mt-10'>
                      <div>
                        現在<span className="text-5xl mx-3">{userCount}</span>人
                      </div>
                      <div>
                        {room.tags && room.tags.map(tag => (
                          <Link
                          key={tag.id} 
                          className="text-sm mr-3 box-border px-3 py-1 bg-slate-500 text-white rounded-lg inline-block" 
                          href={`/tags/${tag.id}`}
                          onClick={(e) => {
                            e.preventDefault();
                            console.log('Tag clicked:', tag.name);
                            onTagSearch(tag.name);
                          }}
                          >
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
        const userCount = userCounts[room.id] || 0;
        return (
          <Link key={room.id} href={`/room/${room.id}`} className="block mt-3">
            <div className="bg-opacity-10 border border-gray-200 box-border px-6 py-3 transition duration-100 flex justify-between items-center bg-gray-50 hover:bg-gray-100">
              <div className="w-full">
                <h2 className="font-bold text-5xl min-h-100 box-border w-full">{room.name}</h2>
                <div className='flex justify-between items-end mt-10'>
                  <div>
                    現在<span className="text-5xl mx-3">{userCount}</span>人
                  </div>
                  <div>
                    {room.tags && room.tags.map(tag => (
                      <Link 
                      key={tag.id} 
                      className="text-sm mr-3 box-border px-3 py-1 bg-slate-500 text-white rounded-lg inline-block" 
                      href={`/tags/${tag.id}`}
                      onClick={(e) => {
                        e.preventDefault();
                        console.log('Tag clicked:', tag.name);
                        onTagSearch(tag.name);
                      }}                      >
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
                        <img src={usersample} alt="ゲストのプロフィール画像" className="h-12 w-12 rounded-full mr-1 object-cover" />
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
