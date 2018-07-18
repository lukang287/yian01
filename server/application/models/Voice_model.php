<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/11
 */

class Voice_Model extends My_Model {

    private $table_name = "voice";
    private $field_return = array("voice_id","user_id","voice_text","create_time", "update_time");
    private $field_all = array("voice_id","user_id","voice_text","voice_status", "create_time", "update_time");

    //field要与允许的fieldlist相同
    public function insert($voice_item){
                if (!is_array($voice_item) || empty($voice_item)){
                        return false;
        }
        $insert_data = array();
        foreach ($this->field_all as $field){
                        if (isset($voice_item[$field])) $insert_data[$field] = $voice_item[$field];
        }
        return $this->db->insert($this->table_name, $insert_data);
    }

    //field要与允许的fieldlist相同
    public function insert_batch($voice_items){
                if (!is_array($voice_items) || empty($voice_items)){
                        return false;
        }
        $insert_data = array();
        foreach ($voice_items as $item){
                        $insert_v = array();
                        foreach ($this->field_all as $field){
                                if (isset($item[$field])) $insert_v[$field] = $item[$field];
            }
            $insert_data[] = $insert_v;
        }
        return $this->db->insert_batch($this->table_name, $insert_data);
    }

    /*limit array(length, offset=0)*/
    public function get_voice_by_user_id($user_id, $limit=array()){
                if (empty($user_id)) return array();
        $sql = "select ".implode(",", $this->field_return)." from ".$this->table_name." where user_id = ?";
        if (!empty($limit) && isset($limit[0])){
                        $offset = isset($limit[1])?$limit[1]:0;
                        $this->db->limit($limit[0], $offset);
                    }
        $query = $this->db->query($sql, array($user_id));
        return $query->result_array();
    }

    public function get_voice_by_voice_id($voice_id){
                if (empty($voice_id)) return array();
        $sql = "select ".implode(",", $this->field_return) . " from ".$this->table_name." where voice_id = ?";
        $query = $this->db->query($sql, array($voice_id));
        return $query->row();
    }

    //$where $update field要与允许的fieldlist相同
    public function update_voice_by_where($where_str, $update_arr){
                if (empty($where_str) || !is_array($update_arr) || empty($update_arr)) return false;
        $update_str = "";
        foreach ($update_arr as $field=>$value){
                        if (isset($this->field_return[$field])) $update_str .= "'$field' = '$value',";
        }
        $update_str = trim($update_str, ',');
        $sql = "update ".$this->table_name." set ".$update_str." where ".$where_str;
        return $this->db->query($sql);
    }

    //改变状态，不真删除
    public function delete_voice_by_where($where_str){
                if (empty($where_str)) return false;
        return $this->update_voice_by_where($where_str, array('voice_status'=>TABLE_ITEM_STATUS_DELETE));
    }

    //从db中删除数据
    public function drop_voice_by_where($voice_id){
                if(empty($voice_id)) return false;
        $this->db->where('voice_id', $voice_id);
        return $this->db->delete();
    }
} 