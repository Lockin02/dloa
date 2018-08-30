<?php
/**
 * @author Show
 * @Date 2011年12月25日 星期日 14:36:05
 * @version 1.0
 * @description:租车单位(oa_carrental_units) Model层
 */
 class model_carrental_units_units  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_units";
		$this->sql_map = "carrental/units/unitsSql.php";
		parent::__construct ();
	}


	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 重写新增
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * 根据读取EXCEL中的信息导入到系统中
	 * @param $stockArr
	 * importUnitsInfo()--->importUnitsInfo()
	 */
	function importUnitsInfo($excelData) {
			set_time_limit ( 0 );
			$resultArr = array ();//结果数组
		    $addArr = array();//正确信息数组
			$datadictArr = array();//数据字典数组
			$datadictDao = new model_system_datadict_datadict();
			$countryArr = array();//国家数组
			$countryDao = new model_system_procity_country();
			$provinceArr = array();//省份数组
			$provinceDao = new model_system_procity_province();
			$cityArr = array();//城市数组
			$cityDao = new model_system_procity_city();
			if(is_array($excelData)){
				//行数组循环
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$val[1] = str_replace( ' ','',$val[1]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						//出租单位名称
						if(!empty($val[0])){
							$unitName = $val[0];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '更新失败!没有填写的出租单位';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//国家
						if(!empty($val[2])){
							$countryName = $val[2];
							if(!isset($countryArr[$val[2]])){

							   $sql = "select countryCode from oa_system_country_info where countryName = '$countryName'"; //查找ID
				               $countryN =  $this->_db->getArray($sql);

				               $countryCode = $countryN[0]['countryCode'];

								if(!empty($countryCode)){
									$countryCode = $countryArr[$val[2]]['countryCode'] = $countryCode;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!没有对应的国家';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$countryCode = $countryArr[$val[2]]['countryCode'];
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有填入国家';
							array_push( $resultArr,$tempArr );
							continue;
						}


						//省份
						if(!empty($val[3])){
							$provinceName = $val[3];
							if(!isset($provinceArr[$val[3]])){
								$provinceCode = $provinceDao->getCodeByName($val[3]);
								if(!empty($provinceCode)){
									$provinceCode = $provinceArr[$val[3]]['provinceCode'] = $provinceCode;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!没有对应的省份';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$provinceCode = $provinceArr[$val[3]]['provinceCode'];
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有填入省份';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//城市
						if(!empty($val[4])){
							$cityName = $val[4];
							if(!isset($cityArr[$val[4]])){

							   $sql = "select cityCode from oa_system_city_info where cityName = '$cityName'"; //查找ID
				               $cityN =  $this->_db->getArray($sql);

				               foreach($cityN as $k => $v){
			                       $cityCode = $v['cityCode'];
				               }

								if(!empty($cityCode)){
									$cityCode = $cityArr[$val[4]]['cityCode'] = $cityCode;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!没有对应的城市';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$cityCode = $cityArr[$val[4]]['cityCode'];
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有填入城市';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//单位性质

						if(!empty($val[5])){
							$val[5] = trim($val[5]);
							if(!isset($datadictArr[$val[5]])){
								$rs = $datadictDao->getCodeByName('DWXZ',$val[5]);
								if(!empty($rs)){
									$unitNature = $datadictArr[$val[5]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!不存在的单位性质';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$unitNature = $datadictArr[$val[5]]['code'];
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有单位性质';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//联系人
						if(!empty($val[6])){
							$linkMan = $val[6];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '更新失败!没有填写联系人';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//联系电话
						if(!empty($val[7])){
							$linkPhone = $val[7];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '更新失败!没有填写联系电话';
							array_push( $resultArr,$tempArr );
							continue;
						}

						$updateRows = array(
							'unitName' => $unitName,
							'address' => $val[1],
							'countryName' => $countryName,
							'countryCode' => $countryCode,
							'provinceName' => $provinceName,
							'provinceCode' => $provinceCode,
							'cityName' => $cityName,
							'cityCode' => $cityCode,
							'unitNature' => $unitNature,
							'linkMan' => $linkMan,
							'linkPhone' => $linkPhone,
							'remark' => $val[8]
						);
							if ($this->add_d ($updateRows,true)) {
								$tempArr['result'] = '更新成功';
							}else{
								$tempArr['result'] = '无更新内容';
							}

						$tempArr['docCode'] = '第' . $actNum .'条数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}

	}


}
?>