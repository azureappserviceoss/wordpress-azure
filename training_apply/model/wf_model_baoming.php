<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 21/05/13
 * Time: 11:27 PM
 * To change this template use File | Settings | File Templates.
 */
$WF_MODEL_SETTING['name'] = 'baoming';

//数组中第一个 index 必须是表的主键；除主键外，其他字段必须设置至少一个验证规则。
$WF_MODEL_SETTING['fields'] = array(
    'id' => array(),
    'name' => array(
        'label' =>  '姓名',
        'rules' => array(
            'required' => true
        )
    ),
    'age' => array(
        'label' => '年龄',
        'rules' => array(
            'required' => true,
            'numeric' => true
        )
    ),
    'phone' => array(
        'label' => '电话',
        'rules' => array(
            'required' => true
        )
    ),
    'email' => array(
        'label' => '邮件',
        'rules' => array(
            'required' => true
        )
    ),
    'address' => array(
        'label' => '地址',
        'rules' => array(
            'required' => true
        )
    ),
    'level' => array(
        'label' => '基本水平',
        'rules' => array(
            'required' => true
        )
    ),
    'site' => array(
        'label' => '培训地点',
        'rules' => array(
            'required' => true
        )
    ),
    'advice' => array(
        'label' => '建议',
        'rules' => array(
            'length_min' => 1,
            'length_max' => 1000
        )
    ),
    'emergency_name' => array(
        'label' =>  '紧急联系人姓名',
        'rules' => array(
            'required' => true
        )
    ),
    'emergency_phone' => array(
        'label' => '紧急联系人电话',
        'rules' => array(
            'required' => true
        )
    )
);

$WF_MODEL_SETTING['table_name'] = 'mj_baoming';
$WF_MODEL_SETTING['primary_key'] = 'id';