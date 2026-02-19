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
    <th>Tags</th>

  </tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['title']) ?></td>
  <td><?= htmlspecialchars($row['details']) ?></td>
  <td><?= htmlspecialchars($row['category_name']) ?></td>

<td>
<?php
echo "<pre>";
print_r($row['image_name']);
echo "</pre>";

$imagePath = "images/" . $row['image_name'];
$fullPath = __DIR__ . "/" . $imagePath;

if (empty($row['image_name']) || !file_exists($fullPath)) {
    $imagePath = "images/placeholder.png";
}
?>
<img src="<?= htmlspecialchars($imagePath) ?>" width="80">
</td>





  <td>
    <a href="task_detail.php?id=<?= $row['id']; ?>">View</a> |
    <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
    <a href="delete.php?id=<?= $row['id']; ?>">Delete</a>
  </td>

  <!-- ✅ TAGS COLUMN INSIDE ROW -->
  <td>
  <?php
  $tagQuery = $conn->prepare("
      SELECT tags.name
      FROM tags
      JOIN task_tags ON tags.id = task_tags.tag_id
      WHERE task_tags.task_id = ?
  ");

  $tagQuery->bind_param("i", $row['id']);
  $tagQuery->execute();
  $tagResult = $tagQuery->get_result();

  while($tag = $tagResult->fetch_assoc()) {
      echo "<span style='background:#3498db;color:white;
            padding:4px 8px;border-radius:6px;margin-right:5px;
            font-size:12px;'>"
            . htmlspecialchars($tag['name']) .
            "</span>";
  }
  ?>
  </td>

</tr>
<?php endwhile; ?>

</table>

</body>
</html>
