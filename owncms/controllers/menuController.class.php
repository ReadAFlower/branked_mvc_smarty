<?php


pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('menuModel','models/',0);
class menuController extends baseController
{
    public function __construct()
    {
        $adminModel = new adminModel();
        $che = $adminModel->isLogin();
        if (!$che){
            $_SESSION['messagesUrl']=LOGIN_ADMIN;
            adminModel::showMessages();
            exit();
        }
        $this->urlList['menuIndex'] = '/index.php?m=menu&c=menu&e=menuIndex';
        $this->urlList['menuList'] = '/index.php?m=menu&c=menu&e=menuList';
    }

    public function init(){

        if(@$_SESSION['adminid'.HASH_IP] && @$_SESSION['adminname'.HASH_IP]){
           return $this->menuIndex();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

    public function menuIndex(){
        $view = viewEngine();
//        $view->display('login_index.tpl');
        $view->display('menu/index.tpl');
        exit();
    }

    public function menuList(){
        $adminModel = new adminModel();
        $level = $adminModel->getLevel();
        $menuModel = new menuModel();
        $menuList = $menuModel->getMenuList($level);
        if ($menuList){
            $view = viewEngine();
            $view ->assign('menuList', $menuList);
//            $view->display('login_index.tpl');
            $view->display('menu/list.tpl');
            exit();
        }else{
            $_SESSION['messagesTips'] = '菜单列表获取失败';
            @$_SESSION['messagesUrl'] = $this->urlList['goback'];
            menuModel::showMessages();
            exit();
        }
    }

    public function menuAdd(){
        $view = viewEngine();

       if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
           $adminModel = new adminModel();
           $level = $adminModel->getLevel();
           $menuModel = new menuModel();
           $menuList = $menuModel->getMenuList($level);
           $allLevel = $adminModel->getAllLevel();

           $view->assign('menuList', $menuList);
           $view->assign('allLevel', $allLevel);

           if (isset($_POST) && !empty($_POST)){
               $res = $this->add();

               if ($res){
                   $menu = $menuModel->getMenuList($_SESSION['level'.HASH_IP]);
                   $fp = fopen(SMARTY_DIR.'cache/menu.txt','w+');
                   fwrite($fp,json_encode($menu));
                   fclose($fp);
                   $menuAddRes = '菜单添加成功';
               }else{
                   $menuAddRes = '菜单添加失败';
               }

           }else{
//               $view->display('login_index.tpl');
               $view->display('menu/add.tpl');
               exit();
           }
       }else{
           $menuAddRes = '无权限执行此操作，请联系站长获取权限';
       }

        @$_SESSION['messagesTips'] = $menuAddRes;
        @$_SESSION['messagesUrl'] = $this->urlList['menuList'];
        menuModel::showMessages();
        exit();

    }

    /**
     * 删除菜单
     * @return string
     */
    public function menuDel(){
        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
            if (isset($_GET['id']) && !empty($_GET['id'])){
                $id = safe_replace($_GET['id']);
                $menuModel = new menuModel();
                $res = $menuModel->deleteMenu($id);
                if ($res){
//                    $menuModel->updateSessionMenuList($_SESSION['level'.HASH_IP]);
                    $view = viewEngine();
                    $menu = $menuModel->getMenuList($_SESSION['level'.HASH_IP]);
                    $fp = fopen(SMARTY_DIR.'cache/menu.txt','w+');
                    fwrite($fp,json_encode($menu));
                    fclose($fp);

                    $menuDelRes = '删除成功';
                }else{
                    $menuDelRes = '删除失败';
                }

            }else{
                $menuDelRes = '错误请求，请重新操作';
            }
        }else{
            $menuDelRes = '无权限执行此操作，请联系站长获取权限';
        }
        @$_SESSION['messagesTips'] = $menuDelRes;
        @$_SESSION['messagesUrl'] = $this->urlList['menuList'];
        menuModel::showMessages();
        exit();
    }

    public function menuUpdate(){
        $menuModel = new menuModel();
        $view = viewEngine();

        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
            if (isset($_POST['cn_name']) && !empty($_POST['cn_name'])){

                $data = null;
                $updateID = intval($_POST['update_id']);
                $data['parentID'] = safe_replace($_POST['parentID']);
                $data['zh_name'] = safe_replace($_POST['zh_name']);
                $data['cn_name'] = safe_replace($_POST['cn_name']);
                $data['level'] = intval($_POST['level'])==0 || intval($_POST['level'])==1 ? intval($_POST['level'])+1 : 3;
                $data['m'] = safe_replace($_POST['m']);
                $data['c'] = safe_replace($_POST['c']);
                $data['e'] = safe_replace($_POST['e']);
                $data['data'] = safe_replace($_POST['data']);
                $data['ismenu'] = intval($_POST['ismenu']);

                $res = $menuModel->updateMenu($data,$updateID);
                if($res){
                    $menu = $menuModel->getMenuList($_SESSION['level'.HASH_IP]);
                    $fp = fopen(SMARTY_DIR.'cache/menu.txt','w+');
                    fwrite($fp,json_encode($menu));
                    fclose($fp);
                    $menuUpdateRes = '修改成功';
                }else{
                    $menuUpdateRes = '修改失败';
                }
            }elseif (isset($_GET['id']) && !empty($_GET['id'])){
                $id = intval($_GET['id']);
                $adminModel = new adminModel();
                $level = $adminModel->getLevel();
                $menuList = $menuModel->getMenuList($level);
                $view->assign('menuList', $menuList);

                $allLevel = $menuModel->getEnum('level');
                $view->assign('allLevel', $allLevel);

                $updateData = $menuModel->getOne($id);
                if($updateData){
                    $view->assign('userID', $id);
                    $view->assign('updateDate', $updateData);
//                    $view->display('login_index.tpl');
                    $view->display('menu/update.tpl');
                    exit();
                }else{
                    $menuUpdateRes = '菜单信息获取失败';
                }

            }else{
                $menuUpdateRes = '请求错误，请重新操作';
            }
        }else{
            $menuUpdateRes = '无权限执行此操作，请联系站长获取权限';
        }

        @$_SESSION['messagesTips'] = $menuUpdateRes;
        @$_SESSION['messagesUrl'] = $this->urlList['menuList'];
        menuModel::showMessages();
        exit();
    }

    //添加菜单子方法
    public function add(){
        if($_POST['cn_name']){
            $data = null;
            $data['parentID'] = safe_replace($_POST['parentID']);
            $data['zh_name'] = safe_replace($_POST['zh_name']) ? safe_replace($_POST['zh_name']) : 2;
            $data['cn_name'] = safe_replace($_POST['cn_name']) ? safe_replace($_POST['cn_name']) : 3;
            $data['level'] = intval($_POST['level'])==0 || intval($_POST['level'])==1 ? intval($_POST['level'])+1 : 3;
            $data['m'] = safe_replace($_POST['m']);
            $data['c'] = safe_replace($_POST['c']);
            $data['e'] = safe_replace($_POST['e']);
            $data['data'] = safe_replace($_POST['data']);
            if (empty($data['level'])){

            }
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