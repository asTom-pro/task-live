import { useEffect, FormEventHandler } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import Header from '@/Components/Header'; 
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm, usePage  } from '@inertiajs/react';
import { User, Auth, PageProps } from '@/types';


export default function Register() {
    const { auth, ziggy } = usePage<PageProps>().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('register'));
    };

    return (
        <>
            <Header auth={auth} ziggy={ziggy} />
            <div className="min-h-screen bg-gray-100 pt-8">
            <div className="max-w-md w-full bg-white p-8 rounded-lg shadow-lg mx-auto">
                <Head title="Register" />

                <form onSubmit={submit} className="space-y-6">
                <h1 className="text-2xl font-bold text-gray-900 text-center">ユーザー登録</h1>

                <div>
                    <InputLabel htmlFor="email" value="メールアドレス" className="text-lg font-medium text-gray-900 dark:text-gray-200" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-800 dark:text-gray-300"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
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
                        autoComplete="new-password"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />

                    <InputError message={errors.password} className="mt-2 text-red-500 text-sm" />
                </div>

                <div>
                    <InputLabel htmlFor="password_confirmation" value="パスワード確認" className="text-lg font-medium text-gray-900 dark:text-gray-200" />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-800 dark:text-gray-300"
                        autoComplete="new-password"
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        required
                    />

                    <InputError message={errors.password_confirmation} className="mt-2 text-red-500 text-sm" />
                </div>

                <div className="flex items-center justify-between mt-6">
                    <Link
                        href={route('login')}
                        className="text-sm text-gray-600 dark:text-gray-400 hover:underline focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        すでに登録済みの方
                    </Link>

                    <PrimaryButton className="ml-4 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600" disabled={processing}>
                        登録
                    </PrimaryButton>
                </div>
                </form>
            </div>
            </div>
    </>
    );
}
