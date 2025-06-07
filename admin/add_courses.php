<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];

    $sql = "INSERT INTO courses (title, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $desc);
    $stmt->execute();

    echo "Course added!";
}
?>

<!-- Add Course Form -->
<form method="POST">
    <input type="text" name="title" placeholder="Course Title" required /><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <button type="submit">Add Course</button>
</form>
