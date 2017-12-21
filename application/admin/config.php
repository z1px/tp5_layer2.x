<?php
//配置文件
return [

    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__JS__'=>'/static/js',
        '__PLUGINS__'=>'/static/admin/plugins',
        '__ADMIN__'=>'/static/admin',
        '__ADMIN_CSS__'=>'/static/admin/css',
        '__ADMIN_JS__'=>'/static/admin/js',
        '__ADMIN_IMAGE__'=>'/static/admin/images',
        '__ADMIN_IMG__'=>'/static/admin/img',
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => 'public:jump',
    'dispatch_error_tmpl'    => 'public:jump',

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH."admin".DS,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH."admin".DS,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],


    //后台账号密码加密密钥
    'login_key'=>'757319b447175b6ca1882635b132a594',

];