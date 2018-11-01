<?php
/**
 * 关键词控制器
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('urlModel','models/',0);
pcBase::loadSysClass('userModel','models/',0);
class keywordsController extends baseController
{
    public function __construct()
    {
        $adminModel = new adminModel();

        if(!$adminModel->isLogin()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

    public function init()
    {
        $adminModel = new adminModel();
        if($adminModel->isLogin()){
            $view = viewEngine();
            $view->display('login_index.tpl');
            exit();
        }else{
            header('location:'.LOGIN_ADMIN);
            exit();
        }

    }
    public function keywordsIndex()
    {
        $view = viewEngine();
        $view->display('login_index.tpl');
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
            }else{
                $getAllKeywordsRes = '关键词数据获取失败';
            }
        }else{
            $getAllKeywordsRes = '暂无关键词';

        }
        if (isset($getAllKeywordsRes) && !empty($getAllKeywordsRes)){
            $view->assign('getAllKeywordsRes',$getAllKeywordsRes);
        }

        $view->display('login_index.tpl');
    }

    /**
     * 单用户关键词列表
     */
    public function wordList()
    {
        if (isset($_GET['userID']) && !empty($_GET['userID'])){

            $view = viewEngine();
            $keywordsModel = new keywordsModel();

            if (isset($_GET['pages']) && !empty($_GET['pages'])){
                $pageNow = $_GET['pages'] > 1 ? intval(safe_replace($_GET['pages'])) : 1;
            }else{
                $pageNow = 1;
            }
            $userID = safe_replace($_GET['userID']);
            $userModel = new userModel();
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
                    }else{
                        $getWordRes = '关键词信息获取失败';
                    }
                }else{
                    $getWordRes = '暂无关键词信息';
                }


            }else{
                $getWordRes = '暂无关键词信息';
            }

            if (isset($getWordRes) && !empty($getWordRes)){
                $view->assign('getWordRes', $getWordRes);
            }

            $view->display('login_index.tpl');
        }else{
            header('location:/index.php?m=user&c=user&e=userIndex');
            exit();
        }
    }

    /**
     * 添加关键词
     */
    public function wordsAdd()
    {
        $view = viewEngine();
        if (isset($_POST['url_id']) && !empty($_POST['url_id'])){

                $data = null;
                $userID = $_GET['userID'];
                $data['url_id'] = safe_replace($_POST['url_id']);
                $data['word_name'] = safe_replace($_POST['word_name']);
                $keywordsModel = new keywordsModel();
                $res = $keywordsModel->checkWordNum($data,$userID);

                if ($res){
                    $_SESSION['wordsAdd'.HASH_IP] = $res.'个关键词添加成功';
                }else{
                    $_SESSION['wordsAdd'.HASH_IP] = '关键词添加失败';
                }
                header('location:/index.php?m=keywords&c=keywords&e=wordList&userID='.$userID);
        }elseif(isset($_GET['userID']) && !empty($_GET['userID'])){
            $wordRes = null;
            $userID = safe_replace($_GET['userID']);
            $wordRes['userID'] = $userID;
            $userModel = new userModel();
            $res = $userModel->getOneUser($userID);
            if ($res){
                if (isset($res[0]['url_id']) && !empty($res[0]['url_id'])){
                    $wordRes['userName'] = $res[0]['user_name'];
                    $wordRes['urlID'] = $res[0]['url_id'];
                    $view->assign('wordRes',$wordRes);
                }else{
                    $wordAddRes = '请先添加用户域名信息';
                }
            }else{
                $wordAddRes = '用户信息获取失败';
            }
            if (isset($wordAddRes) && !empty($wordAddRes)){
                $view->assign('wordAddRes',$wordAddRes);
            }
            $view->display('login_index.tpl');

        }else{
            header('location:/index.php?m=user&c=user&e=userList');
            exit();
        }
    }

    /**
     * 删除关键词
     */
    public function wordDel()
    {
        if (isset($_GET['wordID']) && !empty($_GET['wordID'])){
            $wordID = intval(safe_replace($_GET['wordID']));
            $keywordsModel = new keywordsModel();
            $isBranked = intval(safe_replace($_GET['isBranked']));

            if ($wordID && !empty($isBranked)){

                $res = $keywordsModel->wordDel($wordID,$isBranked);
            }
        }
        if (isset($res) && $res){
            $wordDelRes = '关键词删除成功';
        }else{
            $wordDelRes = '关键词删除失败';
        }

        $_SESSION['wordDelRes'.HASH_IP] = $wordDelRes;

        header('location:/index.php?m=user&c=user&e=userList');
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
            $wordID =  intval(safe_replace($_POST['word_id']));
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
            $wordID = intval(safe_replace($_GET['wordID']));
            $getRes = $keywordsModel->getWord($wordID);
            $statusRes = $keywordsModel->getEnum('word_status');
            if ($getRes){
                $view->assign('wordRes',$getRes);
                $view->assign('statusRes',$statusRes);
            }else{
                $wordStatusRes = '关键词信息获取失败';
            }
        }else{
            $wordStatusRes = '非法请求';
        }
        if (isset($wordStatusRes) && !empty($wordStatusRes)){
            $view->assign('wordStatusRes', $wordStatusRes);
        }
        $view->display('login_index.tpl');
    }
}