<?php


/**
 * �ʲ�����model����
 */
class model_asset_daily_rent extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_rent";
		$this->sql_map = "asset/daily/rentSql.php";
		parent :: __construct();

	}

	/*===================================ҵ����======================================*/

	/**
	* ���ù����ӱ�����뵥id��Ϣ
	*/
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	/* @desription ����id��ȡ���뵥���в�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-8-17
	 */
	function get_d($id) {
		$rentitemDao = new model_asset_daily_rentitem();
		$rentitemDao->searchArr['rentId'] = $id;
		$items = $rentitemDao->listBySqlId();
		$rent = parent :: get_d($id);
		$rent['details'] = $items; //details��c���ȡ
		return $rent;
	}
	/**
	 * @desription ��ӱ��淽��
	 * @date 2011-11-21
	 * @chenzb
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if (is_array($object['rentitem'])) {
				/*s:1.�������������Ϣ*/
				//$codeDao = new model_common_codeRule ();
				$id = parent :: add_d($object, true);
				/*e:1.�������������Ϣ*/
				/*s:2.����ӱ��ʲ���Ϣ*/
				$rentitemDao = new model_asset_daily_rentitem();
				$itemsObjArr = $object['rentitem'];
				$itemsArr = $this->setItemMainId("rentId", $id, $itemsObjArr);
				$itemsObj = $rentitemDao->saveDelBatch($itemsArr);
				/*e:2.����ӱ��ʲ���Ϣ*/
				$this->commit_d();
				return $id;

			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}
	/**
	 * �޸ı���
	* @desription ��ӱ��淽��
	 * @date 2011-11-21
	 * @chenzb
	 */

	function edit_d($object) {
		try {
			$this->start_d();

			if (is_array($object['rentitem'])) {
				/*s:1.�������������Ϣ*/
				//$codeDao = new model_common_codeRule ();
				$id = parent :: edit_d($object, true);
				/*e:1.�������������Ϣ*/
				/*s:2.����ӱ��ʲ���Ϣ*/
				$rentitemDao = new model_asset_daily_rentitem();
				$itemsObjArr = $object['rentitem'];
				$itemsArr = $this->setItemMainId("rentId", $object['id'], $itemsObjArr);

				$itemsObj = $rentitemDao->saveDelBatch($itemsArr);
				/*e:2.����ӱ��ʲ���Ϣ*/
				$this->commit_d();
				return true;
			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

}
?>