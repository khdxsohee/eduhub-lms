<?php
// app/controllers/AdminController.php

class AdminController extends Controller {
    public $userModel;
    public $courseModel;

    public function __construct() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('auth/login'); // Redirect if not logged in as admin
        }
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
    }

    public function dashboard() {
        $pendingInstructors = $this->userModel->getPendingInstructors();
        $totalUsers = $this->userModel->getAllUsers();
        $totalCourses = $this->courseModel->getCourses();

        $data = [
            'title' => 'Admin Dashboard',
            'user' => $this->userModel->getUserById($_SESSION['user_id']),
            'pending_instructors' => $pendingInstructors,
            'total_users' => count($totalUsers),
            'total_courses' => count($totalCourses)
        ];
        $this->view('dashboard/admin', $data);
    }

    public function manageUsers() {
        $users = $this->userModel->getAllUsers();
        $data = [
            'title' => 'Manage Users',
            'users' => $users
        ];
        $this->view('admin/manage_users', $data);
    }

    public function approveInstructor($userId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->userModel->getUserById($userId);
            if ($user && $user->role == 'instructor' && $user->status == 'pending') {
                if ($this->userModel->updateStatus($userId, 'active')) {
                    // Flash message: Instructor approved
                }
            }
            redirect('admin/manageUsers');
        } else {
            redirect('admin/manageUsers');
        }
    }

    public function blockUser($userId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->userModel->getUserById($userId);
            if ($user && $user->id != $_SESSION['user_id']) { // Admin cannot block themselves
                if ($this->userModel->updateStatus($userId, 'blocked')) {
                    // Flash message: User blocked
                }
            }
            redirect('admin/manageUsers');
        } else {
            redirect('admin/manageUsers');
        }
    }

    public function activateUser($userId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->userModel->getUserById($userId);
            if ($user) {
                if ($this->userModel->updateStatus($userId, 'active')) {
                    // Flash message: User activated
                }
            }
            redirect('admin/manageUsers');
        } else {
            redirect('admin/manageUsers');
        }
    }

    public function manageCourses() {
        $courses = $this->courseModel->getCourses();
        $data = [
            'title' => 'Manage All Courses',
            'courses' => $courses
        ];
        $this->view('admin/manage_courses', $data);
    }

    public function deleteCourse($courseId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Admin can delete any course, no need to check instructor ID
            if ($this->courseModel->deleteCourse($courseId, null)) { // Pass null or a dummy ID for admin context
                // Flash message: Course deleted
            } else {
                die('Something went wrong deleting course');
            }
            redirect('admin/manageCourses');
        } else {
            redirect('admin/manageCourses');
        }
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if (empty($name)) {
                // Handle error
                $data = ['name_err' => 'Please enter category name'];
                $this->view('admin/add_category', $data);
                return;
            }

            if ($this->courseModel->addCategory($name, $description)) {
                // Flash message: Category added
                redirect('admin/manageCategories');
            } else {
                die('Failed to add category');
            }
        } else {
            $data = [
                'name' => '',
                'description' => '',
                'name_err' => ''
            ];
            $this->view('admin/add_category', $data);
        }
    }

    public function manageCategories() {
        $categories = $this->courseModel->getCategories();
        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories
        ];
        $this->view('admin/manage_categories', $data);
    }

    public function viewEnrollments($courseId = null) {
        $enrollments = $this->courseModel->getCourseEnrollments($courseId);
        $data = [
            'title' => 'All Enrollments',
            'enrollments' => $enrollments,
            'course_id' => $courseId // Can be null or specific ID
        ];
        $this->view('admin/view_enrollments', $data);
    }
}
?>