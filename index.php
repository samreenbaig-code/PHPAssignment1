<?php
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
    <th>Photo</th>
    <th>Actions</th>
  </tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['title']) ?></td>
  <td><?= htmlspecialchars($row['details']) ?></td>

  <!-- ✅ PHOTO COLUMN -->
  <td>
<?php
$image = $row['image_name'];

if (!$image) {
    $image = "placeholder.png";
}
?>
<img src="images/<?= htmlspecialchars($image) ?>"
     alt="Task Image"
     width="80">
</td>


  <!-- ✅ ACTIONS COLUMN -->
  <td>
    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
    <a href="delete.php?id=<?= $row['id'] ?>"
       onclick="return confirm('Delete this task?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
