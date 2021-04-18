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

			<div class="d-flex">
				<div class="flex-grow-1"><span><?= $txt_total_rows ?>: <strong><?= $total_rows ?></strong></span></div>
				<div><a href="<?= $baseurl ?>/admin/coupons-trash"><?= $txt_trash ?></a></div>
			</div>

			<?php
			if(!empty($coupons_arr)) {
				?>
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
									<span data-toggle="tooltip">
										<button class="btn btn-light btn-sm remove-coupon"
											data-coupon-id="<?= $coupon_id ?>">
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

<!-- admin footer -->
<?php require_once(__DIR__ . '/admin-footer.php') ?>

<!-- javascript -->
<script>
(function(){
	// remove coupon
	$('.remove-coupon').on('click', function(e){
		e.preventDefault();

		var coupon_id = $(this).data('coupon-id');
		var post_url = '<?= $baseurl ?>' + '/admin/process-remove-coupon.php';
		var wrapper = '#coupon-' + coupon_id;

		$.post(post_url, {
			coupon_id: coupon_id
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