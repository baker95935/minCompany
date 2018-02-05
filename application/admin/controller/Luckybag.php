<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Luckybag as luckybagModel;

class Luckybag extends Common
{
  public function index()
	{    
		$list=array();
		$luckybag=new luckybagModel();
		
		$request = request();
		$search=$request->param('search');
	 
		$where['id']=['>',0];
		
		$list=$luckybag->getListInfo($where,array('search'=>$search));
		$this->assign('list',$list);
		$this->assign('search',$search);
		
		return view();
	}

	public function add()
	{
		$luckybag=new luckybagModel();
		$request = request();
		
		$id=$request->param('id');
	 
		if($request->method()=='POST') {
			//数据获取
			$data=array(
				'name'=>$request->param('name'),
				'link'=>$request->param('link'),
				'is_show'=>$request->param('is_show'),
				'id'=>$request->param('id'),
			);
			
 
			//视频封面上传3个
			$file = $request->file('pic');
			if(!empty($file)) {
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
			        $data['pic']="uploads/".$info->getSaveName();
			    }
			}
			 
		 
		 
		 
			//数据校验
			$validate = validate('Luckybag');
			
			if(!$validate->check($data)){
				$this->error($validate->getError());
			
			} else {
				 
				
				$result=0;
				if(empty($id)){//添加
					$data['create_time']=time();
					$result=$luckybag->addInfo($data);
				} else {
					$result=$luckybag->addInfo($data,array('id'=>$id));//更新
				}
				
				if($result) {
					$this->success('操作成功', '/admin/luckybag/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/admin/luckybag/index/');
				}
			}
	
		}
			
		$data=array();
		!empty($id) && $data=luckybagModel::get($id);
 
		$this->assign('data',$data);
		return view();
	}
	
	public function del()
	{
		$luckybag=new luckybagModel();
		$request = request();
		
		if($request->method()=='GET') {
			
			$id=$request->param('id');
			$result=0;
			$result=$luckybag->deleteInfo($id);
			
			if($result==0){
				$this->error('操作失败，请重试');
			} else {
				$this->success('操作成功', '/admin/luckybag/index/');
			}
		}
		
		$this->error('非法操作，请重试');
	}
}
