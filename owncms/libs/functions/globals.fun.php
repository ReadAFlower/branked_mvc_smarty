<?php
use owncms\libs\classes\ValidateCode;
	/**
	*   控制器实例化并调用指定方法
	*/
	function C($name,$method){
		require_once ('../../controllers/'.$name.'Controller.class.php');
		eval('$obj = new '.$name.'Controller();$obj->'.$method.'();');

	}

    /**
     *  Model实例化
     */
    function M($name){
        require_once ('../../views/'.$name.'View.class.php');
        eval('$obj = new '.$name.'View();');

        return $obj;
    }

    /**
     *  View实例化
     */
    function V($name){
        require_once ('../../models/'.$name.'View.class.php');
        eval('$obj = new '.$name.'Model();');

        return $obj;
    }

	/**
	* 第三方类库实例化
	*/
	function libs($name, $path, $array=[]){
//        var_dump($path);
//        exit();
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
     * 生成验证码图片
     */
	function createCodeImg($arry=[]){
        require_once(str_replace('\\', '/', BASE_DIR).'owncms/libs/classes/ValidateCode.class.php');
        $validateCode = new ValidateCode($arry);
        $validateCode->doImg();
    }
