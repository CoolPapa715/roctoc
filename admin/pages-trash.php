<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

$query = "SELECT COUNT(*) AS c FROM pages WHERE page_status = -1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pages_arr = array();

	$query = "SELECT * FROM pages WHERE page_status = -1";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$page_id    = $row['page_id'];
		$page_title = $row['page_title'];
		$page_slug  = $row['page_slug'];

		// sanitize
		$page_title = e($page_title);
		$page_slug  = e($page_slug);

		$page_link = "$baseurl/post/$page_slug";

		$cur_lop_arr = array(
			'page_id'    => $page_id,
			'page_title' => $page_title,
			'page_link'  => $page_link,
		);

		$pages_arr[] = $cur_lop_arr;
	}
}
