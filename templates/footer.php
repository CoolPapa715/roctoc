<?php
if(file_exists(__DIR__ . "/footer-child-$html_lang.php") && basename(__FILE__) != "footer-child-$html_lang.php") {
	include_once("footer-child-$html_lang.php");
	return;
}

if(file_exists(__DIR__ . '/footer-child.php') && basename(__FILE__) != 'footer-child.php') {
	include_once('footer-child.php');
	return;
}
?>

<div class="container-fluid">
	<footer class="pt-4 my-md-5 pt-md-5 border-top">
		<div class="row">
			<div class="col-12 col-md">
			    

	<center> <a href="https://www.linkedin.com/company/roctoc/" target="_blank" class="fa fa-linkedin fa-2x" style ="color:#175597"> | <a href="https://www.facebook.com/Roctoc-338030837412438/" target="_blank" class="fa fa-facebook fa-2x" style ="color:#2F82DA"></a> | <a href="https://instagram.com/roctocofficial" target="_blank" class="fa fa-instagram fa-2x" style ="color:#E34A3D"> </a> | <a href="https://twitter.com/roctocofficial" target="_blank" class="fa fa-twitter fa-2x" style ="color:#3DCAE3"></a> | <a href="https://pinterest.com/roctoc" target="_blank" class="fa fa-pinterest-p fa-2x" style ="color:#E33D59"></a> | <a href="https://www.youtube.com/channel/UCVOOxxFg1q5tl2p07QbsmRA" target="_blank" class="fa fa-youtube-play fa-2x" style ="color:#F10711"></a> | <a href="https://www.reddit.com/user/roctoc" target="_blank" class="fa fa-reddit fa-2x" style ="color:#E74C53"></a> | <a href="https://www.quora.com/profile/Roctoc" target="_blank" class="fa fa-quora fa-2x" style ="color:#7F282C"></a>	<small class="d-block mb-3 text-muted">© 2021 Roctoc Three.</small></center>
		
		

				<?php include(__DIR__ . '/../inc/widget-language-selector.php') ?>
			</div>

		</div>

  
  <center><div id="counter-area">Real time <span id="counter"></span> visitors online right now</div></center>


<script>
  function r(t,r){return Math.floor(Math.random()*(r-t+1)+t)}var interval=2e3,variation=5,c=r(1499,2e3);$("#counter").text(c),setInterval(function(){var t=r(-variation,variation);c+=t,$("#counter").text(c)},interval);
</script>


	</footer>
</div>

<!-- css -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://roctoc.com/templates/css/counter.css">
<!-- external javascript -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="<?= $baseurl ?>/templates/js/raty/jquery.raty.js"></script>

<script src="<?= $baseurl ?>/assets/js/jquery-autocomplete/jquery.autocomplete.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/i18n/<?= $html_lang ?>.js"></script>
<script src="<?= $baseurl ?>/templates/js/ads_list.js"></script>


<?php
// include tpl-js
if($route[0] != 'user' && $route[0] != 'admin') {
	$js_inc = __DIR__ . '/tpl-js/js-' . $route[0] . '.php';
}

// if in the 'user' folder
if($route[0] == 'user') {
	$js_inc = __DIR__ . '/tpl-js/user-js/js-' . $route[1] . '.php';
}

// if in the 'admin' folder
if($route[0] == 'admin') {
	$js_inc = __DIR__ . '/tpl-js/admin-js/js-' . $route[1] . '.php';
}

if(file_exists($js_inc)) {
	include_once($js_inc);
}

// global js-footer
if(file_exists(__DIR__ . '/tpl-js/js-footer.php')) {
	include_once(__DIR__ . '/tpl-js/js-footer.php');
}
?>
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HKXQGRW9BR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HKXQGRW9BR');
</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60127b9fc31c9117cb735842/1et435q99';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->