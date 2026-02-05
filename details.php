<?php
include "db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("
SELECT tasks.*, categories.name AS category_name
FROM tasks
LEFT JOIN categories ON tasks.category_id = categories.id
WHERE tasks.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="details-card">

<h2>Task Details</h2>

<img src="images/<?= htmlspecialchars($task['image_name']) ?>" class="details-img">

<div class="details-text">
<p><strong>Title:</strong> <?= htmlspecialchars($task['title']) ?></p>
<p><strong>Details:</strong> <?= htmlspecialchars($task['details']) ?></p>
<p><strong>Category:</strong> <?= htmlspecialchars($task['category_name']) ?></p>
<p><strong>Created:</strong> <?= $task['created_at'] ?></p>
</div>

<a href="index.php" class="btn">Back to Task List</a>

</div>

</body>
</html>
