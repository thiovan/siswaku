<?php

require_once 'models/User.php';

class Login
{
  public function index()
  {
    if (isset($_SESSION['user_id'])) {
      // Menampilkan halaman utama   
      return Route::redirect('/');
    }

    // Menampilkan halaman login
    return Route::view('login');
  }

  public function auth()
  {
    // Mencari pengguna berdasarkan username
    $user = new User();
    $user = $user->getUserByUsername($_POST['username']);

    // Jika pengguna ditemukan dan kata sandi cocok
    if ($user && $_POST['password'] === $user['password']) {

      // Simpan informasi pengguna ke sesi
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_fullname'] = $user['fullname'];
      $_SESSION['user_username'] = $user['username'];
      $_SESSION['user_role'] = $user['role'];

      // Redirect ke dashboard
      return Route::redirect('/');
    } else {

      // Redirect ke halaman login dengan pesan error
      return Route::redirect('/login', 'Nama Pengguna atau Kata Sandi Salah');
    }
  }
}
