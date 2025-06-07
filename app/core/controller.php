<?php
// app/core/Controller.php

class Controller {
    // Load model
    public function model($model) {
        // Require model file
        require_once __DIR__ . '/../models/' . $model . '.php';
        // Instantiate model
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Check for view file
        if (file_exists(__DIR__ . '/../views/' . $view . '.php')) {
            require_once __DIR__ . '/../views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }
}
?>