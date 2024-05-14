import Header from '@/Components/Header';
import React from 'react';
import { User, Auth, PageProps } from '@/types';





// 仮の user データ
const user: User = {
  id: 1,
  name: 'John Doe',
  email: 'johndoe@example.com',
  email_verified_at: 'valid Date object', 
};

const ziggy = {
  location: window.location.href,
};

const auth: Auth = {
  user: user,
};




// コンポーネントで使用する他のコンポーネントやライブラリをインポートする場合はここに記述します

// コンポーネントの定義
const MakeRoom = () => {
  // 状態（state）や他の変数を定義
  // const [state, setState] = React.useState(initialState);
  const hours = [...Array(24).keys()]; // 0から23までの数値を含む配列を生成
  const minutes = [...Array(60).keys()]; // 0から59までの数値を含む配列を生成

  const hourOptions = hours.map(hour => (
    <option key={hour} value={hour}>{hour}</option>
  ));

  const minuteOptions = minutes.map(minute => (
    <option key={minute} value={minute}>{minute}</option>
  ));

  // メソッドやイベントハンドラの定義
  // const handleEvent = (event) => {
  //   // イベントハンドリングのロジック
  // };

  // コンポーネントがレンダリングするUI
  return (
    <>
      <Header auth={auth} ziggy={ziggy} />
      <div className="min-h-screen bg-gray-100 pt-8"> {/* ページ上部に寄せるためのパディングを追加 */}
        <div className="max-w-md w-full bg-white p-8 rounded-lg shadow-lg mx-auto"> {/* フォームの余白を調整 */}
          <form action="" method="GET" className="space-y-6">
            <h1 className="text-2xl font-bold text-gray-900 text-center">部屋を作成する</h1>

            <div>
              <label htmlFor="room-name" className="block text-sm font-medium text-gray-700">
                部屋名 <span className="text-md">(20文字まで)</span><span className="text-red-500"> ※必須</span>
              </label>
              <div className="mt-1 flex items-center justify-between">
                <input type="text" name="room-name" className="w-full p-2 border border-gray-300 rounded-md" placeholder="部屋名を入力" />
                <span className="ml-2 text-sm text-gray-500"><span className="js-show-room-name-count">0</span>/20</span>
              </div>
              <p className="text-red-500 text-sm mt-1">エラー文章表示</p>
            </div>

            <div>
              <label htmlFor="tag" className="block text-sm font-medium text-gray-700">
                タグ <span className="text-md">（スペースで区切る・計20文字まで）</span>
              </label>
              <div className="mt-1 flex items-center justify-between">
                <input type="text" name="tag" className="w-full p-2 border border-gray-300 rounded-md" placeholder="タグを入力" />
                <span className="ml-2 text-sm text-gray-500"><span className="js-show-room-tag-count">0</span>/20</span>
              </div>
            </div>

            <div>
              <label htmlFor="time" className="block text-sm font-medium text-gray-700">
                制限時間 <span className="text-red-500">※必須</span>
              </label>
              <div className="mt-1 flex items-center space-x-2">
                <select name="set-time-hour" className="w-20 p-2 border border-gray-300 rounded-md">
                  {hourOptions}
                </select>
                <span className="text-sm">時間</span>
                <select name="set-time-minute" className="w-20 p-2 border border-gray-300 rounded-md">
                  {minuteOptions}
                </select>
                <span className="text-sm">分</span>
                <button type="button" className="ml-2 px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">おすすめ時間</button>
              </div>
            </div>

            <div>
              <input type="submit" value="作成する" className="w-full px-4 py-2 bg-gray-700 text-white font-bold rounded-md hover:bg-gray-800 cursor-pointer" />
            </div>
          </form>
        </div>
      </div>
    </>
  );
};


// Propsの型定義やデフォルト値の設定が必要な場合はここで設定
  MakeRoom.defaultProps = {
    name: 'World'
  };

// 必要に応じてPropTypesで型チェックを行う
// MyComponent.propTypes = {
//   name: PropTypes.string
// };

// コンポーネントをエクスポート
export default MakeRoom;
