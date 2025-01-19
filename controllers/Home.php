<?php

require_once 'models/Student.php';

class Home
{
  public function index()
  {
    // Memeriksa apakah pengguna sudah login
    if (isset($_SESSION['user_id'])) {

      $student = new Student();

      // Filter siswa berdasarkan kelas
      if (isset($_GET['nis']) && !empty($_GET['nis'])) {
        $students = $student->getStudentByNIS($_GET['nis']);
      } elseif (isset($_GET['kelas']) && !empty($_GET['kelas'])) {
        $students = $student->filterByClass($_GET['kelas']);
      } else {
        $students = $student->getAllStudents();
      }

      // Mengambil data kelas
      $classes = $student->getDistinctClasses();

      // Menampilkan halaman utama dengan data
      return Route::view(
        'home',
        [
          'studentCount' => count($students),
          'students' => $students,
          'classes' => $classes,
          'classCount' => count($classes)
        ]
      );
    }

    // Mengalihkan ke halaman login jika belum login
    return Route::redirect('/login');
  }
}
