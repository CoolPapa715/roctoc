<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

/*
This file is requested by the js-create-listing.php and js-edit-listing.php files when the category is changed
*/

$cat_id   = $_POST['cat_id'];
$place_id = $_POST['place_id'];

/*--------------------------------------------------
Global fields
--------------------------------------------------*/
// find global fields and get just the ids
// if called from edit place page, get field values too
$query = "SELECT f.*
			FROM custom_fields f
			LEFT JOIN rel_cat_custom_fields r
				ON f.field_id = r.field_id
			WHERE r.rel_id IS NULL AND field_status = 1
			ORDER BY f.field_order";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();

$custom_fields_ids = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$field_id = !empty($row['field_id']) ? $row['field_id'] : 0;
	$custom_fields_ids[] = $field_id;
}

/*--------------------------------------------------
Category fields
--------------------------------------------------*/
$custom_fields = array();
$query = "SELECT f.*,
	tr.field_name AS tr_field_name, tr.tooltip AS tr_tooltip, tr.values_list AS tr_values_list
	FROM rel_cat_custom_fields r
	LEFT JOIN custom_fields f ON r.field_id = f.field_id
	LEFT JOIN translation_cf tr ON f.field_id = tr.field_id AND tr.lang = :html_lang
	WHERE r.cat_id = :cat_id AND field_status = 1
	ORDER BY f.field_order";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->bindValue(':html_lang', $html_lang);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$this_field_id    = !empty($row['field_id'   ]) ? $row['field_id'   ] : '';
	$this_field_name  = !empty($row['field_name' ]) ? $row['field_name' ] : '';
	$this_field_type  = !empty($row['field_type' ]) ? $row['field_type' ] : '';
	$this_values_list = !empty($row['values_list']) ? $row['values_list'] : '';
	$this_tooltip     = !empty($row['tooltip'    ]) ? $row['tooltip'    ] : '';
	$this_icon        = !empty($row['icon'       ]) ? $row['icon'       ] : '';
	$this_required    = !empty($row['required'   ]) ? 'required'          : '';
	$this_searchable  = !empty($row['searchable' ]) ? $row['searchable' ] : 0;
	$this_field_order = !empty($row['field_order']) ? $row['field_order'] : 0;

	// field translation values
	$this_tr_field_name  = !empty($row['tr_field_name' ]) ? $row['tr_field_name' ] : $this_field_name;
	$this_tr_tooltip     = !empty($row['tr_tooltip'    ]) ? $row['tr_tooltip'    ] : $this_tooltip;
	$this_tr_values_list = !empty($row['tr_values_list']) ? $row['tr_values_list'] : $this_values_list;

	$custom_fields[] = array(
		'field_id'       => $this_field_id,
		'field_name'     => $this_field_name,
		'tr_field_name'  => $this_tr_field_name,
		'field_type'     => $this_field_type,
		'values_list'    => $this_values_list,
		'tr_values_list' => $this_tr_values_list,
		'tooltip'        => $this_tooltip,
		'tr_tooltip'     => $this_tr_tooltip,
		'icon'           => $this_icon,
		'required'       => $this_required,
		'searchable'     => $this_searchable,
		'field_order'    => $this_field_order,
	);

	$custom_fields_ids[] = $this_field_id;
}

// fields ids hidden field
if(!empty($custom_fields_ids)) {
	$custom_fields_ids = implode(',', $custom_fields_ids);
}

else {
	$custom_fields_ids = '';
}
?>
<div id="cat-fields">
	<input type="hidden" name="custom_fields_ids" id="custom_fields_ids" value="<?= $custom_fields_ids ?>">

	<?php
	foreach($custom_fields as $v) {
		$field_id       = $v['field_id'      ];
		$field_name     = $v['field_name'    ];
		$field_type     = $v['field_type'    ];
		$values_list    = $v['values_list'   ];
		$tooltip        = $v['tooltip'       ];
		$icon           = $v['icon'          ];
		$required       = $v['required'      ];
		$searchable     = $v['searchable'    ];
		$tr_field_name  = $v['tr_field_name' ];
		$tr_values_list = $v['tr_values_list'];
		$tr_tooltip     = $v['tr_tooltip'    ];

		// build tooltip
		if(!empty($tr_tooltip)) {
			$tr_tooltip = "<a class='the-tooltip' data-toggle='tooltip' data-placement='top' title='$tr_tooltip'><i class='far fa-question-circle'></i></a>";
		}

		// explode values
		$values_arr = array();

		if($field_type == 'radio' || $field_type == 'select' || $field_type == 'checkbox') {
			$values_arr = explode(';', $values_list);
			$tr_values_arr = explode(';', $tr_values_list);

			foreach($values_arr as $k2 => $v2) {
				if(empty($tr_values_arr[$k2])) {
					$tr_values_arr[$k2] = $values_arr[$k2];
				}
			}
		}

		if($field_type == 'radio') {
			?>
			<div class="form-group">
				<div><label><?= $tr_field_name ?> <?= $tr_tooltip ?></label></div>

				<?php
				$i = 1;
				foreach($values_arr as $k2 => $v2) {
					$v2 = e(trim($v2));
					?>
					<div class="form-check form-check-inline">
						<input type="radio" id="field_<?= $field_id ?>_<?= $i ?>" class="form-check-input" name="field_<?= $field_id ?>" value="<?= $v2 ?>" <?= $required ?>>
						<label for="field_<?= $field_id ?>_<?= $i ?>" class="font-weight-normal"><?= $tr_values_arr[$k2] ?></label>
					</div>
					<?php
					$i++;
				}
				?>
			</div>
			<?php
		}

		if($field_type == 'select') {
			?>
			<div class="form-group">
				<div><label for="field_<?= $field_id ?>"><?= $tr_field_name ?> <?= $tr_tooltip ?></label></div>
				<select id="field_<?= $field_id ?>" class="form-control" name="field_<?= $field_id ?>" <?= $required ?>>
				<?php
				foreach($values_arr as $k2 => $v2) {
					$v2 = e(trim($v2));
					?>
					<option value="<?= $v2 ?>"><?= isset($tr_values_arr[$k2]) ? $tr_values_arr[$k2] : $v2 ?></option>
					<?php
				}
				?>
				</select>
			</div>
			<?php
		}

		if($field_type == 'checkbox') {
			?>
			<div class="form-group">
				<div><label><?= $tr_field_name ?> <?= $tr_tooltip ?></label></div>

				<?php
				$i = 1;
				foreach($values_arr as $k2 => $v2) {
					$v2 = e(trim($v2));
					?>
					<div class="form-check form-check-inline">
						<input type="checkbox" id="field_<?= $field_id ?>_<?= $i ?>" class="form-check-input" name="field_<?= $field_id ?>[]" value="<?= $v2 ?>" <?= $required ?>>
						<label for="field_<?= $field_id ?>_<?= $i ?>" class="font-weight-normal"><?= $tr_values_arr[$k2] ?></label>
					</div>
					<?php
					$i++;
				}
				?>
			</div>
			<?php
		}

		if($field_type == 'text') {
			?>
			<div class="form-group">
				<div><label for="field_<?= $field_id ?>"><?= $tr_field_name ?> <?= $tr_tooltip ?></label></div>
				<input type="text" id="field_<?= $field_id ?>" class="form-control" name="field_<?= $field_id ?>" <?= $required ?>>
			</div>
			<?php
		}

		if($field_type == 'multiline') {
			?>
			<div class="form-group">
				<div><label for="field_<?= $field_id ?>"><?= $tr_field_name ?> <?= $tr_tooltip ?></label></div>
				<textarea id="field_<?= $field_id ?>" class="form-control" name="field_<?= $field_id ?>" <?= $required ?>></textarea>
			</div>
			<?php
		}

		if($field_type == 'url') {
			?>
			<div class="form-group">
				<div><label for="field_<?= $field_id ?>"><?= $tr_field_name ?> <?= $tr_tooltip ?></label></div>
				<input type="text" id="field_<?= $field_id ?>" class="form-control" name="field_<?= $field_id ?>" <?= $required ?>>
			</div>
			<?php
		}
	}
	?>
</div>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>