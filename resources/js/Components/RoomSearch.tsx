import React, { useState, useEffect } from 'react';
import { Tag } from '@/types';

interface RoomSearchProps {
  tags: Tag[];
  onTagSearch: (tag: string) => void;
  selectedTag: string;
}

const RoomSearch: React.FC<RoomSearchProps> = ({ tags, onTagSearch, selectedTag }) => {
  const [internalSelectedTag, setInternalSelectedTag] = useState<string>(selectedTag);

  useEffect(() => {
    setInternalSelectedTag(selectedTag); 
  }, [selectedTag]);

  const handleTagChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    const tag = event.target.value;
    setInternalSelectedTag(tag);
    onTagSearch(tag);
  };

  return (
    <div className="p-5">
      <form onSubmit={(e) => e.preventDefault()}>
        <label className='flex flex-col'>
          タグ
          <select name="tag" className="select rounded" value={internalSelectedTag} onChange={handleTagChange}>
            <option value="">選択してください。</option>
            {tags.map(tag => (
              <option key={tag.id} value={tag.name}>
                {tag.name}
              </option>
            ))}
          </select>
        </label>
      </form>
    </div>
  );
};

export default RoomSearch;
