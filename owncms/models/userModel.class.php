<?php

/**
 * user MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('keywordsModel','models/',0);
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
     * @return bool
     */
    public function getUserList($level)
    {
        if (is_numeric($level)){
            $level = intval(ceil($level))+1;
            $data = 'user_id,type_num,user_name,level,status,email,phone,created_at';
            $where = ' level >= '.$level;
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

    public function addUser($data)
    {
        if (isset($data) && !empty($data)){

            $data['password'] = $this->createPWD($data['password']);
            $data['created_at'] = time();
            $data['updated_at'] = time();
            $resUserAdd = $this->db->insert($data, $this->tableName);

           if ($resUserAdd){
               return $resUserAdd;
           }else{

               return false;
           }

        }else{
            return false;
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
            $userID = intval(safe_replace($userID));
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
}