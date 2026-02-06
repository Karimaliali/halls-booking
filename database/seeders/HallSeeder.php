<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall;
use App\Models\User; 

class HallSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role','owner')->first();
        if(!$owner){
            $owner = User::create([
                'name' =>'صاحب القاعة',
                'email' =>'karimElshazly@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'owner'
            ]);
        }
        // القاعة الأولى
        Hall::create([
            'name' => 'قاعة الملكة',
            'location' => 'القاهرة - مدينة نصر',
            'capacity' => 200,
            'price' => 5000.00,
            'main_image' => 'elmaleka_hall.jpg',
            'user_id' =>$owner->id
        ]);

        // القاعة الثانية
        Hall::create([
            'name' => 'قاعة الزمردة',
            'location' => 'الجيزة - الهرم',
            'capacity' => 350,
            'price' => 7500.00,
            'main_image'=>'zemorda_hall.jpg',
            'user_id'=>$owner->id
        ]);

        // القاعة الثالثة
        Hall::create([
            'name' => 'قاعة لؤلؤة النيل',
            'location' => 'المعادي',
            'capacity' => 150,
            'price' => 4000.00,
            'main_image'=>'nile_hall.jpg',
            'user_id'=>$owner->id
        ]);
    }
}