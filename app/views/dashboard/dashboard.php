<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h1><?php echo $data['title']; ?></h1>
<p>Hello, <?php echo htmlspecialchars($data['user']->name); ?>!</p>

<h2>Your Enrolled Courses</h2>
<?php if (!empty($data['enrolled_courses'])): ?>
    <div class="course-list">
        <?php foreach ($data['enrolled_courses'] as $enrollment): ?>
            <div class="course-card">
                <h3><?php echo htmlspecialchars($enrollment->course_title); ?></h3>
                <p>Instructor: <?php echo htmlspecialchars($enrollment->instructor_name); ?></p>
                <p>Status: <?php echo ucfirst($enrollment->enrollment_status); ?></p>
                <?php if ($enrollment->enrollment_status == 'in_progress'): ?>
                    <a href="<?php echo BASE_URL; ?>/student/viewCourse/<?php echo $enrollment->course_id; ?>" class="btn btn-info">Continue Learning</a>
                    <form action="<?php echo BASE_URL; ?>/student/completeCourse/<?php echo $enrollment->course_id; ?>" method="post" class="delete-form" style="display:inline;">
                        <button type="submit" class="btn btn-success">Mark as Completed</button>
                    </form>
                <?php else: ?>
                    <p>Completed on: <?php echo htmlspecialchars(date('M d, Y', strtotime($enrollment->completion_date))); ?></p>
                    <a href="<?php echo BASE_URL; ?>/student/viewCourse/<?php echo $enrollment->course_id; ?>" class="btn btn-info">View Course</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>You haven't enrolled in any courses yet. <a href="<?php echo BASE_URL; ?>/student/courses">Browse available courses</a> to get started!</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>