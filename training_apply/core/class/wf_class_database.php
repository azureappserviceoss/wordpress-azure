<?php
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 22/05/13
 * Time: 8:11 PM
 * To change this template use File | Settings | File Templates.
 */
class WF_Database {

    private $db_setting     = array();
    private $db_link        = null;
    private $count_query    = 0;
    private $query_resource = null;

    function __construct($db_setting) {
        $this->db_setting = $db_setting;

        //连接数据库服务器
        $this->db_link = @mysql_pconnect( $db_setting['db_host'], $db_setting['db_user'], $db_setting['db_pass'] );
        if ( ! is_resource($this->db_link) ) {
            $this->halt(0);
        }

        //切换到目标数据库
        $tmp_res_select_db = @mysql_select_db( $db_setting['db_name'], $this->db_link );
        if ( $tmp_res_select_db === false ) {
            $this->halt(1);
        }

        mysql_query("SET NAMES 'UTF8'");
    }

    public function __get($name) {
        return $this->$name;
    }

    /**
     * 保存数据
     * @param str $table_name 数据表名称
     * @param arr $data       存放待保存数据的数组，数组索引即数据表字段名
     * @return int            返回 insert_id 值
     */
    public function save($table_name, $data) {
        $sql_fields = implode( '`,`', array_keys($data) );
        $sql_values = implode("','", $data);
        $sql = "INSERT INTO `{$table_name}` (`{$sql_fields}`) VALUES ('{$sql_values}')";

        $this->query($sql);
        return $this->insert_id();
    }

    /**
     * 返回插入数据后的 insert_id 值
     * @return int
     */
    public function insert_id() {
        return mysql_insert_id($this->db_link);
    }

    /**
     * 执行一条 SQL 语句
     * @param str $sql
     * @return resource
     */
    public function query($sql) {
        $this->count_query++;
        return $this->query_resource = mysql_query($sql, $this->db_link);
    }

    /**
     * 执行一条 SQL 语句，并获取全部结果集
     * @param str $sql
     * @return array|bool
     */
    public function fetchAll($sql) {
        $result = array();

        $this->query($sql);
        while ( $row = mysql_fetch_array($this->query_resource, MYSQL_ASSOC) ) {
            $result[] = $row;
        }

        return count($result) > 0 ? $result : false;
    }

    public function fetchRow() {

    }

    public function fetchFirstColumn() {

    }

    /**
     * 数据库连接失败时，显示错误提示并终止程序运行
     * @param int $state_code 错误代码
     */
    protected function halt($state_code) {
        $msg = '';

        switch ($state_code) {
            case 0:
                $msg = 'Cannot connect to the database server.';
                break;
            case 1:
                $msg = "The specified name of database['{$this->db_setting['db_name']}'] does not exist.";
                break;
        }

        echo $msg;
        die();
    }

}