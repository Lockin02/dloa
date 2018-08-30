<?php
class model_product_feedback_story extends model_base{
	
	private $ftp;
	private $ftp_host;
	private $ftp_port;
	private $ftp_pwd;
	private $ftp_user;
	
	function __construct(){
		parent::__construct();
		$this->tbl_name = 'product_demand_info';
		
		//FTP��������
		$this->ftp_host = '172.16.1.102';
		$this->ftp_port = '21';
		$this->ftp_user = 'zentao';
		$this->ftp_pwd  = 'zentao';
	}
	
	function GetDataList($condition=null,$page,$rows){
		if ($page && $rows && !$this->num)
		{
			//$rs = $this->get_one("select count(0) as num from $this->tbl_name ".($condition ? "where ".str_replace('a.', '', $condition) : ''));
			
			$rs = $this->get_one(
				"
									select
										count(0) as num
									from 
										product_demand_info as a
										left join product as b on b.id=a.product_id
										left join user as c on c.user_id=a.userid
										left join user as d on d.user_id=b.manager
										left join user as e on e.user_id=b.assistant
									".($condition ? " where ".$condition : "")."
		"
			);
			
			$this->num = $rs['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
	
		$query = $this->query ( "
									select
										a.*,
										b.product_name,
										b.manager,
										b.assistant,
										c.user_name,
										d.user_name as manager_name,
										e.user_name as assistant_name,
										b.feedback_owner as feedback_owner,
										b.feedback_assistant as feedback_assistant
									from 
										product_demand_info as a
										left join product as b on b.id=a.product_id
										left join user as c on c.user_id=a.userid
										left join user as d on d.user_id=b.manager
										left join user as e on e.user_id=b.assistant
									".($condition ? " where ".$condition : "")."
									order by a.id desc
									" . ($limit ? "limit " . $limit : '') . "
		" );
	
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$rs['date'] = date('Y-m-d',$rs['date']);
			$data[] = $rs;
		}
		return $data;
	}
	
	function getDataInfoById($id){
		$SQL = "
			SELECT 
				a.id as id, 	
				a.product_id as product_id, 
				a.userid as userid,
				a.degree as degree,  
				a.description as description, 
				a.target as target,
				a.FilePathName as FilePathName,
				a.upfile as upfile,
				a.step as step,
				a.algorithm as algorithm,
				a.unit as unit,
				a.contact as contact, 
				a.mobile as mobile,
				a.email as contact_email,
				a.status as status,
				a.feedback as feedback,
				a.notse as notse,
				a.version as version,
				a.title as title,
				a.estimate as estimate,
				a.type as type,
				a.source as source,
				a.deadline as deadline,
				a.status as status,
				
				b.manager as manager, 
				b.assistant as assistant, 
				b.feedback_owner as feedback_owner, 
				b.feedback_assistant as feedback_assistant,
				b.product_name as product_name
			FROM 
				product_demand_info AS a, 
				product AS b 
			WHERE 
				a.product_id = b.id 
			AND 
				a.id = $id
		";
		return array_pop($this->_db->getArray($SQL));
	}
	
	function getCurrentUserInfo($userid){
		$SQL = "
			SELECT  
				* 
			FROM 
				user
			WHERE 
				`USER_ID` = '$userid'
		";
		return array_pop($this->_db->getArray($SQL));
	}
	
	/**
	 * ����(�б�)EXCEL��
	 */
	function model_list_export()
	{
		
		$start_date = $_POST[ 'start_date' ];
		$end_date = $_POST[ 'end_date' ];
		$where .= ($_POST['h_status'] || $_POST['h_status'] == '0') ? " and  a.status='" . $_POST['h_status'] . "'" : '';
		$where .= $where && $_POST['h_product_id'] ? " and product_id='" . $_POST['h_product_id'] . "'" : ($_POST['h_product_id'] ? " and product_id='" . $_POST['h_product_id'] . "'" : '');
		if ($_POST['username'])
		{
			$userid = $this->get_table_fields ( 'user', "user_name='" . $_POST['username'] . "'", 'user_id' );
			if ($userid)
			{
				$where .= " and userid='$userid'" ;
			}
		}
		if($start_date){
			$where .= "  and a.date>='".strtotime($start_date)."'" ;
		}
		if($end_date){
			$where .=  "  and a.date<=".strtotime($end_date) ;
		}
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(0) as num from product_demand_info as a where 1 $where" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{	
			$query = $this->query ( "
									select
										a.*,b.product_name,b.manager,b.assistant,c.user_name,d.user_name as manager_name,e.user_name as assistant_name
									from 
										product_demand_info as a
										left join product as b on b.id=a.product_id
										left join user as c on c.user_id=a.userid
										left join user as d on d.user_id=b.manager
										left join user as e on e.user_id=b.assistant
									where 1 $where
									order by a.id desc
								" );
			
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ();
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			
			$PHPExcel->setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel->getActiveSheet ();
			//=================��ͷ��������======================
			$objActSheet->setTitle ( un_iconv ( '��Ʒ�����б�' ) );
			$objActSheet->setCellValue ( 'A1', un_iconv ( '���' ) );
			$objActSheet->setCellValue ( 'B1', un_iconv ( '�������' ) );
			$objActSheet->setCellValue ( 'C1', un_iconv ( '�ύ��' ) );
			$objActSheet->setCellValue ( 'D1', un_iconv ( '������Ʒ' ) );
			$objActSheet->setCellValue ( 'E1', un_iconv ( '��Ʒ����' ) );
			$objActSheet->setCellValue ( 'F1', un_iconv ( '��Ʒ����' ) );
			$objActSheet->setCellValue ( 'G1', un_iconv ( '��������̶�' ) );
			$objActSheet->setCellValue ( 'H1', un_iconv ( '����״̬' ) );
			$objActSheet->setCellValue ( 'I1', un_iconv ( '�������ͻ�' ) );
			$objActSheet->setCellValue ( 'J1', un_iconv ( "��Ʒ��������\r\n����Ӧ��Ŀ�ģ�" ) );
			$objActSheet->setCellValue ( 'K1', un_iconv ( '�ͻ������ʵ�ַ�ʽ' ) );
			$objActSheet->setCellValue ( 'L1', un_iconv ( '����ʵ�ֲ���' ) );
			$objActSheet->setCellValue ( 'M1', un_iconv ( '����ʵ���㷨' ) );
			$objActSheet->setCellValue ( 'N1', un_iconv ( '�����λ' ) );
			$objActSheet->setCellValue ( 'O1', un_iconv ( '�����' ) );
			$objActSheet->setCellValue ( 'P1', un_iconv ( '�ύ���ֻ�' ) );
			$objActSheet->setCellValue ( 'Q1', un_iconv ( '�ύ��Email' ) );
			$objActSheet->setCellValue ( 'R1', un_iconv ( '��Ʒʵ������' ) );
			$objActSheet->setCellValue ( 'S1', un_iconv ( '��Ʒ�汾��' ) );
			$objActSheet->setCellValue ( 'T1', un_iconv ( '���������' ) );
			
			//================��ͷ��ʽ����==============
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
			$objActSheet->duplicateStyle ( $A1Style, 'A1:T1' );
			$objActSheet->getRowDimension ( '1' )->setRowHeight ( 30 );
			//============================���õ�Ԫ���============================
			$Width_Array = array(
				
						'A'=>8,
						'B'=>18,
						'C'=>15,
						'D'=>15,
						'E'=>10,
						'F'=>10,
						'G'=>8,
						'H'=>10,
						'I'=>6,
						'J'=>25,
						'K'=>30,
						'L'=>25,
						'M'=>25,
						'N'=>28,
						'O'=>15,
						'P'=>15,
						'Q'=>20,
						'R'=>25,
						'S'=>20,
						'T'=>30
			);
			foreach ( $Width_Array as $key => $val )
			{
				$objActSheet->getColumnDimension ( $key )->setWidth ( $val );
			}
			//===========================�������==========================
			$i = 1;
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				switch ($rs['status'])
				{
					case - 1 :
						$status = '�����';
						break;
					case 1 :
						$status = '���ͨ��';
						break;
					case 2 :
						$status = '��ʵ��';
						break;
					default :
						$status = '�����';
				}
				$i ++;
				$rs = un_iconv ( $rs );
				$objActSheet->setCellValue ( 'A' . $i, $rs['id'] );
				$objActSheet->setCellValue ( 'B' . $i, date('Y-m-d',$rs['date']));
				$objActSheet->setCellValue ( 'C' . $i, $rs['user_name'] );
				$objActSheet->setCellValue ( 'D' . $i, $rs['product_name'] );
				$objActSheet->setCellValue ( 'E' . $i, $rs['manager_name'] );
				$objActSheet->setCellValue ( 'F' . $i, $rs['assistant_name'] );
				$objActSheet->setCellValue ( 'G' . $i, ($rs['degree'] == 2 ? un_iconv ( '����' ) : un_iconv ( '��ͨ' )) );
				$objActSheet->setCellValue ( 'H' . $i, un_iconv ( $status ) );
				$objActSheet->setCellValue ( 'I' . $i, ($rs['feedback'] == 1 ? un_iconv ( '��' ) : un_iconv ( '��' )) );
				$objActSheet->setCellValue ( 'J' . $i, str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($rs['description']))));
				$objActSheet->setCellValue ( 'K' . $i, $rs['realize'] );
				$objActSheet->setCellValue ( 'L' . $i, $rs['step'] );
				$objActSheet->setCellValue ( 'M' . $i, $rs['algorithm'] );
				$objActSheet->setCellValue ( 'N' . $i, $rs['unit'] );
				$objActSheet->setCellValue ( 'O' . $i, $rs['contact'] );
				$objActSheet->setCellValue ( 'P' . $i, $rs['mobile'] );
				$objActSheet->setCellValue ( 'Q' . $i, $rs['email'] );
				$objActSheet->setCellValue ( 'R' . $i, $rs['realize_date'] );
				$objActSheet->setCellValue ( 'S' . $i, $rs['version'] );
				$objActSheet->setCellValue ( 'T' . $i, $rs['notse'] );
			}
			
			if ($i > 1)
			{
				$A2Style = $objActSheet->getStyle ( 'A2' );
				$objBorderA2 = $A2Style->getBorders ();
				$objBorderA2->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
				$objBorderA2->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setWrapText ( true );
				
				$objActSheet->duplicateStyle ( $A2Style, 'A2:T' . $i );
			}
			
			//=======================================
			header ( "Pragma: public" );
			header ( "Expires: 0" );
			header ( "Cache-Control:must-revalidate, post-check=0, pre-check=0" );
			header ( "Content-Type:application/force-download" );
			header ( "Content-Type: application/vnd.ms-excel;" );
			header ( "Content-Type:application/octet-stream" );
			header ( "Content-Type:application/download" );
			header ( "Content-Disposition:attachment;filename=" . time () . '.xls' );
			header ( "Content-Transfer-Encoding:binary" );
			$objWriter->save ( 'php://output' );
			//=========
		

		}
	}
	
	/**
	 * ����ͳ��EXCEL��
	 */
	function model_count_export()
	{
		$start_date = $_GET[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ];
		
		$where .= ($_GET['status'] || $_GET['status'] == '0') ? " and a.status='" . $_GET['status'] . "'" : '';
		$where .= $where && $_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : ($_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : '');
		if ($_GET['username'])
		{
			$userid = $this->get_table_fields ( 'user', "user_name='" . $_GET['username'] . "'", 'user_id' );
			if ($userid)
			{
				$where .= " and userid='$userid'";
			}
		}
		if($start_date){
			$where .= "  and a.date>='".strtotime($start_date)."'" ;
		}
		if($end_date){
			$where .=  "  and a.date<=".strtotime($end_date) ;
		}
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(distinct(a.userid)) as num from product_demand_info as a where 1 $where" );
			$this->num = $rs['num'];
		}
		if ($this->num)
		{
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ();
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			$PHPExcel->setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel->getActiveSheet ();
			//=====================���ñ�ͷ==========================
			$objActSheet->setTitle ( un_iconv ( '��Ʒ����ͳ�Ʊ�' ) );
			
			$objActSheet->setCellValue ( 'A1', un_iconv ( '�ύ��' ) );
			$objActSheet->getColumnDimension ( 'A' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'B1', un_iconv ( '���ύ����' ) );
			$objActSheet->getColumnDimension ( 'B' )->setWidth ( 15 );
			$objActSheet->mergeCells ( 'C1:D1' );
			$objActSheet->setCellValue ( 'C1', un_iconv ( '�������' ) );
			$objActSheet->mergeCells ( 'E1:F1' );
			$objActSheet->setCellValue ( 'E1', un_iconv ( '�������' ) );
			$objActSheet->mergeCells ( 'G1:H1' );
			$objActSheet->setCellValue ( 'G1', un_iconv ( '��ȷ����' ) );
			$objActSheet->mergeCells ( 'I1:J1' );
			$objActSheet->setCellValue ( 'I1', un_iconv ( '��ʵ����' ) );
			//================��ͷ��ʽ����==============
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
			$objActSheet->duplicateStyle ( $A1Style, 'A1:J1' );
			$objActSheet->getRowDimension ( '1' )->setRowHeight ( 25 );
			//===========================================================
			$query = $this->query ( "
									select 
										distinct(a.userid),user_name
									from 
										product_demand_info as a 
										left join user as b on b.user_id=a.userid
									     where 1
									     		$where
										" );
			$i = 1;
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				$i ++;
				$rs = un_iconv ( $rs );
				$objActSheet->setCellValue ( 'A' . $i, $rs['user_name'] );
				$sql = $this->query ( "select count(0) as num,a.userid,a.status from product_demand_info as a where 1 and  a.userid='" . $rs['userid'] . "' group by a.userid,a.status" );
				$data = array();
				while ( ($row = $this->fetch_array ( $sql )) != false )
				{
					$data[$row['status']] = $row['num'] ? $row['num'] : 0;
				}
				$objActSheet->setCellValue ( 'B' . $i, array_sum ( $data ) );
				$objActSheet->setCellValue ( 'C' . $i, ($data['0'] ? $data['0'] : 0) );
				$objActSheet->setCellValue ( 'D' . $i, round ( ($data['0'] * 100) / array_sum ( $data ), 2 ) . '%' );
				$objActSheet->setCellValue ( 'E' . $i, ($data['-1'] ? $data['-1'] : 0) );
				$objActSheet->setCellValue ( 'F' . $i, round ( ($data['-1'] * 100) / array_sum ( $data ), 2 ) . '%' );
				$objActSheet->setCellValue ( 'G' . $i, ($data['1'] ? $data['1'] : 0) );
				$objActSheet->setCellValue ( 'H' . $i, round ( ($data['1'] * 100) / array_sum ( $data ), 2 ) . '%' );
				$objActSheet->setCellValue ( 'I' . $i, ($data['2'] ? $data['2'] : 0) );
				$objActSheet->setCellValue ( 'J' . $i, round ( ($data['2'] * 100) / array_sum ( $data ), 2 ) . '%' );
			}
			//========================================
			if ($i > 1)
			{
				$A2Style = $objActSheet->getStyle ( 'A2' );
				$objBorderA2 = $A2Style->getBorders ();
				$objBorderA2->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
				$objBorderA2->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setWrapText ( true );
				
				$objActSheet->duplicateStyle ( $A2Style, 'A2:J' . $i );
			}
			//=======================================
			header ( "Pragma: public" );
			header ( "Expires: 0" );
			header ( "Cache-Control:must-revalidate, post-check=0, pre-check=0" );
			header ( "Content-Type:application/force-download" );
			header ( "Content-Type: application/vnd.ms-excel;" );
			header ( "Content-Type:application/octet-stream" );
			header ( "Content-Type:application/download" );
			header ( "Content-Disposition:attachment;filename=" . time () . '.xls' );
			header ( "Content-Transfer-Encoding:binary" );
			$objWriter->save ( 'php://output' );
			//=========
		}
	}
	
	function getFileInfo($fileName, $filePath){
		//�����ļ���
		$pms_folder_name = date('Ym');
		//��ȡFTP����
		$this->getFtpConnect();
		$fileArr = array();
		$postFileInfo = '';
		$url = "/upfile/product/demand/";
		if($filePath != "" || $fileName != ""){
			$fileArr = spliti(',', $fileName);
			$info = array();
			if(count($fileArr) != 0){
				foreach($fileArr as $key => $value){
					if($value != ""){
						$fullUrl = $url.$filePath."/".$value;
						//��ȡOA�ļ���Ϣ
						$info = pathinfo($fullUrl);
						//����PMS��Ӧ���ļ�·������
						$pathName = $this->setPathName(0, $info['extension']);
						$postFileInfo .= 'title=='.$info['filename'].
										'|||extension=='.$info['extension'].
										'|||size==0'.
										//'|||pathname=='.$pms_folder_name.'/'.$info['basename'].
										'|||pathname=='.$pms_folder_name.'/'.$pathName.
										'%%%';
						//�ϴ��ļ���PMS FTP
						$this->uploadFile($filePath.'/'.$info['basename'], $pathName, $pms_folder_name);
					}
				}
				$this->ftp->close();
			}
		}
		return substr($postFileInfo, 0, strlen($postFileInfo)-3);
	}
	
	function getFtpConnect(){
		$this->ftp = new includes_class_ftp($this->ftp_host,$this->ftp_port,$this->ftp_user,$this->ftp_pwd);
	}
	
	function uploadFile($filePath, $ftp_file_name, $ftp_file_path){
		$ftpPath = '/www/data/upload/1/'.$ftp_file_path.'/'.$ftp_file_name;
		$path = WEB_TOR.'upfile/product/demand/'.$filePath;
		if(!$this->ftp->up_file($path,$ftpPath)){
			$this->ftp->close();
			showmsg('�ϴ�����ʧ��,����OA����Ա��ϵ��');
		}
		
	}
	
	function setPathName($fileID, $extension)
    {
        $sessionID  = session_id();
        $randString = substr($sessionID, mt_rand(0, strlen($sessionID) - 5), 3);
        return date('dHis', $this->now) . $fileID . mt_rand(0, 10000) . $randString . '.' . $extension;
    }
	
	/**
	 * ��ȡ upfile �ֶ�
	 */
	function getUploadFile($id){
		$result = $this->getDataInfoById($id);
		return spliti(',', $result['upfile']);
	}
	
	/**
	 * ��ȡ uploadPath �ֶ�
	 */
	function getUploadPath($id){
		$result = $this->getDataInfoById($id);
		return $result['FilePathName'];
	}
	
	function synchronization_data($rs){
		
		$isSucceed = true;
		$fileInfo = $this->getFileInfo($rs['upfile'], $rs['FilePathName']);
		$demand = array(
			'company' => 1,
			'product' => $rs['product_id'],
			'product_name' => $rs['product_name'],
			'title' => $rs['title'],
			'type' => $rs['type'],
			'spec' => $rs['description'],
			'source' => $rs['source'],
			'deadline' => $rs['deadline'],
			'tid' => $rs['id'],
			'step' => $rs['step'],
			'algorithm' => $rs['algorithm'],
			'files' => $fileInfo
		);
		
		$user = $this->getCurrentUserInfo($_SESSION['USER_ID']);
		$pms = new api_pms($_SESSION['USER_ID'], $user['PASSWORD']);
		$result = $pms->GetModule('story', 'addDemandFromOa',un_iconv($demand),'post');
		if($result->status == 'success'){
			$pms_story_id = str_replace('"', '', $result->data);
			$pms_story = array(
				'pms_story_id' => (int)$pms_story_id
			);
			
			if(!$this->Edit($pms_story, $rs['id'])){
				$isSucceed = false;
			}
		}else{
			$isSucceed = false;
		}
		
		return $isSucceed;
	}
	
	/**
	 * �����ϴ��ļ�
	 */
	function saveUploadFile(){
		$fileArr = array();
		$path = "";
		if ($_FILES ['upfile']['name']){
			if($_POST['id'] != ""){
				$path = $this->getUploadPath($_POST['id']);
			}
			
			if($path != ''){
				$FilePathName = $path;
			}else{
				$FilePathName = md5 ( time () . rand ( 0, 99999 ) );
			}
			if (! is_dir ( WEB_TOR . 'upfile/product/demand/' . $FilePathName )){
					if (! mkdir ( WEB_TOR . 'upfile/product/demand/' . $FilePathName )){
						showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ����' );
					}
			}
			$_POST ['FilePathName'] = $FilePathName;
			
			//========�����ϴ��ļ���ʼ============
			
			if($_POST['id'] != ''){
				$fileArr = $this->getUploadFile($_POST['id']);
			}
			
			$temp_file_arr = array ();
			$count = 0;
			foreach($_FILES ['upfile'] ['name'] as $key => $value){
				$count++;
				if(!in_array($value, $fileArr)){
					if ($value != ""){
						$name = date('YmdHis');
						$num = strripos($value, '.');
						if($num !== false){
							$suffix = substr($value, $num, 4);
							$name = $name.'-'.$count.$suffix;
							if (move_uploaded_file ( str_replace ( '\\\\', '\\', $_FILES ['upfile'] ['tmp_name'] [$key] ), WEB_TOR . 'upfile/product/demand/' . $FilePathName . '/' . $name )){
								$temp_file_arr [] = $name;
							} else{
								showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ��' );
							}
						}else{
							showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ��' );
						}
						
						
					}
				}
			}
			
			$temp_file_arr = array_merge_recursive($temp_file_arr, $fileArr);
			foreach($temp_file_arr as $key => $value){
				if($value == ''){
					unset($temp_file_arr[$key])	;
				}
			}
			$_POST ['upfile'] = $temp_file_arr ? implode ( ',', $temp_file_arr ) : '';
		}
	}
	
	/**
	 * ��������
	 */
	function saveData(){
		$_POST = mb_iconv($_POST);
		
		$id = 0;
		if(!empty($_POST['userid'])){
			$_POST['userid'] = $this->getAccountByRealName($_POST['userid']);
		}else{
			$_POST['userid'] = $_SESSION['USER_ID'];
		}
		
		
		if ($_POST['id']){
			$_POST['update_time'] = time();
			if ($this->Edit ( $_POST, $_POST ['id'] )){
				$msg = 1;
				$id = $_POST ['id'];
			}else{
				$msg = - 1;
			}
		}else{
			//$_POST['userid'] = $_SESSION['USER_ID'];
			$_POST ['date'] = time ();
			$_POST['update_time'] = time();
			
			$id = $this->Add ( $_POST );
			if ($id != 0){
				$msg = 1;
			}else{
				$msg = - 1;
			}
		}
		
		$result = array();
		if($msg == 1){
			$result = $this->getDataInfoById($id);
		}
		
		return $result;
	}
	
	/**
	 * save import data
	 */
	public function saveImportData($data){
		return $this->Add ( $data );
	}
	
	
	//�ʼ����ݣ�״̬�ı�
	public function mailNoteForStatusChange($data, $oldStatus){
		
		$str = '�����';
		if($data['status'] == 1){
			$str = '���ͨ��';
		}elseif($data['status'] == 2){
			$str = '��ʵ��';
		}elseif($data['status'] == 3){
			$str = '�ѳ���';
		}
		$note = "";
		
		if($data['status'] == -1){
			$note = $data['product_name'].' ��Ʒ����'.$data['title'].'���������������� '.$str.' ,�������¼OA�鿴��'.oaurlinfo;
		}else{
			if($oldStatus == 3){
				$note = $data['product_name'].' ��Ʒ����'.$data['title'].'���������������� '.$str.' ,�������¼OA�鿴��'.oaurlinfo;
			}else{
				$note = $data['product_name'].' ��Ʒ����'.$data['title'].'���������������� '.$str.' ,�������¼PMS�鿴��'.oaurlinfo;
			}
		}
		
		return array(
				'title' => $data['product_name'].' ��Ʒ����'.$data['title'].'����������������',
				'body' => $note
			);
		
	}
	
	public function mailNoteForNew($data){
		
		$body = "";
		$body .= '�������ƣ�'.$data['title'].'<br />';
		$body .= '������Ʒ��'.$data['product_name'].'<br />';
		$body .= '�Ƿ��и���:'.($data ['upfile'] ? '��' : '��').'<br />';
		$body .= '<hr />��Ʒ����������<br />';
		$body .= preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($data['description']));
		$body .= oaurlinfo;
		
		return array(
			'title' => $data['product_name'].' ����������',
			'body' => $body
		);
	}
	
	//�����ʼ�����
	function sendMail($mailTitle, $mailNote, $address){
		$email = new includes_class_sendmail();
		return $email->send($mailTitle, $mailNote.'�������¼OA�鿴��'.oaurlinfo, $address);
	}
	
	/**
	 * ����Excel����
	 */
	function model_save_import($product_id){
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
			for($i = 0; $i < $countnum; $i ++){
				$errorList = array();
				
				if ($i == 0){ //��������豸
					$data = $Excel->getSheet ( $i )->toArray ();
					$data = mb_iconv ( $data );
					
					for ($i = 1; $i < count($data); $i++){
						
						if(!empty($data[$i][0]) && !empty($data[$i][1]) && !empty($data[$i][3])){
							
							$addData['title'] = $data[$i][0];//��������
							$addData['degree'] = $this->getDegreeId($data[$i][1]);//�����̶�
							$addData['deadline'] = $data[$i][2];//�������
							$addData['description'] = $data[$i][3];//��������
							$addData['step'] = $data[$i][4];//ʵ�ֲ���
							$addData['algorithm'] = $data[$i][5];//ʵ���㷨
							$addData['contact'] = $data[$i][6];//�����
							$addData['unit'] = $data[$i][7];//�����λ
							$addData['mobile'] = $data[$i][8];//������ֻ�
							$addData['product_id'] = $product_id;
							$addData['userid'] = $_SESSION['USER_ID'];
							$addData['email'] = $_SESSION['EMAIL'];
							$addData['date'] = time();
							$_POST['update_time'] = time();
							
							$newId = $this->saveImportData($addData);
							if(!$newId){
								$errorList[] = $i;
							}else{
								$newRecordList[] = $newId;
							}
						}
					}
				}
			}
		}	
		return $newRecordList;			
	}
	
	private function getAccountByRealName($name){
		$SQL = "
			SELECT  
				USER_ID  
			FROM 
				user
			WHERE 
				`USER_NAME` = '{$name}'
		";
		$result = $this->get_one ( $SQL );
		return $result['USER_ID'];
	}
	
	private function getDegreeId($name){
		$list = array(
			'��ͨ' => '1',
			'����' => '2'
		);
		
		return $list[$name];
	}
	
	function import_sendMail($recordList){
		$mailParamList = array();
		if(is_array($recordList) and count($recordList) > 0){
			$data = array();
			$titleList = array();
			foreach ($recordList as $key => $value){
				if(!empty($value)){
					$data = $this->getDataInfoById($value);
					
					$titleList[] = $data['title'];
				}
			}
			
			if(count($titleList) > 0){
				$body = "";
				$body .= '������Ʒ��'.$data['product_name'].'<br />';
				$body .= '��������<br/>';
				foreach($titleList as $value){
					$body .= "{$value}<br/>";
				}
				
				$body .= preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",'');
				$body .= oaurlinfo;
				
				
				$user_id_list = $data['feedback_owner'];
				$user_id_list .= $data['feedback_assistant'] ? ','.$data['feedback_assistant']: '';
				
				$address = $this->getAddressList($user_id_list);
				
				$mailParamList = array(
					'address' => $address,
					'title' => $data['product_name'].' ����������',
					'body' => $body
				);
				
			
			}
		} 
		return $mailParamList;
	}
	
	function getAddressList($data){
		$emailList = array();
		$dataList = explode(",", $data);
		
		foreach ($dataList as $value){
			$result = $this->getCurrentUserInfo($value);
			
			$emailList[$result['EMAIL']] = $result['USER_NAME'];
		}
		return $emailList;
	}
	
	function model_delete($id){
		
		$SQL = "DELETE FROM {$this->tbl_name} WHERE id = '{$id}'";
		return $this->_db->query($SQL);
	}
}