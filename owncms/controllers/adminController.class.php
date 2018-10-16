<?php

/**
 * admin控制器
 */

pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
class adminController extends baseController
{
    public function __construct()
    {

    }

    /**
     * 初始化处理
     */
    public function init()
    {
        $adminModel = new adminModel();
        $adminId = $adminModel->isLogin();
        if ($adminId){

            header('location:/index.php?m=admin&c=admin&e=index');
            exit();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }

    /**
     * 登录处理
     */
    public function login()
    {
        if(isset($_POST['login_type']) && !empty($_POST['login_type'])){

            $type = safe_replace($_POST['login_type']);

            if ($type!='admin'){
                header('location:'.LOGIN_ADMIN);
                exit();
            }
            $adminName = safe_replace($_POST['uname']);
            $password = safe_replace($_POST['pwd']);
            $code = safe_replace($_POST['code']);
            $adminModel = new adminModel();
            $adminID = $adminModel->checkAdmin($adminName, $password, $code);

            if ($adminID){
                $_SESSION['adminid'.HASH_IP] = $adminID['admin_id'];
                $_SESSION['adminname'.HASH_IP] = $adminName ;

                header('location:/index.php?m=admin&c=admin&e=index');
                exit();
            }else{
                header('location:'.LOGIN_ADMIN);
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
                header('location:'.LOGIN_ADMIN);
                exit();
            }

        }

    }


    public function index()
    {
        $view = viewEngine();
        $adminModel = new adminModel();
        $level = $adminModel->getLevel($_SESSION['adminname'.HASH_IP]);
        $m = safe_replace($_GET['m']);
        $c = safe_replace($_GET['c']);
        $view -> assign('m', $m);
        $view -> assign('c', $c);
        $view->assign('level','');
        $view->display('login_index.php');
    }

    /**
     * 安全退出处理
     */
    public function loginOut(){
        $adminModel = new adminModel();

        if(!$adminModel->loginOut()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

}