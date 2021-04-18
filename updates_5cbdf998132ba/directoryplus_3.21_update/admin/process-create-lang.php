<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$lang = !empty($_POST['lang']) ? $_POST['lang'] : '';

// insert into db
$query = "INSERT INTO language(
			lang,
			section,
			template,
			var_name,
			translated)
		SELECT '$lang',
			section,
			template,
			var_name,
			translated FROM language WHERE lang = 'en'";

$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $lang);
$stmt->execute();

echo 'ok';