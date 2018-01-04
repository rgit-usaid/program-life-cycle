<footer>
	<div class="container-fluid">
		<nav>
			<ul>
				<li><a href="http://rgitdemo.com/usaid/phoenix/" target="_blank">Phoenix</a> | </li>
				<li><a href="http://rgitdemo.com/usaid/glaas/" target="_blank">GLAAS</a> | </li>
				<li><a href="http://rgitdemo.com/usaid/hr-connect/" target="_blank">HR Connect</a> | </li>
				<li><a href="https://docs.google.com/document/d/1COwMKs9n_J43_rnn9KEgNzS9ejs4h9QrvAu-wByPcRY/edit" target="_blank">Feedback</a></li>
			</ul>
			<div class="clear"></div>
		</nav>
	</div>
	<div class="copyright">&copy; usaid.gov</div>
</footer>
<script>
$(document).ready(function(){
	$('.mb-leftpanel-btn').click(function(){ 
		if($(this).next('.lft-dpw').css('display')=="block"){
			$(this).next('.lft-dpw').css('display','none');	
		}
		else{
			$(this).next('.lft-dpw').css('display','block');	
		}
	});
});
function logout(){
	$('.form_logout').submit();
}
</script>
<script src="<?php echo HOST_URL;?>js/bootstrap-v3.3.4.min.js"></script>
<script src="<?php echo HOST_URL;?>/js/pace.min.js"></script>
