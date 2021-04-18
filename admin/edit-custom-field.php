<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');

$field_id = !empty($_GET['id']) ? $_GET['id'] : 0;

if(empty($field_id)) {
	throw new Exception('Field id cannot be empty');
}

// get custom field data
$query = "SELECT * FROM custom_fields WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$field_name     = !empty($row['field_name'    ]) ? $row['field_name'    ] : 'undefined';
$field_type     = !empty($row['field_type'    ]) ? $row['field_type'    ] : 'text';
$filter_display = !empty($row['filter_display']) ? $row['filter_display'] : 'text';
$values_list    = !empty($row['values_list'   ]) ? $row['values_list'   ] : '';
$tooltip        = !empty($row['tooltip'       ]) ? $row['tooltip'       ] : '';
$icon           = !empty($row['icon'          ]) ? $row['icon'          ] : '';
$field_order    = !empty($row['field_order'   ]) ? $row['field_order'   ] : 0;

$required    = $row['required'  ] == 1 ? 'checked' : '';
$searchable  = $row['searchable'] == 1 ? 'checked' : '';

// sanitize
$icon = e($icon);

// get categories with this custom field
$query = "SELECT cat_id FROM rel_cat_custom_fields WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();
$checked_cats = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$checked_cats[] = $row['cat_id'];
}

// get all categories
$cats_arr = array();
$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY plural_name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cur_loop_arr = array(
		'cat_id'      => $row['id'],
		'cat_name'    => $row['name'],
		'plural_name' => $row['plural_name'],
		'parent_id'   => $row['parent_id']
	);

	$cats_arr[] = $cur_loop_arr;

	// if is empty
}

// store total number of cats in a variable
$total_cats = count($cats_arr);

$cats_grouped_by_parent = group_cats_by_parent($cats_arr);

// custom field name/values translation
$custom_field_lang = array();

$query = "SELECT * FROM translation_cf WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_lang = $row['lang'];
	$this_field_name = $row['field_name'];
	$this_tooltip = $row['tooltip'];
	$this_value_list = $row['values_list'];

	$custom_field_lang[$this_lang] = array(
		'field_name' => $this_field_name,
		'tooltip' => $this_tooltip,
		'values_list' => $this_value_list,
	);
}