<?php
//require_once 'owncms\libs\functions\branked.fun.php';
//
//$word='竞价';
//requestWord($word);
require_once 'owncms\libs\classes\branked.class.php';
use \owncms\libs\classes\branked;
$word = 'seo';
$url = 'www.xiaohaiseo.com';
$branked = new branked($word, $url);

var_dump($branked->getBranked());
