<?php
require "db.php";

$id = $_GET["id"];
mysqli_query($conn, "DELETE FROM watchlist WHERE id=$id");

header("Location: index.php");
