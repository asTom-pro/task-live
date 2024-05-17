import React from 'react';
import { useForm } from '@inertiajs/react';
import Header from '@/Components/Header';
import { PageProps } from '@/types';
import { usePage } from '@inertiajs/react';
import { UserProfilePageProps } from '@/types';


const WithdrawConfirm: React.FC = () => {
  const { post } = useForm();

  const handleWithdraw = () => {
    if (window.confirm('本当に退会しますか？この操作は取り消せません。')) {
      post(route('withdraw'));
    }
  };

  const handleCancel = () => {
    window.location.href = route('user.mypage');
  };

  const { auth, ziggy } = usePage<UserProfilePageProps>().props;


  return (
    <>
      <Header auth={auth} ziggy={ziggy} />
      <div className="max-w-xl mx-auto p-5">
        <div className="bg-white p-10 rounded-lg shadow-md">
          <h1 className="text-2xl font-bold mb-6">退会確認</h1>
          <p className="mb-6">本当に退会しますか？この操作は取り消せません。</p>
          <div className='flex justify-center gap-20'>
            <button onClick={handleWithdraw} className="bg-red-500 text-white py-2 px-4 rounded">退会する</button>
            <button onClick={handleCancel} className="bg-slate-500 text-white py-2 px-4 rounded">キャンセル</button>
          </div>
        </div>
      </div>
    </>
  );
};

export default WithdrawConfirm;
