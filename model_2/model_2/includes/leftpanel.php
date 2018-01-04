<div class="leftpanel">
	<ul>
		<li class="par"><a <?php if(strpos($_SERVER['REQUEST_URI'],"/home")!== false){?> class="active" <?php }?> href="home"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
		<li class="par">
		  <a <?php if(strpos($_SERVER['REQUEST_URI'],"/projects")!== false){?> class="active" <?php }?> href="#"><i class="fa fa-th" aria-hidden="true"></i> Projects</a>
		  <ul class="sub">
			<li><a <?php if(strpos($_SERVER['REQUEST_URI'],"/projects")!== false){?> class="active" <?php }?> href="projects"><i class="fa fa-link" aria-hidden="true"></i> My Projects</a></li>			
			<li><a <?php if(strpos($_SERVER['REQUEST_URI'],"/add_new_project")!== false){?> class="active" <?php }?> href="add_new_project"><i class="fa fa-link" aria-hidden="true"></i> Add New Project</a></li>	
		  </ul>
		</li>
		<li style="height:100px">
		
		</li>
		<li class="crosslink">
		  <a href="#" style="background-image:url('http://rgdemo.com/usaid/amp/2/img/money.png'); background-position:10px; background-size:20px; background-repeat:no-repeat; padding-left:30px"> Phoenix</a>
		</li>
		<li class="crosslink">
		  <a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i> GLASS</a>
		</li>
		<li class="crosslink">
		  <a href="#"><i class="fa fa-user" aria-hidden="true"></i> HR Connect</a>
		</li>
		<li class="crosslink">
		  <a href="#"><i class="fa fa-server" aria-hidden="true"></i> Artifacts</a>
		</li>
		<li class="crosslink">
		  	<form method="post" class="form-inline form_logout" action="login">
				<input type="hidden" name="logout_user" value="logout_user"/>
				<a href="javascript:logout()" class="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
			</form>
		</li>
	</ul>
</div>
<script>
	function logout(){
		$('.form_logout').submit();
	}
</script>