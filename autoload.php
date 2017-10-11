<?php if ( ! defined('basepath')) exit('No direct script access allowed');

//base load
$hdir=backend . '/base';
$kl=scandir($hdir);
foreach ($kl as $k) {
	if (file_exists($hdir.'/'.$k)) {
		if (strpos($k,'.php')>-1) {
			require($hdir . '/'.$k);
		}
	}
}

//helpers
$hdir=backend . '/helpers';
$kl=scandir($hdir);
foreach ($kl as $k) {
	if (file_exists($hdir.'/'.$k)) {
		if (strpos($k,'.php')>-1) {
			require($hdir . '/'.$k);
		}
	}
}

User::init();
Controller::init();
