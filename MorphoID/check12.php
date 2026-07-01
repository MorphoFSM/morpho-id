<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$req = Illuminate\Http\Request::create('/test', 'POST', [], [], ['gambar' => [new Illuminate\Http\UploadedFile('C:/laragon/www/MorphoID/public/index.php', 'index.php', null, 6, true)]]);
var_dump($req->hasFile('gambar'));
