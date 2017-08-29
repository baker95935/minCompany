<?php
namespace app\admin\controller;
use app\admin\model\User as userModel;
use think\Controller;
use think\Session;

//登录页面
class Login extends Controller 
{
	public function Login()
	{
		$user=new userModel();
		$request = request();
		$data=array();
		
		if($request->method()=='POST') {
			$data['email']=$request->param('email');
			$data['password']=md5($request->param('password'));
		 
			$user=new userModel();
			$result=$user->validLogin($data);
			
			if($result) {
				$this->success('登录成功', '/admin/user/index/');
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