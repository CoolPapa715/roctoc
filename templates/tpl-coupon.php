<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>
</head>
<body class="tpl-<?= $route[0] ?>">
<?php require_once('header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-3 mb-3">
			<img src="<?= $coupon_img_url ?>" width="240" class="rounded">
		</div>

		<div class="col-md-9 mb-3">
			<h2 class="mb-1"><?= $coupon_title ?></h2>
			<p class="mb-5">Apply Coupon:<a href="<?= $place_link ?>" class="text-primary"><!--<?= $place_name ?>--> Click And Get Deal Today</a></p>

			<div class="mb-5"><?= nl2p($coupon_description) ?></div>

			<!-- print -->
			<?php
			if($coupon_valid == 'valid') {
				?>
				<a href="<?= $coupon_img_url ?>" target="_blank"
				class="badge badge-pill badge-success">
					<i class="fas fa-print" aria-hidden="true"></i> <?= $txt_print ?>
				</a>

				<span class="badge badge-pill badge-light">
					<i class="far fa-clock" aria-hidden="true"></i> <?= $txt_expires ?>: <?= $coupon_expire ?>
				</span>
				<?php
			}

			else {
				?>
				<a href="<?= $coupon_img_url ?>" target="_blank"
				class="btn btn-default">
				<i class="fas fa-print" aria-hidden="true"></i> <?= $txt_expired ?></a>
				<?php
			}
			?>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>