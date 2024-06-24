<?php

ini_set('include_path', dirname(__FILE__) . '/controllers');

require_once 'HomeController.php';
require_once 'LoginController.php';
require_once 'RegisterController.php';
require_once 'MediaController.php';
require_once 'MedicalController.php';
require_once 'ProfileController.php';
require_once 'RelationshipsController.php';
require_once 'ScheduleController.php';
require_once 'SelectController.php';
require_once 'TimelineController.php';
require_once 'ChildProfileController.php';
require_once 'UserController.php';

class Application
{
    public function router()
    {
        $route = strtok($_SERVER['REQUEST_URI'], '?'); // Get the route without query parameters

        // Map routes to controller and action methods
        $privateRoutes = [
            '/media' => [MediaController::class, 'index'],
            '/medical' => [MedicalController::class, 'index'],
            '/profile' => [ProfileController::class, 'index'],
            '/relationships' => [RelationshipsController::class, 'index'],
            '/schedule' => [ScheduleController::class, 'index'],
            '/select' => [SelectController::class, 'index'],
            '/timeline' => [TimelineController::class, 'index'],
            '/logout' => [LoginController::class, 'logout'],
            '/childProfile' => [ChildProfileController::class, 'index'],
        ];

        $publicRoutes = [
            '/home' => [HomeController::class, 'index'],
            '/login' => [LoginController::class, 'index'],
            '/register' => [RegisterController::class, 'index'],
        ];

        $apiRoutes = [
            '/relationship' => [RelationshipsController::class, 'relationshipsAPI'],
            '/medical' => [MedicalController::class, 'medicalAPI'],
            '/children' => [SelectController::class, 'childrenAPI'],
            '/session' => [LoginController::class, 'sessionAPI'],
            '/users' => [UserController::class, 'userAPI'],
            '/media' => [MediaController::class, 'mediaAPI'],
            '/schedule' => [ScheduleController::class, 'scheduleAPI'],
            '/posts' => [TimelineController::class, 'postsAPI'],
        ];

        // Check if the route is the RSS feed
        if ($route == '/timeline/rss') {
            $route = '/api/posts';
            $_GET['format'] = 'rss';
        }

        // Check if the route is an API route
        if (strpos($route, '/api') === 0) {
            $route = substr($route, 4);
            $parts = explode('/', $route);
            $route = '/' . $parts[1];
            if (isset($apiRoutes[$route])) {
                list($controller, $action) = $apiRoutes[$route];
                $controller = new $controller();

                if (isset($parts[2])) {
                    $id = $parts[2];
                    $controller->$action($id);
                } else
                    $controller->$action();

                return;
            }
        }

        // Check if userId is set in session
        if (isset($_SESSION['userId'])) {
            if (!isset($_COOKIE['childId']) && $route != '/select' && $route != '/profile') {
                header('Location: /select');
                exit();
            }
            if (isset($privateRoutes[$route])) {
                list($controller, $action) = $privateRoutes[$route];
                $controller = new $controller();
                $controller->$action();
            } else {
                if (isset($publicRoutes[$route])) {
                    header('Location: /select', TRUE, 303);
                } else {
                    echo "404 - Page not found (1)";
                }
            }
        } else {
            if (isset($publicRoutes[$route])) {
                list($controller, $action) = $publicRoutes[$route];
                $controller = new $controller();
                $controller->$action();
            } else {
                if (isset($privateRoutes[$route])) {
                    header('Location: /login', TRUE, 303);
                } else {
                    echo "404 - Page not found (2)";
                }
            }
        }
    }
}
