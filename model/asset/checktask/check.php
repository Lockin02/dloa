<?php


/**
 * �����̵�����model����
 *  @author chenzb
 */
class model_asset_checktask_check extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_check";
		$this->sql_map = "asset/checktask/checkSql.php";
		parent :: __construct();
	}


	/** ���ݶ�ȡEXCEL�е���Ϣ���뵽ϵͳ��
	 * @param $stockArr
	 * importStockInfo()--->importCheckInfo()
	 */
	 function importExcel($object) {
		try {
			$this->start_d ();
			$filename = $_FILES ["inputExcel"] ["name"];
			$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
			$fileType = $_FILES ["inputExcel"] ["type"];
			if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
				$dao = new model_asset_checktask_importCheckUtil ();
				$excelData = $dao->readExcelData ( $filename, $temp_name );
				spl_autoload_register('__autoload');

                /*s:1.�������������Ϣ*/
				//$codeDao = new model_common_codeRule ();
				$id = parent::add_d ( $object, true );
				/*e:1.�������������Ϣ*/
				set_time_limit ( 0 );	//ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
				ini_set('memory_limit', '1024M');	//�����ڴ�
	            $addArr = array();//�ӱ�����
				foreach ( $excelData as $key => $obj ) {
					if( !empty($obj[0])){
						//ʹ����ID
						$userName = trim($obj[3]);
						$sql = "select USER_ID from user where USER_NAME = '$userName'";
						$belongNames = $this->_db->getArray($sql);
						$userId = $belongNames[0]['USER_ID'];
						
						//ʹ�ò���ID
						$useDept = str_replace(' ','',$obj[4]);
						$sql = "select DEPT_ID from department where DEPT_NAME ='$useDept'";
						$belongDepts =  $this->_db->getArray($sql);
						$useDeptId = $belongDepts[0]['DEPT_ID'];
						
						//������ID
		            	$belongName = trim($obj[8]);
					    $sql = "select USER_ID from user where USER_NAME = '$belongName'";
		                $belongNames = $this->_db->getArray($sql);		
	                    $belongId = $belongNames[0]['USER_ID'];
	                   		
	                    //��������ID
					    $belongDept = str_replace(' ','',$obj[9]);
					    $sql = "select DEPT_ID from department where DEPT_NAME ='$belongDept'";
		                $belongDepts =  $this->_db->getArray($sql);
	                    $deptId = $belongDepts[0]['DEPT_ID'];
		
	                    //��������ID
		                $belongArea =trim($obj[10]);
					    $sql = "select id from oa_asset_agency where agencyName = '$belongArea'";
		                $belongAreas =  $this->_db->getArray($sql);
	                    $belongAreaId = $belongAreas[0]['id'];
	
                        $addObj=array(
					   		"checkId"=>$id,
                        	"taskId"=>$object['taskId'],
                        	"userId"=>$userId,
                        	"useDeptId"=>$useDeptId,
							"belongId"=>$belongId,
							"belongDeptId"=>$deptId,
							"belongAreaId"=>$belongAreaId,
							"assetCode"=>trim($obj[0]),
							"assetName"=>trim($obj[1]),
                        	"machineCode"=>trim($obj[2]),
                        	"userId"=>trim($obj[3]),
                        	"userName"=>trim($obj[4]),
							"brand"=>trim($obj[5]),
							"patten"=>trim($obj[6]),
                        	"deploy"=>trim($obj[7]),
							"belongName"=>trim($obj[8]),
							"belongDept"=>trim($obj[9]),
							"belongArea"=>trim($obj[10]),
                        	"wirteDate"=>trim($obj[11]),
                        	"origina"=>trim($obj[12]),
							"registNum"=>trim($obj[13]),
							"checkNum"=>trim($obj[14]),
							"overageNum"=>trim($obj[15]),
							"shortageNum"=>trim($obj[16]),
							"remark"=>$obj[17]
                        );
	                    array_push($addArr,$addObj);
	                }else{
	                	unset($addArr[$key]);
	            	}
	            }
			    /*s:2.����ӱ���Ϣ*/
				$checkitemDao = new model_asset_checktask_checkitem ();
				$itemsObj = $checkitemDao->addBatch_d($addArr);
				/*e:2.����ӱ���Ϣ*/

			   $this->commit_d ();
			   return $id;
		    } else {
				echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove();</script>";
		    }
		  } catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
}