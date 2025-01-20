<?php

require_once 'config.php';
session_start();

// Fungsi untuk menghubungkan ke database
function connectDb()
{
  try {
    $DB = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $DB;
  } catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
  }
}

// Fungsi untuk memeriksa apakah pengguna sudah login
function isLoggedIn()
{
  return isset($_SESSION['user_id']);
}

// Fungsi untuk melakukan validasi data
function validate($data, $DB)
{
  if (!$data) {
    return false;
  }

  $validation = [
    'nis' => true,
    'fullname' => true,
    'class' => true,
    'address' => true
  ];

  // Validasi NIS harus berupa angka dan unik
  $query = "SELECT * FROM students WHERE nis = :nis";
  $stmt = $DB->prepare($query);
  $stmt->bindParam(':nis', $data['nis']);
  $stmt->execute();
  $existingStudent = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (!is_numeric($data['nis']) && !empty($existingStudent)) {
    $validation['nis'] = false;
  }

  // Validasi Nama hanya boleh berisi huruf
  if (!preg_match('/^[a-zA-Z ]+$/', $data['fullname'])) {
    $validation['fullname'] = false;
  }

  // Validasi Kelas hanya boleh berisi huruf dan angka
  if (!preg_match('/^[a-zA-Z0-9]+$/', $data['class'])) {
    $validation['class'] = false;
  }

  // Validasi Alamat harus diisi
  if (empty($data['address'])) {
    $validation['address'] = false;
  }

  return $validation;
}

$DB = connectDb();

if (!isLoggedIn()) {

  header('location: login.php');
  exit();
}

// Muat data siswa ketika NIS diberikan
if (isset($_GET['nis'])) {

  // Mengambil data siswa berdasarkan NIS
  $query = "SELECT * FROM students WHERE nis = :nis";
  $stmt = $DB->prepare($query);
  $stmt->bindParam(':nis', $_GET['nis']);
  $stmt->execute();
  $student = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle tambah siswa
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
  $validation = validate($_POST, $DB);

  if ($validation['nis'] && $validation['fullname'] && $validation['class'] && $validation['address']) {
    $query = "INSERT INTO students (nis, fullname, class, address) VALUES (:nis, :fullname, :class, :address)";
    $stmt = $DB->prepare($query);
    $stmt->bindParam(':nis', $_POST['nis']);
    $stmt->bindParam(':fullname', $_POST['fullname']);
    $stmt->bindParam(':class', $_POST['class']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->execute();

    header('location: index.php');
    exit();
  }
}

// Handle ubah siswa
if (isset($_POST['action']) && $_POST['action'] == 'update') {
  $validation = validate($_POST, $DB);

  if ($validation['nis'] && $validation['fullname'] && $validation['class'] && $validation['address']) {
    $query = "UPDATE students SET fullname = :fullname, class = :class, address = :address WHERE nis = :nis";
    $stmt = $DB->prepare($query);
    $stmt->bindParam(':fullname', $_POST['fullname']);
    $stmt->bindParam(':class', $_POST['class']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->bindParam(':nis', $_POST['nis']);
    $stmt->execute();

    header('location: index.php');
    exit();
  }
}

// Handle hapus siswa
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
  $query = "DELETE FROM students WHERE nis = :nis";
  $stmt = $DB->prepare($query);
  $stmt->bindParam(':nis', $_GET['nis']);
  $stmt->execute();

  header('location: index.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP_NAME; ?></title>

  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <script src="assets/js/bootstrap.min.js"></script>
</head>

<body class="min-vh-100 elegant-background">

  <!-- Menampilkan Navigasi -->
  <nav class="navbar bg-body-tertiary">
    <div class="container">

      <!-- Logo -->
      <a class="navbar-brand" href="index.php">
        <img src="assets/images/logo-gray.png" width="64">
      </a>

      <!-- Menampilkan Dropdown Profile -->
      <div class="dropdown text-end">
        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="assets/images/icon-user.png" alt="mdo" width="32" height="32" class="rounded-circle">
        </a>
        <ul class="dropdown-menu text-small">
          <li>
            <div class="dropdown-item disabled text-black"><?php echo $_SESSION['user_fullname'] ?></div>
          </li>
          <li><small class="dropdown-item disabled text-black"><?php echo $_SESSION['user_role'] ?></small></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item text-danger" href="logout.php">Keluar</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- / Menampilkan Navigasi -->

  <!-- Menampilkan Konten -->
  <main class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header">
            <h4 class="text-center"><?php echo isset($student) ? 'Ubah' : 'Tambah'; ?> Data Siswa</h4>
          </div>
          <div class="card-body">
            <form action="form.php" method="post" novalidate>

              <input type="hidden" name="action" value="<?php echo isset($student) ? 'update' : 'insert'; ?>">

              <!-- Menampilkan field NIS -->
              <div class="mb-3">
                <label for="nis" class="form-label">NIS</label>
                <input
                  type="number"
                  class="form-control <?php echo isset($validation['nis']) && !$validation['nis'] ? 'is-invalid' : ''; ?>"
                  value="<?php echo isset($student['nis']) ? $student['nis'] : ''; ?>"
                  name="nis"
                  pattern="[0-9]+"
                  <?php echo isset($student['nis']) ? 'readonly' : ''; ?>
                  required>
                <div class="invalid-feedback">NIS harus berupa angka dan unik</div>
              </div>

              <!-- Menampilkan field Nama Lengkap -->
              <div class="mb-3">
                <label for="fullname" class="form-label">Nama Lengkap</label>
                <input
                  type="text"
                  class="form-control <?php echo isset($validation['fullname']) && !$validation['fullname'] ? 'is-invalid' : ''; ?>"
                  value="<?php echo isset($student['fullname']) ? $student['fullname'] : ''; ?>"
                  name="fullname"
                  pattern="[a-zA-Z\s]+"
                  required>
                <div class="invalid-feedback">Nama hanya boleh berisi huruf</div>
              </div>

              <!-- Menampilkan field Kelas -->
              <div class="mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <input
                  type="text"
                  class="form-control <?php echo isset($validation['class']) && !$validation['class'] ? 'is-invalid' : ''; ?>"
                  value="<?php echo isset($student['class']) ? $student['class'] : ''; ?>"
                  name="class"
                  pattern="[a-zA-Z0-9]+"
                  required>
                <div class="invalid-feedback">Kelas hanya boleh berisi huruf dan angka</div>
              </div>

              <!-- Menampilkan field Alamat -->
              <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea
                  class="form-control <?php echo isset($validation['address']) && !$validation['address'] ? 'is-invalid' : ''; ?>"
                  name="address"
                  rows="3"
                  required><?php echo isset($student['address']) ? $student['address'] : ''; ?></textarea>
                <div class="invalid-feedback">Alamat harus diisi</div>
              </div>

              <!-- Tombol Simpan -->
              <button type="submit" class="btn btn-lg btn-primary w-100">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Menampilkan Footer -->
  <footer class="fixed-bottom w-100">
    <ul class="nav border-bottom pb-3 mb-3">
    </ul>
    <p class="text-center text-body-secondary">Copyright &copy; 2025. Developed by <?php echo APP_AUTHOR; ?></p>
  </footer>
  <!-- / Menampilkan Footer -->

</body>

</html>