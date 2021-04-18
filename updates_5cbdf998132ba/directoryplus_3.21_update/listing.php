<?php
require_once(__DIR__ . '/inc/config.php');
require_once(__DIR__ . '/inc/img-exts.php');

/*--------------------------------------------------
Valid routes

listing/state/city/category/name
--------------------------------------------------*/

if(empty($route[4])) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

// find place by slug, if it fails, try by place id
$stmt = $conn->prepare("SELECT * FROM places WHERE slug = :slug");
$stmt->bindValue(':slug', $route[4]);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(empty($row)) {
	$stmt = $conn->prepare("SELECT * FROM places WHERE place_id = :place_id");
	$stmt->bindValue(':place_id', $route[4]);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(empty($row['place_id'])) {
	http_response_code(404);
	include($install_dir . '/templates/404.php');
	die();
}

$address         = !empty($row['address'        ]) ? $row['address'        ] : '';
$area_code       = !empty($row['area_code'      ]) ? $row['area_code'      ] : '';
$business_hours  = !empty($row['business_hours' ]) ? $row['business_hours' ] : '';
$city_id         = !empty($row['city_id'        ]) ? $row['city_id'        ] : 0;
$contact_email   = !empty($row['contact_email'  ]) ? $row['contact_email'  ] : '';
$country_code    = !empty($row['country_code'   ]) ? $row['country_code'   ] : '';
$cross_street    = !empty($row['cross_street'   ]) ? $row['cross_street'   ] : '';
$description     = !empty($row['description'    ]) ? $row['description'    ] : '';
$facebook        = !empty($row['facebook'       ]) ? $row['facebook'       ] : '';
$feat            = !empty($row['feat'           ]) ? $row['feat'           ] : 0;
$inside          = !empty($row['inside'         ]) ? $row['inside'         ] : '';
$lat             = !empty($row['lat'            ]) ? $row['lat'            ] : '';
$lng             = !empty($row['lng'            ]) ? $row['lng'            ] : '';
$logo            = !empty($row['logo'           ]) ? $row['logo'           ] : '';
$neighborhood    = !empty($row['neighborhood'   ]) ? $row['neighborhood'   ] : '';
$paid            = !empty($row['paid'           ]) ? $row['paid'           ] : 0;
$phone           = !empty($row['phone'          ]) ? $row['phone'          ] : '';
$place_id        = !empty($row['place_id'       ]) ? $row['place_id'       ] : '';
$place_name      = !empty($row['place_name'     ]) ? $row['place_name'     ] : '';
$place_slug      = !empty($row['slug'           ]) ? $row['slug'           ] : '';
$place_userid    = !empty($row['userid'         ]) ? $row['userid'         ] : 1;
$postal_code     = !empty($row['postal_code'    ]) ? $row['postal_code'    ] : '';
$short_desc      = !empty($row['short_desc'     ]) ? $row['short_desc'     ] : '';
$state_id        = !empty($row['state_id'       ]) ? $row['state_id'       ] : 0;
$status          = !empty($row['status'         ]) ? $row['status'         ] : '';
$submission_date = !empty($row['submission_date']) ? $row['submission_date'] : '';
$twitter         = !empty($row['twitter'        ]) ? $row['twitter'        ] : '';
$wa_area_code    = !empty($row['wa_area_code'   ]) ? $row['wa_area_code'   ] : '';
$wa_country_code = !empty($row['wa_country_code']) ? $row['wa_country_code'] : '';
$wa_phone        = !empty($row['wa_phone'       ]) ? $row['wa_phone'       ] : '';
$website         = !empty($row['website'        ]) ? $row['website'        ] : '';

// sanitize
$address         = e($address        );
$area_code       = e($area_code      );
$business_hours  = e($business_hours );
$contact_email   = e($contact_email  );
$country_code    = e($country_code   );
$cross_street    = e($cross_street   );
$description     = e($description    );
$facebook        = e($facebook       );
$inside          = e($inside         );
$lat             = e($lat            );
$lng             = e($lng            );
$logo            = e($logo           );
$neighborhood    = e($neighborhood   );
$phone           = e($phone          );
$place_name      = e($place_name     );
$place_slug      = e($place_slug     );
$postal_code     = e($postal_code    );
$short_desc      = e($short_desc     );
$twitter         = e($twitter        );
$wa_area_code    = e($wa_area_code   );
$wa_country_code = e($wa_country_code);
$wa_phone        = e($wa_phone       );
$website         = e($website        );

if(!$is_admin) {
	if($status != 'approved' && !$paid) {
		http_response_code(404);
		include($install_dir . '/templates/404.php');
		die();
	}
}

//get cat details
$query = "SELECT * FROM rel_place_cat
	INNER JOIN cats ON rel_place_cat.cat_id = cats.id
	WHERE rel_place_cat.place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$cat_id     = $row['cat_id'];
$cat_name   = $row['name'];
$cat_slug   = $row['cat_slug'];
$cat_plural = $row['plural_name'];

// get translated cat name if user language cookie is set
if(!empty($user_cookie_lang)) {
	$cat_name = cat_name_transl($cat_id , $user_cookie_lang, 'singular', $cat_name);
}

// cats path used in breadcrumbs
$cats_path   = get_parent($cat_id, array(), $conn);
$cats_path   = array_reverse($cats_path);
$cats_path[] = $cat_id;

/*--------------------------------------------------
Location
--------------------------------------------------*/

$query = "SELECT
			c.city_name, c.slug AS city_slug,
			s.state_id, s.state_name, s.state_abbr, s.slug AS state_slug
			FROM cities c
			INNER JOIN states s ON c.state_id = s.state_id
			WHERE city_id = :city_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':city_id', $city_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$city_name  = $row['city_name'];
$city_slug  = $row['city_slug'];
$state_id   = $row['state_id'];
$state_name = $row['state_name'];
$state_abbr = $row['state_abbr'];
$state_slug = $row['state_slug'];

/*--------------------------------------------------
Neighborhood
--------------------------------------------------*/
/*
$neighborhood_slug = '';
$neighborhood_name = '';

if(!empty($neighborhood)) {
	$query = "SELECT * FROM neighborhoods WHERE neighborhood_id = :neighborhood_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':neighborhood_id', $neighborhood);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$neighborhood_slug = $row['neighborhood_slug'];
	$neighborhood_name = $row['neighborhood_name'];
}

$places_in_neighborhood = '';
$neighborhood_link = '';

if(!empty($neighborhood)) {
	$query = "SELECT COUNT(*) AS total_count FROM places WHERE neighborhood = :neighborhood_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':neighborhood_id', $neighborhood);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$places_in_neighborhood = $row['total_count'];

	$neighborhood_link = "$baseurl/neighborhood/$neighborhood_slug";
}

// listings in the same neighborhood and category
$cat_members_in_neighborhood = '';
$cat_neighborhood_link = '';

if(!empty($neighborhood)) {
	$query = "SELECT COUNT(*) AS total_count FROM places p
		INNER JOIN rel_place_cat r ON p.place_id = r.place_id
	WHERE neighborhood = :neighborhood_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':neighborhood_id', $neighborhood);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$cat_members_in_neighborhood = $row['total_count'];

	$cat_neighborhood_link = "$baseurl/neighborhood/$neighborhood_slug/$cat_slug";
}
*/

/*--------------------------------------------------
Rating
--------------------------------------------------*/
$rating = '';
$stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM reviews WHERE place_id = :place_id");
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$rating = $row['avg_rating'];

/*--------------------------------------------------
Photos
--------------------------------------------------*/
$photos = array();
$stmt = $conn->prepare("SELECT * FROM photos WHERE place_id = :place_id");
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$img_url = $pic_baseurl . '/' . $place_full_folder . '/' . $row['dir'] . '/' . $row['filename'];
	$img_url_thumb = $pic_baseurl . '/' . $place_thumb_folder . '/' . $row['dir'] . '/' . $row['filename'];
	$data_title = $place_name . ' picture';
	$source = 'self';
	$photos[] = array('img_url' => $img_url, 'img_url_thumb' => $img_url_thumb, 'data_title' => $data_title, 'source' => 'self');
}

/*--------------------------------------------------
Reviews
--------------------------------------------------*/
$query = "SELECT
			UNIX_TIMESTAMP(pubdate) AS review_date, r.*, u.first_name, u.last_name
			FROM reviews r LEFT JOIN users u ON r.user_id = u.id
			WHERE r.place_id = :place_id AND r.status = 'approved'
			ORDER BY r.pubdate DESC LIMIT 100";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

$reviews = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$review_id              = $row['review_id'];
	$review_user_id         = $row['user_id'];
	$review_user_first_name = $row['first_name'];
	$review_user_last_name  = $row['last_name'];
	$review_rating          = $row['rating'];
	$review_text            = $row['text'];
	$review_pubdate         = $row['review_date'];

	// sanitize
	$review_user_first_name = e(trim($review_user_first_name));
	$review_user_last_name  = e(trim($review_user_last_name ));
	$review_text            = e(trim($review_text));

	// prepare vars
	$review_user_display_name = "$review_user_first_name $review_user_last_name";

	// review user profile pic
	$folder = floor($review_user_id / 1000) + 1;
	if(strlen($folder) < 1) {
		$folder = '999';
	}

	$review_user_profile_pic_path = $pic_basepath . '/' . $profile_thumb_folder . '/' . $folder . '/' . $review_user_id;
	$review_user_profile_pic_path = glob("$review_user_profile_pic_path.*");

	if(empty($review_user_display_name)) {
		$review_user_display_name = 'Anonymous';
	}

	if(!empty($review_user_profile_pic_path)) {
		$review_user_profile_pic_path = explode('/', $review_user_profile_pic_path[0]);
		$review_user_profile_pic_filename = end($review_user_profile_pic_path);
		$review_user_profile_pic_url = "$pic_baseurl/$profile_thumb_folder/$folder/$review_user_profile_pic_filename";
	}

	else {
		$review_user_profile_pic_url = "$baseurl/assets/imgs/blank.png";
	}

	$reviews[] = array(
			'review_id'         => $review_id,
			'user_id'           => $review_user_id,
			'user_display_name' => $review_user_display_name,
			'profile_pic_url'   => $review_user_profile_pic_url,
			'rating'            => $review_rating,
			'text'              => $review_text,
			'pubdate'           => $review_pubdate,
			'profile_link'      => $baseurl . '/profile/' . $review_user_id
	);
}

/*--------------------------------------------------
Logo
--------------------------------------------------*/
$logo_url = $baseurl . '/assets/imgs/blank.png';

if(!empty($photos[0]['img_url_thumb'])) {
	$logo_url = $photos[0]['img_url_thumb'];
}

if(!empty($logo)) {
	if(file_exists($pic_basepath . '/logo/' . substr($logo, 0, 2) . '/' . $logo)) {
		$logo_url = $pic_baseurl . '/logo/' . substr($logo, 0, 2) . '/' . $logo;
	}
}

/*--------------------------------------------------
Videos
--------------------------------------------------*/
$videos = array();
$stmt = $conn->prepare("SELECT * FROM videos WHERE place_id = :place_id");
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$video_url = !empty($row['video_url']) ? $row['video_url'] : '';

	// sanitize url
	$video_url = valid_url($video_url);

	// init vars
	$video_thumb = $baseurl . '/assets/imgs/blank.png';
	$video_data = array();
	$video_api_url = '';
	$video_title = '';

	if (strpos($video_url, 'youtu.be') !== false || strpos($video_url, 'youtube.com') !== false) {
		$video_api_url = "http://www.youtube.com/oembed?url=$video_url&format=json";
	}

	if (strpos($video_url, 'vimeo.com') !== false) {
		$video_api_url = "https://vimeo.com/api/oembed.json?url=$video_url";
	}

	if(!empty($video_api_url)) {
		$video_data = curl($video_api_url);
		$video_data = json_decode($video_data['data'], true);

		$video_thumb = $video_data['thumbnail_url'];
		$video_title = $video_data['title'];
	}

	// add to array
	if(!empty($video_url)) {
		$videos[] = array('url' => $video_url, 'title' => $video_title, 'thumb' => $video_thumb, 'data' => $video_data);
	}
}

/*--------------------------------------------------
Prepare
--------------------------------------------------*/
// add line break
$description = nl2p($description);

// business hours
$business_hours = nl2br($business_hours);

/*--------------------------------------------------
Coupons
--------------------------------------------------*/
$query = "SELECT * FROM coupons WHERE place_id = :place_id AND coupon_status > 0";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
// if this place has coupons
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$coupon_id          = !empty($row['id'         ]) ? $row['id'         ] : '';
	$coupon_title       = !empty($row['title'      ]) ? $row['title'      ] : '';
	$coupon_description = !empty($row['description']) ? $row['description'] : '';
	$coupon_place_id    = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
	$coupon_expire      = !empty($row['expire'     ]) ? $row['expire'     ] : '';
	$coupon_img         = !empty($row['img'        ]) ? $row['img'        ] : '';

	// sanitize
	$coupon_id          = e($coupon_id         );
	$coupon_title       = e($coupon_title      );
	$coupon_description = e($coupon_description);
	$coupon_place_id    = e($coupon_place_id   );
	$coupon_expire      = e($coupon_expire     );
	$coupon_img         = e($coupon_img        );

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

	// sanitize
	$coupon_title = e($coupon_title);
	$coupon_description = e($coupon_description);

	$cur_loop_arr = array(
					'coupon_id'          => $coupon_id,
					'coupon_title'       => $coupon_title,
					'coupon_description' => $coupon_description,
					'coupon_place_id'    => $coupon_place_id,
					'coupon_expire'      => $coupon_expire,
					'coupon_img'         => $coupon_img_url
					);

	// add cur loop to places array
	$coupons_arr[] = $cur_loop_arr;
}

/*--------------------------------------------------
Custom fields
--------------------------------------------------*/

// custom fields
$query = "SELECT
			r.field_value,
			f.*
			FROM rel_place_custom_fields r
			LEFT JOIN custom_fields f ON r.field_id = f.field_id
			WHERE r.place_id = :place_id AND f.field_status = 1
			ORDER BY f.field_order";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

$custom_fields = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$field_id    = !empty($row['field_id'   ]) ? $row['field_id'   ] : '';
	$field_value = !empty($row['field_value']) ? $row['field_value'] : '';
	$field_name  = !empty($row['field_name' ]) ? $row['field_name' ] : '';
	$field_type  = !empty($row['field_type' ]) ? $row['field_type' ] : '';
	$values_list = !empty($row['values_list']) ? $row['values_list'] : '';
	$tooltip     = !empty($row['tooltip'    ]) ? $row['tooltip'    ] : '';
	$icon        = !empty($row['icon'       ]) ? $row['icon'       ] : '';
	$required    = !empty($row['required'   ]) ? $row['required'   ] : '';
	$field_order = !empty($row['field_order']) ? $row['field_order'] : '';

	// sanitize
	$field_id    = e($field_id   );
	$field_value = e($field_value);
	$field_name  = e($field_name );
	$field_type  = e($field_type );
	$values_list = e($values_list);
	$tooltip     = e($tooltip    );
	$required    = e($required   );
	$field_order = e($field_order);

	$this_loop_array = array(
		'field_id'    => $field_id,
		'field_name'  => $field_name,
		'field_value' => $field_value,
		'field_type'  => $field_type,
		'values_list' => $values_list,
		'tooltip'     => $tooltip,
		'icon'        => $icon,
		'required'    => $required,
		'field_order' => $field_order
	);

	$custom_fields[] = $this_loop_array;
}

$custom_fields_grouped = array();

foreach($custom_fields as $v) {
	$custom_fields_grouped[$v['field_name']][] = array(
		'field_id'    => $v['field_id'],
		'field_value' => $v['field_value'],
		'field_type'  => $v['field_type'],
		'values_list' => $v['values_list'],
		'tooltip'     => $v['tooltip'],
		'icon'        => $v['icon'],
		'required'    => $v['required'],
		'field_order' => $v['field_order']
	);
}

/*--------------------------------------------------
Similar Listings
--------------------------------------------------*/
$similar_items = array();

$query = "SELECT
			p.place_id, p.place_name, p.logo, p.slug AS place_slug, p.address, p.cross_street,
			p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.feat, p.short_desc,
			c.city_name, c.slug, c.state,
			s.state_name, s.slug AS state_slug, s.state_abbr,
			cats.cat_icon, cats.name AS cat_name, cats.cat_slug, cats.id AS cat_id,
			ph.dir, ph.filename,
			rev_table.text, rev_table.avg_rating
		FROM places p
		LEFT JOIN rel_place_cat r ON p.place_id = r.place_id
		LEFT JOIN cities c ON c.city_id = p.city_id
		LEFT JOIN states s ON c.state_id = s.state_id
		LEFT JOIN cats ON r.cat_id = cats.id
		LEFT JOIN (SELECT * FROM photos) ph ON p.place_id = ph.place_id
		LEFT JOIN (
			SELECT *,
				AVG(rev.rating) AS avg_rating
				FROM reviews rev
				GROUP BY place_id
			) rev_table ON p.place_id = rev_table.place_id
		WHERE r.cat_id = :cat_id AND p.status = 'approved' AND p.paid = 1 AND p.place_id < :place_id
		GROUP BY p.place_id
		ORDER BY p.place_id DESC
		LIMIT 4";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_place_id         = !empty($row['place_id'    ]) ? $row['place_id'    ] : '';
	$this_address          = !empty($row['address'     ]) ? $row['address'     ] : '';
	$this_area_code        = !empty($row['area_code'   ]) ? $row['area_code'   ] : '';
	$this_cat_id           = !empty($row['cat_id'      ]) ? $row['cat_id'      ] : '';
	$this_cat_icon         = !empty($row['cat_icon'    ]) ? $row['cat_icon'    ] : '';
	$this_cat_name         = !empty($row['cat_name'    ]) ? $row['cat_name'    ] : '';
	$this_cat_slug         = !empty($row['cat_slug'    ]) ? $row['cat_slug'    ] : '';
	$this_cross_street     = !empty($row['cross_street']) ? $row['cross_street'] : '';
	$this_is_feat          = !empty($row['feat'        ]) ? $row['feat'        ] : '';
	$this_lat              = !empty($row['lat'         ]) ? $row['lat'         ] : '';
	$this_lng              = !empty($row['lng'         ]) ? $row['lng'         ] : '';
	$this_logo             = !empty($row['logo'        ]) ? $row['logo'        ] : '';
	$this_phone            = !empty($row['phone'       ]) ? $row['phone'       ] : '';
	$this_place_city_name  = !empty($row['city_name'   ]) ? $row['city_name'   ] : '';
	$this_place_city_slug  = !empty($row['slug'        ]) ? $row['slug'        ] : 'city';
	$this_place_name       = !empty($row['place_name'  ]) ? $row['place_name'  ] : '';
	$this_place_slug       = !empty($row['place_slug'  ]) ? $row['place_slug'  ] : $this_place_id;
	$this_place_state_abbr = !empty($row['state'       ]) ? $row['state'       ] : '';
	$this_place_state_id   = !empty($row['state_id'    ]) ? $row['state_id'    ] : '';
	$this_place_state_slug = !empty($row['state_slug'  ]) ? $row['state_slug'  ] : '';
	$this_postal_code      = !empty($row['postal_code' ]) ? $row['postal_code' ] : '';
	$this_rating           = !empty($row['avg_rating'  ]) ? $row['avg_rating'  ] : 5;
	$this_short_desc       = !empty($row['short_desc'  ]) ? $row['short_desc'  ] : '';

	// sanitize
	$this_place_id         = e($this_place_id        );
	$this_address          = e($this_address         );
	$this_area_code        = e($this_area_code       );
	$this_cat_name         = e($this_cat_name        );
	$this_cat_slug         = e($this_cat_slug        );
	$this_cross_street     = e($this_cross_street    );
	$this_is_feat          = e($this_is_feat         );
	$this_lat              = e($this_lat             );
	$this_lng              = e($this_lng             );
	$this_logo             = e($this_logo            );
	$this_phone            = e($this_phone           );
	$this_place_city_name  = e($this_place_city_name );
	$this_place_city_slug  = e($this_place_city_slug );
	$this_place_name       = e($this_place_name      );
	$this_place_slug       = e($this_place_slug      );
	$this_place_state_abbr = e($this_place_state_abbr);
	$this_place_state_id   = e($this_place_state_id  );
	$this_place_state_slug = e($this_place_state_slug);
	$this_postal_code      = e($this_postal_code     );
	$this_rating           = e($this_rating          );
	$this_short_desc       = e($this_short_desc      );

	// get translated cat name if user language cookie is set
	if(!empty($user_cookie_lang)) {
		$this_cat_name = cat_name_transl($this_cat_id , $user_cookie_lang, 'singular', $this_cat_name);
	}

	// thumb
	$this_photo_url = $baseurl . '/assets/imgs/blank.png';

	if(!empty($row['filename'])) {
		$this_photo_url = $pic_baseurl . '/' . $place_thumb_folder . '/' . $row['dir'] . '/' . $row['filename'];
	}

	// logo
	$this_logo_url = $baseurl . '/assets/imgs/blank.png';

	if(!empty($this_photo_url)) {
		$this_logo_url = $this_photo_url;
	}

	if(!empty($this_logo) && file_exists($pic_basepath . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo)) {
		$this_logo_url = $pic_baseurl . '/logo/' . substr($this_logo, 0, 2) . '/' . $this_logo;
	}

	// clean place name
	$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
	$this_place_name = str_replace($endash, "-", $this_place_name);

	// rating
	$this_rating = number_format((float)$this_rating, 2, isset($cfg_decimal_separator) ? $cfg_decimal_separator : '.', '');

	// link
	$this_place_link = $baseurl . '/listing/' . $this_place_state_slug . '/' . $this_place_city_slug . '/' . $this_cat_slug . '/' . $this_place_slug;

	// items array
	$similar_items[] = array(
		'address'      => $this_address,
		'area_code'    => $this_area_code,
		'cat_icon'     => $this_cat_icon,
		'cat_name'     => $this_cat_name,
		'cat_slug'     => $this_cat_slug,
		'city_name'    => $this_place_city_name,
		'city_slug'    => $this_place_city_slug,
		'cross_street' => $this_cross_street,
		'is_feat'      => $this_is_feat,
		'lat'          => $this_lat,
		'lng'          => $this_lng,
		'logo_url'     => $this_logo_url,
		'phone'        => $this_phone,
		'photo_url'    => $this_photo_url,
		'place_id'     => $this_place_id,
		'place_link'   => $this_place_link,
		'place_name'   => $this_place_name,
		'place_slug'   => $this_place_slug,
		'postal_code'  => $this_postal_code,
		'rating'       => $this_rating,
		'short_desc'   => $this_short_desc,
		'state_abbr'   => $this_place_state_abbr,
		'state_slug'   => $this_place_state_slug,
	);
}

/*--------------------------------------------------
Manager
--------------------------------------------------*/
$query = "SELECT * FROM users WHERE id = :place_userid";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_userid', $place_userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$manager_email      = !empty($row['email'             ]) ? $row['email'             ] : '';
$manager_first_name = !empty($row['first_name'        ]) ? $row['first_name'        ] : '';
$manager_last_name  = !empty($row['last_name'         ]) ? $row['last_name'         ] : '';
$manager_city       = !empty($row['city_name'         ]) ? $row['city_name'         ] : '';
$manager_country    = !empty($row['country_name'      ]) ? $row['country_name'      ] : '';
$manager_pic_status = !empty($row['profile_pic_status']) ? $row['profile_pic_status'] : '';
$manager_joined     = !empty($row['created'           ]) ? $row['created'           ] : '';

// sanitize
$manager_email      = e($manager_email     );
$manager_first_name = e($manager_first_name);
$manager_last_name  = e($manager_last_name );
$manager_city       = e($manager_city      );
$manager_country    = e($manager_country   );
$manager_pic_status = e($manager_pic_status);
$manager_joined     = e($manager_joined    );

// prepare vars
$email_frags = explode('@', $manager_email);
$email_prefix = $email_frags[0];

if(!empty($manager_first_name) && !empty($manager_last_name)) {
	$manager_display_name = $manager_first_name . ' ' . $manager_last_name;
}
elseif(!empty($manager_first_name) && empty($manager_last_name)) {
	$manager_display_name = $manager_first_name;
}
elseif(empty($prof_first_name) && !empty($manager_last_name)) {
	$manager_display_name = $manager_last_name;
}
else {
	$manager_display_name = $email_prefix;
}

$join_date = date($cfg_date_format, strtotime($manager_joined));

// profile pic
$manager_profile_pic = '';
$manager_profile_pic_folder = floor($place_userid / 1000) + 1;

if(strlen($manager_profile_pic_folder) < 1) {
	$manager_profile_pic_folder = '999';
}

// get profile pic filename
$manager_profile_pic_path = $profile_thumb_folder . '/' . $manager_profile_pic_folder . '/' . $place_userid;

foreach($img_exts as $v) {
	if(file_exists($pic_basepath . '/' . $manager_profile_pic_path . '.' . $v)) {
		$manager_profile_pic = $pic_baseurl . '/' . $manager_profile_pic_path . '.' . $v;
		break;
	}
}

/*--------------------------------------------------
Some vars
--------------------------------------------------*/

$website_url = $website;
$website = (!empty($website)) ?
	'<a href="' . $website . '" rel="nofollow" target="_blank">' . $website . '</a>'
	: '';

/*--------------------------------------------------
Is favorite
--------------------------------------------------*/
$is_fave = false;

if(!empty($_SESSION['user_connected'])) {
	$query = "SELECT * FROM rel_favorites WHERE place_id = :place_id AND userid = :userid";
	$stmt  = $conn->prepare($query);
	$stmt->bindValue(':userid', $userid);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row)) {
		$is_fave = true;
	}
}

/*--------------------------------------------------
Legacy compatibility
--------------------------------------------------*/
$specialties  = $short_desc;

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/listing';

if(!empty($place_slug)) {
	$canonical = "$baseurl/listing/$state_slug/$city_slug/$cat_slug/$place_slug";
}

else {
	$canonical = "$baseurl/listing/$state_slug/$city_slug/$cat_slug/$place_id";
}

/*--------------------------------------------------
Disqus
--------------------------------------------------*/
$page_identifier = 'listing-' . $place_id;