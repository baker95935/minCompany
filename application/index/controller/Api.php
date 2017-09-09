<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\admin\model\Information as informationModel;
use app\admin\model\InformationExtend as infoEModel;

class Api extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function information()
    {
        $information=new informationModel();
		$infoE=new infoEModel();
		$request = request();
		
		$result=array();
		
		$id=$request->param('id');
		if(!empty($id)) {
	    	$data=$hotList=array();
			$data=informationModel::get($id);
			$data['extend']=$infoE->getList(array('iid'=>$data['id']));
		}
		return jsonp($data);
    }

  
}
