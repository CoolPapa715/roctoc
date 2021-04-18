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
			if(!empty($cats_arr)) {
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
							<th><?= $txt_parent_id ?></th>
							<th><?= $txt_order ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($cats_arr as $k => $v) {
							$cat_id        = $v['cat_id'];
							$cat_name      = $v['cat_name'];
							$cat_parent_id = $v['cat_parent_id'];
							$cat_order     = $v['cat_order'];
							?>
							<tr id="cat-<?= $cat_id ?>">
								<td><?= $cat_id ?></td>
								<td class="text-nowrap">
									<?= $cat_name ?>
								</td>
								<td class="text-nowrap">
									<?= $cat_parent_id ?>
								</td>
								<td class="text-nowrap">
									<?= $cat_order ?>
								</td>
								<td class="text-nowrap">
									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_restore ?>">
										<button class="btn btn-light btn-sm restore-cat"
											data-cat-id="<?= $cat_id ?>">
											<i class="fas fa-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_remove ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-cat-modal"
											data-cat-id="<?= $cat_id ?>">
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

<!-- Remove Cat Modal -->
<div class="modal fade" id="remove-cat-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_remove ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-cat"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty Trash Modal -->
<div class="modal fade" id="empty-trash-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_remove ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_all_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm empty-trash-confirm"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// restore cat
    $('.restore-cat').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-cat.php';
		var cat_id = $(this).data('cat-id');

		$.post(post_url, { cat_id: cat_id },
			function(data) {
				location.reload(true);
			}
		);
    });

	// when remove cat modal pops up
	$('#remove-cat-modal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget);
		var cat_id = button.data('cat-id');
		var modal = $(this);

		modal.find('.remove-cat').attr('data-cat-id', cat_id);
	});

	// remove cat button in modal clicked
    $('.remove-cat').on('click', function(e){
		e.preventDefault();
		var modal = $('#remove-cat-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-cat-perm.php';
		var clicked_button = $(this);
		var cat_id = clicked_button.data('cat-id');

		$.post(post_url, { cat_id: cat_id },
			function(data) {
				console.log(data);
				location.reload(true);
			}
		);
    });

	// after removing and clicking the close button on the modal, reload
	$('#remove-cat-modal').on('hide.bs.modal', function() {
		location.reload(true);
	});

	// when empty trash modal pops up
	$('#empty-trash-modal').on('show.bs.modal', function() {
		// do nothing for now
	});

	// empty all button in modal clicked
    $('.empty-trash-confirm').on('click', function(event){
		event.preventDefault();
		var modal = $('#empty-trash-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-cats.php';
		var clicked_button = $(this);

		$.post(post_url, {},
			function(data) {
				console.log(data);
				location.reload(true);
			}
		);
    });

	// after emptying all and clicking the close button on the modal, reload
	$('#empty-trash-modal').on('hide.bs.modal', function() {
		location.reload(true);
	});
}());
</script>

</body>
</html>