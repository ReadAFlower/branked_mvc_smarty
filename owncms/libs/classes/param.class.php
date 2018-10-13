<?php

class param
{
    private $routeConfig = null;

    public function __construct()
    {
        if(!get_magic_quotes_gpc()) {
            $_POST = new_addslashes($_POST);
            $_GET = new_addslashes($_GET);
            $_REQUEST = new_addslashes($_REQUEST);
            $_COOKIE = new_addslashes($_COOKIE);
        }

        //加载路由配置文件
        $this->routeConfig = \pcBase::loadConfig('route');

        if(isset($_GET['page'])) {
            $_GET['page'] = max(intval($_GET['page']),1);
            $_GET['page'] = min($_GET['page'],1000000000);
        }

        return true;
    }

    /**
     * 获取模型
     * @return mixed|string
     */
    public function getModel(){
        $m = isset($_GET['m']) && !empty($_GET['m']) ? $_GET['m'] : (isset($_POST['m']) && !empty($_POST['m']) ? $_POST['m'] : '');
        $m = $this->safeDeal($m);
        if (empty($m)) {
            return $this->routeConfig['route']['m'];
        } else {
            if(is_string($m)) return $m;
        }
    }

    /**
     * 获取控制器
     * @return mixed|string
     */
    public function getController() {
        $c = isset($_GET['c']) && !empty($_GET['c']) ? $_GET['c'] : (isset($_POST['c']) && !empty($_POST['c']) ? $_POST['c'] : '');
        $c = $this->safeDeal($c);
        if (empty($c)) {
            return $this->routeConfig['route']['c'];
        } else {
            if(is_string($c)) return $c;
        }
    }

    /**
     * 获取事件
     * @return mixed|string
     */
    public function getEvent() {
        $e = isset($_GET['e']) && !empty($_GET['e']) ? $_GET['e'] : (isset($_POST['e']) && !empty($_POST['e']) ? $_POST['e'] : '');
        $e = $this->safeDeal($e);
        if (empty($e)) {
            return $this->routeConfig['route']['e'];
        } else {
            if(is_string($e)) return $e;
        }
    }

    public static function setCookie($var, $value = '', $time = 0) {
        $time = $time > 0 ? $time : ($value == '' ? SYS_TIME - 3600 : 0);
        $s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
        $httponly = $var=='userid'||$var=='auth'?true:false;
//        $var = pcBase::loadConfig('system','cookie_pre').$var;
//        $_COOKIE[$var] = $value;
//        if (is_array($value)) {
//            foreach($value as $k=>$v) {
//                setcookie($var.'['.$k.']', sys_auth($v, 'ENCODE', md5(PC_PATH.'cookie'.$var).pc_base::load_config('system','auth_key')), $time, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s, $httponly);
//            }
//        } else {
//            setcookie($var, sys_auth($value, 'ENCODE', md5(PC_PATH.'cookie'.$var).pc_base::load_config('system','auth_key')), $time, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s, $httponly);
//        }
    }

    /**
     * 安全处理函数
     * 处理m,a,c
     */
    private function safeDeal($str) {
        return str_replace(array('/', '.'), '', $str);
    }
}