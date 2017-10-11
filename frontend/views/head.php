<?php if ( ! defined('basepath')) exit('No direct script access allowed');

$csslist=array('style.css');
if (isset($extracss)) {
	if (is_array($extracss)) {
		foreach ($extracss as $ca) {
			$csslist[]=$ca;
		}
	}
}
?>
<!DOCTYPE HTML>
<html class="<?php if (isset($pagestyle)) echo $pagestyle; ?>">
<head>
    <script type="text/javascript">
        var site_url='<?php echo burl(); ?>';
    </script>
    <?php 
    foreach ($csslist as $cssf) {
    	?>
    	<link rel="stylesheet" href="<?php echo burl(frontend); ?>/css/<?php echo $cssf; ?>">
    	<?php 
    }
    ?>
    
    <title>site name<?php echo (isset($title))?(' - ' . $title):(''); ?></title>
</head>
<body>
