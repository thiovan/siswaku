<?php
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

// Memuat file helper
foreach (glob(__DIR__ . '/helpers/' . '*.php') as $file) {
  require_once $file;
}

// Ambil URL dari query string
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Cek apakah permintaan adalah untuk file CSS, JS, atau gambar
if (preg_match('/^assets\/(css|js|images)\/(.+)$/', $url, $matches)) {
  Route::loadAssets($matches[2], $matches[1]); // Memanggil loadAssets dengan nama file dan jenis
} else {
  // Panggil fungsi routing
  echo Route::handle($url);
}