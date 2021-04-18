<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$place_id = $_POST['place_id'];
$status = $_POST['status'];

if($status == 'pending'){
	$query = "UPDATE places SET status = 'approved' WHERE place_id= :place_id";
	$status = 'approved';
}

else {
	$query = "UPDATE places SET status = 'pending' WHERE place_id= :place_id";
	$status = 'pending';
}

$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

// get paid status
$query = "SELECT paid FROM `places` where place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$paid = $row['paid'];

// sitemaps
if($cfg_enable_sitemaps) {
	if($status == 'approved' && $paid == 1){
		$link_url = get_listing_link($place_id);
		if(!empty($link_url)) {
			sitemap_add_url($link_url);
		}
	}

	elseif($status == 'approved' && $paid == 0){
		// do nothing
	}

	elseif($status == 'pending' && $paid == 1){
		$link_url = get_listing_link($place_id);
		if(!empty($link_url)) {
			sitemap_remove_url($link_url);
		}
	}

	else {
		// do nothing
	}
}

echo $status;