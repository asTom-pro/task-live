// Header.tsx
import React from 'react';
import logo from '../Pages/img/logo.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';

const RoomSummary = () => {
  return (
    <div className="w-full p-10">
      {/* <div className="open-myroom">
        <p className="room-select-lead info-txt">作成した部屋がまだ開いています</p>
        <a>
          <div className="room">
            <div className="room-info">
              <h2 className="room-title">レポート作成</h2>
              <div className="room-member room-info-list">
                <div className="member-container">
                  現在<span className="member-num">3</span>人
                </div>
              </div>
              <div className="room-tag room-info-list">
                
                    <object><a className="btn tag" href="index.php?tag="></a></object>
              
              </div>
              <div className="room-user-info room-info-list">
                <img src="" alt="" className="favorite no-favorite" />
                <object>
                  <a href="userpage.php?u=" className='flex items-center'>
                    <img src={'/img/1.jpg'} alt="" className="h-12 w-12 overflow-hidden rounded-full" />
                    <span className="user-name">
                      ああああ
                    </span>
                  </a>
                </object>
              </div>
            </div>
            <div className="room-time">
              <div className="clock">
                <div className="clock-hand"
                     style={{ transform: "rotate(-90deg)", animation: "rotation-s 10s linear 1 forwards" }}></div>
              </div>
              <div className="remain-time">
                <span className="remain-time-num-m">3</span>分
              </div>
          </div>
          </div>
        </a>
      </div> */}
  
      <p className="font-semibold">参加する部屋を選択</p>
      <a href="" className="block mt-3">
        <div className=" bg-opacity-10 border border-gray-200 box-border px-6 py-3 transition duration-100 flex justify-between items-center bg-gray-50	hover:bg-gray-100">
          <div className="w-full">
            <h2 className="font-bold text-5xl min-h-100 box-border w-full">頑張る</h2>
            <div className='flex justify-between items-end mt-atuo mt-10'>
              <div className="">
                <div className="">
                  現在<span className="text-5xl mx-3">2</span>人
                </div>
              </div>
              <div className="">
                  <a className="h-20 text-sm mr-10 box-border px-3 py-1 bg-slate-500 text-white rounded-lg" href="">タグ</a>
              </div>
              <div className="">
                <img src="img/favorite.png" alt="" className="mt-15 ml-10 mr-10" />
                <object>
                  <a href="userpage.php?u=" className='flex items-center'>
                    <img src={'/img/1.jpg'} alt="" className="h-12 w-12 rounded-full mr-1" />
                    <span className="user-name">ああああ</span>
                  </a>
                </object>
              </div>
            </div>
          </div>
          <div className="ml-3">
            <div className="clock">
              <div
                className="clock-hand"
                style={{ transform: "rotate(-90deg)", animation: "rotation-s 10s linear 1 forwards" }}>
              </div>
            </div>
            <div className="text-center">
              <span className="text-2xl mt-3">10</span>/30分
            </div>
          </div>
        </div>
      </a>
</div>
  );
};

export default RoomSummary;