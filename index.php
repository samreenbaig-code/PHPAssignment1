<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$search = $_GET['search'] ?? '';

if (!empty($search)) {

    $stmt = $conn->prepare("
        SELECT tasks.*, categories.name AS category_name
        FROM tasks
        LEFT JOIN categories
        ON tasks.category_id = categories.id
        WHERE tasks.title LIKE ?
        ORDER BY tasks.id DESC
    ");

    $searchParam = "%" . $search . "%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    $result = $conn->query("
        SELECT tasks.*, categories.name AS category_name
        FROM tasks
        LEFT JOIN categories
        ON tasks.category_id = categories.id
        ORDER BY tasks.id DESC
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div style="text-align:right; margin-bottom:20px;">
    <a class="btn" href="logout.php">Logout</a>
</div>

<h1>Task Manager</h1>

<a class="btn" href="add.php">+ Add Task</a>

<form method="GET" style="margin-bottom:15px;">
    <input type="text" name="search"
           placeholder="Search task title..."
           value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

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
    <a href="task_detail.php?id=<?= $row['id']; ?>">View</a> |
    <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
    <a href="delete.php?id=<?= $row['id']; ?>">Delete</a>
  </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
