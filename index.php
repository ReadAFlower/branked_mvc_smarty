<?php

//根目录
define('APP_PATH', str_replace('\\','/',dirname(__FILE__).DIRECTORY_SEPARATOR));

require_once APP_PATH.'owncms/base.php';

pcBase::createApp();
//require_once 'owncms/libs/view/Smarty/Smarty.class.php';
//$smarty = new Smarty();
//$smarty -> template_dir = 'owncms/libs/view/Smarty/templates/';
//var_dump($smarty->getTemplateDir());

//require_once(str_replace('\\', '/', APP_PATH).'owncms/libs/functions/globals.fun.php');
//
//require_once(str_replace('\\', '/', APP_PATH).'owncms/libs/view/Smarty/base.php');
//
//$Smarty=libs('Smarty',str_replace("\\", "/", APP_PATH).'owncms/libs/view/Smarty/',$config);
//// var_dump($Smarty->getTemplateDir());
//// exit();
//
//$Smarty->assign('title','require ok');
//$Smarty->display('index.php');
//
//use owncms\libs\classes\viewPages;
//require_once 'owncms/libs/classes/viewPages.class.php';
//
//if(isset($_GET['page']) && !empty($_GET['page'])){
//    $pageNow=$_GET['page'];
//}else{
//    $pageNow=1;
//}
//$arr=[];
//$arr['nums'] = 70;
//$arr['urlRuel'] = 'c=viewpage';
////$arr['pageNavNum'] = 11;
//$newPage= new viewPages($arr);
//
//echo $newPage->getPageNav($pageNow);

?>

