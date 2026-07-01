<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$_FILES = [
    'gambar' => [
        'name' => ['test.jpg'],
        'type' => ['image/jpeg'],
        'tmp_name' => ['C:/laragon/www/MorphoID/public/index.php'],
        'error' => [0],
        'size' => [100]
    ]
];
$req = Illuminate\Http\Request::capture();
var_dump($req->hasFile('gambar'));
var_dump($req->file('gambar'));
