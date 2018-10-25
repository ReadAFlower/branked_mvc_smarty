<?php


pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('menuModel','models/',0);
class menuController extends baseController
{
    public function __construct()
    {
        $adminModel = new adminModel();

        if(!$adminModel->isLogin()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }

    public function init(){

        $adminModel = new adminModel();
        if($adminModel->isLogin()){
           return $this->menuIndex();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

    public function menuIndex(){
        //$this->menuList();
        $view = viewEngine();
        $view->display('login_index.tpl');
        exit();
    }

    public function menuList(){
        $adminModel = new adminModel();
        $level = $adminModel->getLevel();
        $menuModel = new menuModel();
        $menuList = $menuModel->getMenuList($level);
        $view = viewEngine();
        $view ->assign('menuList', $menuList);
        $view->display('login_index.tpl');
    }

    public function menuAdd(){

        $adminModel = new adminModel();
        $level = $adminModel->getLevel();
        $menuModel = new menuModel();
        $menuList = $menuModel->getMenuList($level);
        $allLevel = $adminModel->getAllLevel();
        $view = viewEngine();
        $view->assign('menuList', $menuList);
        $view->assign('allLevel', $allLevel);

        if (isset($_POST) && !empty($_POST)){
            $res = $this->add();

            if ($res){
                $menuAddRes = '菜单添加成功';
            }else{
                $menuAddRes = '菜单添加失败';
            }
            $view->assign('menuAddRes', $menuAddRes);
        }

        $view->display('login_index.tpl');
    }

    /**
     * 删除菜单
     * @return string
     */
    public function menuDel(){
        if (isset($_GET['id']) && !empty($_GET['id'])){
            $id = safe_replace($_GET['id']);
            $menuModel = new menuModel();
            $res = $menuModel->deleteMenu($id);
            if ($res){
                $menuModel->updateSessionMenuList($_SESSION['level'.HASH_IP]);
                $menuDelRes = '删除成功';
            }else{
                $menuDelRes = '删除失败';
            }
            $_SESSION['menuDelRes'.HASH_IP] = $menuDelRes;
            header('location:/index.php?m=menu&c=menu&e=menuList');
            exit();
        }else{
            header('location:/index.php?m=menu&c=menu&e=menuList');
            exit();
        }
    }

    public function menuUpdate(){
        $menuModel = new menuModel();
        $view = viewEngine();

        if (isset($_POST['cn_name']) && !empty($_POST['cn_name'])){

            $data = null;
            $updateID = safe_replace($_POST['update_id']);
            $data['parentID'] = safe_replace($_POST['parentID']);
            $data['zh_name'] = safe_replace($_POST['zh_name']);
            $data['cn_name'] = safe_replace($_POST['cn_name']);
            $data['level'] = intval(safe_replace($_POST['level']))+1;
            $data['m'] = safe_replace($_POST['m']);
            $data['c'] = safe_replace($_POST['c']);
            $data['e'] = safe_replace($_POST['e']);
            $data['data'] = safe_replace($_POST['data']);

            $res = $menuModel->updateMenu($data,$updateID);

            if($res){
                $menuUpdateRes = '修改成功';
            }else{
                $menuUpdateRes = '修改失败';
            }

            $view->assign('menuUpdateRes', $menuUpdateRes);

            $view->display('login_index.tpl');

        }elseif (isset($_GET['id']) && !empty($_GET['id'])){

            $id = intval($_GET['id']);

            $adminModel = new adminModel();
            $level = $adminModel->getLevel();
            $menuList = $menuModel->getMenuList($level);
            $view->assign('menuList', $menuList);

            $allLevel = $adminModel->getAllLevel();
            $view->assign('allLevel', $allLevel);

            $updateData = $menuModel->getOne($id);

            if($updateData){
                $view->assign('updateDate', $updateData);

                $view->display('login_index.tpl');
            }else{
                header('location:/index.php?m=menu&c=menu&e=menuList');
                exit();
            }

        }else{
            header('location:/index.php?m=menu&c=menu&e=menuList');
            exit();
        }
    }

    //添加菜单
    public function add(){
        if($_POST['cn_name']){
            $data = null;
            $data['parentID'] = safe_replace($_POST['parentID']);
            $data['zh_name'] = safe_replace($_POST['zh_name']);
            $data['cn_name'] = safe_replace($_POST['cn_name']);
            $data['level'] = intval(safe_replace($_POST['level']))+1;
            $data['m'] = safe_replace($_POST['m']);
            $data['c'] = safe_replace($_POST['c']);
            $data['e'] = safe_replace($_POST['e']);
            $data['data'] = safe_replace($_POST['data']);

            $menuModel = new menuModel();
            $menuID = $menuModel->addMenu($data);

            if ($menuID){
                $menuModel->updateSessionMenuList($_SESSION['level'.HASH_IP]);
                return $menuID;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

}