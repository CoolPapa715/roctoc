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
$stmt->bindValue(':template', 'edit-custom-field');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	${$row['var_name']} = $row['translated'];
}

// custom field details
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$field_id       = !empty($params['field_id'      ]) ? $params['field_id'      ] : '';
$field_name     = !empty($params['field_name'    ]) ? $params['field_name'    ] : '';
$field_type     = !empty($params['field_type'    ]) ? $params['field_type'    ] : '';
$filter_display = !empty($params['filter_display']) ? $params['filter_display'] : '';
$tooltip        = !empty($params['tooltip'       ]) ? $params['tooltip'       ] : '';
$icon           = !empty($params['icon'          ]) ? $params['icon'          ] : '';
$required       = !empty($params['required'      ]) ? $params['required'      ] : 0;
$searchable     = !empty($params['searchable'    ]) ? $params['searchable'    ] : 0;
$values_list    = !empty($params['values_list'   ]) ? $params['values_list'   ] : '';
$field_order    = !empty($params['field_order'   ]) ? $params['field_order'   ] : 0;
$categories     = !empty($params['cats'          ]) ? $params['cats'          ] : array();

// sanitize
$icon = htmlspecialchars_decode($icon);

// trim
$field_name     = trim($field_name);
$field_type     = trim($field_type);
$filter_display = trim($filter_display);
$tooltip        = trim($tooltip);
$icon           = trim($icon);

// convert to integers
$field_id    = intval($field_id);
$required    = intval($required);
$searchable  = intval($searchable);
$field_order = intval($field_order);

// allowed field types
$allowed_types = array('radio', 'checkbox', 'select', 'text', 'multiline', 'url');

// allowed filter display types
$allowed_filter_displays = array('radio', 'checkbox', 'select', 'text', 'range_text', 'range_select', 'range_number', 'range_decimal');

// field types that ignore values_list
$ignore_values_list = array('text', 'multiline', 'url');

if(in_array($field_type, $ignore_values_list)) {
	$values_list = '';
}

// count total cats
$query = "SELECT COUNT(*) AS total_cats FROM cats WHERE cat_status = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_cats = $row['total_cats'];

// init result message
$result_message = '';

// check if field type submitted is allowed
if(!in_array($field_type, $allowed_types)) {
	$result_message .= '- Field type is not allowed<br>';
}

if(!in_array($filter_display, $allowed_filter_displays)) {
	$filter_display = 'select';
}

// check if field id is not integer
if(empty($field_id)) {
	$result_message .= '- Field id cannot be undefined and must be an integer<br>';
}

// check if field type submitted is allowed
if(!in_array($field_type, $allowed_types)) {
	$result_message .= '- Field type is not allowed<br>';
}

// check if this field is set to show on all cats
$is_global_field = 0;

if($total_cats == count($categories)) {
	$is_global_field = 1;
}

if(empty($result_message)) {
	try {
		$conn->beginTransaction();

		// update table 'custom_fields'
		$query = "UPDATE custom_fields SET
			field_name     = :field_name,
			field_type     = :field_type,
			filter_display = :filter_display,
			values_list    = :values_list,
			tooltip        = :tooltip,
			icon           = :icon,
			required       = :required,
			searchable     = :searchable,
			field_order    = :field_order
			WHERE field_id = :field_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':field_id'       , $field_id);
		$stmt->bindValue(':field_name'     , $field_name);
		$stmt->bindValue(':field_type'     , $field_type);
		$stmt->bindValue(':filter_display' , $filter_display);
		$stmt->bindValue(':values_list'    , $values_list);
		$stmt->bindValue(':tooltip'        , $tooltip);
		$stmt->bindValue(':icon'           , $icon);
		$stmt->bindValue(':required'       , $required);
		$stmt->bindValue(':searchable'     , $searchable);
		$stmt->bindValue(':field_order'    , $field_order);
		$stmt->execute();

		// update table 'rel_cat_custom_fields'
		// first delete all
		$query = "DELETE FROM rel_cat_custom_fields WHERE field_id = :field_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':field_id', $field_id);
		$stmt->execute();

		if(!empty($categories)) {
			// now reinsert
			// only if it's not global field
			if(!$is_global_field) {
				$query = "INSERT INTO rel_cat_custom_fields(cat_id, field_id) VALUES";

				foreach($categories as $k => $v) {
					$v = intval($v);

					if($k == 0) {
						$query .= "( $v, $field_id)";
					}

					else {
						$query .= ",( $v, $field_id)";
					}
				}

				$stmt = $conn->prepare($query);
				$stmt->bindValue(':field_id', $field_id);
				$stmt->execute();
			}
		}

		$conn->commit();
		$result_message = $txt_field_updated;
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message = $e->getMessage();
	}
}

/*--------------------------------------------------
Custom fields translations
--------------------------------------------------*/

// delete previous values
$query = "DELETE FROM translation_cf WHERE field_id = :field_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':field_id', $field_id);
$stmt->execute();


/*

[custom_field_lang] => Array(
		[en] => Fuel Type
		[es] => Combustível)

[values_list_lang] => Array(
		[en] => Gasoline;Diesel;Electric;Hybrid;Other
		[es] => gasolina;Diesel;Elétrico;Híbrido;Outro)
*/

$custom_field_lang = !empty($params['custom_field_lang']) ? $params['custom_field_lang'] : array();
$tooltip_lang      = !empty($params['tooltip_lang'     ]) ? $params['tooltip_lang'     ] : array();
$values_list_lang  = !empty($params['values_list_lang' ]) ? $params['values_list_lang' ] : array();

// process submitted values
if(!empty($cfg_languages) && is_array($cfg_languages)) {
	foreach($cfg_languages as $v) {
		$query = "INSERT INTO translation_cf(lang, field_id, field_name, tooltip, values_list) VALUES (:lang, :field_id, :field_name, :tooltip, :values_list)";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lang', $v);
		$stmt->bindValue(':field_id', $field_id);
		$stmt->bindValue(':field_name', $custom_field_lang[$v]);
		$stmt->bindValue(':tooltip', $tooltip_lang[$v]);
		$stmt->bindValue(':values_list', $values_list_lang[$v]);
		$stmt->execute();
	}
}

echo $result_message;