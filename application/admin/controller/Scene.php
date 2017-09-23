<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Scene as sceneModel;
class Scene extends Common
{  
	public function index()
	{
		$list=array();
		$scene=new sceneModel();
		
		$request = request();
		$search=$request->param('search');
	 
		!empty($search) && $where['name']=['like',"%".$search."%"];
		$where['id']=['>',0];
		
		$list=$scene->getListInfo($where,array('search'=>$search));
		$this->assign('list',$list);
		$this->assign('search',$search);
		
		return view();
	}

	public function add()
	{
		$scene=new sceneModel();
		$request = request();
		
		$id=$request->param('id');
 
		if($request->method()=='POST') {
			//数据获取
			$data=array(
				'type'=>$request->param('type'),
				'name'=>$request->param('name'),
				'id'=>$request->param('id'),
			);
			
			//数据校验
			$validate = validate('scene');
			
			if(!$validate->check($data)){
				$this->error($validate->getError());
			
			} else {
				 
				
				$result=0;
				if(empty($id)){//添加
					$data['create_time']=time();
					$result=$scene->addInfo($data);
				} else {
					$result=$scene->addInfo($data,array('id'=>$id));//更新
				}
				
				if($result) {
					$this->success('操作成功', '/admin/scene/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/admin/scene/index/');
				}
			}
	
		}
			
		$data=array();
		!empty($id) && $data=sceneModel::get($id);
 
		$this->assign('data',$data);
		return view();
	}
	
	public function del()
	{
		$scene=new sceneModel();
		$request = request();
		
		if($request->method()=='GET') {
			
			$id=$request->param('id');
			$result=0;
			$result=$scene->deleteInfo($id);
			
			if($result==0){
				$this->error('操作失败，请重试');
			} else {
				$this->success('操作成功', '/admin/scene/index/');
			}
		}
		
		$this->error('非法操作，请重试');
	}
}
