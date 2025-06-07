<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' | EduHub' : 'EduHub - Online Learning Platform'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>">EduHub</a>
            </div>
            <ul class="nav-links">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="<?php echo BASE_URL; ?>/admin/dashboard">Admin Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/admin/manageUsers">Manage Users</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/admin/manageCourses">Manage Courses</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/admin/manageCategories">Categories</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/admin/viewEnrollments">Enrollments</a></li>
                    <?php elseif (isInstructor()): ?>
                        <li><a href="<?php echo BASE_URL; ?>/instructor/dashboard">Instructor Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/instructor/mycourses">My Courses</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/instructor/createCourse">Create New Course</a></li>
                    <?php elseif (isStudent()): ?>
                        <li><a href="<?php echo BASE_URL; ?>/student/dashboard">Student Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/student/courses">Browse Courses</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/student/mycourses">My Enrollments</a></li>
                    <?php endif; ?>
                    <li class="user-info">
                        <span>Welcome, <?php echo $_SESSION['user_name']; ?> (<?php echo $_SESSION['user_role']; ?>)</span>
                        <a href="<?php echo BASE_URL; ?>/auth/logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>/auth/login">Login</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/auth/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container">
            <?php // flash messages if implemented ?>
            <?php // flash('register_success'); ?>
            <?php // flash('login_error'); ?>
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php endif; ?>