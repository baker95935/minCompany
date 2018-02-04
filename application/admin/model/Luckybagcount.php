<?php

namespace app\admin\model;

use think\Model;

class Luckybagcount extends Model
{
    //����,����
	public function addInfo($data,$where=array())
	{
		$result=0;
		!empty($data) && $result=$this->save($data,$where);
 		return $result;
	}
	
	//ɾ��
	public	function deleteInfo($id)
	{
		$result=0;
		$id && $result=$this->destroy($id);
		
		return $result;
	}
	
	//�б�
	public function getListInfo($where,$query)
	{
		$list=array();
		$list = Count::where($where)->order('id desc')->paginate(array('list_rows'=>10,'query'=>$query)); // ��ҳ��url�������);
		return $list;
	}
	
	//�ܼ���
	public function listCount($where)
	{
		$count=0;
		$count=Count::where($where)->count();
		return $count;
	}
}
