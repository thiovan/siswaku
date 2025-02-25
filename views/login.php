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
        <form action="login/auth" method="post">
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
