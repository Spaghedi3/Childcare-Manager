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

    public function mediaAPI()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once '../app/models/media/addFile.php';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                require_once '../app/models/media/getFile.php';
            } else {
                require_once '../app/models/media/getFiles.php';
            }
        } else {
            sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
        }
    }
}
