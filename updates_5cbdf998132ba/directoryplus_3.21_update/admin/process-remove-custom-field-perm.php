<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

// language
$query = "SELECT * FROM language WHERE lang = :lang AND section = :section AND template = :template";
$stmt = $conn->prepare($query);
$stmt->bindValue(':lang', $html_lang);
$stmt->bindValue(':section', 'admin');
$stmt->bindValue(':template', 'custom-fields');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = $row['translated'];
}

$field_id = $_POST['field_id'];

$query = "DELETE FROM custom_fields WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();

echo $txt_field_removed;