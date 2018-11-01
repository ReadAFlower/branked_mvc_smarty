<?php

pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('industryModel','models/',0);
class industryController extends baseController
{
    public function __construct()
    {
        $adminModel = new adminModel();

        if(!$adminModel->isLogin()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }

    public function industryIndex()
    {
        $view = viewEngine();
        $view->display('login_index.tpl');
        exit();
    }

    /**
     * 行业分类
     */
    public function industryList()
    {
        $industryModel = new industryModel();
        $industryList = $industryModel->getIndustryList();
        $view = viewEngine();
        if ($industryList){
            $view->assign('industryList', $industryList);
        }else{
            $industryListRes = '获取行业分类信息失败';
            $view->assign('industryListRes', $industryListRes);
        }

        $view->display('login_index.tpl');
        exit();

    }

    /**
     * 添加行业
     */
    public function addIndustry()
    {
        $industry = new industryModel();
        $view = viewEngine();

        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
            if (isset($_POST['type_num']) && !empty($_POST['type_num'])){
                $data['type_name'] = $_POST['type_name'];
                $data['type_num'] = $_POST['type_num'];
                $res = $industry->addIndustryList($data);

                if ($res){
                    $industryAddRes = '添加行业成功';
                }else{
                    $industryAddRes = '添加行业失败';
                }
            }
        }else{
            $industryAddRes = '无权限执行此操作，请联系站长获取权限';
        }
        if (isset($industryAddRes)){
            $view->assign('industryAddRes', $industryAddRes);
        }
        $view->display('login_index.tpl');
    }

    /**
     * 删除行业
     */
    public function industryDel(){
        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
            if (isset($_GET['id']) && !empty($_GET['id'])){
                $typeId = $_GET['id'];
                $industryModel = new industryModel();
                $where = 'type_id = '.intval($typeId);

                $res = $industryModel->delIndustry($where);

                if ($res){
                    $industryDelRes = '删除成功';
                }else{
                    $industryDelRes = '删除失败';
                }

            }else{
                $industryDelRes = '错误请求，请重新操作';
            }
        }else{
            $industryDelRes = '无权限执行此操作，请联系站长获取权限';
        }
        $_SESSION['industryDelRes'.HASH_IP] = $industryDelRes;
        header('location:/index.php?m=industry&c=industry&e=industryList');
        exit();
    }

    /**
     * 行业信息修改
     */
    public function industryUpdate()
    {
        $industryModel = new industryModel();
        $view = viewEngine();
        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
            if(isset($_POST['type_id']) && !empty($_POST['type_id'])){
                $data = null;
                $typeID = intval(safe_replace($_POST['type_id']));
                $data['type_name'] = safe_replace($_POST['type_name']);
                $data['type_num'] = intval(safe_replace($_POST['type_num']));

                $res = $industryModel->updateIndustryList($data, $typeID);

                if ($res){
                    $industryUpdateRes = '行业信息修改成功';
                }else{
                    $industryUpdateRes = '行业信息修改失败';
                }

            }elseif(isset($_GET['id']) && !empty($_GET['id'])){
                $typeID = intval(safe_replace($_GET['id']));

                $industryRes = $industryModel->getIndustryRes($typeID);
                if ($industryRes){
                    $view->assign('industryRes',$industryRes);
                }else{
                    $industryUpdateRes = '行业信息获取失败';
                }
            }
        }else{
            $industryUpdateRes = '无权限执行此操作，请联系站长获取权限';
        }

        if (isset($industryUpdateRes) && !empty($industryUpdateRes)){
            $view->assign('industryUpdateRes',$industryUpdateRes);
        }
        $view->display('login_index.tpl');
    }
}