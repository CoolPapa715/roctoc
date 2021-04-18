<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<?php require_once(__DIR__ . '/admin-head.php') ?>
</head>
<body class="tpl-admin-<?= $route[1] ?>">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-lg-3 mb-5">
			<?php include_once('admin-menu.php') ?>
		</div>

		<div class="col-md-8 col-lg-9">
			<h2 class="mb-5"><?= $txt_main_title ?></h2>

			<?php
			if(!empty($custom_fields)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong class="total-rows"><?= $total_rows ?></strong></span></div>
					<div><a href="#" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th><?= $txt_id ?></th>
							<th><?= $txt_field_name ?></th>
							<th><?= $txt_field_type ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($custom_fields as $k => $v) {
							$field_id   = $v['field_id'];
							$field_name = $v['field_name'];
							$field_type = $v['field_type'];
							?>
							<tr id="field-<?= $field_id ?>">
								<td><?= $field_id ?></td>
								<td><?= $field_name ?></td>
								<td><?= $field_type ?></td>

								<td class="text-nowrap">
									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_restore ?>">
										<button class="btn btn-light btn-sm restore-field"
											data-custom-field-id="<?= $field_id ?>">
											<i class="fas fa-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_remove ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-custom-field-modal"
											data-custom-field-id="<?= $field_id ?>">
											<i class="far fa-trash-alt"></i>
										</button>
									</span>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			<?php
			}

			else {
				?>
				<div class="mt-5 mb-3">
					<?= $txt_no_results ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- Remove field modal -->
<div id="remove-custom-field-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_remove_custom_field ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm remove-field-cancel" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="button" class="btn btn-primary btn-sm remove-field-confirm"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty trash modal -->
<div class="modal fade" id="empty-trash-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_empty ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_all_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm empty-trash-confirm" data-dismiss="modal"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
// restore field
(function(){
    $('.restore-field').on('click', function(e){
		e.preventDefault();

		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-custom-field.php';
		var field_id = $(this).data('custom-field-id');
		var wrapper = '#field-' + field_id;

		$.post(post_url, { field_id: field_id },
			function(data) {
				// remove field row from table
				$(wrapper).empty();

				// subtract from total rows count
				var total = $('.total-rows').html();

				$('.total-rows').html(total-1);
			}
		);
    });
}());

// remove field
(function(){
	// on show remove modal
	$('#remove-custom-field-modal').on('show.bs.modal', function(event) {
		console.log('hhh');
		// show buttons
		$('.remove-field-confirm').show();
		$('.remove-field-cancel').empty().html('<?= $txt_cancel ?>');

		// show original body message
		$('#remove-custom-field-modal .modal-body').empty().html('<?= $txt_remove_sure ?>');

		var button = $(event.relatedTarget);
		var custom_field_id = button.data('custom-field-id');
		var modal = $(this);

		modal.find('.remove-field-confirm').attr('data-custom-field-id', custom_field_id);

		removeField(custom_field_id);
	});

	// confirm remove field
	function removeField(field_id) {
		// unbind previous click events
		$('.remove-field-confirm').unbind('click');

		// add new click event handler
		$('.remove-field-confirm').on('click', function(e){
			e.preventDefault();

			// var field_id = $(this).data('custom-field-id');
			var post_url = '<?= $baseurl ?>' + '/admin/process-remove-custom-field-perm.php';
			var wrapper = '#field-' + field_id;

			$.post(post_url, {
				field_id: field_id
				},
				function(data) {
					console.log(data);

					// show result message in modal
					$('#remove-custom-field-modal .modal-body').empty().html(data);

					// reconfig modal buttons
					$('.remove-field-confirm').hide();
					$('.remove-field-cancel').empty().html('<?= $txt_ok ?>');

					// remove field row from table
					$(wrapper).empty();

					// subtract from total rows count
					var total = $('.total-rows').html();

					$('.total-rows').html(total-1);
					console.log('total is ' + total);
				}
			);
		});
	}
}());

// empty trash
(function(){
    $('.empty-trash-confirm').on('click', function(event){
		event.preventDefault();

		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-custom-fields.php';

		$.post(post_url, {},
			function(data) {
				location.reload(true);
			}
		);
    });
}());
</script>

</body>
</html>