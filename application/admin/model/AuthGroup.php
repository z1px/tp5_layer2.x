<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 2017/12/25
 * Time: 23:35
 */

namespace app\admin\model;


use think\Model;

class AuthGroup extends Model {

    // 设置数据表（不含前缀）
    protected $name = 'auth_group';
    // 设置当前模型对应的完整数据表名称
//    protected $table = 'tp5_auth_group';
    // 设置主键
    protected $pk = 'id';
    // 类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y-m-d H:i:s',
        'update_time'  =>  'timestamp:Y-m-d H:i:s',
    ];

    // 关闭自动写入create_time字段
//    protected $createTime = false;
    // 关闭自动写入update_time字段
//    protected $updateTime = false;

    //自动完成包含新增和更新操作
    protected $auto = [];
    //自动完成包含新增操作
    protected $insert = [];
    //自动完成包含更新操作
    protected $update = [];

    //返回结果
    protected $result=[
        "code"=>1,
        "msg"=>"data normal",
        "data"=>[],
    ];

    //状态
    public $list_status=[
        1=>"正常",
        2=>"禁用",
    ];

    protected function getStatusNameAttr($value,$data) {
        if(!isset($data["status"])) return "未知状态";
        return isset($this->list_status[$data["status"]])?$this->list_status[$data["status"]]:"未知状态";
    }

}