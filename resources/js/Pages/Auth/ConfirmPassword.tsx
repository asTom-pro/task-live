import { useEffect, FormEventHandler } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, useForm, usePage } from '@inertiajs/react';
import BaseLayout from '@/Layouts/BaseLayout';
import { PageProps } from '@/types';


export default function ConfirmPassword() {

    const title = 'パスワード確認';
    useEffect(() => {
      document.title = title;
    }, [title]);    

    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });
    const { auth, ziggy, tags, search, rooms, url } = usePage<PageProps>().props;


    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('password.confirm'));
    };

    return (
        <>
            <Head title="Confirm Password" />
            <BaseLayout auth={auth} ziggy={ziggy}>
                    <div className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        続行するには、パスワードを入力してください。
                    </div>

                    <form onSubmit={submit}>
                        <div className="mt-4">
                            <InputLabel htmlFor="password" value="Password" />

                            <TextInput
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className="mt-1 block w-full"
                                isFocused={true}
                                onChange={(e) => setData('password', e.target.value)}
                            />

                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div className="flex items-center justify-end mt-4">
                            <PrimaryButton className="ms-4" disabled={processing}>
                                Confirm
                            </PrimaryButton>
                        </div>
                    </form>
            </BaseLayout>
        </>
    );
}
