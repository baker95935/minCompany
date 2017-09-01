<?php
namespace app\admin\validate;

use think\Validate;

class Brand extends Validate
{
	protected $rule = [
		'name'  =>  'require|max:30|unique:brand',
		'location' => 'require',
		'title' =>  "require",
		'content' => 'require',
		'status' => 'require',
		'video1' => 'require',
		'video2' => 'require',
		'video3' => 'require',
		'__token__' => 'token',
	];

}