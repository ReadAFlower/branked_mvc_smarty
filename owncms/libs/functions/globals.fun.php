<?php

	/**
	*   控制器实例化并调用指定方法
	*/
	function C($name,$method){
		require_once (PC_PATH.'controllers/'.$name.'Controller.class.php');
		eval('$obj = new '.$name.'Controller();$obj->'.$method.'();');

	}

    /**
     *  Model实例化
     */
    function M($name){
        require_once (PC_PATH.'/views/'.$name.'View.class.php');
        eval('$obj = new '.$name.'View();');

        return $obj;
    }

    /**
     *  View实例化
     */
    function V($name){
        require_once (PC_PATH.'/models/'.$name.'View.class.php');
        eval('$obj = new '.$name.'Model();');

        return $obj;
    }

	/**
	* 第三方类库实例化
	*/
	function libs($name, $path, $array=[]){
		require_once($path.$name.'.class.php');
		$obj=new $name();
		if(!empty($array)){
			foreach ($array as $key => $value) {
				$obj->$key=$value;
			}
		}

		return $obj;
	}

    /**
     * Smarty视图引擎
     * @param $name Smarty目录引擎目录名
     */
	function viewEngine($name = 'Smarty'){
        $view = pcBase::loadPluginClass($name, 'libs/view/'.$name.'/', 'libs/view/'.$name.'/configs/SmartyConfig.php' );
        return $view;
    }

    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $$string 需要处理的字符串或数组
     * @return mixed
     */
    function new_addslashes($string){
        if(!is_array($string)) return addslashes($string);
        foreach($string as $key => $val) $string[$key] = new_addslashes($val);
        return $string;
    }

    /**
     * 返回经stripslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    function new_stripslashes($string) {
        if(!is_array($string)) return stripslashes($string);
        foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
        return $string;
    }

    /**
     * 返回经htmlspecialchars处理过的字符串或数组
     * @param $obj 需要处理的字符串或数组
     * @return mixed
     */
    function new_html_special_chars($string) {
        $encoding = 'utf-8';
        if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
        if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,$encoding);
        foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
        return $string;
    }

    function new_html_entity_decode($string) {
        $encoding = 'utf-8';
        if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
        return html_entity_decode($string,ENT_QUOTES,$encoding);
    }

    function new_htmlentities($string) {
        $encoding = 'utf-8';
        if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
        return htmlentities($string,ENT_QUOTES,$encoding);
    }

    /**
     * 安全过滤函数
     *
     * @param $string
     * @return string
     */
    function safe_replace($string) {
        $string = trim($string);
        $string = str_replace('%20','',$string);
        $string = str_replace('%27','',$string);
        $string = str_replace('%2527','',$string);
        $string = str_replace('*','',$string);
        $string = str_replace('"','&quot;',$string);
        $string = str_replace("'",'',$string);
        $string = str_replace('"','',$string);
        $string = str_replace(';','',$string);
        $string = str_replace('<','&lt;',$string);
        $string = str_replace('>','&gt;',$string);
        $string = str_replace("{",'',$string);
        $string = str_replace('}','',$string);
        $string = str_replace('\\','',$string);
        return $string;
    }

    /**
     * xss过滤函数
     *
     * @param $string
     * @return string
     */
    function remove_xss($string) {
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

        $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

        $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

        $parm = array_merge($parm1, $parm2);

        for ($i = 0; $i < sizeof($parm); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($parm[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                    $pattern .= '|(&#0([9][10][13]);?)?';
                    $pattern .= ')?';
                }
                $pattern .= $parm[$i][$j];
            }
            $pattern .= '/i';
            $string = preg_replace($pattern, ' ', $string);
        }
        return $string;
    }

    /**
     * 转义 javascript 代码标记
     *
     * @param $str
     * @return mixed
     */
    function trim_script($str) {
        if(is_array($str)){
            foreach ($str as $key => $val){
                $str[$key] = trim_script($val);
            }
        }else{
            $str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
            $str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
            $str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
            $str = str_replace ( 'javascript:', 'javascript：', $str );
        }
        return $str;
    }

    /**
     * 过滤ASCII码从0-28的控制字符
     * @return String
     */
    function trim_unsafe_control_chars($str) {
        $rule = '/[' . chr ( 1 ) . '-' . chr ( 8 ) . chr ( 11 ) . '-' . chr ( 12 ) . chr ( 14 ) . '-' . chr ( 31 ) . ']*/';
        return str_replace ( chr ( 0 ), '', preg_replace ( $rule, '', $str ) );
    }

    /**
     * 格式化文本域内容
     *
     * @param $string 文本域内容
     * @return string
     */
    function trim_textarea($string) {
        $string = nl2br ( str_replace ( ' ', '&nbsp;', $string ) );
        return $string;
    }

    /**
     * 将文本格式成适合js输出的字符串
     * @param string $string 需要处理的字符串
     * @param intval $isjs 是否执行字符串格式化，默认为执行
     * @return string 处理后的字符串
     */
    function format_js($string, $isjs = 1) {
        $string = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $string));
        return $isjs ? 'document.write("'.$string.'");' : $string;
    }

    /**验证码验证
     * @param $code
     * @return bool
     */
    function checkCode($code){
        if (strcasecmp($code,$_SESSION['codeCnt'])==0){
            return true;
        }else{
            return false;
        }
    }
