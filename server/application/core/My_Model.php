<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/11
 */


const TABLE_ITEM_STATUS_DELETE = -1;
const TABLE_ITEM_STATUS_OK = 0;

const SQL_WHERE_LIKE_BEFORE = "before";
const SQL_WHERE_LIKE_AFTER = "after";
const SQL_WHERE_LIKE_BOTH = "both";//默认

const SQL_WHERE_ORDER_BY_DESC = "desc";
const SQL_WHERE_ORDER_BY_ASC = "asc";

class My_Model extends CI_Model{

    public function __construct()
    {
        // init the CI_Model parent class
        parent::__construct();

        //$this->db->set_dbprefix('ya_');
        //$this->db->dbprefix('tablename'); // outputs newprefix_tablename
        //$this->load->database(); //手动链接db
    }


    protected function set_db_where(
        $and_where = array(),
        $or_where = array(),
        $and_where_in = array(),
        $or_where_in = array(),
        $and_where_not_in = array(),
        $or_where_not_in = array(),
        $and_where_like = array(),
        $or_where_like = array(),
        $and_where_not_like = array(),
        $or_where_not_like = array(),
        $group_by = '',
        $distinct = false,
        $having = array(),
        $or_having = array(),
        $order_by = array(),
        $limit = array()
    ){
        $this->_set_where_arr($and_where, "where");
        $this->_set_where_arr($or_where, "or_where");
        $this->_set_where_arr($and_where_in, "where_in");
        $this->_set_where_arr($or_where_in, "or_where_in");
        $this->_set_where_arr($and_where_not_in, "where_not_in");
        $this->_set_where_arr($or_where_not_in, "or_where_not_in");
        $this->_set_where_arr($and_where_like, "where_like");
        $this->_set_where_arr($or_where_like, "or_where_like");
        $this->_set_where_arr($and_where_not_like, "where_not_like");
        $this->_set_where_arr($or_where_not_like, "or_where_not_like");
        if (!empty($group_by))$this->db->group_by($group_by);
        if ($distinct) $this->db->distinct();
        $this->_set_where_arr($having, "having");
        $this->_set_where_arr($or_having, "or_having");
        $this->_set_where_arr($order_by, "order_by");
        if (count($limit)>1){
            $this->db->limit($limit[0], $limit[1]);//len, offset
        }else{
            $this->db->limit($limit[0]);
        }
    }

    private function _set_where_arr($where_arr, $func){
        if (!empty($where_arr) && is_array($where_arr)){
            foreach ($where_arr as $key=>$value){
                $this->db->$func($key, $value);
            }
        }
    }

    /*
     * return array('code'=> '', 'message'=> '');
     * */
    public function get_last_error(){
        $ret = $this->db->error();
        $last_query = $this->db->last_query();
        return array_merge($ret, array('sql'=>$last_query));
    }

    /*query 查询结果处理，results()返回查询对象数组，result_array()查询结果数组，row(i)返回第i个查询结果,
    row_nums()返回查询结果的行数， free_result()释放查询结果内存，*/

    /*$this->db->insert_id(), $this->db->affected_rows(), $this->db->last_query(), $this->db->count_all()总行数，*/

    /*支持事务： $this->db->trans_start() ，$this->db->trans_complete()，$this->db->trans_rollback();手动运行事务时，请务必使用 $this->db->trans_begin() 方法*/


    public function get_count_all($table_name=""){
        $table_name = empty($table_name)?$this->table_name:$table_name;
        return $this->db->count_all($table_name);
    }

    public function get_result_count(){
        return $this->db->count_all_results();;
    }
}