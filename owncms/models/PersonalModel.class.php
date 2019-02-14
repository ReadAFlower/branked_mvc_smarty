<?php

/**
 * user MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('industryModel','models/',0);
pcBase::loadSysClass('keywordsModel','models/',0);
class PersonalModel extends baseModel
{
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);
        $this->tableName = 'user';
        parent::__construct();
    }


    /**
     * 通过用户名获取用户ID
     * @param $userName
     * @return array
     */
    private function getUserName($userName){
        $userName = safe_replace($userName);
        $userRes = $this -> db -> get_one('user_id,password,level,status,lastloginip,lastlogintime', $this->tableName, 'user_name = "'.$userName.'"');

        return $userRes;
    }

    /**
     * 用户名和密码验证
     * @param $name
     * @param $password
     * @return array|bool|string
     */
    public function checkManager($name, $password){

        $name = safe_replace($name);
        $password = safe_replace($password);
        $UserRes = $this->getUserName($name);
        if ($UserRes['status']!='启用'){
            $_SESSION['messagesTips']='此用户账号已被停用';
            return false;
        }
        if ($UserRes['password']==$this->createPWD($password)){
            $_SESSION['userid'.HASH_IP] = $UserRes['user_id'];
            $_SESSION['userLevel'.HASH_IP] = $UserRes['level'];
            $_SESSION['lastloginip'.HASH_IP] = $UserRes['lastloginip'];
            $_SESSION['lastlogintime'.HASH_IP] = $UserRes['lastlogintime'];
            $_SESSION['username'.HASH_IP] = $name;
            $loginUpdate['lastlogintime'] = time();
            $loginUpdate['lastloginip'] = $_SERVER['REMOTE_ADDR'];
            $updateInfo = $this->updateInfo($UserRes['user_id'],$loginUpdate);

            return $updateInfo;
        }else{
            return false;
        }
    }

    /**
     * userName 和密码验证
     * @param $userName
     * @param $password
     * @return array|bool|string
     */
    public function checkUser($userName, $password, $code){

        if (checkCode($code)){
            return $this->checkManager($userName, $password);
        }else{
            return false;
        }

    }

    /**
     * 验证是否登录过
     * @param $userID
     * @param $username
     * @return bool|string
     */
    public function checkLogin($userID,$username){
        $userID = intval($userID);
        $username = safe_replace($username);
        $checkID = $this->getUserName($username);

        if ($userID==$checkID['user_id']){
            return $userID;
        }else{
            return false;
        }

    }

    /**
     * 登录验证
     * @param $adminName
     * @param $password
     * @param $code
     * @return array|bool|string
     */
    public function checkAdmin($userName,$password,$code){
        if (checkCode($code)){
            return $this->checkManager($userName, $password);
        }else{
            return false;
        }

    }

    /**
     * 是否登录验证
     * @return array|bool|string
     */
    public function isLogin(){

        if(isset($_SESSION['userid'.HASH_IP]) && !empty($_SESSION['userid'.HASH_IP])){

            $userId = $_SESSION['userid'.HASH_IP];
            $userName = $_SESSION['username'.HASH_IP];

            $userId = $this->checkLogin($userId,$userName);

            if ($userId){
                return $userId;
            }else{
                return false;
            }

        }else{
            return false;
        }

    }

    /**
     * 获取用户基本信息
     * @param $userID
     */
    public function getUserInfo($userID)
    {
        $userID = intval($userID);
        if (!$userID) return false;
        $sql = 'select * from
                    (select
                      A.*,B.type_id,B.type_name 
                    from 
                      (select 
                        u.user_id,u.type_num,u.user_name,u.level,u.status,u.created_at,u.email,u.phone,r.url_id,r.url_name,r.word_num,r.word_branked_num 
                      from 
                        user as u 
                      left join 
                        user_url as r 
                      on 
                        u.user_id=r.user_id
                      ) as A 
                    left join 
                      industry_type as B 
                    on 
                    A.type_num=B.type_num) as e where e.user_id = '.$userID;


        $res = $this->db->query($sql);
        while ($arr = mysqli_fetch_assoc($res)){
            $userInfo = $arr;
        }

        return $userInfo;
    }

    /**
     * 获取登录用户下的所有关键词
     * @param $userID
     * @param int $pageNow
     * @param int $pageSize
     * @return bool
     */
    public function getWordList($userID,$pageNow = 1,$pageSize = 10)
    {
        $userID = intval($userID);
        if (!$userID) return false;
        $pageNow = $pageSize ? intval($pageNow) : 1;
        $pageSize = $pageSize ? intval($pageSize) : 10;
        $urlModel = new urlModel();
        $urlID = $urlModel->getOneUrlRes($userID);
        $keywordsModel = new keywordsModel();
        $wordList = $keywordsModel->getWordsRes($urlID['url_id'],$pageNow,$pageSize);

        return $wordList;

    }

    /**
     * 安全退出
     */
    public function loginOut(){
        if (isset($_SESSION) && !empty($_SESSION)){
            session_destroy();
            unset($_SESSION);
        }
        return $this->isLogin();
    }

    public function updateInfo($userID,$data)
    {
        if (!$data) return false;
        $where = ' user_id= '.intval($userID);
        $res = $this->db->update($data, $this->tableName, $where);
        if ($res){
            return true;
        }else{
            return false;
        }
    }

}