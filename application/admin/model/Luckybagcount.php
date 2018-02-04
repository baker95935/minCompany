<?php

namespace app\admin\model;

use think\Model;

class Luckybagcount extends Model
{
    //新增,更新
	public function addInfo($data,$where=array())
	{
		$result=0;
		!empty($data) && $result=$this->save($data,$where);
 		return $result;
	}
	
	//删除
	public	function deleteInfo($id)
	{
		$result=0;
		$id && $result=$this->destroy($id);
		
		return $result;
	}
	
	//列表
	public function getListInfo($where,$query)
	{
		$list=array();
		$list = Count::where($where)->order('id desc')->paginate(array('list_rows'=>10,'query'=>$query)); // 分页的url额外参数);
		return $list;
	}
	
	//总计数
	public function listCount($where)
	{
		$count=0;
		$count=Count::where($where)->count();
		return $count;
	}
}
