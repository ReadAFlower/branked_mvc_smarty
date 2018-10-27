<?php

//
define('IN_OWNCMS', true);
//框架路径
define('PC_PATH', str_replace('\\','/',dirname(__FILE__).DIRECTORY_SEPARATOR));

//主机协议
define('SITE_PROTOCOL', isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://');

//当前访问的主机名
define('SITE_URL', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));

//客户端IP
define('HASH_IP', str_replace('.', '0' ,$_SERVER['REMOTE_ADDR']));

//来源
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

//系统开始时间
define('SYS_START_TIME', microtime());

//系统时间
define('SYS_TIME', time());

//style
define('STYLE_PATH', SITE_URL.'/style/');

//css
define('CSS_PATH', SITE_URL.'/style/css/');

//img
define('IMG_PATH', SITE_URL.'/style/images/');

//js
define('JS_PATH', SITE_URL.'/style/js/');

//font物理路径
define('FONT_PATH', APP_PATH.'style/fonts/');
//加载公用函数库
pcBase::loadGlobalSysFun('functions');

//加载公用函数库
pcBase::loadGlobalSysClass('classes');


class pcBase
{
    /**
     * 初始化
     */
    public static function createApp(){
        return self::loadSysClass('APPlication');
    }

    /**
     * 加载系统类
     * @param $className    类名
     * @param $path         扩展地址
     * @param int $initialize   是否初始化
     */
    public static function loadSysClass($className, $path = '', $initialize = 1){
        return self::loadClass($className, $path, $initialize);
    }

    /**
     * 加载类方法
     * @param $className        类名
     * @param $path             扩展地址
     * @param int $initialize   是否初始化
     */
    public static function loadClass($className, $path = '', $initialize = 1){
        if(empty($path)) $path = 'libs/classes/';

        if (file_exists(PC_PATH.$path.$className.'.class.php')){

            require_once PC_PATH.$path.$className.'.class.php';

            if ($initialize){
                $name = new $className;
                return $name;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 加载公共系统类函数库
     * @param $class    公共类库配置文件
     */
    public static function loadGlobalSysClass($class){
        return self::loadIncludeConfig($class);
    }

    /**
     * 加载公共系统函数库
     * @param $funConfig    公共函数库配置文件
     */
    public static function loadGlobalSysFun($fun){
        return self::loadIncludeConfig($fun);
    }

    /**
     * 加载引入文件配置文件
     * @param $fileName         配置文件所在文件夹
     * @param string $path      配置文件夹位置
     * @return bool
     */
    public static function loadIncludeConfig($fileName, $path='libs/'){
        $dir = PC_PATH.$path.$fileName.'/';
        if (file_exists( $dir.'include.php' )){
            $funArr = require_once $dir.'include.php';
            $len = count($funArr);
            for ($i = 0; $i < $len; $i++){
                require_once $dir.$funArr[$i];
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * 加载系统配置文件
     * @param $file
     */
    public static function loadConfig($file, $path = ''){

       if (empty($path)) {
           $path = APP_PATH.'configs/'.$file.'.php';
       }else{
           $path = PC_PATH.$path;
       }

        if (file_exists($path)){
            $configs = require_once $path;
            return $configs;
        }else{
            return false;
        }
    }

    /**
     * 加载插件类库
     * @param $classname        插件名
     * @param $path             插件目录
     * @param $configs          插件配置文件目录路径
     * @param int $initialize   是否初始化
     * @return bool
     */
    public static function loadPluginClass($classname, $path, $configs = '' , $initialize = 1 ){
        $configs = self::loadConfig('', $configs);
        $pluginClass = pcBase::loadClass($classname, $path, $initialize);
        if(isset($configs) && !empty($configs)){
            foreach ($configs as $key => $value) {
                $pluginClass->$key = $value;
            }
        }
        return $pluginClass;
    }
}