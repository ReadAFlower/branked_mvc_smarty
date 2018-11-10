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
        if(@!$_SESSION['adminid'.HASH_IP] || @!$_SESSION['adminname'.HASH_IP]){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
        $this->urlList['userList'] = '/index.php?m=user&c=user&e=userList';
        $this->urlList['userIndex'] = '/index.php?m=user&c=user&e=userIndex';
    }

    public function init()
    {
        if(@$_SESSION['adminid'.HASH_IP] && @$_SESSION['adminname'.HASH_IP]){
            return $this->userIndex();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }

    public function userIndex()
    {
        $view = viewEngine();

//        $view->display('login_index.tpl');
        $view->display('user/index.tpl');
        exit();
    }

    /**
     * 用户列表
     */
    public function userList()
    {
        $view = viewEngine();
        $adminModel = new adminModel();

        if (isset($_GET['pages']) && !empty($_GET['pages'])){
            $pageNow = $_GET['pages'] > 1 ? intval(safe_replace($_GET['pages'])) : 1;
        }else{
            $pageNow = 1;
        }

        $pageData['nums'] = $adminModel->nums();

        if ($pageData['nums']){
            $pageData['nums'] = intval($pageData['nums']);
            $pageData['urlRule'] = 'index.php?m=user&c=user&e=userList';
            $viewPages = new viewPages($pageData);
            $pagesNav = $viewPages->getPageNav($pageNow);

            $level = $adminModel->getLevel();
            $levelNum = $adminModel->levelToNum($level);
            $userModel = new userModel();
            $userList = $userModel->getUserList($levelNum,$pageNow);

            if ($userList){
                $view->assign('userList',$userList);
                $view->assign('pagesNav',$pagesNav);
//                $view->display('login_index.tpl');
                $view->display('user/list.tpl');
                exit();
            }else{
                $userListRes = '用户数据获取失败';
            }

        }else{
            $userListRes = '暂无用户数据';

        }

        @$_SESSION['messagesTips'] = $userListRes;
        @$_SESSION['messagesUrl'] = $this->urlList['goback'];
        userModel::showMessages();
        exit();
    }

    /**
     * 添加用户
     */
    public function userAdd()
    {
        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
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

                if ($res==1){
                    $userAddRes = '用户添加成功';
                }else{
                    if ($res==2){
                        $userAddRes = '同名管理员或用户已存在';
                    }else{
                        $userAddRes = '用户添加失败';
                    }

                }
            }else{
//                $view->display('login_index.tpl');
                $view->display('user/add.tpl');
                exit();
            }
        }else{
            $userAddRes = '暂无权限';
        }

        @$_SESSION['messagesTips'] = $userAddRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        userModel::showMessages();
        exit();
    }

    /**
     * user信息修改
     */
    public function userUpdate()
    {
        $view = viewEngine();
        $userModel = new userModel();

        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
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
//                    $view->display('login_index.tpl');
                    $view->display('user/update.tpl');
                    exit();
                }else{
                    $userUpdateRes = '用户信息获取失败';
                }
            }else{
                $userUpdateRes = '非法请求';
            }
        }else{
            $userUpdateRes = '暂无权限';
        }

        @$_SESSION['messagesTips'] = $userUpdateRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        userModel::showMessages();
        exit();
    }

    /**
     * 删除用户
     */
    public function userDel()
    {
        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
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
        }else{
            $userDelRes = '暂无权限';
        }

        @$_SESSION['messagesTips'] = $userDelRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        userModel::showMessages();
        exit();
    }

}