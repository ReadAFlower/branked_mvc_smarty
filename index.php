<?php

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

require_once(str_replace('\\', '/', BASE_DIR).'owncms/libs/functions/globals.fun.php');

require_once(str_replace('\\', '/', BASE_DIR).'owncms/libs/view/Smarty/base.php');

$Smarty=libs('Smarty',str_replace("\\", "/", BASE_DIR).'owncms/libs/view/Smarty/',$config);
// var_dump($Smarty->getTemplateDir());
// exit();

$Smarty->assign('title','require ok');
$Smarty->display('index.php');
?>
<img src="/owncms/libs/functions/codeImg.php">
