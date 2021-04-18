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
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'sort-name';

$page_url = "$baseurl/admin/users-trash?sort=$sort&page=";

// count how many cats
$query = "SELECT COUNT(*) AS total_rows FROM users WHERE status = 'trashed'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['total_rows'];

// initialize cats array
$users_arr = array();

if($total_rows > 0) {
	$pager = new DirectoryPlus\PageIterator($limit, $total_rows, $page);
	$start = $pager->getStartRow();

	// select all cats information and put in an array
	if($sort == 'sort-name') {
		$query = "SELECT * FROM users WHERE status = 'trashed' ORDER BY first_name LIMIT :start, :limit";
	}

	if($sort == 'sort-email') {
		$query = "SELECT * FROM users WHERE status = 'trashed' ORDER BY email LIMIT :start, :limit";
	}

	if($sort == 'sort-date') {
		$query = "SELECT * FROM users WHERE status = 'trashed' ORDER BY created DESC LIMIT :start, :limit";
	}

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this_id              = $row['id'];
		$this_username        = $row['first_name'] . ' ' . $row['last_name'];
		$this_email           = $row['email'];
		$this_created         = $row['created'];
		$this_status          = $row['status'];
		$this_prof_pic_status = $row['profile_pic_status'];

		// sanitize
		$this_username = e($this_username       );
		$this_email    = e($this_email          );

		// username
		if (mb_strlen($this_username) > 15) {
			$this_username = mb_substr($this_username, 0, 15) . '...';
		}

		// sanitize
		$this_username = e(trim($this_username));
		$this_email    = e(trim($this_email));

		// simplify date
		$this_created = strtotime($this_created);
		$this_created = date( 'Y-m-d', $this_created );

		// profile pic
		$folder = floor($this_id / 1000) + 1;

		if(strlen($folder) < 1) {
			$folder = '999';
		}

		// profile pic path
		$this_pic_path = $pic_basepath . '/' . $profile_full_folder . '/' . $folder . '/' . $this_id;

		// check if file exists
		$this_pic_glob_arr = glob("$this_pic_path.*");

		if(!empty($this_pic_glob_arr)) {
			$this_prof_pic_filename = basename($this_pic_glob_arr[0]);
		}
		else {
			$this_prof_pic_filename = '';
		}

		if(!empty($this_pic_glob_arr)) {
			// set first match as profile pic
			$this_prof_pic_url = $pic_baseurl . '/' . $profile_full_folder . '/' . $folder . '/' . $this_prof_pic_filename;
		}
		else {
			$this_prof_pic_url = '';
		}

		$cur_loop_arr = array(
			'id'              => $this_id,
			'name'            => $this_username,
			'email'           => $this_email,
			'created'         => $this_created,
			'status'          => $this_status,
			'prof_pic_status' => $this_prof_pic_status,
			'prof_pic_url'    => $this_prof_pic_url
		);

		if($cur_loop_arr['id'] != 1) {
			$users_arr[] = $cur_loop_arr;
		}
	}
}
