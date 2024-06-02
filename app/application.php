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
        ];

        $publicRoutes = [
            '/home' => [HomeController::class, 'index'],
            '/login' => [LoginController::class, 'index'],
            '/register' => [RegisterController::class, 'index'],
        ];

        $apiRoutes = [
            // TODO Restful API routes
            '/childProfile' => [ChildProfileController::class, 'index'],
            '/getChildProfile' => [ChildProfileController::class, 'getChildProfile'],
            '/getProfiles' => [SelectController::class, 'get_profiles'],
            '/addProfile' => [SelectController::class, 'add_profile'],
            '/updateProfile' => [SelectController::class, 'update_profile'],
            '/deleteProfile' => [SelectController::class, 'delete_profile'],
            '/loginTest' => [LoginController::class, 'login'],
            '/api/users' => [RegisterController::class, 'userAPI'],
            '/api/users/username' => [ProfileController::class, 'usernameAPI'],
            '/api/users/email' => [ProfileController::class, 'emailAPI'],
            '/api/users/password' => [ProfileController::class, 'passwordAPI'],
            '/api/media' => [MediaController::class, 'mediaAPI'],
        ];

        // Check if the route is an API route
        if (isset($apiRoutes[$route])) {
            list($controller, $action) = $apiRoutes[$route];
            $controller = new $controller();
            $controller->$action();
            return;
        }

        // Check if userId cookie is set
        if (isset($_COOKIE['userId'])) {
            if(!isset($_COOKIE['childId']) && $route != '/select' && $route != '/profile') {
                // TODO gray out the navbar links in profile page as well
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
