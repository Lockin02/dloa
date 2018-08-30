<?php
/**
 * @author Michael
 * @Date 2014年8月25日 11:05:34
 * @version 1.0
 * @description:生产任务配置清单控制层
 */
class controller_produce_task_taskconfigitem extends controller_base_action {

	function __construct() {
		$this->objName = "taskconfigitem";
		$this->objPath = "produce_task";
		parent::__construct ();
	}

	/**
	 * 跳转到生产任务配置清单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增生产任务配置清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑生产任务配置清单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看生产任务配置清单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 获取表格所有数据的Json
	 */
	function c_tableJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_table');

		if (is_array($rows)) {
			$configDao = new model_produce_task_taskconfig();
			$condiction = array('taskId' => $_POST['taskId'] ,'configCode' => $_POST['configCode']);
			$configObj = $configDao->findAll($condiction ,'id ASC');
			$rowData = array();
			if (is_array($configObj)) {
				foreach ($configObj as $key => $val) {
					$i = 0;
					foreach ($rows as $k => $v) {
						if ($val['colCode'] == $v['colCode']) {
							$rowData[$i][$val['colCode']] = $v['colContent'];
							unset($rows[$k]); //数据用完就删除了，提高效率
							$i++;
						}
					}
				}
			}
			$rows = $rowData;
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>