<?php

class MediaController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Media/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Media/media.php';
        require_once '../app/views/footer.php';
    }
}