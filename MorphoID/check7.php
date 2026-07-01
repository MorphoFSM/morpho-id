<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\Specimen::latest()->first();
echo json_encode(['id' => $s->id, 'nama' => $s->nama_spesimen, 'gambar' => $s->gambar, 'galeri' => $s->galeri]);
