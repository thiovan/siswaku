<?php
require_once 'config.php';
session_start();

// Fungsi untuk memeriksa apakah pengguna sudah login
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

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

// Fungsi untuk mengambil data semua siswa
function fetchStudents($DB)
{
    $students = [];
    if (isset($_GET['nis']) && !empty($_GET['nis'])) {
        $query = "SELECT * FROM students WHERE nis = :nis";
        $stmt = $DB->prepare($query);
        $stmt->bindParam(':nis', $_GET['nis']);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($_GET['kelas']) && !empty($_GET['kelas'])) {
        $query = "SELECT * FROM students WHERE class = :class";
        $stmt = $DB->prepare($query);
        $stmt->bindParam(':class', $_GET['kelas']);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = "SELECT * FROM students ORDER BY id DESC";
        $stmt = $DB->prepare($query);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $students;
}


// Fungsi untuk mengambil data semua kelas
function fetchClasses($DB)
{
    $query = "SELECT DISTINCT class FROM students";
    $stmt = $DB->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


$DB = connectDb();

if (!isLoggedIn()) {
    header('location: login.php');
    exit();
}

$students = fetchStudents($DB);
$classes = fetchClasses($DB);

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

<body class="min-vh-100">
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
    <main class="container text-center my-4">

        <!-- Menampilkan Logo dan Judul -->
        <img src="assets/images/logo-gray.png" width="150" class="mt-4 mb-2 d-block mx-auto">
        <h3 class="mb-5 display-6">SISTEM PENDATAAN SISWA</h3>

        <!-- Menampilkan Jumlah Siswa dan Kelas -->
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card text-bg-light mb-3">
                    <h5 class="card-header">Jumlah Siswa</h5>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo count($students); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card text-bg-light mb-3">
                    <h5 class="card-header">Jumlah Kelas</h5>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo count($classes); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menampilkan Tambahkan atau Filter Data Siswa -->
        <div class="accordion mb-2 mt-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Tambahkan atau Filter Data Siswa
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-12 col-md-2 my-1">
                                <div class="d-grid gap-2">
                                    <a class="btn btn-primary" href="form.php">+ Tambah Siswa</a>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 offset-md-3 my-1">
                                <form class="row" role="filter" method="get">
                                    <div class="col-8">
                                        <select class="form-select me-2" name="kelas" aria-label="Pilih Kelas">
                                            <option value="">Pilih Kelas</option>
                                            <?php foreach ($classes as $class): ?>
                                                <option value="<?php echo $class; ?>"><?php echo $class; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-success w-100" type="submit">Filter</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-3 my-1">
                                <form class="row" role="search" method="get">
                                    <div class="col-8">
                                        <input class="form-control me-2" type="search" placeholder="Masukkan NIS" name="nis">
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-success w-100" type="submit">Cari</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-1 my-1">
                                <div class="d-grid gap-2 text-center">
                                    <button class="btn btn-secondary" type="button" onclick="window.print()">Cetak</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menampilkan Data Siswa -->
        <div class="table-responsive rounded">
            <table class="table table-hover border">
                <thead>
                    <tr>
                        <th class="bg-primary-subtle" scope="col">NIS</th>
                        <th class="bg-primary-subtle" scope="col">Nama Lengkap</th>
                        <th class="bg-primary-subtle" scope="col">Kelas</th>
                        <th class="bg-primary-subtle" scope="col">Alamat</th>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <th class="bg-primary-subtle" scope="col" class="d-print-none">Aksi</th>
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
                                        <a href="form.php?nis=<?php echo $student['nis']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Yakin ingin mengubah data siswa ini?')">Ubah</a>
                                        <a href="form.php?action=delete&nis=<?php echo $student['nis']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data siswa ini?')">Hapus</a>
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