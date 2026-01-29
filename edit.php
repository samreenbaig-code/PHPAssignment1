<?php
include "db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $details = $_POST['details'];

    $update = $conn->prepare("UPDATE tasks SET title=?, details=? WHERE id=?");
    $update->bind_param("ssi", $title, $details, $id);
    $update->execute();

    header("Location: index.php");
    exit;
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
  <input type="text" name="title"
         value="<?= htmlspecialchars($task['title']) ?>" required>
  <textarea name="details"><?= htmlspecialchars($task['details']) ?></textarea>
  <button type="submit">Update</button>
</form>

</body>
</html>
