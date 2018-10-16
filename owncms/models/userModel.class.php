<?php

/**
 * user MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
class userModel extends baseModel
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
        $userId = $this -> db -> get_one('user_id', $this->tableName, 'user_name = "'.$userName.'"');

        return $userId;
    }

    /**
     * @param $userPassword
     * @return array
     */
    private function gerUserPassword($userPassword){
        $userPassword = safe_replace($userPassword);
        $userPassword = substr(md5($userPassword), 12,18);
        $hash = substr('owmcms', 3, 12);
        $userPassword = substr(md5($userPassword.$hash),5,24);
        $userId = $this -> db -> get_one('user_id', $this->tableName, 'password = "'.$userPassword.'"');

        return $userId;
    }

    /**
     * 用户名和密码一致性验证
     * @param $name
     * @param $password
     * @return array|bool|string
     */
    public function checkManager($name, $password){
        $name = safe_replace($name);
        $password = safe_replace($password);
        $name = $this->getUserName($name);
        $password = $this->gerUserPassword($password);

        if ($name==$password){
            return $name;
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
     * @param $userID
     * @param $username
     * @return bool|string
     */
    public function checkLogin($userID,$username){
        $userID = safe_replace($userID);
        $username = safe_replace($username);
        $checkID = $this->getUserName($username);

        if ($userID==$checkID['user_id']){
            return $userID;
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

            $userModel = new userModel();
            $userId = $userModel->checkLogin($userId,$userName);

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
     * 安全退出
     */
    public function loginOut(){
        if (isset($_SESSION) && !empty($_SESSION)){
            session_destroy();
            unset($_SESSION);
        }

        return $this->isLogin();
    }

    public function getLevel(){

    }

}