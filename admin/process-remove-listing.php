<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// listings details
$listing_id = !empty($_POST['place_id'  ]) ? $_POST['place_id'  ] : '';
$place_slug = !empty($_POST['place_slug']) ? $_POST['place_slug'] : '';
$cat_id     = !empty($_POST['cat_id'    ]) ? $_POST['cat_id'    ] : '';
$cat_slug   = !empty($_POST['cat_slug'  ]) ? $_POST['cat_slug'  ] : '';
$city_id    = !empty($_POST['city_id'   ]) ? $_POST['city_id'   ] : '';
$city_slug  = !empty($_POST['city_slug' ]) ? $_POST['city_slug' ] : '';
$state_slug = !empty($_POST['state_slug']) ? $_POST['state_slug'] : '';

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = 'admin' AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':template', 'listings');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = $row['translated'];
}

try {
	$conn->beginTransaction();

	// set status = 'trashed' in db
	$query = "UPDATE places SET status = 'trashed' WHERE place_id = :place_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $listing_id);
	$stmt->execute();

	$conn->commit();
	$result_message = $txt_place_removed;

	echo $result_message;

	/*--------------------------------------------------
	Build url to delete from sitemap
	--------------------------------------------------*/
	if($cfg_enable_sitemaps) {
		$link_url = get_listing_link($listing_id, $place_slug, $cat_id, $cat_slug, $city_id, $city_slug, $state_slug, $cfg_permalink_struct);

		if(!empty($link_url)) {
			sitemap_remove_url($link_url);
		}

		echo $link_url;
	}
}

catch(PDOException $e) {
	$conn->rollBack();
	$result_message =  $e->getMessage();

	echo $result_message;
}
