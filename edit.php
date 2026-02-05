<?php
include "db.php";

$id = $_GET['id'];

/* Get task data */
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

/* Load categories */
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

/* Update record */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $category_id = $_POST['category_id'];
    $image_name = $_POST['image_name'];

    $stmt = $conn->prepare("
        UPDATE tasks
        SET title=?, details=?, category_id=?, image_name=?
        WHERE id=?
    ");

    $stmt->bind_param("ssisi", $title, $details, $category_id, $image_name, $id);
    $stmt->execute();

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
<a class="btn" href="index.php">Back</a>

<form method="post">

    <input type="text" name="title"
           value="<?= htmlspecialchars($task['title']) ?>"
           required>

    <textarea name="details"><?= htmlspecialchars($task['details']) ?></textarea>

    <!-- CATEGORY DROPDOWN -->
    <label>Category:</label>
    <select name="category_id" required>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>"
                <?= $task['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <!-- IMAGE DROPDOWN -->
    <label>Photo:</label>
    <select name="image_name">
        <option value="placeholder.png">Placeholder</option>
        <option value="3d-kid.png" <?= $task['image_name']=='3d-kid.png'?'selected':'' ?>>3D Kid</option>
        <option value="donald.png" <?= $task['image_name']=='donald.png'?'selected':'' ?>>Donald</option>
        <option value="duck.jpeg" <?= $task['image_name']=='duck.jpeg'?'selected':'' ?>>Duck</option>
        <option value="duck2.jpeg" <?= $task['image_name']=='duck2.jpeg'?'selected':'' ?>>Duck 2</option>
        <option value="duck3.jpeg" <?= $task['image_name']=='duck3.jpeg'?'selected':'' ?>>Duck 3</option>
        <option value="duck4.jpeg" <?= $task['image_name']=='duck4.jpeg'?'selected':'' ?>>Duck 4</option>
        <option value="micky.png" <?= $task['image_name']=='micky.png'?'selected':'' ?>>Mickey</option>
    </select>

    <button type="submit">Update</button>

</form>

</body>
</html>
