// Header.tsx
import React from 'react';

const RoomSearch = () => {
  return (
    <div className="p-5">
        <div className="">
          <form action="" method="GET">
            <label className='flex flex-col'>
              タグ
              <select name="tag" className="select rounded">
                <option value="">選択してください。</option>
                <option value="数学">数学</option>
              </select>
            </label>
            <div className="mt-3 flex justify-end">
              <input type="submit" value="検索" className="mt-3 text-white bg-gray-500 px-2 py-1 ml-auto rounded" />
            </div>
          </form>
        </div>
    </div>
  );
};

export default RoomSearch;