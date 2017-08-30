<?php

use think\migration\Seeder;
use app\admin\model\Member;


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
    		Member::create([
	    		'username'   => 'baker'.$i,
	    		'password'   => md5("111111"),
	    		'create_time'=>time(),
    		]);
    	}
    }
}