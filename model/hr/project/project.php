<?php

/**
 * @author Administrator
 * @Date 2012-05-30 19:25:55
 * @version 1.0
 * @description:��Ŀ���� Model��
 */
class model_hr_project_project extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_project";
		$this->sql_map = "hr/project/projectSql.php";
		parent :: __construct();
	}


	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d($objNameArr){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
			if(is_array($excelData)){
                $objectArr = array ();
                $resultArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				//ѭ�����������
				foreach($objectArr as $key => $val){
                     if(empty($val['userNo']) && empty($val['userName'])){
                     	 unset($objectArr[$key]);
                     }
				}
				$actNum = 3;
                //ѭ������
                foreach($objectArr as $key => $val){
                    //������������
                    $tempArr = $this->disposeData($val,$actNum);
                  array_push( $resultArr,$tempArr );
                  $actNum += 1;
                }

				return $resultArr;
			}
		}
	}
	//������������
	function disposeData($row,$actNum){
		 $addArr=array();
          //��ȡ�˺���Ϣ
          $otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
          $rs = $otherDataDao->getUserInfoByUserNo($row['userNo']);
			if(empty($rs)){
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '����ʧ��!�����ڵ�Ա�����';
				 return $tempArr;
			}else{
				$row['userAccount'] = $rs['USER_ID'];
				$row['deptName'] = $rs['DEPT_NAME'];
				$row['deptId'] = $rs['DEPT_ID'];
				$row['jobName'] = $rs['jobName'];
				$row['jobId'] = $rs['jobId'];
               //�ж���Ŀ����
               $ru = $otherDataDao->getUserID($row['projectManager']);
               if(empty($ru)){
               	 $tempArr['docCode'] = '��' . $actNum .'������';
				 $tempArr['result'] = '����ʧ��!��Ŀ�������ڣ�';
				 return $tempArr;
               }else{
               	   $row['projectManagerId']=$ru[0]['USER_ID'];
               	   //����ʱ��
                  $beginDate = trim($row["beginDate"]);
                  $closeDate = trim($row["closeDate"]);
				  $row["beginDate"] = date('Y-m-d',(mktime(0,0,0,1, $beginDate - 1 , 1900)));
				  $row["closeDate"] = date('Y-m-d',(mktime(0,0,0,1, $closeDate - 1 , 1900)));
               	   $newId = $this->add_d($row,true);
					if($newId){
						$tempArr['result'] = '�����ɹ�';
					}else{
						$tempArr['result'] = '����ʧ��';
					}
					$tempArr['docCode'] = '��' . $actNum .'������';
					return $tempArr;
               }
			}

	}
	/******************* E ���뵼��ϵ�� ************************/



/**
	 * �����û��˺Ż�ȡ��Ϣ
	 *
	 */
	 function getInfoByUserNo_d($userNoArr){
		$this->searchArr = array ('userNoArr' => $userNoArr );
		$this->__SET('sort', 'c.userNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }
}
?>