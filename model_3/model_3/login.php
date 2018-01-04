<?php include('config/functions.inc.php');
	if(isset($_POST['logout_user']) && $_POST['logout_user']!=""){
		unset($_SESSION['user']);
	}
 
?> 

<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-3</title>
<?php include('includes/resources.php');?> 
</head>
<body>
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<!--main-container start-->
	<div class="container-fluid main-container styleguide-content">
		<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="login-panel">
				<legend>Sign in</legend>
				<form action="home" method="post" autocomplete="off" id="login_form">
					<fieldset>
					<div class="ip-outer">
						<label class="usa-input-error-label error-message" for="input-error">We didn’t find your email address in the HR Connect model. Please try again.</label>
						<div class="extra_ht"></div><div class="extra_ht"></div>
						<label class="" for="input-error">Your Email <span class="usa-additional_text">Required</span></label>
						<input type="hidden" name="employee_id" id="employee_id"/>
						<input type="hidden" name="first_last_name" id="first_last_name"/>
						<input type="text" id="username" name="username"/>
						<div class="clearfix"></div>
					</div>
					<input type="submit"  value="Sign in" id="login" />
					</fieldset>
				</form>
			</div>
		</div>
		</div>
	</div>
	<!--main-container end-->
	<!--footer start-->
	<?php include('includes/footer.php');?>
	<!--footer end-->
	<script>
		$('#login').click(function(){
			var return_val = false;
			if($('#username').val()==""){
				$('#username').closest('.ip-outer').addClass('usa-input-error');
				$('#username').closest('.ip-outer').find('.error-message').css({display:'block'});
				return_val = false;	
			}
			else{
				var val = $('#username').val();
				$.ajax({
					url:'<?php echo API_HOST_URL_PROJECT2;?>get_login_details.php',
					type:'POST',
					data:{'user_email':val},
					datatype:'jsonp',
					context:this,
					success:function(data){ 
						if(data['status_msg']=="Record Found"){
							var employee_id  = data['data']['employee_id'];	
							var first_last_name  = data['data']['first_last_name'];		
							$('#employee_id').val(employee_id);
							$('#first_last_name').val(first_last_name);
							$('#login_form').submit();
						}
						else{
							$('#username').val("");
							$('.ip-outer').addClass('input-error');
							$('.ip-outer').find('.error-message').css({display:'block'});
						}
					}
				});
				return_val = false;	
			}
			
			return return_val;
		});	
	</script>
</body>
</html>
