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

    public function getEnum($column)
    {
        $sql = ' show columns from '.$this->tableName.' like "'.$column.'" ';

        $res = $this->db->query($sql);
        while ($arr = mysqli_fetch_assoc($res)){
            $columnVal = $arr['Type'];
        }

        $columnVal = str_replace(["enum",")","(","'"],'',$columnVal);
        $valArr = explode(',',$columnVal);
        $len = count($valArr);
        $res = null;
        for ($i=1;$i<=$len;$i++){
            $res[$i]=$valArr[($i-1)];
        }

        return $res;
    }

}