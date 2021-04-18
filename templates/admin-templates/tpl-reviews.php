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
				<a href="<?= $baseurl ?>/admin/reviews?sort=pending" class="btn btn-light btn-sm"><?= $txt_pending ?></a>
			</div>

			<div class="d-flex">
				<div class="flex-grow-1"><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></div>
				<div class=""><a href="<?= $baseurl ?>/admin/reviews-trash"><?= $txt_trash ?></a></div>
			</div>

			<?php
			if($total_rows > 0) {
				?>
				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th><?= $txt_id ?></th>
							<th><?= $txt_date ?></th>
							<th><?= $txt_user ?></th>
							<th><?= $txt_place_name ?></th>
							<th><?= $txt_action ?></th>
						</tr>

						<?php
						foreach($reviews_arr as $k => $v) {
							?>
							<tr id="tr-review-id-<?= $v['review_id'] ?>">
								<td class="text-nowrap"><?= $v['review_id'] ?></td>
								<td class="text-nowrap"><?= $v['pubdate'] ?></td>
								<td class="text-nowrap"><?= $v['author_name'] ?></td>
								<td><a href="<?= $v['link_url'] ?>" target="_blank"><?= $v['place_name'] ?></a></td>
								<td class="text-nowrap">
									<?php
									if($v['status'] == 'pending') {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_tooltip_toggle_approved ?>">
											<button class="btn btn-light btn-sm approve-review"
												id="status-review-<?= $v['review_id'] ?>"
												data-review-id="<?= $v['review_id'] ?>"
												data-status="pending">
												<i class="fas fa-toggle-off"></i>
											</button>
										</span>
										<?php
									}
									else {
										?>
										<span data-toggle="tooltip"	title="<?= $txt_tooltip_toggle_approved ?>">
											<button class="btn btn-success btn-sm approve-review"
												id="status-review-<?= $v['review_id'] ?>"
												data-review-id="<?= $v['review_id'] ?>"
												data-status="approved">
												<i class="fas fa-toggle-on"></i>
											</button>
										</span>
										<?php
									}
									?>

									<span data-toggle="tooltip" title="<?= $txt_tooltip_expand_review ?>">
										<button class="btn btn-light btn-sm expand-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="fas fa-expand"></i>
										</button>
									</span>

									<span data-toggle="tooltip" title="<?= $txt_tooltip_remove_review ?>">
										<button class="btn btn-light btn-sm remove-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="far fa-trash-alt"></i>
										</button>
									</span>
								</td>
							</tr>
							<tr id="expand-review-<?= $v['review_id'] ?>" class="review-text">
								<td colspan="5" class="wrap">
									<div class="review-text-wrapper"><?= $v['text'] ?></div>
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

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// hide all reviews' texts
	$('.review-text').hide();

	// expand review
	$('.expand-review').on('click', function(e) {
		e.preventDefault();
		var review_id = $(this).data('review-id');
		$('#expand-review-' + review_id).toggle();

	});

	// remove review
	$('.remove-review').on('click', function(e) {
		e.preventDefault();
		var review_id = $(this).data('review-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-review.php';
		$.post(post_url, {
			review_id: review_id
			},
			function(data) {
				location.reload(true);
			}
		);
	});

	// toggle review status
	$('.approve-review').on('click', function(e) {
		e.preventDefault();
		var review_id = $(this).data('review-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-approve-review.php';
		var status = $(this).data('status');

		$.post(post_url, {
			review_id: review_id,
			status: status
			},
			function(data) {
				if(data == 'approved') {
					$('#status-review-' + review_id).removeClass('btn-light');
					$('#status-review-' + review_id).addClass('btn-success');
					$('#status-review-' + review_id + ' i').removeClass('fa-toggle-off');
					$('#status-review-' + review_id + ' i').addClass('fa-toggle-on');
					$('#status-review-' + review_id).data('status', 'approved');
				}

				if(data == 'pending') {
					$('#status-review-' + review_id).removeClass('btn-success');
					$('#status-review-' + review_id).addClass('btn-light');
					$('#status-review-' + review_id + ' i').removeClass('fa-toggle-on');
					$('#status-review-' + review_id + ' i').addClass('fa-toggle-off');
					$('#status-review-' + review_id).data('status', 'pending');
				}
			}
		);
	});
}());
</script>

</body>
</html>