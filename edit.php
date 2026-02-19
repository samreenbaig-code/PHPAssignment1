<?php
session_start();
include "db.php";

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

/* Load categories */
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

$error = "";

/* ================= UPDATE SECTION ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $category_id = $_POST['category_id'];

    if (empty($title) || empty($category_id)) {
        $error = "Title and Category are required.";
    } else {

        $image_name = $task['image_name'];

        /* Image upload */
        if (!empty($_FILES['image']['name'])) {

            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $error = "Image must be less than 2MB.";
            } else {

                $allowed = ['jpg','jpeg','png','gif'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    $error = "Invalid image type.";
                } else {

                    /* Delete old image */
                    if ($task['image_name'] !== "placeholder.png" && !empty($task['image_name'])) {
                        $oldPath = "images/" . $task['image_name'];
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $image_name = time() . "_" . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image_name);
                }
            }
        }

        if (empty($error)) {

            /* Update task */
            $stmt = $conn->prepare("
                UPDATE tasks
                SET title=?, details=?, category_id=?, image_name=?
                WHERE id=?
            ");
            $stmt->bind_param("ssisi", $title, $details, $category_id, $image_name, $id);
            $stmt->execute();

            /* ===== UPDATE TAGS ===== */

            // Delete old relations
            $deleteTags = $conn->prepare("DELETE FROM task_tags WHERE task_id=?");
            $deleteTags->bind_param("i", $id);
            $deleteTags->execute();

            // Insert new selected tags
            if (!empty($_POST['tags'])) {

                foreach ($_POST['tags'] as $tag_id) {

                    $stmtTag = $conn->prepare("
                        INSERT INTO task_tags (task_id, tag_id)
                        VALUES (?, ?)
                    ");

                    $stmtTag->bind_param("ii", $id, $tag_id);
                    $stmtTag->execute();
                }
            }

            header("Location: index.php");
            exit;
        }
    }
}

/* ===== Load Tag Data ===== */
$allTags = $conn->query("SELECT * FROM tags ORDER BY name");

$currentTags = [];
$tagCheck = $conn->prepare("SELECT tag_id FROM task_tags WHERE task_id=?");
$tagCheck->bind_param("i", $id);
$tagCheck->execute();
$tagResult = $tagCheck->get_result();

while($t = $tagResult->fetch_assoc()) {
    $currentTags[] = $t['tag_id'];
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

    <label>Category:</label>
    <select name="category_id" required>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>"
                <?= $task['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Current Image:</label><br>
    <img src="images/<?= htmlspecialchars($task['image_name']) ?>"
         width="120"
         style="border-radius:8px;"><br><br>

    <label>Change Image:</label>
    <input type="file" name="image">

    <br><br>

    <label>Tags:</label><br>

    <?php while($tag = $allTags->fetch_assoc()): ?>
        <label>
            <input type="checkbox"
                   name="tags[]"
                   value="<?= $tag['id'] ?>"
                   <?= in_array($tag['id'], $currentTags) ? 'checked' : '' ?>>
            <?= htmlspecialchars($tag['name']) ?>
        </label><br>
    <?php endwhile; ?>

    <br>

    <button type="submit">Update Task</button>

</form>

</body>
</html>
