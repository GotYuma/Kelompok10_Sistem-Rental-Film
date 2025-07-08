<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $email = $koneksi->real_escape_string($_POST['email']);
    $password = $koneksi->real_escape_string($_POST['password']);
    $telpon = $koneksi->real_escape_string($_POST['telpon']);
    $alamat = $koneksi->real_escape_string($_POST['alamat']);


    // Hash password sebelum menyimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Set tanggaldaftar ke tanggal saat ini
    $tanggaldaftar = date('Y-m-d');
    $statusmember = 'Active'; // Atur statusmember default

    // Cek apakah email sudah terdaftar
    $check_email_sql = "SELECT id_cust FROM customer WHERE email = '$email'";
    $check_email_result = $koneksi->query($check_email_sql);

    if ($check_email_result->num_rows > 0) {
        // Email sudah terdaftar
        header("Location: customer.php?error=email_exists");
        exit();
    }

    $sql = "INSERT INTO customer (nama, email, password, telpon, alamat, tanggaldaftar, statusmember) VALUES ('$nama', '$email', '$hashed_password', '$telpon', '$alamat', '$tanggaldaftar', '$statusmember')";

    if ($koneksi->query($sql) === TRUE) {
        header("Location: customer.php?success=registered");
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
} else {
    header("Location: customer.php"); // Redirect if not a POST request
}
