import React, { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { router, Head } from '@inertiajs/react';

import Header from '@/Components/Header'; 
import RoomSummary from '@/Components/RoomSummary';
import RoomSearch from '@/Components/RoomSearch';

import { Room, PageProps, PaginatedResponse } from '@/types';

const Top: React.FC = () => {
  const title = 'ホーム';
  useEffect(() => {
    document.title = title;
  }, [title]);

  const { auth, ziggy, tags, search, rooms } = usePage<PageProps>().props;

  // rooms が PaginatedResponse<Room> 型であることを確認
  const initialRooms: PaginatedResponse<Room> = rooms ?? {
    data: [],
    links: [],
    meta: { current_page: 1, last_page: 1, per_page: 10, total: 0 },
  };

  const [filteredRooms, setFilteredRooms] = useState<PaginatedResponse<Room>>(initialRooms);
  const [selectedTag, setSelectedTag] = useState<string>('');

  useEffect(() => {
    if (search) {
      const filteredData = initialRooms.data.filter((room: Room) =>
        room.name.toLowerCase().includes(search.toLowerCase())
      );
      setFilteredRooms({ ...initialRooms, data: filteredData });
    } else {
      setFilteredRooms(initialRooms);
    }
  }, [search, initialRooms]);

  const handleTagSearch = async (tag: string) => {
    setSelectedTag(tag);
    try {
      router.get('/', { tag }, {
        preserveState: true, 
        onSuccess: (page) => {
          const newRooms = page.props.rooms as PaginatedResponse<Room>;
          setFilteredRooms(newRooms);
        },
        onError: (errors) => {
          console.error('Error fetching rooms:', errors);
        }
      });
    } catch (error) {
      console.error('Unexpected error:', error);
    }
  };

  return (
    <>
      <Head title={title} />
      <div>
        <Header auth={auth} ziggy={ziggy} />
      </div>
      <div className='bg-slate-100 p-5 lg:p-10 min-h-screen'>
        <div className='w-full mx-auto'>
          <div className="bg-white">
            <RoomSummary rooms={filteredRooms} onTagSearch={handleTagSearch} />
          </div>
        </div>
      </div>
    </>
  );
};

export default Top;
