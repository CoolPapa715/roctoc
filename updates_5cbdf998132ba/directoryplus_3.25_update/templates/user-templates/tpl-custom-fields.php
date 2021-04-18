<?php
/*
This file is included in the 'tpl-create-listing.php' and 'tpl-edit-listing.php' files

This file checks if there are global custom fields and generates the corresponding html code
It also inserts javascript which calls user-get-custom-fields when category field is changed
*/
?>
<div id="custom-fields" class="mt-5">
	<input type="hidden" name="custom_fields_ids" id="custom_fields_ids" value="<?= $custom_fields_ids; ?>">
	<?php
	// show header if
	if(!empty($custom_fields) || !empty($cat_fields)) {
		?>
		<p class="text-dark text-uppercase" style="font-weight:600"><?= $txt_other_info ?></p>
		<hr>
		<?php
	}

	/*--------------------------------------------------
	Global custom fields
	--------------------------------------------------*/
	if(!empty($custom_fields)) {
		?>
		<div id="global-fields">
			<?php
			foreach($custom_fields as $v) {
				if(!empty($v['tr_tooltip'])) {
					$v['tr_tooltip'] = '<a class="the-tooltip" data-toggle="tooltip" data-placement="top" title="' . $v['tr_tooltip'] . '"><i class="far fa-question-circle"></i></a>';
				}

				else {
					$v['tr_tooltip'] = '';
				}

				// explode values
				if($v['field_type'] == 'radio' || $v['field_type'] == 'select' || $v['field_type'] == 'checkbox') {
					$values_arr      = explode(';', $v['values_list']);
					$tr_values_arr   = explode(';', $v['tr_values_list']);
					$field_value_arr = explode(':::', $v['field_value']);
				}

				if($v['field_type'] == 'radio') {
					?>
					<div class="mb-3">
						<div><strong><em><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></em></strong></div>

						<?php
						$i = 1;
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// check if translated values exist
							if(empty($tr_values_arr[$k2])) {
								$tr_values_arr[$k2] = $values_arr[$k2];
							}

							// is checked
							$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
							?>
							<div class="form-check form-check-inline">
								<input type="radio" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>" value="<?= $v2 ?>" <?= $checked ?> <?= $v['required'] ?>>
								<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal" style="font-style: normal"><?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
							$i++;
						}
						?>
					</div>
					<?php
				}

				if($v['field_type'] == 'select') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<select id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>>
						<?php
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// check if translated values exist
							if(empty($tr_values_arr[$k2])) {
								$tr_values_arr[$k2] = $values_arr[$k2];
							}

							// is selected
							$selected = in_array($v2, $field_value_arr) ? 'selected' : '';
							?>
							<option value="<?= $v2 ?>" <?= $selected ?>><?= $tr_values_arr[$k2] ?>
							<?php
						}
						?>
						</select>
					</div>
					<?php
				}

				if($v['field_type'] == 'checkbox') {
					?>
					<div class="mb-3">
						<div><strong><em><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></em></strong></div>

						<?php
						$i = 1;

						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// check if translated values exist
							if(empty($tr_values_arr[$k2])) {
								$tr_values_arr[$k2] = $values_arr[$k2];
							}

							// is checked
							$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
							?>
							<div class="form-check form-check-inline">
								<input type="checkbox" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>" <?= $checked ?>>
								<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal" style="font-style: normal"> <?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
							$i++;
						}
						?>
					</div>
					<?php
				}

				if($v['field_type'] == 'text') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" value="<?= $v['field_value'] ?>" <?= $v['required'] ?> >
					</div>
					<?php
				}

				if($v['field_type'] == 'multiline') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<textarea class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>><?= $v['field_value'] ?></textarea>
					</div>
					<?php
				}

				if($v['field_type'] == 'url') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?> value="<?= $v['field_value'] ?>">
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}

	/*--------------------------------------------------
	Category Custom Fields
	--------------------------------------------------*/

	if(!empty($cat_fields)) {
		?>
		<div id="cat-fields">
			<?php
			foreach($cat_fields as $v) {
				$v['field_value'] = !empty($v['field_value']) ? $v['field_value'] : '';
				$v['required'] = $v['required'] == 1 ? 'required' : '';

				if(!empty($v['tr_tooltip'])) {
					$v['tr_tooltip'] = '<a class="the-tooltip" data-toggle="tooltip" data-placement="top" title="' . $v['tr_tooltip'] . '"><i class="far fa-question-circle"></i></a>';
				}

				else {
					$v['tr_tooltip'] = '';
				}

				// explode values
				if($v['field_type'] == 'radio' || $v['field_type'] == 'select' || $v['field_type'] == 'checkbox') {
					$values_arr      = explode(';', $v['values_list']);
					$tr_values_arr   = explode(';', $v['tr_values_list']);
					$field_value_arr = explode(':::', $v['field_value']);
				}

				if($v['field_type'] == 'radio') {
					?>
					<div class="mb-3">
						<div><strong><em><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></em></strong></div>

						<?php
						$i = 1;
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// check if translated values exist
							if(empty($tr_values_arr[$k2])) {
								$tr_values_arr[$k2] = $values_arr[$k2];
							}

							// is checked
							$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
							?>
							<div class="form-check form-check-inline">
								<input type="radio" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>" value="<?= $v2 ?>" <?= $checked ?> <?= $v['required'] ?>>
								<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal" style="font-style: normal"><?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
							$i++;
						}
						?>
					</div>
					<?php
				}

				if($v['field_type'] == 'select') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<select id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>>
						<?php
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// check if translated values exist
							if(empty($tr_values_arr[$k2])) {
								$tr_values_arr[$k2] = $values_arr[$k2];
							}

							// is selected
							$selected = in_array($v2, $field_value_arr) ? 'selected' : '';
							?>
							<option value="<?= $v2 ?>" <?= $selected ?>><?= $tr_values_arr[$k2] ?>
							<?php
						}
						?>
						</select>
					</div>
					<?php
				}

				if($v['field_type'] == 'checkbox') {
					?>
					<div class="mb-3">
						<div><strong><em><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></em></strong></div>

						<?php
						$i = 1;
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));

							// check if translated values exist
							if(empty($tr_values_arr[$k2])) {
								$tr_values_arr[$k2] = $values_arr[$k2];
							}

							// is checked
							$checked = in_array($v2, $field_value_arr) ? 'checked' : '';
							?>
							<div class="form-check form-check-inline">
								<input type="checkbox" id="field_<?= $v['field_id'] ?>_<?= $i ?>" class="form-check-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>" <?= $checked ?>>
								<label for="field_<?= $v['field_id'] ?>_<?= $i ?>" class="font-weight-normal" style="font-style: normal"><?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
							$i++;
						}
						?>
					</div>
					<?php
				}

				if($v['field_type'] == 'text') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?> value="<?= $v['field_value'] ?>">
					</div>
					<?php
				}

				if($v['field_type'] == 'multiline') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<textarea class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?>><?= $v['field_value'] ?></textarea>
					</div>
					<?php
				}

				if($v['field_type'] == 'url') {
					?>
					<div class="form-group">
						<label for="field_<?= $v['field_id'] ?>"><?= $v['tr_field_name'] ?> <?= $v['tr_tooltip'] ?></label>
						<input type="text" id="field_<?= $v['field_id'] ?>" class="form-control" name="field_<?= $v['field_id'] ?>" <?= $v['required'] ?> value="<?= $v['field_value'] ?>">
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
	?>
</div>