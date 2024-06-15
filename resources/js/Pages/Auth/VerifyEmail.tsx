import GuestLayout from '@/Layouts/GuestLayout';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEventHandler, useEffect  } from 'react';
import BaseLayout from '@/Layouts/BaseLayout';
import { PageProps } from '@/types';



export default function VerifyEmail({ status }: { status?: string }) {
    const title = 'メール認証';
    useEffect(() => {
      document.title = title;
    }, [title]);
    const { post, processing } = useForm({});
    const { auth, ziggy, tags, search, rooms, url } = usePage<PageProps>().props;

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('verification.send'));
    };

    return (
        <>
            <Head title={title} />
            <BaseLayout auth={auth} ziggy={ziggy}>
                <div className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the
                    link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </div>
                {status === 'verification-link-sent' && (
                    <div className="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                )}
                <form onSubmit={submit}>
                    <div className="mt-4 flex items-center justify-between">
                        <PrimaryButton disabled={processing}>Resend Verification Email</PrimaryButton>
                        <Link
                            href={route('logout')}
                            method="post"
                            as="button"
                            className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            Log Out
                        </Link>
                    </div>
                </form>
            </BaseLayout>
        </>
    );
}
