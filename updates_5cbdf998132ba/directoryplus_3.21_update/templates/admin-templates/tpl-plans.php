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

			<div class="mb-3">
				<strong><?= $txt_action ?>:</strong><br>
				<button class="btn btn-light btn-sm"
					data-loc-type="city"
					data-modal-title="<?= $txt_create ?>"
					data-toggle="modal"
					data-target="#create-plan-modal"
					><?= $txt_create ?></button>
			</div>

			<?php
			if(!empty($plans_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/plans-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th><?= $txt_plan_type ?></th>
							<th><?= $txt_plan_name ?></th>
							<th><?= $txt_price ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($plans_arr as $k => $v) {
							$this_plan_id     = $v['plan_id'];
							$this_plan_type   = $v['plan_type'];
							$this_plan_name   = $v['plan_name'];
							$this_plan_price  = $v['plan_price'];
							$this_plan_status = $v['plan_status'];
							?>
							<tr id="plan-<?= $this_plan_id ?>">
								<td><?= $this_plan_type ?></td>
								<td><?= $this_plan_name ?></td>
								<td><?= $this_plan_price ?></td>
								<td class="text-nowrap">
									<?php
									if($this_plan_status == 0) {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-light btn-sm toggle-plan-status"
												id="toggle-plan-<?= $this_plan_id ?>"
												data-plan-id="<?= $this_plan_id ?>"
												data-plan-status="inactive">
												<i class="fas fa-toggle-off" aria-hidden="true"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-success btn-sm toggle-plan-status"
												id="toggle-plan-<?= $this_plan_id ?>"
												data-plan-id="<?= $this_plan_id ?>"
												data-plan-status="active">
												<i class="fas fa-toggle-on" aria-hidden="true"></i>
											</button>
										</span>
										<?php
									}
									?>
									<span id="edit-plan-<?= $this_plan_id ?>" data-toggle="tooltip" title="<?= $txt_edit_plan ?>">
										<button class="btn btn-light btn-sm edit-plan-btn"
											data-plan-id="<?= $this_plan_id ?>"
											data-toggle="modal"
											data-target="#edit-plan-modal">
											<i class="fas fa-pencil-alt"></i>
										</button>
									</span>

									<span data-toggle="tooltip" title="<?= $txt_remove_plan ?>">
										<button class="btn btn-light btn-sm remove-plan"
											data-plan-id="<?= $this_plan_id ?>">
											<i class="far fa-trash-alt" aria-hidden="true"></i>
										</button>
									</span>
								</td>
							</tr>
						<?php
						}
					?>
					</table>
				</div>

				<nav>
					<ul class="pagination flex-wrap">
						<?php
						if($total_rows > 0) {
							include_once(__DIR__ . '/../../inc/pagination.php');
						}
						?>
					</ul>
				</nav>
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

<!-- Modal edit plan -->
<div class="modal fade" id="edit-plan-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_edit_plan ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button id="edit-plan-submit" class="btn btn-primary btn-sm"><?= $txt_save ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal create plan -->
<div class="modal fade" id="create-plan-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_create ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-create-plan" method="post">
					<div class="form-group">
						<label class="label" for="plan_name"><strong><?= $txt_plan_name ?></strong></label>
						<input type="text" id="plan_name" name="plan_name" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="label" for="plan_type"><?= $txt_plan_type ?></label>
						<select id="plan_type" name="plan_type" class="form-control">
							<option value="free"><?= $txt_free ?></option>
							<option value="free_feat"><?= $txt_free_featured ?></option>
							<option value="one_time"><?= $txt_one_time ?></option>
							<option value="one_time_feat"><?= $txt_one_time_f ?></option>
							<option value="monthly"><?= $txt_monthly ?></option>
							<option value="monthly_feat"><?= $txt_monthly_f ?></option>
							<option value="annual"><?= $txt_annual ?></option>
							<option value="annual_feat"><?= $txt_annual_f ?></option>
						</select>
					</div>

					<div class="form-group">
						<label class="label" for="plan_period"><?= $txt_period ?></label>
						<input type="number" id="plan_period" name="plan_period" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="label" for="plan_order"><?= $txt_order ?></label>
						<input type="number" id="plan_order" name="plan_order" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="label" for="plan_price"><?= $txt_plan_price ?></label>
						<input type="number" id="plan_price" name="plan_price" class="form-control">
					</div>

					<div class="form-group">
						<label class="label" for="plan_features"><?= $txt_features ?></label>
						<textarea id="plan_features" name="plan_features" class="form-control" rows="5"></textarea>
					</div>

					<div class=""><?= $txt_plan_status ?></div>
					<div class="form-check form-check-inline">
						<input type="radio" id="plan_status1" class="form-check-input" name="plan_status" value="1">
						<label class="form-check-label" for="plan_status1"><?= $txt_enabled ?></label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="plan_status2" class="form-check-input" name="plan_status" value="0">
						<label class="form-check-label" for="plan_status2"><?= $txt_disabled ?></label>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button id="create-plan-submit" class="btn btn-primary btn-sm"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// show edit plan modal
	$('#edit-plan-modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var plan_id = button.data('plan-id');
		var modal = $(this);
		$('#edit-plan-submit').show();

		// ajax
		var post_url = '<?= $baseurl ?>' + '/admin/get-plan.php';

		$.post(post_url, { plan_id: plan_id },
			function(data) {
				modal.find('.modal-body').html(data);
			}
		);
	});

	// submit edit plan modal
    $('#edit-plan-submit').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-edit-plan.php';

		$.post(post_url, {
			params: $('form.form-edit-plan').serialize(),
			},
			function(data) {
				$('#edit-plan-submit').hide();
				$('#edit-plan-modal .modal-body').html(data);
			}
		);
    });

	// submit create plan modal
    $('#create-plan-submit').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-create-plan.php';

		$.post(post_url, {
			params: $('form.form-create-plan').serialize(),
			},
			function(data) {
				location.reload(true);
			}
		);
    });

	// toggle plan status
	$('.toggle-plan-status').on('click', function(e) {
		e.preventDefault();
		var plan_id     = $(this).data('plan-id');
		var post_url    = '<?= $baseurl ?>' + '/admin/process-toggle-plan-status.php';
		var plan_status = $(this).data('plan-status');

		$.post(post_url, {
			plan_id    : plan_id,
			plan_status: plan_status
			},
			function(data) {
				if(data == '<?= $txt_enabled ?>') {
					$('#toggle-plan-' + plan_id).removeClass('btn-light');
					$('#toggle-plan-' + plan_id).addClass('btn-success');
					$('#toggle-plan-' + plan_id + ' i').removeClass('fa-toggle-off');
					$('#toggle-plan-' + plan_id + ' i').addClass('fa-toggle-on');
					$('#toggle-plan-' + plan_id).data('plan-status', 'approved');
				}
				if(data == '<?= $txt_disabled ?>') {
					$('#toggle-plan-' + plan_id).removeClass('btn-success');
					$('#toggle-plan-' + plan_id).addClass('btn-light');
					$('#toggle-plan-' + plan_id + ' i').removeClass('fa-toggle-on');
					$('#toggle-plan-' + plan_id + ' i').addClass('fa-toggle-off');
					$('#toggle-plan-' + plan_id).data('plan-status', 'inactive');
				}
			}
		);
	});

	// remove plan
	$('.remove-plan').on('click', function(e){
		e.preventDefault();
		var plan_id = $(this).data('plan-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-plan.php';
		var wrapper = '#plan-' + plan_id;
		$.post(post_url, {
			plan_id: plan_id
			},
			function(data) {
				location.reload(true);
			}
		);
	});
}());
</script>

</body>
</html>