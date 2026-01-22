<?php
include "db.php";
$id = $_GET['id'];

$result = $conn->query("SELECT * FROM tasks WHERE id=$id");
$task = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST['title'];
  $details = $_POST['details'];

  $conn->query("UPDATE tasks SET title='$title', details='$details' WHERE id=$id");
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Task</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Edit Task</h1>

<form method="post">
  <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>">
  <textarea name="details"><?= htmlspecialchars($task['details']) ?></textarea>
  <button type="submit">Update</button>
</form>

</body>
</html>
