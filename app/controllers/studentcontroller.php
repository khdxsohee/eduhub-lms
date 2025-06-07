<?php
// app/controllers/StudentController.php

class StudentController extends Controller {
    public $userModel;
    public $courseModel;

    public function __construct() {
        if (!isLoggedIn() || !isStudent()) {
            redirect('auth/login'); // Redirect if not logged in as student
        }
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
    }

    public function dashboard() {
        $enrolledCourses = $this->courseModel->getEnrolledCourses($_SESSION['user_id']);
        $data = [
            'title' => 'Student Dashboard',
            'user' => $this->userModel->getUserById($_SESSION['user_id']),
            'enrolled_courses' => $enrolledCourses
        ];
        $this->view('dashboard/student', $data);
    }

    public function courses() {
        $courses = $this->courseModel->getCourses(null, 'published'); // All published courses
        $data = [
            'title' => 'Available Courses',
            'courses' => $courses
        ];
        $this->view('courses/index', $data);
    }

    public function enroll($courseId) {
        if ($this->courseModel->isEnrolled($_SESSION['user_id'], $courseId)) {
            // Already enrolled
            redirect('student/mycourses'); // Redirect to student's enrolled courses
        }

        if ($this->courseModel->enrollStudent($_SESSION['user_id'], $courseId)) {
            // Flash message: Successfully enrolled
            redirect('student/mycourses');
        } else {
            die('Enrollment failed');
        }
    }

    public function mycourses() {
        $enrolledCourses = $this->courseModel->getEnrolledCourses($_SESSION['user_id']);
        $data = [
            'title' => 'My Courses',
            'enrolled_courses' => $enrolledCourses
        ];
        $this->view('courses/mycourses', $data);
    }

    public function viewCourse($courseId) {
        if (!$this->courseModel->isEnrolled($_SESSION['user_id'], $courseId)) {
            redirect('student/mycourses'); // Only enrolled students can view
        }

        $course = $this->courseModel->getCourseById($courseId);
        $lessons = $this->courseModel->getLessonsByCourseId($courseId);

        if (!$course) {
            redirect('student/mycourses'); // Course not found
        }

        $data = [
            'title' => $course->title,
            'course' => $course,
            'lessons' => $lessons
        ];
        $this->view('courses/show', $data);
    }

    public function viewLesson($lessonId) {
        $lesson = $this->courseModel->getLessonById($lessonId);
        if (!$lesson) {
            redirect('student/mycourses'); // Lesson not found
        }
        
        // Ensure student is enrolled in the course this lesson belongs to
        if (!$this->courseModel->isEnrolled($_SESSION['user_id'], $lesson->course_id)) {
            redirect('student/mycourses');
        }

        $course = $this->courseModel->getCourseById($lesson->course_id);

        $data = [
            'title' => $lesson->title,
            'lesson' => $lesson,
            'course' => $course
        ];
        $this->view('courses/lesson', $data);
    }

    public function completeCourse($courseId) {
        if ($this->courseModel->completeCourse($_SESSION['user_id'], $courseId)) {
            // Flash message: Course completed!
            redirect('student/mycourses');
        } else {
            die('Failed to mark course as complete.');
        }
    }
}
?>