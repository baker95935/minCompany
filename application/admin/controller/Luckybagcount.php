<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use PHPExcel_IOFactory;
use PHPExcel;

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
	
	public function excelAll()
	{
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
			            ->setCellValue('B1', '流量(PV)')
			            ->setCellValue('C1', '访客量(uv)')
			            ->setCellValue('D1', '福袋点击量')
					    ->setCellValue('E1', '顾问点击量')
					    ->setCellValue('F1', '试驾点击量')
					    ->setCellValue('G1', '订购点击量')
					    ->setCellValue('H1', '活动点击量')
					    ->setCellValue('I1', '金融点击量')
					    ->setCellValue('J1', '置换点击量');

 	  		$list=	Db::table('luckybagcount')
		    ->field('DATE(FROM_UNIXTIME(create_time)) AS day,count(id) as pv,count(distinct ipaddr) as uv,SUM(luckybag_click) AS luckybag_click_total,SUM(adviser_click) AS adviser_click_total,SUM(testdrive_click) AS testdrive_click_total,SUM(buy_click) AS buy_click_total,SUM(activity_click) AS activity_click_total,SUM(finance_click) AS finance_click_total,SUM(substitution_click) AS substitution_click_total')
		    ->group('DATE(FROM_UNIXTIME(create_time)) DESC')
	 		->select();
	 		
	 		$i=2;
	 		foreach($list as $k=>$v)
	 		{
 				$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$i,$v['day'])
		            ->setCellValue('B'.$i,$v['pv'])
		            ->setCellValue('C'.$i,$v['uv'])
		            ->setCellValue('D'.$i,$v['luckybag_click_total'])
				    ->setCellValue('E'.$i,$v['adviser_click_total'])
				    ->setCellValue('F'.$i,$v['testdrive_click_total'])
				    ->setCellValue('G'.$i,$v['buy_click_total'])
				    ->setCellValue('H'.$i,$v['activity_click_total'])
				    ->setCellValue('I'.$i,$v['finance_click_total'])
				    ->setCellValue('J'.$i,$v['substitution_click_total']);	
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
	    ->field('create_time AS day,ipaddr,luckybag_click,scene_id')
	    ->where(array('DATE(FROM_UNIXTIME(create_time))'=>$day,'luckybag_click'=>1))
	    ->paginate(10);
  	 
  		$this->assign('list',$lucybaglist);
  		$this->assign('day',$day)
  		return view();
	}
	
	public function excelluckybag($day)
	{
		
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
			            ->setCellValue('C1', '福袋点击量')
			            ->setCellValue('D1', '福袋位置')
 

 	  		$list=	Db::table('luckybagcount')
    				->field('create_time AS day,ipaddr,luckybag_click,scene_id')
	    			->where(array('DATE(FROM_UNIXTIME(create_time))'=>$day,'luckybag_click'=>1))
	 				->select();
	 		
	 		$i=2;
	 		foreach($list as $k=>$v)
	 		{
 				$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$i,$v['day'])
		            ->setCellValue('B'.$i,$v['ip'])
		            ->setCellValue('C'.$i,$v['luckybag_click'])
		            ->setCellValue('D'.$i,getSceneNameById($v['scene_id']))
	 
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
