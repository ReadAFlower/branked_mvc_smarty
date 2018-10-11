<?php
namespace owncms\libs\classes;

/**
 * Class branked
 * @package owncms\libs\classes
 */
class branked
{
    //查询页数
    private $pages=5;

    //查询关键词
    private $word;

    //查询域名
    private $url;

    //搜索引擎
    private $UserAgent='www.baidu.com';

    //初始搜索结果页
    private $orgiURL;

    //当前查询页数
    private $nums=0;

    //下一结果页
    private $nextURL;

    public function __construct($word,$url)
    {
        $this->word = $word;
        $this->url = $url;
        $this->orgiURL ='http://'.$this->UserAgent.'/s?wd='.$this->word;
    }

    /**
     * 获取页面内容
     * @return mixed
     */
    private function requestURL($url)
    {
        $header = array (
            "Host:".$this->UserAgent,
            "Content-Type:application/x-www-form-urlencoded",
            "Connection: keep-alive",
            'Referer:http://'.$this->UserAgent,
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36'
        );

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 执行
        $content = curl_exec ( $ch );
        if ($content == FALSE) {
            echo "error:" . curl_error ( $ch );
        }
        // 关闭
        curl_close ( $ch );

        return $content;
    }

    /**
     * 页面内容初始处理
     */
    public function contentDeal()
    {
        if (!$this-> nums){
            $content = $this->requestURL($this->orgiURL);
        }else{
            $content = $this->requestURL($this->nextURL);
        }

        preg_match_all('/<div id="content_left">.*<div id="rs">/is',$content,$contentRES);

        //从当前结果页面匹配查找目标url
        if (preg_match('/'.addslashes($this->url).'/',$contentRES)){
                //排名计算
                $this->getBranked();
        }else{
            //如果没有匹配到目标url，结果页数加1，并返回$nextURL
            $this->nums+=1;

            //判断查询到第几页了
            if( $this->nums > $this->pages ){
                return false;
            }else{
                preg_match_all('/<div id="page" >.*<div id="content_bottom">/is',$content,$pagesRES);
                preg_match_all('/<a href="([^<>]*)" class="n">/is',$pagesRES[0][0],$nextUrl);

                $this->nextURL = $nextUrl[1][0];

                //递归调用继续处理下一个页面
                $this->contentDeal();
            }

        }

    }

    public function getBranked()
    {

    }
}