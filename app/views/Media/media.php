<?php
if (!isset($_COOKIE['child_id'])) {
    header('Location: /select');
    exit();
}
?>

<div class="media">
    <div class="menu">
        <a class="current" href="/media">Photos</a>
        <a href="#">Videos</a>
        <a href="#">Audio</a>
        <a href="#">Documents</a>
    </div>

    <div class="content">
    </div>
</div>