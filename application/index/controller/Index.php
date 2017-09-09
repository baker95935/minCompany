<?php
namespace app\index\controller;
use think\Controller;

use think\Request;
use think\Session;
use app\admin\model\Count as countModel;

class Index extends Controller
{
	public function _initialize()
	{
		$request = Request::instance();
		
		//登录校验
		if(!Session::has('countId'))
		{
			 var_dump('sdsd');
			$data=array(
			    'ipaddr'=>getIp(),
				'start_time'=>time(),
				'status'=>1,
				'create_time'=>time(),
			);
			$count=new countModel();
			$count->addInfo($data);
			$countId=$count->id;
			$countId >0 && Session::set('countId',$countId);
		} else {
			$data['end_time']=time();
     		$count=new countModel();
     		$count->addInfo($data,array('id'=>$countId));
		}
		
	}
	
    public function index()
    {
    	return view();
    }
    
     
}
