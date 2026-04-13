<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hall;

$hall = Hall::with('gallery')->find(8);
if (!$hall) {
    echo "Hall 8 not found\n";
    exit(0);
}

echo "ID=" . $hall->id . " name=" . $hall->name . "\n";
echo "main_image=" . ($hall->main_image ?? 'null') . "\n";
echo "main_exists=" . (\Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($hall->main_image ?? '', '/')) ? 'yes' : 'no') . "\n";
echo "main_url=" . $hall->main_image_url . "\n";
echo "first_url=" . $hall->first_image_url . "\n";
echo "gallery count=" . $hall->gallery->count() . "\n";
foreach ($hall->gallery as $img) {
    $path = $img->path ?? 'null';
    echo " gallery=" . $path . " exists=" . (\Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($path, '/')) ? 'yes' : 'no') . "\n";
}
