<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
	protected $rule = [
		'username'  =>  'require|max:30|unique:user',
		'email' =>  'require|email|unique:user',
		'group' => 'require',
		'password' => 'require',
		'confirmPassword' =>  "require|confirm:password",
		'__token__' => 'token',
	];

}