<?php
defined('IN_OWNCMS') or exit('No permission resources.');
pcBase::loadSysClass('BaiduController','controllers/',0);
if (!session_start()){
    session_start();
}

if (isset($_POST['resID']) || isset($_GET['resID'])){
    $resID = $_GET['resID'];

    $search = new BaiduController();
    $resInfo = $search->getFile($resID);

    if ($resInfo){
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename={$_SESSION['word'.HASH_IP]}.xls");

        echo '<table  border="1" cellspacing="0" cellpadding="0" style="font-size:16px;">';
        echo '<tr style="height:60px;margin:10px 20px;line-height:60px;background:#ffff00;font-size:24px;font-weight:bold;"><td width="60">序号</td><td width="400">标题</td><td width="500">简介</td><td width="300">快照</td></tr>';
        $len =  count($resInfo);
        for ($i=0;$i<$len;$i++){
            $n = $i+1;
            echo '<tr><td>'.$n.'</td><td>'.$resInfo[$i]['title'].'</td><td>'.$resInfo[$i]['description'].'</td><td>'.$resInfo[$i]['snapshot'].'</td></tr>';
        }
        echo '</table>';

    }else{
        echo '';

    }

}else{
    echo '<script type="text/javascript">window.history.go(-1)</script>';

}
