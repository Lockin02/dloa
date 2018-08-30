<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:52:42
 * @version 1.0
 * @description:�豸����-���滻�豸���� Model��
 */
 class model_engineering_resources_replaced  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_replaced";
		$this->sql_map = "engineering/resources/replacedSql.php";
		parent::__construct ();
	}


	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//����������Ϣ
			$newId = parent :: add_d($object, true);
			//���滻����
			$infoDao = new model_engineering_resources_replacedinfo();
			$infoDao->createBatch($object['info'], array (
				'replacedId' => $newId
			));

			$this->commit_d();
//						$this->rollBack();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//����������Ϣ
			parent :: edit_d($object, true);
			//����
			$infoDao = new model_engineering_resources_replacedinfo();
			$infoDao->delete(array ( 'replacedId' => $object['id'], ));
			//�ڿ���
			foreach ($object['info'] as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($object['info'][$k]);
				}
			}
			$infoDao->createBatch($object['info'], array (
				'replacedId' => $object['id']
			));

			$this->commit_d();
//						$this->rollBack();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

 }
?>