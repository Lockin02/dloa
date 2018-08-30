<?php
/**
 * @author Administrator
 * @Date 2012年12月14日 星期五 15:17:39
 * @version 1.0
 * @description:采购订单_供应商主信息控制层
 */
class controller_purchase_contract_applysupp extends controller_base_action {

	function __construct() {
		$this->objName = "applysupp";
		$this->objPath = "purchase_contract";
		parent::__construct ();
	 }

	/**
	 * 跳转到采购订单_供应商主信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增采购订单_供应商主信息页面
	 */
	function c_toAdd() {
		$applyId=isset($_GET['applyId'])?$_GET['applyId']:null;
		$parentId=isset($_GET['parentId'])?$_GET['parentId']:null;
		$supplierName=isset($_GET['supplierName'])?$_GET['supplierName']:null;
		$supplierId=isset($_GET['supplierId'])?$_GET['supplierId']:null;
		$quoteId=isset($_GET['quoteId'])?$_GET['quoteId']:null;
		$this->show->assign('parentId',$parentId);
		$this->show->assign('supplierName',$supplierName);
		$this->show->assign('supplierId',$supplierId);
		$this->show->assign('quoteId',$quoteId);

        //获取询价产品清单
		$inquiryEquDao=new model_purchase_contract_equipment();
        $inquiryEquRows=$inquiryEquDao->getEqusByContractId($applyId);
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($applyId);
 		$suppProDao=new model_purchase_contract_applysuppequ();

		//获取订单中物料的总数量和协议价格
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		for($i = 0; $i < count($inquiryEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($inquiryEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($inquiryEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $inquiryEquRows[$i]['amountAll']);//加上当前购买数量
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($inquiryEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//没有当前数量
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

 		if($inquiryEquRows){
 			$productRows=explode('|',$suppProDao->productAddList($inquiryEquRows,$uniqueEquRows));

 			$this->show->assign('productList',$productRows[0]);   //供应商_产品列表
 			$this->show->assign('proNumber',$productRows['1']);
 		}else{
 			$str="<tr align='center'><td colspan='50'>暂无相应信息</td></tr>";
 			$this->show->assign('productList',$str);   //供应商_产品列表
 		}

		//配置数据字典的值
		$this->showDatadicts ( array ('transportation' => 'YSFS' ) ); //发票类型
		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) ); //付款条件
		$this->showDatadicts ( array ('taxRate' => 'XJSL' ) ); //税率
		$this->view ( 'add');
	}

   /**
	 * 跳转到编辑采购订单_供应商主信息页面
	 */
	function c_toEdit() {
		$applyId=isset($_GET['applyId'])?$_GET['applyId']:null;
		$this->show->assign('parentId',$_GET['parentId']);
		$suppRows=$this->service->get_d($_GET['parentId']);
		$this->show->assign('supplierName',$_GET['supplierName']);
		$this->show->assign('supplierId',$_GET['supplierId']);
		$this->show->assign('quoteId',$_GET['quoteId']);
        foreach($suppRows as $key=>$val){
        	$this->show->assign($key,$val);
        }

		//获取供应商—产品清单
		$suppProDao=new model_purchase_contract_applysuppequ();
		$suppProductRows=$suppProDao->getProByParentId($_GET['parentId']);

		if(is_array($suppProductRows)){
    		$uniqueEquRows=$suppProDao->getUniqueByParentId($_GET['parentId']);

			//获取订单中物料的总数量和协议价格
			$applybasicDao = new model_purchase_apply_applybasic();
			$materialequDao = new model_purchase_material_materialequ();
			$materialDao = new model_purchase_material_material();
			for($i = 0; $i < count($suppProductRows); $i++) {
				$amount = $applybasicDao->getAmountAll($suppProductRows[$i]['productId']);

				$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
				$materialequRow = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $suppProductRows[$i]['amountAll']);//加上当前购买数量
				$materialRow = $materialDao->get_d($materialequRow['parentId']);

				$materialequRow1 = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//没有当前数量
				$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

				$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
			}

 			$productRows=explode('|',$suppProDao->prosEditNewList($suppProductRows,$uniqueEquRows));
 			$this->show->assign('equsEditList',$productRows['0']);   //供应商_产品列表
 			$this->show->assign('proNumber',$productRows['1']);
 		}else{
	        //获取询价产品清单
			$inquiryEquDao=new model_purchase_contract_equipment();
	        $inquiryEquRows=$inquiryEquDao->getEqusByContractId($applyId);
	        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($applyId);
	 		$suppProDao=new model_purchase_contract_applysuppequ();
 			$suppProductRows=explode('|',$suppProDao->productAddList($inquiryEquRows,$uniqueEquRows));
 			$this->show->assign('equsEditList',$suppProductRows[0]);   //供应商_产品列表
 			$this->show->assign('proNumber',$suppProductRows['1']);

 		}
		$length=count($suppProductRows); //获取物料数组的长度
		for($i=1;$i<=$length;$i++){
			$j=$i-1;
			$this->showDatadicts ( array ('transportation'.$i => 'YSFS' ), $suppProductRows [$j]['transportation'] );
			$this->showDatadicts ( array ('taxRate'.$i => 'XJSL' ), $suppProductRows [$j]['taxRate'] );
		}
		$this->showDatadicts ( array ('transportation' => 'YSFS' ) );
		$this->showDatadicts ( array ('taxRate' => 'XJSL' ) , $suppRows['taxRate']); //税率
		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) , $suppRows['paymentCondition']); //付款条件
		$this->view ( 'edit');
	}

   /**
	 * 跳转到查看采购订单_供应商主信息页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$parentId=isset($_GET['parentId'])?$_GET['parentId']:"";
		$supplierName=isset($_GET['supplierName'])?$_GET['supplierName']:null;
		$quote=isset($_GET['quote'])?$_GET['quote']:null;
		$this->show->assign('supplierName',$supplierName);
		$this->show->assign('quote',$quote);
		$suppRows=$this->service->get_d($_GET['parentId']);
        foreach($suppRows as $key=>$val){
        	$this->show->assign($key,$val);
        }

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['parentId'], false ) );

		//获取供应商—产品清单
		$suppProDao=new model_purchase_contract_applysuppequ();
		$suppProductRows=$suppProDao->getProByParentId($parentId);
		$uniqueEquRows=$suppProDao->getUniqueByParentId($_GET['parentId']);

		//获取订单中物料的总数量和协议价格
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		for($i = 0; $i < count($suppProductRows); $i++) {
			$amount = $applybasicDao->getAmountAll($suppProductRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $suppProductRows[$i]['amountAll']);//加上当前购买数量
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//没有当前数量
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

		$this->show->assign('proList',$suppProDao->productViewList($suppProductRows,$uniqueEquRows));
      $this->view ( 'view' );
   }

   	/**添加供应商_产品
	*/
	function c_addProduct(){
		$product=$this->service->addProduct_d($_POST[$this->objName]);
	}

	/**修改报单时，重新保存产品清单
	*/
	function c_addEditPro(){
		$product=$this->service->addEditPro_d($_POST[$this->objName]);
	}

		/**添加或修改询价单时，删除供应商信息
	*/
	function c_del(){
		$id=$_POST['id'];
		$flag=$this->service->deletes($id);
		echo $flag;
	}

	function c_getSuppInfo(){
		$suppId=isset($_POST['id'])?$_POST['id']:"";
		$rows=$this->service->get_d($suppId);
		if(is_array($rows)){
			echo util_jsonUtil::encode ( $rows);
		}else{
			echo "";
		}
	}


	/**获取供应商
	*/
	function c_getSupp(){
		$parentId=$_POST['parentId'];
		$supps=$this->service->getSuppByParentId($parentId);
        echo util_jsonUtil::encode ($supps);

	}
 }
?>