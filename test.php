<html>
<header>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>排名测试</title>
</header>
<form action="" method="post">
    <input type="hidden" name="type" value="testbranked">
    <input type="text" name="testword" id="testword" placeholder="请输入关键词">
    <input type="text" name="testurl" id="testurl" placeholder="其输入匹配域名">
    <input type="submit" name="sub" value="查询">
</form>
</html>

<?php
//require_once 'owncms\libs\functions\branked.fun.php';
//
//$word='竞价';
//requestWord($word);
require_once 'owncms\libs\classes\branked.class.php';
use \owncms\libs\classes\branked;

if (isset($_POST) && !empty($_POST)){
    if (isset($_POST['type']) && $_POST['type'] == 'testbranked'){
        if(!empty($_POST['testword']) && !empty($_POST['testurl'])){

            $word = $_POST['testword'];
            $url = $_POST['testurl'];
            $branked = new branked($word, $url);

            echo $branked->getBranked();
        }else{
            echo '请输入查询词和域名';
        }
    }
}


