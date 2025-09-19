import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm } from '@inertiajs/react';
import ForgotPasswordForm from './components/ForgotPasswordForm';

export default function ForgotPassword({ status }) {
    return (
        <GuestLayout>
            <Head title="Восстановление пароля" />
            <ForgotPasswordForm status={status}/>
        </GuestLayout>
    );
}
