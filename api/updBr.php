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
}elseif(isset($_GET['doUpdate']) && $_GET['doUpdate']){
    if (isset($_GET['all'])){
        $flgNum = null;     //计数器
        $TableName = safe_replace($_GET['all']);
        $keywordsModel = new keywordsModel();
        $where = ' word_status = 2';
        $wordIDArr = $keywordsModel->getAllIds($where);
        if($wordIDArr){
            $idNum = count($wordIDArr);
            foreach ($wordIDArr as $item){
                $newBr = updateBr($item['word_id']);
                if ($newBr) $flgNum++;
            }
        }

        if($flgNum){
          echo '此次更新共'.count($wordIDArr).'个关键词，其中'.$flgNum.'个关键词更新成功，'.(count($wordIDArr)-intval($flgNum)).'个关键词更新失败或无排名';
        }else{
            echo '关键词更新失败';
            exit();
        }

    }else{
        exit();
    }
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
        $data['url_id'] = $wordRes['url_id'];

        if ($newBr && !$wordRes['word_branked']){
            $data['url']['word_branked_num'] = 'word_branked_num+1';
        }

        if (!$newBr && $wordRes['word_branked']){
            $data['url']['word_branked_num'] = 'word_branked_num-1';
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