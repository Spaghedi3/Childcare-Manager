<?php

class GalleryController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Gallery/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Gallery/gallery.php';
        require_once '../app/views/footer.php';
    }
}