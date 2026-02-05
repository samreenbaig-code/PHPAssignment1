<?php
include "db.php";

$result = $conn->query("
    SELECT tasks.*, categories.name AS category_name
    FROM tasks
    LEFT JOIN categories
    ON tasks.category_id = categories.id
    ORDER BY tasks.id DESC
");

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
    <th>Category</th>
    <th>Photo</th>
    <th>Actions</th>
  </tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['title']) ?></td>
  <td><?= htmlspecialchars($row['details']) ?></td>
  <td><?= htmlspecialchars($row['category_name']) ?></td>

  <td>
    <img src="images/<?= htmlspecialchars($row['image_name']) ?>" width="80">
  </td>

  <td>
    <a href="task_detail.php?id=<?php echo $row['id']; ?>">View</a>

<a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
<a href="delete.php?id=<?= $row['id'] ?>">Delete</a>

  </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
