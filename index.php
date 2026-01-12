<?php
require "db.php";

$search = $_GET["search"] ?? "";
$sort   = $_GET["sort"] ?? "latest";

$where = "";
if ($search) {
    $safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE title LIKE '%$safe%'";
}

$order = "ORDER BY id DESC";
if ($sort === "title") {
    $order = "ORDER BY title ASC";
} elseif ($sort === "status") {
    $order = "ORDER BY status ASC";
}

$query = "SELECT * FROM watchlist $where $order";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Watchlist</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="top-bar">
    <div class="container header-inner">
        <h1 class="brand">Your Library</h1>

        <form class="search-bar" method="GET">
            <input type="text" name="search" placeholder="Search…" value="<?= htmlspecialchars($search) ?>">
            <select name="sort">
                <option value="latest" <?= $sort === "latest" ? "selected" : "" ?>>Latest</option>
                <option value="title" <?= $sort === "title" ? "selected" : "" ?>>Title</option>
                <option value="status" <?= $sort === "status" ? "selected" : "" ?>>Status</option>
            </select>
            <button type="submit" class="btn-go">Go</button>
        </form>

        <a href="add.php" class="add-btn">＋ Add</a>
    </div>
</header>

<main class="container main-grid">

    <?php if (mysqli_num_rows($result) === 0): ?>
        <div class="empty-state">
            <p>Your watchlist is empty.</p>
            <a class="link-primary" href="add.php">Add your first movie or series</a>
        </div>
    <?php endif; ?>

    <div class="grid">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
            <div class="card-actions">
                <a href="edit.php?id=<?= $row["id"] ?>" class="action edit" title="Edit">✏️</a>
                <a href="delete.php?id=<?= $row["id"] ?>" class="action del" title="Delete">🗑</a>
            </div>

            <?php if($row["poster"]): ?>
                <img src="uploads/<?= htmlspecialchars($row["poster"]) ?>" class="card-poster">
            <?php else: ?>
                <div class="card-poster placeholder"></div>
            <?php endif; ?>

            <h2 class="card-title"><?= htmlspecialchars($row["title"]) ?></h2>

            <a href="update_status.php?id=<?= $row['id'] ?>" class="badge <?= htmlspecialchars($row['status']) ?>">
                <?= ucfirst($row["status"]) ?>
            </a>

            <p class="type"><?= ucfirst(htmlspecialchars($row["type"])) ?></p>
        </div>
    <?php endwhile; ?>
    </div>

</main>

</body>
</html>
