<?php

    /**
     * smarty 自定义注销变量
     * @param $var
     * @return bool
     */
    function smarty_function_myunset($var){

        if ($var){
            if (is_array($var)){
                $len = count($var);
                foreach ($var as $key=>$value){
                    unset($_SESSION[$var[$key]]);
                }
            }else{
                unset($_SESSION[$var]);
            }
        }else{
            return false;
        }
    }