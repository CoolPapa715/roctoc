<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// get all categories
$cats_arr = array();
$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY plural_name";
$stmt = $conn->prepare($query);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cur_loop_arr = array(
		'cat_id'      => $row['id'],
		'cat_name'    => $row['name'],
		'plural_name' => $row['plural_name'],
		'parent_id'   => $row['parent_id']
	);

	$cats_arr[] = $cur_loop_arr;
}

// store total number of cats in a variable
$total_cats = count($cats_arr);
