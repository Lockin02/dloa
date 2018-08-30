<?php
/**
 * 数据字典控制层类
 */
class controller_system_datadict_datadict extends controller_base_action {

	function __construct() {
		$this->objName = "datadict";
		$this->objPath = "system_datadict";
		$this->fieldsArr = array ("dataName", "parentName", "orderNum", "orderNum" );
		parent::__construct ();
	}



	/**
	 * 跳转到框架页面
	 */

	function c_treelist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
//		$this->service->filterFunc();
       $rowsId = $_GET['parentId'];
       $this->assign('parentId' , $_GET['parentId']);
       $this->show->assign("id" , $_GET['parentId']);
       $this->service->searchArr = array('id'=>$rowsId);
       $rows = $this->service->listBySqlId('select_parentName');

       if( empty ( $rows)){

            $this->show->assign('dataName', '根节点');
            $this->assign('parentId' , '-1');
       }else{
       	   $this->show->assign('dataName',$rows[0]['dataName']);
       }

//       $this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
       $this->view('add', true, true);
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
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
	 * 构造树
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
	 * 重写PageJson
	 */
	 function c_DatadictPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
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
	 * 根据上级获取数据字典列表（一般用于构造树）
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
		//把是否叶子值0转成false，1转成true
		function toBoolean($row) {
			$row ['leaf'] = $row ['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil::encode ( array_map ( "toBoolean", $rows ) );
		//return json_encode($rows);
	}

	/**
	 * 根据上级Code获取数据字典列表（一般用于构造树）
	 */
	function c_listByParentCode() {
		//		$page = new model_base_param();
		$isRoot=$_GET['isRoot'];//是否需要构造根节点
		$id=$_POST['id'];

//		if($isRoot==1&&empty($id)){
//			$rows=array(array(id=>"root",dataName=>"全选",isParent=>1,));
//			echo util_jsonUtil::encode ( $rows );
//		}else if($id=='root'||empty($isRoot)){
			$searchArr = array ("parentCode" => $_GET ['parentCode'] );
			//		$page->__SET('searchArr', $searchArr);
			//		$page->__SET('sort', 'orderNum');
			$service = $this->service;
			$service->searchArr = $searchArr;
			$service->sort = 'orderNum,id';
			$rows = $service->list_d ();
			//把是否叶子值0转成false，1转成true
			function toBoolean($row) {
				$row ['leaf'] = $row ['leaf'] == 0 ? false : true;
				return $row;
			}
			echo util_jsonUtil::encode ( $this->addRoot(array_map ( "toBoolean", $rows ),'dataName') );
		//}

		//return json_encode($rows);
	}

	/*
	 * 根据Code返回对应的value
	 */
	function c_getDataJsonByCode() {
		$datas = $this->service->getDataNameByCode ( $_GET ['code'] );
		echo util_jsonUtil::iconvGB2UTF ( $datas );
	}
	/*
	 * 根据多个上级编码获取下属数据字典信息
	 */
	function c_getDataJsonByCodes() {
		$datas = $this->service->getDatadictsByParentCodes ( $_POST ['codes'] );
		echo util_jsonUtil::encode ( $datas );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonDesc() {
		$service = $this->service;
		$service->getParam ( $_REQUEST ); //设置前台获取的参数信息
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
		 * @ ajax判断项
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
	 * 获取分页数据转成Json
	 */
	function c_orderNaturePageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );

		$service->searchArr['parentCodes'] = "HTLX-XSHT,HTLX-FWHT,HTLX-ZLHT,HTLX-YFHT";
		$service->asc = false;

		$rows=$service->listBySqlId('select_datadict');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

   /**
    * 客户类型 下拉多选树
    */
     function c_getCustomerType(){
		$service = $this->service;

		$service->searchArr['parentCodes'] = "KHLX";
		$service->asc = false;

		$rows=$service->listBySqlId('select_datadict');
      //构造数组
      $arr = $this->handleArr($rows);

		echo util_jsonUtil :: encode ($arr);
	}
	//整理数组
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
           "name" => "客户类型",
           "code" => "",
           "parentId" => "",
           "nodes" => $Arra

       );
       return $arr;
	}

	/**
	 * 是否启用设备
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
	 * 获取数据 -- 用于easyui
	 */
	function c_ajaxGetForEasyUI(){
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_foreasyui');

		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * 更新海外产品信息
	 */
	 function c_getOAPro(){
	 	$typeArr = $this->service->getOAPro_d();
		return util_jsonUtil :: iconvGB2UTFArr ($typeArr);
	 }

	 	/**
	 * 更新海外物料信息
	 */
	 function c_getOAMaterial(){
	 	$materialArr = $this->service->getOAMaterial_d();
		return util_jsonUtil :: iconvGB2UTFArr ($materialArr);
	 }
}
?>