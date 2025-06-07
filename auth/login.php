<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user'] = $user;

                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../student/dashboard.php");
                }
                exit;
            } else {
                echo "Invalid Password";
            }
        } else {
            echo "No user found!";
        }
    }
}
?>

<!-- Login Form -->
<form method="POST">
    <input type="email" name="email" placeholder="Email" required /><br>
    <input type="password" name="password" placeholder="Password" required /><br>
    <button type="submit">Login</button>
</form>
