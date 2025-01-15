<?php

class Home
{
  public function index()
  {
    if (isset($_SESSION['user_id'])) {

      // Menampilkan halaman utama
      return Route::view('home');

    } else {

      // Redirect ke halaman login
      return Route::redirect('/login');

    }
  }
}
