<?php

class ScheduleController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Schedule/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Schedule/schedule.php';
        require_once '../app/views/footer.php';
    }

    public function scheduleAPI()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                require_once '../app/models/schedule/getSchedule.php';
                break;
            case 'PUT':
                require_once '../app/models/schedule/updateSchedule.php';
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }
}
