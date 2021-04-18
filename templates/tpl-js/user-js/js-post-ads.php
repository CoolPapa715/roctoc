<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder@1.7.0/dist/Control.Geocoder.js"></script>

<script>

/*--------------------------------------------------
Upload Logo
--------------------------------------------------*/
(function() {
	// generate a click on the hidden input file field
	$('#upload-logo-btn').on('click', function(e){
		e.preventDefault();
		$('#logo_img').val("");
		$('#logo_img').trigger('click');
	});

	// upload logo img
	$('#logo_img').on('change',(function(e) {
		// append file input to form data
		var fileInput = document.getElementById('logo_img');
		var file = fileInput.files[0];
		var prev_img = $('#uploaded_logo').val();

		var formData = new FormData();
		formData.append('logo_img', file);
		formData.append('prev_img', prev_img);

		$.ajax({
			url: "<?= $baseurl ?>/user/process-upload-logo.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// remove current logo pic
				$('#logo-img').empty();

				// Add preloader
				$('<div class="thumbs-preloader" id="logo-preloader"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#logo-img');
			},
			success: function(data) {
				console.log(data);

				// parse json string
				var data = JSON.parse(data);

				// check if previous upload failed because of non allowed ext
				// #upload_failed div created by onSumit function above
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// delete preloader spinner
				$('#logo-preloader').remove();

				// remove current logo pic
				$('#logo-img').empty();

				if(data.result == 'success') {
					// create thumbnail src
					var logo_img = '<img src="' + data.message + '" class="rounded" width="132">';

					// display uploaded pic's thumb
					$('#logo-img').append(logo_img);

					// add hidden input field
					$('#uploaded_logo').val(data.filename);
				}

				else {
					$('<div id="upload-failed"></div>').appendTo('#logo-img').text(data.message);
				}
			},
			error: function(e) {
				$('<div id="upload-failed"></div>').appendTo('#logo-img').text(e);
			}
		});
	}));
}());

/*--------------------------------------------------
Delete Logo
--------------------------------------------------*/
(function() {
	$('#delete-logo-btn').on('click', function(e){
		e.preventDefault();

		var logo_img = $('#uploaded_logo').val();
		var post_url = '<?= $baseurl ?>/user/process-remove-logo.php';

		$.post(post_url, {
			logo_img: logo_img
			},
			function(data) {
				console.log(data);
				$('#logo-img').empty();
			}
		);
	});
}());

/*--------------------------------------------------
Upload Pics
--------------------------------------------------*/
(function() {
	// generate a click on the hidden input file field
	$('#upload-button').on('click', function(e){
		e.preventDefault();
		$('#item_img').trigger('click');
	});

	// upload img
	$('#item_img').on('change', function(e) {
		var formData = new FormData();
		var files = $('#item_img').prop('files');

		$.each(files, function(i, file) {
			// generate a random div id
			var random_id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

			// append file
			formData.append('item_img', file);

			$.ajax({
				type: 'POST',
				url: '<?= $baseurl ?>/user/process-upload.php',
				cache: false,
				contentType: false,
				processData: false,
				data : formData,
				beforeSend : function() {
					// remove previous preloader
					$('#upload_failed').remove();

					// Add preloader
					$('<div id="' + random_id + '" class="thumbs-preloader mr-3"><i class="fas fa-spinner fa-spin"></i></div>').appendTo('#uploaded');
				},
				success: function(response){
					console.log(response);

					// check if previous upload failed because of non allowed ext
					// #upload_failed div created by onSumit function above
					if ($('#upload_failed').length) {
						$('#upload_failed').remove();
					}

					// delete preloader spinner
					$('#' + random_id).remove();

					if(response == 1) {
						// Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
						$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_file_size ?>');
						// cancel upload
						return false;
					}

					else if(response == 10) {
						// Value: 10; custom error code, failed to move file
						$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_upload ?>');
						// cancel upload
						return false;
					}

					else if(response == 11) {
						// Value: 11; custom error code, no submit token
						$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_upload ?>');
						// cancel upload
						return false;
					}

					else if(response == 12) {
						// Value: 12; custom error code, more than max num pics
						// $('<div id="upload_failed"></div>').appendTo('#uploaded').text('Error: number of uploads exceeded (max <?= $max_pics ?>)');
						// cancel upload
						return false;
					}

					// upload success
					else {
						if($('.thumbs').length < <?= $max_pics ?>) {
							var thumb = '<?= $pic_baseurl ?>/<?= $place_tmp_folder ?>/' + response;

							// check file exists
							$.get(thumb).done(function() {
								// store thumb container div in memory
								var temp_thumb_div = $('<div class="thumbs position-relative mr-3"></div>');

								// display uploaded pic's thumb
								$('<img>').addClass("rounded").attr('src', thumb).attr('width', '132').appendTo(temp_thumb_div);
								$('<div id="delete-' + random_id + '" class="btn-light delete_pic"><small><?= $txt_delete ?></small></div>').appendTo(temp_thumb_div);
								$('<input type="hidden" name="uploads[]">').attr('value', response).appendTo(temp_thumb_div);
								$('#uploaded').append(temp_thumb_div);

								// count pics and enable/disable upload button
								switchUploadButton($('.thumbs').length);

								// unbind click event to previous .delete_pic links and attach again so that the click event is not assigned twice to the same .delete_pic link
								//$('.delete_pic').unbind('click');

								// make delete link work
								$('#delete-' + random_id).on('click', function() {
									// get pic filename from hidden input
									var pic = $(this).next().attr('value');

									// remove div.thumbs
									$(this).parent().fadeOut("fast", function() {
										$(this).remove();

										// re-enable upload button
										switchUploadButton($('.thumbs').length);
									});

									//
									$('<input type="hidden" name="delete_temp_pics[]" />').attr('value', pic).appendTo('#uploaded');

									// delete from tmp_photos table
									var post_url = '<?= $baseurl ?>/user/process-remove-tmp.php';

									$.post(post_url, {
										tmp_filename: response
										},
										function(data) {
											console.log(data);
										}
									);
								});
							}).fail(function() {
								// thumb does not exist
							})
						}
					}
				},
				error: function(err){
					console.log(err);
				}
			})
		});
	});
}());

/*--------------------------------------------------
Delete Pics (not being used
--------------------------------------------------*/
(function(){
	$('.delete_existing_pic').on('click', function() {
		// get pic filename from hidden input
		var pic = $(this).next().attr('value');

		// remove div.thumbs
		$(this).parent().fadeOut("fast", function() { $(this).remove(); });

		//
		$('<input type="hidden" name="delete_existing_pics[]">').attr('value', pic).appendTo('#uploaded');

		// re-enable upload button
		$('#upload_button').text('<?= $txt_upload_btn ?>');
	});
}());

/*--------------------------------------------------
Max allowed pics check
--------------------------------------------------*/
// Function to disable upload button if equal max
function switchUploadButton(count) {
	if(count >= <?= $max_pics ?>) {
		$('#upload-button').addClass('disabled').text("<?= $txt_upload_limit ?>");
	} else {
		$('#upload-button').removeClass('disabled').text("<?= $txt_upload_btn ?>");
	}
}

/*--------------------------------------------------
Categories
--------------------------------------------------*/
(function(){
	// init select2 for categories
	$('#category_id').select2({
		placeholder: '<?= $txt_select_cat ?>'
	});

	// Copy values to hidden inputs
	$('#category_id').on('change', function() {
		$('#category_id_hidden').val($(this).val());
	});
}());

/*--------------------------------------------------
Cities
--------------------------------------------------*/
(function(){
	<?php
	if($cfg_use_select2) {
		?>
		$('#city_id').select2({
			ajax: {
				url: '<?= $baseurl ?>/_return_cities_select2.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						query: params.term, // search term
						page: params.page
					};
				}
			},
			escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			minimumInputLength: 1
		});
		<?php
	}
	?>

	// Copy values to hidden inputs
	$('#city_id').on('change', function() {
		$('#city_id_hidden').val($(this).val());
	});
}());

/*--------------------------------------------------
Tooltips
--------------------------------------------------*/
(function(){
	$('[data-toggle="tooltip"]').tooltip()
}());

/*--------------------------------------------------
Bootstrap modifications
--------------------------------------------------*/
(function(){
	// Allow Bootstrap dropdown menus to have forms/checkboxes inside,
	// and when clicking on a dropdown item, the menu doesn't disappear.
	$(document).on('click', '.dropdown-menu.dropdown-menu-form', function(e) {
		e.stopPropagation();
	});
}());

/*--------------------------------------------------
Custom Fields
--------------------------------------------------*/
var ajax_request;

(function(){
	// on primary category change
	$('#category_id').on('change', function(e) {
		e.preventDefault();

		// cancel previous ajax_request
		if(typeof(ajax_request) != "undefined"){
			ajax_request.abort();
		}

		// init array
		var cat_ids = [];

		// add primary cat
		cat_ids.push(parseInt($('#category_id').val()));

		// add secondary cats
		$("input[name='cats[]']:checked").each(function (){
			cat_ids.push(parseInt($(this).val()));
		});

		// vars
		var post_url = baseurl + '/user/get-custom-fields.php';

		// post
		$.post(post_url, {
			cat_id: cat_ids,
			place_id: 0,
			from: 'create',
			custom_fields_ids: '<?= $custom_fields_ids ?>'
			},
			function(data) {
				console.log(data);

				// remove #custom_fields_ids hidden input
				$('#custom_fields_ids').remove();

				// remove previous #cat-fields
				$('#cat-fields').fadeOut(300, function() { $(this).remove(); });

				// append html response
				$('#custom-fields').append(data).hide().fadeIn();
			}
		);
	});

	// on secondary categories change
	$('.cat-tree-item').on('change', function(e) {
		// cancel previous ajax_request
		if(typeof(ajax_request) != "undefined"){
			ajax_request.abort();
		}

		// init array
		var cat_ids = [];

		// add primary cat
		cat_ids.push(parseInt($('#category_id').val()));

		// add secondary cats
		$("input[name='cats[]']:checked").each(function (){
			cat_ids.push(parseInt($(this).val()));
		});

		var place_id = <?= !empty($place_id) ? $place_id : '0' ?>;

		var post_url = baseurl + '/user/get-custom-fields.php';
		$.post(post_url, {
			cat_id: cat_ids,
			place_id: place_id,
			from: 'create',
			custom_fields_ids: '<?= $custom_fields_ids ?>'
			},
			function(data) {
				console.log(data);

				// remove #custom_fields_ids hidden input
				$('#custom_fields_ids').remove();

				// remove previous #cat-fields
				$('#cat-fields').fadeOut(300, function() { $(this).remove(); });

				// append html response
				$('#custom-fields').append(data);
			}
		);
	});
}());

/*--------------------------------------------------
Textarea char counter
--------------------------------------------------*/
(function(){
	var text_max = <?= $short_desc_length ?>;
	$('#count_message').html(text_max + ' <?= $txt_remaining ?>');

	$('#short_desc, #specialties').on('keyup', function() {
		var text_length = $('#short_desc, #specialties').val().length;
		var text_remaining = text_max - text_length;

		$('#count_message').html(text_remaining + ' <?= $txt_remaining ?>');
	});
}());


</script>