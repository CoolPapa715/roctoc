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
	<div class="container-fluid">
		<h2 class="mb-5"><?= $txt_html_title ?></h2>
	</div>

	<div class="row">
		<div class="col-md-8">
			<?php
			if(!empty($posts_arr)) {
				foreach($posts_arr as $k => $v) {
					?>
					<div id="post-<?= $v['page_id'] ?>" class="d-sm-flex list-item mb-4 mx-3 mx-sm-0">
						<a href="<?= $baseurl ?>/post/<?= $v['page_slug'] ?>">
							<div class="thumb rounded" image="background-image: url('<?= $v['page_thumb'] ?>');"></div>
						</a>

						<div class="flex-grow-1 p-3 pl-4">
							<div class="d-flex mb-3">
								<div class="flex-grow-1">
									<h4 class="mb-2"><a href="<?= $baseurl ?>/post/<?= $v['page_slug'] ?>"><?= $v['page_title'] ?></a></h4>
								</div>
							</div>

							<div class="card-text mb-3">
								<?= strip_tags($v['page_contents']) ?>
							</div>

							<hr>

							<div>
								<strong style="font-size: 75%;"><?= $v['page_date'] ?></strong>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<nav>
					<ul class="pagination flex-wrap">
						<?php
						if($total_rows > 0) {
							include_once(__DIR__ . '/../inc/pagination.php');
						}
						?>
					</ul>
				</nav>
				<?php
			}

			else {
				?>
				<div class="mt-5">
					<?= $txt_no_results ?>
				</div>
				<?php
			}
			?>
		</div>

		<div class="col-md-4">
			<h5 class="mb-3"><?= $txt_search_pages ?></h5>

			<form class="" method="get" action="<?= $baseurl ?>/posts">
				<div class="form-group">
					<input type="text" id="" class="form-control" name="term">
				</div>
				<button class="btn btn-primary btn-block"><?= $txt_search ?></button>
			</form>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>