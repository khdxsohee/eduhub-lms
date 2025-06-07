<?php
session_start();
include '../includes/db.php';

$user_id = $_SESSION['user']['id'];
$sql = "SELECT c.title FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE e.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['title'] . "</p>";
}
?>
