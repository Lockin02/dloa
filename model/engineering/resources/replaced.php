<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:52:42
 * @version 1.0
 * @description:设备管理-可替换设备管理 Model层
 */
 class model_engineering_resources_replaced  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_replaced";
		$this->sql_map = "engineering/resources/replacedSql.php";
		parent::__construct ();
	}


	/**
	 * 添加对象
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//插入主表信息
			$newId = parent :: add_d($object, true);
			//可替换物料
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
	 * 根据主键修改对象
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//插入主表信息
			parent :: edit_d($object, true);
			//物料
			$infoDao = new model_engineering_resources_replacedinfo();
			$infoDao->delete(array ( 'replacedId' => $object['id'], ));
			//在库类
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