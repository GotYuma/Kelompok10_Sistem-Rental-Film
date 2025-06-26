<?php
include 'config.php'; // Include database connection and session start

// Redirect if customer is not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer.php"); // Redirect to login/register page
    exit();
}

// Get customer data from session
$customer = $_SESSION['customer'];

// No need to fetch from DB again here, as $_SESSION['customer'] should be up-to-date
// after login or initial registration.
// If you want to ensure the absolute latest data, you would re-query the DB using $customer['id_cust']
// For this example, we'll rely on the session data for pre-filling.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <link rel="stylesheet" href="randomcihuy.css">
</head>
<body>
    <header>
        <h1>Random Play Film Rental</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="myaccount.php">My Account</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="register-form">
            <h2>Update Your Account Information</h2>
            <form action="update_process.php" method="post">
                <input type="hidden" name="id_cust" value="<?php echo htmlspecialchars($customer['id_cust']); ?>">

                <label for="nama">Full Name:</label>
                <input type="text" id="nama" name="nama" placeholder="Full Name" value="<?php echo htmlspecialchars($customer['nama']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>

                <label for="telpon">Phone Number:</label>
                <input type="tel" id="telpon" name="telpon" placeholder="Phone Number" value="<?php echo htmlspecialchars($customer['telpon']); ?>" required>

                <label for="alamat">Address:</label>
                <input type="text" id="alamat" name="alamat" placeholder="Address" value="<?php echo htmlspecialchars($customer['alamat']); ?>" required>

                <input type="submit" value="Update Account">
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Random Play Film Rental</p>
    </footer>
</body>
</html>