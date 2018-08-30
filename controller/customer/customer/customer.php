<?php

/**
 * 客户控制层类
 */
class controller_customer_customer_customer extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "customer";
		$this->objPath = "customer_customer";
		parent::__construct ();
	}
	/**
	 * 跳转到客户信息列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 *
	 * 选择客户
	 */
	function c_selectCustomers() {
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( 'select' );
	}

	/**
	 * 客户可编辑表格测试
	 */
	function c_editlist() {
		$this->view ( 'edit-list' );
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('TypeOne' => 'KHLX' ) ); //客户类型数据字典
		$this->assign ( 'createName', $_SESSION ['USERNAME'] );
		$this->assign ( 'createNameId', $_SESSION ['USER_ID'] );
		$this->assign ( 'CreateDT', date("Y-m-d") );
		$this->provArr = $this->service->province_d ();
		$this->softSelect ( $this->service->province_d (), 'Prov' );
		$this->view ( 'add' );
	}
	/**
	 * 跳转到新增联系人页面
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
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$customer = $_POST [$this->objName];
		$codeDao = new model_common_codeRule ();
		$customer ['objectCode'] = $codeDao->customerCode ( "customer", $customer ['TypeOne'], $customer ['CountryId'], $customer ['CityId'] );
		$id = $this->service->add_d ( $customer, $isAddInfo );
		$customer ['id'] = $id;
		if ($id) {
			echo "<script>window.returnValue='" . util_jsonUtil::encode ( $customer ) . "';</script>";
			msg ( '添加成功！' );
		}

		//$this->listDataDict();
	}

	/**
	 * 跳转编辑页面
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
	 * 跳转更新客户页面（更新客户关联的业务信息）
	 */
	function c_toUpdate() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'update' );
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			msg ( '编辑成功！' );
		}
	}

	/****
	 * 省份下拉列表城市信息
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
	 * 查看页面Tab
	 */
	function c_viewTab() {
		$this->permCheck (); //安全校验
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}

	/***
	 * 查看客户基本信息
	 */
	function c_readInfo() {
		$this->permCheck (); //安全校验
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $isUsing = ($rows['isUsing'] == 1)? "开启" : "关闭";
        $this->assign ( "isUsing", $isUsing );

		$TypeOne = $this->getDataNameByCode ( $rows ['TypeOne'] );
		$regionDao = new model_system_region_region();
		$areaPrincipal = $regionDao ->find(array("id" => $rows['AreaId']),null,"areaPrincipal");
		$this->assign ( 'AreaLeaderNow', $areaPrincipal['areaPrincipal'] );

		$this->assign ( 'TypeOne', $TypeOne );
		$this->display ( 'read' );
	}

    /**
     * 重写获取分页数据转Json方法
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );

        // 客户国家信息非中国的,把省份或城市信息替换为该客户的国家信息 PM2468
        foreach($rows as $k => $v){
            $rows[$k]['Prov'] = ($v['Country'] != '中国')? $v['Country'] : $v['Prov'];
            $rows[$k]['City'] = ($v['Country'] != '中国')? $v['Country'] : $v['City'];

            // 补充客户类型中文名
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
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 *
	 * 上传客户信息EXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	/**
	 *
	 *导入EXCEL中上传客户信息
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
//                 0 => 'province',//所属省份
//                 1 => 'city',//地区
//                 2 => 'area',//所属区域
//                 3 => 'customrType',//客户性质
//                 4 => 'customerName', //客户名称
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
				echo util_excelUtil::showResult ( $resultArr, "客户基本信息导入结果", array ("客?裘?称", "结果" ) );

			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 * 区域权限合并
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
	 * 客户列表PageJson
	 * by Liub
	 */

	function c_cusPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		$userId = $_SESSION ['USER_ID'];
		$updateLimit = isset ( $this->service->this_limit ['更新'] ) ? $this->service->this_limit ['更新'] : null;
		$privlimit = isset ( $this->service->this_limit ['客户区域'] ) ? $this->service->this_limit ['客户区域'] : null; //外部调配
		$linkmanDao = new model_customer_linkman_linkman ();
		$arealimit = $linkmanDao->getAreaIds_d (); //内部调配
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
	 * 合并客户信息
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
	 * @ ajax判断项 验证客户名称是否重复
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
	 * 获取客户关联业务对象
	 */
	function c_getCustomerRelationArr() {
		include_once ("model/customer/customer/customerRelationTableArr.php");
		echo util_jsonUtil::encode ( $customerRelationTableArr );
	}

	/**
	 * 更新客户相关业务信息
	 */
	function c_updateCustomer() {
		$customer = $_POST ['customer'];
		$relationArr = $_POST ['checked']; //选中更新的业务对象
		if ($_POST ['updateType'] == 1) {
			$this->service->updateCustomer ( $customer, $relationArr );
		} else {
			$this->service->updateBusCustomerIdByName ( $customer , $relationArr );
		}
		msg ( '更新成功！' );
	}

	/**
	 * ajax方式批量删除对象（应该把成功标志跟消息返回）
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
	 * 跳转到客户检测页面
	 */
	function c_toViewRelation(){
		$msg=$this->service->customerRelateMsg($_GET['id']);
		$this->assign ( 'msg', $msg );
		$this->view ( "relation" );
	}

	/**
	 * 更新所有客户编码
	 */
	function c_updateCustomersCode(){
		echo $this->service->updateCustomersCode();
	}

	/**
	 * 客户权限查询
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}
	/**
	 * ajax根据客户ID获取客户信息 By LiuB
	 */
     function c_getCusInfo(){
          $id = $_POST['id'];
         $service = $this->service;
         $service->searchArr['id'] = $id;
//		 $rows = $service->list_d("select_customer");
		 $rows = $service->list_d("select_customer1");// 添加了最新的区域信息(ID2232 2016-11-18)

         $datadictDao = new model_system_datadict_datadict ();
         foreach ($rows as $k => $v){
             $rows[$k]['TypeOneName'] =  ($v['TypeOne'] != '')? $datadictDao->getDataNameByCode ( $v['TypeOne'] ) : '';
         }

         echo util_jsonUtil::encode ( $rows );
     }

    /**
     * 更新客户使用状态
     */
     function c_updateUsingState(){
         $id = $_REQUEST['id'];
         $newVal = $_REQUEST['newVal'];

         $result = $this->service->updateById(array('id'=>$id,'isUsing'=>$newVal));
         echo util_jsonUtil::encode ( $result );
     }
}
?>