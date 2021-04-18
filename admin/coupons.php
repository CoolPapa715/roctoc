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

$page_url = "$baseurl/admin/coupons?page=";

// get coupons
$query = "SELECT COUNT(*) AS c FROM coupons WHERE coupon_status > -1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['c'];

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	$query = "SELECT c.*,
				p.place_name
				FROM coupons c
				LEFT JOIN places p ON c.place_id = p.place_id
				WHERE coupon_status > -1 ORDER BY created DESC LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();

	// if this user has coupons
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$coupon_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
		$coupon_title       = !empty($row['title'      ]) ? $row['title'      ] : '';
		$coupon_description = !empty($row['description']) ? $row['description'] : '';
		$coupon_place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
		$coupon_created     = !empty($row['created'    ]) ? $row['created'    ] : '';
		$coupon_expire      = !empty($row['expire'     ]) ? $row['expire'     ] : '';
		$coupon_img         = !empty($row['img'        ]) ? $row['img'        ] : '';
		$place_name         = !empty($row['place_name' ]) ? $row['place_name' ] : '';

		$coupon_id          = e($coupon_id         );
		$coupon_title       = e($coupon_title      );
		$coupon_description = e($coupon_description);
		$coupon_place_id    = e($coupon_place_id   );
		$coupon_created     = e($coupon_created    );
		$coupon_expire      = e($coupon_expire     );
		$coupon_img         = e($coupon_img        );
		$place_name         = e($place_name        );

		// format datetime to date
		$coupon_expire = new DateTime($coupon_expire);
		$coupon_expire = $coupon_expire->format("Y-m-d");
		$coupon_created = new DateTime($coupon_created);
		$coupon_created = $coupon_created->format("Y-m-d");

		// photo_url
		$coupon_img_url = '';
		if(!empty($coupon_img)) {
			$coupon_img_url = $baseurl . '/pictures/coupons/' . substr($coupon_img, 0, 2) . '/' . $coupon_img;
		}

		else {
			$coupon_img_url = $baseurl . '/assets/imgs/blank.png';
		}

		// description
		if(!empty($coupon_description)) {
			$coupon_description = mb_substr($coupon_description, 0, 75) . '...';
		}

		// link
		$coupon_link = $baseurl . '/coupon/' . $coupon_id;

		$cur_loop_arr = array(
						'coupon_id'          => $coupon_id,
						'coupon_title'       => $coupon_title,
						'coupon_description' => $coupon_description,
						'coupon_place_id'    => $coupon_place_id,
						'coupon_created'     => $coupon_created,
						'coupon_expire'      => $coupon_expire,
						'coupon_img'         => $coupon_img_url,
						'coupon_link'        => $coupon_link,
						'place_name'         => $place_name,
						);

		// add cur loop to places array
		$coupons_arr[] = $cur_loop_arr;
	}
}

