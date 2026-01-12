<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title  = mysqli_real_escape_string($conn, $_POST["title"]);
    $type   = mysqli_real_escape_string($conn, $_POST["type"]);
    $status = mysqli_real_escape_string($conn, $_POST["status"]);

    $posterPath = NULL;
    if (!empty($_FILES['poster']['name'])) {
        $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['poster']['tmp_name'], "uploads/$filename");
        $posterPath = $filename;
    }

    $posterSQL = $posterPath ? "'$posterPath'" : "NULL";

    mysqli_query(
        $conn,
        "INSERT INTO watchlist (title, type, status, poster)
         VALUES ('$title', '$type', '$status', $posterSQL)"
    );

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← Back to Library</a>
</div>

<div class="page-center">
    <div class="form-card">
        <h1>Add to Watchlist</h1>

        <form method="POST" enctype="multipart/form-data">
            <label>Title</label>
            <input name="title" required>

            <label>Type</label>
            <select name="type">
                <option value="movie">Movie</option>
                <option value="series">Series</option>
            </select>

            <label>Status</label>
            <select name="status">
                <option value="planned">Planned</option>
                <option value="watching">Watching</option>
                <option value="completed">Completed</option>
            </select>

            <label>Poster (optional)</label>
            <input type="file" name="poster" accept="image/*">

            <button type="submit">Add</button>
        </form>
    </div>
</div>

</body>
</html>
