<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once('head.php') ?>

<!-- Page CSS -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/js/raty/jquery.raty.css">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/style_ads.css">


</head>

<body class="tpl-<?= $route[0] ?>">

<?php include('header.php') ?>

<div id="public-listings" class="container-fluid mt-5">
	<!-- dummy -->
	<div id="dummy" style="position:absolute;top:0;left:0;z-index:20000"></div>

	<!-- Sidebar -->
	<div id="the-sidebar" class="sidebar bg-light">
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-times"></i></a>

		<?php
		if(!empty($top_level_cats)) {
			?>
			<div class="mb-4 p-2">
				<p><strong><i class="fas fa-th-large"></i> <?= $txt_categories ?></strong></p>
				<hr>

				<?php
				foreach($top_level_cats as $v) {
					?>
					<div class="mb-0">
						<?php
						if($cat_id == $v['cat_id']) echo '<strong>';
						?>
						<a href="<?= $v['cat_link'] ?>" title="<?= $v['cat_name'] ?>" class="mb-0"><?= $v['cat_name'] ?></a>
						<?php
						if($cat_id == $v['cat_id']) echo '</strong>';
						?>

						<?php
						// will only show if cur cat is not a top parent cat
						// $cur_cat_top_level_parent = isset($cats_path[0]) ? $cats_path[0] : '';
						if($cur_cat_top_level_parent == $v['cat_id']) {
							if(count($cats_path) == 1) {
								foreach($cur_cat_siblings as $v2) {
									?>
									<div class="mb-0 pl-4">
										<?php
										if($cat_id == $v2['cat_id']) echo '<strong>';
										?>
										<a href="<?= $v2['cat_link'] ?>" title="" class="mb-0"><?= $v2['cat_name'] ?></a>
										<?php
										if($cat_id == $v2['cat_id']) echo '</strong>';
										?>
									</div>
									<?php
									if($cat_id == $v2['cat_id']) {
										if(!empty($cur_cat_children)) {
											foreach ($cur_cat_children as $v3) {
												?>
												<div class="mb-0 pl-5">
													<a href="<?= $v3['cat_link'] ?>" title="" class="mb-0"><?= $v3['cat_name'] ?></a>
												</div>
												<?php
											}
										}
									}
								}
							}

							if(count($cats_path) == 2) {
								// show parent cat
								?>
								<div class="mb-0 pl-4">
									<a href="<?= $all_cats[$cats_path[1]]['cat_link'] ?>" title="" class="mb-0"><?= $all_cats[$cats_path[1]]['cat_name'] ?></a>
								</div>
								<?php
								// show subcats and its siblings
								foreach($cur_cat_siblings as $v2) {
									?>
									<div class="mb-0 pl-5">
										<?php
										if($cat_id == $v2['cat_id']) echo '<strong>';
										?>
										<a href="<?= $v2['cat_link'] ?>" title="" class="mb-0"><?= $v2['cat_name'] ?></a>
										<?php
										if($cat_id == $v2['cat_id']) echo '</strong>';
										?>
									</div>
									<?php
								}
							}
						}

						// show children of top parent cats
						if($cat_id == $v['cat_id']) {
							if(!empty($cur_cat_children)) {
								foreach ($cur_cat_children as $v2) {
									?>
									<div class="mb-0 pl-4">
										<a href="<?= $v2['cat_link'] ?>" title="" class="mb-0"><?= $v2['cat_name'] ?></a>
									</div>
									<?php
								}
							}
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		<?php
		}
		?>

		<form id="sidebar-form" class="p-2" method="get" action="<?= $baseurl ?>/results">
			<input type="hidden" name="cat_id" value="<?= $cat_id ?>">

			<!-- location -->
			<h6><i class="fas fa-map-marker-alt"></i> <strong><?= $txt_location ?></strong></h6>
			<hr>

			<div id="select2-sidebar" class="form-row mb-3">
				<select id="city-input-sidebar" class="form-control form-control-lg" name="city">
					<option value="0"><?= $txt_city ?></option>

					<?php
					if(isset($city_id) && isset($city_name)) {
						?>
						<option value="<?= $city_id ?>" selected><?= $city_name ?>, <?= $state_abbr ?></option>
						<?php
					}
					?>

					<?php
					if(!$cfg_use_select2) {
						$stmt = $conn->prepare("SELECT * FROM cities LIMIT $cfg_city_dropdown_limit");
						$stmt->execute();

						while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							?>
							<option value="<?= e($row['city_id']) ?>"><?= e($row['city_name']) ?>, <?= e($row['state']) ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>

			<!-- custom fields -->
			<?php
			foreach($custom_fields as $k => $v) {
				if($v['field_type'] == 'radio' || $v['field_type'] == 'select' || $v['field_type'] == 'checkbox') {
					$values_arr = explode(';', $v['values_list']);
					$tr_values_arr = explode(';', $v['tr_values_list']);

					// check if translated values exist
					foreach($values_arr as $k2 => $v2) {
						if(empty($tr_values_arr[$k2])) {
							$tr_values_arr[$k2] = $values_arr[$k2];
						}
					}
				}
				?>
				<div class="mb-3" id="li-field-<?= $v['field_id'] ?>">
					<p><strong><?= $v['tr_field_name'] ?></strong></p>

					<?php
					if($v['filter_display'] == 'radio') {
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));
							?>
							<div class="custom-control custom-radio">
								<input type="radio" id="val_<?= $k ?><?= $k2 ?>" class="custom-control-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>">
								<label class="custom-control-label" for="val_<?= $k ?><?= $k2 ?>"><?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
						}
					}

					elseif($v['filter_display'] == 'select') {
						?>
						<div class="form-group">
							<select class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>">
								<option value=""></option>
								<?php
								foreach($values_arr as $k2 => $v2) {
									$v2 = e(trim($v2));
									?>
									<option value="<?= $v2 ?>"><?= $tr_values_arr[$k2] ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<?php
					}

					elseif($v['filter_display'] == 'checkbox') {
						foreach($values_arr as $k2 => $v2) {
							$v2 = e(trim($v2));
							?>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" id="val_<?= $k ?><?= $k2 ?>" class="custom-control-input" name="field_<?= $v['field_id'] ?>[]" value="<?= $v2 ?>">
								<label class="custom-control-label" for="val_<?= $k ?><?= $k2 ?>"><?= $tr_values_arr[$k2] ?></label>
							</div>
							<?php
						}
					}

					elseif($v['filter_display'] == 'range_text') {
						?>
						<div class="form-group">
							<div class="row g-3">
								<div class="col-6">
									<input type="text" id="val_<?= $v['field_id'] ?>_from" class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>[]" placeholder="<?= $txt_from ?>">
								</div>
								<div class="col-6">
									<input type="text" id="val_<?= $v['field_id'] ?>_to" class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>[]" placeholder="<?= $txt_to ?>">
								</div>
							</div>
						</div>
						<?php
					}

					elseif($v['filter_display'] == 'range_number') {
						?>
						<div class="form-group">
							<div class="row g-3">
								<div class="col-6">
									<input type="number" id="val_<?= $v['field_id'] ?>_from" class="form-control form-control-sm mb-1" name="field_<?= $v['field_id'] ?>[from]" placeholder="<?= $txt_from ?>">
								</div>
								<div class="col-6">
									<input type="number" id="val_<?= $v['field_id'] ?>_to" class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>[to]" placeholder="<?= $txt_to ?>">
								</div>
							</div>
						</div>
						<?php
					}

					elseif($v['filter_display'] == 'range_select') {
						?>
						<div class="form-group">
							<div class="row g-3">
								<div class="col-6">
									<select class="form-control form-control-sm mb-2" name="field_<?= $v['field_id'] ?>">
										<option value=""><?= $txt_from ?></option>
										<?php
										foreach($values_arr as $k2 => $v2) {
											$v2 = e(trim($v2));
											?>
											<option value="<?= $v2 ?>"><?= $tr_values_arr[$k2] ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="col-6">
									<select class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>">
										<option value=""><?= $txt_to ?></option>
										<?php
										foreach($values_arr as $k2 => $v2) {
											$v2 = e(trim($v2));
											?>
											<option value="<?= $v2 ?>"><?= $tr_values_arr[$k2] ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<?php
					}

					else {
						?>
						<div class="form-group">
							<input type="text" class="form-control form-control-sm" name="field_<?= $v['field_id'] ?>">
						</div>
						<?php
					}
					?>
				</div>
			<?php
			}
			?>

			<!-- submit -->
			<button class="btn btn-block btn-primary mb-4"><?= $txt_submit ?></button>
		</form>
	</div>

    <div class="row" id="content">
		<!-- Map 
        <div id="map-col" class="col-lg-5 h-100 fixed-top">
			<?php
			if(!empty($list_items)) {
				?>
				<div class="map-wrapper sidebar-map" id="sticker" style="z-index:998;width:100%; height:100%">
					<div id="map-canvas" style="width:100%; height:100%"></div>
				</div>
				<?php
			}
			?>
        </div>-->

		<!-- Scrollable content -->
		<div class="col-md-6">
			<!-- Breadcrumbs and filter button -->
			<div class="container-fluid mt-3 mb-4">
				<div class="d-flex">
					<div class="flex-grow-1 breadcrumbs"><?= $breadcrumbs ?></div>
				</div>
			</div>

			<div class="container-fluid mb-4">
				<!-- sort, nearby, filters -->
				<ul class="nav justify-content-end mb-2">
					<!-- filters -->
					<li class="nav-item">
						<button class="btn btn-sm btn-light mr-1" type="button" onclick="openNav()"><i class="fas fa-sliders-h"></i> <?= $txt_filters ?></button>
					</li>

					<?php
					if(!empty($cgf_max_dist_values)) {
						?>
						<li class="nav-item">
							<div class="dropdown">
								<button class="btn btn-sm btn-light dropdown-toggle mr-1" type="button" id="dropdown-menu-nearby" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?= $txt_nearby ?>
								</button>
								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-menu-nearby">
									<?php
									if(empty($user_lat) || empty($user_lng)) {
										?>
										<a class="dropdown-item" href="#"><?= $txt_enable_geo ?></a>
										<?php
									}

									else {
										?>
										<a class="dropdown-item" href="<?= $page_url_without_page ?><?= !empty($sort) ? '?sort=' . e($_GET['sort']) : '' ?>"><?= $txt_clear ?></a>
										<?php
										foreach($max_dist_values as $v) {
											?>
											<a class="dropdown-item <?= $v == $_GET['dist'] ? 'active' : '' ?>" href="<?= $page_url_without_page ?>?<?= !empty($sort) ? 'sort=' . e($_GET['sort']) . '&' : '' ?>dist=<?= $v ?>"><?= $v ?> <?= $cgf_max_dist_unit ?> </a>
											<?php
										}
									}
									?>
								</div>
							</div>
						</li>
						<?php
					}
					?>
				</ul>
			</div>

			<div class="container-fluid item-list">
				<?php
				// if no listings
				if(empty($list_items)) {
					echo $txt_no_results;
				}

				foreach($list_items as $k => $v) {
					$feat_class = $v['is_feat'] ? 'featured' : '';
					$feat_badge = $v['is_feat'] ? '<span class="badge badge-success">' . $txt_featured . '</span>' : '';
					?>
					<div class="row list-item mb-4 mx-3 mx-sm-0 <?= $feat_class ?>" data-listing-id="<?= $v['place_id'] ?>">
						<div class="col-sm-5">
							<a href="<?= $v['listing_link'] ?>"><img src="<?= $v['logo_url'] ?>" class="rounded" style="max-height: 240px"></a>
							<span class="cat-name-figure rounded p-2"><?= $v['cat_name'] ?></span>
						</div>

						<div class="col-sm-7 px-sm-3 py-3 pt-sm-0 pl-sm-4">
							<div class="d-flex mb-3">
								<div class="flex-grow-1">
									<h4 class="mb-2"><a href="<?= $v['listing_link'] ?>"><?= $v['place_name'] ?></a>
										<?= $feat_badge ?>
									</h4>
									<div class="item-rating" data-rating="<?= $v['rating'] ?>">
										<!-- raty plugin placeholder -->
									</div>
								</div>
							</div>

							<div class="card-text mb-2">
								<?= $v['short_desc'] ?>
							</div>

							<?php
							if($cfg_show_website) {
								?>
								<small><a href="<?= $v['website'] ?>" target="_blank"><?= $v['website'] ?></a></small>
								<?php
							}
							?>

							<hr>

							<div class="d-flex flex-wrap mb-2">
								<?php
								if(!empty($custom_fields_values[$v['place_id']])) {
									foreach($custom_fields_values[$v['place_id']] as $k2 => $v2) {
										?>
										<div class="mr-1 custom-field">
											<?php
											if(in_array($custom_fields[$k2]['show_in_res'], array('icon', 'name-icon'))) {
												echo $custom_fields[$k2]['icon'];
											}

											if(in_array($custom_fields[$k2]['show_in_res'], array('name', 'name-icon'))) {
												?>
												<small><strong><?= $custom_fields[$k2]['field_name'] ?></strong></small><?php
											}
											?>:
											<small><span class="custom-field-value"><?= $v2 ?> <?= $custom_fields[$k2]['value_unit'] ?></span></small>
										</div>
										<?php
									}
								}
								?>
							</div>

							<div class="d-flex">
								<div class="address flex-grow-1">
									<strong>
										<i class="fas fa-map-marker-alt"></i>
										<?= !empty($v['address']) ? $v['address'] : '' ?>
										<?= !empty($v['city_name']) ? " - " . $v['city_name'] . ", " : '' ?>
										<?= !empty($v['state_abbr']) ? $v['state_abbr'] : '' ?>
										<?= !empty($v['postal_code']) ? $v['postal_code'] : '' ?>
									</strong>

									<?php
									if(!empty($v['area_code']) && !empty($v['phone'])) {
										?>
										<div class="tel">
											<a href="tel:+<?= $v['country_call'] ?><?= $v['area_code'] ?><?= $v['phone'] ?>">
												<strong><i class="fas fa-mobile-alt"></i>
													<?php
													if($cfg_show_country_calling_code) {
														?>
														+<?= $v['country_call'] ?>
														<?php
													}
													?>
													<?= $v['area_code'] ?>-<?= $v['phone'] ?>
												</strong>
											</a>
										</div>
										<?php
									}
									?>
								</div>

								<div class="btn pointer">
									<span class="add-to-favorites" data-listing-id=<?= $v['place_id'] ?>><i class="<?= in_array($v['place_id'], $favorites) ? 'fas' : 'far' ?> fa-heart"></i></span>
								</div>
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
			</div>
		</div>
		<div class="col-md-6">
		    <div class="container-fluid mt-3 mb-4">
		        <div class="col-md-6">
		            <a href="<?= $baseurl ?>/user/post-ads" class="btn btn-outline-primary btn-block">POST ADS </a>
		        </div>
            </div>
            <div class="container-fluid item-list">
                   
				<table class='table table-bordered articles articles-index'>
				    <tbody>
				        
				        <?php foreach($ads_list as $k => $v) { ?>
    				        <tr class="article" id="ads_<?= $v['ads_id'] ?>">
    				            <!--<td class="avatar">-->
    				            <!--    <a href="/profiles/14860"> style="visibility: visible;"></a>-->
				                <!--</td>-->
    				            
				                <td class="name">
    				                <div class="inner-wrapper">
    				                    <a target="_blank" href="/marketing_articles/42866"><?= $v['first_name'] ?> <?= $v['last_name'] ?></a>
				                    </div>
				                    <hr>
				                    <div class="type">
        				               <span class="sell">Seller</span>
    				                </div>
				                    
		                        </td>
    				            <td class="subject">
    				                <div class="inner-wrapper">
    				                    <a target="_blank" href="/marketing_articles/42866"><?= $v['ads_subject'] ?></a>
				                    </div>
				                    <div class="comment-link">
				                        <span class="comments_span"  data-ads-id="<?= $v['ads_id'] ?>" style="display: inline;" page-num="0" page-offset="3"><?= $v['ads_comment_cnt'] ?> Comments</span>
			                        </div>
		                        </td>
		                        <td class="description">
		                            <div class="inner-wrapper">
		                                <?= $v['ads_message'] ?>
		                            </div>
	                            </td>
	                            <td class="meta-data gray" rowspan="1"><div class="views">
	                                <div class="count">
	                                    <img src="https://assets.mymediads.com/assets/svg/views-3d0ed90cea95c106a4ac1da62a508bd3370a62e25960a5867cdb2cfed07cb2b2.svg" data-nsfw-filter-status="sfw" style="visibility: visible;"><span><?= $v['ads_visitor_cnt'] ?></span>
                                    </div>
                                    <span class="last-up-at pull-right">6 h ago</span>
                                    </div>
                                    <hr>
                                    <div class="mini-buttons">
                                        <div class="social-block" id="social-buttons-marketing_advert_42866">
                                            <div class="social-buttons"> 
                                                <a target="_blank" class=" social-button" href="https://www.facebook.com/sharer.php?u=https%3A%2F%2Fmymediads.com%2Farticles%2F42866">
                                                    <img src="https://assets.mymediads.com/assets/svg/facebook_b-cd5e090d8a8e42bc62855b5a1a0d899308523179466a1d012842363f42948c3c.svg" data-nsfw-filter-status="sfw" style="visibility: visible;"></a> 
                                                <a target="_blank" class=" social-button" href="https://www.linkedin.com/cws/share?url=https%3A%2F%2Fmymediads.com%2Farticles%2F42866">
                                                    <img src="https://assets.mymediads.com/assets/svg/linkedin_b-b19cbd6c55a46aba2fc6f9bf1f94f398dc495a865e9fc59f80611f020d3eaf05.svg" data-nsfw-filter-status="sfw" style="visibility: visible;"></a> 
                                                <a target="_blank" class=" social-button" href="https://plus.google.com/share?url=https%3A%2F%2Fmymediads.com%2Farticles%2F42866">
                                                    <img src="https://assets.mymediads.com/assets/svg/g-cbf536b9d26441c8b2f7a8a5c99bdd5c965d5eb9c7d1543ed4f6a1fc22b7f289.svg" data-nsfw-filter-status="sfw" style="visibility: visible;"></a> 
                                                <a target="_blank" class=" social-button" href="https://twitter.com/intent/tweet?url=https%3A%2F%2Fmymediads.com%2Farticles%2F42866&amp;text=&amp;hashtags=">
                                                    <img src="https://assets.mymediads.com/assets/svg/tw-e116669226929ee3cb4fddc7407b0f65ff148fa8b0f401da0ef565873c2b1802.svg" data-nsfw-filter-status="sfw" style="visibility: visible;"></a> 
                                            </div>
                                        </div>
                                        <!--<form class="button_to" method="get" action="/message_threads/new?source_id=42866&amp;source_type=advert&amp;to_user_id=14906" data-remote="true">-->
                                        <!--    <button class="button btn btn-blue message contact_user" type="submit"><span>Contact</span></button>-->
                                        <!--</form>-->
                                        <!--<form class="button_to" method="post" action="/marketing_articles/42866/make_favorite" data-remote="true">-->
                                        <!--    <input class="btn-favorite" type="submit" value="">-->
                                        <!--    <input type="hidden" name="authenticity_token" value="ucGUVklW7ls72QeUBcagp7rQtv2RdL3wTk42AqteKxEw6buetpj1FNqTcdRHw+GJ8U5laAnK0K+LWujmV9xfGg==">-->
                                        <!--    <input type="hidden" name="is_my_list">-->
                                        <!--</form>-->
                                    </div>
                                </td>
                            </tr>
                            <tr class="comments-table collapse" id="ads_tr_<?= $v['ads_id'] ?>">
                                <td class="comments-td">
                                    <div class="comments row" id="comment_div_<?= $v['ads_id'] ?>">

                                    </div>
    
                                    <div class="row comment-form">
                                    
                                          
                                            <div class="col-md-8 buttons"><textarea class="text optional form-control" maxlength="1024" placeholder="Add your comment" id="comment_body_<?= $v['ads_id'] ?>" style="overflow: autoscroll; height: 30px;">
                                                </textarea>
                                            </div>
                                            <div class="col-md-4 buttons">
                                                <input type="button"  value="Post comment" class="btn btn-blue comment_btn"  data-disable-with="Post comment" data-ads-id="<?= $v['ads_id'] ?>" >
                                            </div>
        
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                       
			        </tbody>
				</table>
			</div>

		</div>
	</div>
</div>

<!-- footer -->
<?php require_once('footer.php') ?>

</body>
</html>