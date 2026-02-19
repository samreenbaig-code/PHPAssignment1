<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id > 0) {

    // Get image name first
    $stmt = $conn->prepare("SELECT image_name FROM tasks WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if ($task) {

        $image = $task['image_name'];

        // Delete image if not placeholder
        if ($image && $image !== "placeholder.png") {
            $filePath = "images/" . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete tags first (VERY IMPORTANT)
        $stmtDeleteTags = $conn->prepare("DELETE FROM task_tags WHERE task_id=?");
        $stmtDeleteTags->bind_param("i", $id);
        $stmtDeleteTags->execute();

        // Delete task
        $stmtDelete = $conn->prepare("DELETE FROM tasks WHERE id=?");
        $stmtDelete->bind_param("i", $id);
        $stmtDelete->execute();
    }
}

header("Location: index.php");
exit;
?>
