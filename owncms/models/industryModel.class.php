<?php

pcBase::loadSysClass('baseModel','models/',0);
class industryModel extends baseModel
{
    public function __construct()
    {
        $this->db = pcBase::loadSysClass('db_mysqli','',1);
        $this->tableName = 'industry_type';
        parent::__construct();
    }

    /**
     * 获取行业分类列表
     */
    public function getIndustryList(){
        $List = $this->db->select('*', $this->tableName);
        if ($List){
            return $List;
        }else{
            return false;
        }

    }

    /**
     * 添加分类
     * @param $data
     * 返回插入记录ID值
     */
    public function addIndustryList($data){
        if (!intval($data['type_num'])) return false;
        $res = $this->db->insert($data, $this->tableName);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 修改分类
     * @param $data
     */
    public function updateIndustryList($data,$typeID){
        if (!intval($typeID)) return false;
        $where = 'type_id = '.intval($typeID);
        $res = $this->db->update($data, $this->tableName, $where);

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 删除分类
     * @param $data
     */
    public function delIndustry($where){
        $res = $this->db->delete($this->tableName, safe_replace($where));

        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 获取单条信息
     * @param $typeID
     */
    public function getIndustryRes($typeID)
    {
        $typeID = intval($typeID);
        if (!$typeID) return false;
        $where = 'type_id = '.$typeID;

        $res = $this->db->get_one('*', $this->tableName, $where);
        if ($res){
            return $res;
        }else{
            return false;
        }
    }
}