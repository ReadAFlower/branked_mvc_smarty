<?php


pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('userModel','models/',0);
pcBase::loadSysClass('keywordsModel','models/',0);
class historyModel extends baseModel
{
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);
        $this->tableName = 'history_branked';
        parent::__construct();
    }

    /**
     * 批量删除用户下的所有关键词历史数据
     * @param $urlID
     */
    public function HistoryDelByUserID($urlID)
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
     * 获取关键词最新一条历史数据
     * @param $wordID
     */
    public function getNewWordRes($wordID)
    {
        $wordID = intval(safe_replace($wordID));
        $where = ' word_id = '.$wordID;
        $orderBy = ' updated_at desc';
        $newWordRes = $this->db->get_one('*',$this->tableName,$where,$orderBy);

        if ($newWordRes){
            return $newWordRes;
        }else{
            return false;
        }
    }

    /**
     * 插入旧数据
     * @param $data
     */
    public function insertWord($data)
    {
        $res = $this->db->insert($data,$this->tableName);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 默认获取30天内历史数据
     * @param $wordID
     * @param int $pageNow
     * @param int $pageSize
     * @param string $smallTime
     * @return bool
     */
    public function getHistoryWordList($wordID, $pageNow=1, $smallTime='', $pageSize = 10)
    {
        if (empty($smallTime)){
            $smallTime = strtotime(date('Y-m-d',time()))-3600*24*30;
        }else{
            $smallTime = strtotime(date('Y-m-d',time()))-3600*24*intval($smallTime);
        }
        $wordID = intval($wordID);
        $data = '*';
        $where = ' word_id = '.$wordID.' and updated_at > '.$smallTime;
        $orderBy = ' id desc';
        $pageNow = intval($pageNow);
        $limit= ' '.$pageSize*($pageNow-1).','.$pageSize.' ';
        $res = $this->db->select($data, $this->tableName, $where, $limit, $orderBy);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 获取总记录数
     * @param $where
     * @return bool
     */
    public function nums($where)
    {
        $where = safe_replace($where);

        $res = $this->db->select('count(*)', $this->tableName, $where);

        if ($res[0]['count(*)']){
            return $res[0]['count(*)'];
        }else{
            return false;
        }
    }

    public function getWordBaseRes($wordID,$userID)
    {
        $userModel = new userModel();
        $userRes = $userModel->getOneUser(intval($userID));

        $keywordsModel = new keywordsModel();
        $wordRes = $keywordsModel->getWord(intval($wordID));

        return array_merge($userRes[0],$wordRes);


    }
}