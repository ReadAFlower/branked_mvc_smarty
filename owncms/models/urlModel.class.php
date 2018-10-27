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
     * 添加关键词后更新更新关键词数，以及排名关键词数
     * @param $data
     * @param $urlID
     */
    public function addWordUpdate($data,$urlID)
    {
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
}