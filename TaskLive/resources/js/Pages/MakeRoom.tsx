import Header from '@/Components/Header';
import React, { useState, useEffect } from 'react';
import { useForm, usePage, Head } from '@inertiajs/react';
import { PageProps } from '@/types';

const MakeRoom = () => {
  const title = '部屋作成';
  useEffect(() => {
    document.title = title;
  }, [title]);
  const { auth, ziggy } = usePage<PageProps>().props;

  const { data, setData, post, processing, errors } = useForm({
    room_name: '',
    tag: '',
    set_time_hour: 0,
    set_time_minute: 0,
  });

  const [showDropdown, setShowDropdown] = useState(false);
  const [selectedHour, setSelectedHour] = useState<number | null>(null);
  const [selectedMinute, setSelectedMinute] = useState<number | null>(null);

  const hours = [...Array(24).keys()];
  const minutes = [...Array(60).keys()];

  const hourOptions = hours.map(hour => (
    <option key={hour} value={hour}>{hour}</option>
  ));
  const minuteOptions = minutes.map(minute => (
    <option key={minute} value={minute}>{minute}</option>
  ));

  const handleRecommendedTime = (hour: number, minute: number) => {
    console.log('hour:');
    console.log(hour);
    setSelectedHour(hour);
    setSelectedMinute(minute);
    setShowDropdown(false);
  };

  useEffect(() => {
    if (selectedHour !== null) {
      setData('set_time_hour', selectedHour);
    }
    if (selectedMinute !== null) {
      setData('set_time_minute', selectedMinute);
    }
  }, [selectedHour, selectedMinute]);

  const submit: React.FormEventHandler = (e) => {
    e.preventDefault();
    post(route('room.store'));
  };

  return (
    <>
      <Head title={title} />
      <Header auth={auth} ziggy={ziggy} />
      <div className="min-h-screen bg-gray-100 pt-8">
        <div className="max-w-md w-full bg-white p-8 rounded-lg shadow-lg mx-auto">
          <form onSubmit={submit} className="space-y-6">
            <h1 className="text-2xl font-bold text-gray-900 text-center">部屋を作成する</h1>

            <div>
              <label htmlFor="room-name" className="block text-sm font-medium text-gray-700">
                部屋名 <span className="text-md">(20文字まで)</span><span className="text-red-500"> ※必須</span>
              </label>
              <div className="mt-1 flex items-center justify-between">
                <input
                  type="text"
                  name="room_name"
                  className="w-full p-2 border border-gray-300 rounded-md"
                  placeholder="部屋名を入力"
                  value={data.room_name}
                  onChange={e => setData('room_name', e.target.value)}
                />
                <span className="ml-2 text-sm text-gray-500"><span className="js-show-room-name-count">{data.room_name.length}</span>/20</span>
              </div>
              {errors.room_name && <p className="text-red-500 text-sm mt-1">{errors.room_name}</p>}
            </div>

            <div>
              <label htmlFor="tag" className="block text-sm font-medium text-gray-700">
                タグ <span className="text-md">（スペースで区切る・計20文字まで）</span>
              </label>
              <div className="mt-1 flex items-center justify-between">
                <input
                  type="text"
                  name="tag"
                  className="w-full p-2 border border-gray-300 rounded-md"
                  placeholder="タグを入力"
                  value={data.tag}
                  onChange={e => setData('tag', e.target.value)}
                />
                <span className="ml-2 text-sm text-gray-500"><span className="js-show-room-tag-count">{data.tag.length}</span>/20</span>
              </div>
              {errors.tag && <p className="text-red-500 text-sm mt-1">{errors.tag}</p>}
            </div>

            <div>
              <label htmlFor="time" className="block text-sm font-medium text-gray-700">
                制限時間 <span className="text-red-500">※必須</span>
              </label>
              <div className="mt-1 flex items-center space-x-2">
                <select
                  name="set_time_hour"
                  className="w-20 p-2 border border-gray-300 rounded-md"
                  value={selectedHour !== null ? selectedHour : ''}
                  onChange={e => setSelectedHour(parseInt(e.target.value))}
                >
                  {hourOptions}
                </select>
                <span className="text-sm">時間</span>
                <select
                  name="set_time_minute"
                  className="w-20 p-2 border border-gray-300 rounded-md"
                  value={selectedMinute !== null ? selectedMinute : ''}
                  onChange={e => setSelectedMinute(parseInt(e.target.value))}
                >
                  {minuteOptions}
                </select>
                <span className="text-sm">分</span>
                <div className="relative">
                  <button
                    type="button"
                    className="ml-2 px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800"
                    onClick={() => setShowDropdown(!showDropdown)}
                  >
                    おすすめ時間
                  </button>
                  {showDropdown && (
                    <div className="absolute mt-2 bg-white border border-gray-300 rounded-md shadow-lg z-10">
                      <button
                        type="button"
                        className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        onClick={() => handleRecommendedTime(0, 25)}
                      >
                        ポモドーロ (25分)
                      </button>
                      <button
                        type="button"
                        className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        onClick={() => handleRecommendedTime(0, 15)}
                      >
                        ショートブレイク (15分)
                      </button>
                      <button
                        type="button"
                        className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        onClick={() => handleRecommendedTime(1, 0)}
                      >
                        ロングブレイク (1時間)
                      </button>
                    </div>
                  )}
                </div>
              </div>
              {(errors.set_time_hour || errors.set_time_minute) && <p className="text-red-500 text-sm mt-1">時間設定に誤りがあります。</p>}
            </div>

            <div>
              <input
                type="submit"
                value="作成する"
                className="w-full px-4 py-2 bg-gray-700 text-white font-bold rounded-md hover:bg-gray-800 cursor-pointer"
                disabled={processing}
              />
            </div>
          </form>
        </div>
      </div>
    </>
  );
};

MakeRoom.defaultProps = {
  name: 'World'
};

export default MakeRoom;
