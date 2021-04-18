<script src="//cdnjs.cloudflare.com/ajax/libs/jinplace/1.2.1/jinplace.min.js"></script>
<script>
(function(){
	$('#remove-review-modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var review_id = button.data('review-id');
		var modal = $(this);

		// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// var post_url = '<?= $baseurl ?>' + '/user/ajax-get-place-name.php';

		modal.find('.remove-review').attr('data-remove-id', review_id);

		//$.post(post_url, { review_id: review_id },
		//	function(data) {
		//		modal.find('.remove-review').attr('data-remove-id', review_id);
		//	}
		//);
	})

	$('.remove-review').on('click', function() {
		var review_id = $(this).attr('data-remove-id');
		var post_url = '<?= $baseurl ?>' + '/user/process-remove-review.php';
		var wrapper = '#review-' + review_id;
		$.post(post_url, {
			review_id: review_id
			},
			function(data) {
				if(data) {
					$(wrapper).empty();
					var review_removed = $('<div class="alert alert-success"></div>');
					$(review_removed).text(data);
					$(review_removed).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
	});

	// edit in place
	$('.editable').jinplace();
}());
</script>
