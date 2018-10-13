<?php
/**
 * 基础控制器
 */

class baseController
{
        public function __construct()
        {
            $db = pcBase::loadSysClass('db_mysqli');
        }
}