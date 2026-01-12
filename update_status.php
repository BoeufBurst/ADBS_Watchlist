<?php
require "db.php";

$id = $_GET["id"] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM watchlist WHERE id=" . intval($id)));
if (!$item) { header("Location: index.php"); exit; }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title  = mysqli_real_escape_string($conn, $_POST["title"]);
    $type   = mysqli_real_escape_string($conn, $_POST["type"]);
    $status = mysqli_real_escape_string($conn, $_POST["status"]);

    $posterSQL = $item['poster'] ? "'".$item['poster']."'" : "NULL";
    if (!empty($_FILES['poster']['name'])) {
        $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['poster']['tmp_name'], "uploads/$filename");
        $posterSQL = "'$filename'";
    }

    mysqli_query(
        $conn,
        "UPDATE watchlist
         SET title='$title', type='$type', status='$status', poster=$posterSQL
         WHERE id=" . intval($id)
    );

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← Back to Library</a>
</div>

<div class="page-center">
    <div class="form-card">
        <h1>Edit Item</h1>

        <form method="POST" enctype="multipart/form-data">
            <label>Title</label>
            <input name="title" value="<?= htmlspecialchars($item["title"]) ?>" required>

            <label>Type</label>
            <select name="type">
                <option value="movie" <?= $item["type"] === "movie" ? "selected" : "" ?>>Movie</option>
                <option value="series" <?= $item["type"] === "series" ? "selected" : "" ?>>Series</option>
            </select>

            <label>Status</label>
            <select name="status">
                <option value="planned" <?= $item["status"] === "planned" ? "selected" : "" ?>>Planned</option>
                <option value="watching" <?= $item["status"] === "watching" ? "selected" : "" ?>>Watching</option>
                <option value="completed" <?= $item["status"] === "completed" ? "selected" : "" ?>>Completed</option>
            </select>

            <label>Poster (optional)</label>
            <input type="file" name="poster" accept="image/*">

            <button type="submit">Save</button>
        </form>
    </div>
</div>

</body>
</html>
