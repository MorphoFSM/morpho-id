<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$supabaseUrl = env('SUPABASE_URL');
$supabaseKey = env('SUPABASE_KEY');
$cleanBaseUrl = str_replace('/rest/v1', '', rtrim($supabaseUrl, '/'));
$file = new Illuminate\Http\UploadedFile('C:/laragon/www/MorphoID/public/index.php', 'index.php', null, 0, true);
$endpoint = $cleanBaseUrl . '/storage/v1/object/natey/uploads/test.php';

$response = Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . $supabaseKey,
    'apikey' => $supabaseKey,
    'Content-Type' => 'text/php'
])->withBody(file_get_contents($file), 'text/php')->post($endpoint);

echo $response->body();
