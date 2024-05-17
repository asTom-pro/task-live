import React, { useEffect, useState, FormEvent } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import usersample from '@/Pages/img/user-sample.svg';
import { faShareAlt } from '@fortawesome/free-solid-svg-icons';
import Header from '@/Components/Header'; 
import Clock from '@/Components/Clock'; 
import RoomInviteButton from '@/Components/RoomInviteButton'; 
import { User, Auth, PageProps, Room, RoomComment, EndedTaskFormData } from '@/types';
import { usePage, useForm } from '@inertiajs/react';
import axios from 'axios';




// コンポーネントで使用する他のコンポーネントやライブラリをインポートする場合はここに記述します
// 秒数を「hh:mm:ss」に変換する
const formatTime = (seconds: number): string => {
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = seconds % 60;

  return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

const RoomShow: React.FC = () => {
  // 状態（state）や他の変数を定義
  // const [state, setState] = React.useState(initialState);
  const { room, auth, ziggy } = usePage<PageProps>().props;
  const [comments, setComments] = useState<RoomComment[]>([]);
  const [newComment, setNewComment] = useState('');
  const [remainingTime, setRemainingTime] = useState<number>(0);
  const [task, setTask] = useState('');
  const [isTaskModalOpen, setIsTaskModalOpen] = useState(false);
  if (!room) {
    return <div>Loading...</div>; // データが読み込まれるまでの間、ローディング表示を行う
  }
  const { data, setData, post } = useForm<EndedTaskFormData>({
    room_id: room.id, 
    ended_task: ''
  });

  // メソッドやイベントハンドラの定義
  // const handleEvent = (event) => {
  //   // イベントハンドリングのロジック
  // };



  const [limitTimeSec, setLimitTimeSec] = useState<number>(Number(room.time_limit));

  useEffect(() => {
    const roomCreatedAt = new Date(room.created_at).getTime();
    const now = new Date().getTime();
    const elapsedTime = Math.floor((now - roomCreatedAt) / 1000);
    const limitTimeInSeconds = Number(room.time_limit);
    const remainingTime = limitTimeInSeconds - elapsedTime;
    setRemainingTime(remainingTime > 0 ? remainingTime : 0);

    const interval = setInterval(() => {
      setRemainingTime((prev) => {
        if (prev <= 1) {
          clearInterval(interval);
          setIsTaskModalOpen(true);
          return 0;
        }
        return prev - 1;
      });
    }, 1000);

    return () => clearInterval(interval);
    
  }, [room.created_at, room.time_limit]);

  useEffect(() => {
    const fetchComments = async () => {
      try {
        const response = await axios.get(`/rooms/${room.id}/comments`);
        console.log('Fetched comments:', response.data);  // デバッグ用のログ
        setComments(response.data);
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    };

    fetchComments();
  }, [room.id]);

  const handleCommentSubmit = async () => {
    try {
      const response = await axios.post('/ajaxComment', {
        comment: newComment,
        room_id: room.id,
      });
      console.log('Submitted comment:', response.data);  // デバッグ用のログ
      setComments(() => {
        return response.data;
      });
      setNewComment('');
    } catch (error) {
      console.error('Error submitting comment:', error);
    }
  };

  const handleTaskSubmit = (e: FormEvent) => {
    e.preventDefault();
    post(route('task.store'));
    setIsTaskModalOpen(false);
  };

  useEffect(() => {
    console.log('Comments updated:', comments);  // comments が更新されるたびにログを出力
  }, [comments]);

  const formattedTime = formatTime(remainingTime);

  const totalDuration = Number(room.time_limit);
  const percentage = (remainingTime / totalDuration) * 100;
  const rotation = (360 * percentage) / 100;


    


  // コンポーネントがレンダリングするUI
  return (
    <>
      <Header auth={auth} ziggy={ziggy} />
      <div className="bg-slate-100 p-10 min-h-screen">
          <div className="max-w-screen-lg mx-auto justify-center">
            <div className="bg-white">
            <FontAwesomeIcon icon={faShareAlt} className="ml-2 mt-2" size='lg' color='#7a7a7a' />
              <div className="text-center">
                <div className=" leading-100 mt-5">
                  <div className="leading-100  w-96 mx-auto">
                    <span className="text-5xl ">{room.name}</span>
                    の部屋
                  </div>
                </div>
              </div>
              <div className=" pb-5 w-full flex items-center">
                <div className='w-3/12  box-border pr-0'></div>
                <div className="w-6/12  box-border pr-0">
                  <div className="min-h-150 m-0 text-center"><span className="text-[7rem] block text-center tabular-nums">{formattedTime}</span>
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
                        <img src={room.user ? (room.user.profile_img || usersample) : usersample} alt="" className="h-16 w-16 mr-1 object-cover	 rounded-full" />
                        <span className="text-base">{room.user ? (room.user.name || '名称未設定') : 'ゲスト'}</span>
                      </div>
                    </a>
                  </div>
                </div>
                <div className="w-3/12 flex justify-center">
                  <div className="relative mt-5 -translate-y-5">
                    <Clock duration={Number(room.time_limit)} created_at = {String(room.created_at)} />
                    <div className="text-center mt-5">
                      <RoomInviteButton />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="mt-5 bg-white min-h-500 box-border p-20 block room-board">
              <p className="text-center text-lg font-bold">この部屋でやることを宣言しましょう!</p>
              <div className="room-board-user">
              {comments.map(comment => (
                <div key={comment.id} className={comment.user?.id === auth.user?.id ? "right-user board-user" : "left-user board-user"}>
                  <div className="user-info">
                    <img src={comment.user?.profile_img || usersample} alt="" className="user-img" />
                    <span className="user-name">{comment.user?.name || 'ゲスト'}</span>
                  </div>
                  <div className="comment-container">
                    <p className="comment">
                      <span className="user-ballon"></span>{comment.comment}
                    </p>
                  </div>
                </div>
              ))}
              </div>
              <div className="pt-100 pb-20 max-w-700 mx-auto overflow-hidden bg-white">
                <textarea 
                name="comment" 
                id="" 
                cols={30} 
                rows={10} 
                className="w-full mt-5 h-40 p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                value={newComment}
                onChange={e => setNewComment(e.target.value)}
                ></textarea>
                <button 
                type="button" 
                className="ml-auto block py-2 px-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600"
                onClick={handleCommentSubmit}
                >コメントする</button>
              </div>
            </div>
            {isTaskModalOpen && (
            <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div className="bg-white p-5 rounded-lg shadow-lg">
                <h2 className="text-xl font-bold mb-4">完了タスクを保存しますか？</h2>
                <textarea
                  value={data.ended_task}
                  onChange={(e) => setData('ended_task', e.target.value)}
                  rows={4}
                  className="w-full p-2 border border-gray-300 rounded"
                  placeholder="完了タスクを入力してください"
                ></textarea>
                <div className="flex justify-end mt-4">
                  <button
                    onClick={() => setIsTaskModalOpen(false)}
                    className="bg-gray-500 text-white py-2 px-4 rounded-lg mr-2"
                  >
                    キャンセル
                  </button>
                  <button
                    onClick={handleTaskSubmit}
                    className="bg-blue-500 text-white py-2 px-4 rounded-lg"
                  >
                    保存
                  </button>
                </div>
              </div>
            </div>
          )}
          </div>
      </div>
    </>
  );
};

// Propsの型定義やデフォルト値の設定が必要な場合はここで設定
RoomShow.defaultProps = {
  name: 'World'
};

// 必要に応じてPropTypesで型チェックを行う
// MyComponent.propTypes = {
//   name: PropTypes.string
// };

// コンポーネントをエクスポート
export default RoomShow;
