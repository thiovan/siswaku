<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <script src="assets/js/bootstrap.min.js"></script>
</head>

<body class="min-vh-100">
    <!-- Menampilkan Navigasi -->
    <nav class="navbar bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="assets/images/logo-gray.png" width="64">
            </a>
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
    <main class="container text-center my-4">

        <h3 class="mb-5">SISTEM PENDATAAN SISWA</h3>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card text-bg-light mb-3">
                    <h5 class="card-header">Jumlah Siswa</h5>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $studentCount; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card text-bg-light mb-3">
                    <h5 class="card-header">Jumlah Kelas</h5>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $classCount; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 mb-2">
            <div class="col-12 col-md-2">
                <div class="d-grid gap-2">
                    <a class="btn btn-primary btn-sm" href="/form">+ Tambah Siswa</a>
                </div>
            </div>

            <div class="col-12 col-md-3 offset-md-3">
                <form class="d-flex" role="filter" method="get">
                    <select class="form-select me-2 form-select-sm" name="kelas" aria-label="Pilih Kelas">
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class; ?>"><?php echo $class; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-success btn-sm" type="submit">Filter</button>
                </form>
            </div>

            <div class="col-12 col-md-3">
                <form class="d-flex" role="search" method="get">
                    <input class="form-control me-2 form-control-sm" type="search" placeholder="Masukkan NIS" name="nis">
                    <button class="btn btn-success btn-sm" type="submit">Cari</button>
                </form>
            </div>

            <div class="col-12 col-md-1">
                <div class="d-grid gap-2 text-center">
                    <button class="btn btn-secondary btn-sm" type="button" onclick="window.print()">Cetak</button>
                </div>
            </div>
        </div>

        <div class="table-responsive border rounded">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Alamat</th>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <th scope="col" class="d-print-none">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) == 0) { ?>
                        <tr>
                            <td colspan="5" class="text-center">Data siswa tidak ditemukan</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($students as $student) { ?>
                            <tr>
                                <th scope="row"><?php echo htmlspecialchars($student['nis']); ?></th>
                                <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($student['class']); ?></td>
                                <td><?php echo htmlspecialchars($student['address']); ?></td>
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                                    <td class="d-print-none">
                                        <a href="form?nis=<?php echo $student['nis']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Yakin ingin mengubah data siswa ini?')">Ubah</a>
                                        <a href="form/delete?nis=<?php echo $student['nis']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data siswa ini?')">Hapus</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </main>
    <!-- / Menampilkan Konten -->

    <!-- Menampilkan Footer -->
    <footer class="fixed-bottom w-100">
        <ul class="nav border-bottom pb-3 mb-3">
        </ul>
        <p class="text-center text-body-secondary">Copyright &copy; 2025. Developed by <?php echo APP_AUTHOR; ?></p>
    </footer>
    <!-- / Menampilkan Footer -->
</body>


</html>
