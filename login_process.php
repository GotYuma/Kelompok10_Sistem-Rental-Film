<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $koneksi->real_escape_string($_POST['email']);

    $sql = "SELECT * FROM customer WHERE email = '$email'";
    $result = $koneksi->query($sql);

    if ($result->num_rows == 1) {
        $customer = $result->fetch_assoc();
        $_SESSION['customer'] = $customer;
        header("Location: index.php");
    } else {
        header("Location: customer.php?error=notfound");
    }
}
