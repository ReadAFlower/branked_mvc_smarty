<?php

pcBase::loadSysClass('baseModel','models/',0);

class menuModel extends baseModel
{
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);
        $this->tableName = 'menu';
        parent::__construct();
    }

    /**
     * 根据权限返回相应的菜单内容
     * @param $level
     * @return mixed
     */
    public function getMenuList($level){
        if (is_numeric($level)){
            $num = intval($level);
        }else{
            $num = $this->levelToNum($level);
        }
        $menuData = $this->db -> select('*', $this->tableName, 'level > '.intval($num));

        return $menuData;
    }

    /**
     * 权限转数字
     * @param $level
     * @return int
     */
    public function levelToNum($level){
        switch ($level){
            case '超级管理员';
                $num = 0;
                break;
            case '高级管理员':
                $num = 1;
                break;
            case '普通管理员':
                $num = 2;
                break;
            default:
                $num = 3;
                break;
        }

        return intval($num);
    }

    /**
     * 添加菜单
     * @param $data
     * @return bool
     */
    public function addMenu($data){
        if(!is_array( $data )  || count($data) == 0) {
            return false;
        }else{
            $res = $this->db->insert($data,$this->tableName);
            if ($res){
                return $res;
            }else{
                return false;
            }
        }

    }

    /**
     * 权限等级列表
     * @return array
     */
    public function getAllLevel(){
        $sql = 'show columns from '.$this->tableName.' like "level"';
        $res = $this -> db -> query($sql);
        while ($arr = mysqli_fetch_assoc($res)){
            $resLevel = $arr['Type'];
        }
        $resLevel = str_replace(["enum",")","(","'"],'',$resLevel);
        $level = explode(',',$resLevel);

        return $level;
    }

    /**
     * 更新菜单列表session数据
     * @param $level
     */
    public function updateSessionMenuList($level){
        $newMenuList = $this->getMenuList($level);

        if($newMenuList){
            $_SESSION['menu'.HASH_IP] = $newMenuList;
            return true;
        }else{
            return false;
        }
    }

    public function deleteMenu($id){
        if (is_numeric($id) && $id>0){
            $id = intval(ceil($id));
            $where = ' id = '.$id.'  or  parentID = '.$id;
            $res = $this->db->delete($this->tableName,$where);

            if ($res){
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

}