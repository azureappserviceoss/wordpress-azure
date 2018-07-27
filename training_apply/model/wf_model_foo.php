<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 21/05/13
 * Time: 11:27 PM
 * To change this template use File | Settings | File Templates.
 */
$WF_MODEL_SETTING['name'] = 'foo';

//数组中第一个 index 必须是表的主键；除主键外，其他字段必须设置至少一个验证规则。
$WF_MODEL_SETTING['fields'] = array(
    'id' => array(),
    'name' => array(
        'label' =>  '姓名',
        'rules' => array(
            'required' => true,
            'length_max' => 4,
            'length_min' => 2,
            'regex' => '',
            'default' => '',
            'numeric' => false,
            'email' => false,
            'url' => false
        )
    ),
    'age' => array(
        'label' => '年龄',
        'rules' => array(
            'numeric' => true
        )
    ),
    'gender' => array(
        'label' => '性别',
        'rules' => array(
            'required' => false,
            'default' => 'male'
        )
    ),
    'webstie' => array(
        'label' => '网址',
        'rules' => array(
            'required' => false,
            'url' => true,
            'default' => 'http://passg1.ca'
        )
    )
);

$WF_MODEL_SETTING['table_name'] = 'table_foo';