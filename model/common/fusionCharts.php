<?php
/*
 * Created on 2010-9-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * FusionCharts图表类
 */

class model_common_fusionCharts extends model_base{
	function __construct($isInclude=true) {
		parent::__construct ();
		if($isInclude==true){
			//引入类文件
			include(WEB_TOR."FusionCharts/Includes/FusionCharts.php");
			echo "<script type='text/javascript' src='FusionCharts/js/FusionCharts.js'></script>";
			echo "<script type='text/javascript' src='FusionCharts/js/FusionChartsExportComponent.js'></script>";
		}
	}

	private  $arr_chartConf = array(
		 'exportEnabled'=>1,                //是否允许导出images/pdf s
		 'showPrintMenuItem'=>0,            //是否在右键菜单中显示打印选项
         'exportHandler'=>"FusionCharts/exportHandlers/FCExporter.php",       //服务器运行脚本所在路径
  		 'exportAtClient'=>0,               //0: 服务器端运行， 1： 客户端运行
	     'exportAction'=>"download",              //如果是服务器运行，支持浏览器下载or服务器保存
	     'exportFileName'=>"图表",                 //导出的文件名
	     'caption'=>"我的图表",                 //显示名称
	     'showValues' =>1,                       //是否在图表上显示值
	     'numberPrefix' =>"",                   //前缀 如  $180
	     'numberSuffix' =>"",  					//后缀 如  180/月
		 'baseFontSize'=>12,                         //字体大小
		 'numVisiblePlot'=>20,                       //滚动图表中设置分栏数目
		 'yAxisMaxValue'=>80,                        //y轴的最大值，比自动计算的默认值大时有效
		 'exportCallback'=>"FC_Exported",
		 'formatNumberScale' =>0,
		 'Decimals'=>2, //小数点后两位 四舍五入
		 'forceDecimals'=>0 //小数点位数不够是否强制补0
//		 'labelDisplay'=>'WRAP', //y轴标记的显示间隔
//		 'rotateLabels'=>1,
//		 'slantLabels'=>1

	);

	private $arr_FCColors = array(
		'0' => "1941A5" ,//Dark Blue
		'1' => "AFD8F8",
		'2' => "F6BD0F",
		'3' => "8BBA00",
		'4' => "A66EDD",
		'5' => "F984A1" ,
		'6' => "CCCC00" ,//Chrome Yellow+Green
		'7' => "999999" ,//Grey
		'8' => "0099CC" ,//Blue Shade
		'9' => "FF0000" ,//Bright Red
		'10' => "006F00" ,//Dark Green
		'11' => "0099FF", //Blue (Light)
		'12' => "FF66CC" ,//Dark Pink
		'13' => "669966" ,//Dirty green
		'14' => "7C7CB4" ,//Violet shade of blue
		'15' => "FF9933" ,//Orange
		'16' => "9900FF" ,//Violet
		'17' => "99FFCC" ,//Blue+Green Light
		'18' => "CCCCFF" ,//Light violet
		'19' => "669900" //Shade of green
	);

	private $FC_ColorCounter = 0;

	function getColor(){
		$this->FC_ColorCounter++;
		//Return color
		return $this->arr_FCColors[$this->FC_ColorCounter % count($this->arr_FCColors)];

	}

	/**
	 * 获取报表xml
	 */
	function getCharXml($rows,$chartConf){
		$strXML = "<chart useRoundEdges='1' legendBorderAlpha='0'";
        foreach($this->arr_chartConf as $key => $value){
        	if(array_key_exists("$key", $chartConf)&&$chartConf[$key]!="" && $chartConf[$key]!=null){
        	    $strXML.= $key."='".$chartConf[$key]."' ";
        	}else{
        	    $strXML.= $key."='".$value."' ";
        	}
        }

 		$strXML.= ">";
	   	$strDataCurr = "";
		//Iterate through the data
		foreach ($rows as $arSubData) {
			if(empty($arSubData[2])){
				$arSubData[2]=0;
			}
	        $strDataCurr .= "<set label='".$arSubData[1]."' value='" . $arSubData[2] . "' color='". $this->getColor() ."'/>";
		}

		$strXML .=  $strDataCurr . "</chart>";
		return $strXML;
	}

	/**
	 * 简单图
	 * 传入数组返回图表
	 * 第一个参数是值数组
	 * 第二个参数是创建的图表类型 如Column2D.swf
	 * 第三个参数是构建图表xml的各项参数，类型为数组，参考$this->arr_chartConf
	 * 第四五个参数是图表的长宽，具有默认值
	 */
	function showCharts($rows,$swfName="Column2D.swf",$chartConf,$width='450',$hight='350'){
		if(empty($swfName)){
			$swfName="Column2D.swf";
		}
	    $strXML=$this->getCharXml($rows,$chartConf);

		return renderChart("$swfName", "", $strXML, "productSales".rand(), $width, $hight);
	}

/**
 * 简单图形
 */
	function getSimpleCharXml($rows,$chartConf){
		$strXML = "<chart useRoundEdges='1' legendBorderAlpha='0' ";
        foreach($this->arr_chartConf as $key => $value){
        	if(array_key_exists("$key", $chartConf)&&$chartConf[$key]!="" && $chartConf[$key]!=null){
        	    $strXML.= $key."='".$chartConf[$key]."' ";
        	}else{
        	    $strXML.= $key."='".$value."' ";
        	}
        }

 		$strXML.= ">";
	   	$strDataCurr = "";
		//Iterate through the data
		foreach ($rows as $key=>$value) {
	        $strDataCurr .= "<set label='".$key."' value='" . $value . "' color='". $this->getColor() ."'/>";
		}

		$strXML .=  $strDataCurr . "</chart>";
		return $strXML;
	}


	/**
	 * 混合柱状图
	 * $rows 一个多维数组，其中data为二维数据
	 */
	function getMixCharXml($rows,$chartConf,$isMergeData=true){
		if(!is_array(reset($rows))){
			return $this->getSimpleCharXml($rows,$chartConf);
		}
		//if($isMergeData==true){
			//处理数据的正确性
			$mergeArr=array();
			foreach($rows as $key=>$valArr){
				$mergeArr=array_merge($mergeArr,$valArr);
			}
			//按照第一个数组的key顺序进行排序
			$newRows=array();
			foreach($rows as $key=>$valArr){
				$array=array();
				foreach($mergeArr as $k1=>$v1){
					if(empty($valArr[$k1])){
						$rows[$key][$k1]=0;
					}
					$array[$k1]=$rows[$key][$k1];
				}
				$newRows[$key]=$array;
				//ksort($rows[$key]);
			}
//		}else{
//			$newRows=$rows;
//		}

	    $strXML = "<chart useRoundEdges='1' legendBorderAlpha='0' palette='2' ";
	    foreach($this->arr_chartConf as $key => $value){
        	if(array_key_exists("$key", $chartConf)&&$chartConf[$key]!="" && $chartConf[$key]!=null){
        	    $strXML.= $key."='".$chartConf[$key]."' ";
        	}else{
        	    $strXML.= $key."='".$value."' ";
        	}
        }
        $strXML.= ">";
		$strCategories = "<categories>";
		foreach ($newRows as $k1=>$v1) {
	        $strChart .= "<dataset seriesName='" . $k1. "' >";
	        foreach ($v1 as $k2=>$v2) {
		         $strChart .= "<set  value='" . $v2. "' />";
	        }
	        $strChart .= "</dataset>";
		}
		foreach ($v1 as $k=>$v) {
		 	$strCategories .= "<category label='" . $k . "' />";
		}
		$strCategories .= "</categories>";
		$strXML = $strXML.$strCategories.$strChart. "</chart>";
        return $strXML;
	}




	/**
	 * 混合柱状图
	 */
	function showMixChart($rows, $swfName, $chartConf, $width = '450', $hight = '350') {
		$strXML = $this->getMixCharXml ( $rows, $chartConf );
		return renderChart ( "$swfName", "", $strXML, "productSales", $width, $hight );
	}

	/**
	 * 混合柱状图（研发用）
	 */
	function showBarChart($rows,$swfName,$chartConf,$width='450',$hight='350'){
	    $strXML = "<chart ";
        foreach($this->arr_chartConf as $key => $value){
        	if($chartConf[$key]=="" or $chartConf[$key]==null){
        	    $strXML.= $key."='".$value."' ";
        	}else{
        	    $strXML.= $key."='".$chartConf[$key]."' ";
        	}
        }

        $col=0;
 		$strXML.= ">";
		//多对象图必须
		$strCategories = "<categories>";

		//Initiate <dataset> elements
		$strDataCurr = "<dataset seriesName='估计工作量' color='". $this->getColor() ."'>";
		$strDataPrev = "<dataset seriesName='已投入工作量' color='". $this->getColor() ."'>";
		//Iterate through the data
		foreach ($rows as $arSubData) {
			$col++;
	        //Append <category name='...' /> to strCategories
	        $strCategories .= "<category name='" . $arSubData[1] . "' />";
	        //Add <set value='...' /> to both the datasets
	        $strDataCurr .= "<set value='" . $arSubData[2] . "' />";
	        $strDataPrev .= "<set value='" . $arSubData[3] . "' />";
		}

		//闭合
		$strCategories .= "</categories>";

		//Close <dataset> elements
		$strDataCurr .= "</dataset>";
		$strDataPrev .= "</dataset>";

		//Assemble the entire XML now
		$strXML .= $strCategories . $strDataCurr . $strDataPrev . "</chart>";

        $width=($col * 30 > $width) ?  $col * 30 : $width;

		//Create the chart - MS Column 3D Chart with data contained in strXML
		return renderChart("$swfName", "", $strXML, "productSales", $width	, $hight);
	}



	/**
	 * 纯动态混合组图
	 */
	function mixCharts($rows,$name = '我的图表',$numberPrefix='',$numberSuffix = '',$width='600',$hight='300',$nameArr = null){
		$strXML = "<graph  exportEnabled='".$this->exportEnabled."' showPrintMenuItem='".$this->showPrintMenuItem."' exportAtClient='".$this->exportAtClient."' exportHandler='".$this->exportHandler."' exportAction='".$this->exportAction."' exportFileName=='$name'  useRoundEdges='1' caption='".$name."4444' showValues ='1' numberPrefix = '".$numberPrefix."' numberSuffix='".$numberSuffix."' baseFontSize='".$this->baseFontSize." yAxisMaxValue='100'>";

		$tempArr = array();
		$tempCount = 0;

		foreach($nameArr as $val){
			$tempArr[$tempCount] .= "<dataset seriesName='".$val."' color='". $this->getColor() ."'>";
			$tempCount++;
		}

		//构建XML开始
		$strCategories = "<categories>";

		//循环输出数据
		foreach ($rows as $arSubData) {
	        //Append <category name='...' /> to strCategories
	        $strCategories .= "<category name='" . $arSubData[1] . "' />";
	        //Add <set value='...' /> to both the datasets
	        $tempCountAnother = $tempCount;
	        $i = 0;
	        foreach($tempArr as $rs){
	        	$tempArr[$i] .= "<set value='" . $arSubData[$tempCountAnother] . "' />";
	        	$i ++;
	        	$tempCountAnother ++;
//	        	echo $tempCountAnother;

	        }
		}

		//闭合
		$strCategories .= "</categories>";

		$i = 0;
		foreach($tempArr as $rs){
        	$tempArr[$i] .= "</dataset>";
        	$i ++;
        }

		$strXML .= $strCategories;
		foreach($tempArr as $rs){
        	$strXML .= $rs;
        }
		//Assemble the entire XML now
		$strXML .= "</graph>";

		//实例化图表
		return renderChart("MSColumn2D.swf", "", $strXML, "productSales", $width, $hight);
	}


	/**
	 * 导出网格图excel
	 */
	function exportExcel($data){
		include_once "module/phpExcel/classes/PHPExcel.php";
		include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
		include_once "module/phpExcel/Classes/PHPExcel/Cell.php";
		include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
		include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
		include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
		include_once "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";
		try{
				//创建一个Excel工作流
				$objPhpExcelFile = new PHPExcel ();
				//Excel2003及以前的格式
				$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );
				//设置当前工作表的名称
				$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", "统计信息" ) );
				//设置模板
				$excelActiveSheet = $objPhpExcelFile->getActiveSheet ();
				foreach($data as $rowkey =>$rows) {
					foreach($rows as $colkey =>$row) {
						$excelActiveSheet->getColumnDimensionByColumn($colkey)->setWidth(20);
						$excelActiveSheet->setCellValueByColumnAndRow ( $colkey, $rowkey+1, iconv ( "GBK", "utf-8", $row) );
					}
				}
				//到浏览器
				ob_end_clean(); //解决输出到浏览器出现乱码的问题
				header("Content-Type: application/force-download");
				header("Content-Type: application/octet-stream");
				header("Content-Type: application/download");
				header('Content-Disposition:inline;filename="' . "统计信息.xls" . '"');
				header("Content-Transfer-Encoding: binary");
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: no-cache");
				$objWriter->save ( 'php://output' );
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}


}


?>
