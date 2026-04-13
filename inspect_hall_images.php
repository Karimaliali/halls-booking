<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hall;

$h = Hall::latest()->first();

if (! $h) {
    echo "no hall\n";
    exit(0);
}

echo json_encode([
    'id' => $h->id,
    'name' => $h->name,
    'images' => $h->images,
    'main_image' => $h->main_image,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
