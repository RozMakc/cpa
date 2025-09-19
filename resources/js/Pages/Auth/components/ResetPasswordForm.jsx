import { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { ChevronLeftIcon, EyeCloseIcon, EyeIcon } from '@/icons'
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import Checkbox from '@/Components/Checkbox';
import PrimaryButton from '@/Components/PrimaryButton';

export default function ResetPasswordForm() {

    const { data, setData, post, processing, errors, reset } = useForm({
      token: token,
      email: email,
      password: '',
      password_confirmation: '',
  });

  const submit = (e) => {
      e.preventDefault();

      post(route('password.store'), {
          onFinish: () => reset('password', 'password_confirmation'),
      });
  };


  return (
    <div className="flex flex-col flex-1">
      <div className="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
        <div>
          <div className="mb-5 sm:mb-8">
            <h1 className="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
              Сбросить пароль
            </h1>
          </div>
          
          <div>
            <form onSubmit={submit}>
              <div className="space-y-6">
                <div>
                  <InputLabel>
                    Email <span className="text-error-500">*</span>{" "}
                  </InputLabel>
                  <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        placeholder="Email"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    {errors.email && (
                        <p className="text-red-500 text-sm mt-1">{errors.email}</p>
                    )}
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Пароль" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        isFocused={true}
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm Password"
                    />

                    <TextInput
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>

                <div>
                  <PrimaryButton className="w-full" size="sm">
                  Reset Password
                  </PrimaryButton>
                </div>
              </div>
            </form>

            <div className="mt-5">
              <p className="text-sm font-normal text-center text-gray-700 dark:text-gray-400 sm:text-start">
                У Вас еще нет аккаунта? {""}
                <Link
                  href={route('register')}
                  className="text-brand-500 hover:text-brand-600 dark:text-brand-400"
                >
                  Создайте аккаунт
                </Link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}