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
        $urlID = intval($urlID);
        $where = ' url_id = '.$urlID;
        if (!$urlID) return false;
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
        $wordID = intval($wordID);
        $where = ' word_id = '.$wordID;
        $orderBy = ' updated_at desc';
        if (!$wordID) return false;
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
        if (!intval($data['url_id'])) return false;
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
        if (!intval($wordID)) return false;
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
//        $where = safe_replace($where);
//        $res = $this->db->select('count(*)', $this->tableName, $where);

        $sql = ' SELECT count(*) FROM '.$this->tableName.' WHERE '.$where;
        $res = $this->db->query($sql);
        while ($arr=mysqli_fetch_assoc($res)){
            $nums = $arr['count(*)'];
        }

        if ($nums){
            return $nums;
        }else{
            return false;
        }
    }

    /**
     * 获取关键词基础信息
     * @param $wordID
     * @param $userID
     * @return array|bool
     */
    public function getWordBaseRes($wordID,$userID)
    {
        if (!intval($wordID) || !intval($userID)) return false;
        $userModel = new userModel();
        $userRes = $userModel->getOneUser(intval($userID));

        $keywordsModel = new keywordsModel();
        $wordRes = $keywordsModel->getWord(intval($wordID));

        return array_merge($userRes[0],$wordRes);


    }

    /**
     * 删除单个关键词的所有历史记录
     * @param $wordID
     */
    public function delWords($wordID)
    {
        if (intval($wordID)) $wordID = intval($wordID);
        $check = $this->db->get_one('id',$this->tableName,' word_id = '.$wordID);
        if ($check){
            $delRes = $this->db->delete($this->tableName, ' word_id = '.$wordID);
            if ($delRes){
                return $delRes;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
}