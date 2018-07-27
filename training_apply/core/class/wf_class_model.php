<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 21/05/13
 * Time: 11:07 PM
 * To change this template use File | Settings | File Templates.
 */
class WF_Model extends WF_Database {
    protected $model_setting = null;      //当前 model 的设置
    protected $table_name    = '';        //当前 model 的数据表名称
    protected $primary_key   = '';        //当前 model 的数据表的主键名称

    public $model_name = ''; //当前 model 的名称

    /**
     * 构造函数
     * @param str $model_name  Model的名称
     * @param arr $db_setting  数据库连接设置
     */
    function __construct($model_name, $db_setting) {
        parent::__construct($db_setting);

        $this->model_setting = $this->_loadModelSetting($model_name);
        $this->table_name = $this->model_setting['table_name'];
        $this->primary_key = $this->model_setting['primary_key'];
        $this->model_name = $this->model_setting['name'];
    }

    /**
     * 读取 model 的配置
     * @param $model_name    Model的名称
     * @return bool | array
     */
    private function _loadModelSetting($model_name) {
        $file_model = WF_ROOT . "/model/wf_model_{$model_name}.php";
        if ( ! is_file($file_model) ) { return false; }
        include_once($file_model);
        return $WF_MODEL_SETTING;
    }

    /**
     * 生成错误提示信息
     * @param string $rule_name    规则的名称
     * @param string $field_label  字段在表单上显示的名称
     * @param string $field_limit  字段的限制（可选），比如长度限制，这里就要传递入长度的上限或下限
     * @return string
     */
    private function _fetchErrorMessage($rule_name, $field_label, $field_limit = '') {
        $arr_error_messages = array(
            'required'   => $field_label . '不能为空',
            'numeric'    => $field_label . '必须为纯数字',
            'length_max' => $field_label . '的长度不能超过' . $field_limit,
            'length_min' => $field_label . '的长度不能少于' . $field_limit,
            'regex'      => $field_label . '的格式不符合要求',
            'url'        => $field_label . '的格式不符合要求'
        );
        return $arr_error_messages[$rule_name];
    }

    /**
     * 校验 model 中的各项字段
     * @param string $field_name  字段名称
     * @param mixed  $field_data  对应的字段数据
     * @return bool | string      如果校验无误，返回 true；否则返回字符串形式的错误提示。
     */
    private function _checkModelField($field_name, & $field_data) {
        $setting = $this->model_setting['fields'][$field_name]; //获取该字段的验证规则
        $field_data = trim($field_data);

        //如果字段没有设定任何校验规则，那么表示该字段为一个数据表的主键；则返回 true，同时将其值设置为 null。
        if ( ! is_array($setting) || ! count($setting) ) {
            $field_data = 'null';
            return true;
        }

        //如果字段设置了默认值，但用户又没有输入任何信息
        if ( isset($setting['rules']['default']) && ! strlen($field_data) ) {
            $field_data = $setting['rules']['default'];
        }

        //如果字段为必填项
        if ( isset($setting['rules']['required']) && $setting['rules']['required'] == true) {
            if ( ! strlen($field_data) ) {
                return $this->_fetchErrorMessage('required', $setting['label']);
            }
        }

        //如果字段必须为纯数字
        if ( $field_data != '' && isset($setting['rules']['numeric']) && $setting['rules']['numeric'] == true) {
            if ( ! is_numeric($field_data) ) {
                return $this->_fetchErrorMessage('numeric', $setting['label']);
            }
        }

        //如果字段设置了长度上限
        if ( $field_data != '' && isset($setting['rules']['length_max']) && is_numeric($setting['rules']['length_max']) ) {
            if ( mb_strlen($field_data, 'UTF-8') > $setting['rules']['length_max'] ) {
                return $this->_fetchErrorMessage('length_max', $setting['label'], $setting['rules']['length_max']);
            }
        }

        //如果字段设置了长度下限
        if ( $field_data != '' && isset($setting['rules']['length_min']) && is_numeric($setting['rules']['length_min']) ) {
            if ( mb_strlen($field_data, 'UTF-8') < $setting['rules']['length_min'] ) {
                return $this->_fetchErrorMessage('length_min', $setting['label'], $setting['rules']['length_min']);
            }
        }

        //如果字段设置了正则表达式
        if ( $field_data != '' && isset($setting['rules']['regex']) && $setting['rules']['regex'] != '' ) {
            if ( ! preg_match($setting['rules']['regex'], $field_data) ) {
                return $this->_fetchErrorMessage('regex', $setting['label']);
            }
        }

        //如果字段设置了网址格式
        if ( $field_data != '' && isset($setting['rules']['url']) && $setting['rules']['url'] == true ) {
            $pattern = "/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
            if ( ! preg_match($pattern, $field_data) ) {
                return $this->_fetchErrorMessage('url', $setting['label']);
            }
        }

        return true;
    }

    /**
     * 格式化传入 SELECT 语句的字段名部分，为其添加“`”引号
     * @param $select_items_string
     * @return string
     */
    private function _formatSelectItems($select_items_string) {
        if ( stripos($select_items_string, ',') === false ) return $select_items_string;

        $arr_items = explode(',', $select_items_string);
        foreach ($arr_items as & $item) {
            $item = '`' + trim($item) + '`';
        }
        return implode(',', $arr_items);
    }

    /**
     * 列出当前 model 的设置
     * @param void
     */
    public function printModelSetting() {
        echo '<pre>';
        var_dump($this->model_setting);
        echo '</pre>';
    }

    /**
     * 根据 model 的设定，获取表单数据
     * @param array $form_post    提交的表单数据，通常是 $_POST
     * @param bool  $to_validate  是否在获取表单数据的过程中进行校验（默认为 true）
     * @return array | string     如果成功获取数据且校验无误，返回数组；否则返回字符串格式的错误提示信息。
     */
    public function getPost($form_post, $to_validate = true) {
        $data = array();

        foreach ( $this->model_setting['fields'] as $field_name => $field_data ) {
            if ( isset($form_post[$field_name]) ) {
                $data[$field_name] = $form_post[$field_name];
            }
        }

        if (! $to_validate ) return $data; //跳过校验，返回数组

        foreach ($data as $k => & $v) {
            $result_check = $this->_checkModelField($k, $v);
            if ( $result_check !== true ) {
                return $result_check; //发现错误，返回 false
            }
        }

        return $data; //校验无误，返回数组
    }

    /**
     * 读取该 model 中的一条记录
     * @param int    $primary_key   主键名称
     * @param string $select_items  所要筛选的字段（默认全部字段）
     * @return array
     */
    public function fetchOne($primary_key, $select_items = '*') {
        $select_part = '';
        $sql = '';

        $select_part = ($select_items == '*') ? '*' : $this->_formatSelectItems($select_items);
        $sql = "SELECT `{$select_part}` FROM `{$this->table_name}` WHERE `{$this->primary_key}` = '{$primary_key}'";

        parent::query($sql);
        return parent::fetchRow();
    }

    /**
     * 读取该 model 中的多条记录
     * @param int    $limit_start   读取记录时的起始位置（分页操作）
     * @param int    $limit_num     需要读取的记录数量（默认为0，即不读取任何记录）
     * @param string $order_by      排序的字段及方式（比如：“dateline DESC”）
     * @param string $select_items  所要筛选的字段（默认全部字段）
     * @return array
     */
    public function fetchList($limit_start = 0, $limit_num = 0, $order_by = '', $select_items = '*') {
        $select_part = '';
        $order_part = '';
        $limit_part = '';
        $sql = '';

        $select_part = ($select_items == '*') ? '*' : $this->_formatSelectItems($select_items);
        $order_part = ($order_by == '') ? '' : " {$order_by}";
        $limit_part = ($limit_num > 0) ? " LIMIT {$limit_start}, {$limit_num}" : false;

        if (! $limit_part) return false;

        $sql = "SELECT `{$select_part}` FROM `{$this->table_name}`" . $order_part . $limit_part;

        parent::query($sql);
        return parent::fetchAll();
    }

    /**
     * 获取当前 model 内的记录总数
     * @param string $where_clause  统计时的条件子句（如：“gender = 'male'”）
     * @return array
     */
    public function fetchTotalNum($where_clause = '') {
        $sql = "SELECT COUNT(*) AS `total` FROM `{$this->table_name}`";
        if ($where_clause != '') {
            $sql = $sql . " {$where_clause}";
        }

        parent::query($sql);
        return parent::fetchFirstColumn();
    }

    /**
     * 删除 model 内的一条或多条记录
     * @param mixed $primary_keys 单条或多条记录的主键（如：“27”或“26,27,28”或“array(26,27,28)”）
     * @return void
     */
    public function delete($primary_keys) {
        $ids = '';
        if ( is_array($primary_keys) ) {
            $ids = implode(',', $primary_keys);
        } else if ( stripos($primary_keys, ',') ) {
            $tmp_arr_ids = explode(',', $primary_keys);
            $tmp_arr_ids = array_map('trim', $tmp_arr_ids);
            $ids = implode(',', $tmp_arr_ids);
        } else {
            $ids = $primary_keys;
        }

        $sql = "DELETE FROM `{$this->table_name}` WHERE `{$this->primary_key}` IN ({$ids})";
        parent::query($sql);
    }

}