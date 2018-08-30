<?php

/**
 * �ͻ����Ʋ���
 */
class controller_customer_customer_customer extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "customer";
		$this->objPath = "customer_customer";
		parent::__construct ();
	}
	/**
	 * ��ת���ͻ���Ϣ�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 *
	 * ѡ��ͻ�
	 */
	function c_selectCustomers() {
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( 'select' );
	}

	/**
	 * �ͻ��ɱ༭������
	 */
	function c_editlist() {
		$this->view ( 'edit-list' );
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('TypeOne' => 'KHLX' ) ); //�ͻ����������ֵ�
		$this->assign ( 'createName', $_SESSION ['USERNAME'] );
		$this->assign ( 'createNameId', $_SESSION ['USER_ID'] );
		$this->assign ( 'CreateDT', date("Y-m-d") );
		$this->provArr = $this->service->province_d ();
		$this->softSelect ( $this->service->province_d (), 'Prov' );
		$this->view ( 'add' );
	}
	/**
	 * ��ת��������ϵ��ҳ��
	 */
	//	 function c_toAddLinkman(){
	//
	//
	//		$rows = $this->service->get_d($_GET['id']);
	//		foreach ($rows as $key => $val) {
	//			$this->assign($key, $val);
	//		}
	//		$TypeOne = $this->getDataNameByCode($rows['TypeOne']);
	//		$this->assign('TypeOne', $TypeOne);
	////      $rows = $this->service->get_d ( $_GET ['id'] );
	////		foreach ( $rows as $key => $val ) {
	////			$this->show->assign ( $key, $val );
	////		}
	//	 	   $this->view ( 'linkman-add' );
	//	 }


	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$customer = $_POST [$this->objName];
		$codeDao = new model_common_codeRule ();
		$customer ['objectCode'] = $codeDao->customerCode ( "customer", $customer ['TypeOne'], $customer ['CountryId'], $customer ['CityId'] );
		$id = $this->service->add_d ( $customer, $isAddInfo );
		$customer ['id'] = $id;
		if ($id) {
			echo "<script>window.returnValue='" . util_jsonUtil::encode ( $customer ) . "';</script>";
			msg ( '��ӳɹ���' );
		}

		//$this->listDataDict();
	}

	/**
	 * ��ת�༭ҳ��
	 */
	function c_init() {
		$id = $_GET ['id'];
		$rows = $this->service->get_d ( $id );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('TypeOne' => 'KHLX' ), $rows ['TypeOne'] );
		$this->softSelect ( $this->provArr, 'Prov', $rows ['Prov'] );
		try {
			$isRelated = $this->service->isCustomerRelated ( $id );
		} catch ( Exception $e ) {
			$isRelated = true;
		}
		$this->assign ( "isRelated", $isRelated );
		$this->assign ( "UpdateDT", date("Y-m-d") );
		$this->display ( 'edit' );
	}

	/**
	 * ��ת���¿ͻ�ҳ�棨���¿ͻ�������ҵ����Ϣ��
	 */
	function c_toUpdate() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'update' );
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/****
	 * ʡ�������б������Ϣ
	 * ****/
	function softSelect($object, $mark, $keyCode = null) {
		$str = "";
		if (is_array ( $object )) {
			foreach ( $object as $v ) {
				if ($v ['provinceName'] == $keyCode)
					$str .= '<option value="' . $v ['id'] . '" selected>';
				else
					$str .= '<option value="' . $v ['id'] . '">';
				$str .= $v ['provinceName'];
				$str .= '</option>';
			}
		}
		$this->assign ( $mark, $str );
	}
	/*
	 * �鿴ҳ��Tab
	 */
	function c_viewTab() {
		$this->permCheck (); //��ȫУ��
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}

	/***
	 * �鿴�ͻ�������Ϣ
	 */
	function c_readInfo() {
		$this->permCheck (); //��ȫУ��
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $isUsing = ($rows['isUsing'] == 1)? "����" : "�ر�";
        $this->assign ( "isUsing", $isUsing );

		$TypeOne = $this->getDataNameByCode ( $rows ['TypeOne'] );
		$regionDao = new model_system_region_region();
		$areaPrincipal = $regionDao ->find(array("id" => $rows['AreaId']),null,"areaPrincipal");
		$this->assign ( 'AreaLeaderNow', $areaPrincipal['areaPrincipal'] );

		$this->assign ( 'TypeOne', $TypeOne );
		$this->display ( 'read' );
	}

    /**
     * ��д��ȡ��ҳ����תJson����
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );

        // �ͻ�������Ϣ���й���,��ʡ�ݻ������Ϣ�滻Ϊ�ÿͻ��Ĺ�����Ϣ PM2468
        foreach($rows as $k => $v){
            $rows[$k]['Prov'] = ($v['Country'] != '�й�')? $v['Country'] : $v['Prov'];
            $rows[$k]['City'] = ($v['Country'] != '�й�')? $v['Country'] : $v['City'];

            // ����ͻ�����������
            $customerType = '';
            $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$v['TypeOne']}'";
            if($v['TypeOne'] != ''){
                $result = $this->service->_db->getArray($sql);
                $customerType = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
            }
            $rows[$k]['TypeOneName'] = $customerType;
        }

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
	 *
	 * �ϴ��ͻ���ϢEXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	/**
	 *
	 *����EXCEL���ϴ��ͻ���Ϣ
	 */
	function c_importCustomer() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_customer_customer_importCustomerUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
//			$objNameArr =  array(
//                 0 => 'province',//����ʡ��
//                 1 => 'city',//����
//                 2 => 'area',//��������
//                 3 => 'customrType',//�ͻ�����
//                 4 => 'customerName', //�ͻ�����
//
//            );
//            $objectArr = array ();
//				foreach ( $excelData as $rNum => $row ) {
//					foreach ( $objNameArr as $index => $fieldName ) {
//						$objectArr [$rNum] [$fieldName] = $row [$index];
//					}
//				}
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importCustomerInfo ( $excelData );

			if (is_array ( $resultArr ))
				echo util_excelUtil::showResult ( $resultArr, "�ͻ�������Ϣ������", array ("��?��?��", "���" ) );

			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 * ����Ȩ�޺ϲ�
	 */
	function regionMerge($privlimit, $arealimit) {
		$str = null;
		if (! empty ( $privlimit ) || ! empty ( $arealimit )) {
			if ($privlimit) {
				$str .= $privlimit;
			}
			if (! empty ( $str ) && ! empty ( $arealimit )) {
				$str .= ',' . $arealimit;
			} else {
				$str = $arealimit;
			}
			return $str;
		} else {
			return null;
		}
	}

	/**
	 * �ͻ��б�PageJson
	 * by Liub
	 */

	function c_cusPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		$userId = $_SESSION ['USER_ID'];
		$updateLimit = isset ( $this->service->this_limit ['����'] ) ? $this->service->this_limit ['����'] : null;
		$privlimit = isset ( $this->service->this_limit ['�ͻ�����'] ) ? $this->service->this_limit ['�ͻ�����'] : null; //�ⲿ����
		$linkmanDao = new model_customer_linkman_linkman ();
		$arealimit = $linkmanDao->getAreaIds_d (); //�ڲ�����
		$thisAreaLimit = $this->regionMerge ( $privlimit, $arealimit );

		$flag = $service->customerRows ( $privlimit, $thisAreaLimit, $userId );
		if($flag == '1'){
            $service->searchArr ['AreaId'] = $privlimit;
            $service->searchArr ['SellManIdO'] = $userId;
		}else if($flag == '2'){
            $service->searchArr ['SellManId'] = $userId;
		}else if($flag == '3'){
            $service->searchArr ['AreaId'] = $thisAreaLimit;
            $service->searchArr ['SellManIdO'] = $userId;
		}
		$rows = $service->pageBySqlId ( 'select_customer' );
		foreach($rows as $k => $v){
             $rows[$k]['updateLimit'] = $updateLimit;
		}
		//		//$service->asc = false;
		//		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �ϲ��ͻ���Ϣ
	 */
	function c_mergerCustomer() {
		try {
			$tag = $this->service->mergerCustomer ( $_POST ['objectCode'], $_POST ['mergerIdArr'] );
			if ($tag == true) {
				echo $tag;
			}
		} catch ( Exception $e ) {
			echo util_jsonUtil::iconvGB2UTF ( $e->getMessage () );
		}
	}

	/**
	 * @ ajax�ж��� ��֤�ͻ������Ƿ��ظ�
	 *
	 */
	function c_ajaxCustomerName() {
		$service = $this->service;
		$cusName = isset ( $_GET ['customerName'] ) ? $_GET ['customerName'] : false;
		$searchArr = array ("ajaxCusName" => $cusName );
		$isRepeat = $service->isRepeat ( $searchArr, "" );

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * ��ȡ�ͻ�����ҵ�����
	 */
	function c_getCustomerRelationArr() {
		include_once ("model/customer/customer/customerRelationTableArr.php");
		echo util_jsonUtil::encode ( $customerRelationTableArr );
	}

	/**
	 * ���¿ͻ����ҵ����Ϣ
	 */
	function c_updateCustomer() {
		$customer = $_POST ['customer'];
		$relationArr = $_POST ['checked']; //ѡ�и��µ�ҵ�����
		if ($_POST ['updateType'] == 1) {
			$this->service->updateCustomer ( $customer, $relationArr );
		} else {
			$this->service->updateBusCustomerIdByName ( $customer , $relationArr );
		}
		msg ( '���³ɹ���' );
	}

	/**
	 * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
	 */
	function c_ajaxdeletes() {
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo util_jsonUtil::iconvGB2UTF ( $e->getMessage () );
		}
	}

	/**
	 * ��ת���ͻ����ҳ��
	 */
	function c_toViewRelation(){
		$msg=$this->service->customerRelateMsg($_GET['id']);
		$this->assign ( 'msg', $msg );
		$this->view ( "relation" );
	}

	/**
	 * �������пͻ�����
	 */
	function c_updateCustomersCode(){
		echo $this->service->updateCustomersCode();
	}

	/**
	 * �ͻ�Ȩ�޲�ѯ
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}
	/**
	 * ajax���ݿͻ�ID��ȡ�ͻ���Ϣ By LiuB
	 */
     function c_getCusInfo(){
          $id = $_POST['id'];
         $service = $this->service;
         $service->searchArr['id'] = $id;
//		 $rows = $service->list_d("select_customer");
		 $rows = $service->list_d("select_customer1");// ��������µ�������Ϣ(ID2232 2016-11-18)

         $datadictDao = new model_system_datadict_datadict ();
         foreach ($rows as $k => $v){
             $rows[$k]['TypeOneName'] =  ($v['TypeOne'] != '')? $datadictDao->getDataNameByCode ( $v['TypeOne'] ) : '';
         }

         echo util_jsonUtil::encode ( $rows );
     }

    /**
     * ���¿ͻ�ʹ��״̬
     */
     function c_updateUsingState(){
         $id = $_REQUEST['id'];
         $newVal = $_REQUEST['newVal'];

         $result = $this->service->updateById(array('id'=>$id,'isUsing'=>$newVal));
         echo util_jsonUtil::encode ( $result );
     }
}
?>