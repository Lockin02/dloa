<?php
/**
 * @author Show
 * @Date 2014年1月7日 星期二 10:27:48
 * @version 1.0
 * @description:车辆供应商-车辆资源库 Model层
 */
 class model_outsourcing_outsourcessupp_vehicle  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcessupp_vehicle";
		$this->sql_map = "outsourcing/outsourcessupp/vehicleSql.php";
		parent::__construct ();
	}

	//公司权限处理 TODO
	// protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	/**
	 * excel导入
	 */
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		$linkmanArr = array();//插入数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$vehiclesuppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					$actNum = $key + 1;
					if( empty($val[0])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//供应商名称
						if(!empty($val[0])&&trim($val[0])!=''){
							$vehiclesuppObj = $vehiclesuppDao->find(array("suppName" => $val[0]));
							if(!$vehiclesuppObj){
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!该车辆供应商信息不存在</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$inArr['suppName'] = trim($val[0]);
								$inArr['suppId'] = $vehiclesuppObj['id'];
								$inArr['suppCode'] = $vehiclesuppObj['suppCode'];
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商名称为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//地点
						if(!empty($val[1])&&trim($val[1])!=''){
							$inArr['place'] = trim($val[1]);
						}

						//车牌号
						if(!empty($val[2])&&trim($val[2])!=''){
							$inArr['carNumber'] = trim($val[2]);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!车牌号为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//车型
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['carModel'] = trim($val[3]);
						}

						//品牌
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['brand'] = trim($val[3]);
						}

						//排量
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['displacement'] = trim($val[5]);
						}

						//车辆供电情况
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['powerSupply'] = trim($val[6]);
						}

						//综合油耗
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['oilWear'] = trim($val[7]);
						}

						//购车时间
						if(!empty($val[8])&& $val[8] != '0000-00-00'&&trim($val[8])!=''){
							$val[8] = trim($val[8]);
							if(!is_numeric($val[8])){
								$inArr['buyDate'] = $val[8];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[8] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$entryDate = date('Y-m-d',strtotime ($val[8]));
									$inArr['buyDate'] = $entryDate;
								}else{
									$inArr['buyDate'] = $recorderDate;
								}
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!购车日期为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//司机
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['driver'] = trim($val[9]);
						}

						//联系电话
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['phoneNum'] = trim($val[10]);
						}

						//身份证号
						if(!empty($val[11])&&trim($val[11])!=''){
							$inArr['idNumber'] = trim($val[11]);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!身份证号为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//驾驶证
						if(!empty($val[12])&&trim($val[12])!=''){
							$inArr['drivingLicence'] = trim($val[12]);
						}

						//行驶证
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['vehicleLicense'] = trim($val[13]);
						}

						//保险
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['insurance'] = trim($val[14]);
						}

						//年审
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['annualExam'] = trim($val[15]);
						}

						//租车单价
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['rentPrice'] = trim($val[16]);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!租车单价为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						$newId = parent::add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<font color=red>导入失败</font>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}
}
?>