<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$hostname = "localhost";
$username = "root";
$password = "";
$database = "random_play";

$koneksi = new mysqli($hostname, $username, $password, $database);
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

session_start();
