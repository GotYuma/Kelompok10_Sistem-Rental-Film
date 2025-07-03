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

            // Query untuk SUM Stok sdfsdf
            $sql_max_harga_film = "SELECT judul, hargasewa FROM kasetfilm WHERE hargasewa = (SELECT MAX(hargasewa) FROM kasetfilm) LIMIT 1";
            $result_max_harga_film = $koneksi->query($sql_max_harga_film);
            $max_film_data = null;
            if ($result_max_harga_film && $result_max_harga_film->num_rows > 0) {
                $max_film_data = $result_max_harga_film->fetch_assoc();
            }
            $max_harga = $max_film_data ? $max_film_data['hargasewa'] : 0;
            $max_film_judul = $max_film_data ? $max_film_data['judul'] : 'N/A';
            // Query untuk MIN Harga Sewa
            $sql_min_harga_film = "SELECT judul, hargasewa FROM kasetfilm WHERE hargasewa = (SELECT MIN(hargasewa) FROM kasetfilm) LIMIT 1";
            $result_min_harga_film = $koneksi->query($sql_min_harga_film);
            $min_film_data = null;
            if ($result_min_harga_film && $result_min_harga_film->num_rows > 0) {
                $min_film_data = $result_min_harga_film->fetch_assoc();
            }
            $min_harga = $min_film_data ? $min_film_data['hargasewa'] : 0;
            $min_film_judul = $min_film_data ? $min_film_data['judul'] : 'N/A';
            ?>
            <div>
                <p>Total Film Stock : <?php echo htmlspecialchars($total_tersedia); ?></p>
            </div>
            <div>
                <p>Highest Rental Price : Rp.<?php echo htmlspecialchars(number_format($max_harga, 0, ',', '.')); ?> (Film: <?php echo htmlspecialchars($max_film_judul); ?>)</p>
            </div>
            <div>
                <p>Lowest Rental Price : Rp.<?php echo htmlspecialchars(number_format($min_harga, 0, ',', '.')); ?> (Film: <?php echo htmlspecialchars($min_film_judul); ?>)</p>
            </div>
        </div>

        <!-- SQL : View -->
        <div>
            <?php
            $sql_view_film_murah = "select * from film_murah_tersedia";
            $result_film_murah = $koneksi->query($sql_view_film_murah);

            // Cek isi view
            if ($result_film_murah && $result_film_murah->num_rows > 0) {
                echo "<h3>Film Murah Dibawah Rata - Rata:</h3>";
            ?>
                <!-- membuat tabel utk menampilkan isi view --> 
                <table class="film-table">
                    <thead>
                        <tr>
                            <th>Judul Film</th>
                            <th>Harga Sewa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Start looping
                        while ($film_murah_row = $result_film_murah->fetch_assoc()) {
                            $nama_film_terpilih = htmlspecialchars($film_murah_row['judul']);
                            $harga_film_terpilih = htmlspecialchars(number_format($film_murah_row['hargasewa'], 0, ',', '.'));
                        ?>
                            <tr>
                                <td><?php echo $nama_film_terpilih; ?></td>
                                <td>Rp.<?php echo $harga_film_terpilih; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
                // pesan kalo gaada isi view
                echo "<p>No films found below average price.</p>";
            }
            ?>
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