<script>
/*--------------------------------------------------
Ratings
--------------------------------------------------*/
(function(){
	$('.item-rating').raty({
		readOnly: true,
		score: function() {
			return this.getAttribute('data-rating');
		},
		starType: 'i'
	});
}());
</script>
