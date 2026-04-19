<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hall;

$halls = Hall::select('id','name','location','capacity','category','status')->take(20)->get();
echo 'count=' . $halls->count() . PHP_EOL;
foreach ($halls as $h) {
    echo $h->id . ' | ' . $h->name . ' | ' . $h->location . ' | ' . $h->capacity . ' | ' . $h->category . ' | ' . $h->status . PHP_EOL;
}
