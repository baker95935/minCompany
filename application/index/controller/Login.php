<?php

namespace app\index\controller;

use think\response\Redirect;

use think\Controller;
use think\Request;
use think\Session;

use app\index\model\User as userModel;
use app\admin\model\Count as countModel;

class Login extends Controller
{
     public function login()
     {
     	$user=new userModel();
		$request = request();
		$data=array();
		
		if($request->method()=='POST') {
			$data['username']=$request->param('username');
			$data['password']=md5($request->param('password'));
		 
			$result=$user->validLogin($data);
			
			if($result) {
				$this->success('登录成功', '/index/index/index/');
			} 
			$this->error('登录失败，请重试');
			
		}
     	return view();
     }
     
     public function changepw()
     {
     	echo 'changpw';
     	return view();
     }
     
     public function logout()
     {
     	$countId=Session::get('countId');
     	
     	if($countId) {
     		$data['end_time']=time();
     		$count=new countModel();
     		$count->addInfo($data,array('id'=>$countId));
     	}
     	
     	Session::delete('username');
		Session::delete('password');
		
		Session::clear();
		$this->redirect('http://www.jd.com');
     	//退出登录，记载时间然后跳转
     }
     
     public function register()
     {
     	$user=new userModel();
		$request = request();
		
	 
		if($request->method()=='POST') {
			//数据获取
			$data=array(
				'username'=>$request->param('username'),
				'password'=>md5($request->param('password')),
				'confirmPassword'=>md5($request->param('confirmPassword')),
			
			);
			
			//数据校验
			$validate = validate('user');
			$captcha=$request->param('captcha');
			
			if(!captcha_check($captcha)){
				$this->error($validate->getError());
			
			} else {
				 
				unset($data['confirmPassword']);
				$data['create_time']=time();
				$result=0;
				$result=$user->addInfo($data);
 
				
				if($result) {
					$this->success('操作成功', '/index/index/index/');
				} else {
					$this->success('操作失败或未生效请重试', '/index/index/index/');
				}
			}
		}
     	return view();
     }
}
