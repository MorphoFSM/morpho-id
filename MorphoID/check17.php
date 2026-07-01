<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$all = App\Models\Specimen::orderBy('id', 'desc')->get(['id', 'nama_spesimen', 'created_at']);
foreach ($all as $item) {
    echo $item->id . ' | ' . $item->nama_spesimen . ' | ' . $item->created_at . "\n";
}
