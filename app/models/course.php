<?php
// app/models/Course.php

class Course {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all courses (optionally filtered by instructor/status)
    public function getCourses($instructorId = null, $status = null) {
        $sql = 'SELECT courses.*, users.name as instructor_name, categories.name as category_name FROM courses 
                JOIN users ON courses.instructor_id = users.id 
                JOIN categories ON courses.category_id = categories.id';
        $conditions = [];
        if ($instructorId) {
            $conditions[] = 'courses.instructor_id = :instructor_id';
        }
        if ($status) {
            $conditions[] = 'courses.status = :status';
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $this->db->query($sql);
        if ($instructorId) {
            $this->db->bind(':instructor_id', $instructorId);
        }
        if ($status) {
            $this->db->bind(':status', $status);
        }
        return $this->db->resultSet();
    }

    // Get a single course by ID
    public function getCourseById($id) {
        $this->db->query('SELECT courses.*, users.name as instructor_name, categories.name as category_name FROM courses 
                          JOIN users ON courses.instructor_id = users.id 
                          JOIN categories ON courses.category_id = categories.id 
                          WHERE courses.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add a new course
    public function addCourse($data) {
        $this->db->query('INSERT INTO courses (instructor_id, category_id, title, description, price, image, status) 
                          VALUES (:instructor_id, :category_id, :title, :description, :price, :image, :status)');
        $this->db->bind(':instructor_id', $data['instructor_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    // Update an existing course
    public function updateCourse($data) {
        $this->db->query('UPDATE courses SET category_id = :category_id, title = :title, 
                          description = :description, price = :price, image = :image, status = :status 
                          WHERE id = :id AND instructor_id = :instructor_id'); // Ensure instructor owns the course
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':instructor_id', $data['instructor_id']);

        return $this->db->execute();
    }

    // Delete a course
    public function deleteCourse($id, $instructorId) {
        $this->db->query('DELETE FROM courses WHERE id = :id AND instructor_id = :instructor_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':instructor_id', $instructorId);
        return $this->db->execute();
    }

    // Get lessons for a course
    public function getLessonsByCourseId($courseId) {
        $this->db->query('SELECT * FROM lessons WHERE course_id = :course_id ORDER BY order_index ASC');
        $this->db->bind(':course_id', $courseId);
        return $this->db->resultSet();
    }

    // Add a lesson to a course
    public function addLesson($data) {
        $this->db->query('INSERT INTO lessons (course_id, title, description, video_url, content, order_index) 
                          VALUES (:course_id, :title, :description, :video_url, :content, :order_index)');
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':video_url', $data['video_url']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':order_index', $data['order_index']);
        return $this->db->execute();
    }

    // Get a single lesson by ID
    public function getLessonById($id) {
        $this->db->query('SELECT * FROM lessons WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update a lesson
    public function updateLesson($data) {
        $this->db->query('UPDATE lessons SET title = :title, description = :description, 
                          video_url = :video_url, content = :content, order_index = :order_index 
                          WHERE id = :id AND course_id = :course_id');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':video_url', $data['video_url']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':order_index', $data['order_index']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':course_id', $data['course_id']);
        return $this->db->execute();
    }

    // Delete a lesson
    public function deleteLesson($lessonId, $courseId) {
        $this->db->query('DELETE FROM lessons WHERE id = :id AND course_id = :course_id');
        $this->db->bind(':id', $lessonId);
        $this->db->bind(':course_id', $courseId);
        return $this->db->execute();
    }

    // Enroll a student in a course
    public function enrollStudent($studentId, $courseId) {
        $this->db->query('INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)');
        $this->db->bind(':student_id', $studentId);
        $this->db->bind(':course_id', $courseId);
        return $this->db->execute();
    }

    // Check if student is enrolled in a course
    public function isEnrolled($studentId, $courseId) {
        $this->db->query('SELECT * FROM enrollments WHERE student_id = :student_id AND course_id = :course_id');
        $this->db->bind(':student_id', $studentId);
        $this->db->bind(':course_id', $courseId);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    // Get enrolled courses for a student
    public function getEnrolledCourses($studentId) {
        $this->db->query('SELECT courses.*, users.name as instructor_name, enrollments.completion_date, enrollments.status as enrollment_status FROM enrollments 
                          JOIN courses ON enrollments.course_id = courses.id
                          JOIN users ON courses.instructor_id = users.id
                          WHERE enrollments.student_id = :student_id');
        $this->db->bind(':student_id', $studentId);
        return $this->db->resultSet();
    }

    // Mark course as completed for a student
    public function completeCourse($studentId, $courseId) {
        $this->db->query('UPDATE enrollments SET completion_date = CURRENT_TIMESTAMP, status = "completed" WHERE student_id = :student_id AND course_id = :course_id');
        $this->db->bind(':student_id', $studentId);
        $this->db->bind(':course_id', $courseId);
        return $this->db->execute();
    }

    // Get categories
    public function getCategories() {
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // Add a new category
    public function addCategory($name, $description = null) {
        $this->db->query('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    // Get course enrollments (for admin/instructor)
    public function getCourseEnrollments($courseId = null) {
        $sql = 'SELECT enrollments.*, users.name as student_name, courses.title as course_title 
                FROM enrollments JOIN users ON enrollments.student_id = users.id 
                JOIN courses ON enrollments.course_id = courses.id';
        if ($courseId) {
            $sql .= ' WHERE enrollments.course_id = :course_id';
        }
        $this->db->query($sql);
        if ($courseId) {
            $this->db->bind(':course_id', $courseId);
        }
        return $this->db->resultSet();
    }
}
?>