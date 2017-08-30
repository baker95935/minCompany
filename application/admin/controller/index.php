<?php

namespace app\Admin\controller;

 
use app\admin\controller\Common;

class Index extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return view();
    }
}
