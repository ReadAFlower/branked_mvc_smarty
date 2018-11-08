<?php

/**
 * keywords MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('userModel','models/',0);
pcBase::loadSysClass('branked','',0);
pcBase::loadSysClass('historyModel','models/',0);
class keywordsModel extends baseModel
{
    public static $num;
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);
        $this->tableName = 'keywords';
        self::$num = 0;
        parent::__construct();
    }

    /**
     * 添加关键词信息
     * @param $data  一维数组
     */
    private function addWords($data)
    {

        if(!is_numeric($data['url_id'])) return false;
        $wordArr = explode(',', $data['word_name']);
        $wordLen = count($wordArr);

        if ($wordLen>1){
            for ($i=0;$i<$wordLen;$i++){
                $stemp = null;
                $stemp['url_id'] = $data['url_id'];
                $stemp['word_name'] = $wordArr[$i];
                $this->addWords($stemp);
            }
        }elseif($wordLen==1){

            $checkRes = $this->checkWord($data['word_name'],$data['url_id']);

            if ($checkRes)return false;


            $urlModel = new urlModel();
            $urlRes = $urlModel->getOneUrl($data['url_id']);
            $data['word_status'] = 2;
            $branked = new branked($data['word_name'],$urlRes['url_name']);

            $data['word_branked'] =$branked->getBranked();
            $data['created_at'] = time();
            $data['updated_at'] = time();
            $res = $this->db->insert($data, $this->tableName);

            if ($res){
                $data['user_url']['word_num'] = 'word_num + 1';
                if (!empty($data['word_branked'])) $data['user_url']['word_branked_num'] = 'word_branked_num + 1';
                $data['user_url']['word_list'] ='CONCAT("'.$data['word_name'].'",",",IFNULL(word_list," "))';

                $userUlrUpdateRes = $urlModel->addWordUpdate($data['user_url'],$data['url_id']);
                if ($userUlrUpdateRes){
                    self::$num+=1;
                }else{
                    return false;
                }
            }else{
                return false;
            }

        }

        return self::$num;

    }

    /**
     * 获取同一URL下的关键词数量
     * @param $urlID    urlID
     */
    public function getWordNum($urlID)
    {
        if (is_numeric($urlID)){
            $urlID = intval(ceil($urlID));
            $where = ' url_id = '.$urlID;
            $res = $this->db->select('count(*)', $this->tableName, $where);
            if ($res[0]){
                return $res[0]['count(*)'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 获取同一URL下的排名关键词数量
     * @param $urlID    urlID
     */
    public function getBrankedWordNum($urlID)
    {
        if (is_numeric($urlID)){
            $urlID = intval(ceil($urlID));

            $where = ' url_id = '.$urlID;
            $res = $this->db->select('count(*)',$this->tableName,$where);
            if ($res){
                return $res['count(*)'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 获取关键词信息
     * @param $urlID
     * @param $pageNow
     * @param $pageSize
     * @return bool
     */
    public function getWordsRes($urlID, $pageNow=1, $pageSize = 10)
    {
        $pageNow = $pageSize ? intval($pageNow) : 1;
        $pageSize = $pageSize ? intval($pageSize) : 10;
        $urlID = intval($urlID);
        if (!$urlID) return false;
        $data = 'word_id,word_name,word_status,updated_at,word_branked';
        $where = ' url_id = '.$urlID;
        $orderBy = ' word_id desc ';
        $limit = ' '.(intval($pageNow)-1)*$pageSize.','.$pageSize.' ';
        $res = $this->db->select($data, $this->tableName, $where, $limit, $orderBy);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 检验关键词数量是否到上限
     * @param $data     准备添加的关键词数据
     * @param $userID   用户ID
     */
    public function checkWordNum($data,$userID)
    {

        $userID = intval($userID);
        if (!$userID) return false;
        $userModel = new userModel();
        $limitNum = $userModel->wordLimitNum($userID);

        $urlModel = new urlModel();
        $oldNum = $urlModel->getWordNum($userID);

        $addWordNum = count(array_filter(explode(',', $data['word_name'])));

        if (($oldNum+$addWordNum)>$limitNum){
            return false;
        }else{
            $addRes = $this->addWords($data);

            return $addRes;
        }
    }

    /**
     * 检查同一域名下是否已存在关键词
     * @param $word
     * @param $urlID
     */
    public function checkWord($word,$urlID)
    {

        if (intval($urlID)) return false;
        $word = safe_replace($word);
        $where = ' url_id = '.$urlID.' and word_name = "'.$word.'"';

        $res = $this->db->get_one('word_id', $this->tableName, $where);

        if ($res){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 获取所有关键词列表
     * @return bool
     */
    public function getAllkeywords($pageNow, $pageSize = 10)
    {
        if (!intval($pageNow)) return false;

//        $sql = 'select * from
//                  (select d.user_name,c.* from
//                    (select a.url_id,a.user_id,a.url_name,b.word_id,b.word_name,b.word_status,b.word_branked,b.updated_at from user_url as a left join keywords as b on a.url_id = b.url_id) as c
//                    left join user as d on c.user_id = d.user_id order by updated_at desc) as e where word_id > 0 order by word_id desc limit '.(intval($pageNow)-1)*$pageSize.','.$pageSize.' ';

        $sql = 'select * from 
                  (select d.user_name,c.* from 
                    (select a.url_id,a.user_id,a.url_name,b.word_id,b.word_name,b.word_status,b.word_branked,b.updated_at from user_url as a, keywords as b where a.url_id = b.url_id) as c, 
                     user as d where c.user_id = d.user_id order by updated_at desc) as e where word_id > 0 order by word_id desc limit '.(intval($pageNow)-1)*$pageSize.','.$pageSize.' ';
        $res = $this->db->query($sql);


        if ($res){
            $allKeywords = $this->db->resToArr($res);

            return $allKeywords;
        }else{
            return false;
        }
    }

    /**
     * 关键词删除
     * @param $wordID   关键词ID
     */
    public function wordDel($wordID,$isBranked)
    {

        $wordID = intval(safe_replace($wordID));
        $isBranked = intval(safe_replace($isBranked));
        if (!$wordID) return false;
        $wordRes = $this->getWord($wordID);

        if ($isBranked!=1 && $isBranked!=2) return false;
        if ($isBranked==1 && !$wordRes['word_branked']) return false;
        if ($isBranked==2 && $wordRes['word_branked']) return false;

        $where = ' word_id = '.$wordID;

        //先删历史数据
        $historyModel = new historyModel();
        $histRes = $historyModel->delWords($wordID);
        if (!$histRes) return false;
        $res = $this->db->delete($this->tableName, $where);



        //然后删关键词数据
        if ($res){
            $data = null;
            $data['word_num'] = "word_num-1";
            if($isBranked==1){
                if (intval($wordRes['word_branked_num'])<1){
                    $data['word_branked_num'] = '0';
                }else{
                    $data['word_branked_num'] = "word_branked_num-1";
                }

            }
            $data['word_list'] = "replace(word_list,'".$wordRes['word_name'].",','')";
            $urlModel = new urlModel();
            $urlUpdateRes = $urlModel->addWordUpdate($data, $wordRes['url_id']);

            if ($urlUpdateRes){
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }

    }

    /**
     * 通过userID删除该用户下的所有关键词非历史数据关键词
     * @param $urlID
     */
    public function keywordsDelByUserID($urlID)
    {
        if (!intval($urlID)) return false;
        $urlID = intval(safe_replace($urlID));

        $where = ' url_id = '.$urlID;

        $res = $this->db->delete($this->tableName, $where);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 通过id获取关键词信息
     * @param $wordID
     */
    public function getWord($wordID){
        if (!intval($wordID)) return false;
        $wordID = intval(safe_replace($wordID));
        $where = ' word_id = '.$wordID;
        $res = $this->db->get_one('*', $this->tableName, $where);
        if ($res){
            $urlModel = new urlModel();
            $urlRes = $urlModel->getOneUrl($res['url_id']);

            if ($urlRes){
               $wordRes = array_merge($res,$urlRes);
            }
        }

        if (isset($wordRes) && !empty($wordRes)){
            return $wordRes;
        }else{
            return false;
        }
    }

    /**
     * 更新关键词排名
     * @param $data
     * @param $wordID
     * @return bool
     */
    public function updateWordBr($data, $wordID){

        if (!isset($data['keywords']) || empty($data['keywords'])) return false;

        $wordID = intval(safe_replace($wordID));
        if(!$wordID) return false;
        $where = ' word_id = '.$wordID;
        $keywordsRes = $this->db->update($data['keywords'],$this->tableName, $where);

        //排名从无到有
        if (isset($data['url']['word_branked_num'])){
            $urlModel = new urlModel();
            $urlRes = $urlModel->addWordUpdate($data['url'],$data['url_id']);
            if (!$urlRes) return false;
        }

        //是否有history数组数据
        if (isset($data['history']) && !empty($data['history'])){
            $history = new historyModel();
            $keywordsHistoryRes = $history->insertWord($data['history']);
        }

        if ($keywordsRes){
            if (isset($data['history']) && !$keywordsHistoryRes){
                return false;
            }
            return true;
        }else{
            return false;
        }

    }

    /**
     * 监控状态修改
     * @param $wordID
     * @param $data
     */
    public function wordStatus($wordID, $data)
    {
        $wordID = intval($wordID);
        if (!$wordID || !$data) return false;
        $where = ' word_id = '.$wordID;

        $wordInfo = $this->getWord($wordID);
        $wordStatus = $this->getEnum('word_status');

        if ($wordStatus[intval($data['word_status'])]==$wordInfo['word_status']){
            return false;
        }else{
            $urlData = null;
            if ($data['word_status']==1){
                // 2>> 1    取消监控
                if ($wordInfo['word_branked']){
                    if ($wordInfo['word_branked_num']<1){
                        $urlData['word_branked_num'] = '0';
                    }else{
                        $urlData['word_branked_num'] = 'word_branked_num-1';
                    }

                }
            }elseif($data['word_status']==2){
                //1 >> 2    恢复监控
                $branked = new branked($wordInfo['word_name'],$wordInfo['url_name']);
                $newBr = $branked->getBranked();
                if ($newBr){
                    if (strtotime(date('Y-m-d',$wordInfo['updated_at']))<strtotime(date('Y-m-d',time())) && $wordInfo['word_branked']){
                        $historyDate = null;
                        $historyDate['word_id'] = $wordInfo['word_id'];
                        $historyDate['url_id'] = $wordInfo['url_id'];
                        $historyDate['old_branked'] = $wordInfo['word_branked'];
                        $historyDate['updated_at'] = $wordInfo['updated_at'];
                    }
                    $urlData['word_branked_num'] = 'word_branked_num+1';
                }
            }else{
                return false;
            }
        }

        //恢复监控后将历史数据插入历史数据表
        if (@$historyDate){
            $histtoryModel = new historyModel();
            $updateHistory = $histtoryModel->insertWord($historyDate);
            if (!$updateHistory) return false;
        }

        if (@$urlData){
            $urlModel = new urlModel();
            $updateUrl = $urlModel->addWordUpdate($urlData,$wordInfo['url_id']);
            if (!$updateUrl) return false;
        }
        $data['updated_at'] = time();
        $res = $this->db->update($data, $this->tableName, $where);
        if ($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取关键词总数量
     * @return bool
     */
    public function nums()
    {
        $res = $this->db->select('count(*)', $this->tableName);
        if ($res[0]['count(*)']){
            return $res[0]['count(*)'];
        }else{
            return false;
        }
    }

    /**
     * 获取所有符合条件关键词id
     */
    public function getAllIds($where)
    {

        $res = $this->db->select('word_id',$this->tableName,safe_replace($where));

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**记录数查询统计
     * @param $column   单字段
     * @param $where
     */
    public function countColumn($column,$where)
    {
        if (!preg_match('/[a-zA-Z]{1}[\w_]/',safe_replace($column))) return false;
        $column = safe_replace($column);

        $data = 'count('.$column.')';
        $res = $this->db->select($data,$this->tableName,$where);
        if ($res[0]){
            return $res[0][$data];
        }else{
            return false;
        }
    }
}