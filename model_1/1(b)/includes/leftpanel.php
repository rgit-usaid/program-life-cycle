<div class="overlay"></div>
<aside class="sidenav">
	<nav>
	  <ul class="usa-sidenav-list">
		<li><a <?php if(strpos($_SERVER['REQUEST_URI'],"/home")!== false){?> class="usa-current" <?php }?> href="home"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
		<li>
		  <a <?php if(strpos($_SERVER['REQUEST_URI'],"/projects")!== false){?> class="usa-current" <?php }?> href="#"><i class="fa fa-th" aria-hidden="true"></i> Projects</a>
		  <ul class="usa-sidenav-sub_list">
			<li><a <?php if(strpos($_SERVER['REQUEST_URI'],"/projects")!== false){?> class="usa-current" <?php }?> href="projects"><i class="fa fa-link" aria-hidden="true"></i> My Projects</a></li>			
			<li><a <?php if(strpos($_SERVER['REQUEST_URI'],"/add_new_project")!== false){?> class="usa-current" <?php }?> href="add_new_project"><i class="fa fa-link" aria-hidden="true"></i> Add New Project</a></li>	
		  </ul>
		</li>
		<li style="height:100px">
		
		</li>
		<li style="background:#e4e2e0">
		  <a href="#" style="background-image:url('<?php echo HOST_URL;?>img/money.png'); background-position:17px; background-size:20px; background-repeat:no-repeat; padding-left:40px"> Phoenix</a>
		</li>
		<li style="background:#e4e2e0">
		  <a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i> GLASS</a>
		</li>
		<li style="background:#e4e2e0">
		  <a href="#"><i class="fa fa-user" aria-hidden="true"></i> HR Connect</a>
		</li>
		<li style="background:#e4e2e0">
		  <a href="#"><i class="fa fa-server" aria-hidden="true"></i> Artifacts</a>
		</li>
		<li>
		  	<form method="post" class="form-inline form_logout" action="login">
				<input type="hidden" name="logout_user" value="logout_user"/>
				<a href="javascript:logout()" style="background:#dce4ef"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
			</form>
		</li>
	  </ul>
	</nav>
</aside>
<aside class="sidenav-mobile">
  <a class="sliding-panel-close" href="#">
	<img src="img/close.svg" alt="close">
  </a>
  <nav>
	<ul class="usa-sidenav-list usa-accordion">
	  <li>
		<button class="usa-current" aria-controls="side-nav-1">Model 1</button>
		<ul id="side-nav-1" class="usa-sidenav-sub_list">
		  <li>
			<a class="usa-current" href="home"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a>
		  </li>
		</ul>
	  </li>
	  <li>
		<button aria-expanded="false" aria-controls="sidenav-3" class="fc-black">Projects</button>
		<ul id="sidenav-2" class="usa-sidenav-sub_list">
		  <li>
			<a href="projects"><i class="fa fa-link" aria-hidden="true"></i> My Projects</a>
		  </li>
		  <li><a href="add_new_project"><i class="fa fa-link" aria-hidden="true"></i> Add New Projects</a></li>	
		</ul>
	  </li>
	  <li>
	  <a href="#" style="background-image:url(../img/money.png); background-position:17px; background-size:20px; background-repeat:no-repeat; padding-left:40px"> Phoenix</a>
	</li>
	<li>
	  <a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i> GLASS</a>
	</li>
	<li>
	  <a href="#"><i class="fa fa-users" aria-hidden="true"></i> HR Connect</a>
	</li>
	<li>
	  <a href="#"><i class="fa fa-server" aria-hidden="true"></i> Artifacts</a>
	</li>
	<li>
		<a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
	</li>
	</ul>
 </nav>
</aside>
<script>
	function logout(){
		$('.form_logout').submit();
	}
</script>