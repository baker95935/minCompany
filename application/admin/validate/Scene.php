<?php
namespace app\admin\validate;

use think\Validate;

class Scene extends Validate
{
	protected $rule = [
		'name'  =>  'require',
		'__token__' => 'token',
	];

}