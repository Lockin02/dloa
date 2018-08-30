<?php
/**
 * @author Michael
 * @Date 2014��8��25�� 11:05:34
 * @version 1.0
 * @description:�������������嵥���Ʋ�
 */
class controller_produce_task_taskconfigitem extends controller_base_action {

	function __construct() {
		$this->objName = "taskconfigitem";
		$this->objPath = "produce_task";
		parent::__construct ();
	}

	/**
	 * ��ת���������������嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������������������嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭�������������嵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴�������������嵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ��ȡ����������ݵ�Json
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
							unset($rows[$k]); //���������ɾ���ˣ����Ч��
							$i++;
						}
					}
				}
			}
			$rows = $rowData;
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>