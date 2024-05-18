import React, { useEffect, useRef } from 'react';
import '@/../css/components/_roomclock.css';

interface ClockProps {
  duration: number; // ルームの全体の時間
  created_at: string;
  clockWidth?: string;
}

const Clock: React.FC<ClockProps> = ({ duration, created_at, clockWidth }) => {
  const clockHandRef = useRef<HTMLDivElement>(null);
  // const [remainingTimeFlash, setRemainingTimeFlash] = useState<number>(0);

  useEffect(() => {
    const roomCreatedAt = new Date(created_at).getTime();
    const now = new Date().getTime();
    const elapsedTime = Math.floor((now - roomCreatedAt) / 1000); // 秒に変換
    const limitTimeInSeconds = Number(duration);
    const remainingTime = limitTimeInSeconds - elapsedTime;
    // setRemainingTimeFlash(remainingTime > 0 ? remainingTime : 0);

    const initialRotation = (elapsedTime / limitTimeInSeconds) * 360;
    const editInitialRotation = initialRotation + 90;
    if (clockHandRef.current) {
      clockHandRef.current.style.transform = `rotate(${editInitialRotation}deg)`;
      clockHandRef.current.style.animation = `${remainingTime}s linear 0s 1 normal forwards running rotation-s`;
    }
  }, []);

  return (
    <div>
      <div className={`clock ${clockWidth}`}>
        <div className="clock-hand" ref={clockHandRef}></div>
      </div>
    </div>
  );
};

export default Clock;
