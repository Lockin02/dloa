<?php
/**
 * @author Michael
 * @Date 2014��1��7�� ���ڶ� 10:27:48
 * @version 1.0
 * @description:������Ӧ��-������Դ����Ʋ�
 */
class controller_outsourcing_outsourcessupp_vehicle extends controller_base_action {

	function __construct() {
		$this->objName = "vehicle";
		$this->objPath = "outsourcing_outsourcessupp";
		parent::__construct ();
	}

	/**
	 * ��ת��������Ӧ��-������Դ���б�
	 */
    function c_page() {
		$this->view('list');
	}

   /**
	 * ��ת������������Ӧ��-������Դ��ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' )
;	}

   /**
	 * �ӹ�Ӧ����ת������������Ӧ��-������Դ��ҳ��
	 */
	function c_toAddSupp() {
		$vehiclesuppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$obj = $vehiclesuppDao->get_d( $_GET ['suppId'] );
		$this->assign ('suppId' ,$obj['id']);
		$this->assign ('suppCode' ,$obj['suppCode']);
		$this->assign ('suppName' ,$obj['suppName']);
		$this->view ( 'add-supp' );
	}

   /**
	 * ��ת���༭������Ӧ��-������Դ��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

   /**
	 * �ӹ�Ӧ����ת���༭������Ӧ��-������Դ��ҳ��
	 */
	function c_toEditSupp() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit-supp');
	}

   /**
	 * ��ת���༭������Ӧ��-������Դ���б�
	 */
	function c_toEditList() {
		$this->permCheck (); //��ȫУ��
		$suppId = isset($_GET ['suppId'])?$_GET ['suppId']:'';
		$this->assign('suppId' ,$suppId);
		$this->view ( 'edit-list' );
	}

   /**
	 * ��ת���鿴������Ӧ��-������Դ��ҳ��
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
	 * ��ת���鿴������Ӧ��-������Դ���б�
	 */
	function c_toViewList() {
		$this->permCheck (); //��ȫУ��
		$suppId = isset($_GET ['suppId'])?$_GET ['suppId']:'';
		$this->assign('suppId' ,$suppId);
		$this->view ( 'view-list' );
	}

	/**
	 * ����������Դ����Ϣ
	 */
	function c_excelOut() {
		set_time_limit(0);
		$rows = $this->service->listBySqlId('select_excelOut');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '������Դ����Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
	}

    /**
	 * ��ת���Զ��嵼��excelҳ��
	 */
	function c_toExcelOutCustom() {
		$this->view('excelOutCustom');
	}

/**
	 * �Զ��嵼��������Ӧ����Ϣ
	 */
	function c_excelOutCustom() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['suppCode'])) //��Ӧ�̱��
			$this->service->searchArr['suppCodeSea'] = $formData['suppCode'];

		if(!empty($formData['suppName'])) //��Ӧ������
			$this->service->searchArr['suppName'] = $formData['suppName'];

		if(!empty($formData['place'])) //�ص�
			$this->service->searchArr['place'] = $formData['place'];

		if(!empty($formData['carNumber'])) //���ƺ�
			$this->service->searchArr['carNumber'] = $formData['carNumber'];

	 	if(!empty($formData['carModel'])) //����
			$this->service->searchArr['carModel'] = $formData['carModel'];

		if(!empty($formData['brand'])) //Ʒ��
			$this->service->searchArr['brand'] = $formData['brand'];

		if(!empty($formData['displacement'])) //����
			$this->service->searchArr['displacement'] = $formData['displacement'];

		if(!empty($formData['buyDateSta'])) //����������
			$this->service->searchArr['buyDateSta'] = $formData['buyDateSta'];
		if(!empty($formData['buyDateEnd'])) //����������
			$this->service->searchArr['buyDateEnd'] = $formData['buyDateEnd'];

		$rows = $this->service->listBySqlId('select_excelOut');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
		}

		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '������Դ����Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
	}

    /**
	 * ��ת������excelҳ��
	 */
	function c_toExcelIn() {
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn() {
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '������Դ�⵼�����б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
}
?>