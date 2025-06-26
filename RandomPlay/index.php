<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Play - Film Rental</title>
    <link rel="stylesheet" href="randomcihuy.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Exo+2:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Random Play Film Rental</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['customer'])): ?>
                <a href="myaccount.php">My Account</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="customer.php">Login/Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>Film Statistics</h2>
        <div>
            <?php
            // Query untuk SUM Stok
            $sql_sum_tersedia = "SELECT SUM(tersedia) FROM kasetfilm";
            $result_sum_tersedia = $koneksi->query($sql_sum_tersedia);
            $total_tersedia = $result_sum_tersedia->fetch_assoc()['SUM(tersedia)'];

            // Query untuk MAX Harga Sewa
            $sql_max_harga = "SELECT MAX(hargasewa) FROM kasetfilm";
            $result_max_harga = $koneksi->query($sql_max_harga);
            $max_harga = $result_max_harga->fetch_assoc()['MAX(hargasewa)'];


            // Query untuk MIN Harga Sewa
            $sql_min_harga = "SELECT MIN(hargasewa) FROM kasetfilm";
            $result_min_harga = $koneksi->query($sql_min_harga);
            $min_harga = $result_min_harga->fetch_assoc()['MIN(hargasewa)'];
            ?>
            <div>
                <p>Total Film Stock : <?php echo htmlspecialchars($total_tersedia); ?></p>
            </div>
            <div>
                <p>Highest Rental Price : Rp.<?php echo htmlspecialchars(number_format($max_harga, 0, ',', '.')); ?></p>
            </div>
            <div>
                <p>Lowest Rental Price : Rp.<?php echo htmlspecialchars(number_format($min_harga, 0, ',', '.')); ?></p>
            </div>
        </div>

        <h2>Available Films</h2>
        <div class="film-grid">
            <?php
            $sql = "SELECT * FROM kasetfilm WHERE tersedia >= 1";
            $result = $koneksi->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="film-card">';
                    echo '<h3>' . htmlspecialchars($row['judul']) . '</h3>';
                    echo '<p>Genre: ' . htmlspecialchars($row['genre']) . '</p>';
                    echo '<p>Year: ' . htmlspecialchars($row['tahunrilis']) . '</p>';
                    echo '<p>Rating: ' . htmlspecialchars($row['rating']) . '/10</p>';
                    echo '<p>Stock: ' . htmlspecialchars($row['stok']) . '</p>';
                    echo '<p>Price: Rp.' . htmlspecialchars($row['hargasewa']) . '</p>';

                    if (isset($_SESSION['customer'])) {
                        echo '<form action="rent.php" method="post">';
                        echo '<input type="hidden" name="id_film" value="' . $row['id_film'] . '">';
                        echo '<input type="submit" value="Rent This Film">';
                        echo '</form>';
                    }

                    echo '</div>';
                }
            } else {
                echo "<p>No films available at the moment.</p>";
            }
            ?>
        </div>
    </main>
</body>

</html>