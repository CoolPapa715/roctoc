<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// set status to -2
$query = "UPDATE coupons SET coupon_status = -2 WHERE coupon_status = -1";
$stmt = $conn->prepare($query);
$stmt->execute();
