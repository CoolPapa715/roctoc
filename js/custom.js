var swiper = new Swiper('.featureSliderMain', {
	slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
  	},
	breakpoints: {
		576: {
		  slidesPerView: 2,
		  spaceBetween: 48,
		},
	}
});

var swiper = new Swiper('.featureSliderBtm', {
	slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
  	},
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
  	},
	breakpoints: {
		576: {
		  slidesPerView: 2,
		  spaceBetween: 30,
		},
		768: {
		  slidesPerView: 3,
		  spaceBetween: 30,
		},
	}
});

$(window).scroll(function () {
var sc = $(window).scrollTop()
if (sc > 30) {
    $(".headerNavCol").addClass("fixed-header")
} else {
    $(".headerNavCol").removeClass("fixed-header")
    }
});

$('.toggle').click(function(){
  $('body').toggleClass('actNav');
});

$('.menuBackDrop').click(function(){
  $('body').removeClass('actNav');
});




AOS.init();


$(function() {
  $(".cardList").slice(0, 8).show();
  $("#loadMore").on('click', function(e) {
    $(".cardList:hidden").slice(0, 8).slideDown();
    if ($(".cardList:hidden").length == 0) {
      $("#loadLess").fadeIn('slow');
      $("#loadMore").hide();
    }
   
  });

  $("#loadLess").on('click', function(e) {
    $('.cardList:not(:lt(8))').fadeOut();
    $("#loadMore").fadeIn('slow');
    $("#loadLess").hide();

  });

});