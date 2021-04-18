<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../sitemaps/sitemap-functions.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_php.php');

/*--------------------------------------------------
init vars
--------------------------------------------------*/
$errors = array();
$amount = 0;

// has errors
$has_errors = false;

// default assume place submitted successfully
$result_message = '';

/*--------------------------------------------------
Post vars
--------------------------------------------------*/


$ads_type           = !empty($_POST['ads_type'    ]) ? $_POST['ads_type'    ] : '0';
$cat_id             = !empty($_POST['category_id' ]) ? $_POST['category_id' ] : '';
$ads_subject        = !empty($_POST['ads_subject' ]) ? $_POST['ads_subject' ] : '';
$ads_message        = !empty($_POST['ads_message' ]) ? $_POST['ads_message' ] : '';
$ads_company        = !empty($_POST['ads_company' ]) ? $_POST['ads_company' ] : '';
// $ads_maker_name     = !empty($_POST['ads_maker_name' ]) ? $_POST['ads_maker_name' ] : '';
// $ads_maker_email    = !empty($_POST['ads_maker_email' ]) ? $_POST['ads_maker_email' ] : '';

/*--------------------------------------------------
prepare vars
--------------------------------------------------*/
// trim
$ads_subject        = is_string($ads_subject)    ? trim($ads_subject)    : $ads_subject;
$ads_message        = is_string($ads_message)    ? trim($ads_message)    : $ads_message;
$ads_company        = is_string($ads_company)    ? trim($ads_company)    : $ads_company;



// check cat id selection
if(empty($cat_id)) {
	trigger_error("Invalid category selection", E_USER_ERROR);
	die();
}



/*--------------------------------------------------
Submit routine
--------------------------------------------------*/
// check if this page is refreshed/reloaded
// if $_SESSION['submit_token'] and submitted $_POST['submit_token'] match
// it means that the page has not been reloaded,
// process insert, then unset $_SESSION['submit_token'],
// so that if user reloads this page, it doesn't match, so it's not inserted
$post_token    = !empty($_POST['submit_token'  ]) ? $_POST['submit_token'   ] : 'aaa';
$session_token = isset($_SESSION['submit_token']) ? $_SESSION['submit_token'] : '';
$cookie_token  = isset($_COOKIE['submit_token' ]) ? $_COOKIE['submit_token' ] : '';


if($post_token == $session_token || $post_token == $cookie_token) {
	try {
		$conn->beginTransaction();

		/*--------------------------------------------------
		Insert listing
		--------------------------------------------------*/
		$query = "INSERT INTO ads(
			ads_subject,
			ads_message,
			ads_type,
			ads_category,
			ads_visitor_cnt,
			ads_top,
			ads_company,
			ads_maker_id
		)
		VALUES(
			:subject,
			:message,
			:type,
			:cat_id,
			0,
			0,
			:company,
			:maker_id
		)";
 
	
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':subject'         , $ads_subject);
		$stmt->bindValue(':message'         , $ads_message);
		$stmt->bindValue('type'             , $ads_type);
		$stmt->bindValue(':cat_id'          , $cat_id);
		$stmt->bindValue(':company'         , $ads_company);
		$stmt->bindValue(':maker_id'        , $userid);
	
		$stmt->execute();

		$ads_id = $conn->lastInsertId();

		/*--------------------------------------------------
		Commit
		--------------------------------------------------*/
		$conn->commit();
		$has_errors = false;
		$txt_main_title = '';//$txt_main_title_success;
		$result_message = '';//$txt_checkout_msg;
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$has_errors = true;
		$txt_main_title = '';//$txt_main_title_error;
		$result_message = $e->getMessage();

		echo $result_message;
		die('<br>Listing was not created');
	}

	// empty session submit token
	unset($_SESSION['submit_token']);
	
	// get cat slug for redirect after inserted
	$query = "SELECT cat_slug FROM cats where id = :cat_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id'         , $cat_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$txt_cat_slug = $row['cat_slug'];
}

/*--------------------------------------------------
canonical
--------------------------------------------------*/
$canonical = $baseurl . '/user/process-post-ads';
