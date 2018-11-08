<?php

/**
 * 分页类只做分页导航，给定总记录数和每页数完成分页导航，根据当前页重新调整导航
 * Class viewPages
 * @package owncms\libs\classes
 */
class viewPages
{
    private $nums;           //记录总数
    public $pageSize;      //每页显示数
    private $pageNav;       //分页导航
    private $urlRule;       //URL规则 抛开分页参数外的其他参数规则
    private $pagesNum;      //总页数
    private $prePage;       //上一页 默认显示
    private $nextPage;      //下一页 默认显示
    private $starPage;      //首页
    private $endPage;       //末页
    private $pageNavNum;    //
    private $host;          //主域名

    public function __construct($arrPage)
    {
        $this->pageSize = isset($arrPage['pageSize']) ? $arrPage['pageSize'] : 10 ;
        $this->host = isset($arrPage['host']) ? $arrPage['host'] : '/';
        $this->nums = $arrPage['nums'];
        $this->urlRule = $arrPage['urlRule'];
        $this->prePage = isset($arrPage['prePage']) ? $arrPage['prePage'] : true;
        $this->nextPage = isset($arrPage['nextPage']) ? $arrPage['nextPage'] : true;
        $this->starPage = isset($arrPage['starPage']) ? $arrPage['starPage'] : true;
        $this->endPage = isset($arrPage['endPage']) ? $arrPage['endPage'] : true;
        $this->pageNavNum = isset($arrPage['pageNavNum']) ? $arrPage['pageNavNum'] : 10;
        $this->pagesNum = intval(ceil($this->nums/$this->pageSize));
        $this->pageNav = '<span>共'.$this->nums.'条记录/'.$this->pagesNum.'页</span>';
    }

    private function createPageNav($pageNow)
    {

        if ($pageNow<=1){
            //页数大于1才显示分页导航
            if ($this->pagesNum>1){
                $end = $this->pagesNum>$this->pageNavNum ? $this->pageNavNum : $this->pagesNum;
                for ($i=1;$i<= $end;$i++){
                    if(intval($pageNow) == $i){
                        $this->pageNav .= '<span class="cur">'.$i.'</span>';
                    }else{
                        $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages='.$i.'">'.$i.'</a></span>';
                    }
                }
                if($pageNow<$this->pagesNum){
                    $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages='.(intval($pageNow)+1).'">下一页</a></span>';
                }
                if ($this->pagesNum>$this->pageNavNum){
                    $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages='.$this->pagesNum.'">末页</a></span>';
                }
            }
        }else{
            if ($this->pagesNum>$this->pageNavNum){
                $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages=1">首页</a></span>';
            }
            $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages='.(intval($pageNow)-1).'">上一页</a></span>';
            if(ceil($this->pageNavNum/2)>=$pageNow){
                for ($i=1;$i<=($this->pagesNum>$this->pageNavNum ? $this->pageNavNum : $this->pagesNum);$i++){
                    if(intval($pageNow) == $i){
                        $this->pageNav .= '<span class="cur">'.$i.'</span>';
                    }else{
                        $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages='.$i.'">'.$i.'</a></span>';
                    }
                }
            }else{
                $star = intval($pageNow)-ceil($this->pageNavNum/2)+1;
                $end = intval($pageNow)+floor($this->pageNavNum/2)<$this->pagesNum ? (intval($pageNow)+floor($this->pageNavNum/2)+1) : ($this->pagesNum+1);
                for ($i=$star;$i<$end;$i++){
                    if(intval($pageNow) == $i){
                        $this->pageNav .= '<span class="cur">'.$i.'</span>';
                    }else{
                        $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages='.$i.'">'.$i.'</a></span>';
                    }
                }
            }
            if($pageNow<$this->pagesNum){
                $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&pages='.(intval($pageNow)+1).'">下一页</a></span>';
            }
            if ($this->pagesNum>$this->pageNavNum){
                $this->pageNav .= '<span><a href="'.$this->host.$this->urlRule.'&page='.$this->pagesNum.'">末页</a></span>';
            }
        }
    }

    public function getPageNav($pageNow)
    {
        $this->createPageNav($pageNow);
        return $this->pageNav;
    }

}



