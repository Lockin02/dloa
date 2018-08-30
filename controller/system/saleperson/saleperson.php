<?php
/**
 * @author Administrator
 * @Date 2012-12-24 14:48:23
 * @version 1.0
 * @description:���۸����˹�����Ʋ�
 */
class controller_system_saleperson_saleperson extends controller_base_action {

	function __construct() {
		$this->objName = "saleperson";
		$this->objPath = "system_saleperson";
		parent::__construct ();
	 }

	/*
	 * ��ת�����۸����˹����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���������۸����˹���ҳ��
	 */
	function c_toAdd() {
	 $this->assign('businessBelong',$_SESSION['COM_BRN_PT']);
	 $this->assign('businessBelongName',$_SESSION['COM_BRN_CN']);
	 $this->assign('formBelong',$_SESSION['COM_BRN_PT']);
     $this->assign('formBelongName',$_SESSION['COM_BRN_CN']);
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭���۸����˹���ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $obj['customerType']);
		$this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���۸����˹���ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isDirector'] == '0'){
			$obj['isDirector'] = "��";
		}else{
			$obj['isDirector'] = "��";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
   }
   /*
    * �鿴--���ݸ�����
    */

   function c_toViewall() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isDirector'] == '0'){
			$obj['isDirector'] = "��";
		}else{
			$obj['isDirector'] = "��";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $this->assign("ids",$_GET['ids']);
		$this->view ( 'viewall' );
   }
   /*
    * �༭--���ݸ�����
    */

   function c_toEditall() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isDirector'] == '0'){
			$obj['isDirector'] = "��";
		}else{
			$obj['isDirector'] = "��";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $this->assign("ids",$_GET['ids']);
		$this->view ( 'editall' );
   }
	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}
	/**
	 * �޸Ķ���-- ���ݸ�����
	 */
	function c_editall($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->editall_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}


  /**
   * �ϲ��鿴�б�
   */
  function c_mergeList(){
     $this->view("mergelist");
  }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_mergeJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$service->groupBy = 'c.personName,c.businessBelongName';
		$service->searchArr['isUse'] = "0";
		$rows = $service->pageBySqlId('select_merge');
	    //����ϲ��������
	   foreach($rows as $key => $val){
	   	 $rows[$key]['country'] = implode(",",array_flip(array_flip(explode(",",$val['country']))));
	   	 $rows[$key]['province'] = implode(",",array_flip(array_flip(explode(",",$val['province']))));
	   	 $rows[$key]['city'] = implode(",",array_flip(array_flip(explode(",",$val['city']))));
	   	 $rows[$key]['customerTypeName'] = implode(",",array_flip(array_flip(explode(",",$val['customerTypeName']))));
	   }
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ����ʡ�� - ���� - �ͻ�����ȷ�Ϸ��ù�����
	 */
	function c_getSalePerson(){
		$province = util_jsonUtil::iconvUTF2GB($_POST['province']);
		$city = util_jsonUtil::iconvUTF2GB($_POST['city']);
		$customerTypeName = util_jsonUtil::iconvUTF2GB($_POST['customerTypeName']);

		//��ȡ���ù�����
		$rs = $this->service->getDirector_d($province,$city,$customerTypeName);
		if($rs){
			echo util_jsonUtil::encode($rs);
		}else{
			echo "";
		}
		exit();
	}
	/*
	 * ajax ���ݸ�����ɾ������
	 */
	function c_ajaxdeletesPerson() {
		try {
			$this->service->deletesAll_d ( $_POST ['personId'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * ����ʡ�� - ���� ��ѯ���۸�����
	 */
	function c_getPersonByProvince(){
		$province = util_jsonUtil::iconvUTF2GB($_POST['province']);

		//��ȡ���ù�����
		$rs = $this->service->getPersonByProvince_d($province);
		if($rs){
			echo util_jsonUtil::encode($rs);
		}else{
			echo "";
		}
		exit();
	}

/*************************����***********************************************/

	/**
	 *��ת��excel�ϴ�ҳ��
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel');
	}
	/**
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		$objNameArr = array (
		    0 => 'areaName', //��������
			1 => 'personName', //����������
			2 => 'deptName', //�����˲���
			3 => 'country', //����
			4 => 'province', //ʡ��
			5 => 'city', //����
			6 => 'businessBelongName',//������˾
			7 => 'customerTypeName', //�ͻ�����
			8 => 'isUse', //�Ƿ�����
			9 => 'isDirector' //��ҵ�ܼ�
		);
		$this->c_addExecel($objNameArr);
	}

	/**
	 * �ϴ�EXCEl������������
	 */
	function c_addExecel($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ

			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}

				$isUseArr = array("����"=>"0","����"=>"1");//�Ƿ�����
				$isDirectorArr = array("��"=>"0","��"=>"1");//�Ƿ�����
				$arrinfo = array();//��������
                //ѭ�������������
                foreach($objectArr as $key => $val){
                   $personArr = $this->service->userArr($val['personName']);
                   $DirectorArr = $this->service->userArr($val['areaName']);
                    if(empty($personArr)){
                      array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ��,���۸����˲�����" ) );
                    }else{
                        $objectArr[$key]['personId'] = $personArr['USER_ID'];
                        $objectArr[$key]['deptName'] = $personArr['DEPT_NAME'];
                        $objectArr[$key]['deptId'] = $personArr['DEPT_ID'];

                        $objectArr[$key]['areaNameId'] = $personArr['USER_ID'];

                        //�жϹ���
                        $countryId = $this->service->isArea("country",$val['country']);
                        if(empty($countryId)){
                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ��,���Ҵ���" ) );
                        }else{
                        	$objectArr[$key]['countryId'] = $countryId;
                        	$provinceId = $this->service->isArea("province",$val['province']);
	                        if(empty($provinceId) && $val['province'] != "ȫ��"){
	                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ��,ʡ�ݴ���" ) );
	                        }else{
	                        	$objectArr[$key]['provinceId'] = $provinceId;
	                        	$cityId = $this->service->isArea("city",$val['city'],$provinceId);
		                        if(empty($cityId)  && $val['city'] != "ȫ��"){
		                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ��,���д���" ) );
		                        }else{
		                        	if($val['city'] == "ȫ��"){
		                        		$objectArr[$key]['city'] = $this->service->allCity($cityId);
		                        	}
		                        	$objectArr[$key]['cityId'] = $cityId;
		                        	//�ͻ�����
		                        	$customerType = $this->service->customerTypeStr($val['customerTypeName']);
			                        if(empty($customerType)){
				                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ��,�ͻ����ʹ���" ) );
				                    }else{
				                    	$objectArr[$key]['customerType'] = $customerType;

				                    	$obj = $this->service->businessBelongNameStr($val['businessBelongName']);
				                    	 $businessBelongName = $obj['NameCN'];
				                    	 $businessBelong = $obj['NamePT'];
				                    	if(empty($businessBelongName)){
				                    		array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ��,������˾������" ) );
				                    	}
				                    	else{
				                    		$objectArr[$key]['businessBelongName']= $businessBelongName;
				                    		$objectArr[$key]['businessBelong'] = $businessBelong;
				                    		$isUse = $val['isUse'];
				                    		$isDirector = $val['isDirector'];
				                    		$objectArr[$key]['isUse'] = $isUseArr[$isUse];
				                    		$objectArr[$key]['isDirector'] = $isDirectorArr[$isDirector];

				                    		$customerTypeNameStr = explode(",", $val['customerTypeName']);
				                    		$customerTypeNames = "";
				                    		foreach ($customerTypeNameStr as $k =>$v){
				                    			$customerTypeNames .= "'".$v."',";
				                    		}
				                    		$customerTypeNames = rtrim($customerTypeNames, ",");
	                                       //�ж��Ƿ����ظ�����
							       	 		$sql = "select count(*) as num from oa_system_saleperson where " .
							       	 			   "province = '".$val['province']."' and city = '".$val['city']."'" .
							       	 			   " and customerTypeName in (".$customerTypeNames.")".
							       	 			   " and businessBelongName = '".$val['businessBelongName']."'";
							       	 	   $isRepeatArr = $this->service->_db->getArray($sql);

							             if($isRepeatArr[0]['num'] != '0' && $val['province'] != "ȫ��" && $val['city'] != "ȫ��" ){
							             	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ�ܣ�������ͬ��ʡ/��/����/��˾" ) );
							             }else{
							             	$add = $this->service->importAdd_d($objectArr[$key]);
					                        if($add){
					                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ɹ���" ) );
					                        }else{
					                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "����ʧ�ܣ�" ) );
					                        }
							              }
				                    	}
				                    }
		                        }
	                        }
                        }
                    }
                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "������", array ("����������","ʡ���У�", "���" ) );
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}

/*************************����******end*****************************************/
 }
?>