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

$page_url = "$baseurl/admin/reviews?page=";

// get reviews
$query = "SELECT COUNT(*) AS c FROM reviews WHERE status = 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	$query = "SELECT
		r.review_id, r.place_id, r.pubdate, r.rating, r.text, r.user_id, r.status,
		p.place_name, p.slug AS place_slug,
		rel.cat_id,
		cats.id AS cat_id, cats.cat_slug,
		c.city_id, c.slug AS city_slug, c.city_name,
		s.slug AS state_slug,
		ph.dir, ph.filename,
		u.first_name, u.last_name
		FROM reviews r
		LEFT JOIN places p ON r.place_id = p.place_id
		LEFT JOIN rel_place_cat rel ON rel.place_id = r.place_id AND is_main = 1
		LEFT JOIN cats ON cats.id = rel.cat_id
		LEFT JOIN cities c ON p.city_id = c.city_id
		LEFT JOIN states s ON c.state_id = s.state_id
		LEFT JOIN (SELECT * FROM photos GROUP BY place_id) ph ON ph.place_id = r.place_id
		LEFT JOIN users u ON r.user_id = u.id
		WHERE r.status = 'trashed'
		GROUP BY review_id
		ORDER BY pubdate DESC LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	// reviews array
	$reviews_arr = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$review_id         = $row['review_id'];

		$this_cat_id            = !empty($row['cat_id'    ]) ? $row['cat_id'    ] : '';
		$this_cat_slug          = !empty($row['cat_slug'  ]) ? $row['cat_slug'  ] : $this_cat_id;
		$this_dir               = !empty($row['dir'       ]) ? $row['dir'       ] : null;
		$this_filename          = !empty($row['filename'  ]) ? $row['filename'  ] : null;
		$this_first_name        = !empty($row['first_name']) ? $row['first_name'] : null;
		$this_last_name         = !empty($row['last_name' ]) ? $row['last_name' ] : null;
		$this_place_id          = !empty($row['place_id'  ]) ? $row['place_id'  ] : null;
		$this_place_name        = !empty($row['place_name']) ? $row['place_name'] : '-';
		$this_place_slug        = !empty($row['place_slug']) ? $row['place_slug'] : $this_place_id;
		$this_pubdate           = !empty($row['pubdate'   ]) ? $row['pubdate'   ] : '2016-03-18';
		$this_rating            = !empty($row['rating'    ]) ? $row['rating'    ] : 0;
		$this_review_city_id    = !empty($row['city_id'   ]) ? $row['city_id'   ] : null;
		$this_review_city_name  = !empty($row['city_name' ]) ? $row['city_name' ] : null;
		$this_review_city_slug  = !empty($row['city_slug' ]) ? $row['city_slug' ] : null;
		$this_review_state_slug = !empty($row['state_slug']) ? $row['state_slug'] : null;
		$this_status            = !empty($row['status'    ]) ? $row['status'    ] : null;
		$this_text              = !empty($row['text'      ]) ? $row['text'      ] : '';
		$this_user_id           = !empty($row['user_id'   ]) ? $row['user_id'   ] : '';

		// simplify date
		$this_pubdate = strtotime($this_pubdate);
		$this_pubdate = date( 'Y-m-d', $this_pubdate );

		// author_name
		$this_author_name = "$this_first_name $this_last_name";

		// limit strings
		if (strlen($this_place_name) > 20) {
			$this_place_name = mb_substr($this_place_name, 0, 20) . '...';
		}

		if (mb_strlen($this_author_name) > 10) {
			$this_author_name = mb_substr($this_author_name, 0, 10) . '...';
		}

		// sanitize
		$this_text        = e($this_text);
		$this_place_name  = e($this_place_name);
		$this_author_name = e($this_author_name);
		$this_place_slug  = e($this_place_slug);
		$this_first_name  = e($this_first_name);
		$this_last_name   = e($this_last_name);

		// link to the place's page
		$this_link_url = get_listing_link(
							$this_place_id,
							$this_place_slug,
							$this_cat_id,
							$this_cat_slug,
							'',
							$this_review_city_slug,
							$this_review_state_slug,
							$cfg_permalink_struct);

		$cur_arr = array(
					'review_id'        => $review_id,
					'author_name'      => $this_author_name,
					'dir'              => $this_dir,
					'filename'         => $this_filename,
					'link_url'         => $this_link_url,
					'place_id'         => $this_place_id,
					'place_name'       => $this_place_name,
					'pubdate'          => $this_pubdate,
					'rating'           => $this_rating,
					'review_city_id'   => $this_review_city_id,
					'review_city_name' => $this_review_city_name,
					'review_city_slug' => $this_review_city_slug,
					'status'           => $this_status,
					'text'             => $this_text,
					);

		$reviews_arr[] = $cur_arr;
	}
}