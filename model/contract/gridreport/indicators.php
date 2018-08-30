<?php
/**
 * @author yxin1
 * @Date 2014��12��2�� 14:42:10
 * @version 1.0
 * @description:ָ��ֵ�� Model��
 */
 class model_contract_gridreport_indicators  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_indicators";
		$this->sql_map = "contract/gridreport/indicatorsSql.php";
		parent::__construct ();
	}

	/**
	 * ��дadd
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
	 * ��дedit_d
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
	 * ��д����ɾ������
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