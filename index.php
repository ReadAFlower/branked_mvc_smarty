<?php

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

require_once(str_replace('\\', '/', BASE_DIR).'owncms/libs/functions/globals.fun.php');

require_once(str_replace('\\', '/', BASE_DIR).'owncms/libs/view/Smarty/base.php');

$Smarty=libs('Smarty',str_replace("\\", "/", BASE_DIR).'owncms/libs/view/Smarty/',$config);
// var_dump($Smarty->getTemplateDir());
// exit();

$Smarty->assign('title','require ok');
$Smarty->display('index.php');

use owncms\libs\classes\viewPages;
require_once 'owncms/libs/classes/viewPages.class.php';

if(isset($_GET['page']) && !empty($_GET['page'])){
    $pageNow=$_GET['page'];
}else{
    $pageNow=1;
}
$arr=[];
$arr['nums'] = 70;
$arr['urlRuel'] = 'c=viewpage';
//$arr['pageNavNum'] = 11;
$newPage= new viewPages($arr);

echo $newPage->getPageNav($pageNow);

?>
<!--<img src="/owncms/libs/functions/codeImg.php">-->
