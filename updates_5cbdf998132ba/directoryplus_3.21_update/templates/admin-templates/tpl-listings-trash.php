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
				<strong><?= $txt_sort ?>:</strong><br>
				<a href="<?= $baseurl ?>/admin/listings-trash?sort=name" class="btn btn-light btn-sm"><?= $txt_by_name ?></a>
				<a href="<?= $baseurl ?>/admin/listings-trash" class="btn btn-light btn-sm"><?= $txt_by_date ?></a>
			</div>

			<?php
			if($total_rows > 0) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
					<div><a href="#" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table table-striped">
						<tr>
							<th><?= $txt_id ?></th>
							<th><?= $txt_place_name ?></th>
							<th><?= $txt_city ?></th>
							<th><?= $txt_date ?></th>
							<th><?= $txt_action ?></th>
						</tr>
						<?php
						foreach($places_arr as $k => $v) {
							$place_id       = $v['place_id'];
							$place_name     = $v['place_name'];
							$place_slug     = $v['place_slug'];
							$link_url       = $v['link_url'];
							$city_name      = $v['city_name'];
							$city_slug      = $v['city_slug'];
							$state_abbr     = $v['state_abbr'];
							$cat_name       = $v['cat_name'];
							$date_formatted = $v['date_formatted'];
							$feat_home      = $v['feat_home'];
							$status         = $v['status'];
							$paid           = $v['paid'];
							$user_email     = $v['user_email'];
							$plan_name      = $v['plan_name'];
							?>
							<tr id="tr-place-id=<?= $place_id ?>">
								<td><?= $place_id ?></td>
								<td><a href="<?= $link_url ?>" target="_blank"><?= $place_name ?></a></td>
								<td class="text-nowrap">
									<?php echo (!empty($city_name)) ? "$city_name, $state_abbr" : '' ?>
								</td>
								<td class="text-nowrap"><?= $date_formatted ?></td>
								<td class="text-nowrap">
									<!-- expand btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_expand ?>">
										<button class="btn btn-light btn-sm expand-details"
											data-place-id="<?= $place_id ?>">
											<i class="fas fa-expand" aria-hidden="true"></i>
										</button>
									</span>

									<!-- restore btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_restore ?>">
										<button class="btn btn-light btn-sm restore-place"
											data-place-id="<?= $place_id ?>">
											<i class="fas fa-undo-alt"></i>
										</button>
									</span>

									<!-- remove btn -->
									<span data-toggle="tooltip"	title="<?= $txt_tooltip_remove ?>">
										<button class="btn btn-light btn-sm"
											data-toggle="modal"
											data-target="#remove-place-modal"
											data-place-id="<?= $place_id ?>">
											<i class="far fa-trash-alt"></i>
										</button>
									</span>
								</td>
							</tr>
							<tr id="expand-details-<?= $place_id ?>" class="details-row">
								<td colspan="5" class="wrap">
									<div class="details-block">
										<div class="">
											<strong><?= $txt_listing_owner ?>:</strong>
											<?= $user_email ?>
										</div>

										<div class="">
											<strong><?= $txt_city ?>:</strong>
											<?php
											echo (!empty($city_name)) ? "$city_name, $state_abbr" : '';
											?>
										</div>

										<div class="">
											<strong><?= $txt_plan_name ?>:</strong>
											<?= $plan_name ?>
										</div>
										<div class="">
											<strong><?= $txt_category ?>:</strong>
											<?= $cat_name ?>
										</div>
									</div>
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

<!-- Remove Place Modal -->
<div class="modal fade" id="remove-place-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_remove_perm ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-place" data-dismiss="modal"><?= $txt_remove_perm ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty Trash Modal -->
<div class="modal fade" id="empty-trash-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_remove_perm ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure_all ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm empty-trash-confirm" data-dismiss="modal"><?= $txt_empty ?></button>
			</div>
		</div>
	</div>
</div>

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// hide all details
	$('.details-row').hide();

	// expand details
	$('.expand-details').on('click', function(e) {
		e.preventDefault();
		var place_id = $(this).data('place-id');
		$('#expand-details-' + place_id).toggle();

	});

	// restore listing
    $('.restore-place').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-listing.php';
		var place_id = $(this).data('place-id');

		$.post(post_url, { place_id: place_id },
			function(data) {
				location.reload(true);
			}
		);
    });

	// when remove place modal pops up
	$('#remove-place-modal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget);
		var place_id = button.data('place-id');
		var modal = $(this);

		modal.find('.remove-place').attr('data-place-id', place_id);
	});

	// remove place button in modal clicked
    $('.remove-place').on('click', function(e){
		e.preventDefault();
		var modal = $('#remove-place-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-listing-perm.php';
		var clicked_button = $(this);
		var place_id = clicked_button.data('place-id');

		$.post(post_url, { place_id: place_id },
			function(data) {
				console.log(data);
				modal.find('.modal-body').empty();
				modal.find('.modal-body').html(data).fadeIn();
				location.reload(true);
			}
		);
    });

	// after removing and clicking the close button on the modal, reload
	$('#remove-place-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});

	// when empty trash modal pops up
	$('#empty-trash-modal').on('show.bs.modal', function(event) {
		// do nothing for now
	});

	// empty all button in modal clicked
    $('.empty-trash-confirm').on('click', function(event){
		event.preventDefault();
		var modal = $('#empty-trash-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-listings.php';
		var clicked_button = $(this);

		$.post(post_url, {},
			function(data) {
				location.reload(true);
			}
		);
    });

	// after emptying all and clicking the close button on the modal, reload
	$('#empty-trash-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});
}());
</script>

</body>
</html>