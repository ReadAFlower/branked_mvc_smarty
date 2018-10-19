<?php

/**
 * user控制器
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('userModel','models/',0);
class userController extends baseController
{
    public function __construct()
    {

    }

    public function init()
    {
        $userModel = new userModel();
        $userId = $userModel->isLogin();
        if ($userId){
            header('location:/index.php?m=user&c=user&e=index');
            exit();
        }else{
            header('location:'.LOGIN_USER);
            exit();
        }

    }

    public function login()
    {

        if(isset($_POST['login_type']) && !empty($_POST['login_type'])){

            $type = safe_replace($_POST['login_type']);

            if ($type!='user'){
                header('location:'.LOGIN_USER);
                exit();
            }
            $userName = safe_replace($_POST['uname']);
            $password = safe_replace($_POST['pwd']);
            $code = safe_replace($_POST['code']);
            $userModel = new userModel();
            $userID = $userModel->checkUser($userName, $password, $code);

            if ($userID){
                $_SESSION['userid'.HASH_IP] = $userID['user_id'];
                $_SESSION['username'.HASH_IP] = $userName ;

                header('location:/index.php?m=user&c=user&e=index');
                exit();
            }else{
                header('location:'.LOGIN_USER);
                exit();
            }

        }else{
            if (isset($_GET['dosubmit']) && !empty($_GET['dosubmit'])){
                $view = viewEngine();
                $loginType = safe_replace($_GET['dosubmit']);
                $m = safe_replace($_GET['m']);
                $c = safe_replace($_GET['c']);
                $view -> assign('loginType', $loginType);
                $view -> assign('m', $m);
                $view -> assign('c', $c);
                $view->display('login.php');
            }else{
                header('location:'.LOGIN_USER);
                exit();
            }

        }

    }


    public function userIndex()
    {
        $view = viewEngine();
        $userModel = new userModel();
        $level = $userModel->getLevel($_SESSION['username'.HASH_IP]);
        $view->assign('level','');
        $m = safe_replace($_GET['m']);
        $c = safe_replace($_GET['c']);
        $view -> assign('m', $m);
        $view -> assign('c', $c);
        $view->display('login_index.php');
    }

    public function loginOut(){
        $userModel = new userModel();

        if(!$userModel->loginOut()){
            header('location:'.LOGIN_USER);
            exit();
        }
    }

}