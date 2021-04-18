<style>
    .mt-5, .my-5 {
        margin-top: 5rem!important;
    }
</style>
</style>
<?php
if(file_exists(__DIR__ . "/header-child-$html_lang.php") && basename(__FILE__) != "header-child-$html_lang.php") {
	include_once("header-child-$html_lang.php");
	return;
}

if(file_exists(__DIR__ . '/header-child.php') && basename(__FILE__) != 'header-child.php') {
	include_once('header-child.php');
	return;
}

if($maintenance_mode == 1 && $is_admin) {
	?>
	<div class="maintenance-mode-note badge badge-warning" style="position:fixed;top:0;right:0;z-index:10000">Maintenance mode is on</div>
	<?php
}
?>
<!-- Preloader -->
<div class="preloader"></div>

<!-- Navbar -->
<nav id="header-nav" class="navbar navbar-expand-md fixed-top" style="z-index:2000;">
	<div class="container-fluid">
		<!-- Brand -->
		<a class="navbar-brand" href="<?= $baseurl ?>">
			<img class="logo" src="<?= $baseurl ?>/assets/imgs/logo.png" alt="<?= $site_name ?>" width="<?= $site_logo_width ?>">
		</a>

		<!-- Toggler button -->
		<button class="navbar-toggler text-dark" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<i class="fas fa-bars"></i>
		</button>

		<!-- Navbar collapsible -->
		<div id="navbarSupportedContent" class="collapse navbar-collapse flex-column  mr-md-5">
			<ul class="navbar-nav ml-auto">
				<?php
				// user is logged in
				if(!empty($_SESSION['user_connected'])) {
					?>
					<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/post/how-to-add-your-business-at-roctoc" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-book"></i> How To Add Business?</a>
				</li>
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/post/roctoc-premium-plans" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fa fa-btc"></i> Promotion Pricing</a>
				</li>
				
		<!--		<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/coupons" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-percent fa-spin"></i> <?= $txt_coupons ?></a>
				</li>-->
				
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/categories" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-sort-alpha-desc"></i> <?= $txt_categories ?></a>
					
				</li>
				
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/user/select-plan" id="navbarBtnCreateListing" class="btn btn-block text-white"><i class="fa fa-plus-circle"></i> <?= $txt_create_listing ?></a>
				</li>

				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/posts" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-newspaper-o"></i> <?= $txt_explore ?></a>
				</li>

					<li class="nav-item dropdown mr-md-3">
						<a href="#" id="navbarDropdown" class="btn btn-block" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-user"></i> <?= $txt_user ?>
						</a>

						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="<?= $baseurl ?>/user/"><?= $txt_dashboard ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= $baseurl ?>/user/select-plan"><?= $txt_create_listing ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= $baseurl ?>/user/sign-out"><?= $txt_signout ?></a>
							<?php
							if($is_admin) {
								?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?= $baseurl ?>/admin"><?= $txt_admin ?></a>
								<?php
							}
							?>
						</div>
					</li>
					<?php
				}

				// user is not logged in
				else {
					?>
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-book"></i> How To Add Business?</a>
				</li>
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fa fa-btc"></i> Promotion Pricing</a>
				</li>
				
		<!--		<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/coupons" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-percent fa-spin"></i> <?= $txt_coupons ?></a>
				</li>-->
				
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/categories" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fa fa-sort-alpha-desc"></i> <?= $txt_categories ?></a>
					
				</li>
				
				<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/user/sign-in" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-unlock-alt"></i> <?= $txt_signin ?></a>
				</li>
				
					<li class="nav-item mr-md-3">
					<a href="<?= $baseurl ?>/posts" id="navbarBtnSignIn" class="btn btn-block text-white"><i class="fas fa-newspaper-o"></i> <?= $txt_explore ?></a>
				</li>
					<?php
				}
				?>

				<!-- Search -->
				<li class="nav-item">
					<a href="#" id="navbarBtnSearch" class="btn btn-block"><i class="fas fa-search"></i> <?= $txt_search ?></a>
				</li>
			</ul>
		</div>
	</div>
</nav>

<!-- dummy navbar to create padding -->
<!--<div id="header-nav-dummy" class="navbar">-->
	<!-- Dummy Logo -->
<!--	<a class="navbar-brand" href="">-->
<!--		<img class="logo" src="<?= $baseurl ?>/assets/imgs/logo.png" alt="roctoc logo" width="<?= $site_logo_width ?>">-->
<!--	</a>-->
<!--</div>-->

<div id="mainSearch" class="container-fluid p-2 fixed-top bg-light" style="display:none">
	<form action="<?= $baseurl ?>/results" class="form-row" method="get">
		<div class="form-row container-fluid">
			<div class="col-md-5 mb-2 mb-md-0">
				<div class="input-group mr-md-2">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
					</div>
					<input type="text" id="s" class="form-control form-control-lg" name="s" placeholder="<?= $txt_keyword ?>">
				</div>
			</div>

			<div class="col-md-5 mb-2 mb-md-0">
				<div class="input-group mr-md-2 text-left">
					<select id="city-input" class="form-control form-control-lg" name="city">
						<?php
						if(!$cfg_use_select2) {
							?>
							<option disabled selected></option>
							<?php
							$stmt = $conn->prepare("SELECT * FROM cities LIMIT $cfg_city_dropdown_limit");
							$stmt->execute();

							while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								?>
								<option value="<?= e($row['city_id']) ?>"><?= e($row['city_name']) ?>, <?= e($row['state']) ?></option>
								<?php
							}
						}

						else {
							?>
							<option value="" disabled selected><?= $txt_city ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>

			<div class="col-md-2 mb-2 mb-md-0">
				<button type="submit" class="btn btn-lg btn-primary btn-block"><?= $txt_search ?></button>
			</div>
		</div>
	</form>
</div>
