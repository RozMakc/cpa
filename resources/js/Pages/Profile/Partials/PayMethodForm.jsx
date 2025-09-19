import DangerButton from '@/Components/DangerButton';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import Modal from '@/Components/Modal';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import { useForm, usePage } from '@inertiajs/react';
import { useRef, useState } from 'react';

export default function PayMethodForm({ className = '', user }) {

    
    const {
        data,
        setData,
        post,
        processing,
        reset,
        errors,
        clearErrors,
    } = useForm({
        payment_method: {
            bank_name: user.paymethods?.bank_name || '',
            bank_bik: user.paymethods?.bank_bik || '',
            bank_rs: user.paymethods?.bank_rs || '',
        }
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('profile.paymethod'));
    };

    return (
        <>
            <div class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Платежная информация</h3>
                </div>

            </div>
            <div className="p-4 sm:p-6 dark:border-gray-800">

                <form onSubmit={submit} className="space-y-6">
                    <div className="space-y-5">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-5 ">    
                            <div className="space-y-3">
                                <div>
                                    <InputLabel>Банк</InputLabel>
                                    <TextInput
                                        id="bank_name"
                                        type="text"
                                        name="bank_name"
                                        value={data.payment_method.bank_name}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) => setData('payment_method.bank_name', e.target.value)}
                                    />
                                    {errors[`payment_method.bank_name`] && (
                                    <p className="text-red-500 text-sm mt-1">Необходимо указать Место рождения</p>
                                    )}
                                </div>

                                <div>
                                    <InputLabel>БИК Банка</InputLabel>
                                    <TextInput
                                        id="bank_bik"
                                        type="text"
                                        name="bank_bik"
                                        value={data.payment_method.bank_bik}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) => setData('payment_method.bank_bik', e.target.value)}
                                    />
                                    {errors[`payment_method.bank_bik`] && (
                                    <p className="text-red-500 text-sm mt-1">Необходимо указать bank_bik</p>
                                    )}
                                </div>

                                <div>
                                    <InputLabel>Расчетный счет</InputLabel>
                                    <TextInput
                                        id="bank_rs"
                                        type="text"
                                        name="bank_rs"
                                        value={data.payment_method.bank_rs}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) => setData('payment_method.bank_rs', e.target.value)}
                                    />
                                    {errors[`payment_method.bank_rs`] && (
                                    <p className="text-red-500 text-sm mt-1">Необходимо указать bank_rs</p>
                                    )}
                                </div>

                            </div>             

                        </div>
                    </div>

                    <div className="mt-5 flex flex-col gap-3 sm:flex-row justify-end">
                        <div>
                        <PrimaryButton disabled={processing}>
                        {processing ? 'Сохранение...' : 'Сохранить'}
                        </PrimaryButton>
                        </div>
                    </div>
                </form>

            </div>
        </>
    );
}
