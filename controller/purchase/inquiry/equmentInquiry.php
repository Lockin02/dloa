<?php
/*询价单_产品清单
 * Created on 2010-12-29
 * can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class controller_purchase_inquiry_equmentInquiry extends controller_base_action {

	function __construct() {
		$this->objName = 'equmentInquiry';
		$this->objPath = 'purchase_inquiry';
		parent::__construct ();
	}

/*****************************************显示分割线********************************************/


	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		$rows=$service->getPurchName($rows);
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
	 * 生成采购订单时，判断物料的类型是否为同一采购类型
	 *
	 */
	 function c_isSameType(){
	 	$idsArr=$_POST['parentIds'];
	 	$flag=$this->service->isSameType_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }


	/**我的询价单列表-物料汇总
	*2010-12-28
	*/
	function c_toMyInquiryEquList () {
		$service = $this->service;
		$equNumb = isset( $_GET['equNumb'] )?$_GET['equNumb']:"";
		$equName = isset( $_GET['equName'] )?$_GET['equName']:"";
		$idsArry = isset( $_GET['idsArry'] )?$_GET['idsArry']:"";
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$searchArr = array ( "state" =>2,"purcherId" => $_SESSION ['USER_ID']);
		if($purchType=="contract_sales"){
			$searchArr['purchType'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//借试用采购
			$searchArr['purchType'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//显示全部采购类型的物料汇总表
			//$searchArr['purchTypeArr'] = "" ;
		}else{
			$searchArr['purchType']=$purchType;
		}
		if($equName!=""){
			$searchArr['productNameSear'] = $equName;
		}
		$service->getParam ( $_GET );
		$service->__SET('sort', "i.suppId");
		$service->__SET('groupBy',"c.id");
		$service->__SET ( 'searchArr', $searchArr );
		//获取个人的已指定供应商的物料信息
		$equRows=$service->getEquList_d();
		//获取非重复供应商物料信息
		$service->__SET('groupBy',"i.suppId,c.purchType");
		$equUniqueRows=$service->getEquPageList_d();
		$this->pageShowAssign();
		$this->assign("purchType",$purchType);
		$this->show->assign('idsArry', $idsArry);
		$this->assign('equName', $equName);
		$this->assign ( 'list', $service->showEquList ( $equRows,$equUniqueRows ) );

		$this->show->display($this->objPath.'_'.$this->objName.'-myequ-list');
	}

	/**
	 * 下达采购订单时，判断物料的采购类型和供应商是否一致
	 *
	 */
	 function c_isSameTypeByOrder(){
	 	$idsArr=$_POST ['ids'];
	 	$flag=$this->service->isSameTypeByOrder_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

}
?>
