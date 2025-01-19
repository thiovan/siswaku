<?php

require_once 'models/Student.php';

class Form
{
  public function index()
  {
    // Memeriksa apakah pengguna sudah login
    if (isset($_SESSION['user_id'])) {

      if (isset($_GET['nis'])) {

        // Mengambil data siswa berdasarkan NIS
        $student = new Student();
        $data = $student->getStudentByNIS($_GET['nis']);

        // Memeriksa apakah data siswa ditemukan
        if (count($data) > 0) {

          // Menampilkan halaman form dengan data siswa
          return Route::view('form', ['data' => $data[0]]);
        }
      }

      // Menampilkan halaman form
      return Route::view('form');
    }

    // Jika pengguna belum login, kembali ke halaman login
    return Route::redirect('/login');
  }

  public function insert()
  {

    // Validasi form
    $validation = $this->validate($_POST);

    // Jika semua validasi berhasil, tambahkan siswa
    if ($validation['nis'] && $validation['fullname'] && $validation['class'] && $validation['address']) {

      // Menambahkan siswa
      $student = new Student();
      $student->insertStudent($_POST['nis'], $_POST['fullname'], $_POST['class'], $_POST['address']);

      // Kembali ke halaman utama
      return Route::redirect('/');
    }

    // Jika ada validasi yang gagal, kembali ke halaman form
    return Route::redirect(
      '/form',
      [
        'validation' => $validation,
        'data' => $_POST
      ]
    );
  }

  public function update()
  {

    // Validasi form
    $validation = $this->validate($_POST);

    // Jika semua validasi berhasil, ubah siswa
    if ($validation['nis'] && $validation['fullname'] && $validation['class'] && $validation['address']) {

      // Mengubah siswa
      $student = new Student();
      $student->updateStudent($_POST['nis'], $_POST['fullname'], $_POST['class'], $_POST['address']);

      // Kembali ke halaman utama
      return Route::redirect('/');
    }

    // Jika ada validasi yang gagal, kembali ke halaman form
    return Route::redirect(
      '/form',
      [
        'validation' => $validation,
        'data' => $_POST
      ]
    );
  }

  public function delete()
  {

    // Menghapus siswa
    $student = new Student();
    $student->deleteStudent($_GET['nis']);

    // Kembali ke halaman utama
    return Route::redirect('/');
  }

  private function validate($data)
  {
    $validation = [
      'nis' => true,
      'fullname' => true,
      'class' => true,
      'address' => true
    ];

    $student = new Student();

    // Validasi NIS harus berupa angka dan unik
    $existingStudent = $student->getStudentByNIS($_POST['nis']);
    if (!is_numeric($_POST['nis']) && !empty($existingStudent)) {
      $validation['nis'] = false;
    }

    // Validasi Nama hanya boleh berisi huruf
    if (!preg_match('/^[a-zA-Z ]+$/', $_POST['fullname'])) {
      $validation['fullname'] = false;
    }

    // Validasi Kelas hanya boleh berisi huruf dan angka
    if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['class'])) {
      $validation['class'] = false;
    }

    // Validasi Alamat harus diisi
    if (empty($_POST['address'])) {
      $validation['address'] = false;
    }

    return $validation;
  }
}
