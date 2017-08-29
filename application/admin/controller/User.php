<?php
namespace app\admin\controller;
use app\admin\model\User as userModel;
use app\admin\model\UserGroup as userGroupModel;

//用户表
class User extends Common
{
	public function index()
	{
		$list=array();
		$users=new userModel();
		
		$request = request();
		$search=$request->param('search');
	 
		!empty($search) && $where['username']=['like',"%".$search."%"];
		$where['id']=['>',0];
		
		$list=$users->getListInfo($where,array('search'=>$search));
		$this->assign('list',$list);
		$this->assign('search',$search);
		
		return view();
	}

	public function add()
	{
		$user=new userModel();
		$userGroups=new userGroupModel();
		$request = request();
		
		$id=$request->param('id');
	 
		if($request->method()=='POST') {
			//数据获取
			$data=array(
				'username'=>$request->param('username'),
				'email'=>$request->param('email'),
				'group'=>$request->param('group'),
				'password'=>md5($request->param('password')),
				'confirmPassword'=>md5($request->param('confirmPassword')),
				'id'=>$request->param('id'),
			);
			
			//数据校验
			$validate = validate('user');
			
			if(!$validate->check($data)){
				$this->error($validate->getError());
			
			} else {
				 
				unset($data['confirmPassword']);
				
				$result=0;
				if(empty($id)){//添加
					$data['create_time']=time();
					$result=$user->addInfo($data);
				} else {
					$result=$user->addInfo($data,array('id'=>$id));//更新
				}
				
				if($result) {
					$this->success('操作成功', '/admin/user/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/admin/user/index/');
				}
			}
	
		}
			
		$data=array();
		!empty($id) && $data=UserModel::get($id);
		$listGroup=$userGroups->getListInfo($where=array());
 
		$this->assign('data',$data);
		$this->assign('listGroup',$listGroup);
		return view();
	}
	
	public function del()
	{
		$user=new userModel();
		$request = request();
		
		if($request->method()=='GET') {
			
			$id=$request->param('id');
			$result=0;
			$result=$user->deleteInfo($id);
			
			if($result==0){
				$this->error('操作失败，请重试');
			} else {
				$this->success('操作成功', '/admin/user/index/');
			}
		}
		
		$this->error('非法操作，请重试');
	}
}