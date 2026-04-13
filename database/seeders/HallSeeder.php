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

        // قاعات في طلخا
        Hall::create([
            'name' => 'قاعة الرياضة الطلخاوية',
            'location' => 'طلخا - الدقهليه',
            'capacity' => 300,
            'price' => 6000.00,
            'main_image' => 'talakha_sports_hall.jpg',
            'user_id' => $owner->id
        ]);

        Hall::create([
            'name' => 'قاعة الأفراح الطلخاوية',
            'location' => 'طلخا - الدقهليه',
            'capacity' => 250,
            'price' => 4500.00,
            'main_image' => 'talakha_wedding_hall.jpg',
            'user_id' => $owner->id
        ]);

        Hall::create([
            'name' => 'قاعة المؤتمرات الطلخاوية',
            'location' => 'طلخا - الدقهليه',
            'capacity' => 150,
            'price' => 3500.00,
            'main_image' => 'talakha_conference_hall.jpg',
            'user_id' => $owner->id
        ]);

        // قاعات في بلقاس
        Hall::create([
            'name' => 'قاعة بلقاس الكبرى',
            'location' => 'بلقاس - الدقهليه',
            'capacity' => 400,
            'price' => 8000.00,
            'main_image' => 'belqas_grand_hall.jpg',
            'user_id' => $owner->id
        ]);

        Hall::create([
            'name' => 'قاعة الأفراح بلقاس',
            'location' => 'بلقاس - الدقهليه',
            'capacity' => 200,
            'price' => 5000.00,
            'main_image' => 'belqas_wedding_hall.jpg',
            'user_id' => $owner->id
        ]);
}}