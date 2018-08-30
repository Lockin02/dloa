<?php
/**
 * @author phz
 * @Date 2014��1��20�� ����һ 10:37:38
 * @version 1.0
 * @description:�������Ʋ� 
 */
class controller_outsourcing_workorder_order extends controller_base_action {

	function __construct() {
		$this->objName = "order";
		$this->objPath = "outsourcing_workorder";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת����������ҳ��
	 */
	function c_toAdd() {
	 $this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//��ͬ�����ʽ
	 $this->showDatadicts ( array ('nature' => 'GCXMXZ' ) );//��Ŀ����
     $this->view ( 'add' );
	 
   }
   
   /**
	 * ��ת���༭����ҳ��
	 */
	function c_toEdit() {
//   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      		$this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴����ҳ��
	 */
	function c_toView() {
//      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
  	 /**
	 * ������������excel
	 */
	function c_excelOutAll() {
		set_time_limit(0);
		if($_GET['act']==1){	//	�ж��Ƿ�ӹ�Ӧ�̹�������
			$suppCode = $_SESSION['USER_ID'];
			$this->service->searchArr['suppCode'] = $suppCode;
		}
		$rows = $this->service->list_d('select_orderOut');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		//var_dump($rows);exit();
		$colArr  = array(
		);
		$modelName = '�������';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$rows, $modelName);
	}
	 /*
	  * ��ת����������߼���ѯҳ��
	  */
	function c_toSearchExport(){
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ));//��ͬ�����ʽ
		$this->showDatadicts ( array ('nature' => 'GCXMXZ' ));//�������Ŀ����
	  	$this->view ( 'searchExport' );
	}
	  /*
	   * ��������߼���ѯ��������
	   */
	 function c_searchExport(){
	   	set_time_limit(0);
	   	$service = $this->service;
	 	$formData = $_POST[$this->objName];
	 	if(trim($formData['approvalCode'])){ //���������
			$service->searchArr['approvalCode'] = $formData['approvalCode'];
	 	}
	 	if(trim($formData['suppName'])){ //�����Ӧ������
			$service->searchArr['suppName'] = $formData['suppName'];
	 	}
	 	if(trim($formData['suppCode'])){ //�����Ӧ�̱��
			$service->searchArr['suppCode'] = $formData['suppCode'];
	 	}
	 	if(trim($formData['projectName'])){ //��Ŀ����
			$service->searchArr['projectName'] = $formData['projectName'];
	 	}
	 	if(trim($formData['projectCode'])){ //��Ŀ���
			$service->searchArr['projectCode'] = $formData['projectCode'];
	 	}
	 	if(trim($formData['provinceId'])){ //��Ŀ���
			$service->searchArr['provinceId'] = $formData['provinceId'];
	 	}
	 	if(trim($formData['suppType'])){ //�������
			$service->searchArr['suppType'] = $formData['suppType'];
	 	}
	 	if(trim($formData['natureCode'])){ //��Ŀ����
			$service->searchArr['natureCode'] = $formData['natureCode'];
	 	}
	 	if(trim($formData['projectManager'])){ //��Ŀ����
			$service->searchArr['projectManager'] = $formData['projectManager'];
	 	}
	 	if(trim($formData['ExaStatus'])){ //���״̬
			$service->searchArr['ExaStatus'] = $formData['ExaStatus'];
	 	}
	 	if(trim($formData['ExaDT'])){ //���ʱ��
			$service->searchArr['ExaDT'] = $formData['ExaDT'];
	 	}
	 	$rows = $service->list_d('select_orderOut');
	 	for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array(
		);
		$modelName = '�������';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$rows, $modelName);
	 }
	 /*
	  * ��Ӧ�̹���
	  */
	 function c_toSupplierOrder(){
	 	$userAccount = $_SESSION['USER_ID'];
		$suppCode = $this->service->get_table_fields('oa_outsourcesupp_personnel', "userAccount='".$userAccount."'", 'suppCode');
		if($suppCode){
			$this->assign('suppCode',$suppCode);
		}
		else{
			$this->assign('suppCode',"-");	//�����ѯ������Ϊ��ֵ�������ϸ�����
		}
		$this->view('supplierList');
		
	 }
 }
?>