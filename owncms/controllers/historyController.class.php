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

        if(!$adminModel->isLogin()){
            header('location:'.LOGIN_ADMIN);
            exit();
        }
    }

    public function historyBranked()
    {
        if (isset($_GET['wordID']) && !empty($_GET['wordID'])){
            $historyModel = new historyModel();
            $smallTime = isset($_GET['smallTime']) && !empty($_GET['smallTime']) ? intval($_GET['smallTime']) : '';
            if(!empty($smallTime)){
                if (intval($smallTime)==30 || intval($smallTime)==90 || intval($smallTime)==180){
                    $smallTime = intval($smallTime);
                }else{
                    $smallTime = '';
                }
            }
            $wordID = intval(safe_replace($_GET['wordID']));
            $userID = intval($_GET['userID']);

            $wordBaseRes = $historyModel->getWordBaseRes($wordID,$userID);

            if (isset($_GET['pages']) && !empty($_GET['pages'])){
                $pageNow = $_GET['pages'] > 1 ? intval(safe_replace($_GET['pages'])) : 1;
            }else{
                $pageNow = 1;
            }

            $view = viewEngine();

            $pageData['nums'] = $historyModel->nums(' word_id = '.$wordID);
            if ($pageData['nums']){
                $pageData['nums'] = intval($pageData['nums']);
                $pageData['urlRule'] = 'index.php?m=history&c=history&e=historyBranked&smallTime='.$smallTime;
                $viewPages = new viewPages($pageData);
                $pagesNav = $viewPages->getPageNav($pageNow);

                $historyWordRes = $historyModel->getHistoryWordList($wordID, $pageNow, $smallTime);

                if ($historyWordRes){
                    $view->assign('pagesNav',$pagesNav);
                    $view->assign('historyWordRes',$historyWordRes);
                    $view->assign('wordBaseRes',$wordBaseRes);
                }else{
                    $getHistoryBranked = '历史数据获取失败';
                }
            }else{
                $getHistoryBranked = '暂无历史数据';
            }

            if (isset($getHistoryBranked) && !empty($getHistoryBranked)){
                $view->assign('getHistoryBranked',$getHistoryBranked);
            }

            $view->display('login_index.tpl');
        }else{
            echo '<script>history.go(-1);</script>';
            exit();
        }
    }
}