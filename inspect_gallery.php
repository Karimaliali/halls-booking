<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hall;

$halls = Hall::with('gallery')->get();

foreach ($halls as $h) {
    echo "Hall ID: {$h->id}, Name: {$h->name}, Gallery count: " . $h->gallery->count() . "\n";
    foreach ($h->gallery as $img) {
        echo "  Image ID: {$img->id}, Path: {$img->path}, Order: {$img->order}\n";
    }
}