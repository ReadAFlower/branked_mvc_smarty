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

    /**
     * enum值获取
     * @param $column
     * @return null
     */
    public function getEnum($column)
    {
        if (!safe_replace($column)) return false;
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

    /**
     * 密码加密生成
     * @param $pwd
     * @return bool|string
     */
    public function createPWD($pwd)
    {
        $pwd = safe_replace($pwd);
        $pwd = substr(md5($pwd), 12,18);
        $hash = substr('owmcms', 3, 12);
        $pwd = substr(md5($pwd.$hash),5,24);

        return $pwd;
    }

}