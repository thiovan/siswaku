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
      <a class="navbar-brand" href="/">
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
          <li><a class="dropdown-item text-danger" href="/logout">Keluar</a></li>
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
            <h4 class="text-center"><?php echo isset($data['nis']) ? 'Ubah' : 'Tambah'; ?> Data Siswa</h4>
          </div>
          <div class="card-body">
            <form action="<?php echo isset($data['nis']) ? 'form/update' : 'form/insert'; ?>" method="post" novalidate>
              <?php
              if (isset($_SESSION['flash_message'])) {
                $validation = $_SESSION['flash_message']['validation'];
                $data = $_SESSION['flash_message']['data'];
              }
              ?>

              <!-- Menampilkan field NIS -->
              <div class="mb-3">
                <label for="nis" class="form-label">NIS</label>
                <input
                  type="number"
                  class="form-control <?php echo isset($validation['nis']) && !$validation['nis'] ? 'is-invalid' : ''; ?>"
                  value="<?php echo isset($data['nis']) ? $data['nis'] : ''; ?>"
                  name="nis"
                  pattern="[0-9]+"
                  <?php echo isset($data['nis']) ? 'readonly' : ''; ?>
                  required>
                <div class="invalid-feedback">NIS harus berupa angka dan unik</div>
              </div>

              <!-- Menampilkan field Nama Lengkap -->
              <div class="mb-3">
                <label for="fullname" class="form-label">Nama Lengkap</label>
                <input
                  type="text"
                  class="form-control <?php echo isset($validation['fullname']) && !$validation['fullname'] ? 'is-invalid' : ''; ?>"
                  value="<?php echo isset($data['fullname']) ? $data['fullname'] : ''; ?>"
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
                  value="<?php echo isset($data['class']) ? $data['class'] : ''; ?>"
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
                  required><?php echo isset($data['address']) ? $data['address'] : ''; ?></textarea>
                <div class="invalid-feedback">Alamat harus diisi</div>
              </div>

              <?php unset($_SESSION['flash_message']); ?>

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