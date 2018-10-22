<?php

/**
 * admin控制器
 */

pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('menuModel','models/',0);
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
        $adminModel = new adminModel();
        if ($adminModel->isLogin()){
            header('location:/index.php?m=admin&c=admin&e=index');
            exit();
        }else{
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
                    $view->display('login.tpl');
                }else{
                    header('location:'.LOGIN_ADMIN);
                    exit();
                }

            }
        }


    }


    public function index()
    {
        $view = viewEngine();
        $adminModel = new adminModel();
        $level = $adminModel->getLevel();
        $menuModel = new menuModel();
        $levelNum = $adminModel->levelToNum($level);
        $menu = $menuModel->getMenuList($level);
        $view -> assign('menu', $menu);
        $m = safe_replace($_GET['m']);
        $c = safe_replace($_GET['c']);
        $view -> assign('m', $m);
        $view -> assign('c', $c);
        $view->assign('level','');

        $_SESSION['level'.HASH_IP] = $levelNum;
        $_SESSION['m'.HASH_IP] = $m;
        $_SESSION['c'.HASH_IP] = $c;
        $_SESSION['menu'.HASH_IP] = $menu;
        $_SESSION['haship'] = HASH_IP;

        $view->display('login_index.tpl');
    }

    /**
     * 安全退出处理
     */
    public function loginOut()
    {
        $adminModel = new adminModel();

        if(!$adminModel->loginOut()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

    /**
     * 管理员管理
     */
    public function managerIndex()
    {
        $view = viewEngine();
        $view->display('login_index.tpl');
        exit();
    }

    /**
     * 管理员列表
     */
    public function managerList()
    {
        $adminModel = new adminModel();
        $adminList = $adminModel->getManagerList();
        $view = viewEngine();
        if ($adminList){
            $view ->assign('adminList', $adminList);
        }else{
            $adminListRes = '对不起，您无权限获取管理员列表';
            $view->assign('adminListRes',$adminListRes);
        }

        $view->display('login_index.tpl');
    }

    /**
     * 添加管理员
     */
    public function managerAdd()
    {
        $view = viewEngine();

        $view->display('login_index.tpl');
    }
}