<?php
/**
 * @description: 采购询价单供应商action
 * @date 2010-12-24 上午10:00:20
 * @author oyzx
 * @version V1.0
 */
class controller_purchase_inquiry_inquirysupp extends controller_base_action {

	function __construct() {
		$this->objName = 'inquirysupp';
		$this->objPath = 'purchase_inquiry';
		parent::__construct ();
	}

/*****************************************页面跳转方法开始********************************************/
	/**根据供应商跳转到报单添加页面
	*author can
	*2010-12-29
	*/
	function c_quotationInit () {
		$inquiryId=isset($_GET['inquiryId'])?$_GET['inquiryId']:null;
		$parentId=isset($_GET['parentId'])?$_GET['parentId']:null;
		$supplierName=isset($_GET['supplierName'])?$_GET['supplierName']:null;
		$supplierId=isset($_GET['supplierId'])?$_GET['supplierId']:null;
		$quoteId=isset($_GET['quoteId'])?$_GET['quoteId']:null;
		$this->show->assign('parentId',$parentId);
		$this->show->assign('supplierName',$supplierName);
		$this->show->assign('supplierId',$supplierId);
		$this->show->assign('quoteId',$quoteId);

        //获取询价产品清单
		$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
        $inquiryEquRows=$inquiryEquDao->getEqusByParentId($inquiryId);
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($inquiryId);
 		$suppProDao=new model_purchase_inquiry_inquirysupppro();
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
		$this->show->display($this->objPath.'_inquirysheet-quotationInit');
	}
	/**如果已保存报价单，再选择，则跳到此页面
	*author can
	*2011-1-3
	*/
	function c_toQuoteEdit () {
		$inquiryId=isset($_GET['inquiryId'])?$_GET['inquiryId']:null;
		$this->show->assign('parentId',$_GET['parentId']);
		$suppRows=$this->service->get_d($_GET['parentId']);
		$this->show->assign('supplierName',$_GET['supplierName']);
		$this->show->assign('supplierId',$_GET['supplierId']);
		$this->show->assign('quoteId',$_GET['quoteId']);
        foreach($suppRows as $key=>$val){
        	$this->show->assign($key,$val);
        }

		//获取供应商—产品清单
		$suppProDao=new model_purchase_inquiry_inquirysupppro();
		$suppProductRows=$suppProDao->getProByParentId($_GET['parentId']);
		if(is_array($suppProductRows)){
    		$uniqueEquRows=$suppProDao->getUniqueByParentId($_GET['parentId']);
//    		echo "<pre>";
//    		print_r($uniqueEquRows);
// 			$productRows=explode('|',$suppProDao->prosEditList($suppProductRows));
 			$productRows=explode('|',$suppProDao->prosEditNewList($suppProductRows,$uniqueEquRows));

 			$this->show->assign('equsEditList',$productRows['0']);   //供应商_产品列表
 			$this->show->assign('proNumber',$productRows['1']);
 		}else{
	        //获取询价产品清单
			$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
	        $inquiryEquRows=$inquiryEquDao->getEqusByParentId($inquiryId);
        	$uniqueEquRows=$inquiryEquDao->getUniqueByParentId($inquiryId);
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
		$this->show->display($this->objPath.'_inquirysheet-quotedit');
	}

	/**查看报价单
	*author can
	*2011-1-2
	*/
	function c_readQuotation(){
		$parentId=isset($_GET['parentId'])?$_GET['parentId']:"";
		$supplierName=isset($_GET['supplierName'])?$_GET['supplierName']:null;
		$quote=isset($_GET['quote'])?$_GET['quote']:null;
		$this->show->assign('supplierName',$supplierName);
		$this->show->assign('quote',$quote);
		$suppRows=$this->service->get_d($_GET['parentId']);
		 $suppRows['taxRate']=$this->getDataNameByCode( $suppRows['taxRate']); //税率
        foreach($suppRows as $key=>$val){
        	$this->show->assign($key,$val);
        }

		//获取供应商—产品清单
		$suppProDao=new model_purchase_inquiry_inquirysupppro();
		$suppProductRows=$suppProDao->getProByParentId($parentId);
		$uniqueEquRows=$suppProDao->getUniqueByParentId($_GET['parentId']);
		$this->show->assign('proList',$suppProDao->productViewList($suppProductRows,$uniqueEquRows));
		$this->show->display($this->objPath.'_inquirysheet-quotationread');
	}

/*****************************************页面跳转方法结束********************************************/

/*****************************************业务操作方法开始********************************************/

	/**添加供应商_产品
	*author can
	*2010-12-31
	*/
	function c_addProduct(){
		$product=$this->service->addProduct_d($_POST[$this->objName]);
	}

	/**修改报单时，重新保存产品清单
	*author can
	*2011-1-3
	*/
	function c_addEditPro(){
		$product=$this->service->addEditPro_d($_POST[$this->objName]);
	}

	/**获取供应商
	*author can
	*2011-1-6
	*/
	function c_getSupp(){
		$parentId=$_POST['parentId'];
		$supps=$this->service->getSuppByParentId($parentId);
        echo util_jsonUtil::encode ($supps);

	}

	/**添加或修改询价单时，删除供应商信息
	*author can
	*2011-1-7
	*/
	function c_del(){
		$id=$_POST['id'];
		$flag=$this->service->deletes($id);
		echo $flag;
	}

/*****************************************业务操作方法结束********************************************/

}
?>
