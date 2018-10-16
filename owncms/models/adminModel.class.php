<?php

/**
 * admin MODEL
 */
pcBase::loadSysClass('baseModel','models/',0);
class adminModel extends baseModel
{
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);

        $this->tableName = 'admin';
        parent::__construct();


    }

    /**
     * adminName验证
     * @param $adminName
     * @return array
     */
    private function getAdminName($adminName){
        $adminName = safe_replace($adminName);

        $adminId = $this -> db -> get_one('admin_id', $this->tableName, 'admin_name = "'.$adminName.'"');

        return $adminId;
    }

    /**
     * 密码验证
     * @param $adminPassword
     * @return array
     */
    private function gerAdminPassword($adminPassword){
        $adminPassword = safe_replace($adminPassword);
        $adminPassword = substr(md5($adminPassword), 12,18);
        $hash = substr('owmcms', 3, 12);
        $adminPassword = substr(md5($adminPassword.$hash),5,24);
        $adminId = $this -> db -> get_one('admin_id', $this->tableName, 'password = "'.$adminPassword.'"');

        return $adminId;
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
        $name = $this->getAdminName($name);
        $password = $this->gerAdminPassword($password);


        if ($name==$password){
            return $name;
        }else{
            return false;
        }
    }

    /**
     * 管理员登录验证
     * @param $adminName
     * @param $password
     * @param $code
     * @return array|bool|string
     */
    public function checkAdmin($adminName,$password,$code){
        if (checkCode($code)){
            return $this->checkManager($adminName, $password);
        }else{
            return false;
        }

    }

    /**
     * 验证是否登录过
     * @param $adminID
     * @param $adminName
     * @return bool|string
     */
    public function checkLogin($adminID,$adminName){
        $adminID = safe_replace($adminID);
        $adminName = safe_replace($adminName);
        $checkID = $this->getAdminName($adminName);

        if ($adminID==$checkID['admin_id']){
            return $adminID;
        }else{
            return false;
        }

    }

    /**
     * 是否登录验证处理
     * @return array|bool|string
     */
    public function isLogin(){

        if(isset($_SESSION['adminid'.HASH_IP]) && !empty($_SESSION['adminid'.HASH_IP])){

            $adminId = $_SESSION['adminid'.HASH_IP];
            $adminName = $_SESSION['adminname'.HASH_IP];

            $adminModel = new adminModel();
            $adminId = $adminModel->checkLogin($adminId,$adminName);

            if ($adminId){
                return $adminId;
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

    public function getLevel($adminName){

    }


}