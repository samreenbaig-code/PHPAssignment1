<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $details = trim($_POST['details']);

    // selected image from dropdown
    $imageName = $_POST['image_name'] ?? 'placeholder.png';

    if ($title !== "") {
        $stmt = $conn->prepare(
            "INSERT INTO tasks (title, details, image_name) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $title, $details, $imageName);
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

  <label>Photo:</label>
  <select name="image_name">
    <option value="placeholder.png">Placeholder</option>
    <option value="3d-kid.png">3D Kid</option>
    <option value="donald.png">Donald Duck</option>
    <option value="duck.jpeg">Duck</option>
    <option value="duck2.jpeg">Duck 2</option>
    <option value="duck3.jpeg">Duck 3</option>
    <option value="duck4.jpeg">Duck 4</option>
    <option value="micky.png">Mickey</option>
  </select>

  <button type="submit">Save</button>
</form>

</body>
</html>
