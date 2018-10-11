<?php

////关键词排名计算流程：百度搜索结果——url匹配——是——计算排名
//                                        |
//                                        否
//                                        |
//                                      查询下一页——url匹配——是——计算排名
//                                                      |
//                                                      否
//                                                    查询下一页
//                                                      |||
/**
 * @param $word  待查询排名关键词
 * @param int $pages  搜索页数，默认只查询前5页结果
 */
    function requestWord($word,$pages=5){
        getBranked($word);
        $origURL='http://www.baidu.com/s?wd='.$word;
        $cnt=requestURL($origURL);
        contentDeal($cnt);

    }

/**
 * @param $url
 */
    function requestURL($url){
        $header = array (
            "Host:www.baidu.com",
            "Content-Type:application/x-www-form-urlencoded",//post请求
            "Connection: keep-alive",
            'Referer:http://www.baidu.com',
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
 * 结果页内容初步处理
 * @param $content 搜索结果页内容
 */
    function contentDeal($content){

        preg_match_all('/<div id="content_left">.*<div id="content_bottom">/is',$content,$naturalCnt);
        var_dump($naturalCnt[0][0]);
        exit();
        return $naturalCnt;
    }

/**
 * @param $url 目标url
 */
    function matchURL($url,$content){

        $url=addslashes(trim($url));
        if(preg_match('/'.$url.'/',$content)){
           // return $pageNum;
        }else{
          //  return $nextPageURL;
        }

    }

/**
 * 计算关键词排名
 * @param $word
 */
    function getBranked($word){
        //matchURL();
    }