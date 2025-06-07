<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    public $courseModel;

    public function __construct() {
        $this->courseModel = $this->model('Course');
    }

    public function index() {
        $data = [
            'title' => 'Welcome to EduHub!',
            'description' => 'Your journey to knowledge starts here.',
            'courses' => $this->courseModel->getCourses(null, 'published') // Get published courses
        ];
        $this->view('home/index', $data);
    }
}
?>