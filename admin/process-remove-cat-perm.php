<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$cat_id = !empty($_POST['cat_id']) ? $_POST['cat_id'] : 0;

if(!empty($cat_id)) {
	$query = "UPDATE cats SET cat_status = -2 WHERE id = :cat_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $cat_id);

	if($stmt->execute()) {
		echo 'Category removed:' . $cat_url;
	}
}