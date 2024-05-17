import React, { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { router } from '@inertiajs/react';


import Header from '@/Components/Header'; 
import RoomSummary from '@/Components/RoomSummary';
import RoomSearch from '@/Components/RoomSearch';

import { Room, PageProps } from '@/types';



// コンポーネントで使用する他のコンポーネントやライブラリをインポートする場合はここに記述します

// コンポーネントの定義
const Top: React.FC = () => {
  // 状態（state）や他の変数を定義
  // const [state, setState] = React.useState(initialState);
  const { auth, ziggy, tags, search, rooms = [] } = usePage<PageProps>().props;
  const [filteredRooms, setFilteredRooms] = useState<Room[]>(rooms as Room[]);
  const [selectedTag, setSelectedTag] = useState<string>('');

  useEffect(() => {
    if (search) {
      setFilteredRooms(
        rooms.filter((room) =>
          room.name.toLowerCase().includes(search.toLowerCase())
        )
      );
    } else {
      setFilteredRooms(rooms as Room[]);
    }
  }, [search, rooms]);

  const handleTagSearch = async (tag: string) => {
    setSelectedTag(tag);
    try {
      router.get('/', { tag }, {
        preserveState: true, 
        onSuccess: (page) => {
          const newRooms = page.props.rooms as Room[] ?? [];
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



  // メソッドやイベントハンドラの定義
  // const handleEvent = (event) => {
  //   // イベントハンドリングのロジック
  // };


  // コンポーネントがレンダリングするUI
  return (
    <>
      <div>
      <Header auth={auth} ziggy={ziggy} />
        {/* <h1>Hello, {props.name}!</h1> */}
        {/* <h1>Hello!</h1> */}
        {/* JSXの中で他のコンポーネントを呼び出したり、条件分岐、リスト処理を行う */}
      </div>
      <div className='bg-slate-100 p-10 min-h-screen'>
        <div className='max-w-screen-lg mx-auto flex gap-5 justify-between'>
          <div className="w-3/4 bg-white">
            <RoomSummary rooms={filteredRooms} />
          </div>
          <div className="w-1/4 bg-white">
            <RoomSearch tags={tags ?? []} onTagSearch={handleTagSearch} selectedTag={selectedTag} />
          </div>
        </div>
      </div>
    </>
  );
};

// Propsの型定義やデフォルト値の設定が必要な場合はここで設定

// 必要に応じてPropTypesで型チェックを行う
// MyComponent.propTypes = {
//   name: PropTypes.string
// };

// コンポーネントをエクスポート
export default Top;
