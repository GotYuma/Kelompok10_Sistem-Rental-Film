<?php
include 'config.php';

if (!isset($_SESSION['customer'])) {
    header("Location: customer.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_film'])) {
    $id_cust = $_SESSION['customer']['id_cust'];
    $id_film = $koneksi->real_escape_string($_POST['id_film']);
    $tanggalsewa = date('Y-m-d');
    $status = "Active";

    // Get film price
    $sql = "SELECT hargasewa FROM kasetfilm WHERE id_film = $id_film";
    $result = $koneksi->query($sql);
    $film = $result->fetch_assoc();
    $biaya = $film['hargasewa'];

    // Create rental record
    $sql = "INSERT INTO rent (id_cust, id_film, tanggalsewa, status, biaya) 
            VALUES ($id_cust, $id_film, '$tanggalsewa', '$status', $biaya)";

    if ($koneksi->query($sql)) {
        // Update film stock
        $sql = "UPDATE kasetfilm SET stok = stok - 1 WHERE id_film = $id_film";
        $koneksi->query($sql);

        // Check if stock reached 0
        $sql = "SELECT stok FROM kasetfilm WHERE id_film = $id_film";
        $result = $koneksi->query($sql);
        $film = $result->fetch_assoc();

        if ($film['stok'] <= 0) {
            $sql = "UPDATE kasetfilm SET tersedia = 0 WHERE id_film = $id_film";
            $koneksi->query($sql);
        }

        header("Location: myaccount.php?success=rented");
    } else {
        header("Location: index.php?error=rent");
    }
} else {
    header("Location: index.php");
}
