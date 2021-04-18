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

$page_url = "$baseurl/admin/pages?page=";

// count pages
$query = "SELECT COUNT(*) AS c FROM pages WHERE page_status >= 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	$query = "SELECT page_id, page_title, page_slug, page_group, page_order FROM pages WHERE page_status >= 0 ORDER BY page_id DESC LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	$pages_arr = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$page_id    = $row['page_id'];
		$page_title = $row['page_title'];
		$page_slug  = $row['page_slug'];
		$page_group = $row['page_group'];
		$page_order = $row['page_order'];

		// sanitize
		$page_title = e($page_title);
		$page_slug  = e($page_slug);
		$page_group = e($page_group);
		$page_order = e($page_order);

		$page_link = "$baseurl/post/$page_slug";

		$cur_lop_arr = array(
			'page_id'    => $page_id,
			'page_title' => $page_title,
			'page_link'  => $page_link,
			'page_group' => $page_group,
			'page_order' => $page_order,
		);

		$pages_arr[] = $cur_lop_arr;
	}
}
