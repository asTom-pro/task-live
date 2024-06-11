import React, { useEffect, useState } from 'react';
import { usePage, Link } from '@inertiajs/react';
import { PageProps, Room, PaginatedResponse } from '@/types';
import usersample from '@/Pages/img/user-sample.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faDoorOpen } from '@fortawesome/free-solid-svg-icons';
import Clock from '@/Components/Clock';
import Pagination from '@/Components/Pagination';
import axios from 'axios';
import styles from '../../css/components/_roomsummaryclock.module.css';
import roomBg from '@/Pages/img/room-bg.png'

interface RoomSummaryProps {
  rooms: PaginatedResponse<Room>;
  onTagSearch: (tag: string) => void;
}

const RoomSummary: React.FC<RoomSummaryProps> = ({ rooms, onTagSearch }) => {
  const { auth } = usePage<PageProps>().props;
  const [userCounts, setUserCounts] = useState<{ [roomId: number]: number }>({});
  const [userPositions, setUserPositions] = useState<{ [roomId: number]: { [userId: number]: { top: string, left: string } } }>({});
  const [hoveredRoomId, setHoveredRoomId] = useState<number | null>(null);



  useEffect(() => {
    const fetchUserCounts = async () => {
      const counts: { [roomId: number]: number } = {};
      for (const room of rooms.data) {
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
  }, [rooms.data]);


  useEffect(() => {
    const positions: { [roomId: number]: { [userId: number]: { top: string, left: string } } } = {};
    rooms.data.forEach(room => {
      positions[room.id] = {};
      room.users.forEach(user => {
        positions[room.id][user.id] = {
          top: `${Math.random() * 80}%`,
          left: `${Math.random() * 80}%`
        };
      });
    });
    setUserPositions(positions);
  }, [rooms.data]);
  

  if (!rooms.data || rooms.data.length === 0) {
    return <div className='text-center'>Loading...</div>;
  }

  const calculateRemainTime = (durationInSeconds: number, createdAt: string) => {
    const now = new Date();
    const createdDate = new Date(createdAt);
    const elapsedTimeInMinutes = Math.floor((now.getTime() - createdDate.getTime()) / 1000 / 60);
    const durationInMinutes = durationInSeconds / 60;
    return Math.max(0, Math.floor(durationInMinutes - elapsedTimeInMinutes));
  };

  const userRooms = auth?.user ? rooms.data.filter(room => room.user && room.user.id === auth.user?.id) : [];
  const activeRooms = userRooms.filter(room => calculateRemainTime(Number(room.time_limit), room.created_at) > 0);

  return (
    <div className="w-full lg:px-10 py-10">
      {auth?.user && activeRooms.length > 0 && (
        <div className="mb-5">
          <p className="font-semibold px-4">作成した部屋がまだ開いています</p>
          <div className="flex flex-wrap px-2">
            {activeRooms.map(room => {
              const remainTime = calculateRemainTime(Number(room.time_limit), room.created_at);
              const timeLimitInMinutes = Math.floor(room.time_limit / 60);
              const userCount = userCounts[room.id] || 0;
              return (
                <Link key={room.id} href={`/room/${room.id}`} className="w-full md:w-1/2 xl:w-1/4 px-2 mt-3">
                  <div className='flex flex-col h-full border border-gray-200'>
                    <div className='relative w-full flex-grow' style={{ height: '180px' }}>
                      <img src={roomBg} className='w-full h-full object-cover absolute inset-0 z-0' alt="" />
                      <div className='absolute right-3 top-3'>
                        <Clock duration={Number(room.time_limit)} created_at={String(room.created_at)} clockStyles={{ clock: styles.clock, clockHand: styles['clock-hand'] }} />
                        <div className="text-center">
                          <span className="text-2xl mt-3">{remainTime}</span>/{timeLimitInMinutes}分
                        </div>
                      </div>
                      <div className='absolute right-3 bottom-0'>
                        { room.is_room_expired ?  '終了' : '現在'}
                        <span className="text-3xl mx-3">{userCount}</span>人
                      </div>
                      {room.users.map(user => (
                        <img 
                          key={user.id} 
                          src={user.profile_img || usersample} 
                          alt={`User ${user.id} Profile`} 
                          className="absolute w-10 h-10 rounded-full bg-white object-cover" 
                          style={userPositions[room.id] && userPositions[room.id][user.id] ? userPositions[room.id][user.id] : { top: '0%', left: '0%' }} 
                          onError={(e) => (e.currentTarget.src = usersample)} 
                        />
                      ))}
                    </div>
                    <div className="relative bg-opacity-10 box-border px-6 py-3 transition duration-100 flex justify-between items-center bg-gray-50 hover:bg-gray-100">
                      <div className="w-full">
                        <h2 className="font-bold text-xl min-h-100 box-border w-full break-words max-w-full min-h-[2.4em] leading-[1.2]">{room.name}</h2>
                        <div className='flex justify-between'>
                          <div className='min-h-[3em]'>
                            {room.tags && room.tags.map(tag => (
                              <Link 
                                key={tag.id} 
                                className="text-xs mr-3 box-border px-1 py-px bg-slate-500 text-white rounded-md inline-block" 
                                href={`/tags/${tag.id}`}
                                onClick={(e) => {
                                  e.preventDefault();
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
                                <img 
                                  src={room.user.profile_img || usersample} 
                                  alt={`${room.user.name}のプロフィール画像`} 
                                  className="h-10 w-10 rounded-full mr-1 object-cover"
                                  onError={(e) => (e.currentTarget.src = usersample)} 
                                />
                                <span className="user-name text-xs whitespace-nowrap">{room.user.name || '名称未設定'}</span>
                              </Link>
                            ) : (
                              <div className="flex items-center">
                                <img src={usersample} alt="ゲストのプロフィール画像" className="h-10 w-10 rounded-full mr-1 object-cover" />
                                <span className="user-name text-xs whitespace-nowrap">ゲスト</span>
                              </div>
                            )}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </Link>
              );
            })}
          </div>
        </div>

      )}
      <p className="font-semibold px-4">参加する部屋を選択</p>
      <div className='flex flex-wrap'>
        {rooms.data.map(room => {
          const remainTime = calculateRemainTime(Number(room.time_limit), room.created_at);
          const timeLimitInMinutes = Math.floor(room.time_limit / 60);
          const userCount = userCounts[room.id] || 0;
          return (
            <Link 
            key={room.id} 
            href={`/room/${room.id}`} 
            className="w-full md:w-1/2 xl:w-1/4  px-2 xl:px-4 mt-3 relative"
            onMouseEnter={() => setHoveredRoomId(room.id)}
            onMouseLeave={() => setHoveredRoomId(null)}
            >
              <div className='flex flex-col h-full border border-gray-200'>
                <div className='relative w-full flex-grow' style={{ height: '180px' }}>
                    <img src={roomBg} className='w-full h-full object-cover absolute inset-0 z-0' alt="" />
                    <div className='absolute right-3 top-3'>
                      <Clock duration={Number(room.time_limit)} created_at={String(room.created_at)} clockStyles={{ clock: styles.clock, clockHand: styles['clock-hand'] }} />
                      <div className="text-center">
                        <span className="text-2xl mt-3">{remainTime}</span>/{timeLimitInMinutes}分
                      </div>
                    </div>
                    <div className='absolute right-3 bottom-0'>
                      { room.is_room_expired ?  '終了' : '現在'}
                      <span className="text-3xl mx-3">{userCount}</span>人
                    </div>
                    {room.users.map(user => (
                      <img 
                      key={user.id} 
                      src={user.profile_img || usersample} 
                      alt={`User ${user.id} Profile`} 
                      className="absolute w-10 h-10 rounded-full bg-white object-cover" 
                      style={userPositions[room.id] && userPositions[room.id][user.id] ? userPositions[room.id][user.id] : { top: '0%', left: '0%' }} 
                      onError={(e) => (e.currentTarget.src = usersample)} 
                       />
                      // <img key={user.id} src={user.profile_img || usersample} alt={`User ${user.id} Profile`} className="absolute w-10 h-10 rounded-full bg-white object-cover" style={{ top: `${Math.random() * 80}%`, left: `${Math.random() * 80}%` }} />
                    ))}
                </div>
                <div className="relative bg-opacity-10 box-border px-6 py-3 transition duration-100 flex justify-between items-center bg-gray-50 hover:bg-gray-100">
                  <div className="w-full">
                    <h2 className="font-bold text-xl min-h-100 box-border w-full break-words max-w-full min-h-[2.4em] leading-[1.2]">{room.name}</h2>
                    <div className='flex justify-between'>
                      <div className='min-h-[3em]'>
                        {room.tags && room.tags.map(tag => (
                          <Link 
                          key={tag.id} 
                          className="text-xs	mr-3 box-border px-1 py-px bg-slate-500 text-white rounded-md inline-block" 
                          href={`/tags/${tag.id}`}
                          onClick={(e) => {
                            e.preventDefault();
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
                            <img 
                            src={room.user.profile_img || usersample} 
                            alt={`${room.user.name}のプロフィール画像`} 
                            className="h-10 w-10 rounded-full mr-1 object-cover"
                            onError={(e) => (e.currentTarget.src = usersample)} 
                             />
                            <span className="user-name text-xs whitespace-nowrap">{room.user.name || '名称未設定'}</span>
                          </Link>
                        ) : (
                          <div className="flex items-center">
                            <img src={usersample} alt="ゲストのプロフィール画像" className="h-10 w-10 rounded-full mr-1 object-cover" />
                            <span className="user-name text-xs whitespace-nowrap">ゲスト</span>
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div 
                className={`absolute top-0 left-0 z-50 w-full h-full px-2 xl:px-4`}
              >
                <div 
                className={`w-full flex items-center justify-center transition-opacity duration-100 ${hoveredRoomId === room.id ? 'opacity-100' : 'opacity-0'}`}
                style={{
                  height: '100%',
                  backgroundColor: 'rgba(108, 122, 137, 0.95)',
                }}
                >
                  {hoveredRoomId === room.id && (
                    <div className='flex flex-col items-center'>
                      <FontAwesomeIcon icon={faDoorOpen} className="" size="2x" color="#f0f8ff" />
                      <span className='text-slate-100 font-bold'>入室する</span>
                    </div>
                  )}
                </div>
              </div>

            </Link>
          );
        })}
        <Pagination links={rooms.links} />
      </div>
    </div>
  );
};

export default RoomSummary;
