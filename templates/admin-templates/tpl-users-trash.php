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
				if(!empty($users_arr)) {
					?>
					<div class="d-flex">
						<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
						<div><a href="#" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
					</div>

					<div class="table-responsive">
						<table class="table admin-table">
							<tr>
								<th><?= $txt_id ?></th>
								<th><?= $txt_name ?></th>
								<th><?= $txt_email ?></th>
								<th><?= $txt_created ?></th>
								<th><?= $txt_action ?></th>
							</tr>
							<?php
							foreach($users_arr as $k => $v) {
								$this_user_id              = $v['id'];
								$this_user_name            = $v['name'];
								$this_user_email           = $v['email'];
								$this_user_created         = $v['created'];
								$this_user_status          = $v['status'];
								?>
								<tr id="user-<?= $this_user_id ?>">
									<td><?= $this_user_id ?></td>
									<td><a href="<?= $baseurl ?>/profile/<?= $this_user_id ?>" target="_blank" class="text-dark"><?= $this_user_name ?></a></td>
									<td><?= $this_user_email ?></td>
									<td><?= $this_user_created ?></td>
									<td>
										<!-- restore btn -->
										<span data-toggle="tooltip" title="<?= $txt_tooltip_restore ?>">
											<button class="btn btn-light btn-sm restore-user"
												data-user-id="<?= $this_user_id ?>">
												<i class="fas fa-undo-alt"></i>
											</button>
										</span>

										<!-- remove btn -->
										<span data-toggle="tooltip" title="<?= $txt_tooltip_remove_user ?>">
											<button class="btn btn-light btn-sm remove-user"
												data-toggle="modal"
												data-target="#remove-user-modal"
												data-user-id="<?= $this_user_id ?>">
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

<!-- Modal Remove User -->
<div class="modal fade" id="remove-user-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_modal_remove_title ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-user-confirm" data-dismiss="modal"><?= $txt_remove ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Empty Trash -->
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
				<?= $txt_remove_perm_sure_all ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm empty-trash-confirm"><?= $txt_empty ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// restore user
    $('.restore-user').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-user.php';
		var restore_user_id = $(this).data('user-id');

		$.post(post_url, { restore_user_id: restore_user_id },
			function(data) {
				location.reload(true);
			}
		);
    });

	// when remove user modal pops up
	$('#remove-user-modal').on('show.bs.modal', function(e) {
		var button = $(e.relatedTarget);
		var remove_user_id = button.data('user-id');
		var modal = $(this);

		modal.find('.remove-user-confirm').attr('data-user-id', remove_user_id);
	});

	// remove user permanently
	$('.remove-user-confirm').on('click', function(e) {
		e.preventDefault();
		var remove_user_id = $(this).data('user-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-user-perm.php';
		var tr = '#user-' + remove_user_id;

		$.post(post_url, {
			remove_user_id: remove_user_id
			},
			function(data) {
				$(tr).hide();
			}
		);
	});

	// empty trash button in modal clicked
    $('.empty-trash-confirm').on('click', function(event){
		event.preventDefault();
		var modal = $('#empty-trash-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-users.php';

		$.post(post_url, {
			from_check: 'admin-users-trash'
			},
			function(data) {
				modal.find('.modal-body').empty();
				modal.find('.modal-body').text("<?= $txt_ok ?>").fadeIn();
				location.reload(true);
			}
		);
    });
}());
</script>

</body>
</html>