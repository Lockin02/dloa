<?php
/**
 * 联系人控制层类
 */
class controller_customer_linkman_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "customer_linkman";
		parent::__construct ();
	}
	/*
      * 客户联系人列表
      */
	function c_linkmanInfo() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listinfo' );
	}

     /**
      * 客户联系人导入 by Liub
      */
    function c_toUplod(){
        $this->display("upload");
    }
    /**
     * 导入Excel by  Liub
     */
    function c_importExcel() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_customer_customer_importCustomerUtil ();
			$excelData = $dao->linkmanDate ( $filename, $temp_name );
            $objNameArr =  array(
                 0 => 'province',//所属省份
                 1 => 'city',//地区
                 2 => 'area',//所属区域
                 3 => 'customrType',//客户性质
                 4 => 'customerName', //客户名称
                 5 => 'linkmanName',  //联系人姓名
                 6 => 'sex' , //性别
                 7 => 'age', // 年龄
                 8 => 'duty', //职务
                 9 => 'phone', //电话号码
                 10 =>'mobile',//手机号码
                 11 =>'email',//Eamil
                 12 =>'address',//地址
                 13 =>'QQ',//qq
                 14 =>'MSN',//MSN
                 15 =>'height',//身高
                 16 =>'weight',//体重
                 17 =>'remark'//备注
            );
            $objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}

			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importExcel ( $objectArr );

			if (is_array ( $resultArr ))
				echo util_excelUtil::showResult ( $resultArr, "客户联系人信息导入结果", array ("联系人名称", "结果" ) );

			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}


	function c_toAdd() {
		$customerId=$_GET['id'];
		if(!empty($customerId)){
			$this->assign('customerId',$customerId);
			$this->assign('customerName',$_GET['customerName']);
			$this->assign('isFromCustomer',$_GET['isFromCustomer']);//是否从客户模块新增联系人，用于区分编辑
		}else{
			$this->assign('customerName','');
			$this->assign('customerId','');
			$this->assign('isFromCustomer','');
		}
		$this->assign('createId',$_SESSION['USER_ID']);
	    $this->assign('createName',$_SESSION['USERNAME']);
		$this->view ( 'add' );
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
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}


	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$linkman=$_POST [$this->objName];
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$linkman['id']=$id;
	//	if ($id) {
	//		msg ( '添加成功！' );
	//	}
		//$this->listDataDict();
		if ($id) {
			echo "<script>window.returnValue='" . util_jsonUtil::encode ( $linkman ) . "';</script>";
			msg ( '添加成功！' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！', '?model=customer_linkman_linkman&action=linkmanInfo' );
		}
	}

	/**
	 * 查看
	 */
	function c_readInfo() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * 在客户基本信息中查看联系人
	 */
	function c_readInfoInS() {
		$rows = $this->service->get_d ( $_GET ['id'], 'read' );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-readins' );
	}

	/**
	 * 在客户信息中显示联系人列表
	 */
	function c_listLinkManInCustomer() {
		$this->permCheck ($_GET ['id'],'customer_customer_customer');//安全校验
		$this->show->assign ( 'id', $_GET ['id'] );
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr = "";
		$service->searchArr ['customerId'] = $_GET ['id'];
		$rows = $service->getLinkManBySId ();
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $service->count ) );
		$this->show->assign ( 'list', $service->showlistInCustomer ( $rows, $showpage ) );
		//		$this->show->assign('list',$rows);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listincustomer' );
	}
	/*
     * 重写客户信息中显示联系人列表的PageJson
     */
	function c_linsinCustomerPageJson() {
		//         	$this->show->assign ('id' , $_GET ['id']);
		$customerId = $_GET ['id'];

		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = false;

		$service->searchArr ['customerId'] = $customerId;
		$rows = $service->pageBySqlId ( "select_linkman" );

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**选择联系人
	 * @author suxc
	 *
	 */
	function c_selectLinkman() {
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->view ( 'select' );
	}


	/**
	 * 区域权限合并
	 */
	function regionMerge($privlimit,$arealimit){
		$str = null;
		if(!empty($privlimit)||!empty($arealimit)){
			if($privlimit){
				$str .= $privlimit;
			}
			if(!empty($str) && !empty($arealimit)){
				$str .= ','.$arealimit;
			}else{
				$str .= $arealimit;
			}
			return $str;
		}else{
			return null;
		}
	}

	/**
	 * 联系人列表PageJson
	 * by Liub
	 */

	function c_linkmanPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
        $otherDao = new model_common_otherdatas();
        $privlimit = $otherDao->getUserPriv('customer_customer_customer',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);

		$userId = $_SESSION ['USER_ID'];
//		$privlimit = isset ( $this->service->this_limit ['客户区域'] ) ? $this->service->this_limit ['客户区域'] : null; //外部调配
	   $arealimit = $service->getAreaIds_d();//内部调配
		$thisAreaLimit = $this->regionMerge($privlimit['客户区域'],$arealimit);

       $cusDao = new model_customer_customer_customer();
       $cusInfo = $cusDao->customerRows($privlimit['客户区域'],$thisAreaLimit,$userId);
       $rows = $service->linkmanInfo($cusInfo);
//echo "<pre>";
//print_r($privlimit['客户区域']);
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

}
?>