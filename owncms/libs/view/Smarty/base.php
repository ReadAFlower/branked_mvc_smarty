<?php
	
	define('SMARTY_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

	$config=[
		'template_dir'=>str_replace('\\', '/', SMARTY_DIR).'templates/',
		'compile_dir'=>str_replace('\\', '/', SMARTY_DIR).'templates_c/',
		'config_dir'=>str_replace('\\', '/', SMARTY_DIR).'configs/',
		'cache_dir'=>str_replace('\\', '/', SMARTY_DIR).'cache/',
		'left_delimiter'=>'{',
		'right_delimiter'=>'}'
	]
?>