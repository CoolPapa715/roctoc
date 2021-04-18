<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// pagination
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$limit = $items_per_page;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

// sort order (parent, name)
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'name';

$page_url = "$baseurl/admin/categories?sort=$sort&page=";

// count how many cats
$query = "SELECT COUNT(*) AS c FROM cats WHERE cat_status = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	$cats_arr = array();

	// select all cats information and put in an array
	if($sort == 'name') {
		$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY name LIMIT :start, :limit";
	}

	if($sort == 'parent') {
		$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY parent_id LIMIT :start, :limit";
	}

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$cat_id          = $row['id'];
		$cat_name        = $row['name'];
		$cat_plural_name = $row['plural_name'];
		$cat_parent_id   = $row['parent_id'];
		$cat_order       = $row['cat_order'];

		// sanitize
		$cat_name        = e(trim($cat_name));
		$cat_plural_name = e(trim($cat_plural_name));
		$cat_parent_id   = (!empty($cat_parent_id)) ? $cat_parent_id : 0;

		$cur_loop_arr = array(
			'cat_id'          => $cat_id,
			'cat_name'        => $cat_name,
			'cat_plural_name' => $cat_plural_name,
			'cat_parent_id'   => $cat_parent_id,
			'cat_order'       => $cat_order
		);

		$cats_arr[] = $cur_loop_arr;
	}
}
