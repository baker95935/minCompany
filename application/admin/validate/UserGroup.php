<?php
namespace app\admin\validate;

use think\Validate;

class UserGroup extends Validate
{
	protected $rule = [
		'group_name'  =>  'require|max:30|unique:user_group',
		'__token__' => 'token',
	];

}