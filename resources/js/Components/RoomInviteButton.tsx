import React, { useState } from 'react';

const RoomInviteButton = () => {
  const [showInvite, setShowInvite] = useState(false);

  const handleInviteToggle = () => {
    setShowInvite(!showInvite);
  };

  const handleCopyLink = () => {
    const inviteLink = window.location.href;
    navigator.clipboard.writeText(inviteLink).then(() => {
      alert('リンクがコピーされました');
    }).catch(() => {
      alert('リンクのコピーに失敗しました');
    });
  };

  return (
    <div className="relative">
      <button 
        className="py-2 px-4 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600"
        onClick={handleInviteToggle}
      >
        部屋に招く
      </button>

      {showInvite && (
        <div className="absolute top-12 left-1/2 transform -translate-x-1/2 mt-2 bg-white p-5 rounded shadow-lg z-10">
          <p className="link-text mb-4">
            <span className="font-bold">招待リンク:</span>
            <span className="block mt-2">{window.location.href}</span>
          </p>
          <button 
            className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            onClick={handleCopyLink}
          >
            リンクをコピー
          </button>
        </div>
      )}
    </div>
  );
};

export default RoomInviteButton;
