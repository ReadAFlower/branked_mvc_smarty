<?php


/**
 * url控制器
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('userController','controllers/',0);
class urlController extends baseController
{
    public function __construct()
    {
        $userController = new userController();
        $adminModel = new adminModel();
        $che = $adminModel->isLogin();
        if (!$che){
            $_SESSION['messagesUrl']=LOGIN_ADMIN;
            adminModel::showMessages();
            exit();
        }
        $this->urlList['userList'] = $userController->urlList['userList'];
    }

    public function init()
    {
        if(@$_SESSION['adminid'.HASH_IP] && @$_SESSION['adminname'.HASH_IP]){
            $view = viewEngine();
//            $view->display('login_index.tpl');
            $view->display('url/index.tpl');
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
            $data['user_id'] = intval($_POST['user_id']);
            $data['url_name'] = safe_replace($_POST['url_name']);

            $urlModel = new urlModel();
            $res = $urlModel->addUrl($data);
            if ($res){
                $urlAddRes = '域名添加成功';
            }else{
                $urlAddRes = '域名添加失败';
            }

        }elseif (isset($_GET['userID']) && !empty($_GET['userID'])){
            $userRes['userID'] = intval($_GET['userID']);
            $userRes['userName'] = safe_replace($_GET['userName']);
            $view->assign('userRes',$userRes);
//            $view->display('login_index.tpl');
            $view->display('url/add.tpl');
            exit();
        }else{
            $urlAddRes = '非法操作';
        }

        @$_SESSION['messagesTips'] = $urlAddRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        urlModel::showMessages();
        exit();
    }

    //重新统计
    public function recount()
    {
        $urlModel = new urlModel();
        if (@$_GET['userID']){
            $userID = intval($_GET['userID']);
            $res = $urlModel->reCheck($userID);

            if ($res){
//                echo json_encode($res);
//                exit();
                return json_encode($res);
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
}