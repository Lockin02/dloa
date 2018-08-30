<?php
class model_administration_appraisal_performance_statistics extends model_base
{

	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'appraisal_performance';
		$this->pk = 'id';
		$this->filebase = "attachment/appraisal/att/";
	}


	function model_administration_statistics_deptData() {
		global $func_limit;
		$deptIds = $func_limit['管理部门'] ? $func_limit['管理部门']: $_SESSION['DEPT_ID'];
		$deptIds=str_replace(';;','',$deptIds);
		$deptIds=trim($deptIds,',');
		if($deptIds){
			$str=" and dept_id IN ($deptIds)";
		}	
	    $sql="SELECT dept_id,parent_id,dept_name FROM `department` WHERE  1 $str and delflag=0";				
	    $query = $this->query ( $sql );
	    $data[]=un_iconv(array(
				'id'=>'',
				'text'=>'所属所有部门',
				'pid'=>0
			));
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$data[]=un_iconv(array(
				'id'=>$row['dept_id'],
				'text'=>$row['dept_name'],
				'pid'=>$row['parent_id']
			));
		}
		return json_encode ( $data );

	}
function model_administration_appraisal_performance_list_deptDataAll() {
		    $sql="SELECT dept_id,parent_id,dept_name FROM `department` WHERE  delflag=0";				
		    $query = $this->query ( $sql );
		    $data[]=un_iconv(array(
					'id'=>'',
					'text'=>'所属所有部门',
					'pid'=>0
				));
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$data[]=un_iconv(array(
					'id'=>$row['dept_id'],
					'text'=>$row['dept_name'],
					'pid'=>$row['parent_id']
				));
			}
		return json_encode ( $data );

	}	

function model_administration_statitics_indexData($exFlag=false) {
	    global $func_limit;
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$deptId=$deptId?$deptId:($func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID']);
		$deptId=str_replace(';;','',$deptId);
		$deptId=trim($deptId,',');
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.deptId in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		//$sqlstr.=" and a.inFlag='7' ";
		//S$this->tbl_name = 'appraisal_performance';
		$sqlstr.=" and a.inFlag<>'10' ";
		$sqlc = "select COUNT(1) as num  FROM (select id FROM  appraisal_performance as a   
						where  1  $sqlstr  GROUP BY a.years,a.user_id) b";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
				$order = " order by a.years desc";
		}
		$sqlStrCont="select a.user_id,a.years,a.quarter,a.finalScore,a.deptRank,a.deptRankPer
			    from 
				appraisal_performance as a
				where  1  $sqlstr ";		
		$querys = $this->query ( $sqlStrCont );
		while ( ($row = $this->fetch_array ( $querys )) != false )
		{
			for($i=1;$i<5;$i++){
				$dataArr[$row['user_id']][$row['years']]['finalScoreQ'.$i]=$row['quarter']==$i?$row['finalScore']:$dataArr[$row['user_id']][$row['years']]['finalScoreQ'.$i];
				$dataArr[$row['user_id']][$row['years']]['deptRankQ'.$i]=$row['quarter']==$i?$row['deptRank']:$dataArr[$row['user_id']][$row['years']]['deptRankQ'.$i];
				$dataArr[$row['user_id']][$row['years']]['deptRankPerQ'.$i]=$row['quarter']==$i?$row['deptRankPer']:$dataArr[$row['user_id']][$row['years']]['deptRankPerQ'.$i];	
			}
			
		}
		$sql="select a.userNo,a.user_id,a.userName,a.deptName,a.jobName,a.comeInDate,a.ReguDate,a.years
			    from 
				appraisal_performance as a
				where  1  $sqlstr  GROUP BY a.years,a.user_id $order ".($exFlag?'':"limit $start,$pageSize");		
		$query = $this->query ( $sql );
		$data=array();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$dataI[$row['years']][$row['user_id']]=$row;
			$dataI[$row['years']][$row['user_id']]=array_merge($dataI[$row['years']][$row['user_id']],$dataArr[$row['user_id']][$row['years']]);
			//$dataI[$row['years']][$row['user_id']]['finalScoreQ'.$row['quarter']]=$row['finalScore'];
			//$dataI[$row['years']][$row['user_id']]['deptRankQ'.$row['quarter']]=$row['deptRank'];
			//$dataI[$row['years']][$row['user_id']]['deptRankPerQ'.$row['quarter']]=$row['deptRankPer'];
			//array_push($data,un_iconv($row));
			//unset($dataI[$row['years']][$row['user_id']]['user_id']);
		}
		if($dataI&&is_array($dataI)){
			foreach($dataI as $key=>$_dataI){
				foreach($_dataI as $_key=>$val){
					array_push($data,un_iconv($val));
					if($exFlag){
						$exT=array();
						foreach($val as $_keys=>$vals){
							if($_keys!='user_id'&&$_keys!='comeInDate'&&$_keys!='ReguDate'&&$_keys!='date'){
								$exT[]=$vals;
							}
						}
						$exData[]=$exT;
					}	
				}
			}
		}
		if($exFlag){
			return $exData;
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );

	}	
	function model_administration_appraisal_performance_list_perListData() {
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.assessName like '%$wordkey%' or a.auditName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.dept_id='$deptId'";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		$sqlc = "select count(0) as num 
					from 
						appraisal_performance as a 
						where  1 and a.user_id='".$_SESSION['USER_ID']."' $sqlstr ";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " ORDER BY a.years DESC,a.quarter DESC, a.id DESC";
		}
		 $sql="select a.*
			    from 
				appraisal_performance as a
				where  1 and a.user_id='".$_SESSION['USER_ID']."' and a.inFlag<>10  $sqlstr $order limit $start,$pageSize";				
		$query = $this->query ( $sql );
		$data=array();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?(date('Y-m-d',strtotime($row['assess_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['assess_date']))):'';
			$row['audit_date']=$row['audit_date']?(date('Y-m-d',strtotime($row['audit_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['audit_date']))):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			if(($row['inFlag']<6.5)||($_SESSION['USER_ID']==$row['audit_userid'])){
				$row['finalScore']='';//$row['countFraction']-($row['count_audit_fraction']*$row['asAudit']/100);
				$row['count_audit_fraction']='';
				$row['deptRank']='';
				$row['deptRankPer']='';
			}
			array_push($data,un_iconv($row));
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );

	}	
	
   function model_administration_appraisal_transformCycle($case){
   	 if($case){
   	 	switch ($case)
		{
			case 1:
			    return '一季度';
			  break;  
			case 2:
			   return  '二季度';
			  break;
			case 3:
			   return  '三季度';
			  break;
			case 4:
			   return  '四季度';
			  break;
			case 5:
			   return  '上半年';
			  break;
			case 6:
			   return  '下半年';
			  break;
			case 7:
			   return  '全年';
			  break; 
   	   }
   }
   }
      
   function model_administration_appraisal_performance_list_getHeaderData($tplId){
     if($tplId){
     	$this->tbl_name = 'appraisal_template_columnname';
     	$conData=$this->findAll(array('tId'=>$tplId));
     	if($conData&&is_array($conData)){
     		foreach($conData as $key =>$val){
     			if($val){ 
     			   $str.="<td align='center' ><strong>$val[columnsName]</strong></td>";
     			}
     		}
     	$data['str']=$str;
     	$data['num']=count($conData);	
     	}
     	return $data; 
     }
   }

	
	

function model_list_getDeptInfo($deptName){
	if($deptName){
	  $this -> tbl_name = 'department';
	  $deptInfo=$this->find(array('DEPT_NAME'=>trim($deptName)));	
	}
  return $deptInfo;
}
function model_list_getJobInfo($deptName,$jobName){
	if($deptName&&$jobName){
	    $Sql =" SELECT b.`name`,b.id
				FROM  department as a LEFT JOIN user_jobs as b ON a.DEPT_ID=b.dept_id
				WHERE  a.DEPT_NAME='$deptName' AND b.`name`='$jobName' ;";
			$query = $this->query ( $Sql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$jobInfo = $row;
				
			}
	}
  return $jobInfo;
}    
function model_list_getUserIdInfo($userName){
	if($userName){
	  $this -> tbl_name = 'user';
	  $userInfo=$this->find(array('USER_NAME'=>trim($userName)));	
	}
  return $userInfo;
	
} 

 function model_administration_appraisal_performance_list_optionStatus(){
 	$inFlag=$_POST['inFlag'];
 	$keyId=$_POST['keyId'];
 	if($inFlag&&$keyId){
 		$this->tbl_name = 'appraisal_performance';
		$flag=$this -> update ( array ( 'id' =>$keyId) , array('inFlag'=>$inFlag));
		if($flag){
			$flag=2;
		}
 	}
 	return $flag;
 }

 
 function model_administration_statistics_exExcel() {
 		$title = '员工考核数据';
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$Title = array (array ($title));
		$ExcelData[] = array ("员工号","被考核人","所属部门","职位","年份","考核周期","状态","自评总分",
							  "评价总分","考核总分","审核总分","加权得分","最终得分","排名","排名比例","z","z");
		$ExcelData[] = array ("员工号","被考核人","所属部门","职位","年份","第一季度","状态","自评总分",
							  "第二季度","考核总分","审核总分","第三季度","最终得分","排名","第四季度","z","z");
		$ExcelData[] = array ("员工号","被考核人","所属部门","职位","年份","最终得分","排名","排名比例(前%)",
							  "最终得分","排名","排名比例(前%)","最终得分","排名","排名比例(前%)","最终得分","排名","排名比例(前%)");
		$ExcelDataT=$this->model_administration_statitics_indexData(true);
			
		$data = array_merge($ExcelData,$ExcelDataT);
       	$total = ( count ( $data ) - 4 );
		$xls = new includes_class_excel ( $title . '.xls' );
		$xls -> SetTitle ( array ( $title) , $Title );
		$xls -> SetContent (array ($data));
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A1:Q1' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A2:A4' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'B2:B4' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'C2:C4' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'D2:D4' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'E2:E4' );
		
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'F2:Q2' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'F3:H3' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'I3:K3' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'L3:N3' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'O3:Q3' );
		
		//$xls -> objActSheet[ 0 ] -> mergeCells ( 'E2:P2' );
		//$xls -> objActSheet[ 0 ] -> mergeCells ( 'E2:P2' );
		//$xls -> objActSheet[ 0 ] -> mergeCells ( 'E2:P2' );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A2:Q2' ) -> getFont ( ) -> setBold ( true );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:Q'.($total+5)) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$styleArray = array ( 
								'borders' => array ( 
													'allborders' => array ( 
																			'style' => PHPExcel_Style_Border :: BORDER_THIN , 
																			'color' => array ( 
																							'argb' => '00000000' 
																			) 
													) 
								) 
			);
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:Q'.($total+5) ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:Q'.($total+5)) -> applyFromArray ( $styleArray );
		
		$xls -> objActSheet[ 0 ] -> setCellValue ( 'A' . ( $total + 8 ) , un_iconv ( '合计：' . $total ) );
		$columnData=array('A','B','C','D','E','F','G','H','I','J','K','M','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA');	
		foreach($columnData as $key =>$val){
		  $xls -> objActSheet[ 0 ] -> getColumnDimension ( $val ) -> setWidth ( 15 );
		}
		$xls -> OutPut ( );
	}
}