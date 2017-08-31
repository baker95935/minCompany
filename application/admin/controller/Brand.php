<?php

namespace app\admin\controller;

use app\admin\controller\Common;
use think\Request;
use app\admin\model\Brand as brandModel;

//品牌信息
class Brand extends Common
{
  public function index()
	{
		$list=array();
		$members=new brandModel();
		
		$request = request();
		$search=$request->param('search');
	 
		!empty($search) && $where['username']=['like',"%".$search."%"];
		$where['id']=['>',0];
		
		$list=$members->getListInfo($where,array('search'=>$search));
		$this->assign('list',$list);
		$this->assign('search',$search);
		
		return view();
	}

	public function add()
	{
		$member=new brandModel();
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
				'id'=>$request->param('id'),
			);
			
			//多文件上传处理
			$files = request()->file('uploadFiles');
			foreach($files as $file){
				var_dump($file);
				// 移动到框架应用根目录/public/uploads/ 目录下
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
					var_dump($info);
					// 成功上传后 获取上传信息
					// 输出 jpg
					echo $info->getExtension();
					// 输出 42a79759f284b767dfcb2a0197904287.jpg
					echo $info->getFilename();
				}else{
					// 上传失败获取错误信息
					echo $file->getError();
				}
			}
			exit;
			//数据校验
			$validate = validate('brand');
			
			if(!$validate->check($data)){
				$this->error($validate->getError());
			
			} else {
				 
				unset($data['confirmPassword']);
				
				$result=0;
				if(empty($id)){//添加
					$data['create_time']=time();
					$result=$member->addInfo($data);
				} else {
					$result=$member->addInfo($data,array('id'=>$id));//更新
				}
				
				if($result) {
					$this->success('操作成功', '/admin/member/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/admin/member/index/');
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
