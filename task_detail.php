<?php
include "db.php";

$id = $_GET['id'];

$sql = "SELECT tasks.*, categories.name AS category_name
        FROM tasks
        LEFT JOIN categories ON tasks.category_id = categories.id
        WHERE tasks.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Task Details</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="detail-wrapper">

    <div class="detail-card">

        <h2>Task Details</h2>

        <div class="detail-image">
            <img src="images/<?php echo $task['image_name']; ?>">
        </div>

        <div class="detail-info">
            <p><strong>Title:</strong> <?php echo $task['title']; ?></p>
            <p><strong>Details:</strong> <?php echo $task['details']; ?></p>
            <p><strong>Category:</strong> <?php echo $task['category_name']; ?></p>
            <p><strong>Created:</strong> <?php echo $task['created_at']; ?></p>
        </div>

        <a href="index.php" class="back-btn">Back to Task List</a>

    </div>

</div>

</body>
</html>
