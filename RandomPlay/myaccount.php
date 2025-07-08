<?php
include 'config.php';

// Redirect jika customer belum login
if (!isset($_SESSION['customer'])) {
    header("Location: customer.php"); // Redirect ke halaman login/register
    exit();
}

$customer = $_SESSION['customer']; // Mengambil data customer dari session

// --- Tambahan untuk menangani pesan dari update_process.php atau return_process.php ---
$message = '';
$message_type = ''; // 'success' atau 'error'

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'profile_updated') {
        $message = 'Profil Anda berhasil diperbarui!';
        $message_type = 'success';
    } else if ($_GET['success'] == 'rented') {
        $message = 'Film berhasil disewa!';
        $message_type = 'success';
    } else if ($_GET['success'] == 'film_returned') {
        $message = 'Film berhasil dikembalikan dan stok diperbarui!';
        $message_type = 'success';
    }
} elseif (isset($_GET['error'])) {
    if ($_GET['error'] == 'update_failed') {
        $message = 'Gagal memperbarui profil. Silakan coba lagi.';
        $message_type = 'error';
    } else if ($_GET['error'] == 'rent') {
        $message = 'Gagal menyewa film. Silakan coba lagi.';
        $message_type = 'error';
    } else if ($_GET['error'] == 'return_failed') {
        $message = 'Gagal mengembalikan film. Silakan coba lagi.';
        $message_type = 'error';
    } else if ($_GET['error'] == 'invalid_request') {
        $message = 'Permintaan tidak valid.';
        $message_type = 'error';
    }
}
// --- Akhir penambahan ---

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="randomcihuy.css">
    <style>
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .return-button {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .return-button:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .original-price {
            text-decoration: line-through;
            color: #aaa;
            font-size: 0.9em;
            margin-left: 5px;
        }

        .discounted-price {
            font-weight: bold;
            color: #28a745;
        }
    </style>
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

        <?php if ($message): // Tampilkan pesan jika ada 
        ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="account-info">
            <h3>Account Information</h3>
            <p>Email: <?php echo htmlspecialchars($customer['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($customer['telpon']); ?></p>
            <p>Address: <?php echo htmlspecialchars($customer['alamat']); ?></p>
            <p>Member Since: <?php echo htmlspecialchars($customer['tanggaldaftar']); ?></p>
            <p>Status: <?php echo htmlspecialchars($customer['statusmember']); ?></p>

            <?php
            if (isset($_SESSION['customer']['id_cust'])) {
                $customer_id = $_SESSION['customer']['id_cust'];

                $sql_active_rentals = "SELECT GET_CUSTOMER_ACTIVE_RENTALS(?) AS active_rentals_count";
                $stmt = $koneksi->prepare($sql_active_rentals);
                $stmt->bind_param("i", $customer_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $active_rentals_count = $row['active_rentals_count'];
                $stmt->close();

                echo "<p>Jumlah Film yang Sedang Disewa: <strong>" . htmlspecialchars($active_rentals_count) . "</strong></p>";
            } else {
                echo "<p>Silakan login untuk melihat detail akun Anda.</p>";
            }
            ?>
        </div>

        <br>
        <form action="updateaccount.php">
            <input type="submit" value="Update Account">
        </form>
        <br>

        <div class="rental-history">
            <h3>Your Rentals</h3>
            <?php
            // Mengambil riwayat sewa, termasuk info promo dan harga asli film
            $sql = "SELECT r.*, k.judul, k.hargasewa AS harga_asli_film, p.nama_promo, p.diskon_persen
                    FROM rent r
                    JOIN kasetfilm k ON r.id_film = k.id_film
                    LEFT JOIN promo p ON r.id_promo = p.id_promo -- LEFT JOIN untuk promo
                    WHERE r.id_cust = " . $customer['id_cust'] . "
                    ORDER BY r.tanggalsewa DESC";
            $result = $koneksi->query($sql);

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr><th>Film Title</th><th>Rental Date</th><th>Status</th><th>Cost</th><th>Promo Used</th><th>Aksi</th></tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['judul']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['tanggalsewa']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                    echo '<td>';
                    // Tampilkan harga asli jika ada promo, dan harga setelah diskon
                    if (!empty($row['nama_promo'])) {
                        echo '<span class="original-price">Rp.' . htmlspecialchars(number_format($row['harga_asli_film'], 0, ',', '.')) . '</span>';
                        echo '<span class="discounted-price">Rp.' . htmlspecialchars(number_format($row['biaya'], 0, ',', '.')) . '</span>';
                    } else {
                        echo 'Rp.' . htmlspecialchars(number_format($row['biaya'], 0, ',', '.'));
                    }
                    echo '</td>';
                    echo '<td>';
                    if (!empty($row['nama_promo'])) {
                        echo htmlspecialchars($row['nama_promo']) . ' (' . htmlspecialchars($row['diskon_persen']) . '%)';
                    } else {
                        echo 'N/A';
                    }
                    echo '</td>';
                    echo '<td>';
                    // tampil tombol 'Hapus' hanya jika status sewa'Active'
                    if ($row['status'] == 'Active') {
                        echo '<form action="return_process.php" method="post" onsubmit="return confirm(\'Apakah Anda yakin ingin mengembalikan film ini?\');">';
                        echo '<input type="hidden" name="id_rent" value="' . htmlspecialchars($row['id_rent']) . '">';
                        echo '<input type="submit" value="Hapus" class="return-button">'; // Tombol Hapus
                        echo '</form>';
                    } else {
                        echo '—';
                    }
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo "<p>You haven't rented any films yet.</p>";
            }
            ?>
        </div>
    </main>
</body>

</html>