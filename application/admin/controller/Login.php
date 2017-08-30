<?php
namespace app\admin\controller;
use app\admin\model\Member as memberModel;
use think\Controller;
use think\Session;

//登录页面
class Login extends Controller 
{
	public function Login()
	{
		$member=new memberModel();
		$request = request();
		$data=array();
		
		if($request->method()=='POST') {
			$data['username']=$request->param('username');
			$data['password']=md5($request->param('password'));
		 
			$result=$member->validLogin($data);
			
			if($result) {
				$this->success('登录成功', '/admin/index/index/');
			} 
			$this->error('登录失败，请重试');
			
		}
		
		return view();
	}
	
	public function Logout()
	{
		Session::delete('username');
		Session::delete('password');
		
		Session::clear();
		$this->success('退出成功', '/admin/login/login/');
		
	}
	
}