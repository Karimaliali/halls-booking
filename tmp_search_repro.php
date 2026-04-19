<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hall;
use Illuminate\Http\Request;

function searchResults($params) {
    $query = Hall::query();

    if ($location = $params['location'] ?? null) {
        $location = trim($location);
        $query->where(function($q) use ($location) {
            $q->where('location', 'like', '%' . $location . '%')
              ->orWhere(function($subQ) use ($location) {
                  $words = preg_split('/\s+/', $location);
                  foreach ($words as $word) {
                      $word = trim($word);
                      if (!empty($word)) {
                          $subQ->orWhere('location', 'like', '%' . $word . '%');
                      }
                  }
              });
        });
    }

    if ($category = $params['category'] ?? null) {
        $query->where('category', $category);
    }

    if ($feature = $params['feature'] ?? null) {
        $query->whereJsonContains('features', $feature);
    }

    if ($minPrice = $params['min_price'] ?? null) {
        $query->where('price', '>=', floatval($minPrice));
    }

    if ($maxPrice = $params['max_price'] ?? null) {
        $query->where('price', '<=', floatval($maxPrice));
    }

    if ($guests = $params['guests'] ?? null) {
        if (str_contains($guests, '-')) {
            [$min, $max] = array_map('trim', explode('-', $guests, 2));
            $min = intval(preg_replace('/[^0-9]/', '', $min));
            $max = intval(preg_replace('/[^0-9]/', '', $max));
            if ($min) {
                $query->where('capacity', '>=', $min);
            }
            if ($max) {
                $query->where('capacity', '<=', $max);
            }
        } elseif (str_contains($guests, 'أقل') || str_contains($guests, 'اقل')) {
            $num = intval(preg_replace('/[^0-9]/', '', $guests));
            if ($num) {
                $query->where('capacity', '<=', $num);
            }
        } elseif (str_contains($guests, 'أكثر') || str_contains($guests, 'اكثر')) {
            $num = intval(preg_replace('/[^0-9]/', '', $guests));
            if ($num) {
                $query->where('capacity', '>=', $num);
            }
        }
    }

    return $query->get();
}

$cases = [
    ['location' => 'المنصورة', 'guests' => '100 - 300'],
    ['location' => 'طلخا', 'guests' => '100 - 300'],
    ['location' => 'المنصورة - الدقهلية', 'guests' => '100 - 300'],
    ['location' => 'طلخا - الدقهلية', 'guests' => '100 - 300'],
    ['location' => 'المنصورة', 'guests' => 'أكثر من 600'],
];

foreach ($cases as $case) {
    echo "=== Query: " . json_encode($case, JSON_UNESCAPED_UNICODE) . " ===\n";
    $results = searchResults($case);
    echo 'count=' . $results->count() . PHP_EOL;
    foreach ($results as $h) {
        echo $h->id . ' | ' . $h->name . ' | ' . $h->location . ' | ' . $h->capacity . ' | ' . $h->category . ' | ' . $h->status . PHP_EOL;
    }
    echo "\n";
}
