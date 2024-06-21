<?php

class RelationshipsController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Relationships/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Relationships/relationships.php';
        require_once '../app/views/footer.php';
    }

    public function updateRelationship()
    {
        require_once '../app/models/relationships/updateRelationship.php';
    }

    public function getRelationships()
    {
        require_once '../app/models/relationships/getRelationships.php';
    }
}
