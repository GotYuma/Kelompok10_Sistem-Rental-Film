<?php
include 'config.php'; // Include database connection and session start

// Redirect if customer is not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer.php"); // Redirect to login/register page
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Check if the form was submitted via POST method
    // Get and escape data from the form
    $id_cust = $koneksi->real_escape_string($_POST['id_cust']); // Get customer ID from hidden input
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $email = $koneksi->real_escape_string($_POST['email']);
    $telpon = $koneksi->real_escape_string($_POST['telpon']);
    $alamat = $koneksi->real_escape_string($_POST['alamat']);

    // SQL to update customer data
    // The table name from your register_process.php and login_process.php is 'customer'
    $sql = "UPDATE customer SET 
                nama = '$nama', 
                email = '$email', 
                telpon = '$telpon', 
                alamat = '$alamat' 
            WHERE id_cust = '$id_cust'";

    if ($koneksi->query($sql)) {
        // If update is successful, update the session with new data
        // This is crucial so myaccount.php displays the updated information immediately
        $_SESSION['customer']['nama'] = $nama;
        $_SESSION['customer']['email'] = $email;
        $_SESSION['customer']['telpon'] = $telpon;
        $_SESSION['customer']['alamat'] = $alamat;

        header("Location: myaccount.php?success=profile_updated"); // Redirect to my account page with success message
        exit();
    } else {
        // Handle error if update fails
        header("Location: myaccount.php?error=update_failed"); // Redirect with error message
        exit();
    }
} else {
    // If accessed directly without POST request, redirect to my account page
    header("Location: myaccount.php");
    exit();
}
?>