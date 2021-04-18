<?php
require_once(__DIR__ . '/inc/config.php');

/*--------------------------------------------------
Valid routes

categories/
categories/state/city (1)

baseurl/categories/state/city AND has city_id
	show all cities link

baseurl/categories AND has city_id
	show city cats link

baseurl/categories AND no city_id
	show nothing
--------------------------------------------------*/

// init
$option_link = '';
$city_id     = 0;
$city_slug   = '';
$state_slug  = '';

/*--------------------------------------------------
City details
--------------------------------------------------*/
// case baseurl/categories/state/city
if(!empty($route[1]) && !empty($route[2])) {
	$query = "SELECT
				c.city_id, c.city_name, c.slug AS city_slug,
				s.state_abbr, s.slug AS state_slug
				FROM cities c LEFT JOIN states s ON c.state_id = s.state_id
				WHERE c.slug = :city_slug";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':city_slug', $route[2]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$city_id    = $row['city_id'];
	$city_name  = $row['city_name'];
	$city_slug  = $row['city_slug'];
	$state_abbr = $row['state_abbr'];
	$state_slug = $row['state_slug'];

	// show all locs link
	$option_link = $txt_all_locs;
	$option_link = "<a href='$baseurl/categories/'>$option_link</a>";
}

// else case baseurl/categories
else {
	// has city id
	if(!empty($_COOKIE['city_id']) && !empty($_COOKIE['city_name']) && !empty($_COOKIE['city_slug']) && !empty($_COOKIE['state_slug'])) {
		$option_link = $txt_suggest_city;
		$option_link = str_replace('%city_name%' , $_COOKIE['city_name'] , $option_link);
		$option_link = '<a href="' . $baseurl . '/categories/' . $_COOKIE['state_slug'] . '/' . $_COOKIE['city_slug'] . '">' . $option_link . '</a>';
	}
}

/*--------------------------------------------------
$cat_items_count - Count for each category
--------------------------------------------------*/
if($city_id != 0) {
	$query = "SELECT r.cat_id, COUNT(*) AS cat_count
			FROM rel_place_cat r
			INNER JOIN places p ON r.place_id = p.place_id
			WHERE r.city_id = :city_id AND p.status = 'approved'
			GROUP BY cat_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $city_id);
	$stmt->execute();
}

else {
	$query = "SELECT r.cat_id, COUNT(*) AS cat_count
			FROM rel_place_cat r
			INNER JOIN places p ON r.place_id = p.place_id
			WHERE p.status = 'approved'
			GROUP BY cat_id";
	$stmt = $conn->prepare($query);
	$stmt->execute();
}

// init items count arr
$cat_items_count = array();

// build array with items count for each cat
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cat_items_count[$row['cat_id']] = $row['cat_count'];
}

/*--------------------------------------------------
$cats_arr - flat array with all cats
--------------------------------------------------*/
$cats_arr = array();

$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY parent_id, cat_order, name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if(!empty($city_id) && !empty($city_slug) && !empty($state_slug)) {
		$cat_link = $baseurl . '/listings/' . $state_slug . '/' . $city_slug . '/' . $row['cat_slug'];
	}

	else {
		$cat_link = $baseurl . '/listings/' . $row['cat_slug'];
	}

	// get translated cat name if user language cookie is set
	$cat_name = $row['name'];

	if(!empty($user_cookie_lang)) {
		$cat_name = cat_name_transl($row['id'], $user_cookie_lang, 'singular', $cat_name);
	}

	$cur_loop_arr = array(
		'cat_id'      => $row['id'],
		'cat_name'    => $cat_name,
		'cat_slug'    => $row['cat_slug'],
		'plural_name' => $row['plural_name'],
		'parent_id'   => $row['parent_id'],
		'cat_icon'    => $row['cat_icon'],
		'cat_link'    => $cat_link,
	);

	$cats_arr[] = $cur_loop_arr;
}

/*--------------------------------------------------
$cats_grouped_by_parent
--------------------------------------------------*/

// array with cats grouped by parent_id
$cats_grouped_by_parent = group_cats_by_parent($cats_arr);

/*--------------------------------------------------
$cat_parents - create another cat hierarchy so it's easier to calculate and sum counts
--------------------------------------------------*/
$cat_parents = array();

foreach($cats_grouped_by_parent as $k => $v) {
	foreach($v as $k2 => $v2) {
		$cat_parents[$v2['cat_id']] = $v2['parent_id'];
	}
}

/*--------------------------------------------------
$cat_tree
--------------------------------------------------*/

function buildTree($items) {
    $childs = array();

    foreach($items as &$item) {
		$childs[$item['parent_id']][] = &$item;
	}

    unset($item);

    foreach($items as &$item) if (isset($childs[$item['cat_id']])) {
		$item['childs'] = $childs[$item['cat_id']];
	}

    return $childs[0];
}

$cat_tree = buildTree($cats_arr);

/*--------------------------------------------------
Sum item count to parents
--------------------------------------------------*/
//echo '<h2>$cat_parents</h2>';
//print_r2($cat_parents);
//echo '<h2>$cats_grouped_by_parent</h2>';
//print_r2($cats_grouped_by_parent);
foreach($cats_grouped_by_parent as $k => $v) {
	foreach($v as $k2 => $v2) {
		if(isset($v2['parent_id']) && $v2['parent_id'] != 0) {
			if(!isset($cat_items_count[$v2['parent_id']])) {
				$cat_items_count[$v2['parent_id']] = 0;
			}

			if(!isset($cat_items_count[$v2['cat_id']])) {
				$cat_items_count[$v2['cat_id']] = 0;
			}

			// add count to immediate parent
			$cat_items_count[$v2['parent_id']] += $cat_items_count[$v2['cat_id']];

			// add count to parent parent
			if(isset($cat_parents[$v2['parent_id']])) {
				if(isset($cat_items_count[$cat_parents[$v2['parent_id']]])) {
					$cat_items_count[$cat_parents[$v2['parent_id']]] += $cat_items_count[$v2['cat_id']];
				}

				else {
					$cat_items_count[$cat_parents[$v2['parent_id']]] = $cat_items_count[$v2['cat_id']];
				}
			}
		}
	}
}

/*--------------------------------------------------
Get total listings count
--------------------------------------------------*/

$query = "SELECT COUNT(*) AS c FROM places WHERE status = 'approved'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$total_ads = $row['c'];

/*--------------------------------------------------
translation replacements
--------------------------------------------------*/
if(!empty($city_id)) {
	// case: city defined
	$txt_html_title_1  = str_replace('%city_name%' , $city_name , $txt_html_title_1);
	$txt_html_title_1  = str_replace('%state_abbr%', $state_abbr, $txt_html_title_1);
	$txt_meta_desc_1   = str_replace('%city_name%' , $city_name , $txt_meta_desc_1);
	$txt_meta_desc_1   = str_replace('%state_abbr%', $state_abbr, $txt_meta_desc_1);
	$txt_main_title_1  = str_replace('%city_name%' , $city_name , $txt_main_title_1);
	$txt_main_title_1  = str_replace('%state_abbr%', $state_abbr, $txt_main_title_1);
	$txt_all_cats_city = str_replace('%city_name%', $city_name, $txt_all_cats_city);

	$txt_html_title = $txt_html_title_1;
	$txt_meta_desc  = $txt_meta_desc_1;
	$txt_main_title = $txt_main_title_1;
	$txt_all_cats   = $txt_all_cats_city;
}

else {
	$txt_html_title = $txt_html_title_2;
	$txt_meta_desc  = $txt_meta_desc_2;
	$txt_main_title = $txt_main_title_2;
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/categories';

if(!empty($city_slug) && !empty($state_slug)) {
	$canonical .= '/' . $state_slug . '/' . $city_slug;
}
