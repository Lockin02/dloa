<?php
class model_project_ipo extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'project_ipo';
	}
	
	/**
	 * ��ȡ�û�acount��realname����
	 */
	public function getUserList(){
		$SQL = "SELECT `USER_ID`, `USER_NAME` FROM `user` WHERE `ready_left` != 1";
		$query = $this->query($SQL);
		$userList = array();
		while(($rs = $this->fetch_array($query)) != false){
			$userList[] = array('user_id' => $rs['USER_ID'], 'user_name' => $rs['USER_NAME']);
		}
		return $userList;
		
	} 

	function conditions(){
		$condition = '';
		if($_REQUEST['export_keyword'] != ''){
			$condition .= "a.name like '%".trim($_REQUEST['export_keyword'])."%'";
		}
		
		if($_REQUEST['export_projectType'] != ''){
			if($condition != ''){
				$condition .= " AND a.project_type = '".trim($_REQUEST['export_projectType'])."'";
			}else{
				$condition .= " a.project_type = '".trim($_REQUEST['export_projectType'])."'";
			}
		}
		
		if($_REQUEST['export_stage'] != ''){
			if($condition != ''){
				$condition .= " AND a.stage = '".trim($_REQUEST['export_stage'])."'";
			}else{
				$condition .= " a.stage = '".trim($_REQUEST['export_stage'])."'";
			}
		}
		
		if($_REQUEST['export_dept'] != ''){
			if($condition != ''){
				$condition .= " AND a.dept_id = '".trim($_REQUEST['export_dept'])."'";
			}else{
				$condition .= " a.dept_id = '".trim($_REQUEST['export_dept'])."'";
			}
		}
		
		if($_REQUEST['export_ipoType'] != ''){
			if($condition != ''){
				$condition .= " AND a.ipo_id = '".trim($_REQUEST['export_ipoType'])."'";
			}else{
				$condition .= " a.ipo_id = '".trim($_REQUEST['export_ipoType'])."'";
			}
		}
		
		if($_REQUEST['export_zfType'] != ''){
			if($condition != ''){
				$condition .= " AND a.zf_id like '%".trim($_REQUEST['export_zfType'])."%'";
			}else{
				$condition .= " a.zf_id like '%".trim($_REQUEST['export_zfType'])."%'";
			}
		}
		
		if($_REQUEST['export_projectStatus'] != ''){
			if($_REQUEST['export_projectStatus'] != 'all'){
				if($condition != ''){
					$condition .= " AND a.status = '".trim($_REQUEST['export_projectStatus'])."'";
				}else{
					$condition .= " a.status = '".trim($_REQUEST['export_projectStatus'])."'";
				}
			}
			
		}else{
			if($condition != ''){
				$condition .= " AND a.status != '3' AND a.status != '6' ";
			}else{
				$condition .= " a.status != '3' AND a.status != '6' ";
			}
		}
		return $condition;
	}
	
function import(){
		$flag = -1;
		$errList = array();
		
		$filename = $_FILES['upfile']['tmp_name'];
		require_once WEB_TOR . 'includes/classes/PHPExcel.php'; //������   
		require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
		require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';
		$PHPExcel = new PHPExcel ();
		$PHPReader = new PHPExcel_Reader_Excel2007 ( $PHPExcel );
		if (! $PHPReader->canRead ( $filename )){
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
		}
		
		if ($PHPReader->canRead ( $filename )){
			$newRecordList = array();
			$Excel = $PHPReader->load ( $filename );
			$countnum = $Excel->getSheetCount ();
			$this->_db->ping ();
			
			$encrypts = $this->selectField('','','encrypt','');//ȡ����Ŀ����
			
			for($i = 0; $i < $countnum; $i ++){
				$errorList = array();
				
				if ($i == 0){ //��������豸
					$data = $Excel->getSheet ( $i )->toArray ();
					$data = mb_iconv ( $data );
					
					if(count($data) > 0){
						
						for ($i = 1; $i < count($data); $i++){
							$record['encrypt'] = $data[$i][0];
							$record['year'] = $data[$i][1];
							
							$record['project_name']=$data[$i][2];
							$type = 0;
							if(trim($data[$i][3]) == "ļͶ��Ŀ"){
								$type = 1;
							}elseif(trim($data[$i][3]) == "������Ŀ"){
								$type = 2;
							}
							$record['type']=$type;
							$record['leader']=$data[$i][4];
							$record['money']=$data[$i][5];
							$record['amount']=$data[$i][6];
							$record['starttime']=$data[$i][7];
							$record['endtime']=$data[$i][8];
							$state = 0;
							if(trim($data[$i][9])=='������'){
								$state = 1;
							}else if(trim($data[$i][9])=='���δ����'){
								$state = 2;
							}else if(trim($data[$i][9])=='��ɲ�����'){
								$state = 0;
							}
							$record['state']=$state;
							$record['belong_plan']=$data[$i][10];
							$record['description']=$data[$i][11];
							/*
							$stage = 0;
							if(trim($data[$i][3]) == "�о��׶�"){
								$stage = 1;
							}elseif(trim($data[$i][3]) == "�����׶�"){
								$stage = 2;
							}
							$record['stage'] = $stage;
							
							$record['ipo_id'] = $this->getField('id', 'id', 'project_name = "'.$data[$i][4].'" AND type = "1"', 'project_ipo');//ļͶ��Ŀ
							
							
							
							
							$zfStr = "";
							if(!empty($data[$i][5])){
								$zfs = explode(",", $data[$i][5]);
								
								if(is_array($zfs) and count($zfs) > 0){
									foreach ($zfs as $zfName){
										if(!empty($zfName)){
											$zfStr .= $this->getField('id', 'id', 'project_name = "'.$zfName.'" AND type = "2"', 'project_ipo').",";//������Ŀ
										}
									}
									
									if(strlen($zfStr) > 0){
										$zfStr = substr($zfStr, 0, strlen($zfStr) - 1);
									}
								}
							}
							
							$record['zf_id'] = $zfStr;
							
							
							$record['project_type'] = $this->getField('id', 'id', 'typename = "'.$data[$i][6].'"', 'project_rd_type');//��Ŀ����
							
							$record['extra'] = $data[$i][7];//��ͬ\�̻����
							
							
							$record['begin_date'] = $data[$i][8];//����ʱ��
							
							$record['manager'] = $this->getField('USER_ID', 'account', 'USER_NAME = "'.$data[$i][9].'"', 'USER');//��Ŀ����
							
							$record['dept_id'] = $this->getField('DEPT_ID', 'id', 'DEPT_NAME = "'.$data[$i][10].'"', 'department');//��������
							
							$record['status'] = $this->exchangeStatus($data[$i][11], false);//��Ŀ״̬
							
							$productStr = "";
							if(!empty($data[$i][12])){
								$products = explode(",", $data[$i][12]);
								if(is_array($products) and count($products) > 0){
									foreach ($products as $productId){
										$productStr .= $this->getField('id', 'id', 'product_name = "'.$productId.'"', 'product').",";//������Ʒ
									}
									if(strlen($productStr) > 0){
										$productStr = substr($productStr, 0, strlen($productStr) - 1);
									}
								}
							}
							$record['product_id_str'] = $productStr;//������Ʒ
							
							//$record['developer'] = $data[$i][13];//��Ŀ��Ա
							$record['description'] = $data[$i][14];//��Ŀ����
							
							*/
							//var_dump($data[$i][9]);die;
							
							if(!empty($data[$i][0]) && in_array($data[$i][0], $encrypts)){
								$encrypt = $data[$i][0];
								//$result = $this->Edit($record, $encrypt);
								$result = $this->update(array('encrypt'=>$encrypt), $record);
								
							}else{
								$new_id = $this->Add($record);
								
							}
							
							if(!$result){
								$errList[] = $data[$i][0];
							}
						}
						
					}
					
				}
			}
							echo "<pre>";
							print_r($data);
							echo "</pre>";
			
		}else{
			$errList[] = 'error';
		}
		
		if(empty($errList)){
			$flag = 1;
		}
		
		return $flag;
	}
	
	function canImport(){
		$SQL = "SELECT value from project_config";
		$query = $this->query($SQL);
		$data = array();
		while (($rs = $this->fetch_array($query)) != false){
			$data[] = $rs['value'];
		}
		
		return $data;
	}
	
	function export($condition){
		
		/*$SQL = "
				select 
					a.*,b.project_name as ipo_name,c.project_name as zf_name,d.dept_name,e.typename as project_type_name
				from
					$this->tbl_name as a
					left join project_ipo as b on  b.id=a.ipo_id
					left join project_ipo as c on c.id=a.zf_id
					left join department as d on d.dept_id=a.dept_id
					left join project_rd_type as e on e.id = a.project_type 
				where 
					a.is_delete=0  ".($condition ? "and  $condition" : "")."
				order by a.id desc
		";*/
		$SQL = "
				select * from $this->tbl_name
		";

		$query = $this->query($SQL);
		
		//$user_data = $this->get_username();
		while (($rs = $this->fetch_array($query)) != false){
			//var_dump($rs);die;
			/*
			$manager = $rs['manager'] ? $this->get_username_list($user_data, $rs['manager']) : '';
			$assistant = $rs['assistant'] ? $this->get_username_list($user_data, $rs['assistant']) : '';
			$developer = $rs['developer'] ? $this->get_username_list($user_data, $rs['developer']) : '';
			$rs['manager_name'] = $manager ? implode(',', $manager) : '';
			$rs['assistant_name'] = $assistant ? implode(',', $assistant) : '';
			$rs['developer_name'] = $developer ? implode(',', $developer) : '';
			
			$zfList = explode(',', $rs['zf_id']);
			
			
			$zfNameStr = '';
			foreach($zfList as $value){
				if($value != "" and $value != 0){
					$ipoData = $this->getIpoName($value);
					
					
					$zfNameStr .= $ipoData['project_name'].",";
					
				}
				
			}
			if(strlen($zfNameStr) > 0){
				$zfNameStr = substr($zfNameStr, 0, strlen($zfNameStr) - 1);
			}
			$rs['zf_name'] = $zfNameStr;
			*/
			$data[] = $rs;
		}
			//var_dump($data);die;
		if(count($data) > 0){
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ();
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			
			$PHPExcel->setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel->getActiveSheet ();
			
			/**
			 * header
			 */
			$objActSheet->setTitle ( un_iconv ( 'ļͶ��Ŀ' ) );
			
			$objActSheet->setCellValue ( 'A1', un_iconv ( '��Ŀ����' ) );
			$objActSheet->getColumnDimension ( 'A' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'B1', un_iconv ( '�������' ) );
			//$objActSheet->getColumnDimension ( 'B' )->setWidth ( 10 );
			//$objActSheet->setCellValue ( 'C1', un_iconv ( '��Ŀ����' ) );
			$objActSheet->getColumnDimension ( 'C' )->setWidth ( 25 );
			$objActSheet->setCellValue ( 'C1', un_iconv ( '����' ) );
			$objActSheet->getColumnDimension ( 'C' )->setWidth ( 25 );
			
			$objActSheet->setCellValue ( 'D1', un_iconv ( '��Ŀ����' ) );
			$objActSheet->getColumnDimension ( 'D' )->setWidth ( 15 );
			
			$objActSheet->setCellValue ( 'E1', un_iconv ( '������' ) );
			$objActSheet->getColumnDimension ( 'E' )->setWidth ( 15 );
			
			$objActSheet->setCellValue ( 'F1', un_iconv ( '�����ʽ�' ) );
			$objActSheet->getColumnDimension ( 'F' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'G1', un_iconv ( '�����ܶ�' ) );
			$objActSheet->getColumnDimension ( 'G' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'H1', un_iconv ( '��ʼʱ��' ) );
			$objActSheet->getColumnDimension ( 'H' )->setWidth ( 15 );
			
			$objActSheet->setCellValue ( 'I1', un_iconv ( '����ʱ��' ) );
			$objActSheet->getColumnDimension ( 'I' )->setWidth ( 15 );
			
			$objActSheet->setCellValue ( 'J1', un_iconv ( '��Ŀ״̬' ) );
			$objActSheet->getColumnDimension ( 'J' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'K1', un_iconv ( '�����Ƽ��ƻ�' ) );
			$objActSheet->getColumnDimension ( 'K' )->setWidth ( 20 );
			$objActSheet->setCellValue ( 'L1', un_iconv ( '��Ŀ����' ) );
			$objActSheet->getColumnDimension ( 'L' )->setWidth ( 15 );
			
			
			/**
			 * header style
			 */
			$objActSheet->getStyle ( 'A1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objActSheet->getStyle ( 'A1' )->getFill ()->getStartColor ()->setRGB ( 'c0c0c0' );
			$objActSheet->getStyle ( 'A1' )->getFont ()->setBold ( true );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setWrapText ( true );
			$A1Style = $objActSheet->getStyle ( 'A1' );
			$objBorderA1 = $A1Style->getBorders ();
			$objBorderA1->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
			$objBorderA1->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objActSheet->duplicateStyle ( $A1Style, 'A1:P1' );
			$objActSheet->getRowDimension ( '1' )->setRowHeight ( 25 );
			
			/**
			 * set data
			 * @var unknown_type
			 */
			
			$i = 2;
			foreach($data as $key => $record){
				$objActSheet->setCellValue ( 'A' . $i, un_iconv($record['encrypt']));
				$objActSheet->setCellValue ( 'B' . $i, un_iconv($record['year']));
				/*	$type = "";
					if($record['type'] == 1){
						$type = "ļͶ��Ŀ";
					}else if($record['type']==2){
						$type = '������Ŀ';
					}*/
				//$objActSheet->setCellValue ( 'B' . $i, un_iconv($type));
				$objActSheet->setCellValue ( 'C' . $i, un_iconv($record['project_name']));
				
				$type = "";
				if($record['type'] == 1){
					$type = "ļͶ��Ŀ";
				}else if($record['type']==2){
					$type = '������Ŀ';
				}
				$objActSheet->setCellValue ( 'D' . $i, un_iconv($type));
				
				$objActSheet->setCellValue ( 'E' . $i, un_iconv($record['leader']));
				
				$objActSheet->setCellValue ( 'F' . $i, un_iconv($record['money']));
				
				$objActSheet->setCellValue ( 'G' . $i, un_iconv($record['amount']));
				//$objActSheet->getStyle('F' . $i)->getAlignment()->setWrapText(true);
				
				$objActSheet->setCellValue ( 'H' . $i, un_iconv($record['starttime']));
				
				$objActSheet->setCellValue ( 'I' . $i, un_iconv($record['endtime']));
					$state = "";
					if($record['state'] == 1){
						$state = "������";
					}else if($record['state'] == 2){
						$state ="���δ����";
					}else if($record['state'] == 0){
						$state = "��ɲ�����";
					}
				$objActSheet->setCellValue ( 'J' . $i, un_iconv($state));
				$objActSheet->setCellValue ( 'K' . $i, un_iconv($record['belong_plan']));
				$objActSheet->setCellValue ( 'L' . $i, un_iconv($record['description']));
				
				
				
				/*$productNameStr = "";
				if(!empty($record['product_id_str'])){
					$referProducts = explode(',', $record['product_id_str']);
					if(count($referProducts) > 0){
						foreach ($referProducts as  $productId){
							//$productNameStr .= $this->getProduct($productId).",";
							$productNameStr .= $this->getField('product_name', 'name', 'id = "'.$productId.'"', 'product').",";
						}
						if(strlen($productNameStr) > 0){
							$productNameStr = substr($productNameStr, 0, strlen($productNameStr) - 1);
						}
					}
				}
				$objActSheet->setCellValue ( 'M' . $i, un_iconv($productNameStr));
				$objActSheet->getStyle('M' . $i)->getAlignment()->setWrapText(true);
				
				$memberStr = "";
				$members = $this->getMemberListExt($record['id']);
				if(count($members) > 0){
					foreach ($members as $member){
						$memberStr .= $member['USER_NAME'].",";
					}
					if (strlen($memberStr) > 0){
						$memberStr = substr($memberStr, 0, strlen($memberStr) - 1);
					}
				}
				$objActSheet->setCellValue ( 'N' . $i, un_iconv($memberStr));
				$objActSheet->getStyle('N' . $i)->getAlignment()->setWrapText(true);
				
				$objActSheet->setCellValue ( 'O' . $i, un_iconv($record['description']));
				$objActSheet->getStyle('O' . $i)->getAlignment()->setWrapText(true);
				
				$objActSheet->setCellValue ( 'P' . $i, $record['end_date']);
				*/
				$i++;
			}
			/**
			 * output
			 */
			$fileName = 'ļͶ��Ŀ';
			header("Content-type: text/html;charset=GBK");
			header("Content-Type: application/force-download");  
			header("Content-Type: application/octet-stream");  
			header("Content-Type: application/download");
			header("Content-type: application/vnd.ms-excel");
			header('Content-Disposition:inline;filename="'.$fileName.date('Y-m-d').'.xls'.'"');  
			header("Content-Transfer-Encoding: binary");  
			//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
			header("Pragma: no-cache");  
			$objWriter->save('php://output');
		}
	}
	
	public function selectField($conditions = null, $sort = null, $fields = null, $limit = null){
		$rs = $this->findAll($conditions, $sort, $fields, $limit);
		$data=array();
		foreach($rs as $key=>$value){
			foreach($value as $k=>$v){
				$data[]=$v;
			}
		}
		return $data;
	}
	/**
	 * (non-PHPdoc)
	 * ����base.php�����getDataList����
	 */
	function GetDataList($condition = null, $page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$this->num = $this->GetCount ( str_replace ( 'a.', '', $condition ) );
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		return $this->findAll ( $condition,'year desc', '*', $limit );
	}
}