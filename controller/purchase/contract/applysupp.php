<?php
/**
 * @author Administrator
 * @Date 2012��12��14�� ������ 15:17:39
 * @version 1.0
 * @description:�ɹ�����_��Ӧ������Ϣ���Ʋ�
 */
class controller_purchase_contract_applysupp extends controller_base_action {

	function __construct() {
		$this->objName = "applysupp";
		$this->objPath = "purchase_contract";
		parent::__construct ();
	 }

	/**
	 * ��ת���ɹ�����_��Ӧ������Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������ɹ�����_��Ӧ������Ϣҳ��
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

        //��ȡѯ�۲�Ʒ�嵥
		$inquiryEquDao=new model_purchase_contract_equipment();
        $inquiryEquRows=$inquiryEquDao->getEqusByContractId($applyId);
        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($applyId);
 		$suppProDao=new model_purchase_contract_applysuppequ();

		//��ȡ���������ϵ���������Э��۸�
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		for($i = 0; $i < count($inquiryEquRows); $i++) {
			$amount = $applybasicDao->getAmountAll($inquiryEquRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($inquiryEquRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $inquiryEquRows[$i]['amountAll']);//���ϵ�ǰ��������
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($inquiryEquRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//û�е�ǰ����
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

 		if($inquiryEquRows){
 			$productRows=explode('|',$suppProDao->productAddList($inquiryEquRows,$uniqueEquRows));

 			$this->show->assign('productList',$productRows[0]);   //��Ӧ��_��Ʒ�б�
 			$this->show->assign('proNumber',$productRows['1']);
 		}else{
 			$str="<tr align='center'><td colspan='50'>������Ӧ��Ϣ</td></tr>";
 			$this->show->assign('productList',$str);   //��Ӧ��_��Ʒ�б�
 		}

		//���������ֵ��ֵ
		$this->showDatadicts ( array ('transportation' => 'YSFS' ) ); //��Ʊ����
		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) ); //��������
		$this->showDatadicts ( array ('taxRate' => 'XJSL' ) ); //˰��
		$this->view ( 'add');
	}

   /**
	 * ��ת���༭�ɹ�����_��Ӧ������Ϣҳ��
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

		//��ȡ��Ӧ�̡���Ʒ�嵥
		$suppProDao=new model_purchase_contract_applysuppequ();
		$suppProductRows=$suppProDao->getProByParentId($_GET['parentId']);

		if(is_array($suppProductRows)){
    		$uniqueEquRows=$suppProDao->getUniqueByParentId($_GET['parentId']);

			//��ȡ���������ϵ���������Э��۸�
			$applybasicDao = new model_purchase_apply_applybasic();
			$materialequDao = new model_purchase_material_materialequ();
			$materialDao = new model_purchase_material_material();
			for($i = 0; $i < count($suppProductRows); $i++) {
				$amount = $applybasicDao->getAmountAll($suppProductRows[$i]['productId']);

				$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
				$materialequRow = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $suppProductRows[$i]['amountAll']);//���ϵ�ǰ��������
				$materialRow = $materialDao->get_d($materialequRow['parentId']);

				$materialequRow1 = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//û�е�ǰ����
				$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

				$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
			}

 			$productRows=explode('|',$suppProDao->prosEditNewList($suppProductRows,$uniqueEquRows));
 			$this->show->assign('equsEditList',$productRows['0']);   //��Ӧ��_��Ʒ�б�
 			$this->show->assign('proNumber',$productRows['1']);
 		}else{
	        //��ȡѯ�۲�Ʒ�嵥
			$inquiryEquDao=new model_purchase_contract_equipment();
	        $inquiryEquRows=$inquiryEquDao->getEqusByContractId($applyId);
	        $uniqueEquRows=$inquiryEquDao->getUniqueByParentId($applyId);
	 		$suppProDao=new model_purchase_contract_applysuppequ();
 			$suppProductRows=explode('|',$suppProDao->productAddList($inquiryEquRows,$uniqueEquRows));
 			$this->show->assign('equsEditList',$suppProductRows[0]);   //��Ӧ��_��Ʒ�б�
 			$this->show->assign('proNumber',$suppProductRows['1']);

 		}
		$length=count($suppProductRows); //��ȡ��������ĳ���
		for($i=1;$i<=$length;$i++){
			$j=$i-1;
			$this->showDatadicts ( array ('transportation'.$i => 'YSFS' ), $suppProductRows [$j]['transportation'] );
			$this->showDatadicts ( array ('taxRate'.$i => 'XJSL' ), $suppProductRows [$j]['taxRate'] );
		}
		$this->showDatadicts ( array ('transportation' => 'YSFS' ) );
		$this->showDatadicts ( array ('taxRate' => 'XJSL' ) , $suppRows['taxRate']); //˰��
		$this->showDatadicts ( array ('paymentCondition' => 'FKTJ' ) , $suppRows['paymentCondition']); //��������
		$this->view ( 'edit');
	}

   /**
	 * ��ת���鿴�ɹ�����_��Ӧ������Ϣҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
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

		//��ȡ��Ӧ�̡���Ʒ�嵥
		$suppProDao=new model_purchase_contract_applysuppequ();
		$suppProductRows=$suppProDao->getProByParentId($parentId);
		$uniqueEquRows=$suppProDao->getUniqueByParentId($_GET['parentId']);

		//��ȡ���������ϵ���������Э��۸�
		$applybasicDao = new model_purchase_apply_applybasic();
		$materialequDao = new model_purchase_material_materialequ();
		$materialDao = new model_purchase_material_material();
		for($i = 0; $i < count($suppProductRows); $i++) {
			$amount = $applybasicDao->getAmountAll($suppProductRows[$i]['productId']);

			$uniqueEquRows[$i]['amount'] = $amount[0]['SUM(amountAll)']?$amount[0]['SUM(amountAll)']:0;
			$materialequRow = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$amount[0]['SUM(amountAll)'] +  $suppProductRows[$i]['amountAll']);//���ϵ�ǰ��������
			$materialRow = $materialDao->get_d($materialequRow['parentId']);

			$materialequRow1 = $materialequDao->getPrice($suppProductRows[$i]['productId'] ,$uniqueEquRows[$i]['amount']);//û�е�ǰ����
			$materialRow1 = $materialDao->get_d($materialequRow1['parentId']);

			$uniqueEquRows[$i]['referPrice'] = $suppProDao->getPriceArr($materialRow ,$materialequRow ,$materialequRow1);
		}

		$this->show->assign('proList',$suppProDao->productViewList($suppProductRows,$uniqueEquRows));
      $this->view ( 'view' );
   }

   	/**��ӹ�Ӧ��_��Ʒ
	*/
	function c_addProduct(){
		$product=$this->service->addProduct_d($_POST[$this->objName]);
	}

	/**�޸ı���ʱ�����±����Ʒ�嵥
	*/
	function c_addEditPro(){
		$product=$this->service->addEditPro_d($_POST[$this->objName]);
	}

		/**��ӻ��޸�ѯ�۵�ʱ��ɾ����Ӧ����Ϣ
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


	/**��ȡ��Ӧ��
	*/
	function c_getSupp(){
		$parentId=$_POST['parentId'];
		$supps=$this->service->getSuppByParentId($parentId);
        echo util_jsonUtil::encode ($supps);

	}
 }
?>