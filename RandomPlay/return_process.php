<?php
include 'config.php'; // Termasuk koneksi database dan session_start()

// Redirect jika customer belum login
if (!isset($_SESSION['customer'])) {
    header("Location: customer.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_rent'])) {
    $id_rent = $koneksi->real_escape_string($_POST['id_rent']);
    $id_cust = $_SESSION['customer']['id_cust']; // Pastikan hanya customer yang menyewa yang bisa mengembalikan

    // 1. Ambil id_film dan status dari tabel 'rent' berdasarkan id_rent
    $sql_get_rent_info = "SELECT id_film, status FROM rent WHERE id_rent = '$id_rent' AND id_cust = '$id_cust'";
    $result_rent_info = $koneksi->query($sql_get_rent_info);

    if ($result_rent_info->num_rows > 0) {
        $rent_info = $result_rent_info->fetch_assoc();
        $id_film = $rent_info['id_film'];
        $current_status = $rent_info['status'];

        // Periksa apakah film sudah dikembalikan (misalnya statusnya bukan 'Active')
        if ($current_status != 'Active') {
            header("Location: myaccount.php?error=already_returned");
            exit();
        }

        // Mulai transaksi untuk memastikan kedua operasi berhasil atau tidak sama sekali
        $koneksi->begin_transaction();

        try {
            // 2. Perbarui status sewa di tabel 'rent' menjadi 'Returned' atau 'Inactive'
            $sql_update_rent = "UPDATE rent SET status = 'Returned' WHERE id_rent = '$id_rent' AND id_cust = '$id_cust'";
            if (!$koneksi->query($sql_update_rent)) {
                throw new Exception("Gagal memperbarui status sewa.");
            }

            // 3. Tambahkan stok kasetfilm
            $sql_update_stok = "UPDATE kasetfilm SET stok = stok + 1, tersedia = (tersedia + 1) WHERE id_film = '$id_film'";
            if (!$koneksi->query($sql_update_stok)) {
                throw new Exception("Gagal memperbarui stok film.");
            }

            // Commit transaksi jika semua operasi berhasil
            $koneksi->commit();
            header("Location: myaccount.php?success=film_returned");
            exit();
        } catch (Exception $e) {
            // Rollback transaksi jika ada kesalahan
            $koneksi->rollback();
            header("Location: myaccount.php?error=return_failed");
            exit();
        }
    } else {
        // Jika id_rent tidak ditemukan atau bukan milik customer yang login
        header("Location: myaccount.php?error=invalid_request");
        exit();
    }
} else {
    // Jika diakses langsung tanpa POST request atau id_rent
    header("Location: myaccount.php?error=invalid_request");
    exit();
}
