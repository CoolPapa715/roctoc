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
			if(!empty($coupons_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
					<div><a href="#" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th><?= $txt_title ?></th>
							<th><?= $txt_created ?></th>
							<th><?= $txt_expire ?></th>
							<th><?= $txt_listing ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($coupons_arr as $k => $v) {
							$coupon_id          = $v['coupon_id'];
							$coupon_title       = $v['coupon_title'];
							$coupon_description = $v['coupon_description'];
							$coupon_place_id    = $v['coupon_place_id'];
							$coupon_created     = $v['coupon_created'];
							$coupon_expire      = $v['coupon_expire'];
							$coupon_img_url     = $v['coupon_img'];
							$coupon_link        = $v['coupon_link'];
							$place_name         = $v['place_name'];
							?>
							<tr id="coupon-<?= $coupon_id ?>">
								<td><a href="<?= $coupon_link ?>"><?= $coupon_title ?></a></td>
								<td><?= $coupon_created ?></td>
								<td><?= $coupon_expire ?></td>
								<td><?= $place_name ?></td>
								<td class="text-nowrap">
									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_restore ?>">
										<button class="btn btn-light btn-sm restore-coupon"
											data-coupon-id="<?= $coupon_id ?>">
											<i class="fas fa-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_remove ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-coupon-modal"
											data-coupon-id="<?= $coupon_id ?>">
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

<!-- Remove coupon modal -->
<div id="remove-coupon-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
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
				<button class="btn btn-primary btn-sm remove-coupon" data-dismiss="modal"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty trash modal -->
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
				<button class="btn btn-primary btn-sm empty-trash-confirm" data-dismiss="modal"><?= $txt_confirm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// restore coupon
    $('.restore-coupon').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-coupon.php';
		var coupon_id = $(this).data('coupon-id');

		$.post(post_url, { coupon_id: coupon_id },
			function(data) {
				location.reload(true);
			}
		);
    });

	// when remove coupon modal pops up
	$('#remove-coupon-modal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget);
		var coupon_id = button.data('coupon-id');
		var modal = $(this);

		modal.find('.remove-coupon').attr('data-coupon-id', coupon_id);
	});

	// remove coupon button in modal clicked
    $('.remove-coupon').on('click', function(e){
		e.preventDefault();
		var modal = $('#remove-coupon-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-coupon-perm.php';
		var clicked_button = $(this);
		var coupon_id = clicked_button.data('coupon-id');

		$.post(post_url, { coupon_id: coupon_id },
			function(data) {
				location.reload(true);
			}
		);
    });

	// empty all button in modal clicked
    $('.empty-trash-confirm').on('click', function(event){
		event.preventDefault();
		var modal = $('#empty-trash-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-coupons.php';
		var clicked_button = $(this);

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