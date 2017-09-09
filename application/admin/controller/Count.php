<?php

namespace app\admin\controller;

use app\admin\controller\Common;
use think\Request;
use app\admin\model\Count as countModel;

//统计信息
class Count extends Common
{
 	public function index()
	{
		$list=array();
		$count=new countModel();
		
		$request = request();
		$search['id']=$request->param('id');
		$search['username']=$request->param('username');
		$search['ipaddr']=$request->param('ipaddr');
		$search['start_time']=$request->param('start_time');
		$search['end_time']=$request->param('end_time');
	 
	 
		!empty($search['id']) && $where['id']=['=',$search['id']];
		!empty($search['username']) && $where['username']=['like',"%".$search['username']."%"];
		!empty($search['ipaddr']) && $where['ipaddr']=['like',"'".$search['ipaddr']."'"];
		!empty($search['start_time']) && $where['start_time']=['=',strtotime($search['start_time'])];
		!empty($search['end_time']) && $where['end_time']=['=',strtotime($search['end_time'])];
		
		empty($where['id']) && $where['id']=['>',0];
		
		//一些统计信息
		$countlist['id']=['>',0];
		$totalCount=$count->listCount($countlist);
		$countlist=array();
		$countlist['is_click']=['=',1];
		$clickCount=$count->listCount($countlist);
		$per=($clickCount/$totalCount)*100;
 
		$this->assign('totalCount',$totalCount);
		$this->assign('clickCount',$clickCount);
		$this->assign('per',$per);
		
		$list=$count->getListInfo($where,array('search'=>$search));
		$this->assign('list',$list);
		$this->assign('search',$search);
		
		return view();
	}

	public function add()
	{
		$count=new countModel();
		$request = request();
		
		$id=$request->param('id');
	 
		if($request->method()=='POST') {
			//数据获取
			$data=array(
				'username'=>$request->param('username'),
			    'ipaddr'=>$request->param('ipaddr'),
				'start_time'=>strtotime($request->param('start_time')),
				'end_time'=>strtotime($request->param('end_time')),
				'status'=>$request->param('status'),
				'id'=>$request->param('id'),
			);
			
		 
				
			$result=0;
			if(empty($id)){//添加
				$data['create_time']=time();
				$result=$count->addInfo($data);
			} else {
				$result=$count->addInfo($data,array('id'=>$id));//更新
			}
			
			if($result) {
				$this->success('操作成功', '/admin/count/index/');
			} else {
				$this->success('操作失败或未生效请重试', '/admin/count/index/');
			}
	
		}
			
		$data=array();
		!empty($id) && $data=$count::get($id);
 
		$this->assign('data',$data);
		return view();
	}
	
	public function del()
	{
		$count=new countModel();
		$request = request();
		
		if($request->method()=='GET') {
			
			$id=$request->param('id');
			$result=0;
			$result=$count->deleteInfo($id);
			
			if($result==0){
				$this->error('操作失败，请重试');
			} else {
				$this->success('操作成功', '/admin/count/index/');
			}
		}
		
		$this->error('非法操作，请重试');
	}
}
