import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faShareAlt } from '@fortawesome/free-solid-svg-icons';
import Header from '@/Components/Header'; 
import RoomSummary from '@/Components/RoomSummary';
import RoomSearch from '@/Components/RoomSearch';
import { User, Auth, PageProps } from '@/types';

import '@/../css/components/_roomclock.css'


// コンポーネントで使用する他のコンポーネントやライブラリをインポートする場合はここに記述します

const user: User = '';

const ziggy = {
  location: window.location.href,
};

const auth: Auth = {
  user: user,
};

// コンポーネントの定義
const Top = () => {
  // 状態（state）や他の変数を定義
  // const [state, setState] = React.useState(initialState);

  // メソッドやイベントハンドラの定義
  // const handleEvent = (event) => {
  //   // イベントハンドリングのロジック
  // };

    


  // コンポーネントがレンダリングするUI
  return (
    <>
      <Header auth={auth} ziggy={ziggy} />
      <div className="bg-slate-100 p-10 h-screen">
          <div className="max-w-screen-lg mx-auto justify-center">
            <div className="bg-white">
            <FontAwesomeIcon icon={faShareAlt} className="ml-2 mt-2" size='lg' color='#7a7a7a' />
              <div className="text-center">
                <div className=" leading-100 mt-5">
                  <div className="leading-100  w-96 mx-auto">
                    <span className="text-5xl ">試験勉強</span>
                    の部屋
                  </div>
                </div>
              </div>
              <div className=" pb-5 w-full flex items-center">
                <div className='w-3/12  box-border pr-0'></div>
                <div className="w-6/12  box-border pr-0">
                  <div className="min-h-150 text-4xl m-0 text-center"><span className="text-9xl block text-center">22:58:24</span>
                  </div>
                  <div className="flex justify-between">
                    <div className="float-left ">
                    現在                 
                      <span className="text-6xl mx-5">
                        <span id="show-count">1</span>
                      </span>人
                    </div>
                    <a href="userpage.php" className="flex justify-end">
                      <div className="flex items-center  ml-auto">
                        <img src={'/img/1.jpg'} alt="" className="h-12 w-12 mr-1 rounded-full" />
                        <span className="text-base">太郎</span>
                      </div>
                    </a>
                  </div>
                </div>
                <div className="w-3/12 flex justify-center">
                  <div className="float-right mt-5 -translate-y-5">
                    <div className="clock">
                      <div className="clock-hand"></div>
                      <div className="clock-param" style={{ transform: "rotate(-90deg)", animation: "rotation-s 10s linear 1 forwards" }}></div>
                    </div>
                    <div className="text-center mt-5">
                      <button className="text-base px-4 py-3 box-border bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600">部屋に招く</button>
                      <div className="hidden absolute top-neg-100 left-neg-1/2 w-250 h-150 px-5 py-4 box-border bg-gray-800 text-white">
                        <p className="text-left m-0 mb-4 overflow-wrap-word">
                          <span className="m-auto">招待リンク</span>
                          （現在のURL）
                        </p>
                        <span className="text-blue-500 cursor-pointer js-btn-invite">リンクをコピー</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="mt-5 bg-white min-h-500 box-border p-20 block">
              <p className="text-center text-lg font-bold">この部屋でやることを宣言しましょう!</p>
              <div className="room-board-user">
              </div>
              <div className="px-4 pt-100 pb-20 max-w-700 mx-auto overflow-hidden bg-white">
              <textarea name="comment" id="" cols={30} rows={10} className="w-full h-40 p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
              <button type="button" className="ml-auto block py-2 px-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600">コメントする</button>
            </div>
            </div>
          </div>
      </div>
    </>
  );
};

// Propsの型定義やデフォルト値の設定が必要な場合はここで設定
Top.defaultProps = {
  name: 'World'
};

// 必要に応じてPropTypesで型チェックを行う
// MyComponent.propTypes = {
//   name: PropTypes.string
// };

// コンポーネントをエクスポート
export default Top;
