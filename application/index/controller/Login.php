<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

use app\admin\model\User as userModel;

class Login extends Controller
{
     public function login()
     {
     	
     	return view();
     }
     
     public function changepw()
     {
     	return view();
     }
     
     public function logout()
     {
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
