<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        $countries = [
            [
                'iso_name' => 'RU',
                'en_name' => 'Russia',
                'ru_name' => 'Россия',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'iso_name' => 'DE',
                'en_name' => 'Germany',
                'ru_name' => 'Германия',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'iso_name' => 'FR',
                'en_name' => 'France',
                'ru_name' => 'Франция',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'iso_name' => 'JP',
                'en_name' => 'Japan',
                'ru_name' => 'Япония',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'iso_name' => 'BR',
                'en_name' => 'Brazil',
                'ru_name' => 'Бразилия',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('countries')->insert($countries);
    }
}