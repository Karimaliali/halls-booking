<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hall;

echo "جميع المواقع في قاعدة البيانات:\n";
$locations = Hall::select('location')->distinct()->get();

foreach($locations as $loc) {
    echo "- " . $loc->location . "\n";
}

echo "\nالبحث عن طلخا:\n";
$talaqhaHalls = Hall::where('location', 'like', '%طلخا%')->get();
echo "عدد القاعات: " . $talaqhaHalls->count() . "\n";

foreach($talaqhaHalls as $hall) {
    echo "- " . $hall->name . " | " . $hall->location . "\n";
}

echo "\nالبحث عن الدقهليه:\n";
$daqahliaHalls = Hall::where('location', 'like', '%الدقهليه%')->get();
echo "عدد القاعات: " . $daqahliaHalls->count() . "\n";

foreach($daqahliaHalls as $hall) {
    echo "- " . $hall->name . " | " . $hall->location . "\n";
}