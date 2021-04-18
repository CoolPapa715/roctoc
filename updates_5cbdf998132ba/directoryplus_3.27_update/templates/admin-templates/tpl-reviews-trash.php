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
			if($total_rows > 0) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
					<div><a href="#" class="empty-trash" data-toggle="modal" data-target="#empty-trash-modal"><?= $txt_empty ?></a></div>
				</div>

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
									<!-- expand review btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_expand_review ?>">
										<button class="btn btn-light btn-sm expand-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="fas fa-expand"></i>
										</button>
									</span>

									<!-- restore review btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_restore ?>">
										<button class="btn btn-light btn-sm restore-review"
											data-review-id="<?= $v['review_id'] ?>">
											<i class="fas fa-undo-alt"></i>
										</button>
									</span>

									<!-- remove review btn -->
									<span data-toggle="tooltip" title="<?= $txt_tooltip_remove_review ?>">
										<button class="btn btn-light btn-sm remove-review"
											data-toggle="modal"
											data-target="#remove-review-modal"
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

<!-- Modal Remove Review -->
<div class="modal fade" id="remove-review-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title1" class="modal-title"><?= $txt_remove_review ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= $txt_remove_perm_sure ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light btn-sm" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button class="btn btn-primary btn-sm remove-review-confirm" data-dismiss="modal"><?= $txt_remove_review ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Empty Trash Modal -->
<div class="modal fade" id="empty-trash-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modal-title2" class="modal-title"><?= $txt_empty_trash ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
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
	// hide all reviews' texts
	$('.review-text').hide();

	// expand review
	$('.expand-review').on('click', function(e) {
		e.preventDefault();
		var review_id = $(this).data('review-id');
		$('#expand-review-' + review_id).toggle();

	});

	// restore review
    $('.restore-review').on('click', function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl ?>' + '/admin/process-restore-review.php';
		var review_id = $(this).data('review-id');

		$.post(post_url, { review_id: review_id },
			function(data) {
				location.reload(true);
			}
		);
    });

	// when remove review modal pops up
	$('#remove-review-modal').on('show.bs.modal', function(e) {
		var button = $(e.relatedTarget);
		var review_id = button.data('review-id');
		var modal = $(this);

		modal.find('.remove-review-confirm').attr('data-review-id', review_id);
	});

	// remove review permanently
	$('.remove-review-confirm').on('click', function(e) {
		e.preventDefault();
		var review_id = $(this).data('review-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-review-perm.php';
		var tr = '#tr-review-id-' + review_id;
		var tr2 = '#expand-review-' + review_id;
		$.post(post_url, {
			review_id: review_id
			},
			function(data) {
				if(data) {
					$(tr).hide();
					$(tr2).hide();

					modal.find('#remove-review-modal .modal-body').empty();
					modal.find('#remove-review-modal .modal-body').html(data).fadeIn();
				}
			}
		);
	});

	// after removing and clicking the close button on the modal, reload
	$('#remove-review-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});

	// when empty trash modal pops up
	$('#empty-trash-modal').on('show.bs.modal', function(event) {
		// do nothing for now
	});

	// empty trash button in modal clicked
    $('.empty-trash-confirm').on('click', function(event){
		event.preventDefault();
		var modal = $('#empty-trash-modal');
		var post_url = '<?= $baseurl ?>' + '/admin/process-empty-trash-reviews.php';
		var clicked_button = $(this);

		$.post(post_url, {},
			function(data) {
				modal.find('#empty-trash-modal .modal-body').empty();
				modal.find('#empty-trash-modal .modal-body').html(data).fadeIn();
			}
		);
    });

	// after emptying trash and clicking the close button on the modal, reload
	$('#empty-trash-modal').on('hide.bs.modal', function (event) {
		location.reload(true);
	});

}());
</script>

</body>
</html>