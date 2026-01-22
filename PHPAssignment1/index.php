<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "db.php";
$result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Task Manager</h1>
<a class="btn" href="add.php">+ Add Task</a>

<table>
  <tr>
    <th>ID</th>
    <th>Title</th>
    <th>Details</th>
    <th>Date</th>
    <th>Actions</th>
  </tr>

  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= htmlspecialchars($row['details']) ?></td>
    <td><?= $row['created_at'] ?></td>
    <td>
      <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
      <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this task?')">Delete</a>
    </td>
  </tr>
  <?php endwhile; ?>

</table>

</body>
</html>
