<?php

class TimelineController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Timeline/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Timeline/timeline.php';
        require_once '../app/views/footer.php';
    }

    public function postsAPI($id = null)
    {
        require_once '../app/models/db.php';
        require_once '../app/models/apiUtils.php';

        $connection = Database::getConnection();
        $input = json_decode(file_get_contents('php://input'), true);

        $this->validateInput($id, $input);
        $userId = $_SESSION['userId'];
        $childId = $_SESSION['childId'];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                require_once '../app/models/timeline/getPosts.php';
                break;
            case 'POST':
                require_once '../app/models/timeline/addPost.php';
                break;
            case 'DELETE':
                if ($id)
                    require_once '../app/models/timeline/deletePost.php';
                else
                    sendResponse(['status' => 'error', 'message' => 'Post ID is required'], 400);
                break;
            case 'PATCH':
                if ($id)
                    require_once '../app/models/timeline/editPost.php';
                else
                    sendResponse(['status' => 'error', 'message' => 'Post ID is required'], 400);
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }

    private function validateInput($id = null, $input)
    {
        if (!isset($_SESSION['userId'])) {
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
        }

        if (!isset($_SESSION['childId'])) {
            sendResponse(['status' => 'error', 'message' => 'Child ID is required'], 400);
        }

        if ($id !== null && !is_numeric($id))
            sendResponse(['status' => 'error', 'message' => 'Post ID must be an integer'], 400);

        if (isset($input['title']) && !is_string($input['title']))
            sendResponse(['status' => 'error', 'message' => 'Title must be a string'], 400);

        if (isset($input['content']) && !is_string($input['content']))
            sendResponse(['status' => 'error', 'message' => 'Content must be a string'], 400);

        if (isset($input['mediaId']) && !is_int($input['mediaId']))
            sendResponse(['status' => 'error', 'message' => 'Media ID must be an integer'], 400);

        if (isset($input['tags']) && !is_array($input['tags']))
            sendResponse(['status' => 'error', 'message' => 'Tags must be an array'], 400);
        
        if (isset($_GET['relationship_type']) && !is_string($_GET['relationship_type']))
            sendResponse(['status' => 'error', 'message' => 'Relationship type must be a string'], 400);
    }
}
