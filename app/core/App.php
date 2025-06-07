<?php
// app/core/App.php

class App {
    protected $currentController = DEFAULT_CONTROLLER;
    protected $currentMethod = DEFAULT_METHOD;
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Look in controllers for first part of URL
        if (file_exists(__DIR__ . '/../controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            // If exists, set as current controller
            $this->currentController = ucfirst($url[0]);
            unset($url[0]);
        }

        // Require the controller
        require_once __DIR__ . '/../controllers/' . $this->currentController . 'Controller.php';

        // Instantiate controller class (e.g., new HomeController())
        $controllerClassName = $this->currentController . 'Controller';
        $this->currentController = new $controllerClassName();

        // Check for second part of URL (method)
        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [DEFAULT_CONTROLLER, DEFAULT_METHOD]; // Default if no URL
    }
}
?>