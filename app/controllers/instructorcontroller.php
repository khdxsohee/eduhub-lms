<?php
// app/controllers/InstructorController.php

class InstructorController extends Controller {
    public $userModel;
    public $courseModel;

    public function __construct() {
        if (!isLoggedIn() || !isInstructor() || $_SESSION['user_status'] !== 'active') {
            redirect('auth/login?message=Your instructor account is pending approval or inactive.'); // Redirect if not active instructor
        }
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
    }

    public function dashboard() {
        $myCourses = $this->courseModel->getCourses($_SESSION['user_id']);
        $data = [
            'title' => 'Instructor Dashboard',
            'user' => $this->userModel->getUserById($_SESSION['user_id']),
            'my_courses' => $myCourses
        ];
        $this->view('dashboard/instructor', $data);
    }

    public function mycourses() {
        $myCourses = $this->courseModel->getCourses($_SESSION['user_id']);
        $data = [
            'title' => 'My Created Courses',
            'courses' => $myCourses
        ];
        $this->view('instructor/mycourses', $data);
    }

    public function createCourse() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'instructor_id' => $_SESSION['user_id'],
                'category_id' => trim($_POST['category_id']),
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'status' => 'draft', // New courses start as draft
                'image' => '', // Handle image upload separately
                'title_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => ''
            ];

            // Handle image upload
            if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
                $targetDir = UPLOAD_PATH . 'courses/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $imageFileType = strtolower(pathinfo($_FILES['course_image']['name'], PATHINFO_EXTENSION));
                $fileName = uniqid() . '.' . $imageFileType;
                $targetFile = $targetDir . $fileName;
                $uploadOk = 1;

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES['course_image']['tmp_name']);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $data['image_err'] = "File is not an image.";
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES['course_image']['size'] > 5000000) { // 5MB limit
                    $data['image_err'] = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                    $data['image_err'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 1 && move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
                    $data['image'] = $fileName;
                } else {
                    $data['image_err'] = $data['image_err'] ?? "Sorry, there was an error uploading your file.";
                }
            }


            // Validate inputs
            if (empty($data['title'])) { $data['title_err'] = 'Please enter title'; }
            if (empty($data['description'])) { $data['description_err'] = 'Please enter description'; }
            if (empty($data['price'])) { $data['price_err'] = 'Please enter price'; }
            if (empty($data['category_id'])) { $data['category_err'] = 'Please select a category'; }

            if (empty($data['title_err']) && empty($data['description_err']) && empty($data['price_err']) && empty($data['category_err']) && empty($data['image_err'])) {
                if ($this->courseModel->addCourse($data)) {
                    redirect('instructor/mycourses');
                } else {
                    die('Something went wrong adding course');
                }
            } else {
                $data['categories'] = $this->courseModel->getCategories();
                $this->view('instructor/create_course', $data);
            }

        } else {
            $data = [
                'title' => '',
                'description' => '',
                'price' => '',
                'category_id' => '',
                'image' => '',
                'title_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_err' => '',
                'categories' => $this->courseModel->getCategories()
            ];
            $this->view('instructor/create_course', $data);
        }
    }

    public function editCourse($id) {
        $course = $this->courseModel->getCourseById($id);

        if (!$course || $course->instructor_id != $_SESSION['user_id']) {
            redirect('instructor/mycourses'); // Not authorized or course not found
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'id' => $id,
                'instructor_id' => $_SESSION['user_id'],
                'category_id' => trim($_POST['category_id']),
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'status' => trim($_POST['status']),
                'image' => $course->image, // Keep existing image by default
                'title_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => '',
                'status_err' => '',
                'image_err' => ''
            ];

            // Handle image upload (similar logic as createCourse)
            if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
                $targetDir = UPLOAD_PATH . 'courses/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $imageFileType = strtolower(pathinfo($_FILES['course_image']['name'], PATHINFO_EXTENSION));
                $fileName = uniqid() . '.' . $imageFileType;
                $targetFile = $targetDir . $fileName;
                $uploadOk = 1;

                $check = getimagesize($_FILES['course_image']['tmp_name']);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $data['image_err'] = "File is not an image.";
                    $uploadOk = 0;
                }
                if ($_FILES['course_image']['size'] > 5000000) {
                    $data['image_err'] = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                    $data['image_err'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 1 && move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
                    // Delete old image if it exists
                    if (!empty($course->image) && file_exists($targetDir . $course->image)) {
                        unlink($targetDir . $course->image);
                    }
                    $data['image'] = $fileName;
                } else {
                    $data['image_err'] = $data['image_err'] ?? "Sorry, there was an error uploading your file.";
                }
            }


            // Validate inputs
            if (empty($data['title'])) { $data['title_err'] = 'Please enter title'; }
            if (empty($data['description'])) { $data['description_err'] = 'Please enter description'; }
            if (empty($data['price'])) { $data['price_err'] = 'Please enter price'; }
            if (empty($data['category_id'])) { $data['category_err'] = 'Please select a category'; }
            if (!in_array($data['status'], ['draft', 'published', 'archived'])) { $data['status_err'] = 'Invalid status'; }

            if (empty($data['title_err']) && empty($data['description_err']) && empty($data['price_err']) && empty($data['category_err']) && empty($data['status_err']) && empty($data['image_err'])) {
                if ($this->courseModel->updateCourse($data)) {
                    redirect('instructor/mycourses');
                } else {
                    die('Something went wrong updating course');
                }
            } else {
                $data['categories'] = $this->courseModel->getCategories();
                $this->view('instructor/edit_course', $data);
            }

        } else {
            $data = [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'category_id' => $course->category_id,
                'status' => $course->status,
                'image' => $course->image,
                'title_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => '',
                'status_err' => '',
                'image_err' => '',
                'categories' => $this->courseModel->getCategories()
            ];
            $this->view('instructor/edit_course', $data);
        }
    }

    public function deleteCourse($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->courseModel->deleteCourse($id, $_SESSION['user_id'])) {
                redirect('instructor/mycourses');
            } else {
                die('Something went wrong deleting course');
            }
        } else {
            redirect('instructor/mycourses'); // Prevent direct access
        }
    }

    public function manageLessons($courseId) {
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course || $course->instructor_id != $_SESSION['user_id']) {
            redirect('instructor/mycourses');
        }
        $lessons = $this->courseModel->getLessonsByCourseId($courseId);
        $data = [
            'title' => 'Manage Lessons for: ' . $course->title,
            'course' => $course,
            'lessons' => $lessons
        ];
        $this->view('instructor/manage_lessons', $data);
    }

    public function addLesson($courseId) {
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course || $course->instructor_id != $_SESSION['user_id']) {
            redirect('instructor/mycourses');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'course_id' => $courseId,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'video_url' => trim($_POST['video_url']),
                'content' => trim($_POST['content']),
                'order_index' => trim($_POST['order_index']),
                'title_err' => '',
                'order_index_err' => ''
            ];

            if (empty($data['title'])) { $data['title_err'] = 'Please enter lesson title'; }
            if (empty($data['order_index'])) { $data['order_index_err'] = 'Please enter order index'; }

            if (empty($data['title_err']) && empty($data['order_index_err'])) {
                if ($this->courseModel->addLesson($data)) {
                    redirect('instructor/manageLessons/' . $courseId);
                } else {
                    die('Something went wrong adding lesson');
                }
            } else {
                $data['course'] = $course;
                $this->view('instructor/add_lesson', $data);
            }

        } else {
            $data = [
                'course' => $course,
                'title' => '',
                'description' => '',
                'video_url' => '',
                'content' => '',
                'order_index' => '',
                'title_err' => '',
                'order_index_err' => ''
            ];
            $this->view('instructor/add_lesson', $data);
        }
    }

    public function editLesson($lessonId) {
        $lesson = $this->courseModel->getLessonById($lessonId);
        if (!$lesson) {
            redirect('instructor/mycourses'); // Lesson not found
        }
        $course = $this->courseModel->getCourseById($lesson->course_id);
        if (!$course || $course->instructor_id != $_SESSION['user_id']) {
            redirect('instructor/mycourses'); // Not authorized
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'id' => $lessonId,
                'course_id' => $lesson->course_id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'video_url' => trim($_POST['video_url']),
                'content' => trim($_POST['content']),
                'order_index' => trim($_POST['order_index']),
                'title_err' => '',
                'order_index_err' => ''
            ];

            if (empty($data['title'])) { $data['title_err'] = 'Please enter lesson title'; }
            if (empty($data['order_index'])) { $data['order_index_err'] = 'Please enter order index'; }

            if (empty($data['title_err']) && empty($data['order_index_err'])) {
                if ($this->courseModel->updateLesson($data)) {
                    redirect('instructor/manageLessons/' . $data['course_id']);
                } else {
                    die('Something went wrong updating lesson');
                }
            } else {
                $data['course'] = $course;
                $data['lesson'] = $lesson;
                $this->view('instructor/edit_lesson', $data);
            }

        } else {
            $data = [
                'course' => $course,
                'lesson' => $lesson,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'video_url' => $lesson->video_url,
                'content' => $lesson->content,
                'order_index' => $lesson->order_index,
                'title_err' => '',
                'order_index_err' => ''
            ];
            $this->view('instructor/edit_lesson', $data);
        }
    }

    public function deleteLesson($lessonId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lesson = $this->courseModel->getLessonById($lessonId);
            if (!$lesson) {
                redirect('instructor/mycourses');
            }
            $course = $this->courseModel->getCourseById($lesson->course_id);
            if (!$course || $course->instructor_id != $_SESSION['user_id']) {
                redirect('instructor/mycourses'); // Not authorized
            }

            if ($this->courseModel->deleteLesson($lessonId, $lesson->course_id)) {
                redirect('instructor/manageLessons/' . $lesson->course_id);
            } else {
                die('Something went wrong deleting lesson');
            }
        } else {
            redirect('instructor/mycourses'); // Prevent direct access
        }
    }
}
?>