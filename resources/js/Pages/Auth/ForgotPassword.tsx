import React, { FormEventHandler, useEffect } from 'react';
import { useForm, Head } from '@inertiajs/react';
import Header from '@/Components/Header';
import { PageProps } from '@/types';
import { usePage } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import BaseLayout from '@/Layouts/BaseLayout';


const ForgotPassword: React.FC<{ status?: string }> = ({ status }) => {

    const title = 'パスワードリセット';
    useEffect(() => {
      document.title = title;
    }, [title]);

    const { auth, ziggy, url } = usePage<PageProps>().props;
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <>
            <Head title={title} />
            <BaseLayout auth={auth} ziggy={ziggy}>
                <div className="min-h-screen bg-gray-100 pt-8">
                    <div className="max-w-md w-full bg-white p-8 rounded-lg shadow-lg mx-auto">
                        <div className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            メールアドレスを教えていただければ、パスワードリセット用のリンクをお送りします。
                        </div>  
                        {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>} 
                        <form onSubmit={submit} className="space-y-6">
                            <h1 className="text-2xl font-bold text-gray-900 text-center">パスワードリセット</h1>  
                            <div>
                                <InputLabel htmlFor="email" value="メールアドレス" className="text-lg font-medium text-gray-900 dark:text-gray-200" />  
                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-800 dark:text-gray-300"
                                    isFocused={true}
                                    onChange={(e) => setData('email', e.target.value)}
                                />  
                                <InputError message={errors.email} className="mt-2 text-red-500 text-sm" />
                            </div>  
                            <div className="flex items-center justify-end mt-6">
                                <PrimaryButton className="ml-4 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600" disabled={processing}>
                                    リンクを送信
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </BaseLayout>
        </>
    );
};

export default ForgotPassword;
