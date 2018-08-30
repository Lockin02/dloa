<?php

/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:��ͬ��Ϣ Model��
 */
class model_hr_recontract_recontract extends model_base {

	function __construct() {
		$this->tbl_name = "hr_recontract";
		$this->sql_map = "hr/recontract/recontractSql.php";
		parent :: __construct();
		$this -> filebase = "attachment/recontract/";//�ϴ�����
		$this->HrManager=array('yu.long','xiujie.zeng','shuyin.lin');
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		try{  
			$this->start_d();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['conTypeName'] = $datadictDao->getDataNameByCode (trim($object['conType']));
			$object['conStateName'] = $datadictDao->getDataNameByCode (trim($object['conState']));
			$object['conNumName'] = $datadictDao->getDataNameByCode (trim($object['conNum']));

			//�޸�������Ϣ
			parent::edit_d($object,true);

			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}
	function buildBaseDate(){
		
		$sqlchk="SELECT id FROM `hr_recontract` WHERE  DATE_FORMAT(NOW() ,'%Y-%m')=DATE_FORMAT(recordDate,'%Y-%m')";
		$objectCHK=$this->_db->getArray($sqlchk);
		if(!$objectCHK&&!is_array($objectCHK)){
			$sql="SELECT a.USER_ID as userAccount,a.USER_NAME as userName,b.UserCard as userNo,
				       b.COME_DATE as comeinDate,b.ContFlagB as obeginDate,b.ContFlagE as ocloseDate,
				       c.DEPT_ID as deptId,c.levelflag,c.DEPT_NAME as deptName,d.NameCN as companyName,
				       d.NamePT as companyId,e.id as jobId,e.`name`  as jobName,b.expflag,
				       DATE_FORMAT(b.ContFlagE,'%Y')-DATE_FORMAT(b.ContFlagB,'%Y') as oconNum,f.conNum as oconNums,f.conNumName as oconNumsName    
				 FROM  `user` a LEFT JOIN  hrms  b ON  a.USER_ID=b.USER_ID
				       LEFT JOIN department c ON a.DEPT_ID=c.DEPT_ID
				       LEFT JOIN  branch_info  d ON  a.Company=d.NamePT
				       LEFT JOIN  user_jobs   e  ON   a.jobs_id=e.id
					   LEFT JOIN  oa_hr_personnel_contract f ON a.USER_ID=f.userAccount AND conState='HRHTZT-YX' AND f.conType='HRHTLX-05'
				 WHERE   1=1 AND a.USER_ID=b.USER_ID AND a.HAS_LEFT=0 AND a.DEL=0 AND a.userType=1  
				/*AND DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-".RecntractDay." DAY)=DATE_FORMAT(NOW(),'%Y-%m-%d')*/  
				AND  DATE_FORMAT(DATE_ADD(NOW(),interval (IF(a.Company='sy',2,2)) MONTH) ,'%Y-%m')=DATE_FORMAT(b.ContFlagE,'%Y-%m')  ORDER BY a.DEPT_ID ";
		$object=$this->_db->getArray($sql);
		if($object){
			$gls = new includes_class_global();
		    $semails = new includes_class_sendmail();
			$datadictDao = new model_system_datadict_datadict ();
			$deptLeav = new  model_deptuser_dept_dept();
			foreach($object as $key =>$val){
				$vals=array();
				if($val['expflag']==0){
					$val['oconState']='EMETH-01';
				}elseif($val['expflag']==1){
					$val['oconState']='EMETH-02';
				}elseif($val['expflag']==2){
					$val['oconState']='EMETH-03';
				}
				$vals=$deptLeav->getSuperiorDeptById_d($val['deptId'],$val['levelflag']);
				$vals['deptNameB']=$vals['deptName'];
	            $vals['deptIdB']=$vals['deptId'];
	             unset($vals['deptName']);
	             unset($vals['deptId']);
		        $val=array_merge($val,$vals);
				$val['conType']='HRHTLX-05';
				$val['recorderId']=$_SESSION['USER_ID'];
				$val['recorderId']=$_SESSION['USERNAME'];
				$val['recordDate']=date('Y-m-d');
				$val['conTypeName'] = $datadictDao->getDataNameByCode (trim($val['conType']));
				$val['oconStateName'] = $datadictDao->getDataNameByCode (trim($val['oconState']));
				$val['oconNumName'] = $datadictDao->getDataNameByCode (trim($val['oconNum']));
			    $id=parent::add_d($val,true);
			    $mailBody.=$val['deptName'].' '.$val['userName'].' '.$val['jobName'].'<br/>';
			
			}
			
			$Address = $gls->get_email($this->HrManager);
			$body='<p>��ã�<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mailBody.'���Ͷ���ͬ�������µ��ڣ����¼OA����<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�麣���Ͷ���ͨ�ſƼ��ɷ����޹�˾<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.date('Y').'&nbsp;��&nbsp; '.date('m').'&nbsp; ��&nbsp; '.date('d').'&nbsp; ��<br />
&nbsp;</p>';
			$body.='<div>���½OA����</span></div><div>&nbsp;</div> <hr /><p><br />
					<font size="2">������ַ��<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br />
						<br />
				    ������ַ��<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></p>';
			$flag = $semails->send('�����µ��Ͷ���ͬ��ǩ����',$body , $Address);
			
		}
			
		}
		
		
		
	}
	/**
	 * ��д��������
	 */
		function add_d($object){
		try{
			$this->start_d();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$deptLeav = new  model_deptuser_dept_dept();
			$object['conTypeName'] = $datadictDao->getDataNameByCode (trim($object['conType']));
			$object['oconStateName'] = $datadictDao->getDataNameByCode (trim($object['oconState']));
			$object['oconNumName'] = $datadictDao->getDataNameByCode (trim($object['oconNum']));
			$objDept=$this->_db->getArray('SELECT levelflag from department where DEPT_ID ="'.$object['deptId'].'"');
            $vals=$deptLeav->getSuperiorDeptById_d($object['deptId'],$objDept[0]['levelflag']);
            $vals['deptNameB']=$vals['deptName'];
            $vals['deptIdB']=$vals['deptId'];
             unset($vals['deptName']);
             unset($vals['deptId']);
		    $object=array_merge($object,$vals);
			//�޸�������Ϣ
			$id=parent::add_d($object,true);
            //$this->sendMails($id);
			$this->commit_d();
            $this->rollBack();
			return $id;
		}catch(exception $e){
			return false;
		}
	}
	
     /**
	 * ��ȡ�ɹ�������������ϸ��Ϣ
	 */
	function getApprovealInfo($pid){
		if($pid){
			$sql="SELECT  a.statusId,a.userName,a.deptName,a.deptId,a.jobName,a.jobId,a.companyId,a.comeinDate,a.oconNumsName,a.obeginDate,a.ocloseDate,b.*
							FROM `hr_recontract` a LEFT JOIN hr_recontract_approval b ON  (a.id=b.recontractId)
							WHERE  b.recontractId ='$pid' and b.mark<>'ST' ORDER BY b.id DESC  LIMIT 1 ";
			$object=$this->_db->getArray($sql);
		}
		return $object;
		
		}
		     /**
	 * ��ȡ�ɹ�������������ϸ��Ϣ
	 */
	function getApprovealSTInfo($pid){
		if($pid){
			$sql="SELECT  a.statusId,a.userName,a.deptName,a.deptId,a.jobName,a.jobId,a.companyId,a.comeinDate,a.oconNumsName,a.obeginDate,a.ocloseDate,b.*
							FROM `hr_recontract` a LEFT JOIN hr_recontract_approval b ON  (a.id=b.recontractId)
							WHERE  b.recontractId ='$pid' and b.mark='ST' ORDER BY b.id DESC  LIMIT 1 ";
			$object=$this->_db->getArray($sql);
		}
		return $object;
		
		}
		
	 /**
	 * ��ȡ�ɹ�������������ϸ��Ϣ
	 */
	function getApprovealHR2STInfo($pid){
		if($pid){
			$sql="SELECT  a.statusId,a.userName,a.deptName,a.deptId,a.jobName,a.jobId,a.companyId,a.comeinDate,a.oconNumsName,a.obeginDate,a.ocloseDate,b.*
							FROM `hr_recontract` a LEFT JOIN hr_recontract_approval b ON  (a.id=b.recontractId)
							WHERE  b.recontractId ='$pid'  ORDER BY b.id ASC  LIMIT 1 ";
			$object=$this->_db->getArray($sql);
		}
		return $object;
		
		}	
	function getDifferent($pid){
		
		$flag=1;
		if($pid){
			$sql="  SELECT  a.isFlag,a.conState,a.conNum
					FROM hr_recontract_approval a LEFT JOIN hr_recontract b ON (a.recontractId=b.id) 
					WHERE  a.recontractId ='$pid'  AND b.statusId NOT IN (7,8,9) 
					AND  (SELECT COUNT(id) FROM hr_recontract_approval WHERE  recontractId='$pid' )>2 
					ORDER BY a.id DESC  LIMIT 2";
			$object=$this->_db->getArray($sql);
			if($object&&is_array($object)){
				if($object[0]['isFlag']!=$object[1]['isFlag']){
					$flag=2;
				}
				if($object[0]['conState']!=$object[1]['conState']){
					$flag=2;
				}
				if($object[0]['conNum']!=$object[1]['conNum']){
					$flag=2;
				}
			}
			
		}
		return $flag;
	
		
		
	}
	/**
	 * ��ȡ�ɹ�������������ϸ��Ϣ
	 */
	function getRecontractInfo($pid){
		if($pid){
			$sql="SELECT  a.*
							FROM `hr_recontract`  a
							WHERE  a.id ='$pid' ";
			$object=$this->_db->getArray($sql);
		}
		return $object;
	}
	/**
	 * ��ȡ�ɹ�������������ϸ��Ϣ
	 */
	function getApprovealInfoTop($pid){
		if($pid){
			$sql="SELECT  a.statusId,a.deptName,a.deptId,a.jobName,a.companyId,a.jobId,a.comeinDate,a.oconNumsName,a.obeginDate,a.ocloseDate,b.*
							FROM `hr_recontract` a LEFT JOIN hr_recontract_approval b ON  (a.id=b.recontractId)
							WHERE  b.recontractId ='$pid'  ORDER BY b.id ASC  LIMIT 1 ";
			$object=$this->_db->getArray($sql);
		}
		return $object;
	}
	function  sendMails($pid){
		$gls = new includes_class_global();
		$semails = new includes_class_sendmail();
		if($pid){
			$obj=$this->getRecontractInfo($pid);
			$Address = $gls->get_email($obj[0]['userAccount']);
			$Address1 = $gls->get_email($this->HrManager);
			$body='<p>��ã�<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$obj[0]['userName'].'�Ͷ���ͬ����'.$obj[0]['ocloseDate'].' �յ��ڣ����¼OA����<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�麣���Ͷ���ͨ�ſƼ��ɷ����޹�˾<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.date('Y').'&nbsp;��&nbsp; '.date('m').'&nbsp; ��&nbsp; '.date('d').'&nbsp; ��<br />
&nbsp;</p>';
			$body.='<div>���½OA����</span></div><div>&nbsp;</div> <hr /><p><br />
					<font size="2">������ַ��<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br />
						<br />
				    ������ַ��<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></p>';
			$flag = $semails->send('�����µ��Ͷ���ͬ��ǩ֪ͨ',$body , $Address);
				$semails->send('�����µ��Ͷ���ͬ��ǩ֪ͨ',$body , $Address1);
	
	
			
		}
		
		
		
		
	}
	
	 /* �б�ע��������
     */
    function listremarkAdd_d($object) {
		try {
			$this->start_d();

			$dao = new model__hr_recontract_recontractRemark();
			$newId = $dao->add_d($object);
            $this->commit_d();
			//$this->rollBack();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ��ȡ��ע����
	 */
	function getRemarkInfo_d($contractId){
		$sql = "select * from hr_recontract_remark where cId=".$contractId."";
        $arr = $this->_db->getArray($sql);
        //��������
       if($arr&&is_array($arr)){
         foreach($arr as $k=>$v){
           $arrHTML .= "<b>".$v['createName']."</b>(".$v['createTime'].") : ".$v['content']."<br/>";
         }	
       } 
       
        return $arrHTML;
	}

	/**
	 *��ȡ�б�ע��Ϣ�ĺ�ͬ ����
	 */
	function getRemarks(){
         $sql = "SELECT cId FROM `hr_recontract_remark` WHERE cId IS NOT NULL  GROUP BY cId ";
         $arr = $this->_db->getArray($sql);
         foreach((array)$arr as $k=>$v){
         	$arr[$v['cId']] =1;
         }
         return $arr;
	}
	
	function model_importTplExContent(){
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$msg = 0;
		$msg = $this -> model_recontract_upfile ();
		if ( $msg == 1)
		{
			$msg = $this -> model_recontract_importdata ( $_FILES[ 'upfile' ][ 'name' ]);
			
		}
		return $msg;
	}
	
	/*
	 * �ϴ��ļ�
	 * @param  $_FILES
	 */
	function model_recontract_upfile ()
	{
		$msg = 0;
		if ( $_FILES )
		{
			$tempname = $_FILES[ 'upfile' ][ 'tmp_name' ];
			$filename = $_FILES[ 'upfile' ][ 'name' ];
			$file_type = end ( explode ( "." , trim ( $filename ) ) );
			if ( file_exists ( $this -> filebase ) == "" )
			{
				@mkdir ( $this -> filebase , 511 );
			}
			if ( in_array ( strtolower ( $file_type ) , array ( 
																"xls" 
			) ) )
			{
				if ( move_uploaded_file ( str_replace ( '\\\\' , '\\' , $tempname ) , WEB_TOR . $this -> filebase . $filename ) )
				{
					$msg = 1;
				
				} else
				{
					$msg = 2;
				}
			} else
			{
				$msg = 3;
			}
		}
		return $msg;
	}
	/**
	 * ��ȡEXCEL�ļ�
	 * @param $filename
	 */
	function model_recontract_getexcel ( $filename , $sheet = '' )
	{
		if ( file_exists ( $filename ) )
		{
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';
			$PHPExcel = new PHPExcel ( );
			$PHPReader = new PHPExcel_Reader_Excel2007 ( $PHPExcel );
			if (  ! $PHPReader -> canRead ( $filename ) )
			{
				$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			}
			if ( $PHPReader -> canRead ( $filename ) )
			{
				$Excel = $PHPReader -> load ( $filename );
				$data = array ();
				if ( $sheet || $sheet == 0 )
				{
					$data[ $sheet ] = $Excel -> getSheet ( $sheet ) -> toArray ( );
					$data[ $sheet ][ 'title' ] = $Excel -> getSheet ( $sheet ) -> getTitle ( );
					unset ( $Excel , $PHPReader , $PHPExcel );
					return $data;
				}
				$countnum = $Excel -> getSheetCount ( );
				for ( $i = 0 ; $i < $countnum ; $i ++  )
				{
					$data[ $i ] = $Excel -> getSheet ( $i ) -> toArray ( );
					$data[ $i ][ 'title' ] = $Excel -> getSheet ( $i ) -> getTitle ( );
				
				}
				return $data;
			} else
			{
				return false;
			}
		} else
		{
			return false;
		}
	}
	/**
	 * �ϴ��ļ�
	 * @param  $_FILES
	 */
	function model_recontract_importdata ( $filename )
	{
		try
		{
			$this -> _db -> query ( "START TRANSACTION" );
			$this -> tbl_name = 'consume_info';
			if ( $filename && file_exists ( $this -> filebase . $filename ) )
			{
				$Excel = mb_iconv ( $this -> model_recontract_getexcel ( $this -> filebase . $filename , 0 ) );
				
				if ( $Excel[ 0 ]&&is_array($Excel[ 0 ]) )
				{
					
					foreach ( $Excel[ 0 ] as $key => $vI )
					{
						if ( $key > 3 )
						{
							if ( is_array ( $vI ) && ( $vI[ '0' ]))
							{
									 if($vI[9]=='δ����')
									{
									  $statusId= 1;
									}else if($vI[9]=='���ύ')
									{
										$statusName= 2;
									}else if($vI[9]=='������')
									{
										$statusName= 3;
									}else if($vI[9]=='��֪ͨԱ��')
									{
										$statusName=4;
									}else if($vI[9]=='��Ա��ȷ��')
									{
										$statusName= 5;
									}else if($vI[9]=='��HRȷ��')
									{
										$statusName=6;
									}else if($vI[9]=='��ǩ��ֽ�ʺ�ͬ')
									{
										$statusName=7;
									}else if($vI[9]=='��ͬ���')
									{
										$statusName= 8;
									}else if($vI[9]=='��ͬ�ر�')
									{
										$statusName=9;
									}
									if($vI[10]=='��ǩ'){
										$paperContract=2;
									}else{
										$paperContract=1;
									}	
							   $this -> tbl_name = 'oa_hr_personnel';
							   $userInfoI=$this->find(array('userNo'=>$vI[ '0' ]));
							   $ExcelData[] = array (   'userNo'=>$vI[0],'userName'=>$userInfoI['userName'],
							                            'userAccount'=>$userInfoI['userAccount'],'userNo'=>$userInfoI['userNo'],
													    'companyName'=>$userInfoI['companyName'],'deptNameS'=>$userInfoI['deptNameS'],
													    'deptIdS'=>$userInfoI['deptIdS'],'deptIdT'=>$userInfoI['deptIdT'],
									    				'deptNameT'=>$userInfoI['deptNameT'],'deptName'=>$userInfoI['deptName'],
									    				'deptId'=>$userInfoI['deptId'],'jobId'=>$userInfoI['jobId'],
														'jobName'=>$vI['jobName'],'comeinDate'=>$vI[8],
													    'statusId'=>$statusName,'isPaperContract'=>$paperContract,
								                        'obeginDate'=>$vI[11],'ocloseDate'=>$vI[12],
														'oconNumName'=>$vI['oconNumName'],'oconStateName'=>$vI['oconStateName'],
														'aisFlagName'=>$vI['aisFlagName'],'aconStateName'=>$vI['aconStateName'],
														'aconNumName'=>$vI['aconNumName'],'pisFlagName'=>$vI['pisFlagName'],
														'pconNumName'=>$vI['pconNumName'],'pconStateName'=>$vI['pconStateName'],
														'isFlagName'=>$vI['isFlagName'],'beginDate'=>$vI['beginDate'],
														'closeDate'=>$vI['closeDate'],'conNumName'=>$vI['conNumName'],
														'conStateName'=>$vI['conStateName'],'signCompanyName'=>$vI['signCompanyName']);
		
							    	
								 
							}
						}
					}
					if($ExcelData&&is_array($ExcelData)){
						 	$this -> tbl_name = 'hr_recontract';
							foreach($ExcelData as $key =>$val){
								if($val&&is_array($val)&&$val['projectId']){
									$this -> create ($val);
								}
							} 
					}
					$msg = 2;
				} else
				{
					$msg = 5;
				}
			} else
			{
				$msg = 6;
			}
			$this -> _db -> query ( "COMMIT" );
			return $msg;
		} catch ( Exception $e )
		{
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		}
	
	}
	
}
?>