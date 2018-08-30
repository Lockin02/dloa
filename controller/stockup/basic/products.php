<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:05:02
 * @version 1.0
 * @description:������Ʒ��Ϣ����Ʋ�
 */
class controller_stockup_basic_products extends controller_base_action {

	function __construct() {
		$this->objName = "products";
		$this->objPath = "stockup_basic";
		parent::__construct ();
	 }

	/**
	 * ��ת��������Ʒ��Ϣ���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������������Ʒ��Ϣ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add',true);
   }
	/**
	 * �����ύ��֤
	 */
	 function c_add($isAddInfo = false) {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object);
		if ($id) {
            msg("����ɹ�");
		} else {
			msg("����ʧ��");
		}
	}
   /**
	 * ��ת���༭������Ʒ��Ϣ��ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit',true);
   }
   /**
    * �༭�ύ��֤
    */
    function c_edit(){
    	$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
    	$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object);
		if ($rs) {
            msg("����ɹ�");
		} else {
			msg('����ʧ��');
		}
    }
     /**
	 * �������
	 */
   	function c_pageSelect(){
   		$this->view('listselect');
	}
	/**
	 * �رտ���
	 */
	 function c_updateStatus(){
	   	if($_POST ['id']&&$_POST ['flag']){
	   		if($this->service->updateObjStatus((int)$_POST ['id'],'isClose',(int)$_POST ['flag'])==true){
	   			echo 1;
	   		}else{
	   			echo 2;
	   		}
	   	}
   }
   /**
	 * ��񷽷�
	 */
	function c_jsonSelect(){
		$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$projectName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] :  $_POST['productName'];
		$this->service->searchArr['productSearch'] = $projectName;
		$rows = $service->pageBySqlId('pageSelect');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
   /**
	 * ��ת���鿴������Ʒ��Ϣ��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			if($key=='isClose'&&$val=='1'){
				$this->assign ( $key, '����' );
			}elseif($key=='isClose'&&$val=='2'){
				$this->assign ( $key, '�ر�' );
			}else{
				$this->assign ( $key, $val );
			}

		}
      $this->view ( 'view' );
   }
         /**
		 * @ ajax�ж���
		 *
		 */
	    function c_ajaxProductName() {
	    	$service = $this->service;
			$projectName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] : false;

			$searchArr = array ("productName" => $projectName );

			$isRepeat =$service->isRepeat ( $searchArr, "" );

			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}
		/**
		 * @ ajax�ж���
		 *
		 */
	    function c_ajaxProductCode() {
	    	$service = $this->service;
			$projectName = isset ( $_GET ['productCode'] ) ? $_GET ['productCode'] : false;

			$searchArr = array ("productCode" => $projectName );

			$isRepeat =$service->isRepeat ( $searchArr, "" );

			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}


	/*********************** ��־���� **********************/
	/**
	 * ��־����
	 */
	function c_toEportExcelIn(){
		$this->display('excelin');
	}

	/**
	 * ��־����
	 */
	function c_eportExcelIn(){
		$resultArr = $this->service->eportExcelIn_d ();
		$title = '�����������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


	/*********************** ��־���� **********************/
	/**
	 * ��־����
	 */
	function c_toOutExcel(){
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
		$beginDate = $weekBEArr['beginDate'];
		$endDate = $weekBEArr['endDate'];
		$this->assign('beginDate',$beginDate);
		$this->assign('endDate',$endDate);
		$this->assign('deptIds',$_GET['deptIds']);
		$this->view('outExcel');
	}

	/**
	 * ��־��������
	 */
	function c_exportSearhDeptJson(){
		set_time_limit(0);
		$service = $this->service;
		$deptIds = $_GET['deptIds'];
		unset($_GET['deptIds']);
		if(!empty($deptIds) && !empty($_GET['deptId'])){//�жϴ���Ȩ��
			$_GET['deptId'] = implode(',',array_intersect(explode(',',$_GET['deptId']),explode(',',$deptIds)));
		}elseif(!empty($deptIds) && empty($_GET['deptId'])){
			$_GET['deptId'] = $deptIds;
		}
//		print_r($_GET);die();
		$service->getParam ( $_GET );
		$service->sort = 'c.executionDate asc,c.deptId asc,c.createId';
		$rows = $service->list_d ();
		//�����ͷ
		$thArr = array('createName' => '��д��','executionDate' => '����','projectCode' => '��Ŀ���',
			'activityName' => '����', 'workloadDay' => '������', 'workloadUnitName' => '��λ', 'thisActivityProcess' =>'�����չ%',
			'thisProjectProcess' => '��Ŀ��չ%', 'processCoefficient' => '��չϵ��',
			'inWorkRate' => '�˹�Ͷ��ռ��%', 'workCoefficient' => '����ϵ��', 'costMoney' => '����', 'description' => '�������',
			'assessResultName' => '���˽��', 'feedBack' => '�ظ�', 'deptName' =>'����'
		);

		model_engineering_util_esmexcelutil::exportSearchDept($thArr,$rows,'������־');
	}

 }
?>