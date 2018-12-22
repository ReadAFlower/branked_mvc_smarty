<?php

/**
 * Class branked
 * @package owncms\libs\classes
 */

class branked
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


        //有相关搜索
        preg_match_all('/<div(\s*)id="content_left"(\s*)>(.*)<div(\s*)id="rs"(\s*)>/Uis',$content,$contentRES);

        //无相关搜索
        if (!isset($contentRES[1]) || empty($contentRES[1])){
            preg_match_all('/<div(\s*)id="content_left"(\s*)>(.*)<div(\s*)id="page"(\s*)>/Uis',$content,$contentRES);
        }

        preg_match_all('/<div(\s*)id="page"(\s*)>(.*)<div(\s*)id="content_bottom"(\s*)>/Uis',$content,$pagesRES);

        if (!isset($pagesRES[0])) return false;
        if (!isset($pagesRES[0][0])) return false;
        preg_match_all('/<a href="([^<>]*)" class="n">/Uis',$pagesRES[0][0],$nextUrl);

        if($this->nums){
            $this->nextURL = 'https://'.$this->UserAgent.$nextUrl[1][1];
        }else{
            $this->nextURL = 'https://'.$this->UserAgent.$nextUrl[1][0];
        }

        //去掉结果中的b标签，防止快照被干扰
        $contentRES=str_replace('<b>', '', $contentRES[0][0]);
        $contentRES=str_replace('</b>', '', $contentRES);

        //从当前结果页面匹配查找目标url
        if ($this->url && preg_match('/'.$this->url.'/',$contentRES)){
                //排名计算
                $this->tempContent = $contentRES;
                $this->calcBranked();
        }else{
            //如果没有匹配到目标url，结果页数加1，并返回$nextURL
            //或者不查排名
            $this->nums+=1;

            //不查排名内容处理
            if(!$this->url) $this->ResInfo[]=$this->dealRes($contentRES);

            //判断查询到第几页了
            if( $this->nums >= $this->pages ){
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
                preg_match_all('/<div class="f13">.*<\/a>/Uis',$resArr[$i],$stemp);

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

    /**
     * 处理每页搜索结果
     * @param $content
     * @return array|bool
     */
    private function dealRes($content){
    	if(!$content) return false;
    	$resArr = preg_split('/<div class="result([^<>]*)c-container([^<>]*)"([^<>]*)>/is',$content);
    	$len = count($resArr);
    	if($len<2) return false;
    	$temp = [];
    	for($i=1;$i<$len;$i++){
    		$temp[$i-1]['title'] = $this->getTitle($resArr[$i],$i);

           if(preg_match('/<script[\s\S]*?<\/script>/i',$resArr[$i])){
               $resArr[$i] = preg_replace('/<script[\s\S]*?<\/script>/i','',$resArr[$i]);
           }
            if(preg_match('/<style[\s\S]*?<\/style>/i',$resArr[$i])){
                $resArr[$i] = preg_replace('/<style[\s\S]*?<\/style>/i','',$resArr[$i]);
            }

    		$temp[$i-1]['description'] = strip_html_tags(['img'], $this->getDescription($resArr[$i]));

    		if($temp[$i-1]['title']){
                $temp[$i-1]['snapshot'] = $this->getSnapshot($resArr[$i]);
            }else{
                $temp[$i-1]['snapshot'] = '';
            }

    	}

    	return $temp;
    }

    /**
     * 获取单条结果的标题
     * @param $content
     * @return null|string|string[]
     */
    private function getTitle($content,$i){
        preg_match_all('/<h3([^<>]*)>(.*)<\/h3>/Uis',$content,$arr);

        if (empty($arr[2][0])){

            if (preg_match('/<div class="op_exactqa_title([^<>]*)>([\s\S]*)<\/div>/U',$content)){
                preg_match_all('/<div class="op_exactqa_title([^<>]*)>([\s\S]*)<\/div>/Uis',$content,$arr2);
                $title = strip_tags($arr2[2][0]);
            }else{
                $title = '';
            }
        }else{
            $title = strip_tags($arr[2][0]);
        }

    	return trim($title);
    }

    /**
     * 获取结果说明内容
     * @param $content
     * @return mixed
     */
    private function getDescription($content){

        $fArr = explode('<div class="c-abstract',$content);
        if(count($fArr)>1){
            //常规
            $rul='/<div class="c-abstract.*>(.*)<\/div>/isU';
            preg_match($rul, $content, $rCnt);
            if(isset($rCnt[0])){
                $rDescription=strip_tags($rCnt[0]);

                return $rDescription;
            }
        }

        //视频
        $spArr = explode('<div class="c-row c-gap-top-small">',$content);
        if(count($spArr)>1){
            $spArr2=explode('<div class="c-span18 c-span-last">',$spArr[1]);
            if(count($spArr2)>1){
                $spArr3=explode('<div class="g">',$spArr2[1]);
            }else{
                $spArr3=explode('<div class="g">',$spArr2[0]);
            }
            $spArrRes=$spArr3[0]."</font>";
            $rDescription=strip_tags($spArrRes);

            return $rDescription;
        }

        //百度经验匹配方式1
        $jyArr = explode('<div class="c-row  op_jingyan_list1">',$content);
        if(count($jyArr)>1){
            $jyArr2 = explode('<div class="c-gap-top-small">',$jyArr[1]);
            $res="<div><div>".$jyArr2[0];
            //$rDescription=strip_tags($res,"<a>");
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //百度经验匹配方式2
        $jyArr = explode('<div class="c-row c-gap-bottom op_jingyan_list1">',$content);
        if(count($jyArr)>1){

            $jyArr2=explode('<div class="c-gap-top-small">',$jyArr[1]);
            $res="<div><div>".$jyArr2[0];
            //$rDescription=strip_tags($res,"<a>");
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //百度贴吧
        $tbArr=explode('<table border="0" cellspacing="0" cellpadding="0" class="op-tieba-general-maintable" width="100%">',$content);

        if(count($tbArr)>1){

            $tbArr2=explode('</table>',$tbArr[1]);
            $res='<table border="0" cellspacing="0" cellpadding="0" class="op-tieba-general-maintable" width="100%">'.$tbArr2[0].'</table>';
            //$rDescription=strip_tags($res,"<a>");
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //权威百科1
        $bkArr=explode('<div class="c-span18 c-span-last">', $content);
        if(count($bkArr)>1){
            $bkArr2=explode('<a class="c-gap-right-small op-se-listen-recommend"', $bkArr[1]);
            $res=$bkArr2[0]."</p>";
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //权威百科2
        $bk2Arr=explode('<div class="c-span24 c-span-last">', $content);
        if(count($bk2Arr)>1){
            $bk2Arr2=explode('<p class=" op-bk-polysemy-move">', $bk2Arr[1]);
            $res=$bk2Arr2[0]."</p>";
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //百度文库列表
        $wkArr=explode('<table class="c-table">', $content);
        if(count($wkArr)>1){
            $wkArr2=explode('</table>', $wkArr[1]);
            $res=$wkArr2[0];
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //百度地图
        $dtArr=explode('<div class="c-span12 c-span-last">',$content);
        if(count($dtArr)>1){

            $dtArr2=explode('<p class="op_mapdots_left"><a href',$dtArr[1]);
            $res=$dtArr2[0];
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //百度汉语

        $hyArr=explode('<div class="op_dict_content" lang="EN-US" xml:lang="EN-US">',$content);
        if(count($hyArr)>1){

            $hyArr2=explode('<div class="op_dict_fmp_flash_div">',$hyArr[1]);
            $res="<div>".$hyArr2[0];
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //新闻门户列表
        $mhArr=explode('<ul class="op-video-vast-ul op-video-vast-border">',$content);
        if(count($mhArr)>1){
            $mhArr2=explode('</ul>',$mhArr[1]);
            $res="<ul>".$mhArr2[0]."</ul>";
            $rDescription=strip_tags($res,"<li>");

            return $rDescription;
        }

        //新闻消息
        $newArr=explode('<div class="c-offset">',$content);
        if(count($newArr)>1){
            $res="<div><div>".$newArr[1];
            //$rDescription=strip_tags($res,"<a>");
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //影视剧、小说、游戏等列表
        $ysjArr=explode('<div class="op_exactqa_body">',$content);
        if(count($ysjArr)>1){
            $ysjArr2=explode('<div class="op_exactqa_tools">',$content);
            $res="<div><div>".$ysjArr2[0];
            $res = preg_replace('/<div class="op_exactqa_title([^<>]*)>([\s\S]*)<\/div>/Uis','',$res);
            $rDescription=strip_tags($res);

            return $rDescription;
        }

        //第三方平台无简介说明
        $dsfArr=explode('<p style="color:#666" class="f13">',$content);
        if(count($dsfArr)>1){
            $dsfArr2=explode('<a href=',$dsfArr[1]);
            $rDescription=strip_tags($dsfArr2[0]);

            return $rDescription;
        }

        //其他补充
        $otherArr=explode('<ol>',$content);
        if(count($otherArr)>1){
            $otherArr2=explode('</ol>',$otherArr[1]);
            $res=$otherArr2[0];
            $rDescription=strip_tags($res);

            return $rDescription;
        }

    }


    /**
     *
     * 获取搜索结果对应页面URL
     * @param $content
     * @return mixed
     */
    private function getSnapshot($content)
    {
        preg_match_all('/<h3([^<>]*)>(.*)<a([^<>]*)>(.*)<\/a>(\s*)<\/h3>/Uis',$content,$arr);

        if (isset($arr[3][0])){
            preg_match_all('/href(\s*)=(\s*)(\'|\")(.*)(\'|\")(\s*)target/Uis',$arr[3][0],$arr2);
            if (!empty($arr2[4][0])){
                $url = $this->getLocationURL($arr2[4][0]);
            }else{
                $url = '';
            }
        }else{
            $url = '';
        }

        //从标题获取url失败
        if(empty($url)){
            preg_match_all('/<div class="f13">(.*)百度快照/Uis',$content,$cArr);
            if (!isset($cArr[1][0]) || empty($cArr[1][0])){
                $url = '';
            }else{
                preg_match_all('/<a(\s*)data(\s*)-(\s*)click(\s*)=(\s*)"{\'rsv_snapshot\':\'1\'}"(\s*)href(\s*)=(\s*)(\'|\")([^<>\s]*)(\'|\")(\s*)target/Uis',$cArr[1][0],$cArr2);

                if (empty($cArr2[10][0])){
                    $url = '';
                }else{
                    $url = $this->getCacheURL($cArr2[10][0]);
                }
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
        $res = file_get_contents($url);
        preg_match_all('/<div id="bd_snap_note">([^<>]*)<a(\s*)href(\s*)=(\s*)(\'|\")([^<>\s\'\"]*)(\'|\")([^<>]*)>/Uis',$res,$resArr);

        if (isset($resArr[6][0]) && !empty($resArr[6][0])){
            $url = trim($resArr[6][0]);
        }else{
            $url = '';
        }
        return $url;
    }

    /**
     * 获取refresh URL
     * no data-click
     * @param $url
     * @return mixed
     */
    private function getRefreshURL($url)
    {
        $res= file_get_contents($url);
        $fp = fopen('test.txt','w');
        fwrite($fp,$res);
        fclose($fp);
        //阻止跳转
        $res = json_encode($res);
        //临时存放
        $file_path = "test.txt";
        if(file_exists($file_path)){
            $file_arr = file($file_path);
            //读取需要处理的内容
            $len = count($file_arr);
            $flg = false;
            for($i=0;$i++;$i<$len){
                if (preg_match('/<noscript>/',$file_arr[$i])){
                    $rStr = trim($file_arr[$i]);
                    $flg = true;
                    break;
                }
            }
            if (!$flg) return '';

            //把源码中的刷新功能清除
            $rStr = str_replace('refresh','',$rStr);
            preg_match_all('/<noscript>(.*)<\/noscript>/U',$rStr,$rArr);

            $rStr2 = str_replace('"','',$rArr[1][0]);
            $rStr3 = substr($rStr2,strrpos($rStr2,'URL')+4);
            $rStr4 = str_replace(["'",">"],'',$rStr3);
            //对非百度地图结果url进行url解码
            if(!preg_match('/map.baidu.com/',$rStr4)){
                return urldecode(trim($rStr4));
            }else{
                return trim($rStr4);
            }

        }else{
            return '';
        }

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
