<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\Specimen::where('nama_spesimen', 'NTAHLA')->first();
echo $s ? json_encode(['id' => $s->id, 'gambar' => $s->gambar, 'galeri' => $s->galeri]) : 'Not found';
