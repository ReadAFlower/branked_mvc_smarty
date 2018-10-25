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
        $adminModel = new adminModel();
        $view = viewEngine();
        $allLevel = $adminModel->getAllLevel();
        $view->assign('allLevel', $allLevel);
        if (isset($_POST['admin_name']) && !empty($_POST['admin_name'])) {
            $data = null;
            $data['admin_name'] = safe_replace($_POST['admin_name']);
            $data['password'] = safe_replace($_POST['password']);
            $data['email'] = safe_replace($_POST['email']);
            $data['phone'] = safe_replace($_POST['phone']);
            $data['level'] = safe_replace($_POST['level']);

            $res = $adminModel->addManager($data);

            if ($res){
                $addManagerRes = '管理员添加成功';
            }else{
                $addManagerRes = '管理员添加失败';
            }
            $view->assign('addManagerRes', $addManagerRes);
            $view->display('login_index.tpl');

        }

        $view->display('login_index.tpl');
    }

    /**
     * 删除管理员
     */
    public function managerDel()
    {

        if (isset($_GET['id']) && !empty($_GET['id'])){
            $adminModel = new adminModel();
            $id = safe_replace($_GET['id']);

            $res = $adminModel->managerDel($id);
            if ($res){
                $managerDelRes = '删除成功';
            }else{
                $managerDelRes = '删除失败';
            }
            $_SESSION['managerDel'.HASH_IP] = $managerDelRes;
            header('location:/index.php?m=admin&c=admin&e=managerList');
            exit();
        }
    }

    /**
     * 修改管理员信息
     */
    public function managerUpdate()
    {
        $view = viewEngine();
        if (isset($_POST['admin_id']) && !empty($_POST['admin_id'])){
            $data = null;
            $adminId = $_POST['admin_id'];
            $data['admin_name'] = $_POST['admin_name'];
            if(!empty($_POST['password'])) $data['password'] = $_POST['password'];
            $data['status'] = $_POST['status'];
            $data['level'] = $_POST['level'];
            $data['email'] = $_POST['email'];
            $data['phone'] = $_POST['phone'];

            $adminModel = new adminModel();
            $res = $adminModel->managerUpdate($data, $adminId);

            if ($res){
                $managerUpdateRes = '管理员信息修改成功';
            }else{
                $managerUpdateRes = '管理员信息修改失败';
            }
            $view->assign('managerUpdateRes', $managerUpdateRes);
            $view->display('login_index.tpl');

        }elseif (isset($_GET['id']) && !empty($_GET['id'])){
            $id = safe_replace($_GET['id']);
            $adminModel = new adminModel();
            $managerRes = $adminModel->getManagerRes($id);
            $allLevel = $adminModel->getAllLevel();
//            echo '<pre>';
//            var_dump($managerRes);
//            exit();
            if ($managerRes){

                $view->assign('allLevel',$allLevel);
                $view->assign('managerRes',$managerRes);
                $view->display('login_index.tpl');
            }else{
                header('location:/index.php?m=admin&c=admin&e=managerList');
                exit();
            }

        }else{
            header('location:/index.php?m=admin&c=admin&e=managerList');
            exit();
        }
    }
}