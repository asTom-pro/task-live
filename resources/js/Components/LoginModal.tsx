import React from 'react';
import { Link } from '@inertiajs/react';

interface LoginModalProps {
  isOpen: boolean;
  onClose: () => void;
}

const LoginModal: React.FC<LoginModalProps> = ({ isOpen, onClose }) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
      <div className="bg-white p-6 rounded shadow-lg">
        <h2 className="text-2xl mb-4">ログインが必要です</h2>
        <p className="mb-4">フォローするにはログインしてください。</p>
        <Link href={route('login')} className="btn-login block bg-blue-500 text-white py-2 px-4 rounded w-full text-center">ログインページへ</Link>
        <button onClick={onClose} className="mt-4 text-gray-500">閉じる</button>
      </div>
    </div>
  );
};

export default LoginModal;
