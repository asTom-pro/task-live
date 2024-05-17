import React, { useEffect, useState, useRef } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown, faChevronUp } from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';

interface Task {
  created_at: string;
  ended_task: string;
}

interface TaskListProps {
  authUserId: number | null;
}

const TaskList: React.FC<TaskListProps> = ({ authUserId }) => {
  const [tasks, setTasks] = useState<Task[]>([]);
  const [isTableOpen, setIsTableOpen] = useState<boolean>(false);
  const tbodyRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const fetchTasks = async () => {
      try {
        const response = await axios.get('/tasks', {
          params: { user_id: authUserId },
        });
        setTasks(response.data);
      } catch (error) {
        console.error('Error fetching tasks:', error);
      }
    };

    fetchTasks();
  }, [authUserId]);

  const toggleTable = () => {
    setIsTableOpen((prev) => !prev);
  };

  useEffect(() => {
    if (tbodyRef.current) {
      if (isTableOpen) {
        const height = tbodyRef.current.scrollHeight;
        tbodyRef.current.style.maxHeight = `${height}px`;
      } else {
        tbodyRef.current.style.maxHeight = '0';
      }
    }
  }, [isTableOpen, tasks]);

  return (
    <div className="mt-12">
      <div className="border border-gray-200 rounded-md overflow-hidden">
        <div
          className="flex justify-between items-center p-4 bg-gray-100 cursor-pointer"
          onClick={toggleTable}
        >
          <span className="text-xl">メモ</span>
          <FontAwesomeIcon
            icon={isTableOpen ? faChevronUp : faChevronDown}
            className="transition-transform duration-300"
            size="lg"
            color="#7a7a7a"
          />
        </div>
        <div
          ref={tbodyRef}
          className={`transition-max-height duration-300 ease-in-out overflow-hidden max-h-0`}
        >
          <ul>
            {tasks.length > 0 ? (
              tasks.map((task, index) => (
                <li key={index} className="border-b border-gray-300 p-4">
                  <span className="block">
                    {new Date(task.created_at).toLocaleDateString('ja-JP', {
                      year: 'numeric',
                      month: '2-digit',
                      day: '2-digit',
                    })}
                  </span>
                  <span className="block">{task.ended_task}</span>
                </li>
              ))
            ) : (
              <li className="p-4 text-center">現在メモはありません。</li>
            )}
          </ul>
        </div>
      </div>
    </div>
  );
};

export default TaskList;
