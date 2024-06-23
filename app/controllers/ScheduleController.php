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
        require_once '../app/models/db.php';
        require_once '../app/models/apiUtils.php';

        $input = json_decode(file_get_contents('php://input'), true);
        $connection = Database::getConnection();

        $this->validateSession();
        $userId = $_SESSION['userId'];
        $childId = $_SESSION['childId'];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                require_once '../app/models/schedule/getSchedule.php';
                break;
            case 'PUT':
                $this->validateSchedule($input);
                require_once '../app/models/schedule/updateSchedule.php';
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }

    private function validateSession()
    {
        if (!isset($_SESSION['userId']))
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);

        if (!isset($_SESSION['childId']))
            sendResponse(['status' => 'error', 'message' => 'Select child at /api/select'], 400);
    }

    private function validateSchedule($input)
    {
        $schedule = $input['schedule'];

        if (!is_array($schedule))
            sendResponse(['status' => 'error', 'message' => 'Schedule must be an array'], 400);

        foreach ($schedule as $day => $hours) {
            if (!is_array($hours))
                sendResponse(['status' => 'error', 'message' => 'Hours must be an array'], 400);

            foreach ($hours as $hour => $details) {
                if (!is_array($details) && $details !== null)
                    sendResponse(['status' => 'error', 'message' => 'Details must be an array or null'], 400);

                if ($details !== null) {
                    if (!isset($details['type']))
                        sendResponse(['status' => 'error', 'message' => 'Type is required'], 400);

                    if ($details['type'] !== 'sleep' && $details['type'] !== 'feed')
                        sendResponse(['status' => 'error', 'message' => 'Invalid type'], 400);
                }
            }
        }
    }
}
