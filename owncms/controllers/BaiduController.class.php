<?php

/**
 * 百度搜索处理
 * Class BaiduControllers
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('branked','',0);
class BaiduController extends baseController
{
    public function __construct()
    {
        if(@!$_SESSION['adminid'.HASH_IP] || @!$_SESSION['adminname'.HASH_IP]){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

    /**
     * 初始化处理
     */
    public function init()
    {
        if (@$_SESSION['adminid'.HASH_IP] && @$_SESSION['adminname'.HASH_IP]){

            header('location:/index.php?m=admin&c=admin&e=index');
            exit();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }

    public function baiduIndex()
    {
        $this->init();
        exit();
    }

    public function getSearch()
    {
        $view = viewEngine();
        if (isset($_POST['word']) && !empty($_POST['word'])){
            $word = safe_replace($_POST['word']);
            if (isset($_SESSION['word'.HASH_IP])) unset($_SESSION['word'.HASH_IP]);
            $baidu = new branked($word);
            $tempInfo = $baidu->getResInfo();
            if (!isset($tempInfo[0][0])){
                $_SESSION['messagesTips']='没有搜索结果';
                $_SESSION['messagesUrl']=$this->urlList['goback'];
                adminModel::showMessages();
                exit();
            }
            $searchInfo = [];
            $len = count($tempInfo);
            for ($i=0;$i<$len;$i++){
                $searchInfo = array_merge($searchInfo,$tempInfo[$i]);
            }
            $fp = fopen(SMARTY_DIR.'cache/searchInfo.txt','w+');
            fwrite($fp,json_encode($searchInfo));
            fclose($fp);
            $_SESSION['word'.HASH_IP] = $word;
            $view->assign('word' ,$word);
            $view->assign('searchInfo', $searchInfo);
            $view->display('baidu/resInfo.tpl');
            exit();
        }else{
            if (isset($_SESSION['word'.HASH_IP])){
                $tempInfo = file_get_contents(SMARTY_DIR.'cache/searchInfo.txt','r');
                $searchInfo = json_decode($tempInfo,true);
                $view->assign('word' ,$_SESSION['word'.HASH_IP]);
                $view->assign('searchInfo', $searchInfo);
                $view->display('baidu/resInfo.tpl');
                exit();
            }
            $view->display('baidu/search.tpl');
            exit();
        }
    }

    public function getFile($arrID){
        $view = viewEngine();
        $rs = file_get_contents(SMARTY_DIR.'cache/searchInfo.txt','r');
        $tempInfo = json_decode($rs,true);
        $resInfo = [];
        if (is_array($arrID)){
            $len = count($arrID);
            $flg = false;
            for ($i=0;$i<$len;$i++){
                if (isset($tempInfo[$arrID[$i]])){
                    $resInfo[] = $tempInfo[$arrID[$i]];
                    $flg = true;
                }
            }
            if (!$flg){
                $resInfo = '';
            }
        }elseif(safe_replace($arrID) == 'all'){
            $resInfo = $tempInfo;
        }else{
            $resInfo = '';
        }

        return $resInfo;
    }

}