<?php
/**
 * @author Michael
 * @Date 2014年11月28日 17:30:26
 * @version 1.0
 * @description:表格勾选记录表 Model层
 */
 class model_contract_gridreport_gridrecord  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_gridrecord";
		$this->sql_map = "contract/gridreport/gridrecordSql.php";
		parent::__construct ();
	}

	/**
	 * 保存用户勾选记录
	 */
	function saveRecord_d($obj ,$objCode) {
		try {
			$this->start_d();

			$record = $this->findAll(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => $objCode));

			if (is_array($record)) {
				if (count($obj) == count($record)) {
					foreach ($obj as $key => $val) {
						foreach ($record as $k => $v) {
							if ($v['colName'] == $key) {
								$this->updateById(array("id" => $v["id"] ,"colValue" => $val));
							}
						}
					}
				} else {
					$this->delete(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => $objCode));
					foreach ($obj as $key => $val) {
						$newObj = array(
							"userId" => $_SESSION["USER_ID"],
							"recordCode" => $objCode,
							"colName" => $key,
							"colValue" => $val
						);
						parent::add_d($newObj);
					}
				}
			} else {
				foreach ($obj as $key => $val) {
					$newObj = array(
						"userId" => $_SESSION["USER_ID"],
						"recordCode" => $objCode,
						"colName" => $key,
						"colValue" => $val
					);
					parent::add_d($newObj);
				}
			}
			//不同指标类型，时间区间同步更新
			$this->update(array("userId" => $_SESSION["USER_ID"],"colName" => "startMonth"),array("colValue" => $obj["startMonth"]));
			$this->update(array("userId" => $_SESSION["USER_ID"],"colName" => "endMonth"),array("colValue" => $obj["endMonth"]));
			
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}

    /**
     *  按登录人获取配置信息
     */
     function getRecordInfo(){
         $recordCode = "productLine"; // 配置编码，暂时写死，后续扩展
         $sql = "select * from oa_system_gridrecord where userId='".$_SESSION["USER_ID"]."' AND recordCode='$recordCode'" ;
         $arr = $this->_db->getArray($sql);
         return $arr;
     }
     
     /**
      * 默认新增配置
      */
     function addDefault(){
     	//用户不存在相关配置则新增：默认指标全部勾选，时间区间为当年1月到当前月，呈现方式为累计，单位为万元
     	$indicatorsDao = new model_contract_gridreport_gridindicators();
     	$indicatorsObj = $indicatorsDao->findAll();
     	$startMonth = date("Y-01");//默认当年第一个月
     	$endMonth = date("Y-m");//默认当前月份
     	$addArr = array();
     	foreach ($indicatorsObj as $key => $val){
     		$rs = $this->findAll(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => $val['objCode']));
     		if(empty($rs)){
     			$defaultArr = array(
     					array(
     							'colName' => 'startMonth',
     							'colValue' => $startMonth,
     							'recordCode' => $val['objCode']
     					),
     					array(
     							'colName' => 'endMonth',
     							'colValue' => $endMonth,
     							'recordCode' => $val['objCode']
     					),
     					array(
     							'colName' => 'presentation',
     							'colValue' => 1,
     							'recordCode' => $val['objCode']
     					),
     					array(
     							'colName' => 'unit',
     							'colValue' => 2,
     							'recordCode' => $val['objCode']
     					)
     			);
     			foreach ($indicatorsObj[$key]["item"] as $k => $v) {
     				array_push($defaultArr, array('colName' => $v['indicatorsCode'],'colValue' => 1,'recordCode' => $val['objCode']));
     			}
     			$addArr = array_merge($addArr,$defaultArr);
     		}
     		continue;
     	}
     	$this->createBatch($addArr,array('userId' => $_SESSION["USER_ID"]));
     }
 }