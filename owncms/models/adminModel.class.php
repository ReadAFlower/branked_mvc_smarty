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
     * 管理员名验证
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
        $adminPassword = $this->createPWD($adminPassword);

        $adminId = $this -> db -> get_one('admin_id', $this->tableName, 'password = "'.$adminPassword.'"');

        return $adminId;
    }

    /**
     * 管理员登录
     * 用户名和密码一致性验证
     * @param $name
     * @param $password
     * @return array|bool|string
     */
    public function checkManager($name, $password){

        $name = safe_replace($name);
        $password = safe_replace($password);
        $nameAdminID = $this->getAdminName($name);
        $passwordAdminID = $this->gerAdminPassword($password);

        if ($nameAdminID==$passwordAdminID){
            $_SESSION['adminid'.HASH_IP] = $nameAdminID['admin_id'];
            $_SESSION['adminname'.HASH_IP] = $name ;

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

    /**
     * 管理员权限查询
     * @param $adminID
     * @return bool
     */
    public function getLevel(){
        $adminID = $this->getAdminId();
        if ($adminID){
            $level = $this->db->get_one('level', $this->tableName, 'admin_id = '.intval($adminID).' and status = 2');

            if ($level){
                return $level['level'];
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    /**
     * 权限转数字
     * @param $level
     * @return int
     */
    public function levelToNum($level){
        switch ($level){
            case '超级管理员';
                $num = 0;
                break;
            case '高级管理员':
                $num = 1;
                break;
            case '普通管理员':
                $num = 2;
                break;
            default:
                $num = 3;
                break;
        }

        return intval($num);
    }

    /**
     * 获取管理员ID
     * @return bool
     */
    public function getAdminId(){

        if (isset($_SESSION['adminid'.HASH_IP]) && !empty($_SESSION['adminid'.HASH_IP])){
            $adminID = safe_replace($_SESSION['adminid'.HASH_IP]);
            $adminId =  $this->db->get_one('admin_id', $this->tableName, 'admin_id = '.intval($adminID).' and status = 2');

            if($adminId){
                return $adminId['admin_id'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 只有超级管理员才有权限获取
     * 获取管理员列表
     */
    public function getManagerList()
    {
        $level = $this->getLevel();
        $levelNum = intval($this->levelToNum($level));

        if ($levelNum===0){
            $data = 'admin_id,admin_name,level,status,lastlogintime,created_at,email,phone';
            $managerList = $this->db->select($data,$this->tableName);

            return $managerList;
        }else{
            return false;
        }

    }

    /**
     * 获取单条管理员信息
     * @param $id
     */
    public function getManagerRes($id)
    {
        $level = $this->getLevel();
        $levelNum = intval($this->levelToNum($level));
        if ($levelNum===0){
            if (is_numeric($id)){
                $id = intval(ceil($id));
                $data = 'admin_id,admin_name,level,status,email,phone';
                $where = ' admin_id = '.$id;
                $managerRes = $this->db->select($data, $this->tableName, $where);
                return $managerRes[0];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 添加管理员
     * @param $data
     * @return bool
     */
    public function addManager($data)
    {
        if (isset($data) && !empty($data)){
            $data['status'] = 2;
            $data['created_at'] = time();
            $data['updated_at'] = time();
            $data['password'] = $this->createPWD($data['password']);
            $data['level'] = $data['level']+1;

            $res = $this->db->insert($data, $this->tableName);
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
     * 删除管理员
     * @param $id
     * @return bool
     */
    public function managerDel($id){
        if (is_numeric($id)){
            $id = intval(ceil($id));
            $where = 'admin_id = '.$id;
            $res = $this->db->delete($this->tableName, $where);
            if ($res){
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function managerUpdate($data,$id)
    {
        if (is_numeric($id)){
            $id = intval(ceil($id));
            $data['updated_at'] = time();
            $data['level'] = $data['level']+1;
            if (isset($data['password']) && !empty($data['password'])) $data['password'] = $this->createPWD($data['password']);
            $where = ' admin_id = '.$id;
            $res = $this->db->update($data,$this->tableName,$where);

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
     * 权限等级列表
     * @return array
     */
    public function getAllLevel(){
        $sql = 'show columns from '.$this->tableName.' like "level"';
        $res = $this -> db -> query($sql);
        while ($arr = mysqli_fetch_assoc($res)){
            $resLevel = $arr['Type'];
        }
        $resLevel = str_replace(["enum",")","(","'"],'',$resLevel);
        $level = explode(',',$resLevel);

        return $level;
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
}