<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$revenue = \App\Models\Booking::where('status', 'completed')->sum('total_price');
$count = \App\Models\Booking::where('status', 'completed')->count();
$all = \App\Models\Booking::all();
echo "Revenue: " . $revenue . "\n";
echo "Count: " . $count . "\n";
foreach ($all as $b) {
    echo "ID: " . $b->id . " Status: " . $b->status . " Price: " . $b->total_price . "\n";
}
