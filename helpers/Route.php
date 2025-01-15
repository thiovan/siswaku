<?php
session_start();

class Route
{
  public static function handle($url)
  {
    // Cek apakah URL adalah untuk file aset
    if (preg_match('/^assets\/(css|js|images)\/(.+)$/', $url, $matches)) {
      // Jika ya, kita tidak perlu memproses lebih lanjut
      return; // Mengabaikan permintaan ini
    }

    // Pisahkan URL menjadi bagian-bagian
    $parts = explode('/', trim($url, '/'));

    // Ambil controller dan method
    $controller = !empty($parts[0]) ? $parts[0] : 'home';
    $method = !empty($parts[1]) ? $parts[1] : 'index';

    // Tentukan path ke file controller
    $controllerFile = 'controllers/' . $controller . '.php';

    // Cek apakah file controller ada
    if (file_exists($controllerFile)) {
      require_once $controllerFile;

      // Cek apakah method ada dalam controller
      if (class_exists($controller) && method_exists($controller, $method)) {
        $controllerInstance = new $controller();

        // Tentukan data yang akan dikirim ke method
        $data = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : [];

        return $controllerInstance->$method($data); // Mengirim data POST atau kosong ke method
      } else {
        return 'Method not found';
      }
    } else {
      return 'Controller not found';
    }
  }

  // Metode untuk memanggil view dengan parameter
  public static function view($viewName, $data = [])
  {

    $viewFile = 'views/' . $viewName . '.php';

    if (file_exists($viewFile)) {
      // Ekstrak data menjadi variabel
      extract($data);
      require $viewFile;
    } else {
      echo 'View not found';
    }
  }

  // Metode untuk memuat file CSS dan JavaScript
  public static function loadAssets($fileName, $type)
  {
    $filePath = 'assets/' . $type . '/' . $fileName;
    if (file_exists($filePath)) {
      if ($type === 'css') {
        header('Content-Type: text/css');
      } elseif ($type === 'js') {
        header('Content-Type: application/javascript');
      } elseif ($type === 'images') {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        header('Content-Type: image/' . $ext);
      }
      readfile($filePath);
      exit;
    } else {
      echo ucfirst($type) . ' file not found';
    }
  }

  // Metode untuk melakukan redirect dengan parameter
  public static function redirect($url, $message = null)
  {
    if ($message) {
      $_SESSION['flash_message'] = $message; // Simpan pesan dalam sesi
    }
    header("Location: $url");
    exit;
  }
}
