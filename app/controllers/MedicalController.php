<?php

class MedicalController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Medical_Info/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Medical_Info/medical_info.php';
        require_once '../app/views/footer.php';
    }
}
