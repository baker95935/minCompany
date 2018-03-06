<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use PHPExcel_IOFactory;
use PHPExcel;

use app\admin\controller\Common;

use app\admin\model\Luckybagcount as luckybagcountModel;
use app\admin\model\Luckybag as luckybagModel;
use app\admin\model\Scene as sceneModel;

class Luckybagcount extends Common
{
     public function index()
	{
		$request=request();
		
		$list=array();
		$scene_id=$stime=$etime=0;
		
		$sceneList=Db::table('scene')->select();
		$this->assign('sceneList',$sceneList);
		
		$scene_id=$request->param('scene_id');
		$stime=$request->param('stime');
		$etime=$request->param('etime');
		
		$data=array();
		$scene_id>0 && $data['scene_id']=$scene_id;
		$stime>0 && $data['create_time']=['>=',strtotime($stime)];
		if($etime>0){
			$oneday=60*60*24;
			$endtime=strtotime($etime)+$oneday;
			$data['create_time']=['<=',$endtime];
		}
 		
 
		if(!empty($stime) && !empty($endtime)) {
			$data['create_time']=['between time',[$stime,$endtime]];
		}
		
		$this->assign('scene_id',$scene_id);

			$list=	Db::table('luckybagcount')
		    ->field('scene_id,count(id) as pv,count(distinct ipaddr) as uv,SUM(luckybag_click) AS luckybag_click_total,SUM(adviser_click) AS adviser_click_total,SUM(testdrive_click) AS testdrive_click_total,SUM(buy_click) AS buy_click_total,SUM(activity_click) AS activity_click_total,SUM(finance_click) AS finance_click_total,SUM(substitution_click) AS substitution_click_total,SUM(service_click) AS service_click_total')
		    ->group('scene_id ASC')
		    ->where($data)
		    ->order('scene_id ASC')
		    ->paginate(25);
			
		if(!$scene_id) {
			//获取总算
			$total_pv=$total_uv=$total_luckybag=$total_adviser=$total_testdrive=$total_buy=$total_activity=$total_testdrive=$total_finance=$total_substitution=$total_service=0;
			
			
			$tmp=Db::table('luckybagcount')->field('count(distinct ipaddr) as uv ')->where('scene_id>0')->find();
			$total_uv=$tmp['uv'];	
		  
			
			foreach($list as $k=>$v)
			{
				$total_pv+=$v['pv'];	
				//$total_uv+=$v['uv'];
				$total_luckybag+=$v['luckybag_click_total'];
				$total_adviser+=$v['adviser_click_total'];
				$total_testdrive+=$v['testdrive_click_total'];
				$total_buy+=$v['buy_click_total'];
				
				$total_activity+=$v['activity_click_total'];
				$total_finance+=$v['finance_click_total'];
				$total_substitution+=$v['substitution_click_total'];
				total_service+=$v['service_click_total'];
			}
		
			$this->assign('total_pv',$total_pv);
			$this->assign('total_uv',$total_uv);
			$this->assign('total_luckybag',$total_luckybag);
			$this->assign('total_adviser',$total_adviser);
			$this->assign('total_testdrive',$total_testdrive);
			$this->assign('total_buy',$total_buy);
			$this->assign('total_activity',$total_activity);
			$this->assign('total_finance',$total_finance);
			$this->assign('total_substitution',$total_substitution);
			$this->assign('total_service',$total_service);
		
		}
		
  		$this->assign('list',$list);
  		$this->assign('stime',$stime);
  		$this->assign('etime',$etime);
  		
		return view();
	}
	
	public function excelAll()
	{
		$objPHPExcel = new PHPExcel();
		$request=request();
		$stime=$etime='';
		
		$scene_id=$request->param('scene_id');
		$stime=$request->param('stime');
		$etime=$request->param('etime');
		
		$data=array();
		$scene_id>0 && $data['scene_id']=$scene_id;
		$stime>0 && $data['create_time']=['>=',strtotime($stime)];
		
		if($etime>0){
			$oneday=60*60*24;
			$endtime=strtotime($etime)+$oneday;
			$data['create_time']=['<=',$endtime];
		}
 		
 
		if(!empty($stime) && !empty($endtime)) {
			$data['create_time']=['between time',[$stime,$endtime]];
		}
		
		 
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('B1', '场景')
			            ->setCellValue('C1', '流量(PV)')
			            ->setCellValue('D1', '访客量(uv)')
			            ->setCellValue('E1', '福袋点击量')
					    ->setCellValue('F1', '顾问点击量')
					    ->setCellValue('G1', '试驾点击量')
					    ->setCellValue('H1', '订购点击量')
					    ->setCellValue('I1', '活动点击量')
					    ->setCellValue('J1', '金融点击量')
					    ->setCellValue('K1', '置换点击量')
					    ->setCellValue('L1', '服务点击量')
					    

			$list=	Db::table('luckybagcount')
		    ->field('scene_id,count(id) as pv,count(distinct ipaddr) as uv,SUM(luckybag_click) AS luckybag_click_total,SUM(adviser_click) AS adviser_click_total,SUM(testdrive_click) AS testdrive_click_total,SUM(buy_click) AS buy_click_total,SUM(activity_click) AS activity_click_total,SUM(finance_click) AS finance_click_total,SUM(substitution_click) AS substitution_click_total,SUM(service_click) AS service_click_total')
		    ->group('scene_id ASC')
		    ->where($data)
		    ->where('scene_id>0')
	 		->select();
	 		
			$i=2;
			if($scene_id=='stime') {
				//获取总数
				$total_pv=$total_uv=$total_luckybag=$total_adviser=$total_testdrive=$total_buy=$total_activity=$total_finance=$total_substitution=$total_service=0;
				
				$tmp=Db::table('luckybagcount')->field('count(distinct ipaddr) as uv ')->where('scene_id>0')->find();
				$total_uv=$tmp['uv'];	
					
				foreach($list as $k=>$v)
				{
					$total_pv+=$v['pv'];
					//$total_uv+=$v['uv'];
					$total_luckybag+=$v['luckybag_click_total'];

					$total_luckybag==0 && $total_luckybag='--';

					$total_adviser+=$v['adviser_click_total'];
					$total_testdrive+=$v['testdrive_click_total'];
					$total_buy+=$v['buy_click_total'];
						
					$total_activity+=$v['activity_click_total'];
					$total_finance+=$v['finance_click_total'];
					$total_substitution+=$v['substitution_click_total'];
					$total_service+=$v['service_click_total'];
				}
				
				//总数列
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B2', '总数')
				->setCellValue('C2', $total_pv)
				->setCellValue('D2', $total_uv)
				->setCellValue('E2', $total_luckybag)
				->setCellValue('F2', $total_adviser)
				->setCellValue('G2', $total_testdrive)
				->setCellValue('H2', $total_buy)
				->setCellValue('I2', $total_activity)
				->setCellValue('J2', $total_finance)
				->setCellValue('K2', $total_substitution);
				->setCellValue('L2', $total_service);
				
				$i++;
			}
			
	 		
	 		foreach($list as $k=>$v)
	 		{
	 			
	 			$v['luckybag_click_total']==0 && $v['luckybag_click_total']='--';
		        $v['adviser_click_total']==0 && $v['adviser_click_total']='--';
		        $v['testdrive_click_total']==0 && $v['testdrive_click_total']='--';
		        $v['buy_click_total']==0 && $v['buy_click_total']='--';
		        $v['activity_click_total']==0 && $v['activity_click_total']='--';
		        $v['finance_click_total']==0 && $v['finance_click_total']='--';
		        $v['substitution_click_total']==0 && $v['substitution_click_total']='--';
		        $v['service_click_total']==0 && $v['service_click_total']='--';
		             
 				$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('B'.$i,getSceneNameById($v['scene_id']))
		            ->setCellValue('C'.$i,$v['pv'])
		            ->setCellValue('D'.$i,$v['uv'])
		            ->setCellValue('E'.$i,$v['luckybag_click_total'])
				    ->setCellValue('F'.$i,$v['adviser_click_total'])
				    ->setCellValue('G'.$i,$v['testdrive_click_total'])
				    ->setCellValue('H'.$i,$v['buy_click_total'])
				    ->setCellValue('I'.$i,$v['activity_click_total'])
				    ->setCellValue('J'.$i,$v['finance_click_total'])
				    ->setCellValue('K'.$i,$v['substitution_click_total'])
				    ->setCellValue('L'.$i,$v['service_click_total']);	
				    
				$i++;
	 		}
 

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Simple');


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="all.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		
	}
	
	public function luckybag($day)
	{
		$lucybaglist=array();
	 
		$lucybaglist=Db::table('luckybagcount')
	    ->field('create_time AS day,ipaddr,luckybag_click,scene_id,luckybag_id')
	    ->where(array('DATE(FROM_UNIXTIME(create_time))'=>$day,'luckybag_click'=>1))
	    ->paginate(10);
  	 
  		$this->assign('list',$lucybaglist);
  		$this->assign('day',$day);
  		return view();
	}
	
	public function excelluckybag()
	{
		$day=request()->param('day');
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', '日期')
			            ->setCellValue('B1', 'IP')
			            ->setCellValue('C1', '福袋名称')
			            ->setCellValue('D1', '福袋点击量')
			            ->setCellValue('E1', '福袋位置');
 

 	  		$list=	Db::table('luckybagcount')
    				->field('create_time AS day,ipaddr,luckybag_id,luckybag_click,scene_id')
	    			->where(array('DATE(FROM_UNIXTIME(create_time))'=>$day,'luckybag_click'=>1))
	 				->select();
	 		
	 		$i=2;
	 		foreach($list as $k=>$v)
	 		{
 				$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$i,date('Y-m-d H:i:s',$v['day']))
		            ->setCellValue('B'.$i,$v['ipaddr'])
		            ->setCellValue('C'.$i,getLuckybagNameById($v['luckybag_id']))
		            ->setCellValue('D'.$i,$v['luckybag_click'])
		            ->setCellValue('E'.$i,getSceneNameById($v['scene_id']));
	 
				$i++;
	 		}
 

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Simple');


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="excel.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		
	}
	
	public function sceneDetail()
	{
		$request=request();
				
		$sceneList=Db::table('scene')->select();
		$this->assign('sceneList',$sceneList);
		
		
		$data=array();
		$scene_id=$data['scene_id']=$request->param('scene_id');
		
		$stime=$request->param('stime');
		$etime=$request->param('etime');
		
		$stime>0 && $data['create_time']=['>=',strtotime($stime)];
		if($etime>0){
			$oneday=60*60*24;
			$endtime=strtotime($etime)+$oneday;
			$data['create_time']=['<=',$endtime];
		}
 		
 
		if(!empty($stime) && !empty($endtime)) {
			$data['create_time']=['between time',[$stime,$endtime]];
		}
		
		$list=	Db::table('luckybagcount')
		    ->field('scene_id,DATE(FROM_UNIXTIME(create_time)) AS day,count(id) as pv,count(distinct ipaddr) as uv,SUM(luckybag_click) AS luckybag_click_total,SUM(adviser_click) AS adviser_click_total,SUM(testdrive_click) AS testdrive_click_total,SUM(buy_click) AS buy_click_total,SUM(activity_click) AS activity_click_total,SUM(finance_click) AS finance_click_total,SUM(substitution_click) AS substitution_click_total')
		    ->group('DATE(FROM_UNIXTIME(create_time)) DESC')
		    ->where($data)
		    ->paginate(10);
	
		//获取总算
		$total_pv=$total_uv=$total_luckybag=$total_adviser=$total_testdrive=$total_buy=$total_activity=$total_testdrive=$total_finance=$total_substitution=0;
			foreach($list as $k=>$v)
			{
				$total_pv+=$v['pv'];	
				$total_uv+=$v['uv'];
				$total_luckybag+=$v['luckybag_click_total'];
				$total_adviser+=$v['adviser_click_total'];
				$total_testdrive+=$v['testdrive_click_total'];
				$total_buy+=$v['buy_click_total'];
				
				$total_activity+=$v['activity_click_total'];
				$total_finance+=$v['finance_click_total'];
				$total_substitution+=$v['substitution_click_total'];
			}
		
			$this->assign('total_pv',$total_pv);
			$this->assign('total_uv',$total_uv);
			$this->assign('total_luckybag',$total_luckybag);
			$this->assign('total_adviser',$total_adviser);
			$this->assign('total_testdrive',$total_testdrive);
			$this->assign('total_buy',$total_buy);
			$this->assign('total_activity',$total_activity);
			$this->assign('total_finance',$total_finance);
			$this->assign('total_substitution',$total_substitution);
		
		$this->assign('list',$list);
		$this->assign('stime',$stime);
		$this->assign('etime',$etime);
		$this->assign('scene_id',$scene_id);
 
  	 
  		return view();
	}
	
	
	public function excelSceneDetail()
	{
		$objPHPExcel = new PHPExcel();
		$request=request();
		$stime=$etime='';
		
		$scene_id=$request->param('scene_id');
		$stime=$request->param('stime');
		$etime=$request->param('etime');
		
		$data=array();
		$scene_id>0 && $data['scene_id']=$scene_id;
		$stime>0 && $data['create_time']=['>=',strtotime($stime)];
		
		if($etime>0){
			$oneday=60*60*24;
			$endtime=strtotime($etime)+$oneday;
			$data['create_time']=['<=',$endtime];
		}
 		
 
		if(!empty($stime) && !empty($endtime)) {
			$data['create_time']=['between time',[$stime,$endtime]];
		}
		
		 
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', '日期')
			            ->setCellValue('B1', '场景')
			            ->setCellValue('C1', '流量(PV)')
			            ->setCellValue('D1', '访客量(uv)')
			            ->setCellValue('E1', '福袋点击量')
					    ->setCellValue('F1', '顾问点击量')
					    ->setCellValue('G1', '试驾点击量')
					    ->setCellValue('H1', '订购点击量')
					    ->setCellValue('I1', '活动点击量')
					    ->setCellValue('J1', '金融点击量')
					    ->setCellValue('K1', '置换点击量');

			$list=	Db::table('luckybagcount')
		    ->field('scene_id,DATE(FROM_UNIXTIME(create_time)) AS day,count(id) as pv,count(distinct ipaddr) as uv,SUM(luckybag_click) AS luckybag_click_total,SUM(adviser_click) AS adviser_click_total,SUM(testdrive_click) AS testdrive_click_total,SUM(buy_click) AS buy_click_total,SUM(activity_click) AS activity_click_total,SUM(finance_click) AS finance_click_total,SUM(substitution_click) AS substitution_click_total')
		    ->group('DATE(FROM_UNIXTIME(create_time)) DESC')
		    ->where($data)
	 		->select();
	 		
			
		
			//获取总数
			$total_pv=$total_uv=$total_luckybag=$total_adviser=$total_testdrive=$total_buy=$total_activity=$total_finance=$total_substitution=0;
			foreach($list as $k=>$v)
			{
				$total_pv+=$v['pv'];
				$total_uv+=$v['uv'];
				$total_luckybag+=$v['luckybag_click_total'];
				$total_adviser+=$v['adviser_click_total'];
				$total_testdrive+=$v['testdrive_click_total'];
				$total_buy+=$v['buy_click_total'];
					
				$total_activity+=$v['activity_click_total'];
				$total_finance+=$v['finance_click_total'];
				$total_substitution+=$v['substitution_click_total'];
			}
			
			//总数列
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A2', '日期')
			->setCellValue('B2', '总数')
			->setCellValue('C2', $total_pv)
			->setCellValue('D2', $total_uv)
			->setCellValue('E2', $total_luckybag)
			->setCellValue('F2', $total_adviser)
			->setCellValue('G2', $total_testdrive)
			->setCellValue('H2', $total_buy)
			->setCellValue('I2', $total_activity)
			->setCellValue('J2', $total_finance)
			->setCellValue('K2', $total_substitution);
				
			$i=3;
			
			
	 		
	 		foreach($list as $k=>$v)
	 		{
 				$objPHPExcel->setActiveSheetIndex(0)
 					->setCellValue('A'.$i,$v['day'])
		            ->setCellValue('B'.$i,getSceneNameById($v['scene_id']))
		            ->setCellValue('C'.$i,$v['pv'])
		            ->setCellValue('D'.$i,$v['uv'])
		            ->setCellValue('E'.$i,$v['luckybag_click_total'])
				    ->setCellValue('F'.$i,$v['adviser_click_total'])
				    ->setCellValue('G'.$i,$v['testdrive_click_total'])
				    ->setCellValue('H'.$i,$v['buy_click_total'])
				    ->setCellValue('I'.$i,$v['activity_click_total'])
				    ->setCellValue('J'.$i,$v['finance_click_total'])
				    ->setCellValue('K'.$i,$v['substitution_click_total']);	
				$i++;
	 		}
 

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Simple');


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="all.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
	}
	
}
