<?php

ini_set('include_path', dirname(__FILE__) . '/controllers');

require_once 'HomeController.php';
require_once 'LoginController.php';
require_once 'RegisterController.php';
require_once 'GalleryController.php';
require_once 'MedicalController.php';
require_once 'ProfileController.php';
require_once 'RelationshipsController.php';
require_once 'ScheduleController.php';
require_once 'SelectController.php';
require_once 'TimelineController.php';

class Application
{
    public function router()
    {
        $route = strtok($_SERVER['REQUEST_URI'], '?'); // Get the route without query parameters

        // Map routes to controller and action methods
        $routes = [
            '/home' => [HomeController::class, 'index'],
            '/login' => [LoginController::class, 'index'],
            '/loginTest' => [LoginController::class, 'login'],
            '/register' => [RegisterController::class, 'index'],
            '/registerTest' => [RegisterController::class, 'register'],
            '/gallery' => [GalleryController::class, 'index'],
            '/medical' => [MedicalController::class, 'index'],
            '/profile' => [ProfileController::class, 'index'],
            '/relationships' => [RelationshipsController::class, 'index'],
            '/schedule' => [ScheduleController::class, 'index'],
            '/select' => [SelectController::class, 'index'],
            '/timeline' => [TimelineController::class, 'index'],
        ];

        if (isset($routes[$route])) {
            list($controller, $action) = $routes[$route];
            $controller = new $controller();
            $controller->$action();
        } else {
            // Handle non-existent routes
            echo "404 - Page not found";
        }
    }
}
