<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $koneksi->real_escape_string($_POST['email']);
    $password = $koneksi->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM customer WHERE email = '$email'";
    $result = $koneksi->query($sql);

    if ($result->num_rows == 1) {
        $customer = $result->fetch_assoc();

        if (password_verify($password, $customer['password'])) {
            $_SESSION['customer'] = $customer;
            header("Location: index.php");
            exit();
        } else {

            header("Location: customer.php?error=wrong_password");
            exit();
        }
    } else {

        header("Location: customer.php?error=email_not_found");
        exit();
    }
} else {
    header("Location: customer.php"); // Redirect if not a POST request
    exit();
}
?>