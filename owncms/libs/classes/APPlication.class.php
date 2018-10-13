<?php

define('HASH_IP', str_replace('.', '0' ,$_SERVER['REMOTE_ADDR']));

class APPlication
{
    public function __construct() {
        $param = pcBase::loadSysClass('param');
        define('ROUTE_M', $param->getModel());
		define('ROUTE_C', $param->getController());
		define('ROUTE_A', $param->getEvent());
		$this->init();
    }

    private function init()
    {
        $controller = $this->loadController();
        if (method_exists($controller, ROUTE_A)) {
            if (preg_match('/^[_]/i', ROUTE_A)) {
                exit('You are visiting the action is to protect the private action');
            } else {
                call_user_func(array($controller, ROUTE_A));
            }
        } else {
            exit('Action does not exist.');
        }
    }

    /**
     * 加载控制器
     * @param $filename     控制器所在目录
     * @param $m
     */
    private function loadController($filename = '', $m = ''){
       // if (empty($filename)) $filename = ROUTE_C;
        if (empty($m)) $m = ROUTE_M.'Controller';
        $path = PC_PATH.'controllers/'.$filename.$m.'.class.php';
        if(file_exists($path)){
            require_once $path;

            return new $m;
        }else{
            exit('Controller does not exist.');
        }

    }
}
