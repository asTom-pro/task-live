import React from 'react';


import Header from '@/Components/Header'; 
import RoomSummary from '@/Components/RoomSummary';
import RoomSearch from '@/Components/RoomSearch';

import { User, Auth, PageProps } from '@/types';


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
      <div>
      <Header auth={auth} ziggy={ziggy} />
        {/* <h1>Hello, {props.name}!</h1> */}
        {/* <h1>Hello!</h1> */}
        {/* JSXの中で他のコンポーネントを呼び出したり、条件分岐、リスト処理を行う */}
      </div>
      <div className='bg-slate-100 p-10 h-screen'>
        <div className='max-w-screen-lg mx-auto flex gap-5 justify-between'>
          <div className="w-3/4 bg-white float-left overflow-hidden box-border">
            <RoomSummary></RoomSummary>
          </div>
          <div className="w-1/4 bg-white float-right overflow-hidden box-border">
            <RoomSearch></RoomSearch>
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
