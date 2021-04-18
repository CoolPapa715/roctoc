<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$field_id = !empty($_POST['field_id']) ? $_POST['field_id'] : 0;

// update status
if(!empty($field_id)) {
	$query = "UPDATE custom_fields SET field_status = 1 WHERE field_id = :field_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':field_id', $field_id);
	$stmt->execute();
}
