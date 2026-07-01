<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\Specimen::orderBy('id', 'desc')->take(10)->get();
foreach($s as $item) {
    echo $item->id . ' - ' . $item->nama_spesimen . ' - ' . json_encode($item->galeri) . "\n";
}
