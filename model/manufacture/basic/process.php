<?php
/**
 * @author Michael
 * @Date 2014年7月25日 15:13:03
 * @version 1.0
 * @description:基础信息-工序 Model层
 */
 class model_manufacture_basic_process  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_process";
		$this->sql_map = "manufacture/basic/processSql.php";
		parent::__construct ();
	}

	/**
	 * 重写add
	 */
	function add_d( $object ) {
		try {
			$this->start_d();

			//获取归属公司名称
			$object['formBelong']         = $_SESSION['USER_COM'];
			$object['formBelongName']     = $_SESSION['USER_COM_NAME'];
			$object['businessBelong']     = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent::add_d($object);

			if ($id) {
				if (is_array($object['thead']) && is_array($object['info'])) {
					$configDao = new model_manufacture_basic_productconfig();
					$configItemDao = new model_manufacture_basic_productconfigitem();

					$theadArr = array(); // 表头数组
					foreach ($object['thead'] as $key => $val) {
						array_push($theadArr ,$val); //重新排序数组，保证下标不间断
					}

					$tbodyArr = array(); //表数据数组
					$i = 0;
					foreach ($object['info'] as $key => $val) {
						unset($val['rowNum_']);
						$j = 0;
						foreach ($val as $k => $v) {
							$tbodyArr[$j][$i] = $v; //重新排序数组，保证下标不间断
							$j++;
						}
						$i++;
					}

					foreach ($theadArr as $key => $val) {
						$configData = array();
						$configData['processId']    = $id;
						$configData['configType']   = $object['configType'];
						$configData['configTypeId'] = $object['configTypeId'];
						$configData['configName']   = $object['configName'];
						$configData['configId']     = $object['configId'];
						$configData['configCode']   = $object['configCode'];
						$configData['colCode']      = 'column'.$key;
						$configData['colName']      = $val;
						$configData['colNum']       = $key;
						$configId = $configDao->add_d($configData);
						if ($configId) {
							foreach ($tbodyArr[$key] as $k => $v) {
								$configItemData = array();
								$configItemData['parentId']   = $configId;
								$configItemData['colCode']    = 'column'.$key;
								$configItemData['colName']    = $val;
								$configItemData['colContent'] = $v;
								$configItemData['rowNum']     = $k;
								$configItemDao->add_d($configItemData);
							}
						}
					}
				}

				if (is_array($object['equ'])) { //工序从表处理
					$equDao = new model_manufacture_basic_processequ();
					foreach ($object['equ'] as $key => $val) {
						$val['parentId'] = $id;
						$equDao->add_d($val);
					}
				}
			}

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit
	 */
	function edit_d( $object ) {
		try {
			$this->start_d();

			$id = parent::edit_d($object ,true);

			if ($id) {
				if (is_array($object['thead']) && is_array($object['info'])) {
					$configDao = new model_manufacture_basic_productconfig();
					$configItemDao = new model_manufacture_basic_productconfigitem();

					$configDao->delete(array('processId' => $object['id'])); // 把原本的配置删除，从表已经设置自带删除功能，此处只需删除主表数据

					$theadArr = array(); // 表头数组
					foreach ($object['thead'] as $key => $val) {
						array_push($theadArr ,$val); //重新排序数组，保证下标不间断
					}

					$tbodyArr = array(); //表数据数组
					$i = 0;
					foreach ($object['info'] as $key => $val) {
						unset($val['rowNum_']);
						$j = 0;
						foreach ($val as $k => $v) {
							$tbodyArr[$j][$i] = $v; //重新排序数组，保证下标不间断
							$j++;
						}
						$i++;
					}

					foreach ($theadArr as $key => $val) {
						$configData = array();
						$configData['processId']    = $object['id'];
						$configData['configType']   = $object['configType'];
						$configData['configTypeId'] = $object['configTypeId'];
						$configData['configName']   = $object['configName'];
						$configData['configId']     = $object['configId'];
						$configData['configCode']   = $object['configCode'];
						$configData['colCode']      = 'column'.$key;
						$configData['colName']      = $val;
						$configData['colNum']       = $key;
						$configId = $configDao->add_d($configData);
						if ($configId) {
							foreach ($tbodyArr[$key] as $k => $v) {
								$configItemData = array();
								$configItemData['parentId']   = $configId;
								$configItemData['colCode']    = 'column'.$key;
								$configItemData['colName']    = $val;
								$configItemData['colContent'] = $v;
								$configItemData['rowNum']     = $k;
								$configItemDao->add_d($configItemData);
							}
						}
					}
				}

				//工序从表处理
				$equDao = new model_manufacture_basic_processequ();
				if (is_array($object['equ'])) {
					$newEquObjs = $equDao->saveDelBatch($object['equ']);
					if (!empty($newEquObjs)) {
						foreach ($newEquObjs as $key => $val) {
							if ($val['isAddAction'] == true) {
								$val['parentId'] = $object['id'];
								$equDao->edit_d($val);
							}
						}
					}
				}
			}

			$this->commit_d();
			return $object['id'];
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 批量修改状态
	 */
	function updateEnableByIds_d($ids ,$isEnable) {
		try {
			$this->start_d();

			$sql = "UPDATE ".$this->tbl_name." SET isEnable='".$isEnable."' WHERE id in (".$ids.")";
			$this->query($sql);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}
?>