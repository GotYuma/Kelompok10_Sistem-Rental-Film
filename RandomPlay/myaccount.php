<?php include 'config.php';

if (!isset($_SESSION['customer'])) {
    header("Location: customer.php");
    exit();
}

$customer = $_SESSION['customer'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="style.css">
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
        <h2>Welcome, <?php echo htmlspecialchars($customer['nama']); ?></h2>

        <div class="account-info">
            <h3>Account Information</h3>
            <p>Email: <?php echo htmlspecialchars($customer['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($customer['telpon']); ?></p>
            <p>Address: <?php echo htmlspecialchars($customer['alamat']); ?></p>
            <p>Member Since: <?php echo htmlspecialchars($customer['tanggaldaftar']); ?></p>
            <p>Status: <?php echo htmlspecialchars($customer['statusmember']); ?></p>
        </div>

        <div class="rental-history">
            <h3>Your Rentals</h3>
            <?php
            $sql = "SELECT r.*, k.judul 
                    FROM rent r 
                    JOIN kasetfilm k ON r.id_film = k.id_film 
                    WHERE r.id_cust = " . $customer['id_cust'] . "
                    ORDER BY r.tanggalsewa DESC";
            $result = $koneksi->query($sql);

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr><th>Film Title</th><th>Rental Date</th><th>Status</th><th>Cost</th></tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['judul']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['tanggalsewa']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                    echo '<td>$' . htmlspecialchars($row['biaya']) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo "<p>You haven't rented any films yet.</p>";
            }
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Random Play Film Rental</p>
    </footer>
</body>

</html>