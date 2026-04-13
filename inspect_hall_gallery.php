<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hall;

$hall = Hall::latest()->first();
if (! $hall) {
    echo "no hall\n";
    exit;
}

$gallery = $hall->gallery()->get();

$output = [
    'hall_id' => $hall->id,
    'gallery_count' => $gallery->count(),
    'gallery' => $gallery->map(function ($img) {
        return ['id' => $img->id, 'path' => $img->path, 'order' => $img->order];
    })->toArray(),
];

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
