<?php
namespace app\admin\validate;

use think\Validate;

class Luckybag extends Validate
{
	protected $rule = [
		'name'  =>  'require|max:30|unique:scene',
		'link' => 'require',
		'pic' => 'require', 
		'__token__' => 'token',
	];

}