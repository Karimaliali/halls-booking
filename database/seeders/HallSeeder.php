<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall;
use App\Models\User;
use App\Models\HallImage;

class HallSeeder extends Seeder
{
    public function run(): void
    {
        // Get the owner user (owner@qaa-a.com)
        $owner = User::where('email', 'owner@qaa-a.com')->first();
        if (!$owner) {
            $owner = User::create([
                'name' => 'Hall Owner',
                'email' => 'owner@qaa-a.com',
                'password' => bcrypt('owner123'),
                'role' => 'owner'
            ]);
        }

        $halls = [
            [
                'user_id' => $owner->id,
                'name' => 'قاعة المنصورة الكبرى - Mansoura Grand Hall',
                'location' => 'المنصورة - شارع الجيش',
                'capacity' => 500,
                'price' => 15000,
                'main_image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة فاخرة في قلب المنصورة، مثالية للأفراح والحفلات الكبرى. تحتوي على نظام إضاءة حديث وصوتيات عالية الجودة ومطبخ مجهز بالكامل.',
                'facilities' => 'مطبخ مجهز، نظام صوتيات، إضاءة حديثة، موقف سيارات، مولد كهرباء احتياطي، تكييف مركزي',
                'phone' => '050-1234567',
                'whatsapp' => '050-1234567',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة النيل - Nile Hall',
                'location' => 'المنصورة - طريق مصر الإسكندرية الصحراوي - قرب كوبري المنصورة',
                'capacity' => 300,
                'price' => 12000,
                'main_image' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة عصرية مطلة على النيل، مثالية للأفراح والمناسبات الخاصة. تصميم أنيق وخدمات متكاملة.',
                'facilities' => 'مطلة على النيل، تكييف مركزي، نظام صوتيات، إضاءة LED، موقف سيارات واسع، خدمة كاترينج',
                'phone' => '050-2345678',
                'whatsapp' => '050-2345678',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة الرياض - Al Riyadh Hall',
                'location' => 'المنصورة - حي الرياض - شارع الجامعة',
                'capacity' => 250,
                'price' => 10000,
                'main_image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة أنيقة في حي الرياض، قريبة من جامعة المنصورة. مناسبة للأفراح والاحتفالات العائلية.',
                'facilities' => 'قريبة من الجامعة، تكييف، صوتيات، إضاءة، موقف سيارات، خدمة تنظيف',
                'phone' => '050-3456789',
                'whatsapp' => '050-3456789',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة الزهور - Flowers Hall',
                'location' => 'المنصورة - شارع بورسعيد - أمام حديقة الزهور',
                'capacity' => 200,
                'price' => 8500,
                'main_image' => 'https://images.unsplash.com/photo-1487014679447-9f8336841d58?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة رومانسية في موقع هادئ، مثالية للأفراح الصغيرة والمناسبات الخاصة.',
                'facilities' => 'موقع هادئ، حديقة خارجية، تكييف، صوتيات، إضاءة ملونة، خدمة تزيين',
                'phone' => '050-4567890',
                'whatsapp' => '050-4567890',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة الجامعة - University Hall',
                'location' => 'المنصورة - داخل جامعة المنصورة - كلية الهندسة',
                'capacity' => 150,
                'price' => 6000,
                'main_image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة داخل جامعة المنصورة، مثالية للمؤتمرات والندوات والفعاليات الجامعية.',
                'facilities' => 'داخل الجامعة، قاعة مؤتمرات، نظام عرض، صوتيات، إنترنت، موقف سيارات',
                'phone' => '050-5678901',
                'whatsapp' => '050-5678901',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة المنارة - Al Manara Hall',
                'location' => 'المنصورة - حي المنارة - شارع الثورة',
                'capacity' => 180,
                'price' => 7500,
                'main_image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة عصرية في حي المنارة، مناسبة للأفراح والاحتفالات بمختلف الأحجام.',
                'facilities' => 'تصميم عصري، تكييف مركزي، صوتيات حديثة، إضاءة LED، خدمة كاملة',
                'phone' => '050-6789012',
                'whatsapp' => '050-6789012',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة الواحة - Oasis Hall',
                'location' => 'المنصورة - طريق المنصورة دمياط - قرب قرية ميت العز',
                'capacity' => 350,
                'price' => 13500,
                'main_image' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة واسعة في موقع استراتيجي، مثالية للأفراح الكبرى والفعاليات التجارية.',
                'facilities' => 'مساحة واسعة، حديقة خارجية، مسرح، نظام صوتيات، إضاءة احترافية، موقف سيارات كبير',
                'phone' => '050-7890123',
                'whatsapp' => '050-7890123',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة النسيم - Breeze Hall',
                'location' => 'المنصورة - شارع الجلاء - أمام نادي المنصورة الرياضي',
                'capacity' => 120,
                'price' => 5500,
                'main_image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة صغيرة ومريحة، مثالية للمناسبات العائلية والاحتفالات الصغيرة.',
                'facilities' => 'جو مريح، تكييف، صوتيات، إضاءة، خدمة شخصية، موقف سيارات',
                'phone' => '050-8901234',
                'whatsapp' => '050-8901234',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة الملكية - Royal Hall',
                'location' => 'المنصورة - شارع الملك فيصل',
                'capacity' => 400,
                'price' => 18000,
                'main_image' => 'https://images.unsplash.com/photo-1512917711951-31c68af8ea66?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة فاخرة بتصميم ملكي، مثالية للأفراح الفاخرة والمناسبات الرسمية.',
                'facilities' => 'تصميم ملكي، ديكور فاخر، نظام صوتيات عالي، إضاءة احترافية، خدمة VIP، موقف سيارات',
                'phone' => '050-9012345',
                'whatsapp' => '050-9012345',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة الشباب - Youth Hall',
                'location' => 'المنصورة - حي الشباب - شارع الجمهورية',
                'capacity' => 100,
                'price' => 4500,
                'main_image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة مناسبة للشباب والمناسبات الصغيرة، موقع مركزي وأسعار مناسبة.',
                'facilities' => 'موقع مركزي، أسعار مناسبة، تكييف، صوتيات، إضاءة، خدمة سريعة',
                'phone' => '050-0123456',
                'whatsapp' => '050-0123456',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة طلخا الشرقية - Talkha East Hall',
                'location' => 'طلخا - شارع سعد زغلول - مقابل مسجد الفتح',
                'capacity' => 180,
                'price' => 5200,
                'main_image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة حديثة في طلخا الشرقية، مناسبة للأفراح الصغيرة والمناسبات العائلية بأسعار مناسبة.',
                'facilities' => 'تكييف مركزي، نظام صوتيات، إضاءة LED، موقف سيارات، خدمة استقبال',
                'phone' => '050-3216549',
                'whatsapp' => '050-3216549',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة بلقاس الكبرى - Belqas Grand Hall',
                'location' => 'بلقاس - شارع 23 يوليو - أمام نادي بلقاس الرياضي',
                'capacity' => 240,
                'price' => 5800,
                'main_image' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة فاخرة في بلقاس، مناسبة للأفراح الكبرى والمناسبات الرسمية بخدمة محلية ممتازة.',
                'facilities' => 'تكييف، صوتيات احترافية، ديكور ذهبي، كاترينج، موقف سيارات واسع',
                'phone' => '050-6543210',
                'whatsapp' => '050-6543210',
            ],
            [
                'user_id' => $owner->id,
                'name' => 'قاعة الدقهلية - Dakahlia Celebration Hall',
                'location' => 'المنصورة - شارع بورسعيد - مقابل فندق جراند',
                'capacity' => 320,
                'price' => 10500,
                'main_image' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80',
                'description' => 'قاعة كبيرة في قلب الدقهلية، مثالية للأفراح الكبرى والمؤتمرات الرسمية.',
                'facilities' => 'مسرح، نظام صوت احترافي، إضاءة متطورة، موقف سيارات، تكييف مركزي',
                'phone' => '050-1122334',
                'whatsapp' => '050-1122334',
            ],
        ];

        foreach ($halls as $hallData) {
            $hall = Hall::updateOrCreate([
                'name' => $hallData['name'],
                'location' => $hallData['location'],
            ], $hallData);

            // إضافة صور إضافية للقاعة الأولى كمثال
            if ($hall->name === 'قاعة المنصورة الكبرى - Mansoura Grand Hall') {
                HallImage::updateOrCreate([
                    'hall_id' => $hall->id,
                    'order' => 1,
                ], [
                    'path' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80',
                ]);
                HallImage::updateOrCreate([
                    'hall_id' => $hall->id,
                    'order' => 2,
                ], [
                    'path' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=800&q=80',
                ]);
                HallImage::updateOrCreate([
                    'hall_id' => $hall->id,
                    'order' => 3,
                ], [
                    'path' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80',
                ]);
            }
        }
    }
}