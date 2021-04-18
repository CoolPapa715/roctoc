<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?></title>
<meta name="description" content="<?= $txt_meta_desc ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php require_once(__DIR__ . '/../head.php') ?>
<?php require_once('user-head.php') ?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<body class="tpl-user-create-listing">
<?php require_once(__DIR__ . '/../header.php') ?>

<div id="public-listings" class="container mt-5">
	<!-- dummy -->
   <form method="post" id="process-post-ads" action="<?= $baseurl ?>/user/process-post-ads">
        <input type="hidden" id="submit_token" name="submit_token" value="<?= $submit_token ?>">
		<input type="hidden" name="csrf_token" value="<?= session_id() ?>">
				
	    <div class="row">
     
    	    <div class="col-md-4 col-lg-3">
    	        <div class="form-group">
	            	
    		        <div class="form-check-inline">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="ads_type" value ='0' checked> Sell
                      </label>
                    </div>
                    <div class="form-check-inline">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="ads_type" value ='1'> Buy
                      </label>
                    </div>
    		    </div>
    
    		    <div class="form-group">
					<label for="category_id"><?= $txt_primary_category ?></label>
					<select id="category_id" name="category_id" class="form-control select2" required>
						<option value=""><?= $txt_select_cat ?></option>
						<?php get_children(0, 0, 0, $conn) ?>
					</select>
				</div>
				
				<div class="form-group">
					<input type="text" id="category_id_hidden" class="form-control" name="category_id_hidden" style="height:0;padding:0;opacity:0" required>
				</div>
    	    </div>

    	    <div class="col-md-8 col-lg-9">

    			
    
    			<div class="form-group">
    				<label for="ads_subject">Ad subject (*)</label>
    				<input type="text" id="ads_subject" class="form-control" name="ads_subject" required="">
    			</div>
                <div class="form-group">
    				<label for="ads_message">Ad message</label>
    				<textarea id="ads_message" class="form-control" name="ads_message" rows="3" required="" ></textarea>
    				<div class="float-right"><small id="count_message">100 Remaining</small></div>
    			</div>
    
    			<div class="form-group">
    				<label for="ads_company">company</label>
    				<input type="text" id="ads_company" class="form-control" name="ads_company" required="">
    			</div>
    			<!--<div class="form-group">-->
    			<!--	<label for="ads_company">name</label>-->
    			<!--	<input type="text" id="ads_maker_name" class="form-control" name="ads_maker_name" required="">-->
    			<!--</div>-->
    			<!--<div class="form-group">-->
    			<!--	<label for="ads_company">email</label>-->
    			<!--	<input type="text" id="ads_maker_email" class="form-control" name="ads_maker_email" required="">-->
    			<!--</div>-->

    			<!-- submit -->
    			
    			<div class="g-recaptcha" data-sitekey="your_site_key"></div>
    			
    			<div style="margin-top:100px">
    				<button class="btn btn-primary">Submit Listing</button>
    			</div>
    		</div>
	
	</div>
	</form>
</div>

<!-- footer -->
<?php require_once(__DIR__ . '/../footer.php') ?>


</body>
</html>
