<?php


pcBase::loadSysClass('baseController','controllers/',0);
pcBase::loadSysClass('historyModel','models/',0);
pcBase::loadSysClass('adminModel','models/',0);
pcBase::loadSysClass('viewPages','',0);
class historyController extends baseController
{

    public function __construct()
    {
        $adminModel = new adminModel();
        $che = $adminModel->isLogin();
        if (!$che){
            $_SESSION['messagesUrl']=LOGIN_ADMIN;
            historyModel::showMessages();
            exit();
        }


    }

    public function historyBranked()
    {
        if (isset($_GET['wordID']) && !empty($_GET['wordID'])){
            $historyModel = new historyModel();
            $smallTime = isset($_GET['smallTime']) && !empty($_GET['smallTime']) ? intval($_GET['smallTime']) : 30;
            if(!empty($smallTime)){
                if ($smallTime==30 || $smallTime==90 || $smallTime==180){
                    $smallTime = intval($smallTime);
                }else{
                    $smallTime = '';
                }
            }else{
                $smallTime = 30;
            }

            $wordID = intval($_GET['wordID']);
            $userID = intval($_GET['userID']);

            $wordBaseRes = $historyModel->getWordBaseRes($wordID,$userID);

            if (isset($_GET['pages']) && !empty($_GET['pages'])){
                $pageNow = $_GET['pages'] > 1 ? intval($_GET['pages']) : 1;
            }else{
                $pageNow = 1;
            }

            $view = viewEngine();
            $timeWhere = strtotime(date('Y-m-d',time()))-3600*24*$smallTime;
            $pageData['nums'] = $historyModel->nums(' word_id = '.$wordID.' and updated_at > '.$timeWhere);

            if ($pageData['nums']){
                $pageData['nums'] = intval($pageData['nums']);
                $pageData['urlRule'] = 'index.php?m=history&c=history&e=historyBranked&wordID='.$wordID.'&userID='.$userID.'&smallTime='.$smallTime;
                $viewPages = new viewPages($pageData);
                $pagesNav = $viewPages->getPageNav($pageNow);

                $historyWordRes = $historyModel->getHistoryWordList($wordID, $pageNow, $smallTime);

                if ($historyWordRes){
                    $view->assign('pagesNav',$pagesNav);
                    $view->assign('historyWordRes',$historyWordRes);
                    $view->assign('wordBaseRes',$wordBaseRes);
//                    $view->display('login_index.tpl');
                    $view->display('history/history.tpl');
                    exit();
                }else{
                    $getHistoryBranked = '历史数据获取失败';
                }
            }else{
                $getHistoryBranked = '暂无历史数据';
            }

        }else{
            $getHistoryBranked = '无效的请求';
        }
        @$_SESSION['messagesTips']=$getHistoryBranked;
        $_SESSION['messagesUrl']='/index.php?m=keywords&c=keywords&e=keywordsList';
        historyModel::showMessages();
        exit();

    }
}