<?php
/**
 * �����ֵ���Ʋ���
 */
class controller_system_datadict_datadict extends controller_base_action {

	function __construct() {
		$this->objName = "datadict";
		$this->objPath = "system_datadict";
		$this->fieldsArr = array ("dataName", "parentName", "orderNum", "orderNum" );
		parent::__construct ();
	}



	/**
	 * ��ת�����ҳ��
	 */

	function c_treelist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
//		$this->service->filterFunc();
       $rowsId = $_GET['parentId'];
       $this->assign('parentId' , $_GET['parentId']);
       $this->show->assign("id" , $_GET['parentId']);
       $this->service->searchArr = array('id'=>$rowsId);
       $rows = $this->service->listBySqlId('select_parentName');

       if( empty ( $rows)){

            $this->show->assign('dataName', '���ڵ�');
            $this->assign('parentId' , '-1');
       }else{
       	   $this->show->assign('dataName',$rows[0]['dataName']);
       }

//       $this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
       $this->view('add', true, true);
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

			$this->showDatadicts(array('module' => 'HTBK'), $obj['module']);
			$this->display ('edit',true);
		}
	}


	/**
	 * ������
	 */
	   function c_getChildren(){
		$service = $this->service;

		if(!empty($_GET ['parentCode'])){
			if(empty($_POST['id'])){
				$service->searchArr = array ("parentCode" => $_GET ['parentCode'] );
			}else{
				$service->searchArr['parentId'] = $_POST['id'];
			}
		}else{
			$parentId = isset($_POST['id'])? $_POST['id'] : -1;
			$service->searchArr['parentId'] = $parentId;
		}
		$service->asc = false;

		$rows=$service->listBySqlId('select_treelist');
//        echo "<pre>";
//        print_r($rows);
		echo util_jsonUtil :: encode ($rows);
	}
	/**
	 * ��дPageJson
	 */
	 function c_DatadictPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;

		$rows = $service->pageBySqlId('select_grid');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
//		print_r ($arr);
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * �����ϼ���ȡ�����ֵ��б�һ�����ڹ�������
	 */
	function c_listByParent() {
		//		$page = new model_base_param();
		if(!empty($_GET ['parentCode'])){
			$searchArr = array ("parentCode" => $_GET ['parentCode'] );
		}else{
			$searchArr = array ("parentId" => $_GET ['parentId'] );
		}
		//		$page->__SET('searchArr', $searchArr);
		//		$page->__SET('sort', 'orderNum');
		$service = $this->service;
		$service->searchArr = $searchArr;
		$service->sort = 'orderNum';
		$rows = $service->list_d ();
		//���Ƿ�Ҷ��ֵ0ת��false��1ת��true
		function toBoolean($row) {
			$row ['leaf'] = $row ['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil::encode ( array_map ( "toBoolean", $rows ) );
		//return json_encode($rows);
	}

	/**
	 * �����ϼ�Code��ȡ�����ֵ��б�һ�����ڹ�������
	 */
	function c_listByParentCode() {
		//		$page = new model_base_param();
		$isRoot=$_GET['isRoot'];//�Ƿ���Ҫ������ڵ�
		$id=$_POST['id'];

//		if($isRoot==1&&empty($id)){
//			$rows=array(array(id=>"root",dataName=>"ȫѡ",isParent=>1,));
//			echo util_jsonUtil::encode ( $rows );
//		}else if($id=='root'||empty($isRoot)){
			$searchArr = array ("parentCode" => $_GET ['parentCode'] );
			//		$page->__SET('searchArr', $searchArr);
			//		$page->__SET('sort', 'orderNum');
			$service = $this->service;
			$service->searchArr = $searchArr;
			$service->sort = 'orderNum,id';
			$rows = $service->list_d ();
			//���Ƿ�Ҷ��ֵ0ת��false��1ת��true
			function toBoolean($row) {
				$row ['leaf'] = $row ['leaf'] == 0 ? false : true;
				return $row;
			}
			echo util_jsonUtil::encode ( $this->addRoot(array_map ( "toBoolean", $rows ),'dataName') );
		//}

		//return json_encode($rows);
	}

	/*
	 * ����Code���ض�Ӧ��value
	 */
	function c_getDataJsonByCode() {
		$datas = $this->service->getDataNameByCode ( $_GET ['code'] );
		echo util_jsonUtil::iconvGB2UTF ( $datas );
	}
	/*
	 * ���ݶ���ϼ������ȡ���������ֵ���Ϣ
	 */
	function c_getDataJsonByCodes() {
		$datas = $this->service->getDatadictsByParentCodes ( $_POST ['codes'] );
		echo util_jsonUtil::encode ( $datas );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonDesc() {
		$service = $this->service;
		$service->getParam ( $_REQUEST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;
		$service->sort = 'orderNum';
        if($_REQUEST['parentCode'] == 'KHLX'){
        	$userId = $_SESSION['USER_ID'];
        	$userDeptId = $_SESSION['DEPT_ID'];
        	$this->service->groupBy=" c.dataName ";
        	//$this->service->searchArr['myCondition']="sql: and FIND_IN_SET('".$userId."',s.salesManIds)";
        	$this->service->searchArr['myCondition']="sql:
        	and  ( (  FIND_IN_SET('".$userId."',s.salesManIds)  and  ".$userDeptId." in( 37,123 ,1 ) )
        	or ".$userDeptId." not in ( 37,123 ,1 )or  '".$userId."' in ('yingchao.zhang')
 )";
            $rows = $service->pageBySqlId('select_KHLX');

        }else{
        	$rows = $service->page_d ();
        }
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


		/**
		 * @ ajax�ж���
		 *
		 */
	    function c_ajaxDataCode() {
	    	$service = $this->service;
			$projectName = isset ( $_GET ['dataCode'] ) ? $_GET ['dataCode'] : false;

			$searchArr = array ("dataCode" => $projectName );

			$isRepeat =$service->isRepeat ( $searchArr, "" );

			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}



	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_orderNaturePageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );

		$service->searchArr['parentCodes'] = "HTLX-XSHT,HTLX-FWHT,HTLX-ZLHT,HTLX-YFHT";
		$service->asc = false;

		$rows=$service->listBySqlId('select_datadict');
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
    * �ͻ����� ������ѡ��
    */
     function c_getCustomerType(){
		$service = $this->service;

		$service->searchArr['parentCodes'] = "KHLX";
		$service->asc = false;

		$rows=$service->listBySqlId('select_datadict');
      //��������
      $arr = $this->handleArr($rows);

		echo util_jsonUtil :: encode ($arr);
	}
	//��������
	function handleArr($rows){
		$Arra = array();
	   foreach($rows as $key => $val){
          $Arra[$key]['id'] = $val['dataCode'];
          $Arra[$key]['name'] = $val['dataName'];
          $Arra[$key]['code'] = $val['dataCode'];
          $Arra[$key]['parentId'] = "-1";
	   }
       $arr = array(

           "id" => "-1",
           "name" => "�ͻ�����",
           "code" => "",
           "parentId" => "",
           "nodes" => $Arra

       );
       return $arr;
	}

	/**
	 * �Ƿ������豸
	 */
	function c_ajaxUseStatus(){
		try{
			$this->service->ajaxUseStatus_d($_POST ['id'],$_POST ['useStatus']);
			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	}

	/**
	 * ��ȡ���� -- ����easyui
	 */
	function c_ajaxGetForEasyUI(){
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_foreasyui');

		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * ���º����Ʒ��Ϣ
	 */
	 function c_getOAPro(){
	 	$typeArr = $this->service->getOAPro_d();
		return util_jsonUtil :: iconvGB2UTFArr ($typeArr);
	 }

	 	/**
	 * ���º���������Ϣ
	 */
	 function c_getOAMaterial(){
	 	$materialArr = $this->service->getOAMaterial_d();
		return util_jsonUtil :: iconvGB2UTFArr ($materialArr);
	 }
}
?>