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

}