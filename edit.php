<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* Protect page */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

/* Get existing task */
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    die("Task not found.");
}
if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
    $error = "Image must be less than 2MB.";
}

/* Load categories */
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

$error = "";

/* Update record */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $category_id = $_POST['category_id'];

    /* Validation */
    if (empty($title) || empty($category_id)) {
        $error = "Title and Category are required.";
    } else {

        $image_name = $task['image_name']; // keep old image by default

        /* Check if new image uploaded */
        if (!empty($_FILES['image']['name'])) {

            $allowed = ['jpg','jpeg','png','gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Invalid image type.";
            } else {

                /* Delete old image if not placeholder */
                if ($task['image_name'] !== "placeholder.png" && !empty($task['image_name'])) {
                    $oldPath = "images/" . $task['image_name'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                /* Upload new image */
                $image_name = time() . "_" . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image_name);
            }
        }

        if (empty($error)) {

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
    }
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

<?php if ($error): ?>
    <p style="color:red; font-weight:bold;"><?= $error ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

    <label>Title:</label>
    <input type="text" name="title"
           value="<?= htmlspecialchars($task['title']) ?>"
           required>

    <label>Details:</label>
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

    <!-- CURRENT IMAGE -->
    <label>Current Image:</label><br>
    <img src="images/<?= htmlspecialchars($task['image_name']) ?>"
         width="120"
         style="border-radius:8px;"><br><br>

    <!-- IMAGE UPLOAD -->
    <label>Change Image:</label>
    <input type="file" name="image">

    <button type="submit">Update Task</button>

</form>

</body>
</html>
