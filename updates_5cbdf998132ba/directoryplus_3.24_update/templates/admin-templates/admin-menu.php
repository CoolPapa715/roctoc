<?php
if(file_exists(__DIR__ . '/admin-menu-child.php')) {
	include_once('admin-menu-child.php');
	return;
}
?>
<?php
$categories_active    = 0;
$coupons_active       = 0;
$custom_fields_active = 0;
$emails_active        = 0;
$home_active          = 0;
$language_active      = 0;
$listings_active      = 0;
$locations_active     = 0;
$pages_active         = 0;
$plans_active         = 0;
$reviews_active       = 0;
$settings_active      = 0;
$tools_active         = 0;
$transactions_active  = 0;
$users_active         = 0;

if($route[1]== 'home') {
	$home_active = 1;
}

if($route[1]== 'listings') {
	$listings_active = 1;
}

if($route[1]== 'categories') {
	$categories_active = 1;
}

if($route[1]== 'reviews') {
	$reviews_active = 1;
}

if($route[1]== 'users') {
	$users_active = 1;
}

if($route[1]== 'plans') {
	$plans_active = 1;
}

if($route[1]== 'language') {
	$language_active = 1;
}

if($route[1]== 'locations') {
	$locations_active = 1;
}

if($route[1]== 'settings') {
	$settings_active = 1;
}

if($route[1]== 'pages' || $route[1]== 'create-page') {
	$pages_active = 1;
}

if($route[1]== 'emails') {
	$emails_active = 1;
}

if($route[1]== 'transactions') {
	$transactions_active = 1;
}

if($route[1]== 'tools') {
	$tools_active = 1;
}

if($route[1]== 'coupons') {
	$coupons_active = 1;
}

if($route[1]== 'custom-fields') {
	$custom_fields_active = 1;
}
?>
<div class="card">
	<ul class="list-group list-group-flush text-dark">
		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/home" class="text-dark <?= ($home_active) ? 'active' : '' ?>">
				<i class="fas fa-home"></i>
				<?= $txt_admin_dashboard ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/categories" class="text-dark <?= ($categories_active) ? 'active' : '' ?>">
				<i class="fas fa-sitemap"></i>
				<?= $txt_categories ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/coupons" class="text-dark <?= ($coupons_active) ? 'active' : '' ?>">
				<i class="fas fa-tags"></i>
				<?= $txt_coupons ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/custom-fields" class="text-dark <?= ($custom_fields_active) ? 'active' : '' ?>">
				<i class="fas fa-plus-circle" aria-hidden="true"></i>
				<?= $txt_custom_fields ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/emails" class="text-dark <?= ($emails_active) ? 'active' : '' ?>">
				<i class="fas fa-envelope"></i>
				<?= $txt_emails ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/language" class="text-dark <?= ($language_active) ? 'active' : '' ?>">
				<i class="fas fa-language"></i>
				<?= $txt_language ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/listings" class="text-dark <?= ($listings_active) ? 'active' : '' ?>">
				<i class="fas fa-list-ul"></i>
				<?= $txt_listings ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/locations/show-cities" class="text-dark <?= ($locations_active) ? 'active' : '' ?>">
				<i class="fas fa-location-arrow"></i>
				<?= $txt_locations ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/pages" class="text-dark <?= ($pages_active) ? 'active' : '' ?>">
				<i class="fas fa-file-alt"></i>
				<?= $txt_pages ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/plans" class="text-dark <?= ($plans_active) ? 'active' : '' ?>">
				<i class="fas fa-sticky-note"></i>
				<?= $txt_plans ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/reviews" class="text-dark <?= ($reviews_active) ? 'active' : '' ?>">
				<i class="fas fa-comment-alt"></i>
				<?= $txt_reviews ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/settings" class="text-dark <?= ($settings_active) ? 'active' : '' ?>">
				<i class="fas fa-cog"></i>
				<?= $txt_site_settings ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/tools" class="text-dark <?= ($tools_active) ? 'active' : '' ?>">
				<i class="fas fa-wrench"></i>
				<?= $txt_tools ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/transactions" class="text-dark <?= ($transactions_active) ? 'active' : '' ?>">
				<i class="fas fa-exchange-alt"></i>
				<?= $txt_transactions ?>
			</a>
		</li>

		<li class="list-group-item">
			<a href="<?= $baseurl ?>/admin/users" class="text-dark <?= ($users_active) ? 'active' : '' ?>">
				<i class="fas fa-users-cog"></i>
				<?= $txt_users ?>
			</a>
		</li>
	</ul>
</div>