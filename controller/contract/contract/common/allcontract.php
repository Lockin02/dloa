<?php
/*
 * Created on 2010-8-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_contract_common_allcontract extends controller_base_action{

	function __construct() {
		$this->docContArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
			"oa_sale_order" => "model_projectmanagent_order_order", //���۷���
			"oa_sale_lease" => "model_contract_rental_rentalcontract", //���޳���
			"oa_sale_service" => "model_engineering_serviceContract_serviceContract", //�����ͬ����
			"oa_sale_rdproject" => "model_rdproject_yxrdproject_rdproject", //�з���ͬ����
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrow", //���÷���
			"oa_present_present" => "model_projectmanagent_present_present", //���÷���
		);

		$this->docModelArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
			"oa_sale_order" => "model_projectmanagent_order_orderequ", //���۷���
			"oa_sale_lease" => "model_contract_rental_tentalcontractequ", //���޳���
			"oa_sale_service" => "model_engineering_serviceContract_serviceequ", //�����ͬ����
			"oa_sale_rdproject" => "model_rdproject_yxrdproject_rdprojectequ", //�з���ͬ����
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrowequ", //���÷���
			"oa_present_present" => "model_stock_outplan_strategy_presentplan", //���÷���
		);
		//���ֺ�ͬ�Զ����嵥 by LiuB
		$this->docCusArr = array (
		    "oa_sale_order" => "model_projectmanagent_order_customizelist", //���۷���
			"oa_sale_lease" => "model_contract_rental_customizelist", //���޳���
			"oa_sale_service" => "model_engineering_serviceContract_customizelist", //�����ͬ����
			"oa_sale_rdproject" => "model_rdproject_yxrdproject_customizelist", //�з���ͬ����

		);
	}

	  function c_getBorrwoToOrderequ(){
	  	$docId = $_POST['docId'];
	  	$docType = $_POST['docType'];
	  	$rowNum = $_POST['rowNum'];
        $dao = new $this->docModelArr[$docType];
        $rows = $dao->findAll(array("orderId" => $docId,"isBorrowToorder" => '1'));
        $rows = util_jsonUtil::iconvGB2UTFArr($rows);
        $equStr = $this->shwoBToOEqu($rows,$rowNum);
        echo $equStr;
	  }
     function shwoBToOEqu($rows,$rowNum){
		$str = "";
		$i = $rowNum;
		foreach($rows as $key => $val ){
				$j = $i + 1;
				//���´�����
				$planNum = $rows[$key]['issuedShipNum'];
				//��ͬ����
				$contNum = $rows[$key]['number'];
				//����������
				$contRemain = $contNum - $planNum;
				if($contRemain==0){
					continue;
				}
				$line = $j-$rowNum;
				$str .= <<<EOT
					<tr><td>$line
							<td>$val[productNo]
							<input type="hidden" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/>
							<input type="hidden" id="BToOTips$j" name="outplan[productsdetail][$j][BToOTips]" value="1" class="txtmiddle"/></td>
							</td>
						<td>$val[productName]
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="contNum$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$planNum</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j" value='$planNum' class="txtmiddle"/>
						</td>
						<td>$contRemain
							<input type="hidden" name="outplan[productsdetail][$j][number]" id="number$j" value="$contRemain" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'borrowbody')" title="" />
						</td>
					</tr>
EOT;
				$i ++;
			}

		return $str;
     }


	/**
	 * ��ͬ�����б�ӱ����ݻ�ȡ
	 */
	function c_pageJson() {
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo ();
		$lockDao = new model_stock_lock_lock ();
		$contType = $_POST ['docType'];
		$daoName = $this->docModelArr [$contType];
		$service = new $daoName ();
		$service->asc = false;
		$service->getParam ( $_POST );
		$service->searchArr ['isDel'] = 0;
		$rows = $service->list_d ();
		foreach ( $rows as $key => $val ) {
			$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,$_POST ['docType'] );
			$rows [$key] ['exeNum'] = $inventoryDao->getExeNums ( $rows [$key] ['productId'], '1' );
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �ж�ĳ����ͬ�Ƿ��в�Ʒ�嵥
	 */
	function c_getEquById() {
		$daoName = $this->docModelArr [$_POST ['type']];
		$docDao = new $daoName ();
		$sql = "select count(*) as equNum from " . $docDao->tbl_name . " where orderId=" . $_POST ['id'];
		$equNum = $docDao->_db->getArray ( $sql );
		echo $equNum [0] ['equNum'];
	}
	/**
	 * �жϺ�ͬ�Ƿ����Զ����嵥
	 * by LiuB 2011-7-5 15:10:50
	 */
	function c_cusById() {
		$cusDaoName = $this->docCusArr [$_POST ['type']];
		$cusDao = new $cusDaoName ();
		$sql = "select count(*) as cusNum from " . $cusDao->tbl_name . " where orderId= " . $_POST ['id'];
		$cusInfo = $cusDao->_db->getArray ( $sql );
		echo $cusInfo [0] ['cusNum'];
	}
	/**
	 * �رշ�������
	 */
	function c_closeCont() {
		switch ($_POST ['type']) {
			case 'oa_sale_order' :
				$modelName = "projectmanagent_order_order";
				break;
			case 'oa_sale_lease' :
				$modelName = "contract_rental_rentalcontract";
				break;
			case 'oa_sale_service' :
				$modelName = "engineering_serviceContract_serviceContract";
				break;
			case 'oa_sale_rdproject' :
				$modelName = "rdproject_yxrdproject_rdproject";
				break;
			case 'oa_borrow_borrow' :
				$modelName = "projectmanagent_borrow_borrow";
				break;
			case 'oa_present_present' :
				$modelName = "projectmanagent_present_present";
				break;
			case 'oa_contract_contract' :
				$modelName = "contract_contract_contract";
				break;
		}
		$this->permCheck ( $_POST ['id'], $modelName );
		$contType = $_POST ['type'];
		$daoName = $this->docContArr [$contType];
		$service = new $daoName ();
		echo $service->updateDeliveryStatus ( $_POST ['id'] );
	}

	/**
	 * �ָ���������״̬
	 */
	function c_recoverCont() {
		switch ($_POST ['type']) {
			case 'oa_sale_order' :
				$modelName = "projectmanagent_order_order";
				break;
			case 'oa_sale_lease' :
				$modelName = "contract_rental_rentalcontract";
				break;
			case 'oa_sale_service' :
				$modelName = "engineering_serviceContract_serviceContract";
				break;
			case 'oa_sale_rdproject' :
				$modelName = "rdproject_yxrdproject_rdproject";
				break;
			case 'oa_borrow_borrow' :
				$modelName = "projectmanagent_borrow_borrow";
				break;
			case 'oa_present_present' :
				$modelName = "projectmanagent_present_present";
				break;
		}
		$this->permCheck ( $_POST ['id'], $modelName );
		$contType = $_POST ['type'];
		$daoName = $this->docContArr [$contType];
		$service = new $daoName ();
		echo $service->updateOrderShipStatus_d ( $_POST ['id'] );
	}

	/**
	 * ��ͬ����excel
	 */
	function c_importCont() {
		//��ͬ���͡���ͬId
		$contType = $_GET ['type'];
		$contId = $_GET ['id'];
		$daoName = $this->docContArr [$contType];
		$contDao = new $daoName ();
		$contObj = $contDao->getByPurview_d ( $contId );
		switch ($contObj ['shipCondition']) {
			case "" : $contObj['shipCondition'] = "";break;
			case "0" : $contObj['shipCondition'] = "��������";break;
			case "1" : $contObj['shipCondition'] = "֪ͨ����";break;
		}
		$contObj['customerType'] = parent::getDataNameByCode($contObj['customerType']);
		$contObj['invoiceType'] =  parent::getDataNameByCode($contObj['invoiceType']);
		if( $contType == 'oa_sale_order' ){
			foreach( $contObj['invoice'] as $key=>$val ){
				$contObj['invoice'][$key]['iTypeName']= parent::getDataNameByCode($val['iType']);
			}
			foreach( $contObj['orderequ'] as $key=>$val ){
				$contObj['orderequ'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			foreach( $contObj['customizelist'] as $key=>$val ){
				$contObj['customizelist'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			//���õ���excel������
			return model_contract_common_orderExcelUtil::exporTemplate($contObj, '','');
		}elseif( $contType == 'oa_sale_service' ){
			foreach( $contObj['customizelist'] as $key=>$val ){
				$contObj['customizelist'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			foreach( $contObj['serviceequ'] as $key=>$val ){
				$contObj['serviceequ'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			//���õ���excel������
			return model_contract_common_serviceExcelUtil::exporTemplate($contObj, '','');
		}elseif( $contType == 'oa_sale_lease' ){
			foreach( $contObj['customizelist'] as $key=>$val ){
				$contObj['customizelist'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			foreach( $contObj['rentalcontractequ'] as $key=>$val ){
				$contObj['rentalcontractequ'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			//���õ���excel������
			return model_contract_common_rentalExcelUtil::exporTemplate($contObj, '','');
		}elseif( $contType == 'oa_sale_rdproject' ){
			foreach( $contObj['customizelist'] as $key=>$val ){
				$contObj['customizelist'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			foreach( $contObj['rdprojectequ'] as $key=>$val ){
				$contObj['rdprojectequ'][$key]['productLine']= parent::getDataNameByCode($val['productLine']);
			}
			//���õ���excel������
			return model_contract_common_rdprojectExcelUtil::exporTemplate($contObj, '','');
		}
	  }
     /**
     * ���ֳ��ⵥ ��Ҫ�Ĵӱ���Ϣ
     * @param $orderId---��ͬId  $orderType--��ͬ���ͣ�������
     */
    function c_getOrderItem(){
    	 $orderId = $_POST['orderId'];
    	 $orderType = $_POST['orderType'];
    	$daoName = $this->docModelArr[$orderType];
    	$dao = new $daoName();
	$item = "";
        switch($orderType){
            case "oa_sale_order" :
                $item = $dao->showItemAtOrder($orderId);
                echo  util_jsonUtil::iconvGB2UTF($item) ;
                break;
            case "oa_sale_service" :
                $item = $dao->showItemAtService($orderId);
                echo util_jsonUtil::iconvGB2UTF($item);
                break;
            case "oa_sale_lease" :
                $item = $dao->showItemAtLease($orderId);
                echo util_jsonUtil::iconvGB2UTF($item);
                break;
            case "oa_sale_rdproject" :
                $item = $dao->showItemAtRdproject($orderId);
                echo util_jsonUtil::iconvGB2UTF($item);
                break;
        }
    }


    /**
     * �Ƚ�����
     */
    function c_dateCompare(){
    	$createTime = strtotime($_POST['createTime']);
    	$ExaDT = strtotime($_POST['ExaDT']);
        $cDate = strtotime('2011-9-1');
        if($createTime > $cDate && $ExaDT > $cDate){
        	echo 1;
        }else{
        	echo 0;
        }
    }

	/**
	 * ��ͬ�������ݵ���
	 */
	function c_contExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		return model_contract_common_contExcelUtil::exportContShipInfo ();
	}


	/**
	 * �����÷������ݵ���
	 */
	function c_borrowExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		$limits = $_GET['limits'];
		return model_contract_common_contExcelUtil::exportBorrowShipInfo ($limits);
	}

	/**
	 * ���ͷ������ݵ���
	 */
	function c_preExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		return model_contract_common_contExcelUtil::exportPreShipInfo ();
	}


}
?>
