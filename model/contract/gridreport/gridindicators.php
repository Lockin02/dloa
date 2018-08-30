<?php
/**
 * @author yxin1
 * @Date 2014年12月1日 13:43:29
 * @version 1.0
 * @description:表格指标表 Model层
 */
 class model_contract_gridreport_gridindicators  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_gridindicators";
		$this->sql_map = "contract/gridreport/gridindicatorsSql.php";
		parent::__construct ();
	}

	/**
	 * 重写add
	 */
	function add_d($object){
		try {
			$this->start_d();

			$id = parent::add_d($object ,true);

			if ($id) {
				if (is_array($object["item"])) {
					$itemDao = new model_contract_gridreport_gridindicatorsitem();
					foreach ($object["item"] as $key => $val) {
						$val["parentId"] = $id;
						$itemDao->add_d($val);
					}
				}
			}

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit
	 */
	function edit_d($object){
		try {
			$this->start_d();

			$id = parent::edit_d($object ,true);

			if ($id) {
				if (is_array($object["item"])) {
					$itemDao = new model_contract_gridreport_gridindicatorsitem();
					$recordDao = new model_contract_gridreport_gridrecord();
					foreach ($object["item"] as $key => $val) {
						if ($val["isDelTag"] != 1) {
							if ($val["id"] > 0) {
								$oldObj = $itemDao->get_d($val["id"]);
								if ($oldObj["indicatorsCode"] != $val["indicatorsCode"]) { //如果改变了编码
									$conditions = array("colName" => $oldObj["indicatorsCode"] ,"recordCode" => $object["objCode"]);
									$newRow = array("colName" => $val["indicatorsCode"]);
									$recordDao->update($conditions ,$newRow);
								}
								$itemDao->edit_d($val);
							} else {
								$val["parentId"] = $object["id"];
								$itemDao->add_d($val);
							}
						} else {
							$recordDao->delete(array("colName" => $val["indicatorsCode"] ,"recordCode" => $object["objCode"]));
							$itemDao->deleteByPk($val["id"]);
						}
					}
				}
			}

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写find
	 */
	function find($conditions = null, $sort = null, $fields = null) {
		$obj = parent::find($conditions ,$sort ,$fields);
		if ($obj) {
			$itemDao = new model_contract_gridreport_gridindicatorsitem();
			$obj["item"] = $itemDao->findAll(array("parentId" => $obj["id"]));
			return $obj;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 重写findAll
	 */
	function findAll($conditions = null, $sort = null, $fields = null) {
		$obj = parent::findAll($conditions ,$sort ,$fields);
		if ($obj) {
			$itemDao = new model_contract_gridreport_gridindicatorsitem();
			foreach ($obj as $k => $v){
				$obj[$k]["item"] = $itemDao->findAll(array("parentId" => $v["id"]));
			}
			return $obj;
		} else {
			return FALSE;
		}
	}
 }
?>