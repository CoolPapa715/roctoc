<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$place_id = $_POST['place_id'];

// get paid status
$query = "SELECT paid FROM `places` where place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$paid = $row['paid'];

if($paid == 1){
	$query = "UPDATE places SET paid = 0 WHERE place_id= :place_id";
	$paid = 'unpaid';
}

else {
	$query = "UPDATE places SET paid = 1 WHERE place_id= :place_id";
	$paid = 'paid';
}

$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

// get status
$query = "SELECT status FROM `places` where place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$status = $row['status'];

// sitemaps
if($cfg_enable_sitemaps) {
	if($status == 'approved' && $paid == 'paid'){
		$link_url = get_listing_link($place_id);
		if(!empty($link_url)) {
			sitemap_add_url($link_url);
		}
	}

	elseif($status == 'approved' && $paid == 'unpaid'){
		$link_url = get_listing_link($place_id);
		if(!empty($link_url)) {
			sitemap_remove_url($link_url);
		}
	}

	elseif($status == 'pending' && $paid == 'paid'){

	}

	else {
		// $status == 'pending' && $paid == 'unpaid'
	}
}

echo $paid;