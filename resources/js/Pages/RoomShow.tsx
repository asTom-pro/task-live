import React, { useEffect, useState, FormEvent, useRef } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import usersample from '@/Pages/img/user-sample.svg';
import { faShareAlt } from '@fortawesome/free-solid-svg-icons';
import Header from '@/Components/Header'; 
import Clock from '@/Components/Clock'; 
import RoomInviteButton from '@/Components/RoomInviteButton'; 
import { User, Auth, PageProps, Room, RoomComment, EndedTaskFormData } from '@/types';
import { usePage, useForm, Link, Head } from '@inertiajs/react';
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
  const { room, auth, ziggy, title } = usePage<PageProps>().props;
  const [comments, setComments] = useState<RoomComment[]>([]);
  const [newComment, setNewComment] = useState('');
  const [remainingTime, setRemainingTime] = useState<number>(0);
  const [task, setTask] = useState('');
  const [isTaskModalOpen, setIsTaskModalOpen] = useState(false);
  const [isRoomEndedModalOpen, setIsRoomEndedModalOpen] = useState(false);
  const [userCount, setUserCount] = useState<number>(1);
  const intervalRef = useRef<number | null>(null);

  useEffect(() => {
    document.title = title;
  }, [title]);


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

    if (remainingTime <= 0) {
      setIsRoomEndedModalOpen(true);
    } else {
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
    }
  }, [room.created_at, room.time_limit]);

  useEffect(() => {
    const fetchComments = async () => {
      try {
        const response = await axios.get(`/rooms/${room.id}/comments`);
        // console.log('Fetched comments:', response.data);
        setComments(response.data);
      } catch (error) {
        // console.error('Error fetching comments:', error);
      }
    };

    fetchComments();
    const interval = setInterval(fetchComments, 1000);

    return () => clearInterval(interval);
  }, [room.id]);

  const handleCommentSubmit = async () => {
    try {
      const response = await axios.post('/ajaxComment', {
        comment: newComment,
        room_id: room.id,
      });
      // console.log('Submitted comment:', response.data);  
      setComments(() => {
        return response.data;
      });
      setNewComment('');
    } catch (error) {
      // console.error('Error submitting comment:', error);
    }
  };

  const handleTaskSubmit = (e: FormEvent) => {
    e.preventDefault();
    post(route('task.store'));
    setIsTaskModalOpen(false);
  };

  // useEffect(() => {
  //   console.log('Comments updated:', comments);  // comments が更新されるたびにログを出力
  // }, [comments]);

  useEffect(() => {
    const fetchUserCount = async () => {
      try {
        const response = await axios.get(`/room/${room.id}/user-count`);
        setUserCount(response.data.user_count);
      } catch (error) {
        console.error('Error fetching user count:', error);
      }
    };

    fetchUserCount();
    intervalRef.current = window.setInterval(fetchUserCount, 1000);
    return () => {
      if (intervalRef.current !== null) {
        clearInterval(intervalRef.current);
      }
    };

  }, [room.id]);

  const formattedTime = formatTime(remainingTime);

  const totalDuration = Number(room.time_limit);
  const percentage = (remainingTime / totalDuration) * 100;
  const rotation = (360 * percentage) / 100;


    


  // コンポーネントがレンダリングするUI
  return (
    <>
      <Head title={`${title}の部屋`} />
      <Header auth={auth} ziggy={ziggy} />
      <div className="bg-slate-100 p-5 md:p-10 min-h-screen">
          <div className="mx-auto justify-center">
            <div className="bg-white py-5 max-w-screen-lg mx-auto">
              {/* <FontAwesomeIcon icon={faShareAlt} className="md:hidden px-10" size='lg' /> */}
              <div className='md:hidden'>
                <RoomInviteButton />
              </div>
              <div className="text-center">
                <div className="md:mt-5">
                  <div className="w-full mx-auto px-10">
                    <span className="text-3xl md:text-5xl break-words">{room.name}</span>
                    の部屋
                  </div>
                </div>
              </div>
              <div className="w-full flex items-center">
                <div className='md:w-3/12  box-border pr-0'></div>
                <div className="w-full md:w-6/12  box-border pr-0">
                  <div className="min-h-150 m-0 text-center"><span className="text-6xl	lg:text-8xl block text-center tabular-nums">{formattedTime}</span>
                  </div>
                  <div className="mt-3 flex justify-between px-10 lg:px-0">
                    <div className="float-left ">
                    {remainingTime > 0 ? '現在': '終了' }                 
                      <span className="text-4xl md:text-6xl mx-5">
                        <span id="show-count">{userCount}</span>
                      </span>人
                    </div>
                    <a href="userpage.php" className="flex justify-end">
                      <div className="flex items-center  ml-auto">
                        <img src={room.user ? (room.user.profile_img || usersample) : usersample} alt="" className="h-8 w-8 md:h-16 md:w-16 mr-1 object-cover	rounded-full" />
                        <span className="text-sm md:text-base">{room.user ? (room.user.name || '名称未設定') : 'ゲスト'}</span>
                      </div>
                    </a>
                  </div>
                </div>
                <div className="hidden md:w-3/12 md:flex justify-center">
                  <div className="md:relative mt-5 -translate-y-5">
                    <Clock duration={Number(room.time_limit)} created_at = {String(room.created_at)} />
                    <div className="text-center mt-5">
                      <RoomInviteButton />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="mt-5 bg-white min-h-500 box-border p-10 md:p-20 block room-board  max-w-screen-lg mx-auto">
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
              <div className="pt-100 md:pb-20 max-w-700 mx-auto bg-white">
                <textarea 
                name="comment" 
                id="" 
                cols={30} 
                rows={10} 
                className="w-full mt-5 h-40 md:p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                value={newComment}
                onChange={e => setNewComment(e.target.value)}
                ></textarea>
                <button 
                type="button" 
                className="w-full md:w-auto mt-2 md:mt-0  md:ml-auto block py-2 px-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600"
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
          {isRoomEndedModalOpen && (
            <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
              <div className="bg-white rounded-lg p-6">
                <h2 className="text-xl font-bold mb-4">この部屋は終了しました</h2>                
                <div className='flex justify-between'>
                  <Link href={route('home')} className="text-white bg-slate-500 p-2 rounded font-semibold">
                  部屋を探す
                  </Link>
                  <Link href={route('room.create')} className="text-white bg-sky-500 p-2 rounded font-semibold">
                  部屋を作る
                  </Link>
                </div>
              </div>
            </div>
          )}
          </div>
      </div>
    </>
  );
};

// 必要に応じてPropTypesで型チェックを行う
// MyComponent.propTypes = {
//   name: PropTypes.string
// };

// コンポーネントをエクスポート
export default RoomShow;
