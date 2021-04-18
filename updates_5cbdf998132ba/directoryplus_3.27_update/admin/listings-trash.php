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

// sort order
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'date';

$page_url = "$baseurl/admin/listings-trash?sort=$sort&page=";

// get listings
$query = "SELECT COUNT(*) AS c FROM places WHERE status = 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// sort, order by
	$orderby = "p.place_name";
	$where = "WHERE p.status = 'trashed'";

	if($sort == 'date') {
		$orderby = "p.place_id DESC";
	}

	if($sort == 'name') {
		$orderby = "p.place_name";
	}

	// the query
	$query = "SELECT
			p.place_id, p.place_name, p.submission_date, p.feat_home, p.status, p.paid, p.userid, p.slug AS place_slug,
			c.city_name, c.slug, c.state,
			s.state_name, s.slug AS state_slug,
			rel.cat_id,
			cats.id AS cat_id, cats.name AS cat_name, cats.cat_slug,
			u.email,
			plans.plan_name
		FROM places p
			LEFT JOIN cities c ON p.city_id = c.city_id
			LEFT JOIN states s ON c.state_id = s.state_id
			LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id
			LEFT JOIN cats ON rel.cat_id = cats.id
			LEFT JOIN users u ON u.id = p.userid
			LEFT JOIN plans ON plans.plan_id = p.plan
		$where
		GROUP BY p.place_id
		ORDER BY $orderby LIMIT :start, :limit";

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	$places_arr = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_cat_id          = !empty($row['cat_id'         ]) ? $row['cat_id'         ] : '';
		$this_cat_name        = !empty($row['cat_name'       ]) ? $row['cat_name'       ] : '';
		$this_cat_slug        = !empty($row['cat_slug'       ]) ? $row['cat_slug'       ] : $this_cat_id;
		$this_city_name       = !empty($row['city_name'      ]) ? $row['city_name'      ] : '';
		$this_city_slug       = !empty($row['slug'           ]) ? $row['slug'           ] : '';
		$this_feat_home       = !empty($row['feat_home'      ]) ? $row['feat_home'      ] : '';
		$this_paid            = !empty($row['paid'           ]) ? $row['paid'           ] : '';
		$this_place_email     = !empty($row['email'          ]) ? $row['email'          ] : '';
		$this_place_id        = !empty($row['place_id'       ]) ? $row['place_id'       ] : '';
		$this_place_name      = !empty($row['place_name'     ]) ? $row['place_name'     ] : '';
		$this_place_owner     = !empty($row['userid'         ]) ? $row['userid'         ] : '';
		$this_place_slug      = !empty($row['place_slug'     ]) ? $row['place_slug'     ] : $this_place_id;
		$this_plan_name       = !empty($row['plan_name'      ]) ? $row['plan_name'      ] : '';
		$this_state_abbr      = !empty($row['state'          ]) ? $row['state'          ] : '';
		$this_state_slug      = !empty($row['state_slug'     ]) ? $row['state_slug'     ] : '';
		$this_status          = !empty($row['status'         ]) ? $row['status'         ] : '';
		$this_submission_date = !empty($row['submission_date']) ? $row['submission_date'] : '';

		// sanitize
		$this_place_name  = e($this_place_name);
		$this_place_slug  = e($this_place_slug);
		$this_place_email = e($this_place_email);

		// simplify date
		$this_submission_date = strtotime($this_submission_date);
		$this_date_formatted  = date($cfg_date_format, $this_submission_date);

		// link to each place
		$this_link_url = get_listing_link(
							$this_place_id,
							$this_place_slug,
							$this_cat_id,
							$this_cat_slug,
							'',
							$this_city_slug,
							$this_state_slug,
							$cfg_permalink_struct);

		$cur_loop_arr = array(
			'cat_name'       => $this_cat_name,
			'cat_slug'       => $this_cat_slug,
			'city_name'      => $this_city_name,
			'city_slug'      => $this_city_slug,
			'date_formatted' => $this_date_formatted,
			'feat_home'      => $this_feat_home,
			'link_url'       => $this_link_url,
			'paid'           => $this_paid,
			'place_email'    => $this_place_email,
			'user_email'     => $this_place_email,
			'place_id'       => $this_place_id,
			'place_name'     => $this_place_name,
			'place_owner'    => $this_place_owner,
			'place_slug'     => $this_place_slug,
			'plan_name'      => $this_plan_name,
			'state_abbr'     => $this_state_abbr,
			'status'         => $this_status,
		);

		$places_arr[] = $cur_loop_arr;
	}
}


