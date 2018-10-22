<?php

pcBase::loadSysClass('baseModel','models/',0);
pcBase::loadSysClass('adminModel','models/',0);
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
            $adminModel = new adminModel();
            $num = $adminModel->levelToNum($level);
        }
        $menuData = $this->db -> select('*', $this->tableName, 'level > '.intval($num));

        return $menuData;
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

    /**
     * 菜单信息修改
     * @param $data 修改数据
     * @param $id   信息id
     * @return bool
     */
    public function updateMenu($data,$id)
    {
        if (isset($id) && !empty($id)){
            $where = ' id = '.$id;
            $res = $this->db->update($data, $this->tableName,$where);
            if ($res){
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 获取单条信息
     * @param $id
     */
    public function getOne($id)
    {
        $id = safe_replace($id);
        $where = ' id = '.$id;
        $res = $this->db->get_one('*',$this->tableName,$where);

        if ($res){
            $where = ' id = '.$res['parentID'];
            $resParent = $this->db->get_one('zh_name',$this->tableName,$where);
            if ($resParent){
                $res['parentName'] = $resParent['zh_name'];
            }else{
                $res['parentName'] = '无';
            }
            return $res;
        }else{
            return false;
        }


    }
}