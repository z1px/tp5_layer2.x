<?php

/**
 * 处理
 * @param $data 待处理一维数组
 * @param array $filter 列表中键值如果为空则删除，否则返回null
 * @return array
 */
function params_format($data,$filter=[]){
    if(!empty($data)&&is_array($data)){
        foreach ($data as $key=>$value){
            if(is_array($value)) continue;
            $value=trim($value);
            if($value==""){
                if(in_array($key,$filter)){
                    unset($data[$key]);
                }else{
                    $data[$key]=null;
                }
            }else{
                $data[$key]=$value;
            }
        }
    }
    return $data;
}
/**
 * 数组NULL处理
 * @param $data 待处理数组
 * @param array $filter 列表中键值如果为null则修改成空字符串""
 * @return array
 */
function result_format($data){
    if(!empty($data)&&is_array($data)){
        foreach ($data as $key=>$value){
            if(is_array($value)){
                $data[$key]=result_format($value);
            }else{
                $value=trim($value);
                if(is_null($value)){
                    $data[$key]="";
                }else{
                    $data[$key]=$value;
                }
            }
        }
    }
    return $data;
}

/**
 * 数据库密码加密
 * @param $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @return string
 */
function auth_code($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
    $ckey_length = 0;
    $key = md5($key ? $key : '9e13yK8RN2M0lKP8CLRLhGs468d1WMaSlbDeCcI');
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = $j % 256;
        $result .= chr(ord($string[$i]));
    }
    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}


/**
 * 递归重组节点信息多维数组
 * @param  [array] $node [要处理的节点数组:二维数组]
 * @param  [int]   $pid  [父级ID]
 * @return [array]       [树状结构的节点体系:多维数组]
 */
if(!function_exists('node_merge')){
    function node_merge($node,$pid=0){
        $arr=array();
        foreach ($node as $v) {
            if ($v['pid']==$pid) {
                $v['child']=node_merge($node,$v['id']);
                $arr[]=$v;
            }
        }

        return $arr;
    }
}

//将时间区间转成时间列表
function list_Ymd($begin, $end = true, $range = 86400) {
    $begin = strtotime($begin)?strtotime($begin):$begin;
    if(true === $end){
        $end=time();
    }else{
        $end = strtotime($end)?strtotime($end):$end;
    }
    $list = array_map(function ($time) {
        return date('Ymd', $time);
    }, range($begin, $end, $range));
    rsort($list);
    return $list;
}