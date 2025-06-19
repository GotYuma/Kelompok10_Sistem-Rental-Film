<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Play - Film Rental</title>
    <link rel="stylesheet" href="style.css">
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
                    echo '<p>Price: $' . htmlspecialchars($row['hargasewa']) . '</p>';

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

    <footer>
        <p>&copy; 2023 Random Play Film Rental</p>
    </footer>
</body>

</html>