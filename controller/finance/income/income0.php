<?php
/**
 * ������Ʋ���
 */
class controller_finance_income_income extends controller_base_action {

	function __construct() {
		$this->objName = "income";
		$this->objPath = "finance_income";
		parent::__construct ();
	}

	/**
	 * ��дpage
	 */
	function c_page(){
		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);

		$this->display($thisObjCode.'-list');
	}

	/**
	 * ��������ҳ��
	 */
	function c_toAdd() {
		//���������ֵ�
		$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ) );
		$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ) );
		$this->assign('incomeDate',day_date);

		//��ȡĬ�Ϸ�����
		$rs = $this->service->getSendMen_d();
		$this->assignFunc($rs);

		//���Ե�������ҳ��
		$this->assign('formType' ,$_GET['formType']);
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);

		$this->display($thisObjCode . '-add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		//���Ե�������ҳ��
		$thisClass = $this->service->getClass($object['formType']);

		$id = $this->service->add_d ( $object,new $thisClass());
		if ($id) {
			msgRf( '��ӳɹ���','?model=finance_income_income&action=toAdd&formType=YFLX-DKD');
		}else{
			msgRf ('���ʧ�ܣ�');
		}
	}

	/**
	 * ����������ͬ����ҳ��
	 */
	function c_toAddOther() {
		//���������ֵ�
		$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ) );
		$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ) );
		$this->assign('incomeDate',day_date);

		//��ȡĬ�Ϸ�����
		$rs = $this->service->getSendMen_d();
		$this->assignFunc($rs);

		$this->display('income-other-add');
	}

	/**
	 * ����������ͬ����
	 */
	function c_addOther(){
		$object = $_POST[$this->objName];

		$id = $this->service->addOther_d ( $object);
		if ($id) {
			msgRf( '��ӳɹ���','?model=finance_income_income&action=toAddOther');
		}else{
			msgRf ('���ʧ�ܣ�');
		}
	}


    /**
     * �������ɵ���
     */
    function c_addByPush(){
        //URLȨ�޿���
        $this->permCheck();

        //��ȡ���ӱ�����
        $income = $this->service->getInfoAndDetail_d ( $_GET ['id'] );

        //��ȡ�ӱ�����
        //��ȡ�ӱ�����
        $incomeAllotRows = $income['incomeAllot'];
        unset($income['incomeAllot']);

        $this->assignFunc($income);

        //���Ե�������ҳ��
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $this->assign('thisFormType',$_GET['formType']);

        //��Ⱦ�ӱ�����
        $incomeStr = $this->service-> initAllot_d( $incomeAllotRows ,'push');
        $this->assign ( 'incomeAllot', $incomeStr[0] );
        $this->assign ( 'countNumb', $incomeStr[1] );

        $this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ),$income['incomeType'] );
        $this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ),$income['sectionType'] );
        $this->display($thisObjCode.'-addbypush');
    }

	/**
	 * ��ʼ����ҳ��
	 */
	function c_init() {
        //URLȨ�޿���
        $this->permCheck();
		$income = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($income);

		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($income['formType']);

		if (isset($_GET ['perm']) && $_GET ['perm'] == 'view') {
			$this->assign('incomeType',$this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionType',$this->getDataNameByCode($income['sectionType']));
			$this->display ( $thisObjCode. '-view' );
		} else {
			$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ),$income['incomeType'] );
			$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ),$income['sectionType'] );
			$this->display ( $thisObjCode. '-edit' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object) ){
			msgRf ( '�༭�ɹ���' );
		}
	}

	/**
	 * ��ʾ���䵽��
	 */
	function c_toAllot(){
        //URLȨ�޿���
        $this->permCheck();

		$incomeId = $_GET ['id'];
		$perm = isset($_GET['perm']) ? $_GET['perm'] : null;

		//��ȡ����Լ�������Ϣ
		$income = $this->service->getInfoAndDetail_d( $incomeId );

		//���ض������
		$thisObjCode = $this->service->getBusinessCode($income['formType']);

		//��ȡ�ӱ�����
		$incomeAllotRows = $income['incomeAllot'];
		unset($income['incomeAllot']);

		$this->assignFunc($income);

		if ( $perm == 'view') {
			$this->assign('incomeType',$this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionType',$this->getDataNameByCode($income['sectionType']));

			//��Ⱦ�ӱ�����
			$incomeStr = $this->service-> initAllot_d( $incomeAllotRows ,$perm );
			$this->assign ( 'incomeAllot', $incomeStr );
			$this->display ( $thisObjCode.'-viewallot' );
		} else {
			$this->assign('incomeTypeCN',$this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionTypeCN',$this->getDataNameByCode($income['sectionType']));
			$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ),$income['incomeType'] );
			$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ),$income['sectionType'] );
			//��Ⱦ�ӱ�����
			$incomeStr = $this->service-> initAllot_d( $incomeAllotRows );
			$this->assign ( 'incomeAllot', $incomeStr[0] );
			$this->assign ( 'countNumb', $incomeStr[1] );
			$this->display ( $thisObjCode.'-editallot' );
		}
	}

	/**
	 * �������
	 */
	function c_allot(){
		$rs = $this->service->allot_d($_POST[$this->objName]);
		if($rs){
			msgRf('����ɹ�');
		}else{
			msgRf('����ʧ��');
		}
	}

	/**
	 * ��������б�
	 */
	function c_allotList(){
		$this->display( 'allotlist' );
	}


	/**
	 * ��ȡ��ҳ����ת��Json--�������ҳ��
	 */
	function c_allotPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST );
		$service->asc = true;
		$rows = $service->pageBySqlId('select_incomeAllot');
        //URL����
        $rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��������б�
	 */
	 function c_manageList(){
	 	$this->display( 'managelist' );
	 }

	/**
	 * ��ȡ��ҳ����ת��Json--�������ҳ��
	 */
	function c_manageJson() {
		$service = $this->service;
		$service->getParam ( $_POST );

		$service->asc = true;
		$rows = $service->pageBySqlId('select_income');
        //URL����
        $rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/************************ excel���벿��*****************************/

     /**
	 *��ת��excel�ϴ�ҳ��
	 */
	function c_toExcel() {
		$this->display ('excel' );
	}

	/**
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		$resultArr = $this->service->addExecelData_d ($_POST['isCheck']);
		$title = '������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);

	}

	/**
	 * excel����
	 */
	function c_toExcOut(){
		$service = $this->service;

		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = 'c.incomeDate';
		$service->asc = false;
	    $rows = $service->list_d('select_excelout');
		return model_finance_common_financeExcelUtil::exportIncome ( $rows );
	}
}
?>