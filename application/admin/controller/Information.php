<?php

namespace app\admin\controller;

use app\admin\controller\Common;
use think\Request;
use app\admin\model\Information as informationModel;

//信息
class Information extends Common
{
     public function index()
	{
		$list=array();
		$information=new informationModel();
		
		$request = request();
		$search=$request->param('search');
	 
		!empty($search) && $where['title']=['like',"%".$search."%"];
		$where['id']=['>',0];
		
		$list=$information->getListInfo($where,array('search'=>$search));
		$this->assign('list',$list);
		$this->assign('search',$search);
		
		return view();
	}

	public function add()
	{
		$information=new informationModel();
		$request = request();
		
		$id=$request->param('id');
	 
		if($request->method()=='POST') {
			//数据获取
			$data=array(
				'username'=>$request->param('username'),
				'password'=>md5($request->param('password')),
				'confirmPassword'=>md5($request->param('confirmPassword')),
				'realname'=>$request->param('realname'),
				'phone'=>$request->param('phone'),
				'status'=>$request->param('status'),
				'id'=>$request->param('id'),
			);
			
			//数据校验
			$validate = validate('member');
			
			if(!$validate->check($data)){
				$this->error($validate->getError());
			
			} else {
				 
				unset($data['confirmPassword']);
				
				$result=0;
				if(empty($id)){//添加
					$data['create_time']=time();
					$result=$information->addInfo($data);
				} else {
					$result=$information->addInfo($data,array('id'=>$id));//更新
				}
				
				if($result) {
					$this->success('操作成功', '/admin/information/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/admin/information/index/');
				}
			}
	
		}
			
		$data=array();
		!empty($id) && $data=informationModel::get($id);
 
		$this->assign('data',$data);
		return view();
	}
	
	public function del()
	{
		$information=new informationModel();
		$request = request();
		
		if($request->method()=='GET') {
			
			$id=$request->param('id');
			$result=0;
			$result=$information->deleteInfo($id);
			
			if($result==0){
				$this->error('操作失败，请重试');
			} else {
				$this->success('操作成功', '/admin/member/index/');
			}
		}
		
		$this->error('非法操作，请重试');
	}
}
