<?php
/*
 * Created on 2010-9-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * FusionChartsͼ����
 */

class model_common_fusionCharts extends model_base{
	function __construct($isInclude=true) {
		parent::__construct ();
		if($isInclude==true){
			//�������ļ�
			include(WEB_TOR."FusionCharts/Includes/FusionCharts.php");
			echo "<script type='text/javascript' src='FusionCharts/js/FusionCharts.js'></script>";
			echo "<script type='text/javascript' src='FusionCharts/js/FusionChartsExportComponent.js'></script>";
		}
	}

	private  $arr_chartConf = array(
		 'exportEnabled'=>1,                //�Ƿ�������images/pdf s
		 'showPrintMenuItem'=>0,            //�Ƿ����Ҽ��˵�����ʾ��ӡѡ��
         'exportHandler'=>"FusionCharts/exportHandlers/FCExporter.php",       //���������нű�����·��
  		 'exportAtClient'=>0,               //0: �����������У� 1�� �ͻ�������
	     'exportAction'=>"download",              //����Ƿ��������У�֧�����������or����������
	     'exportFileName'=>"ͼ��",                 //�������ļ���
	     'caption'=>"�ҵ�ͼ��",                 //��ʾ����
	     'showValues' =>1,                       //�Ƿ���ͼ������ʾֵ
	     'numberPrefix' =>"",                   //ǰ׺ ��  $180
	     'numberSuffix' =>"",  					//��׺ ��  180/��
		 'baseFontSize'=>12,                         //�����С
		 'numVisiblePlot'=>20,                       //����ͼ�������÷�����Ŀ
		 'yAxisMaxValue'=>80,                        //y������ֵ�����Զ������Ĭ��ֵ��ʱ��Ч
		 'exportCallback'=>"FC_Exported",
		 'formatNumberScale' =>0,
		 'Decimals'=>2, //С�������λ ��������
		 'forceDecimals'=>0 //С����λ�������Ƿ�ǿ�Ʋ�0
//		 'labelDisplay'=>'WRAP', //y���ǵ���ʾ���
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
	 * ��ȡ����xml
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
	 * ��ͼ
	 * �������鷵��ͼ��
	 * ��һ��������ֵ����
	 * �ڶ��������Ǵ�����ͼ������ ��Column2D.swf
	 * �����������ǹ���ͼ��xml�ĸ������������Ϊ���飬�ο�$this->arr_chartConf
	 * �������������ͼ��ĳ�������Ĭ��ֵ
	 */
	function showCharts($rows,$swfName="Column2D.swf",$chartConf,$width='450',$hight='350'){
		if(empty($swfName)){
			$swfName="Column2D.swf";
		}
	    $strXML=$this->getCharXml($rows,$chartConf);

		return renderChart("$swfName", "", $strXML, "productSales".rand(), $width, $hight);
	}

/**
 * ��ͼ��
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
	 * �����״ͼ
	 * $rows һ����ά���飬����dataΪ��ά����
	 */
	function getMixCharXml($rows,$chartConf,$isMergeData=true){
		if(!is_array(reset($rows))){
			return $this->getSimpleCharXml($rows,$chartConf);
		}
		//if($isMergeData==true){
			//�������ݵ���ȷ��
			$mergeArr=array();
			foreach($rows as $key=>$valArr){
				$mergeArr=array_merge($mergeArr,$valArr);
			}
			//���յ�һ�������key˳���������
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
	 * �����״ͼ
	 */
	function showMixChart($rows, $swfName, $chartConf, $width = '450', $hight = '350') {
		$strXML = $this->getMixCharXml ( $rows, $chartConf );
		return renderChart ( "$swfName", "", $strXML, "productSales", $width, $hight );
	}

	/**
	 * �����״ͼ���з��ã�
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
		//�����ͼ����
		$strCategories = "<categories>";

		//Initiate <dataset> elements
		$strDataCurr = "<dataset seriesName='���ƹ�����' color='". $this->getColor() ."'>";
		$strDataPrev = "<dataset seriesName='��Ͷ�빤����' color='". $this->getColor() ."'>";
		//Iterate through the data
		foreach ($rows as $arSubData) {
			$col++;
	        //Append <category name='...' /> to strCategories
	        $strCategories .= "<category name='" . $arSubData[1] . "' />";
	        //Add <set value='...' /> to both the datasets
	        $strDataCurr .= "<set value='" . $arSubData[2] . "' />";
	        $strDataPrev .= "<set value='" . $arSubData[3] . "' />";
		}

		//�պ�
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
	 * ����̬�����ͼ
	 */
	function mixCharts($rows,$name = '�ҵ�ͼ��',$numberPrefix='',$numberSuffix = '',$width='600',$hight='300',$nameArr = null){
		$strXML = "<graph  exportEnabled='".$this->exportEnabled."' showPrintMenuItem='".$this->showPrintMenuItem."' exportAtClient='".$this->exportAtClient."' exportHandler='".$this->exportHandler."' exportAction='".$this->exportAction."' exportFileName=='$name'  useRoundEdges='1' caption='".$name."4444' showValues ='1' numberPrefix = '".$numberPrefix."' numberSuffix='".$numberSuffix."' baseFontSize='".$this->baseFontSize." yAxisMaxValue='100'>";

		$tempArr = array();
		$tempCount = 0;

		foreach($nameArr as $val){
			$tempArr[$tempCount] .= "<dataset seriesName='".$val."' color='". $this->getColor() ."'>";
			$tempCount++;
		}

		//����XML��ʼ
		$strCategories = "<categories>";

		//ѭ���������
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

		//�պ�
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

		//ʵ����ͼ��
		return renderChart("MSColumn2D.swf", "", $strXML, "productSales", $width, $hight);
	}


	/**
	 * ��������ͼexcel
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
				//����һ��Excel������
				$objPhpExcelFile = new PHPExcel ();
				//Excel2003����ǰ�ĸ�ʽ
				$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );
				//���õ�ǰ�����������
				$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", "ͳ����Ϣ" ) );
				//����ģ��
				$excelActiveSheet = $objPhpExcelFile->getActiveSheet ();
				foreach($data as $rowkey =>$rows) {
					foreach($rows as $colkey =>$row) {
						$excelActiveSheet->getColumnDimensionByColumn($colkey)->setWidth(20);
						$excelActiveSheet->setCellValueByColumnAndRow ( $colkey, $rowkey+1, iconv ( "GBK", "utf-8", $row) );
					}
				}
				//�������
				ob_end_clean(); //��������������������������
				header("Content-Type: application/force-download");
				header("Content-Type: application/octet-stream");
				header("Content-Type: application/download");
				header('Content-Disposition:inline;filename="' . "ͳ����Ϣ.xls" . '"');
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
