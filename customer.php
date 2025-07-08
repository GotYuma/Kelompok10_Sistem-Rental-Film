<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login/Register</title>
    <link rel="stylesheet" href="randomcihuy.css">
</head>

<body>
    <header>
        <h1>Random Play Film Rental</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="customer.php">Login/Register</a>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            <div class="login-form">
                <h2>Login</h2>
                <form action="login_process.php" method="post">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="submit" value="Login">
                </form>
            </div>

            <div class="register-form">
                <h2>Register</h2>
                <form action="register_process.php" method="post">
                    <input type="text" name="nama" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="tel" name="telpon" placeholder="Phone Number" required>
                    <input type="text" name="alamat" placeholder="Address" required>
                    <input type="submit" value="Register">
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Random Play Film Rental</p>
    </footer>
</body>

</html>