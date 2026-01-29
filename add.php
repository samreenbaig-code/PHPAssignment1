<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $details = trim($_POST['details']);

    if ($title !== "") {
        $stmt = $conn->prepare("INSERT INTO tasks (title, details) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $details);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Task</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Add Task</h1>
<a class="btn" href="index.php">Back</a>

<form method="post">
  <input type="text" name="title" placeholder="Task title" required>
  <textarea name="details" placeholder="Task details"></textarea>
  <button type="submit">Save</button>
</form>

</body>
</html>
