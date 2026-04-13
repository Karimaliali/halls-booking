<?php
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\Hall;

// محاكاة طلب البحث
$request = new Request();
$request->merge([
    'location' => 'طلخا - الدقهليه'
]);

$query = Hall::query();

if ($location = $request->query('location')) {
    $location = trim($location);
    $query->where(function($q) use ($location) {
        $q->where('location', 'like', '%' . $location . '%')
          ->orWhere(function($subQ) use ($location) {
              $words = explode(' ', $location);
              foreach ($words as $word) {
                  $word = trim($word);
                  if (!empty($word)) {
                      $subQ->where('location', 'like', '%' . $word . '%');
                  }
              }
          });
    });
}

$halls = $query->get();

echo "البحث عن: 'طلخا - الدقهليه'\n";
echo "عدد النتائج: " . $halls->count() . "\n\n";

if ($halls->count() > 0) {
    foreach ($halls as $hall) {
        echo "القاعة: " . $hall->name . "\n";
        echo "الموقع: " . $hall->location . "\n";
        echo "السعر: " . $hall->price . "\n";
        echo "---\n";
    }
} else {
    echo "لا توجد نتائج\n";
}