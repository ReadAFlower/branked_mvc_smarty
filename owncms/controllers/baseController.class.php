<?php
/**
 * 基础控制器
 */

class baseController
{
    public $urlList=[];
        public function __construct()
        {
            $this->urlList['goback'] = 'javaScript:history.go(-1);';
        }


}