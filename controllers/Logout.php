<?php

class Logout
{
    public function index()
    {
      
        // Menghapus semua variabel sesi
        session_unset();
        
        // Menghancurkan sesi
        session_destroy();

        // Mengalihkan ke halaman login
        return Route::redirect('/login');
    }
}
