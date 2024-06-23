<?php

class MediaController
{
    public function index()
    {
        require_once '../app/models/media/mediaModel.php';

        require_once '../app/views/header.php';
        require_once '../app/views/Media/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Media/media.php';
        require_once '../app/views/footer.php';
    }

    public function mediaAPI($id = null)
    {
        require_once '../app/models/db.php';
        require_once '../app/models/apiUtils.php';

        $this->validateSession();
        $userId = $_SESSION['userId'];
        $childId = $_SESSION['childId'];

        $connection = Database::getConnection();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $type = $this->validateUpload();
                require_once '../app/models/media/addFile.php';
                break;
            case 'DELETE':
                $this->validateMediaId($connection, $userId, $childId, $id);
                require_once '../app/models/media/deleteFile.php';
                break;
            case 'GET':
                $input = json_decode(file_get_contents('php://input'), true);
                if ($id) {
                    $this->validateMediaId($connection, $userId, $childId, $id);
                    require_once '../app/models/media/getFile.php';
                } else {
                    $type = $this->validateGetFiles();
                    require_once '../app/models/media/getFiles.php';
                }
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }

    private function validateSession($id = null)
    {
        if (!isset($_SESSION['userId']))
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);

        if (!isset($_SESSION['childId']))
            sendResponse(['status' => 'error', 'message' => 'Select child at /api/select'], 400);

        if ($id !== null && !is_numeric($id) && $id !== 'undefined')
            sendResponse(['status' => 'error', 'message' => 'Post ID must be an integer'], 400);
    }

    private function validateUpload()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK)
            sendResponse(['status' => 'error', 'message' => 'No file uploaded'], 400);

        $fileType = $_FILES['file']['type'];
        $mimeToType = [
            'image/jpeg' => 'image',
            'image/png' => 'image',
            'image/gif' => 'image',
            'audio/mpeg' => 'audio',
            'audio/wav' => 'audio',
            'audio/mp4' => 'audio',
            'video/mp4' => 'video',
            'application/pdf' => 'document',
            'application/msword' => 'document',
        ];

        if (!array_key_exists($fileType, $mimeToType))
            sendResponse(['status' => 'error', 'message' => 'Unsupported media type'], 400);

        return $mimeToType[$fileType];
    }

    private function validateGetFiles()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        if ($type !== null && !in_array($type, ['audio', 'video', 'document', 'image']))
            sendResponse(['status' => 'error', 'message' => 'Invalid type specified'], 400);

        return $type;
    }

    private function validateMediaId($connection, $userId, $childId, $id)
    {
        if (!mediaExistsById($connection, $userId, $childId, $id)) {
            sendResponse(['status' => 'error', 'message' => 'Media not found'], 404);
        }
    }
}
