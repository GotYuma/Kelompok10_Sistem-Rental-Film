<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $email = $koneksi->real_escape_string($_POST['email']);
    $telpon = $koneksi->real_escape_string($_POST['telpon']);
    $alamat = $koneksi->real_escape_string($_POST['alamat']);
    $tanggaldaftar = date('Y-m-d');
    $statusmember = "Active";

    $sql = "INSERT INTO customer (nama, email, telpon, alamat, tanggaldaftar, statusmember) 
            VALUES ('$nama', '$email', '$telpon', '$alamat', '$tanggaldaftar', '$statusmember')";

    if ($koneksi->query($sql)) {
        $id_cust = $koneksi->insert_id;
        $sql = "SELECT * FROM customer WHERE id_cust = $id_cust";
        $result = $koneksi->query($sql);
        $_SESSION['customer'] = $result->fetch_assoc();
        header("Location: index.php");
    } else {
        header("Location: customer.php?error=register");
    }
}
