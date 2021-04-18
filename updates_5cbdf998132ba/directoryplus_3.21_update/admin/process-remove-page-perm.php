<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$page_id = !empty($_POST['page_id']) ? $_POST['page_id'] : 0;

// update status
if(!empty($page_id)) {
	// delete from db
	$query = "DELETE FROM pages WHERE page_id = :page_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':page_id', $page_id);
	$stmt->execute();
}