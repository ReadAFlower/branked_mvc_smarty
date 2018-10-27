<?php
defined('IN_OWNCMS') or exit('No permission resources.');

pcBase::loadSysClass('keywordsModel','models/',0);
pcBase::loadSysClass('branked','',0);

if (isset($_GET['wordID']) && !empty($_GET['wordID'])){
    $wordID = intval(safe_replace($_GET['wordID']));

    $newBr = updateBr($wordID);
    $arr = null;
    $arr['newtime'] = date('Y-m-d',time());
    if ($newBr){
        $arr['newBR'] = $newBr;
    }else{
        $arr['newBR'] = '';
    }
    echo json_encode($arr);
}

function updateBr($wordID){
    $wordID = intval(safe_replace($wordID));
    $keywordsModel = new keywordsModel();

    $wordRes = $keywordsModel->getWord($wordID);

    //只对监控中的关键词更新排名
    if ($wordRes && trim($wordRes['word_status'])=='监控'){

        $branked = new branked($wordRes['word_name'],$wordRes['url_name']);
        $newBr = $branked->getBranked();

        $data = null;
        switch ($newBr){
            case !$wordRes['word_branked'] && $newBr :
                $data['url']['word_branked_num'] = 'word_branked_num+1';
                break;
            case $wordRes['word_branked'] && !$newBr :
                $data['url']['word_branked_num'] = 'word_branked_num-1';
                break;
            default:break;
        }

        $data['keywords']['word_branked'] = $newBr;
        $data['keywords']['updated_at'] = time();
        $isToday = strtotime(date('Y-m-d',$wordRes['updated_at'])) < strtotime(date('Y-m-d',time())) ? true : false;

        //今天以前的有排名数据才记录历史
        if ($isToday && $wordRes['word_branked']){
            $data['history']['word_id'] = $wordRes['word_id'];
            $data['history']['old_branked'] = $wordRes['word_branked'];
            $data['history']['updated_at'] = $wordRes['updated_at'];
            $data['history']['url_id'] = $wordRes['url_id'];
        }

        $updateWordRes = $keywordsModel->updateWordBr($data,$wordID);


        if ($updateWordRes){
            $updBrRes = $newBr;
        }else{
            $updBrRes = false;
        }

    }else{
        $updBrRes = false;
    }

    return $updBrRes;

}