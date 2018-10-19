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

    public function addIndustry()
    {
        $industry = new industryModel();

        $view = viewEngine();

        if (isset($_POST) && !empty($_POST)){
            $data['type_name'] = $_POST['type_name'];
            $data['type_num'] = $_POST['type_num'];
            $res = $industry->addIndustryList($data);

            if ($res){
                $industryAddRes = '添加行业成功';
            }else{
                $industryAddRes = '添加行业失败';
            }

            $view->assign('industryAddRes', $industryAddRes);
        }

        $view->display('login_index.tpl');
    }

    public function industryDel(){
        if (isset($_GET['id']) && !empty($_GET['id'])){
            $typeId = $_GET['id'];
            $industryModel = new industryModel();
            $where = 'type_id = '.intval($typeId);

            $res = $industryModel->delIndustryList($where);

            if ($res){
                $industryDelRes = '删除成功';
            }else{
                $industryDelRes = '删除失败';
            }
            $_SESSION['industryDelRes'.HASH_IP] = $industryDelRes;
            header('location:/index.php?m=industry&c=industry&e=industryList');
            exit();


        }
    }
}