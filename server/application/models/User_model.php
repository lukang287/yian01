<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/24
 */

class User_model extends MY_Model{

    private $table_name = "user";
    private $field_return = array("user_id","open_id", "nick_name", "province", "logo_url", "create_time", "last_visit_time");
    private $field_all = array("user_id","open_id", "nick_name", "province", "logo_url", "create_time", "last_visit_time");

    public function count_user_by_open_id($open_id){
        if (empty($open_id)) return 0;
        $this->db->where(array('open_id'=>$open_id));
        return $this->db->count_all();
    }

    public function insert_user($user_item){
        if (!is_array($user_item) || empty($user_item)){
            return false;
        }
        $insert_data = array();
        foreach ($this->field_all as $field){
            if (isset($user_item[$field])) $insert_data[$field] = $user_item[$field];
        }
        return $this->db->insert($this->table_name, $insert_data);
    }

    public function update_user_by_open_id($open_id, $user_item){
        if (empty($open_id) || empty($user_item) || !is_array($user_item)){
            return false;
        }
        $this->db->where(array('open_id', $open_id));
        $update_data = array();
        foreach ($this->field_all as $field){
            if ($field == 'open_id') continue;
            if (isset($user_item[$field])) $update_data[$field] = $user_item[$field];
        }
        return $this->db->update($this->table_name, $update_data);
    }

    public function select_user_by_open_id($open_id, $return_field=array()){
        if (empty($open_id)) return null;
        if (empty($return_field)) $return_field = $this->field_return;
        $new_ret = array();
        foreach ($return_field as $field){
            if (in_array($field, $this->field_return)) $new_ret[] = $field;
        }
        $this->db->where(array('open_id'=>$open_id));
        $this->db->select($new_ret);
        $query = $this->db->get($this->table_name);
        foreach ($query->result() as $row)
        {
            return $row;
        }
        return null;
    }

}