<?php


/**
 * 资产租赁model层类
 */
class model_asset_daily_rent extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_rent";
		$this->sql_map = "asset/daily/rentSql.php";
		parent :: __construct();

	}

	/*===================================业务处理======================================*/

	/**
	* 设置关联从表的申请单id信息
	*/
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	/* @desription 根据id获取申请单所有产品信息
	 * @param tags
	 * @date 2011-8-17
	 */
	function get_d($id) {
		$rentitemDao = new model_asset_daily_rentitem();
		$rentitemDao->searchArr['rentId'] = $id;
		$items = $rentitemDao->listBySqlId();
		$rent = parent :: get_d($id);
		$rent['details'] = $items; //details被c层获取
		return $rent;
	}
	/**
	 * @desription 添加保存方法
	 * @date 2011-11-21
	 * @chenzb
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if (is_array($object['rentitem'])) {
				/*s:1.保存主表基本信息*/
				//$codeDao = new model_common_codeRule ();
				$id = parent :: add_d($object, true);
				/*e:1.保存主表基本信息*/
				/*s:2.保存从表资产信息*/
				$rentitemDao = new model_asset_daily_rentitem();
				$itemsObjArr = $object['rentitem'];
				$itemsArr = $this->setItemMainId("rentId", $id, $itemsObjArr);
				$itemsObj = $rentitemDao->saveDelBatch($itemsArr);
				/*e:2.保存从表资产信息*/
				$this->commit_d();
				return $id;

			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}
	/**
	 * 修改保存
	* @desription 添加保存方法
	 * @date 2011-11-21
	 * @chenzb
	 */

	function edit_d($object) {
		try {
			$this->start_d();

			if (is_array($object['rentitem'])) {
				/*s:1.保存主表基本信息*/
				//$codeDao = new model_common_codeRule ();
				$id = parent :: edit_d($object, true);
				/*e:1.保存主表基本信息*/
				/*s:2.保存从表资产信息*/
				$rentitemDao = new model_asset_daily_rentitem();
				$itemsObjArr = $object['rentitem'];
				$itemsArr = $this->setItemMainId("rentId", $object['id'], $itemsObjArr);

				$itemsObj = $rentitemDao->saveDelBatch($itemsArr);
				/*e:2.保存从表资产信息*/
				$this->commit_d();
				return true;
			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

}
?>