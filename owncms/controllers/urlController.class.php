<?php


/**
 * url控制器
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
class urlController extends baseController
{
    public function __construct()
    {
        $adminModel = new adminModel();
        $userController = new userController();
        if(!$adminModel->isLogin()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
        $this->urlList['userList'] = $userController->urlList['userList'];
    }

    public function init()
    {
        $adminModel = new adminModel();
        if($adminModel->isLogin()){
            $view = viewEngine();
            $view->display('login_index.tpl');
            exit();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }

    public function addURL()
    {
        $view = viewEngine();
        if (isset($_POST['user_id']) && !empty($_POST['user_id'])){
            $data['user_id'] = safe_replace($_POST['user_id']);
            $data['url_name'] = safe_replace($_POST['url_name']);

            $urlModel = new urlModel();
            $res = $urlModel->addUrl($data);
            if ($res){
                $urlAddRes = '域名添加成功';
            }else{
                $urlAddRes = '域名添加失败';
            }

        }elseif (isset($_GET['userID']) && !empty($_GET['userID'])){
            $userRes['userID'] = safe_replace($_GET['userID']);
            $userRes['userName'] = safe_replace($_GET['userName']);
            $view->assign('userRes',$userRes);
            $view->display('login_index.tpl');
            exit();
        }else{
            $urlAddRes = '非法操作';
        }

        @$_SESSION['messagesTips'] = $urlAddRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        urlModel::showMessages();
        exit();
    }
}