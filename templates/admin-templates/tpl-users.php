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
				<form class="form-inline" action="<?= $baseurl ?>/admin/users" method="get">
					<input type="hidden" name="sort" value="<?= $sort ?>">
					<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" id="s" name="s">

					<button type="submit" class="btn btn-primary btn-sm mb-2"><?= $txt_search ?></button>
				</form>
			</div>

			<div class="mb-3">
				<p><strong><?= $txt_sort ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/users?sort=name" class="btn btn-light btn-sm"><?= $txt_by_name ?></a>
				<a href="<?= $baseurl ?>/admin/users?sort=email" class="btn btn-light btn-sm"><?= $txt_by_email ?></a>
				<a href="<?= $baseurl ?>/admin/users?sort=date" class="btn btn-light btn-sm"><?= $txt_by_date ?></a>
				</p>
			</div>

			<?php
			if(!empty($users_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/users-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?= $txt_id ?></th>
							<th><?= $txt_name ?></th>
							<th><?= $txt_email ?></th>
							<th><?= $txt_created ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($users_arr as $k => $v) {
							?>
							<tr id="user-<?= $v['id'] ?>">
								<td><?= $v['id'] ?></td>
								<td><a href="<?= $baseurl ?>/profile/<?= $v['id'] ?>" target="_blank" class="text-dark"><?= $v['name'] ?></a></td>
								<td><?= $v['email'] ?></td>
								<td><?= $v['created'] ?></td>
								<td>
									<?php
									if($v['status'] == 'pending') {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-light btn-sm toggle-user-status"
												id="toggle-user-<?= $v['id'] ?>"
												data-user-id="<?= $v['id'] ?>"
												data-user-status="pending">
												<i class="fas fa-toggle-off" aria-hidden="true"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_toggle_active ?>">
											<button class="btn btn-success btn-sm toggle-user-status"
												id="toggle-user-<?= $v['id'] ?>"
												data-user-id="<?= $v['id'] ?>"
												data-user-status="approved">
												<i class="fas fa-toggle-on" aria-hidden="true"></i>
											</button>
										</span>
										<?php
									}
									?>

									<span id="profile-pic-btn-<?= $v['id'] ?>" data-toggle="tooltip" title="<?= $txt_approve_profile_pic ?>">
										<button class="btn btn-light btn-sm pending-profile-pic"
											data-id="<?= $v['id'] ?>"
											data-toggle="modal"
											data-target="#profile-pic-modal"
											data-profile-id="<?= $v['id'] ?>"
											data-profile-pic-folder="<?= $v['prof_pic_folder'] ?>"
											data-profile-pic-filename="<?= $v['prof_pic_url'] ?>">
											<i class="fas fa-camera"></i>
										</button>
									</span>

									<span data-toggle="tooltip" title="<?= $txt_remove_user ?>">
										<a href="" class="btn btn-light btn-sm remove-user"
											data-user-id="<?= $v['id'] ?>">
											<i class="far fa-trash-alt"></i>
										</a>
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

<!-- modal profile picture -->
<div class="modal fade" id="profile-pic-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_approve_profile_pic ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="button" class="btn btn-light btn-sm pic-delete" data-dismiss="modal" data-delete-id><?= $txt_delete ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// when show profile-pic-modal
	$('#profile-pic-modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var profile_id = button.data('profile-id');
		var filename = button.data('profile-pic-filename');
		var modal = $(this);

		modal.find('.pic-approve').attr('data-approve-id', profile_id);
		modal.find('.pic-delete').attr('data-delete-id', profile_id);
		$('.modal-body').empty();
		$('.modal-body').prepend('<img src="' + filename + '" class="modal-profile-pic" />');
	});

	// delete profile pic
	$('.pic-delete').on('click', function(e) {
		e.preventDefault();
		var delete_id = $(this).attr('data-delete-id');
		var post_url = '<?= $baseurl ?>' + '/admin/moderate-profile-pic.php';
		var btn_wrapper = '#profile-pic-btn-' + delete_id;

		$.post(post_url, {
			delete_id: delete_id,
			operation: 'delete'
			},
			function(data) {
				$(btn_wrapper).empty();
			}
		);
	});

	// toggle user status
	$('.toggle-user-status').on('click', function(e) {
		e.preventDefault();
		var user_id     = $(this).data('user-id');
		var post_url    = '<?= $baseurl ?>' + '/admin/process-toggle-user-status.php';
		var user_status = $(this).data('user-status');
		console.log('before ajax: ' + user_status);

		$.post(post_url, {
			user_id    : user_id,
			user_status: user_status
			},
			function(data) {
				console.log('after ajax: ' + data);
				if(data == 'approved') {
					$('#toggle-user-' + user_id).removeClass('btn-light');
					$('#toggle-user-' + user_id).addClass('btn-success');
					$('#toggle-user-' + user_id + ' i').removeClass('fa-toggle-off');
					$('#toggle-user-' + user_id + ' i').addClass('fa-toggle-on');
					$('#toggle-user-' + user_id).data('user-status', 'approved');
				}
				if(data == 'pending') {
					$('#toggle-user-' + user_id).removeClass('btn-success');
					$('#toggle-user-' + user_id).addClass('btn-light');
					$('#toggle-user-' + user_id + ' i').removeClass('fa-toggle-on');
					$('#toggle-user-' + user_id + ' i').addClass('fa-toggle-off');
					$('#toggle-user-' + user_id).data('user-status', 'pending');
				}
			}
		);
	});

	// remove user
	$('.remove-user').on('click', function(e) {
		e.preventDefault();
		var user_id = $(this).data('user-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-user.php';
		var tr = '#user-' + user_id;

		$.post(post_url, {
			user_id: user_id
			},
			function(data) {
				$(tr).hide();
			}
		);
	});
}());
</script>

</body>
</html>