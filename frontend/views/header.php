<?php if ( ! defined('basepath')) exit('No direct script access allowed'); ?>
<div class="container">
<div id="header">
	
	<h1 class="logo">Logo</h1>

	<ul class="mainmenu">
	  <li><a href="<?php echo burl(); ?>" >Homepage</a></li>
	  <li><a href="<?php echo burl(mainControllerName.'/otherpage');?>">Other Page</a></li>
	  <?php if (User::$user!=false) { ?>
	  <li class="pull-right">User:(<?php echo User::$user->username; ?>) <a href="<?php echo burl('user/logout');?>">Logout</a></li>
	  <?php }else{ ?>
	  <li class="pull-right"><a href="<?php echo burl('user/login');?>">Login</a></li>
	  <?php } ?> 
	</ul>
	<div class="clearb"></div>

</div>

</div>