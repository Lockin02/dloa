<?php
/**
 * @author yxin1
 * @Date 2014年12月2日 14:42:10
 * @version 1.0
 * @description:指标值表 Model层
 */
 class model_contract_gridreport_indicators  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_indicators";
		$this->sql_map = "contract/gridreport/indicatorsSql.php";
		parent::__construct ();
	}

	/**
	 * 重写add
	 */
	function add_d($object){
		try {
			$this->start_d();

			if (is_array($object["item"])) {
				foreach ($object["item"] as $key => $val) {
					$val["objCode"] = $object["objCode"];
					$val["objName"] = $object["objName"];
					$val["setCode"] = $object["setCode"];
					$val["setName"] = $object["setName"];
					parent::add_d($val);
				}
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit_d
	 */
	function edit_d($object){
		try {
			$this->start_d();

			if (is_array($object["item"])) {
				foreach ($object["item"] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val["setCode"] = $object["setCode"];
						$val["setName"] = $object["setName"];
						parent::edit_d($val);
					} else {
						$this->deleteByPk(array("id" => $val["id"]));
					}
				}
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写批量删除对象
	 */
	function deletes_d($ids) {
		try {

			$idArr = explode(",", $ids);
			foreach ($idArr as $key => $val) {
				$obj = $this->get_d($val);
				$this->delete(array("setCode" => $obj["setCode"]));
			}

			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
 }
?>