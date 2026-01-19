<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall; 

class HallSeeder extends Seeder
{
    public function run(): void
    {
        // القاعة الأولى
        Hall::create([
            'name' => 'قاعة الملكة',
            'location' => 'القاهرة - مدينة نصر',
            'capacity' => 200,
            'price' => 5000.00
        ]);

        // القاعة الثانية
        Hall::create([
            'name' => 'قاعة الزمردة',
            'location' => 'الجيزة - الهرم',
            'capacity' => 350,
            'price' => 7500.00
        ]);

        // القاعة الثالثة
        Hall::create([
            'name' => 'قاعة لؤلؤة النيل',
            'location' => 'المعادي',
            'capacity' => 150,
            'price' => 4000.00
        ]);
    }
}