<?php
/**
 * @author Show
 * @Date 2013年6月7日 星期五 11:24:39
 * @version 1.0
 * @description:仓存信息表 Model层
 */

class model_finance_otherfee_otherfee extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_otherfee";
		$this->sql_map = "finance/otherfee/otherfeeSql.php";
		parent :: __construct();
	}
	
	
	function importExcel($stockArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();//结果数组
			$addArr = array();//正确信息数组

				foreach ( $stockArr as $key => $obj ) {
				$course = $obj['subjectName'];
			//	$sql = "select id from customer where Name = '$course'"; //查找客户ID
			/*	$cus =  $this->_db->getArray($sql); //
				foreach($cus as $k => $v){
					$cusId = $v['id'];
				}  */ 
			    $debit = $obj['debit'];
				$linkSql = "select id from oa_finance_otherfee where subjectName = '$course'";
				$subjectName =  $this->_db->getArray($linkSql); //
				if(!empty($obj['subjectName']) && !empty($obj['debit']) && empty($subjectName) && !empty($obj['accountYear']) && !empty($obj['accountPeriod']) && !empty($obj['feeDeptName'])){
					//                      $addArr[$key]['subjectName'] = $obj['subjectName'];
					$addArr[$key]['accountYear'] = $obj['accountYear'];
					$addArr[$key]['accountPeriod'] = $obj['accountPeriod'];
					$addArr[$key]['summary'] = $obj['summary'];
					$addArr[$key]['subjectName'] = $obj['subjectName'];
					$addArr[$key]['debit'] = $obj['debit'];
					$addArr[$key]['chanceCode'] = $obj['chanceCode']; 
					$addArr[$key]['trialProjectCode'] = $obj['trialProjectCode'];
					$addArr[$key]['feeDeptName'] = $obj['feeDeptName'];
					$addArr[$key]['contractCode'] = $obj['contractCode'];
					$addArr[$key]['province'] = $obj['province'];
					
	
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "导入成功！" ) );
				}else if(empty($obj['accountYear'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "失败！会计年度为空" ) );
				}else if(empty($obj['accountPeriod'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "失败！会计期间为空" ) );
				}else if(empty($obj['subjectName'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "失败！科目名称为空" ) );
				}else if(empty($obj['debit'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "失败！借方金额为空" ) );
				}else if(empty($obj['feeDeptName'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "失败！费用归属部门为空" ) );
				}else if(!empty($subjectName)){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "失败！科目名称已存在" ) );
				}
			}
			$this->addBatch_d($addArr);
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

		/**
	 * 导入人力决算
	 * $budgetTypex 导入决算类型
	 */
	/* function eportExcel(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
			
		//项目缓存数组
		$projectCache = array();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );print_r($excelData);
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				//行数组循环
				foreach($excelData as $key => $val){
					$val[8] = str_replace( ' ','',$val[8]);
					$val[1] = str_replace( ' ','',$val[1]);
					$actNum = $key + 2;
					if(empty($val[8]) && empty($val[1])){
						continue;
					}else{
						$contractCode = $val[8];
						$service = $this->service;
						//项目编号
						if(!empty($contractCode)){
							$otherfeeDao = new model_finance_otherfee_otherfee();
							$otherfeeInfo = $otherfeeDao->find(array('contractCode' => $contractCode),null,'contractCode,id,chanceCode');
							
							if(empty($otherfeeInfo)){
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '更新失败!无此<项目编号>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$id = $otherfeeInfo['id'];
								$chanceCode = $otherfeeInfo['chanceCode'];
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '更新失败!<合同编号>不能为空';
							array_push( $resultArr,$tempArr );
							continue;
						}
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
		}
	}
 */

	/**
	 *  重新add
	 */
	/**function add_d($object){
	 *	//首次新增将初始数量赋予实际数量
	 *	$object['actNum'] = $object['initNum'];
	 *	return parent::add_d($object,true);
	}*/

	/**
	 * 重写edit
	 */
	/*function edit_d($object){
	 *	//首次新增将初始数量赋予实际数量
	 *	$object['actNum'] = $object['initNum'];
	 *	return parent::edit_d($object,true);
	}*/


	/*************************** 外部业务调用方法 *******************/
	/**
	 * 增加库存
	 */
	/**function addStockNum_d($id,$inNum){
	 *	$sql = "update ".$this->tbl_name." set actNum = actNum + ".$inNum." where id = ".$id;
	 *	return $this->query($sql);
	}*/
}
?>