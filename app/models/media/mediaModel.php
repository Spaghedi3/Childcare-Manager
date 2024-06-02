<?php

require_once '../app/models/db.php';

if (isset($_GET['type'])) {
	$type = $_GET['type'];
	if ($type != 'audio' && $type != 'video' && $type != 'document') {
		$type = 'image';
	}
} else {
	$type = 'image';
}