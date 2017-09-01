<?php

namespace app\admin\controller;

use app\admin\controller\Common;
use think\Request;
use app\admin\model\Brand as brandModel;
use think\File;

//品牌信息
class Brand extends Common
{
  public function index()
	{
		$list=array();
		$brands=new brandModel();
		
		$request = request();
		$search=$request->param('search');
	 
		$where['id']=['>',0];
		
		$list=$brands->getListInfo($where,array('search'=>$search));
		$this->assign('list',$list);
		$this->assign('search',$search);
		
		return view();
	}

	public function add()
	{
		$brand=new brandModel();
		$request = request();
		
		$id=$request->param('id');
	 
		if($request->method()=='POST') {
			//数据获取
			$data=array(
				'name'=>$request->param('name'),
				'location'=>$request->param('location'),
				'title'=>$request->param('title'),
				'content'=>$request->param('content'),
				'status'=>$request->param('status'),
				'video1'=>$request->param('video1'),
				'video2'=>$request->param('video2'),
				'video3'=>$request->param('video3'),
				'id'=>$request->param('id'),
			);
			
 
			//视频封面上传3个
			$file = $request->file('video1pic');
			if(!empty($file)) {
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
			        $data['video1pic']="uploads/".$info->getSaveName();
			    }
			}
			 
		    
	 
			//视频封面上传3个
			$file = $request->file('video2pic');
			if(!empty($file)) {
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
			        $data['video2pic']="uploads/".$info->getSaveName();
			    }
			}
			 
		    
	 
			//视频封面上传3个
			$file = $request->file('video3pic');
			if(!empty($file)) {
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
			        $data['video3pic']="uploads/".$info->getSaveName();
			    }
			}
		 
		 
			//数据校验
			$validate = validate('brand');
			
			if(!$validate->check($data)){
				$this->error($validate->getError());
			
			} else {
				 
				
				$result=0;
				if(empty($id)){//添加
					$data['create_time']=time();
					$result=$brand->addInfo($data);
				} else {
					$result=$brand->addInfo($data,array('id'=>$id));//更新
				}
				
				if($result) {
					$this->success('操作成功', '/admin/brand/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/admin/brand/index/');
				}
			}
	
		}
			
		$data=array();
		!empty($id) && $data=brandModel::get($id);
 
		$this->assign('data',$data);
		return view();
	}
	
	public function del()
	{
		$user=new brandModel();
		$request = request();
		
		if($request->method()=='GET') {
			
			$id=$request->param('id');
			$result=0;
			$result=$user->deleteInfo($id);
			
			if($result==0){
				$this->error('操作失败，请重试');
			} else {
				$this->success('操作成功', '/admin/member/index/');
			}
		}
		
		$this->error('非法操作，请重试');
	}
}
