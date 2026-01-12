<?php
$conn = mysqli_connect("localhost", "root", "", "watchlist_db");

if (!$conn) {
    die("Database connection failed");
}
