<?php

use think\migration\Seeder;
use app\admin\model\User;


class CreateUserTable extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
    	for ($i=0; $i < 2; $i++) {
    		User::create([
    		'username'   => 'baker'.$i,
    		'email'      => 'baker'.$i.'@qq.com',
    		'password'   => md5("111111"),
    		'group' => 1,
    		'create_time'=>time(),
    		]);
    	}
    }
}