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
				<a href="<?= $baseurl ?>/admin/create-page" class="btn btn-light btn-sm create-cat-btn"><?= $txt_create_page ?></a>
			</div>

			<?php
			if(!empty($pages_arr)) {
				?>
				<div class="d-flex">
					<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
					<div><a href="<?= $baseurl ?>/admin/pages-trash"><?= $txt_trash ?></a></div>
				</div>

				<div class="table-responsive">
					<table class="table admin-table">
						<tr>
							<th><?= $txt_id ?></th>
							<th><?= $txt_title ?></th>
							<th><?= $txt_link ?></th>
							<th><?= $txt_action ?></th>
						</tr>

						<?php
						foreach($pages_arr as $k => $v) {
							$page_id    = $v['page_id'];
							$page_title = $v['page_title'];
							$page_link  = $v['page_link'];
							?>
							<tr id="page-<?= $page_id ?>">
								<td><?= $page_id ?></td>
								<td><?= $page_title ?></td>
								<td><a href="<?= $page_link ?>" target="_blank"><?= $txt_view ?></a></td>
								<td class="text-nowrap">
									<span data-toggle="tooltip" title="<?= $txt_edit_page ?>">
										<a href="<?= $baseurl ?>/admin/edit-page?id=<?= $page_id ?>" class="btn btn-light btn-sm edit-page-btn"
											data-id="<?= $page_id ?>">
											<i class="fas fa-pencil-alt"></i>
										</a>
									</span>

									<span data-toggle="tooltip"	title="<?= $txt_remove_page ?>">
										<button class="btn btn-light btn-sm remove-page"
											data-id="<?= $page_id ?>">
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

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// remove page
	$('.remove-page').on('click', function(e) {
		e.preventDefault();

		var page_id = $(this).data('id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-page.php';

		$.post(post_url, {
			page_id: page_id
			},
			function(data) {
				console.log(data);
				location.reload(true);
			}
		);
	});
}());
</script>

</body>
</html>