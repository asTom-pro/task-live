import React, { useEffect, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import Header from '@/Components/Header'; 
import { User, Auth, PageProps } from '@/types';
import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { usePage } from '@inertiajs/react';

// ログインコンポーネント
const Login = ({ status, canResetPassword }: { status?: string, canResetPassword: boolean }) => {
    const { auth, ziggy } = usePage<PageProps>().props;

    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <>
         <Header auth={auth} ziggy={ziggy} />
         <div className="min-h-screen bg-gray-100 pt-8"> {/* ページ上部に寄せるためのパディングを追加 */}
            <div className="max-w-md w-full bg-white p-8 rounded-lg shadow-lg mx-auto"> {/* フォームの余白を調整 */}
              <Head title="Log in" />

              {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

              <form onSubmit={submit} className="space-y-6">
                <h1 className="text-2xl font-bold text-gray-900 text-center">ログイン</h1>
                
                <div>
                    <InputLabel htmlFor="email" value="メールアドレス" className="text-lg font-medium text-gray-900 dark:text-gray-200" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-800 dark:text-gray-300"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2 text-red-500 text-sm" />
                </div>

                <div>
                    <InputLabel htmlFor="password" value="パスワード" className="text-lg font-medium text-gray-900 dark:text-gray-200" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-800 dark:text-gray-300"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2 text-red-500 text-sm" />
                </div>

                <div className="flex items-center">
                    <Checkbox
                        name="remember"
                        checked={data.remember}
                        onChange={(e) => setData('remember', e.target.checked)}
                        className="h-4 w-4 accent-slate-500 text-slate-500 transition duration-150 ease-in-out dark:bg-gray-800 dark:border-gray-700 focus:ring-gray-500"
                    />
                    <span className="ml-2 text-sm text-gray-600 dark:text-gray-400">ログイン状態を保持する</span>
                </div>

                <div className="flex items-center justify-between mt-6">
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-gray-600 dark:text-gray-400 hover:underline focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            パスワードを忘れた場合
                        </Link>
                    )}

                    <PrimaryButton className="ml-4 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600">
                        ログイン
                    </PrimaryButton>
                </div>
              </form>
            </div>
          </div>
        </>
    );
};

export default Login;
