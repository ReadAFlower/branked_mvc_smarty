<?php

/**
 * Class branked
 * @package owncms\libs\classes
 */
class branked
{
    private $pages = 5;   //查询页数

    private $word;      //查询关键词

    private $url;       //排名关键词匹配域名

    private $UserAgent='www.baidu.com';     //搜索引擎

    private $orgiURL;       //初始搜索结果页

    private $nums=0;        //当前查询页数

    public $nextURL;       //搜索结果下一页的URL

    private $tempContent;   //自然排名内容临时存储，供计算排名用

    private $branked;       //接收排名计算结果

    public function __construct($word,$url)
    {
        $this->word = $word;
        $this->url = $url;
        $this->orgiURL = 'https://'.$this->UserAgent.'/s?ie=utf-8&f=8&rsv_bp=1&rsv_idx=1&tn=baidu&wd='.$this->word;

    }

    /**
     * 获取页面内容
     * @return mixed
     */
    private function requestURL($url)
    {
        $header = array (
            'Content-Type: text/html;charset=utf-8',
            'Accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01',
            //'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: zh-CN,zh;q=0.9',
            'Cache-Control: no-cache',
            'Connection: keep-alive',
            'Host:'.$this->UserAgent,
            'Pragma: no-cache',
            'Referer:https://'.$this->UserAgent.'/',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3239.132 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        );

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

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

        //有相关搜索
        preg_match_all('/<div id="content_left">.*<div id="rs">/is',$content,$contentRES);

        //无相关搜索
        if (!isset($contentRES[1]) || empty($contentRES[1])){
            preg_match_all('/<div id="content_left">.*<div id="page" >/is',$content,$contentRES);
        }

        preg_match_all('/<div id="page" >.*<div id="content_bottom">/is',$content,$pagesRES);

        preg_match_all('/<a href="([^<>]*)" class="n">/is',$pagesRES[0][0],$nextUrl);
        if($this->nums){
            $this->nextURL = 'https://'.$this->UserAgent.$nextUrl[1][1];
        }else{
            $this->nextURL = 'https://'.$this->UserAgent.$nextUrl[1][0];
        }

        //去掉结果中的b标签，防止快照被干扰
        $contentRES=str_replace('<b>', '', $contentRES[0][0]);
        $contentRES=str_replace('</b>', '', $contentRES);

        //从当前结果页面匹配查找目标url
        if (preg_match('/'.$this->url.'/',$contentRES)){
                //排名计算
                $this->tempContent = $contentRES;
                $this->calcBranked();
        }else{
            //如果没有匹配到目标url，结果页数加1，并返回$nextURL
            $this->nums+=1;

            //判断查询到第几页了
            if( $this->nums > $this->pages ){
                $this->branked = '';
                $this->nextURL = false;
                return false;
            }else{

                //递归调用继续处理下一个页面
                $this->contentDeal();
            }

        }

    }

    /**
     * 计算排名
     * @return bool
     */
    private function calcBranked()
    {
        $resArr = preg_split('/<div class="result([^<>]*)c-container([^<>]*)"([^<>]*)>/is',$this->tempContent);
        $resNum = count($resArr);
        $flg = false;
        for($i=1;$i<$resNum;$i++){

            if(preg_match('/'.$this->url.'/',$resArr[$i])){
                preg_match_all('/<div class="f13">.*<\/a>/is',$resArr[$i],$stemp);

                if(isset($stemp[0][0]) && preg_match('/'.$this->url.'/',$stemp[0][0])){
                    $flg = true;
                    $n = $this->nums+1;
                    $this->branked = '第'.$n.'页第'.$i.'位';
                    break;
                }elseif (preg_match('/<span class="c-showurl">'.$this->url.'([^<>]*)<\/span>/is',$resArr[$i])){
                    $flg = true;
                    $n = $this->nums+1;
                    $this->branked = '第'.$n.'页第'.$i.'位';
                    break;
                }else{
                    $this->nums+=1;
                    $this->contentDeal();
                    break;
                }
            }
        }

        if($flg){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 输出排名结果
     * @return mixed
     */
    public function getBranked(){
        $this->contentDeal();
        return $this->branked;
    }
}