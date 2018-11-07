<?php

/**
 * url MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('adminModel','models/',0);
class urlModel extends baseModel
{
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);
        $this->tableName = 'user_url';
        parent::__construct();
    }

    /**
     * 通过关联用户ID获取URL信息
     * @param $userID
     * @return bool
     */
    public function getOneUrlRes($userID)
    {
        if (is_numeric($userID)){
            $userID = intval(ceil($userID));
            $data = 'url_id,url_name,word_num,word_branked_num';
            $where = ' user_id = '.$userID;
            $res = $this->db->get_one($data, $this->tableName, $where);

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
     * 通过urlID获取URL信息
     * @param $urlID
     * @return bool
     */
    public function getOneUrl($urlID)
    {
        if (is_numeric($urlID)){
            $urlID = intval(ceil($urlID));
            $data = 'url_name,word_num,word_branked_num';
            $where = ' url_id = '.$urlID;
            $res = $this->db->get_one($data, $this->tableName, $where);

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
     * 插入用户域名信息
     * @param $data     一维数组
     */
    public function addUrl($data)
    {
        if (!$data['url_name']) return false;
        $res = $this->db->insert($data, $this->tableName);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 更新url表信息
     * @param $data
     * @param $urlID
     */
    public function updateUrl($data,$urlID)
    {
        if (!intval($urlID) || !$data) return false;

        $where = ' url_id = '.intval(safe_replace($urlID));
        $res = $this->db->update($data, $this->tableName,$where);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 通过userID更新信息
     * @param $data
     * @param $userID
     * @return bool
     */
    public function updateUrlByUserID($data,$userID)
    {
        if (!intval($userID) || !$data) return false;
        $where = ' user_id = '.intval(safe_replace($userID));
        $res = $this->db->update($data, $this->tableName,$where);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 通过userID删除域名信息
     * @param $urlID
     * @return bool
     */
    public function urlDelByUserID($urlID)
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
     * 获取关键词数数据
     * @param $userID
     * @return bool
     */
    public function getWordNum($userID)
    {
        if (is_numeric($userID)){
            $userID = intval(safe_replace($userID));
            $data = 'word_num';
            $where = ' user_id = '.$userID;
            $res = $this->db->get_one($data, $this->tableName, $where);

            if ($res){
                return $res['word_num'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 1、添加关键词后更新更新关键词数，以及排名关键词数
     * 2、排名更新后的数据更新
     * @param $data
     * @param $urlID
     */
    public function addWordUpdate($data,$urlID)
    {
        if (!$data || !intval($urlID)) return false;
        $urlID = intval(safe_replace($urlID));


        $sql = ' update '.$this->tableName.' set ';
        foreach ($data as $key => $value){
            if($value!=end($data)){
                $sql .= $key.' = '.$value.', ';
            }else{
                $sql .= $key.' = '.$value.' ';
            }

        }
        $sql .= ' where url_id = '.$urlID;
        //$res = $this->db->update($data, $this->tableName, $where);

        $res = $this->db->query($sql);
        if ($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 重新统计关键词数以及关键词排名数
     * @param $userID
     */
    public function reCheck($userID)
    {
        $userID = intval($userID) ? intval($userID) : '';
        if (empty($userID)) return false;
        $urlInfo = $this->getOneUrlRes($userID);

        $keywordsModel = new keywordsModel();
        $getWordNum = $keywordsModel->getWordNum($urlInfo['url_id']);
        $getBrankedNum = $keywordsModel->countColumn('word_branked','url_id = '.$urlInfo['url_id'].' and word_status>1 and length(word_branked)>5');

        $data= null;
        $data['word_num'] = $getWordNum;
        $data['word_branked_num'] =$getBrankedNum;
        $res = $this->db->update($data, $this->tableName,' user_id = '.$userID);
        if ($res){
            $resDate = null;
            $resDate['wordNum'] = $getWordNum;
            $resDate['brankedNum'] = $getBrankedNum;
            return $resDate;
        }else{
            return false;
        }

    }
}