<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/14
 * Time: 11:33
 */

namespace app\admin\service;


use \app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthGroupAccess;
use think\Loader;

class AuthGroup extends AuthGroupModel {

    public function add($params){
        $params=params_format($params);
        $validate = Loader::validate('AuthGroup');
        if(!$validate->scene("add")->check($params)){
            $this->result["code"]=0;
            $this->result["msg"]=$validate->getError();
            unset($validate);
            return $this->result;
        }
        $this->allowField(true)->isUpdate(false)->save($params);
        if(empty($this->id)){
            $this->result["code"]=0;
            $this->result["msg"]="新增失败";
        }else{
            $this->result["msg"]="新增成功";
        }
        return $this->result;
    }

    public function edit($params){
        $params=params_format($params);
        $validate = Loader::validate('AuthGroup');
        if(!$validate->scene("edit")->check($params)){
            $this->result["code"]=0;
            $this->result["msg"]=$validate->getError();
            unset($validate);
            return $this->result;
        }
        $id=$params["id"];
        unset($params["id"]);
        $data=$this->find($id);
        if(empty($data)){
            $this->result["code"]=0;
            $this->result["msg"]="数据不存在，修改失败";
            return $this->result;
        }
        $res=$data->allowField(true)->save($params);
        if(empty($res)){
            $this->result["code"]=0;
            $this->result["msg"]="修改失败";
        }else{
            $this->result=$this->getById($id);
            $this->result["msg"]="修改成功";
        }
        return $this->result;
    }

    public function getById($id){
        $data=$this->field("id,title,status,rules,create_time,update_time")->find($id);
        if(empty($data)){
            $this->result["code"]=0;
            $this->result["msg"]="用户组不存在";
        }else{
            $this->result["data"]=$data->append(["status_name"])->toArray();
        }
        unset($data);
        return $this->result;
    }

    public function del($id){
        $data=$this->field("id,title,status,rules,create_time,update_time")->find($id);
        if(empty($data)){
            $this->result["code"]=0;
            $this->result["msg"]="用户组不存在";
            return $this->result;
        }
        $res=$data->delete();
        unset($data);
        if(empty($res)){
            $this->result["code"]=0;
            $this->result["msg"]="删除失败";
        }else{
            $this->result["msg"]="删除成功";
        }
        unset($data);
        return $this->result;
    }

    public function editStatus($params){

        $validate = Loader::validate('AuthGroup');
        if(!$validate->scene("edit_status")->check($params)){
            $this->result["code"]=0;
            $this->result["msg"]=$validate->getError();
            unset($validate);
            return $this->result;
        }
        $id=$params["id"];
        unset($params["id"]);
        $data=$this->field("id,status")->find($id);
        if(empty($data)){
            $this->result["code"]=0;
            $this->result["msg"]="数据不存在，修改失败";
            return $this->result;
        }
        $res=$data->allowField(["status","update_time"])->save($params);
        if(empty($res)){
            $this->result["code"]=0;
            $this->result["msg"]="修改失败";
        }else{
            $this->result["msg"]="修改成功";
        }
        return $this->result;
    }

    public function getList($params){

        if(!isset($params["page"])) $params["page"]=1;
        if(!isset($params["limit"])) $params["limit"]=10;
        $where=[];
        $order="id desc";
        if(isset($params["keyword"]) && !empty($params["keyword"])) $where["title|rules"]=["like","%{$params["keyword"]}%"];
        array_map(function ($value) use (&$where,$params){
            if(isset($params[$value]) && !empty($params[$value])) $where[$value]=["like","%{$params[$value]}%"];
        },
            ["title","rules"]
        );
        array_map(function ($value) use (&$where,$params){
            if(isset($params[$value]) && !empty($params[$value])) $where[$value]=$params[$value];
        },
            ["status"]
        );
        if(!isset($params["begin_time"])) $params["begin_time"]="";
        if(!isset($params["end_time"])) $params["end_time"]="";
        if(!empty($params["begin_time"])&&empty($params["end_time"])){
            $where["create_time"]=["egt",strtotime($params["begin_time"]." 00:00:00")];
        }elseif(empty($params["begin_time"])&&!empty($params["end_time"])){
            $where["create_time"]=["elt",strtotime($params["end_time"]." 23:59:59")];
        }elseif(!empty($params["begin_time"])&&!empty($params["end_time"])){
            $where["create_time"]=["between",[strtotime($params["begin_time"]." 00:00:00"),strtotime($params["end_time"]." 23:59:59")]];
        }
        if(isset($params["field"])){
            if(!empty($params["field"])){
                if($params["field"]=="status_name") $params["field"]="status";
                $order="{$params["field"]} {$params["order"]}";
            }
        }
        $list=$this->field("id,title,status,rules,create_time,update_time")->where($where)->page($params["page"],$params["limit"])->order($order)->select();
        $this->result["count"]=$this->where($where)->Count();
        unset($params,$where);
        if(empty($list)){
            $list=[];
        }else{
            foreach ($list as $key=>$value){
                $list[$key]=$value->append(["status_name"])->toArray();
            }
            unset($key,$value);
        }
        $this->result["list"]=$list;
        unset($list);
        return $this->result;
    }

    /**
     * 给用户分配用户组
     * @param $params
     * @return array
     */
    public function editGroupAccess($params){
        $params=params_format($params);
        $validate = Loader::validate('AuthGroupAccess');
        if(!$validate->check($params)){
            $this->result["code"]=0;
            $this->result["msg"]=$validate->getError();
            unset($validate);
            return $this->result;
        }
        $uid=$params["id"];
        $group_ids=[];
        if(isset($params["group_id"]) && !empty($params["group_id"])){
            $group_ids=is_array($params["group_id"])?array_keys($params["group_id"]):[$params["group_id"]];
        }
        $authGroupAccess = new AuthGroupAccess();
        if($group_ids){
            $authGroupAccess->where(["uid"=>$uid,"group_id"=>["not in",$group_ids]])->delete();
            $group_ids_in = $authGroupAccess->where(["uid"=>$uid])->column("group_id","id");
            if($group_ids_in) $group_ids = array_diff($group_ids,array_values($group_ids_in)); //求差集，即未分配的用户组
            unset($group_ids_in);
            if($group_ids){
                $rows=[];
                foreach ($group_ids as $value){
                    $rows[]=["uid"=>$uid,"group_id"=>$value];
                }
                $res = $authGroupAccess->saveAll($rows);
            }else{
                $res = true;
            }
        }else{
            $res = $authGroupAccess->where(["uid"=>$uid])->delete();
        }
        unset($params["id"]);

        if(empty($res)){
            $this->result["code"]=0;
            $this->result["msg"]="修改失败";
        }else{
            $admin = new Admin();
            $this->result=$admin->getById($uid);
            $this->result["msg"]="修改成功";
        }
        return $this->result;
    }


    public function getAll($params){
        $list=$this->order("id asc")->column("title,status,rules","id");
        if($list){
            $groupAccess = [];
            if(isset($params["id"]) && !empty($params["id"])){
                $authGroupAccess = new AuthGroupAccess();
                $groupAccess = $authGroupAccess->where(["uid"=>$params["id"]])->column("group_id","id");
                unset($authGroupAccess);
            }
            foreach ($list as $key=>$value){
                $list[$key]["checked"] = in_array($value["id"],$groupAccess)?"checked":"";
                $list[$key]["disabled"] = $value["status"]==1?"":"disabled";
            }
        }
        return array_values($list);
    }

}