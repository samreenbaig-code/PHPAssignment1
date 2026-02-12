<?php
require 'db.php';

$id = $_GET['id'] ?? 0;

// Get image name first
$stmt = $pdo->prepare("SELECT image_name FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if ($task) {

    $image = $task['image_name'];

    // Delete image if not placeholder and not empty
    if ($image && $image !== 'placeholder.png') {
        $filePath = "images/" . $image;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete record
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
?>
