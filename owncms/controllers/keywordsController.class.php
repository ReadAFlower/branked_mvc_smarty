<?php
/**
 * 关键词控制器
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('userModel','models/',0);
pcBase::loadSysClass('userController','controllers/',0);
class keywordsController extends baseController
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
        $userController = new userController();
        $this->urlList['userList'] = $userController->urlList['userList'];
        $this->urlList['keywordsIndex'] = '/index.php?m=keywords&c=keywords&e=keywordsIndex';
        $this->urlList['keywordsList'] = '/index.php?m=keywords&c=keywords&e=keywordsList';

    }

    public function init()
    {
        if(@$_SESSION['adminid'.HASH_IP] && @$_SESSION['adminname'.HASH_IP]){
            $view = viewEngine();
//            $view->display('login_index.tpl');
            $view->display('keywords/index.tpl');
            exit();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }
    public function keywordsIndex()
    {
        $view = viewEngine();
//        $view->display('login_index.tpl');
        $view->display('keywords/index.tpl');
        exit();
    }

    /**
     * 所有关键词列表
     */
    public function keywordsList()
    {
        $view = viewEngine();
        $keywordsModel = new keywordsModel();
        if (isset($_GET['pages']) && !empty($_GET['pages'])){
            $pageNow = $_GET['pages'] > 1 ? intval(safe_replace($_GET['pages'])) : 1;
        }else{
            $pageNow = 1;
        }
        $pageData['nums'] = $keywordsModel->nums();


        if ($pageData['nums']){
            $pageData['nums'] = intval($pageData['nums']);
            $pageData['urlRule'] = 'index.php?m=keywords&c=keywords&e=keywordsList';
            $viewPages = new viewPages($pageData);
            $pagesNav = $viewPages->getPageNav($pageNow);
            $res = $keywordsModel->getAllkeywords($pageNow);

            if ($res){
                $view->assign('allKeyWords',$res);
                $view->assign('pagesNav',$pagesNav);
//                $view->display('login_index.tpl');
                $view->display('keywords/list.tpl');
                exit();
            }else{
                $getAllKeywordsRes = '关键词数据获取失败';
            }
        }else{
            $getAllKeywordsRes = '暂无关键词';
        }
        @$_SESSION['messagesTips'] = $getAllKeywordsRes;
        @$_SESSION['messagesUrl'] = $this->urlList['goback'];
        keywordsModel::showMessages();
        exit();


    }

    /**
     * 单用户关键词列表
     */
    public function wordList()
    {
        $view = viewEngine();
        $userModel = new userModel();
        $keywordsModel = new keywordsModel();
        if (isset($_GET['userID']) && !empty($_GET['userID'])){

            if (isset($_GET['pages']) && !empty($_GET['pages'])){
                $pageNow = $_GET['pages'] > 1 ? intval(safe_replace($_GET['pages'])) : 1;
            }else{
                $pageNow = 1;
            }
            $userID = safe_replace($_GET['userID']);

            $userBaseRes = $userModel->getOneUser($userID);

            if (isset($userBaseRes[0]['url_id']) && !empty($userBaseRes[0]['url_id'])){
                $urlID = $userBaseRes[0]['url_id'];
                $pageData['nums'] = $keywordsModel->getWordNum($userBaseRes[0]['url_id']);

                if ($pageData['nums']){
                    $pageData['nums'] = intval($pageData['nums']);
                    $pageData['urlRule'] = 'index.php?m=user&c=user&e=userList';
                    $viewPages = new viewPages($pageData);
                    $pagesNav = $viewPages->getPageNav($pageNow);

                    $wordRes =  $keywordsModel->getWordsRes($urlID, $pageNow);

                    if ($wordRes){
                        $view->assign('userBaseRes',$userBaseRes[0]);
                        $view->assign('pagesNav',$pagesNav);
                        $view->assign('wordRes',$wordRes);
//                        $view->display('login_index.tpl');
                        $view->display('keywords/user_list.tpl');
                        exit();
                    }else{
                        $getWordRes = '关键词信息获取失败';
                    }
                }else{
                    $getWordRes = '暂无关键词信息';
                }
            }else{
                $getWordRes = '暂无关键词信息';
            }
        }else{
            $getWordRes = '非法操作';
        }

        @$_SESSION['messagesTips'] = $getWordRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        keywordsModel::showMessages();
        exit();
    }

    /**
     * 添加关键词
     */
    public function wordsAdd()
    {
        $view = viewEngine();
        $keywordsModel = new keywordsModel();
        $userModel = new userModel();
        if (isset($_POST['url_id']) && !empty($_POST['url_id'])){
                $data = null;
                $userID = intval($_GET['userID']);
                $data['url_id'] = intval($_POST['url_id']);
                $data['word_name'] = safe_replace($_POST['word_name']);

                $res = $keywordsModel->checkWordNum($data,$userID);
                if ($res){
                   $wordsAddRes = $res.'个关键词添加成功';
                }else{
                    $wordsAddRes = '关键词添加失败';
                }
        }elseif(isset($_GET['userID']) && !empty($_GET['userID'])){
            $wordRes = null;
            $userID = intval($_GET['userID']);
            $wordRes['userID'] = $userID;

            $res = $userModel->getOneUser($userID);
            if ($res){
                if (isset($res[0]['url_id']) && !empty($res[0]['url_id'])){
                    $wordRes['userName'] = $res[0]['user_name'];
                    $wordRes['urlID'] = $res[0]['url_id'];
                    $view->assign('wordRes',$wordRes);
//                    $view->display('login_index.tpl');
                    $view->display('keywords/add.tpl');
                    exit();
                }else{
                    $wordsAddRes = '请先添加用户域名信息';
                }
            }else{
                $wordsAddRes = '用户信息获取失败';
            }

        }else{
            $wordsAddRes = '非法操作';
        }

        @$_SESSION['messagesTips'] = $wordsAddRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        keywordsModel::showMessages();
        exit();
    }

    /**
     * 删除关键词
     */
    public function wordDel()
    {
        if (isset($_SESSION['level'.HASH_IP]) && $_SESSION['level'.HASH_IP] == 0){
            if (isset($_GET['wordID']) && !empty($_GET['wordID'])){
                $wordID = intval($_GET['wordID']);
                $keywordsModel = new keywordsModel();
                $isBranked = intval($_GET['isBranked']);

                if ($wordID && $isBranked){

                    $res = $keywordsModel->wordDel($wordID,$isBranked);
                }
            }
            if (isset($res) && $res){
                $wordDelRes = '关键词删除成功';
            }else{
                $wordDelRes = '关键词删除失败';
            }
        }else{
            $wordDelRes = '暂无权限';
        }

        @$_SESSION['messagesTips'] = $wordDelRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        keywordsModel::showMessages();
        exit();
    }

    /**
     * 只能修改监控状态
     */
    public function wordUpdate()
    {
        $view = viewEngine();
        $keywordsModel = new keywordsModel();
        if (isset($_POST['word_id']) && !empty($_POST['word_id'])){
            $data = null;
            $wordID =  intval($_POST['word_id']);
            $data['word_status'] = isset($_POST['word_status']) && !empty($_POST['word_status']) ? intval($_POST['word_status']) : '';
            if ($data['word_status']){
                $postRes = $keywordsModel->wordStatus($wordID,$data);
                if ($postRes){
                    $wordStatusRes = '修改成功';
                }else{
                    $wordStatusRes = '修改失败';
                }
            }else{
                $wordStatusRes = '请选择监控状态';
            }
        }elseif (isset($_GET['wordID']) && !empty($_GET['wordID'])){
            $wordID = intval($_GET['wordID']);
            $getRes = $keywordsModel->getWord($wordID);
            $statusRes = $keywordsModel->getEnum('word_status');
            if ($getRes){
                $view->assign('wordRes',$getRes);
                $view->assign('statusRes',$statusRes);
//                $view->display('login_index.tpl');
                $view->display('keywords/update.tpl');
                exit();
            }else{
                $wordStatusRes = '关键词信息获取失败';
            }
        }else{
            $wordStatusRes = '非法请求';
        }

        @$_SESSION['messagesTips'] = $wordStatusRes;
        @$_SESSION['messagesUrl'] = $this->urlList['userList'];
        keywordsModel::showMessages();
        exit();
    }
}