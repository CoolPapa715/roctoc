<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once(__DIR__ . '/../head.php') ?>
<?php require_once('user-head.php') ?>
</head>
<body class="tpl-user-forgot-password">
<?php require_once(__DIR__ . '/../header.php') ?>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header">
					<h4><strong><?= $txt_forgot_pass ?></strong></h4>
				</div>

				<div class="card-body">
					<?php
					if(!$form_submitted) {
						?>
						<form method="post" action="<?= $baseurl ?>/user/forgot-password">
							<div class="form-group">
								<label for="email"><?= $txt_email ?></label>
								<input type="text" id="email" class="form-control" name="email" aria-describedby="emailHelp">
								<small id="emailHelp" class="form-text text-muted"><?= $txt_enter_email ?></small>
							</div>

							<div class="form-group">
								<input type="submit" class="btn btn-primary btn-block" name="submit">
							</div>
						</form>
						<?php
					}

					if($request_sent) {
						?>
						<?= $txt_request_sent ?>

						<p><a href="<?= $baseurl ?>/user/sign-in"><?= $txt_signin ?></a></p>
						<?php
					}

					if($mailer_problem) {
						?>
						<div class="alert alert-danger" role="alert">
							<?= $txt_mailer_problem ?>

							<p><a href="<?= $baseurl ?>/user/forgot-password"><?= $txt_try_again ?></a></p>
						</div>
						<?php
					}

					if(($invalid_email || !$user_exists) && $form_submitted) {
						?>
						<div class="alert alert-danger" role="alert">
							<?= $txt_invalid_email ?>

							<p><a href="<?= $baseurl ?>/user/forgot-password"><?= $txt_try_again ?></a></p>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>

</body>
</html>