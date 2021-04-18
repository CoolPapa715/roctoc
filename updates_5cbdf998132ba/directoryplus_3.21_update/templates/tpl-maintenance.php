<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<?php require_once('head.php') ?>
</head>
<body class="tpl-<?= $route[0] ?>">

<div class="container mt-5">
	<div class="text-center">
		<h4><?= $txt_html_title ?></h4>

		<p><?= $txt_message ?></p>

		<?php
		if($is_admin) {
			?>
			<p><a href="<?= $baseurl ?>/admin/home">Admin Area</a></p>
			<?php
		}
		?>
	</div>
</div>

</body>
</html>