<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hall;

// محاكاة البحث عن "طلخا - الدقهليه"
$searchLocation = "طلخا - الدقهليه";
echo "البحث عن: '$searchLocation'\n\n";

// البحث كما هو في الكود
$query = Hall::query();
$query->where(function($q) use ($searchLocation) {
    // البحث في الموقع كاملاً
    $q->where('location', 'like', '%' . $searchLocation . '%')
      // أو البحث في كل كلمة منفصلة
      ->orWhere(function($subQ) use ($searchLocation) {
          $words = explode(' ', $searchLocation);
          foreach ($words as $word) {
              $word = trim($word);
              if (!empty($word)) {
                  $subQ->where('location', 'like', '%' . $word . '%');
              }
          }
      });
});

$results = $query->get();
echo "عدد النتائج: " . $results->count() . "\n";

foreach($results as $hall) {
    echo "- " . $hall->name . " | " . $hall->location . "\n";
}