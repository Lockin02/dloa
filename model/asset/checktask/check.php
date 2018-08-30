<?php


/**
 * 导入盘点任务model层类
 *  @author chenzb
 */
class model_asset_checktask_check extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_check";
		$this->sql_map = "asset/checktask/checkSql.php";
		parent :: __construct();
	}


	/** 根据读取EXCEL中的信息导入到系统中
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

                /*s:1.保存主表基本信息*/
				//$codeDao = new model_common_codeRule ();
				$id = parent::add_d ( $object, true );
				/*e:1.保存主表基本信息*/
				set_time_limit ( 0 );	//执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
				ini_set('memory_limit', '1024M');	//设置内存
	            $addArr = array();//从表数组
				foreach ( $excelData as $key => $obj ) {
					if( !empty($obj[0])){
						//使用人ID
						$userName = trim($obj[3]);
						$sql = "select USER_ID from user where USER_NAME = '$userName'";
						$belongNames = $this->_db->getArray($sql);
						$userId = $belongNames[0]['USER_ID'];
						
						//使用部门ID
						$useDept = str_replace(' ','',$obj[4]);
						$sql = "select DEPT_ID from department where DEPT_NAME ='$useDept'";
						$belongDepts =  $this->_db->getArray($sql);
						$useDeptId = $belongDepts[0]['DEPT_ID'];
						
						//所属人ID
		            	$belongName = trim($obj[8]);
					    $sql = "select USER_ID from user where USER_NAME = '$belongName'";
		                $belongNames = $this->_db->getArray($sql);		
	                    $belongId = $belongNames[0]['USER_ID'];
	                   		
	                    //所属部门ID
					    $belongDept = str_replace(' ','',$obj[9]);
					    $sql = "select DEPT_ID from department where DEPT_NAME ='$belongDept'";
		                $belongDepts =  $this->_db->getArray($sql);
	                    $deptId = $belongDepts[0]['DEPT_ID'];
		
	                    //所属区域ID
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
			    /*s:2.保存从表信息*/
				$checkitemDao = new model_asset_checktask_checkitem ();
				$itemsObj = $checkitemDao->addBatch_d($addArr);
				/*e:2.保存从表信息*/

			   $this->commit_d ();
			   return $id;
		    } else {
				echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove();</script>";
		    }
		  } catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
}