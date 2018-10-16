<?php

/**
 * base MODEL
 */
pcBase::loadSysClass('db_mysqli','',0);

class baseModel
{

    protected $tableName = '';
    protected $db = '';
    protected $config = '';
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli');
    }


}