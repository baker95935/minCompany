<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\admin\model\Information as informationModel;
use app\admin\model\InformationExtend as infoEModel;
use app\admin\model\Brand as brandModel;
use think\Config;

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
			$tmp=$infoE->getList(array('iid'=>$data['id']));
			!empty($tmp) && $data['extends']=$tmp;
		}
		return jsonp($data);
    }

    
    public function brand()
    {
        $brand=new brandModel();
        $request = request();
        
        $result=array();
        
        $id=$request->param('id');
        if(!empty($id)) {
            $data=array();
            $data=brandModel::get($id);
            $data['video1pic']= Config::get('view_replace_str.__ROOT__').$data['video1pic'];
            $data['video2pic']= Config::get('view_replace_str.__ROOT__').$data['video2pic'];
            $data['video3pic']= Config::get('view_replace_str.__ROOT__').$data['video3pic'];
        }
        return jsonp($data);
    }
}
