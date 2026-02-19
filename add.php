<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";

/* Load categories */
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

/* Load tags */
$allTags = $conn->query("SELECT * FROM tags ORDER BY name");

/* Handle form submit */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $details = trim($_POST['details']);
    $category_id = $_POST['category_id'];
    $image_name = "placeholder.png";

    if (empty($title) || empty($category_id)) {
        $error = "Title and Category are required.";
    } else {

        /* Image upload */
        if (!empty($_FILES['image']['name'])) {

            $allowed = ['jpg','jpeg','png','gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Invalid image type.";
            } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $error = "Image must be less than 2MB.";
            } else {
                $image_name = time() . "_" . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image_name);
            }
        }

        if (empty($error)) {

            /* Insert task */
            $stmt = $conn->prepare("
                INSERT INTO tasks (title, details, category_id, image_name)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param("ssis", $title, $details, $category_id, $image_name);
            $stmt->execute();

            $task_id = $stmt->insert_id;

            /* Insert tags */
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_id) {

                    $stmtTag = $conn->prepare("
                        INSERT INTO task_tags (task_id, tag_id)
                        VALUES (?, ?)
                    ");

                    $stmtTag->bind_param("ii", $task_id, $tag_id);
                    $stmtTag->execute();
                }
            }

            header("Location: index.php");
            exit;
        }
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

<?php if ($error): ?>
    <p style="color:red; font-weight:bold;"><?= $error ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Details:</label>
    <textarea name="details"></textarea>

    <label>Category:</label>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Upload Image:</label>
    <input type="file" name="image">

    <label>Tags:</label><br>

    <?php while($tag = $allTags->fetch_assoc()): ?>
        <label>
            <input type="checkbox"
                   name="tags[]"
                   value="<?= $tag['id'] ?>">
            <?= htmlspecialchars($tag['name']) ?>
        </label><br>
    <?php endwhile; ?>

    <br>
    <button type="submit">Save Task</button>

</form>

</body>
</html>
