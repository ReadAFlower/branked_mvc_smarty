<?php

/**
 * keywords MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('userModel','models/',0);
pcBase::loadSysClass('branked','',0);
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

        if(!is_numeric($data['url_id']))return false;
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

            //开启事务
            //$this->db->query('SET AUTOCOMMIT = 0');
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

//            if ($res && $userUlrUpdateRes){
//                $this->db->query('COMMIT');
//               self::$num+=1;
//            }else{
//                $this->db->query('ROLLBACK');
//            }

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
            $where = ' url_id = '.$urlID.' and update_at > '.strtotime(date('Y-m-d',time()));
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
     * 获取同一URL下当天的排名关键词数量
     * @param $urlID    urlID
     */
    public function getBrankedWordNum($urlID)
    {
        if (is_numeric($urlID)){
            $urlID = intval(ceil($urlID));

            $where = ' url_id = '.$urlID.' and word_branked>0 and updated_at > '.strtotime(date('Y-m-d',time()));
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
     * 获取当天关键词信息
     * @param $urlID
     * @return bool
     */
    public function getWordsRes($urlID)
    {
        if (is_numeric($urlID)){
            $urlID = intval(ceil($urlID));
            $data = 'word_id,word_name,word_status,updated_at,word_branked';
            $where = ' url_id = '.$urlID.' and updated_at > '.strtotime(date('Y-m-d',time()));
            $res = $this->db->select($data,$this->tableName,$where);

            if ($res){
                return $res;
            }else{
                return false;
            }
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

        $userID = intval(safe_replace($userID));
        $userModel = new userModel();
        $limitNum = $userModel->wordLimitNum($userID);

        $urlModel = new urlModel();
        $oldNum = $urlModel->getWordNum($userID);

        $addWordNum = count(explode(',', $data['word_name']));

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

        //$urlID = intval(safe_replace($urlID));
        $word = safe_replace($word);
        $where = ' url_id = '.$urlID.' word_name = "'.$word.'"';

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
    public function getAllkeywords()
    {
        $sql = 'select * from 
                  (select d.user_name,c.* from 
                    (select a.url_id,a.user_id,a.url_name,b.word_id,b.word_name,b.word_status,b.word_branked,b.updated_at from user_url as a left join keywords as b on a.url_id = b.url_id) as c 
                    left join user as d on c.user_id = d.user_id order by updated_at desc) as e 
                group by e.word_name';

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
        $wordRes = $this->getWord($wordID);

        $where = ' word_id = '.$wordID;
        $res = $this->db->delete($this->tableName, $where);
        if ($res){
            $data = null;
            $data['word_num'] = "word_num-1";
            if($isBranked==1){
                $data['word_branked_num'] = "word_branked_num-1";
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
     * 通过id获取关键词
     * @param $wordID
     */
    public function getWord($wordID){
        $wordID = intval(safe_replace($wordID));
        $where = ' word_id = '.$wordID;
        $res = $this->db->get_one('*', $this->tableName, $where);

        return $res;
    }
}