<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\Specimen::find(26);
echo json_encode(['gambar' => $s->gambar, 'galeri' => $s->galeri]);
