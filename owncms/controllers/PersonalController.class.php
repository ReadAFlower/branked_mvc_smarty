<?php

/**
 * user控制器
 */
pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('PersonalModel','models/',0);
pcBase::loadSysClass('historyModel','models/',0);
pcBase::loadSysClass('viewPages','',0);
pcBase::loadSysClass('keywordsModel','models/',0);
class PersonalController extends baseController
{
    public function __construct()
    {
        if (@$_SESSION['userid'.HASH_IP] && @$_SESSION['username'.HASH_IP]){
            $this->urlList['index'] = '/index.php?m=Personal&c=Personal&e=index';
            $this->urlList['wordList'] = '/index.php?m=Personal&c=Personal&e=wordList';
        }
    }

    public function init()
    {
        if (@$_SESSION['userid'.HASH_IP] && @$_SESSION['username'.HASH_IP]){
            header( 'location:'.$this->urlList['index']);
            exit();
        }else{
            header('location:'.LOGIN_PERSONAL);
            exit();
        }

    }
    public function index()
    {
        $PersonalModel = new PersonalModel();
        if (@!$_SESSION['userid'.HASH_IP] && @!$_SESSION['username'.HASH_IP]){
            header('location:'.LOGIN_PERSONAL);
            exit();
        }
        $view = viewEngine();

        $userInfo = $PersonalModel->getUserInfo($_SESSION['userid'.HASH_IP]);
        $userInfo['lastloginip'] = $_SESSION['lastloginip'.HASH_IP];
        $userInfo['lastlogintime'] = $_SESSION['lastlogintime'.HASH_IP];
        $_SESSION['urlid'.HASH_IP] = $userInfo['url_id'];

        $m = safe_replace($_GET['m']);
        $c = safe_replace($_GET['c']);
        $view -> assign('m', $m);
        $view -> assign('c', $c);
        $view->assign('userInfo',$userInfo);

        $_SESSION['m'.HASH_IP] = $m;
        $_SESSION['c'.HASH_IP] = $c;

        $_SESSION['haship'] = HASH_IP;

//        $view->display('user_index.tpl');
        $view->display('personal/index.tpl');
        exit();
    }

    //登录
    public function login()
    {

        $PersonalModel = new PersonalModel();

        if ($PersonalModel->isLogin()){
            header('location:'.$this->urlList['index']);
            exit();
        }else{
            if(isset($_POST['login_type']) && !empty($_POST['login_type'])){

                $type = safe_replace($_POST['login_type']);

                if ($type!='user'){
                    header('location:'.LOGIN_PERSONAL);
                    exit();
                }
                $userName = safe_replace($_POST['uname']);
                $password = safe_replace($_POST['pwd']);
                $code = safe_replace($_POST['code']);
                $userID = $PersonalModel->checkAdmin($userName, $password, $code);

                if ($userID){
                    $_SESSION['messagesTips']='登录成功';
                    $_SESSION['messagesUrl']='/index.php?m=Personal&c=Personal&e=index';
                    PersonalModel::showMessages();
                    exit();
                }else{
                    $_SESSION['messagesUrl']=LOGIN_PERSONAL;
                    PersonalModel::showMessages();
                    exit();
                }

            }else{
                if (isset($_GET['dosubmit']) && $_GET['dosubmit']=='user'){
                    $view = viewEngine();
                    $loginType = safe_replace($_GET['dosubmit']);
                    $m = safe_replace($_GET['m']);
                    $c = safe_replace($_GET['c']);
                    $view -> assign('loginType', $loginType);
                    $view -> assign('m', $m);
                    $view -> assign('c', $c);
                    $view->display('login.tpl');
                }else{
                    header('location:'.LOGIN_PERSONAL);
                    exit();
                }
            }
        }

    }

    //安全退出
    public function loginOut(){
        $personalModel = new PersonalModel();

        if(!$personalModel->loginOut()){
            header('location:'.LOGIN_PERSONAL);
            exit();
        }
    }

    //关键词列表
    public function wordList()
    {
        if (isset($_SESSION['userid'.HASH_IP]) && $_SESSION['userid'.HASH_IP]){
            $view = viewEngine();
            $personalModel = new PersonalModel();
            $wordInfo = $personalModel-> getWordList(intval($_SESSION['userid'.HASH_IP]));

            if ($wordInfo){
                $view -> assign('userID', $_SESSION['userid'.HASH_IP]);
                $view -> assign('wordInfo', $wordInfo);
//                $view->display('user_index.tpl');
                $view->display('personal/wordList.tpl');
                exit();
            }else{
                $wordInfoRes = '暂无关键词';
                $_SESSION['messagesTips'] = $wordInfoRes;
                $_SESSION['messagesUrl'] = '/index.php?m=Personal&c=Personal&e=index';
                PersonalModel::showMessages();
                exit();
            }

        }else{
           $_SESSION['messagesTips'] = '请先登录';
           @$_SESSION['messagesUrl'] = LOGIN_PERSONAL;
            PersonalModel::showMessages();
            exit();

        }

    }

    //历史排名
    public function getHistory()
    {
        $PersonalModel = new PersonalModel();
        $userId = $PersonalModel->isLogin();
        if (!$userId){
            header('location:'.LOGIN_PERSONAL);
            exit();
        }

        if (isset($_GET['wordID']) && !empty($_GET['wordID'])){
            $view = viewEngine();
            $wordID = intval($_GET['wordID']);
            $historyModel = new historyModel();
            $pageData['nums'] = $historyModel->nums(' word_id = '.$wordID);

            if ($pageData['nums']){
                $keywordsModel = new keywordsModel();
                $wordBaseRes = $keywordsModel->getWord($wordID);

                if (isset($_GET['pages']) && !empty($_GET['pages'])){
                    $pageNow = $_GET['pages'] > 1 ? intval($_GET['pages']) : 1;
                }else{
                    $pageNow = 1;
                }

                $smallTime = isset($_GET['smallTime']) && !empty($_GET['smallTime']) ? intval($_GET['smallTime']) : '';
                if(!empty($smallTime)){
                    if ($smallTime==30 || $smallTime==90 || $smallTime==180){
                        $smallTime = intval($smallTime);
                    }else{
                        $smallTime = '';
                    }
                }

                $pageData['nums'] = intval($pageData['nums']);
                $pageData['urlRule'] = 'index.php?m=Personal&c=Personal&e=getHistory&smallTime='.$smallTime;
                $viewPages = new viewPages($pageData);
                $pagesNav = $viewPages->getPageNav($pageNow);

                $historyWordRes = $historyModel->getHistoryWordList($wordID, $pageNow, $smallTime);

                if ($historyWordRes){
                    $view->assign('pagesNav',$pagesNav);
                    $view->assign('historyWordRes',$historyWordRes);
                    $view->assign('wordBaseRes',$wordBaseRes);
//                    $view->display('user_index.tpl');
                    $view->display('personal/historyList.tpl');
                    exit();
                }else{
                    $getHistoryBranked = '历史数据获取失败';
                }
            }else{
                $getHistoryBranked = '暂无历史数据';
            }

        }else{
            $getHistoryBranked = '非法操作';
        }

        @$_SESSION['messagesTips'] = $getHistoryBranked;
        @$_SESSION['messagesUrl'] = $this->urlList['goback'];
        PersonalModel::showMessages();
        exit();
    }

    //添加关键词
    public function addWords()
    {
        $PersonalModel = new PersonalModel();
        $userId = $PersonalModel->isLogin();
        if (!$userId){
            header('location:'.LOGIN_PERSONAL);
            exit();
        }

        $view = viewEngine();
        if (isset($_POST['word_name']) && !empty($_POST['word_name'])){
            $words = null;
            $words['url_id'] = $_SESSION['urlid'.HASH_IP];
            $words['word_name'] = safe_replace($_POST['word_name']);
            $userID = $_SESSION['userid'.HASH_IP];
            $keywordsModel = new keywordsModel();
            $res = $keywordsModel->checkWordNum($words,$userID);

            if ($res){
                $userAddWordsRes = '关键词添加成功';
            }else{
                $userAddWordsRes = '关键词添加失败';
            }
            @$_SESSION['messagesTips'] = $userAddWordsRes;
            @$_SESSION['messagesUrl'] = $this->urlList['wordList'];
            PersonalModel::showMessages();
            exit();
        }else{
//            $view->display('user_index.tpl');
            $view->display('personal/addWords.tpl');
            exit();
        }
    }


    /**
     * 修改关键词监控状态
     */
    public function wordUpdate()
    {
        $PersonalModel = new PersonalModel();
        $userId = $PersonalModel->isLogin();
        if (!$userId){
            header('location:'.LOGIN_PERSONAL);
            exit();
        }

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
//                $view->display('user_index.tpl');
                $view->display('personal/updateWord.tpl');
                exit();
            }else{
                $wordStatusRes = '关键词信息获取失败';
            }
        }else{
            $wordStatusRes = '非法请求';
        }

        @$_SESSION['messagesTips'] = $wordStatusRes;
        @$_SESSION['messagesUrl'] = $this->urlList['wordList'];
        PersonalModel::showMessages();
        exit();
    }

    /**
     * user信息修改
     */
    public function userUpdate()
    {
        $PersonalModel = new PersonalModel();
        $userId = $PersonalModel->isLogin();
        if (!$userId){
            header('location:'.LOGIN_PERSONAL);
            exit();
        }

        $view = viewEngine();
        $userModel = new userModel();
        if (isset($_POST['user_id']) && !empty($_POST['user_id'])){
            $data = null;
            $userID = intval($_POST['user_id']);
            $data['user']['user_name'] = safe_replace($_POST['user_name']);
            $data['user']['type_num'] = safe_replace($_POST['type_num']);
            if(isset($_POST['password']) && !empty($_POST['password'])) $data['user']['password'] = $_POST['password'];
            $data['user']['email'] = safe_replace($_POST['email']);
            $data['user']['phone'] = safe_replace($_POST['phone']);
            $data['url']['url_name'] = safe_replace($_POST['url_name']);
            $data['user']['status'] = intval($_POST['status']);
            $updateRes = $userModel->userUpdate($data,$userID);

            if ($updateRes){
                $userUpdateRes = '信息修改成功';
            }else{
                $userUpdateRes = '信息修改失败';
            }

        }else{
            $userID = $_SESSION['userid'.HASH_IP];
            $userRes = $userModel->getOneUser($userID);

            unset($userRes[0]['level']);

            $industryModel = new industryModel();
            $industryList = $industryModel->getIndustryList();

            if($userRes){
                $view->assign('industryList', $industryList);
                $view->assign('userRes', $userRes['0']);
//                $view->display('user_index.tpl');
                $view->display('personal/updateUser.tpl');
                exit();
            }else{
                $userUpdateRes = '信息获取失败';
            }
        }

        @$_SESSION['messagesTips'] = $userUpdateRes;
        @$_SESSION['messagesUrl'] = $this->urlList['index'];
        PersonalModel::showMessages();
        exit();

    }
}