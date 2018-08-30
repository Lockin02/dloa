<?php
include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";
/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息 Model层
 */
class model_hr_contract_contract extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_contract";
		$this->sql_map = "hr/contract/contractSql.php";
		parent :: __construct();
	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($object){
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['conTypeName'] = $datadictDao->getDataNameByCode ( $object['conType'] );
			$object['conStateName'] = $datadictDao->getDataNameByCode ( $object['conState'] );
			$object['conNumName'] = $datadictDao->getDataNameByCode ( $object['conNum'] );

			//修改主表信息
			parent::edit_d($object ,true);
			//更新附件关联关系
			$this->updateObjWithFile($object['id'] ,$object['conNo']);

			//附件处理
			if(isset($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])){
				$uploadFile = new model_file_uploadfile_management();
				$uploadFile->updateFileAndObj($_POST['fileuploadIds'],$object['id'],$object['conNo']);
			}
			if($object['entryId'] > 0) {
				$entryNoticeDao = new model_hr_recruitment_entryNotice();
				$entryNoticeDao->updateField("id=".$object['entryId'],'contractState',1);
			}

			//更新旧系统的档案合同信息
			$userNo = $this->get_table_fields('hrms', "UserCard='".$object['userNo']."'", 'UserCard');
			$sql = '';
			if($userNo&&$object['conStateName']=='有效'&&($object['conTypeName']=='劳动合同'||$object['conTypeName']=='实习协议'||$object['conTypeName']=='聘用协议')){
				switch($object['conNumName']){
					case '第一次与公司签':$ContractState='1';break;
					case '连续第二次与公司签':$ContractState='3';break;
					case '连续第三次与公司签':$ContractState='10';break;
					case '第一次与众由公司签':$ContractState='2';break;
					case '连续第二次与众由公司签':$ContractState='4';break;
					case '与公司签无固定期限':$ContractState='5';break;
					case '与广东众由公司签无固定期限':$ContractState='6';break;
					case '聘用协议':$ContractState='7';break;
					case '实习协议':$ContractState='8';break;
					case '其他':$ContractState='9';break;
					default :$ContractState='';break;
				}
				$sql = "update  hrms set ContractState='".$ContractState."' , ContFlagB='".$object["beginDate"]."',ContFlagE='".$object["closeDate"]."'
					where usercard='".$object["userNo"]."'";
				$this->query($sql);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写新增方法
	 */
	function add_d($object){
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['conTypeName'] = $datadictDao->getDataNameByCode ( $object['conType'] );
			$object['conStateName'] = $datadictDao->getDataNameByCode ( $object['conState'] );
			$object['conNumName'] = $datadictDao->getDataNameByCode ( $object['conNum'] );

			//修改主表信息
			$id = parent::add_d($object ,true);

			//更新附件关联关系
			$this->updateObjWithFile($id,$object['conNo']);

			//附件处理
			if(isset($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])){
				$uploadFile = new model_file_uploadfile_management();
				$uploadFile->updateFileAndObj($_POST['fileuploadIds'],$id,$object['conNo']);
			}
			if($object['entryId'] > 0){
				$entryNoticeDao=new model_hr_recruitment_entryNotice();
				$entryNoticeDao->updateField("id=".$object['entryId'],'contractState',1);
			}

			//更新旧系统的档案合同信息
			$userNo=$this->get_table_fields('hrms', "UserCard='".$object['userNo']."'", 'UserCard');
			$sql = '';
			if($userNo && $object['conStateName'] == '有效'
					&& ($object['conTypeName'] == '劳动合同' || $object['conTypeName'] == '实习协议' || $object['conTypeName'] == '聘用协议')) {
				switch($object['conNumName']){
					case '第一次与公司签':$ContractState='1';break;
					case '连续第二次与公司签':$ContractState='3';break;
					case '连续第三次与公司签':$ContractState='10';break;
					case '第一次与众由公司签':$ContractState='2';break;
					case '连续第二次与众由公司签':$ContractState='4';break;
					case '与公司签无固定期限':$ContractState='5';break;
					case '与广东众由公司签无固定期限':$ContractState='6';break;
					case '聘用协议':$ContractState='7';break;
					case '实习协议':$ContractState='8';break;
					case '其他':$ContractState='9';break;
					default :$ContractState='';break;
				}
				$sql = "update  hrms set ContractState='".$ContractState."' , ContFlagB='".$object["beginDate"]."',ContFlagE='".$object["closeDate"]."'
					where usercard='".$object["userNo"]."'";
				$this->query($sql);
			}

			$this->commit_d();
			return $id;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据用户账号获取信息
	 */
	function getInfoByUserNo_d($userNoArr){
		$this->searchArr = array ('userNoArr' => $userNoArr );
		$this->__SET('sort', 'c.userNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
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
			$tempArr['docCode'] = '第'. $actNum .'行数据';
			$tempArr['result'] = '更新失败!不存在的员工编号';
			return $tempArr;
		} else {
			$row['userAccount'] = $rs['USER_ID'];
			$row['jobName'] = $rs['jobName'];
			$row['jobId'] = $rs['jobId'];
			//数据字典处理
			$datadictDao = new model_system_datadict_datadict();
			$conType = $datadictDao->getCodeByName('HRHTLX',$row['conTypeName']);
			$conState = $datadictDao->getCodeByName('HRHTZT',$row['conStateName']);
			$conNum = $datadictDao->getCodeByName('HRHTCS',$row['conNumName']);
			if(empty($conType)){
				$tempArr['docCode'] = '第'. $actNum .'行数据';
				$tempArr['result'] = '更新失败!不存在的合同类型！';
				return $tempArr;
			}else{
				if(empty($conState)){
					$tempArr['docCode'] = '第'. $actNum .'行数据';
					$tempArr['result'] = '更新失败!不存在的合同状态！';
					return $tempArr;
				}else{
					if(empty($conNum)){
						$tempArr['docCode'] = '第'. $actNum .'行数据';
						$tempArr['result'] = '更新失败!不存在的合同次数！';
						return $tempArr;
					}else{
						$row['conType'] = $conType;
						$row['conState'] = $conState;
						$row['conNum'] = $conNum;
						$row['recorderName'] = $_SESSION['USERNAME'];
						$row['recorderId'] = $_SESSION['USER_ID'];
						$row['recordDate'] = date("Y-m-d");
						//处理时间
						$row["beginDate"] = date('Y-m-d',strtotime (trim($row["beginDate"])));
						$row["closeDate"] = date('Y-m-d',strtotime (trim($row["closeDate"])));
						$row["trialBeginDate"] = date('Y-m-d',strtotime (trim($row["trialBeginDate"])));
						$row["trialEndDate"] = date('Y-m-d',strtotime (trim($row["trialEndDate"])));;
						$newId = $this->add_d($row ,true);
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
		}
	}

	/**
	 * 导入更新
	 */
	function upDateExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear($filename ,$temp_name ,3);
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				//先对数组进行转换
				$transData = array();
				foreach ($excelData as $key => $val) {
					$transData[$key]['userNo'] = $val[0];
					$transData[$key]['userName'] = $val[1];
					$transData[$key]['conNo'] = $val[2];
					$transData[$key]['conName'] = $val[3];
					$transData[$key]['conTypeName'] = $val[4];
					$transData[$key]['conStateName'] = $val[5];
					$transData[$key]['beginDate'] = $val[6];
					$transData[$key]['closeDate'] = $val[7];
					$transData[$key]['trialBeginDate'] = $val[8];
					$transData[$key]['trialEndDate'] = $val[9];
					$transData[$key]['conNumName'] = $val[10];
					$transData[$key]['conContent'] = $val[11];
				}

				//行数组循环
				foreach($transData as $key => $val){
					$actNum = $key + 1;
					if(empty($val['userNo'])) {
						continue;
					} else {
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val['userNo']) && trim($val['userNo']) != '') {
							$inArr['userNo'] = trim($val['userNo']);
							$rs = $otherDataDao->getUserInfoByUserNo($inArr['userNo']);
							if (empty($rs)) {
								$tempArr['docCode'] = '第'. $actNum .'行数据';
								$tempArr['result'] = '<font color=red>更新失败!员工编号不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第'. $actNum .'行数据';
							$tempArr['result'] = '<font color=red>更新失败!员工编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//员工姓名
						if(!empty($val['userName']) && trim($val['userName']) != '') {
							//暂不处理
						}

						//合同编号
						if(!empty($val['conNo']) && trim($val['conNo']) != '') {
							$inArr['conNo'] = trim($val['conNo']);
						}

						//合同名称
						if(!empty($val['conName']) && trim($val['conName']) != '') {
							$inArr['conName'] = trim($val['conName']);
						}

						//合同类型
						if(!empty($val['conTypeName']) && trim($val['conTypeName']) != '') {
							$inArr['conTypeName'] = trim($val['conTypeName']);
							$conType = $datadictDao->getCodeByName('HRHTLX' ,$inArr['conTypeName']);
							if(empty($conType)) {
								$tempArr['docCode'] = '第'. $actNum .'行数据';
								$tempArr['result'] = '<font color=red>更新失败!不存在的合同类型</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['conType'] = $conType;
							}
						} else {
							$tempArr['docCode'] = '第'. $actNum .'行数据';
							$tempArr['result'] = '<font color=red>更新失败!合同类型为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//合同状态
						if(!empty($val['conStateName']) && trim($val['conStateName']) != '') {
							$inArr['conStateName'] = trim($val['conStateName']);
							$conState = $datadictDao->getCodeByName('HRHTZT' ,$inArr['conStateName']);
							if(empty($conState)) {
								$tempArr['docCode'] = '第'. $actNum .'行数据';
								$tempArr['result'] = '<font color=red>更新失败!不存在的合同状态</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['conState'] = $conState;
							}
						} else {
							$tempArr['docCode'] = '第'. $actNum .'行数据';
							$tempArr['result'] = '<font color=red>更新失败!合同状态为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//合同开始时间
						if(!empty($val['beginDate']) && trim($val['beginDate']) != '') {
							$val['beginDate'] = trim($val['beginDate']);
							if(!is_numeric($val['beginDate'])) {
								$inArr['beginDate'] = $val['beginDate'];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['beginDate'] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['beginDate']));
									$inArr['beginDate'] = $tmpDate;
								} else {
									$inArr['beginDate'] = $beginDate;
								}
							}
						}

						//合同结束时间
						if(!empty($val['closeDate']) && trim($val['closeDate']) != '') {
							$val['closeDate'] = trim($val['closeDate']);
							if(!is_numeric($val['closeDate'])) {
								$inArr['closeDate'] = $val['closeDate'];
							} else {
								$closeDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['closeDate'] - 1 ,1900)));
								if($closeDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['closeDate']));
									$inArr['closeDate'] = $tmpDate;
								} else {
									$inArr['closeDate'] = $closeDate;
								}
							}
						}

						//合同开始时间和结束时间不能同时为空
						if (empty($inArr['beginDate']) && empty($inArr['closeDate'])) {
							$tempArr['docCode'] = '第'. $actNum .'行数据';
							$tempArr['result'] = '<font color=red>更新失败!合同开始时间和结束时间不能同时为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//试用开始时间
						if(!empty($val['trialBeginDate']) && $val['trialBeginDate'] != '0000-00-00' && trim($val['trialBeginDate']) != '') {
							$val['trialBeginDate'] = trim($val['trialBeginDate']);
							if(!is_numeric($val['trialBeginDate'])) {
								$inArr['trialBeginDate'] = $val['trialBeginDate'];
							} else {
								$trialBeginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['trialBeginDate'] - 1 ,1900)));
								if($trialBeginDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['trialBeginDate']));
									$inArr['trialBeginDate'] = $tmpDate;
								} else {
									$inArr['trialBeginDate'] = $trialBeginDate;
								}
							}
						}

						//试用结束时间
						if(!empty($val['trialEndDate']) && $val['trialEndDate'] != '0000-00-00' && trim($val['trialEndDate']) != '') {
							$val['trialEndDate'] = trim($val['trialEndDate']);
							if(!is_numeric($val['trialEndDate'])) {
								$inArr['trialEndDate'] = $val['trialEndDate'];
							} else {
								$trialEndDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['trialEndDate'] - 1 ,1900)));
								if($trialEndDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['trialEndDate']));
									$inArr['trialEndDate'] = $tmpDate;
								} else {
									$inArr['trialEndDate'] = $trialEndDate;
								}
							}
						}

						//合同次数
						if(!empty($val['conNumName']) && trim($val['conNumName']) != '') {
							$inArr['conNumName'] = trim($val['conNumName']);
							$conNum = $datadictDao->getCodeByName('HRHTCS' ,$inArr['conNumName']);
							if(empty($conNum)) {
								$tempArr['docCode'] = '第'. $actNum .'行数据';
								$tempArr['result'] = '<font color=red>更新失败!不存在的合同次数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['conNum'] = $conNum;
							}
						}

						//合同内容
						if(!empty($val['conContent']) && trim($val['conContent']) != '') {
							$inArr['conContent'] = trim($val['conContent']);
						}

						//查找合同
						$condition = array(
							'userNo' => $inArr['userNo']
							,'conType' => $inArr['conType']
						);
						//合同日期
						if (!empty($inArr['beginDate'])) {
							$condition['beginDate'] = $inArr['beginDate'];
						}
						if (!empty($inArr['closeDate'])) {
							$condition['closeDate'] = $inArr['closeDate'];
						}

						$obj = $this->find($condition);
						if (empty($obj)) {
							$tempArr['docCode'] = '第'. $actNum .'行数据';
							$tempArr['result'] = '<font color=red>更新失败!不存在的合同</font>';
							array_push($resultArr ,$tempArr);
							continue;
						} else {
							$inArr['id'] = $obj['id'];
						}

						$id = parent::edit_d($inArr ,true);
						if($id) {
							$tempArr['result'] = '导入更新成功';
						} else {
							$tempArr['result'] = '<font color=red>导入更新失败</font>';
						}
						$tempArr['docCode'] = '第'. $actNum .'行数据';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

	/**
	 * 导出excel
	 */
	 function excelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-合同信息导入模版.xls" ); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '信息列表' ) );
		//设置表头及样式 设置
		$i = 3;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'J' . $i );
			for($m = 0; $m < 10; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
		}

		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "合同信息导出报表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/******************* E 导入导出系列 ************************/

	/**
	 * 根据员工帐号及合同类型获取合同信息
	 * zengzx 2012.12.01
	 */
	function getConInfoByUserId($userId,$contType){
		$this->searchArr = array (
			'userAccount' => $userId ,
			'conTypeArr' => $contType,
			'conState' => 'HRHTZT-YX'
		);
		$this->__SET('sort', 'c.id');
		$this->__SET('asc', true);
		$rows= $this->listBySqlId ();
		return $rows;
	}
}
?>