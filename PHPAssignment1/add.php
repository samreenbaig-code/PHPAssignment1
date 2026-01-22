<?php
include "db.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = trim($_POST["title"]);
  $details = trim($_POST["details"]);

  if ($title == "") {
    $error = "Title is required";
  } else {
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
<a class="btn" href="index.php">← Back</a>

<?php if ($error): ?>
<p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="post">
  <input type="text" name="title" placeholder="Task title">
  <textarea name="details" placeholder="Task details"></textarea>
  <button type="submit">Save</button>
</form>

</body>
</html>
