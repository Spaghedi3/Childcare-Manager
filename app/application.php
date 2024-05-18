<?php

namespace App;

class Application
{
    public function router()
    {
        console.log("router");
        $route = $_SERVER['REQUEST_URI'];

        // Map routes to controller and action methods
        $routes = [
            '/home' => [HomeController::class, 'index'],
            '/login' => [LoginController::class, 'LoginController'],
            '/register' => [RegisterController::class, 'Create_AccountController'],
            '/gallery' => [GalleryController::class, 'GalleryController'],
            '/medical' => [MedicalController::class, 'MedicalController'],
            '/profile' => [ProfileController::class, 'ProfileController'],
            '/relationships' => [RelationshipsController::class, 'RelationshipsController'],
            '/schedule' => [ScheduleController::class, 'ScheduleController'],
            '/select' => [SelectController::class, 'SelectController'],
            '/timeline' => [TimelineController::class, 'TimelineController'],

        ];

        if (isset($routes[$route])) {
            list($controller, $action) = $routes[$route];
            $controller = new $controller();
            $controller->$action();
        } else {
            // Handle non-existent routes (e.g., display 404 page)
            echo "404 - Page not found";
        }
    }
}