<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

use app\admin\controller\Common;

use app\admin\model\Luckybagcount as luckybagcountModel;

class Luckybagcount extends Common
{
     public function index()
	{
		$list=array();

	  	$list=	Db::table('luckybagcount')
	    ->field('DATE(FROM_UNIXTIME(create_time)) AS day,count(id) as pv,count(distinct ipaddr) as uv,SUM(luckybag_click) AS luckybag_click_total,SUM(adviser_click) AS adviser_click_total,SUM(testdrive_click) AS testdrive_click_total,SUM(buy_click) AS buy_click_total,SUM(activity_click) AS activity_click_total,SUM(finance_click) AS finance_click_total,SUM(substitution_click) AS substitution_click_total')
	    ->group('DATE(FROM_UNIXTIME(create_time)) DESC')
	    ->paginate(10);
  		
  		$this->assign('list',$list);
		return view();
	}
	
	
	public function luckybag($day)
	{
		$lucybaglist=array();
	 
		$lucybaglist=Db::table('luckybagcount')
	    ->field('create_time AS day,ipaddr,luckybag_click,scene_id')
	    ->where(array('DATE(FROM_UNIXTIME(create_time))'=>$day,'luckybag_click'=>1))
	    ->paginate(10);
  	 
  		$this->assign('list',$lucybaglist);
  		return view();
	}
}
