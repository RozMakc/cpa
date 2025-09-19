import EcommerceMetrics from '@/Components/dashboard/EcommerceMetrics';
import MonthlySalesChart from '@/Components/dashboard/MonthlySalesChart';
import RecentOrders from '@/Components/dashboard/RecentOrders';
import StatisticsChart from '@/Components/dashboard/StatisticsChart';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';


export default function Activation({leads, users_count = 0, offers_count, clicks_count, leads_count}) {
  return (
    <AuthenticatedLayout
        pageTitle="Главная"
    >
        <Head title="Главная" />

        <div className="grid grid-cols-12 gap-4 md:gap-6">
            <div className="col-span-12 text-center uppercase mt-10 text-yellow-500 font-bold text-xl">
              Дождитесь активации аккаунта!
            </div>
        </div>
    </AuthenticatedLayout>
  );
}