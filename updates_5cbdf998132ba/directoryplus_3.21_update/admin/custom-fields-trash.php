<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// count how many fields exist
$query = "SELECT COUNT(*) AS total_rows FROM custom_fields WHERE field_status = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['total_rows'];

// get all custom fields and their values
$custom_fields = array();
if($total_rows > 0) {
	$query = "SELECT field_id, field_name, field_type, required, searchable
	FROM custom_fields WHERE field_status = 0";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$custom_fields[] = array(
			'field_id'   => $row['field_id'],
			'field_name' => $row['field_name'],
			'field_type' => $row['field_type']
		);
	}
}
