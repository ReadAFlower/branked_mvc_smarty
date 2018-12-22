<?php

/**
 * Class branked
 * @package owncms\libs\classes
 */

class PQbranked
{
    public $pages = 5;   //查询页数

    private $word;      //查询关键词

    private $url;       //排名关键词匹配域名

    private $UserAgent='www.baidu.com';     //搜索引擎

    private $orgiURL;       //初始搜索结果页

    private $nums=0;        //当前查询页数

    private $nextURL;       //搜索结果下一页的URL

    private $tempContent;   //自然排名内容临时存储，供计算排名用

    private $branked;       //接收排名计算结果

    private $ResInfo=[];	//最终搜索处理结果

    public function __construct($word,$url='')
    {
        set_time_limit(0);
        $this->word = urlencode($word);
        $this->url = $url;
        $this->orgiURL = 'https://'.$this->UserAgent.'/s?ie=utf-8&f=8&rsv_bp=0&rsv_idx=1&tn=baidu&wd='.$this->word;
    }

    /**
     * 获取页面内容
     * @return mixed
     */
    private function requestURL($url)
    {
        $header = array (
            'Content-Type: text/html;charset=utf-8',
            //'Accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01',
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            //'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: zh-CN,zh;q=0.9',
            'Cache-Control: no-cache',
            'Connection: keep-alive',
            'Host:'.$this->UserAgent,
            'Pragma: no-cache',
            'Upgrade-Insecure-Requests:1',
            'Referer:https://'.$this->UserAgent.'/',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3239.132 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        );

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $content = curl_exec ( $ch );

        if ($content == FALSE) {
            echo "error:" . curl_error ( $ch );
        }

        curl_close ( $ch );

        return $content;
    }

    /**
     * 页面内容初始处理
     */
    private function contentDeal()
    {
        if (!$this-> nums){
            $content = $this->requestURL($this->orgiURL);
        }else{
            $content = $this->requestURL($this->nextURL);

        }
        $content = preg_replace('/<style([^>]*)>([^<>]*)<\/style>/i','',$content);
        $content = preg_replace('/<script([^>]*)>([^<>]*)<\/script>/i','',$content);
        $pq = phpQuery::newDocument($content);

        $content = '';
        unset($content);

        $lCnt = pq('#content_left', $pq)->text();
        $PreURL = pq('#page .n:eq(0)' ,$pq)->attr('href');
        $NextURL = pq('#page .n:eq(1)' ,$pq)->attr('href');
        $tempNextURL = $NextURL ? $NextURL : $PreURL;

        if (preg_match('/'.$this->UserAgent.'/',$tempNextURL)){
            $this->nextURL = 'https://'.$tempNextURL;
        }else{
            $this->nextURL = 'https://'.$this->UserAgent.$tempNextURL;
        }

        //查排名还是处理搜索结果
        if ($this->url){
            if (preg_match('/'.$this->url.'/',$lCnt)){
                $flg = false;

                for ($i=0;$i<10;$i++){
                    $NID=$this->nums*10+$i+1;
                    $surl = pq('#'.$NID.' .f13', $pq)->text() ? pq('#'.$NID.' .f13', $pq)->text() : pq('#'.$NID.' .c-showurl', $pq)->text() ? pq('#'.$NID.' .c-showurl', $pq)->text() : pq('#'.$NID.' .g', $pq)->text();

                    if (preg_match('/'.$this->url.'/',$surl)){
                        $flg = true;
                        $n = $this->nums+1;
                        $this->branked = '第'.$n.'页第'.($i+1).'位';
                        break;
                    }
                }

                phpQuery::$documents = [];
                if ($flg){
                    return true;
                }
            }
            phpQuery::$documents = [];
            $this->nums+=1;
            $this->contentDeal();

        }else{
            //搜索结果处理
            for($i=0;$i<10;$i++){
                $NID=$this->nums*10+$i+1;
                $title = pq('#'.$NID.' h3', $pq)->text() ? pq('#'.$NID.' h3', $pq)->text() : pq('#'.$NID.' .op_exactqa_title', $pq)->text();
                $cAbstract = pq('#'.$NID.' .c-abstract', $pq)->text();
                $cGapTopSmal = pq('#'.$NID.' .c-gap-top-small', $pq)->text();
                $opJingyanList1 = pq('#'.$NID.' .op_jingyan_list1', $pq)->text();
                $opMusicsong = pq('#'.$NID.' .op-musicsong', $pq)->text();
                $opB2bStraight = pq('#'.$NID.' .op-b2b-straight', $pq)->text();
                $opTieba2Container = pq('#'.$NID.' .op_tieba2_container', $pq)->text();
                $opExactqMain = pq('#'.$NID.' .op_exactqa_main', $pq)->text();
                $cOffset = pq('#'.$NID.' .c-offset', $pq)->text();
                $cBorder = pq('#'.$NID.' .c-border', $pq)->text();
                $opTiebaGeneralMaintable = pq('#'.$NID.' .op-tieba-general-maintable', $pq)->text();
                $cRow = pq('#'.$NID.' .c-row', $pq)->text();
                $description = $cAbstract ? $cAbstract : ($cGapTopSmal ? $cGapTopSmal : ($opJingyanList1 ? $opJingyanList1 : ($opMusicsong ? $opMusicsong : ($opB2bStraight ? $opB2bStraight : ($opTieba2Container ? $opTieba2Container : ($opExactqMain ? $opExactqMain : ($cOffset ? $cOffset : ($cBorder ? $cBorder : ($opTiebaGeneralMaintable ? $opTiebaGeneralMaintable : $cRow)))))))));
                $h3URL = pq('#'.$NID.' h3 a', $pq)->attr('href');
                $sURL = pq('#'.$NID.' .f13 a:has(.m) ', $pq)->attr('href') ? pq('#'.$NID.' .f13 a:has(.m) ')->attr('href') : pq('#'.$NID.' .g a:has(.m) ')->attr('href');
                $eURL = $this -> getSnapshot($h3URL,$sURL);

                $this->ResInfo[]=[
                  'title' => $title,
                  'description' => $description,
                  'snapshot' => $eURL,
                ];
            }
            phpQuery::$documents = [];
            $this->nums+=1;

            if( $this->nums >= $this->pages ){
                $this->branked = '';
                $this->nextURL = false;
                return false;
            }else{
                //递归调用继续处理下一个页面
                $this->contentDeal();
            }
        }

        return true;
    }


    /**
     * 输出排名结果
     * @return mixed
     */
    public function getBranked(){
        $this->contentDeal();
        return $this->branked;
    }

    /**
     *
     * 获取搜索结果对应页面URL
     * @param $content
     * @return mixed
     */
    private function getSnapshot($h3URL,$showURL)
    {
        if ($h3URL){
            $url = $this -> getLocationURL($h3URL);
        }else{
            $url = '';
        }

        //从标题获取url失败
        if(empty($url)){

            if ($showURL){
                $url = $this->getCacheURL($showURL);
            }else{
                //$url = $h3URL ? $h3URL : $showURL;
                $url = '';
            }

        }

        return $url;
    }

    /**
     * 获取重定向后的真实链接 (data-click)
     * @param $url
     * @return mixed
     */
    private function getLocationURL($url)
    {
        @$header = get_headers($url,1);
        if (!$header) return '';
        if (strpos($header[0],'301') || strpos($header[0],'302')) {
            if(is_array($header['Location'])) {
                $info = $header['Location'][count($header['Location'])-1];
                if (!preg_match('/http(s?):\/\//',$info)){
                    $info = $header['Location'][0];
                }
            }else{
                $info = $header['Location'];
            }
        }
        if ($info){
            return urldecode($info);
        }else{
            return '';
        }
    }

    /**
     * 获取百度缓存快照
     * @param $url
     * @return string
     */
    private function getCacheURL($url)
    {
        @$res = file_get_contents($url);
        $nurl = '';
        if ($res){
            $pq = phpQuery::newDocument($res);
            $nurl = pq('#bd_snap_note a',$pq)->attr('href');
            phpQuery::$documents = [];
        }

        return $nurl;
    }

    /**
     * 获取搜索结果信息
     * @return array
     */
    public function getResInfo(){
    	$this->contentDeal();
    	return $this->ResInfo;
    }


}

