import React, { useEffect } from 'react';
import { useForm, usePage, Head, Link } from '@inertiajs/react';
import { User, Auth, PageProps } from '@/types';
import Header from '@/Components/Header';
import usersample from '@/Pages/img/user-sample.svg';
import BaseLayout from '@/Layouts/BaseLayout';


interface EditProfileProps {
  user: User;
}

const EditProfile: React.FC<EditProfileProps> = ({ user }) => {
  const title = 'プロフィール編集';
  useEffect(() => {
    document.title = title;
  }, [title]);
  const { auth, ziggy, url } = usePage<PageProps>().props;
  const { data, setData, post, processing, errors } = useForm({
    name: user.name || '',
    profile_text: user.profile_text || '',
    prof_img: null as File | null,
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setData(e.target.name as keyof typeof data, e.target.value);
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files ? e.target.files[0] : null;
    setData('prof_img', file);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('profile.update'));
  };

  return (
    <>
      <Head title={title} />
      <BaseLayout auth={auth} ziggy={ziggy}>
        <div className='bg-slate-100 min-h-screen p-10'>
          <div className="max-w-xl mx-auto">
            <form onSubmit={handleSubmit} className="bg-white p-10 rounded-lg shadow-md" encType="multipart/form-data">
              <h1 className="text-2xl font-bold mb-6">プロフィール編集</h1>
              <div className="mb-4">
                <div className="relative w-24 h-24 mx-auto mb-4 group">
                  <img
                    src={data.prof_img ? URL.createObjectURL(data.prof_img) : (user.profile_img || usersample)}
                    alt="Profile"
                    className="rounded-full w-full h-full object-cover"
                  />
                  <div className="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-full">
                    <span className="text-white text-4xl">+</span>
                  </div>
                  <input type="file" name="prof_img" className="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer" onChange={handleFileChange} />
                </div>
                {errors.prof_img && <span className="text-red-600">{errors.prof_img}</span>}
              </div>
              <div className="mb-4">
                <label className="block text-gray-700">お名前</label>
                <input type="text" name="name" value={data.name} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                {errors.name && <span className="text-red-600">{errors.name}</span>}
              </div>
              <div className="mb-4">
                <label className="block text-gray-700">メールアドレス</label>
                <p className="mt-1">{user.email}</p>
              </div>
              <div className="mb-4">
                <label className="block text-gray-700">プロフィール文</label>
                <textarea
                  name="profile_text"
                  value={data.profile_text}
                  onChange={handleChange}
                  className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  rows={10}
                ></textarea>
                {errors.profile_text && <span className="text-red-600">{errors.profile_text}</span>}
              </div>
              <div className="flex justify-between">
                <Link href={route('user.mypage')} type="button" className="py-2 px-4 rounded" >戻る</Link>
                <button type="submit" className="bg-slate-500 text-white py-2 px-4 rounded" disabled={processing}>更新する</button>
              </div>
            </form>
          </div>
        </div>
      </BaseLayout>
    </>
  );
};

export default EditProfile;
