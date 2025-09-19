import DangerButton from '@/Components/DangerButton';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import Modal from '@/Components/Modal';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import { useForm, usePage } from '@inertiajs/react';
import { useRef, useState } from 'react';

export default function DocumentsForm({ className = '', user }) {

    
    const {
        data,
        setData,
        post,
        processing,
        reset,
        errors,
        clearErrors,
    } = useForm({
        documents: {
            inn: user?.documents?.inn || '',
            pasport_birthplace: user?.documents?.pasport_birthplace || '',
            pasport_series: user?.documents?.pasport_series || '',
            pasport_number: user?.documents?.pasport_number || '',
            pasport_who: user?.documents?.pasport_who || '',
            pasport_when: user?.documents?.pasport_when || '',
            pasport_code: user?.documents?.pasport_code || ''
        }
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('profile.documents'));
    };

    return (
        <>
            <div class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Документы</h3>
                </div>

            </div>
            <div className="p-4 sm:p-6 dark:border-gray-800">

                <form onSubmit={submit} className="space-y-6">
                    <div className="space-y-5">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-5 ">    
                            <div className="space-y-3">
                                <p>Паспорт</p>
                                <div className="grid grid-cols-3 gap-3">
                                        <div className="">
                                            <InputLabel>Серия</InputLabel>
                                            <TextInput
                                                id="pasport_series"
                                                type="text"
                                                name="pasport_series"
                                                value={data.documents.pasport_series}
                                                className="mt-1 block w-full"
                                                isFocused={true}
                                                onChange={(e) => setData('documents.pasport_series', e.target.value)}
                                            />
                                                                {errors[`documents.pasport_series`] && (
                                                                        <p className="text-red-500 text-sm mt-1">Необходимо указать логин</p>
                                                                    )}
                                        </div>
                                        <div className="col-span-2">
                                            <InputLabel>Номер</InputLabel>
                                            <TextInput
                                                id="pasport_number"
                                                type="text"
                                                name="pasport_number"
                                                value={data.documents.pasport_number}
                                                className="mt-1 block w-full"
                                                isFocused={true}
                                                onChange={(e) => setData('documents.pasport_number', e.target.value)}
                                            />
                                        </div>
                                    </div>

                                <div>
                                    <InputLabel>Место рождения</InputLabel>
                                    <TextInput
                                        id="pasport_birthplace"
                                        type="text"
                                        name="pasport_birthplace"
                                        value={data.documents.pasport_birthplace}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) => setData('documents.pasport_birthplace', e.target.value)}
                                    />
                                                                                                                    {errors[`documents.pasport_birthplace`] && (
                                    <p className="text-red-500 text-sm mt-1">Необходимо указать Место рождения</p>
                                    )}
                                </div>

                                <div>
                                    <InputLabel>Кем выдан</InputLabel>
                                    <TextInput
                                        id="pasport_who"
                                        type="text"
                                        name="pasport_who"
                                        value={data.documents.pasport_who}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) => setData('documents.pasport_who', e.target.value)}
                                    />
                                                                                {errors[`documents.pasport_who`] && (
                                    <p className="text-red-500 text-sm mt-1">Необходимо указать Кем выдан</p>
                                    )}
                                </div>

                                <div>
                                    <InputLabel>Дата выдачи</InputLabel>
                                    <TextInput
                                        id="pasport_when"
                                        type="text"
                                        name="pasport_when"
                                        value={data.documents.pasport_when}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) => setData('documents.pasport_when', e.target.value)}
                                    />
                                                                                                    {errors[`documents.pasport_when`] && (
                                                                        <p className="text-red-500 text-sm mt-1">Необходимо указать дату</p>
                                                                    )}
                                </div>

                                <div>
                                    <InputLabel>Код подразделения</InputLabel>
                                    <TextInput
                                        id="pasport_code"
                                        type="text"
                                        name="pasport_code"
                                        value={data.documents.pasport_code}
                                        className="mt-1 block w-full"
                                        isFocused={true}
                                        onChange={(e) => setData('documents.pasport_code', e.target.value)}
                                    />
                                    {errors[`documents.pasport_code`] && (
                                    <p className="text-red-500 text-sm mt-1">Необходимо указать Код подразделения</p>
                                    )}
                                </div>
                            </div>                            

                            <div className="space-y-3">
                            <p>ИНН</p>
                            <div className="">
                                
                                            <InputLabel>Номер</InputLabel>
                                            <TextInput
                                                id="inn"
                                                type="text"
                                                name="inn"
                                                value={data.documents.inn}
                                                className="mt-1 block w-full"
                                                isFocused={true}
                                                onChange={(e) => setData('documents.inn', e.target.value)}
                                            />
                                            {errors[`documents.inn`] && (
                                    <p className="text-red-500 text-sm mt-1">Необходимо указать ИНН</p>
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
