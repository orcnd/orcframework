<?php  if ( ! defined('basepath')) exit('No direct script access allowed');
$this->load->view('head',array('title'=>'login','pagestyle'=>'loginpagestyle'));
?>
<div id="loginform">
    <h1>Login Form</h1>
    <form role="form" method="post">
        <div class="form-group">
            <input class="form-control" placeholder="username" name="username" type="text" autofocus>
        </div>
        <div class="form-group">
            <input class="form-control" placeholder="password" name="password" type="password" value="">
        </div>
        <?php if (isset($hata)) {?><div class="alert alert-danger" role="alert"><?php echo $hata;?></div><?php } ?>

        <input type="submit" class="btn btn-success btn-block" value="login" />
    </form>
</div>
<?php
$this->load->view('foot');
