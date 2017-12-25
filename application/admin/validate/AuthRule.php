<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/14
 * Time: 13:48
 */

namespace app\admin\validate;


use think\Validate;

class AuthRule extends Validate {

    // 验证规则
    protected $rule = [
        'id'=>'require|integer',
        'name'=>'require|unique:auth_rule,name',
        'title'=>'require',
        'type'=>'require|number',
        'condition'=>'require',
        'status'=>'in:0,1',
    ];

    // 错误提示
    protected $message = [

    ];

    // 验证场景
    protected $scene = [
        'add'=>['name','title','type','condition','status'],
        'edit'=>['id','name','title','type','condition','status'],
        'edit_status'=>['id','status'],
    ];

}