<?php

/**
 * user控制器
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('userModel','models/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('industryModel','models/',0);
class userController extends baseController
{
    public function __construct()
    {
        $adminModel = new adminModel();

        if(!$adminModel->isLogin()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

    public function init()
    {
        $adminModel = new adminModel();
        if($adminModel->isLogin()){
            return $this->userIndex();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }

    public function userIndex()
    {
        $view = viewEngine();
        $view->display('login_index.tpl');
        exit();
    }

    /**
     * 用户列表
     */
    public function userList()
    {
        $adminModel = new adminModel();
        $level = $adminModel->getLevel();
        $levelNum = $adminModel->levelToNum($level);
        $userModel = new userModel();
        $userList = $userModel->getUserList($levelNum);

        $view = viewEngine();

        if ($userList){
            $view->assign('userList', $userList);
        }else{
            $userListRes = '获取用户列表信息失败';
            $view->assign('userListRes', $userListRes);
        }

        $view->display('login_index.tpl');
    }

    /**
     * 添加用户
     */
    public function userAdd()
    {
        $view = viewEngine();

        $industryModel = new industryModel();
        $industryList = $industryModel->getIndustryList();

        $userModel = new userModel();
        $userLevel = $userModel->getAllLevel();

        $view->assign('industryList', $industryList);
        $view->assign('userLevel', $userLevel);
        if (isset($_POST['user_name']) && !empty($_POST['user_name'])){
            $data = null;
            $data['user_name'] = safe_replace($_POST['user_name']);
            $data['type_num'] = safe_replace($_POST['type_num']);
            $data['level'] = (isset($_POST['level']) && !empty($_POST['level'])) ? safe_replace($_POST['level']) : 3;
            $data['password'] = safe_replace($_POST['password']);
            $data['email'] = safe_replace($_POST['email']);
            $data['phone'] = safe_replace($_POST['phone']);

            $res = $userModel->addUser($data);
            if ($res){
                $userAddRes = '用户添加成功';
            }else{
                $userAddRes = '用户添加失败';
            }
            $view->assign('userAddRes', $userAddRes);
            $view->display('login_index.tpl');
        }

        $view->display('login_index.tpl');
    }

    /**
     * user信息修改
     */
    public function userUpdate()
    {
        $view = viewEngine();
        $userModel = new userModel();
        if (isset($_POST['user_id']) && !empty($_POST['user_id'])){
            $data = null;
            $userID = intval(safe_replace($_POST['user_id']));
            $data['user']['user_name'] = safe_replace($_POST['user_name']);
            $data['user']['type_num'] = safe_replace($_POST['type_num']);
            $data['user']['level'] = intval(safe_replace($_POST['level']));
            if(isset($_POST['password']) && !empty($_POST['password'])) $data['user']['password'] = $_POST['password'];
            $data['user']['email'] = safe_replace($_POST['email']);
            $data['user']['phone'] = safe_replace($_POST['phone']);
            $data['url']['url_name'] = safe_replace($_POST['url_name']);
            $data['user']['status'] = intval(safe_replace($_POST['status']));
            $updateRes = $userModel->userUpdate($data,$userID);

            if ($updateRes){
                $userUpdateRes = '用户信息修改成功';
            }else{
                $userUpdateRes = '用户信息修改失败';
            }

        }elseif (isset($_GET['userID']) && !empty($_GET['userID'])){
            $userID = intval(safe_replace($_GET['userID']));
            $userRes = $userModel->getOneUser($userID);

            $industryModel = new industryModel();
            $industryList = $industryModel->getIndustryList();

            $userLevel = $userModel->getAllLevel();
            if($userRes){
                $view->assign('userLevel', $userLevel);
                $view->assign('industryList', $industryList);
                $view->assign('userRes', $userRes['0']);
            }else{
                $userUpdateRes = '用户信息获取失败';
            }
        }else{
            $userUpdateRes = '非法请求';
        }

        if (isset($userUpdateRes) && !empty($userUpdateRes)){
            $view->assign('userUpdateRes',$userUpdateRes);
        }
        $view->display('login_index.tpl');
    }

    /**
     * 删除用户
     */
    public function userDel()
    {
        if (isset($_GET['userID']) && !empty($_GET['userID'])){
            $userID = intval(safe_replace($_GET['userID']));
            $userModel = new userModel();
            $res = $userModel->userDel($userID);

            if ($res){
                $userDelRes = '用户删除成功';
            }else{
                $userDelRes = '用户删除失败';
            }

        }else{
            $userDelRes = '非法操作';
        }

        $_SESSION['userDelRes'.HASH_IP] = $userDelRes;
        header('location:/index.php?m=user&c=user&e=userList');
        exit();

    }
}