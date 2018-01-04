<footer class="usa-footer usa-footer-slim" role="contentinfo">
    <div class="usa-footer-primary-section">
      <div class="usa-grid-full">
        <nav class="usa-footer-nav usa-width-two-thirds">
          <ul class="usa-unstyled-list">
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="<?php echo SITE_PATH.'usaid/phoenix3';?>" target="_blank">Phoenix</a>
            </li>
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="<?php echo SITE_PATH.'usaid/glaas3';?>" target="_blank">GLAAS</a>
            </li>
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="<?php echo PICTURE_SERVER;?>" target="_blank">HR Connect</a>
            </li>
			<li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="<?php echo API_MODEL_URL_STRATEGY;?>" target="_blank">Strategy</a>
            </li>
            <li class="usa-width-one-fourth usa-footer-primary-content">
              <a class="usa-footer-primary-link" href="https://docs.google.com/document/d/1COwMKs9n_J43_rnn9KEgNzS9ejs4h9QrvAu-wByPcRY/edit" target="_blank">Feedback</a>
            </li>
          </ul>
        </nav>
        <div class="usa-width-one-third">
  
        </div>
      </div>
    </div>
	<div class="usa-footer-secondary_section">
      <div class="usa-grid">
        <div class="usa-footer-logo">
          <img class="usa-footer-slim-logo-img" src="img/logo.png" alt="Logo image">
        </div>
      </div>
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
