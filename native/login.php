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

// Fungsi untuk memeriksa apakah pengguna mengirimkan formulir login
function isLoginSubmitted()
{
    return isset($_POST['username']) && isset($_POST['password']);
}

// Fungsi untuk mengotentikasi pengguna
function authenticateUser($DB, $username, $password)
{
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $DB->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        return $user;
    }
    return false;
}

// Fungsi untuk menyimpan informasi pengguna ke sesi
function saveUserSession($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_fullname'] = $user['fullname'];
    $_SESSION['user_username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
}

// Fungsi untuk menampilkan pesan error
function showError($message)
{
    $_SESSION['flash_message'] = $message;
    header('location: login.php');
    exit();
}

$DB = connectDb();

if (isLoggedIn()) {
    header('location: index.php');
    exit();
}

if (isLoginSubmitted()) {
    $user = authenticateUser($DB, $_POST['username'], $_POST['password']);
    if ($user) {
        saveUserSession($user);
        header('location: index.php');
        exit();
    } else {
        showError('Nama Pengguna atau Kata Sandi Salah');
    }
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

<body class="d-flex align-items-center py-4 min-vh-100">

    <main class="form-signin w-100 m-auto">
        <form action="login.php" method="post">
            <!-- Menampilkan Logo -->
            <img class="mb-4 mx-auto d-block" src="assets/images/logo-gray.png" alt="" width="200">

            <!-- Menampilkan Pesan Flash -->
            <?php if (isset($_SESSION['flash_message'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['flash_message']; ?>
                    <?php unset($_SESSION['flash_message']); ?>
                </div>
            <?php } ?>

            <!-- Input Nama Pengguna -->
            <div class="form-floating">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
                <label for="floatingInput">Nama Pengguna</label>
            </div>
            <!-- Input Kata Sandi -->
            <div class="form-floating">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <label for="floatingPassword">Kata Sandi</label>
            </div>

            <!-- Tombol Masuk -->
            <button class="btn btn-primary w-100 py-2" type="submit">Masuk</button>

            <!-- Menampilkan Footer -->
            <p class="mt-5 mb-3 text-body-secondary text-center">Copyright &copy; 2025. Developed by <?php echo APP_AUTHOR; ?></p>
        </form>
    </main>
</body>

</html>