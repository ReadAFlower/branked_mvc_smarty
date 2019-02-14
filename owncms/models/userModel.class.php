<?php

/**
 * user MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('keywordsModel','models/',0);
pcBase::loadSysClass('historyModel','models/',0);
pcBase::loadSysClass('adminModel','models/',0);
class userModel extends baseModel
{
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);
        $this->tableName = 'user';
        parent::__construct();
    }

    /**
     * 获取用户信息
     * @param $level
     * @param $pageNow
     * @param int $pageSize
     * @return bool
     */
    public function getUserList($level, $pageNow, $pageSize = 10)
    {
        if (is_numeric($level)){
            $level = intval(ceil($level))+1;
            $data = 'user_id,type_num,user_name,level,status,email,phone,created_at';
            $where = ' level >= '.$level;
            $orderBy = ' user_id desc ';
            $limit = ' '.(intval($pageNow)-1)*$pageSize.','.$pageSize.' ';
            $res = $this->db->select($data, $this->tableName, $where, $limit, $orderBy);

            if ($res){
                //域名关键词信息
                $urlModel = new urlModel();
                $len = count($res);
                for ($i=0;$i<$len;$i++){
                    $userID = $res[$i]['user_id'];
                    $urlRes = $urlModel->getOneUrlRes($userID);
                    if ($urlRes){
                        $res[$i]['url_id'] = $urlRes['url_id'];
                        $res[$i]['url_name'] = $urlRes['url_name'];
                        $res[$i]['word_num'] = $urlRes['word_num'];
                        $res[$i]['word_branked_num'] = $urlRes['word_branked_num'];
                    }else{
                        continue;
                    }
                }
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 获取单条用户信息
     * @param $userID
     */
    public function getOneUser($userID)
    {
        if (is_numeric($userID)){
            $userID = intval($userID);
            $level = intval(ceil($userID))+1;
            $data = 'user_id,type_num,user_name,level,status,email,phone,created_at';
            $where = ' user_id = '.$userID;
            $res = $this->db->select($data,$this->tableName,$where);
            if ($res){
                //域名关键词信息
                $urlModel = new urlModel();
                $len = count($res);
                for ($i=0;$i<$len;$i++){
                    $userID = $res[$i]['user_id'];
                    $urlRes = $urlModel->getOneUrlRes($userID);
                    if ($urlRes){
                        $res[$i]['url_id'] = $urlRes['url_id'];
                        $res[$i]['url_name'] = $urlRes['url_name'];
                        $res[$i]['word_num'] = $urlRes['word_num'];
                        $res[$i]['word_branked_num'] = $urlRes['word_branked_num'];
                    }else{
                        break;
                    }
                }
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 获取所有用户等级
     */
    public function getAllLevel()
    {
        $sql = 'show columns from '.$this->tableName.' like "level"';
        $res = $this -> db -> query($sql);
        while ($arr = mysqli_fetch_assoc($res)){
            $resLevel = $arr['Type'];
        }
        $resLevel = str_replace(["enum",")","(","'"],'',$resLevel);
        $level = explode(',',$resLevel);
        $len = count($level);
        $res = null;
        for ($i=0;$i<$len;$i++){

            $res[$this->levelToNum($level[$i])]=$level[$i];
        }

        return $res;
    }

    /**
     * 权限转数字
     * @param $level
     * @return int
     */
    public function levelToNum($level){
        switch ($level){
            case '超级VIP用户';
                $num = 1;
                break;
            case '高级VIP用户':
                $num = 2;
                break;
            case '普通用户':
                $num = 3;
                break;
            default:
                $num = 4;
                break;
        }

        return intval($num);
    }

    /**
     * 添加用户
     * @param $data
     * @return bool
     */
    public function addUser($data)
    {
        if (isset($data['user_name']) && !empty($data['user_name'])){

            $userCheck = $this->checkUserName(safe_replace($data['user_name']));
            $adminModel = new adminModel();
            $adminCheck = $adminModel->checkAdminName(safe_replace($data['user_name']));
            if ($userCheck || $adminCheck) return 2;

            $data['password'] = $this->createPWD($data['password']);
            $data['created_at'] = time();
            $data['updated_at'] = time();
            $resUserAdd = $this->db->insert($data, $this->tableName);

           if ($resUserAdd){
               return 1;
           }else{

               return false;
           }

        }else{
            return false;
        }
    }

    /**
     * 修改用户信息
     * @param $data
     * @param $userID
     */
    public function userUpdate($data, $userID)
    {
        $userID = intval($userID);
        if (!$userID) return false;
        $urlModel = new urlModel();
        $isURL = $urlModel->getOneUrlRes($userID);
        $data['user']['updated_at'] = time();
        if (isset($data['user']['password']) && !empty($data['user']['password'])) $data['user']['password'] = $this->createPWD($data['user']['password']);

        $where = ' user_id = '.$userID;
        $userUpdate = $this->db->update($data['user'], $this->tableName, $where);

        if ($isURL){
            $urlRes = $urlModel->updateUrlByUserID($data['url'],$userID);
        }else{
            $data['url']['user_id'] = $userID;
            $urlRes = $urlModel->addUrl($data['url']);
        }

        if ($userUpdate && $urlRes){

            return true;
        }else{

            return false;
        }

    }

    /**
     * 删除user
     * @param $userID
     */
    public function userDel($userID)
    {
        $userID = intval($userID);
        if (!$userID) return false;
        $userWhere = ' user_id = '.$userID;
        $urlModel = new urlModel();
        $urlRes = $urlModel->getOneUrlRes($userID);

        if ($urlRes){

            //删除相关关键词，包括历史关键词信息

            $historyModel = new historyModel();
            $historyRes = $historyModel->HistoryDelByUserID($urlRes['url_id']);

            $keywordsModel = new keywordsModel();
            $keywordsRes = $keywordsModel->keywordsDelByUserID($urlRes['url_id']);

            $urlDelRes = $urlModel->urlDelByUserID($urlRes['url_id']);

            $userDel = $this->db->delete($this->tableName, $userWhere);

            if ($historyRes && $keywordsRes && $urlDelRes && $userDel){
                return true;
            }else{
                return false;
            }

        }else{
            $userDel = $this->db->delete($this->tableName, $userWhere);

            if ($userDel){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 密码加密生成
     * @param $pwd
     * @return bool|string
     */
    public function createPWD($pwd)
    {
        $pwd = safe_replace($pwd);
        $pwd = substr(md5($pwd), 12,18);
        $hash = substr('owmcms', 3, 12);
        $pwd = substr(md5($pwd.$hash),5,24);

        return $pwd;
    }

    /**
     * 获取用户可以添加的关键词数上限
     * @param $userID
     */
    public function wordLimitNum($userID)
    {
        if (is_numeric($userID)){
            $userID = intval($userID);
            $data = 'level';
            $where = ' user_id = '.$userID;
            $res = $this->db->get_one($data, $this->tableName, $where);

            if ($res){
                switch ($res['level']){
                    case '超级VIP用户':
                        $num = 9999;
                        break;
                    case '高级VIP用户':
                        $num = 50;
                        break;
                    case '普通用户':
                        $num = 10;
                        break;
                    default:
                        $num = 0;
                        break;
                }

                return $num;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * check username exist
     * @param $userName
     * @return bool
     */
    public function checkUserName($userName)
    {
        $userName = safe_replace($userName);
        if (!is_string($userName) || !$userName) return false;
        $res = $this->db->get_one('user_id',$this->tableName, ' user_name = "'.$userName.'"');

        if ($res['user_id']){
            return true;
        }else{
            return false;
        }
    }
}