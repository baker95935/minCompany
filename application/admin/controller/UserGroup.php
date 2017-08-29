<?php
namespace app\admin\controller;
use app\admin\model\UserGroup as userGroupModel;

//用户类型表
class UserGroup extends Common
{

	public function index()
	{
		$list=array();
		$userGroups=new userGroupModel();
			
		$list=$userGroups->getListInfo($where=array());
	 
		$this->assign('list',$list);
		
		return view();
	}
	
	public function add()
	{
		$request = request();
		$id=$request->param('id');
		$userGroup= new userGroupModel();
		
		if($request->method()=='POST') {
			//数据获取
			$data=array(
					'group_name'=>$request->param('group_name'),
					'remark'=>$request->param('remark'),
					'id'=>$request->param('id'),
			);
				
			//数据校验
			$validate = validate('userGroup');
				
			if(!$validate->check($data)){
				$this->error($validate->getError());
					
			} else {
					
				$result=0;
				if(empty($id)){//添加
					$data['create_time']=time();
					$result=$userGroup->addInfo($data);
				} else {
					$result=$userGroup->addInfo($data,array('id'=>$id));//更新
				}
		
				if($result) {
					$this->success('操作成功', '/admin/userGroup/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/admin/userGroup/index/');
				}
			}
		
		}
			
		$data=array();
		!empty($id) && $data=UserGroupModel::get($id);
		
		$this->assign('data',$data);
		
		return view();
	}
	
	
	public function del()
	{
		$userGroup=new userGroupModel();
		$request = request();
		
		if($request->method()=='GET') {
				
			$id=$request->param('id');
			$result=0;
			$result=$userGroup->deleteInfo($id);
				
			if($result==0){
				$this->error('操作失败，请重试');
			} else {
				$this->success('操作成功', '/admin/userGroup/index/');
			}
		}
		
		$this->error('非法操作，请重试');
	}
}