<?php

/**
 * @author Administrator
 * @Date 2012-05-30 19:25:55
 * @version 1.0
 * @description:项目经历 Model层
 */
class model_hr_project_project extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_project";
		$this->sql_map = "hr/project/projectSql.php";
		parent :: __construct();
	}


	/******************* S 导入导出系列 ************************/
	function addExecelData_d($objNameArr){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		//判断导入类型是否为excel表
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
				//循环清掉空数组
				foreach($objectArr as $key => $val){
                     if(empty($val['userNo']) && empty($val['userName'])){
                     	 unset($objectArr[$key]);
                     }
				}
				$actNum = 3;
                //循环数据
                foreach($objectArr as $key => $val){
                    //处理并插入数据
                    $tempArr = $this->disposeData($val,$actNum);
                  array_push( $resultArr,$tempArr );
                  $actNum += 1;
                }

				return $resultArr;
			}
		}
	}
	//处理并插入数据
	function disposeData($row,$actNum){
		 $addArr=array();
          //获取账号信息
          $otherDataDao = new model_common_otherdatas();//其他信息查询
          $rs = $otherDataDao->getUserInfoByUserNo($row['userNo']);
			if(empty($rs)){
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '更新失败!不存在的员工编号';
				 return $tempArr;
			}else{
				$row['userAccount'] = $rs['USER_ID'];
				$row['deptName'] = $rs['DEPT_NAME'];
				$row['deptId'] = $rs['DEPT_ID'];
				$row['jobName'] = $rs['jobName'];
				$row['jobId'] = $rs['jobId'];
               //判断项目经理
               $ru = $otherDataDao->getUserID($row['projectManager']);
               if(empty($ru)){
               	 $tempArr['docCode'] = '第' . $actNum .'行数据';
				 $tempArr['result'] = '更新失败!项目经理不存在！';
				 return $tempArr;
               }else{
               	   $row['projectManagerId']=$ru[0]['USER_ID'];
               	   //处理时间
                  $beginDate = trim($row["beginDate"]);
                  $closeDate = trim($row["closeDate"]);
				  $row["beginDate"] = date('Y-m-d',(mktime(0,0,0,1, $beginDate - 1 , 1900)));
				  $row["closeDate"] = date('Y-m-d',(mktime(0,0,0,1, $closeDate - 1 , 1900)));
               	   $newId = $this->add_d($row,true);
					if($newId){
						$tempArr['result'] = '新增成功';
					}else{
						$tempArr['result'] = '新增失败';
					}
					$tempArr['docCode'] = '第' . $actNum .'条数据';
					return $tempArr;
               }
			}

	}
	/******************* E 导入导出系列 ************************/



/**
	 * 根据用户账号获取信息
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