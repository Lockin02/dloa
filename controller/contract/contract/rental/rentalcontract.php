<?php
header("Content-type: text/html; charset=gb2312");
/*
 * Created on 2011-1-21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * ���޺�ͬ���Ʋ�
 */
class controller_contract_rental_rentalcontract extends controller_base_action{
	function __construct(){
		$this->objName = "rentalcontract";
		$this->objPath = "contract_rental";
		parent::__construct();
	}


	/**
	 * ��ת���½������ͬҳ��
	 *
	 */
	function c_toAddSContract () {
		$this->assign('orderInput', ORDER_INPUT );
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('saleman', $_SESSION['USERNAME']);
		$this->assign('salemanId', $_SESSION['USER_ID']);
		$this->assign('hiresName', $_SESSION['USERNAME']);
		$this->assign('hiresId', $_SESSION['USER_ID']);
		$this->assign('createTime' , day_date);
		$id = $_GET['id'];
        if ($id) {
        	$this->permCheck($id,projectmanagent_chance_chance);
			$condiction = array (
				"id" => $id
			);
			$cluesDao = new model_projectmanagent_chance_chance();
			$rows = $cluesDao->get_d($id);
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
            $this->customerProCity($rows['customerId']);//���ݿͻ���ȡ �ͻ���ʡ����Ϣ
			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
			$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
            $orderChance = new model_contract_rental_tentalcontractequ();
			$chanceequ = $orderChance->ChanceOrderEqu($rows['chanceequ']);
			$orderCus = new model_contract_rental_customizelist();
            $customizelist = $orderCus->changeCusOrder($rows['customizelist']);

			$this->assign('chance', $chanceequ[0]);
			$this->assign('productNumber', $chanceequ[1]);
			$this->assign('customizelist', $customizelist[0]);
			$this->assign('PreNum', $customizelist[1]);
			$this->assign('chanceId', $id);
			$this->display('add-chance');
		} else {
			$this->display('add');
		}
	}
	 /**
      * ���ݿͻ�ID ��ȡ �ͻ���ʡ����Ϣ
     */
    function customerProCity($customerId){
      $dao = new model_customer_customer_customer();
      $proId = $dao->find(array("id" => $customerId),null,"ProvId");//ʡ��ID
      $cityId = $dao->find(array("id" => $customerId),null,"CityId");//����ID
      $this->assign("orderProvinceId",$proId['ProvId']);
      $this->assign("orderCityId",$cityId['CityId']);
    }
	/**
	 * ��ͬ�鿴Tab---�ر���Ϣ
	 */
	function c_toCloseInfo() {
		$rows = $this->service->get_d($_GET['id']);
		if ($rows['state'] == '3' || $rows['state'] == '9' ) {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('closeinfo');

		} else {
			echo '<span>���������Ϣ</span>';
		}
	}
/**********************************************************************************************/
     /**
      * δ������ͬ
      */
     function c_toListWtj(){
     	$this->assign("userId" , $_SESSION['USER_ID']);
	 	$this->display("listwtj");
     }
	/**
	 *���������б�
	 */
	 function c_toListWsp(){
	 	$this->assign("userId" , $_SESSION['USER_ID']);
	 	$this->display("listwsp");
	 }

	 /**
	  * ��ִ�е����޺�ͬ
	  */
	  function c_toListZzx(){
	  	$this->assign("userId" , $_SESSION['USER_ID']);
        $this->display('listzzx');
	  }

	 /**
	  * ��ִ�е����޺�ͬ
	  */
	  function c_toListYwc(){
	  	$this->assign("userId" , $_SESSION['USER_ID']);
        $this->display('listywc');
	  }
	  /**
	   * �ѹرյ����޺�ͬ
	   */
	   function c_toListYwg(){
	   	$this->assign("userId" , $_SESSION['USER_ID']);
	   	 $this->display('listywg');
	   }
/**********************************************************************************************/
	/**
	 * �����������
	 */
	function c_add() {
		$orderInfo = $_POST [$this->objName];
//		if($orderInfo['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $orderInfo['sign'] == "��" ){
//				$orderInfo['orderTempCode'] = "LS".$orderInfo['orderTempCode'];
//			   $orderInfo['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}else if($orderInfo['sign'] == "��"){
//				$orderInfo['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}
//			$id = $this->service->add_d ($orderInfo);
//		}else if($orderInfo['orderInput'] == "0"){
//			if(!empty($orderInfo['orderTempCode'])){
//				$orderInfo['orderTempCode'] = $orderInfo['orderTempCode'];
//			}
//			$id = $this->service->add_d ($orderInfo);
//		}else {
//			msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
//		}

		$id = $this->service->add_d ($orderInfo);
        if($id){
			if($_GET['act']=='app'){
				succ_show('controller/contract/rental/ewf_index2.php?actTo=ewfSelect&billId=' . $id );
			}else{
				//�����ӳɹ�������ת�������ͬ�༭ҳ��
				msgGO ( '��ӳɹ���' ,'?model=contract_rental_rentalcontract&action=toAddSContract');
			}
		}
		//$this->listDataDict();
	}

	/**
	 * �̻�ת���޺�ͬ
	 */
	function c_chanceAdd() {
		$orderInfo =  $_POST [$this->objName];
//		if($orderInfo['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $orderInfo['sign'] == "��" ){
//			   $orderInfo['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}else if($orderInfo['sign'] == "��"){
//				$orderInfo['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}
//			$id = $this->service->add_d ($orderInfo);
//		}else if($orderInfo['orderInput'] == "0"){
//			$id = $this->service->add_d ($orderInfo);
//		}else {
//			msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
//		}
		$id = $this->service->add_d ($orderInfo);
        if($id){
			if($_GET['act']=='app'){
				succ_show('controller/contract/rental/ewf_index.php?actTo=ewfSelect&billId=' . $id );
			}else{
				//�����ӳɹ�������ת�������ͬ�༭ҳ��
				msgRF('��ӳɹ���');
			}
		}
		//$this->listDataDict();
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$orderCodeDao = new model_common_codeRule();
        if($object['sign'] == "��" && $object['orderCode'] == ''){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$object['tenantId']);
		}
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}
		/**
	 * �����޸�
	 */
	function c_proedit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$id = $this->service->proedit_d ( $object, $isEditInfo );

		if ($id) {
            $this->service->updateOrderShipStatus_d($id);
			msgRF ( '�༭�ɹ�' );
		}
	}

	/**
	 * ��дint
	 */
	function c_init() {
//		$this->permCheck();
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$rows = $this->service->get_d($_GET['id']);

		if ($perm == 'view') {
            //Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
            if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['hiresId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
                $rows = $this->service->filterWithoutField('���޺�ͬ���',$rows,'form',array('orderMoney','orderTempMoney'));
                $rows['rentalcontractequ'] = $this->service->filterWithoutField('�����豸���',$rows['rentalcontractequ'],'list',array('price','money'));
            }

            $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
            //��ͬ�ı�Ȩ��

			 if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['hiresId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
	                if(!empty($this->service->this_limit ['���޺�ͬ���'])){
	               $rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_lease2' );
				}else{
					$rows ['file2']="";
				}
            }else{
            	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_lease2' );
            }

            $rows = $this->service->initView($rows);

            foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }

             if ($rows['sign'] == '��') {
					$this->assign('sign', '��');
				} else
					if ($rows['sign'] == '��') {
						$this->assign('sign', '��');
					};

			if ($rows['orderstate'] == '���ύ') {
					$this->assign('orderstate', '���ύ');
				} else if ($rows['orderstate'] == '���õ�') {
						$this->assign('orderstate', '���õ�');
					};
			if($rows['shipCondition'] == '0' ){
				$this->assign ( 'shipCondition', '��������' );
			}else if($rows['shipCondition'] == '1' ){
				$this->assign ( 'shipCondition', '֪ͨ����' );
			}else{
				$this->assign ( 'shipCondition', '' );
			}
			$orderTempCode = explode ( ',', $rows ['orderTempCode'] );
			$this->assign ( 'orderTempCode', $this->service->TempOrderView ( $orderTempCode, $_GET ['skey'] ) );
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view');
		}else{
            $rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
            //��ͬ�ı�Ȩ��
		    $rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_lease2' );
            $rows = $this->service->initEdit($rows);

            foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }

			if ($perm == 'signIn') {
				if ($rows['sign'] == '��') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '��') {
						$this->assign('signNo', 'checked');
					};

				if ($rows['orderstate'] == '���ύ') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '���õ�') {
						$this->assign('orderstateNo', 'checked');
					};
				$this->assign('signName', $_SESSION['USERNAME']);
				$this->assign('signNameId', $_SESSION['USER_ID']);
				$this->assign('signDate', date('Y-m-d'));
				$this->showDatadicts(array (
					'customerType' => 'KHLX'
				), $rows['customerType']);
				$this->showDatadicts(array (
					'invoiceType' => 'FPLX'
				), $rows['invoiceType']);
				$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
				$this->display('signin');
			} else {

    			if($rows['sign'] == '��'){
                	$this->assign('signYes' , 'checked');
                }else if($rows['sign'] == '��'){
                	$this->assign('signNo'  , 'checked');
                };

                if($rows['orderstate'] == '���ύ'){
                	$this->assign('orderstateYes' , 'checked');
                }else if($rows['orderstate'] == '���õ�'){
                	$this->assign('orderstateNo'  , 'checked');
                };
    			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
    			$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
    			$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
    			$this->display('edit');
    		}
        }
	}

	/**
	 * ��дint
	 */
	function c_toViewForAudit() {
		$rows = $this->service->get_d($_GET['id']);

        $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
        //��ͬ�ı�Ȩ��
    	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_lease2' );

        $rows = $this->service->initView($rows);

        foreach ($rows as $key => $val) {
            $this->show->assign($key, $val);
        }

         if ($rows['sign'] == '��') {
				$this->assign('sign', '��');
			} else
				if ($rows['sign'] == '��') {
					$this->assign('sign', '��');
				};

		if ($rows['orderstate'] == '���ύ') {
				$this->assign('orderstate', '���ύ');
			} else if ($rows['orderstate'] == '���õ�') {
					$this->assign('orderstate', '���õ�');
				};
		if($rows['shipCondition'] == '0' ){
			$this->assign ( 'shipCondition', '��������' );
		}else if($rows['shipCondition'] == '1' ){
			$this->assign ( 'shipCondition', '֪ͨ����' );
		}else{
			$this->assign ( 'shipCondition', '' );
		}
		$orderTempCode = explode ( ',', $rows ['orderTempCode'] );
		$this->assign ( 'orderTempCode', $this->service->TempOrderView ( $orderTempCode, $_GET ['skey'] ) );
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('view');
	}

    /**
     * �����޸�
     */
    function c_productedit() {
		if(!isset($this->service->this_limit['�����޸�'])||$this->service->this_limit['�����޸�'] != 1){
			echo "û�������޸ĵ�Ȩ�ޣ�����ϵOA����Ա��ͨ";
			exit();
		}
		$this->permCheck();
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$rows = $this->service->get_d($_GET['id']);
//		foreach ($rows['rentalcontractequ'] as $k => $v){
//        	 if($v['purchasedNum'] > 0 || $v['issuedShipNum'] > 0){
//                   echo "�ú�ͬ��ִ�з�����ɹ���������ѡ���������޸�����";
//                   exit();
//        	 }
//        }
            $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
            $rows = $this->service->editProduct($rows);
             foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }
    			if($rows['sign'] == '��'){
                	$this->assign('signYes' , 'checked');
                }else if($rows['sign'] == '��'){
                	$this->assign('signNo'  , 'checked');
                };

                if($rows['orderstate'] == '���ύ'){
                	$this->assign('orderstateYes' , 'checked');
                }else if($rows['orderstate'] == '���õ�'){
                	$this->assign('orderstateNo'  , 'checked');
                };
    			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			   $this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
    			$this->display('productedit');
    		}
	/**��ת�����޺�ͬ���ҳ��
	*author can
	*2011-6-2
	*/
	function c_toChange(){
   	    $changer = isset ($_GET['changer'])? $_GET['changer'] :null;
   	    $changeC = isset ($_GET['changeC'])? $_GET['changeC'] :null;
   	    $changeLogDao = new model_common_changeLog ('rentalcontract');
		if($changeLogDao->isChanging($_GET['id'])){
         msgGo ( "�ú�ͬ���ڱ�������У��޷����." );
         }
		$rows = $this->service->get_d($_GET['id']);
  	    $rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
		$rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}

		if($rows['sign'] == '��'){
        	$this->assign('signYes' , 'checked');
        }else if($rows['sign'] == '��'){
        	$this->assign('signNo'  , 'checked');
        };

        if($rows['orderstate'] == '���ύ'){
        	$this->assign('orderstateYes' , 'checked');
        }else if($rows['orderstate'] == '���õ�'){
        	$this->assign('orderstateNo'  , 'checked');
        };
		$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
		$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
			$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
		$this->assign('changer', $changer);
		$this->assign('changeC', $changeC);
		$this->display('change');

	}

	/**������޺�ͬ
	*author can
	*2011-6-1
	*/
	function c_change() {
		try {
			$changer=isset($_GET['changer'])?$_GET['changer']:null;
			$changeC=isset($_GET['changeC'])?$_GET['changeC']:null;
			$infoArr = $_POST ['rentalcontract'];
			$isDel="0";
			 foreach($infoArr['rentalcontractequ'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['rentalcontractequ'][$key]);
				}
				if(array_key_exists ("isDel",$val)){
					//�ж������Ƿ����´﷢�����´�ɹ����´�����
                   $fsql="select count(id) as isExe from oa_lease_equ where (issuedPurNum > 0 or issuedProNum >0 or issuedShipNum>0) and id=".$val['oldId']." ";
                   $isExeArr = $this->service->_db->getArray($fsql);
                    $isExe = $isExeArr[0]['isExe'];
					$isDel="1";
				}
			}
			foreach($infoArr['rentalcontractequTemp'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['rentalcontractequTemp'][$key]);
				}
			}
		   if($isDel=="1" && $isExe != "0"){
	         $formName="���޺�ͬ�����ɾ���ϣ�";
//	         $formName="���޺�ͬ����";
           }else{
           	 $formName="���޺�ͬ����";
           }
			$id = $this->service->change_d ( $infoArr );
			if($changer=="changer"){
				 if($changeC == "changeC"){
				 	 echo "<script>this.location='controller/contract/rental/ewf_mychangeC_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				 }else{
				 	echo "<script>this.location='controller/contract/rental/ewf_mychange_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				 }

			}else{
				echo "<script>this.location='controller/contract/rental/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
			}
		} catch ( Exception $e ) {
			msgBack2 ( "���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage () );
		}
	}

/**********************************************************************************************************/
	/**
	 * �رպ�ͬҳ��
	 */
	function c_CloseOrder() {
		$row = $this->service->get_d($_GET['id']);
		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('userName', $_SESSION['USERNAME']);
		$this->assign('dateTime', date('Y-m-d'));
		$this->display('closeorder');
	}
	/**
	 * �رպ�ͬ
	 */
	function c_close($isEditInfo = false) {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$object = $_POST[$this->objName];
		$id = $this->service->close_d($object, $isEditInfo);
		if ($id && $_GET['actType'] == "app") {
           succ_show('controller/contract/rental/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
		}else {
			msg('�رճɹ���');
		}
	}

	/**
     * �رպ�ͬ�����鿴ҳ
     */
    function c_toCloseView() {

    	$rows = $this->service->get_d($_GET['id']);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->assign('view', $this->service->closeOrderView($_GET['id']));
    	$this->display('closeview');
    }


	/**
	 * �ر�����tab
	 */
	function c_toCloseAuditTab() {
		$this->display('closeaudittab');
	}

	/**
	 * δ�����Ĺر�
	 */
	function c_toCloseAuditUndo() {
		$this->display('closeauditundo');
	}

	/**
	 * δ����Json
	 */
	function c_jsonCloseAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '���޺�ͬ�쳣�ر�����';
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �������Ĺر�
	 */
	function c_toCloseAuditDone() {
		$this->display('closeauditdone');
	}

	/**
	 * ������Json
	 */
	function c_closeAuditYesJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '���޺�ͬ�쳣�ر�����';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
/**************************************************************/

    /**
     * ��ͬǩ��
     */
	function c_toSign() {
				$this->permCheck();//��ȫУ��
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$tablename = isset ($_GET['tablename']) ? $_GET['tablename'] : null;
		$rows = $this->service->get_d($_GET['id']);


		//��Ⱦ�ӱ�

			//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_lease2' );
	    $rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
				if ($rows['sign'] == '��') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '��') {
						$this->assign('signNo', 'checked');
					};

				if ($rows['orderstate'] == '���ύ') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '���õ�') {
						$this->assign('orderstateNo', 'checked');
					};
				$this->assign('signName', $_SESSION['USERNAME']);
				$this->assign('signNameId', $_SESSION['USER_ID']);
				$this->assign('signDate', date('Y-m-d'));

				$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
				$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
				$this->display('signin');
	}


	/**
	 * ��ͬǩ��
	 */
	function c_signInVerify($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->signin_d($object, $isEditInfo)) {
			msgRF('ǩ����ɣ�');
		}
	}

	/**
	 * by maizp
	 * תΪ��ʽ��ͬ
	 */
	 function c_toBecomeContract() {
		$this->permCheck();//��ȫУ��
		$this->assign('orderInput', ORDER_INPUT);
		$rows = $this->service->get_d($_GET['id']);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_lease2' );
		//��Ⱦ�ӱ�
		$rows = $this->service->becomeEdit($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $rows['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $rows['invoiceType']);
		$this->showDatadicts(array (
			'orderNature' => 'XSHTSX'
		), $rows['orderNature']);
		$this->assign("pId", $_GET['id']);
		$this->display("becomecontract");
	}

		/**
	 * by maizp
	 */
	 function c_become($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$orderCodeDao = new model_common_codeRule();
       if($object['orderInput'] == "1"){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$object['tenantId']);
			if($this->service->becomeEdit_d($object)){
				msgRF('��תΪ��ʽ��ͬ');
			}
		}else if($object['orderInput'] == "0"){
			if($this->service->becomeEdit_d($object)){
				msgRF('��תΪ��ʽ��ͬ');
			}
		}else {
			msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
		}
	}

	/************************��������**************************/
	/**
	 * ��ת���ҵ�����Tabҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 ����02:46:46
	 * @author qian
	 */
	function c_toApprovalTab () {
		$this->display( 'tab-app' );
	}

	/**
	 * �������б�ҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����04:20:30
	 * @author qian
	 */
	function c_toApprovalNo () {
		$this->display( 'approvalno' );
	}
	/**����ĺ�ͬ����Tab
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditTab () {
		$this->display('change-audit-tab');
	}
	/**δ�����ı����ͬ
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditNo(){
		$this->display('change-auditno');
	}
	/**�������ı����ͬ
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditYes(){
		$this->display('change-audityes');
	}
	/**δ�����ı����ͬPageJson
	*author can
	*2011-6-2
	*/
	function c_changeAuditNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('change_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**�������ı�������ͬPageJson
	*author can
	*2011-6-2
	*/
	function c_changeAuditYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('change_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * add by can 2011-06-01
	 * ȷ�Ϻ�ͬ���������ת��δ�����ĺ�ͬ�б�ҳ
	 *
	 */
	function c_confirmChangeToApprovalNo() {
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			$objId = $folowInfo ['objId'];
			if (! empty ( $objId )) {
				$contract = $this->service->get_d ( $objId );
				$contract1 = $this->service->get_d ( $contract['originalId'] );

				$changeLogDao = new model_common_changeLog ( 'rentalcontract' );
				$changeLogDao->confirmChange_d ( $contract );
				    if ($contract ['ExaStatus'] == "���") { //������
				            $sql = "update oa_sale_lease set isBecome = 0 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
							//�������޽�����ת�������ϣ�����ɾ�����һ��
							$dao = new model_projectmanagent_borrow_toorder();
			                $dao->getRelOrderequ($contract['originalId'],"lease","change",$objId);
					}else{
						$orderInfo = $this->service->get_d ( $contract['originalId'] );
						foreach ( $contract['rentalcontractequ'] as $k => $v){
		                     $contractEqu1[$k] = $v['productId'];
		                     $contractNum1[$k] = $v['number'];
						}
						foreach ( $contract1['rentalcontractequ'] as $k => $v){
		                     $contractEqu2[$k] = $v['productId'];
		                     $contractNum2[$k] = $v['number'];
						}
					      //�������޽�����ת��������
					      	  $dao = new model_projectmanagent_borrow_toorder();
			                  $borrowChange = $dao->getRelOrderequ($contract['originalId'],"lease","changeE",$objId);
						if (($contractEqu1 != $contractEqu2) || ($contractNum1 != $contractNum2)){
							   $this->service->updateOrderShipStatus_d($contract['originalId']);
						}else if($borrowChange == '1'){
							$sql = "update oa_sale_lease set state = 2,DeliveryStatus = 10 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
						}
						//�������� ������������Զ����ɹ黹��
				          $toorderDao = new model_projectmanagent_borrow_toorder();
				          $toorderDao->findLoan($objId,"lease");
					}
			}
		}

		//��ֹ�ظ�ˢ��
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//��ֹ�ظ�ˢ��
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=contract_rental_rentalcontract&action=allAuditingList'</script>";
		}
		//$this->display ( 'change-approvalNo' );
	}

 /**
     * ȡ���������
     */
    function c_cancelBecome(){
        $orderId = $_GET['id'];
        $sql = "update oa_sale_lease set isBecome = 0 where id = $orderId";
        $this->service->query($sql);
        return $orderId;
    }

	/**
	 * ��ͬ�鿴ҳ��
	 */
	function c_toViewTab() {
//        $this->permCheck ();//��ȫУ��
		$this->assign('id', $_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/**���������ͬ�Ĳ鿴ҳ��
	*author can
	*2011-6-1
	*/
	function c_toReadTab() {
        $this->permCheck();
		$this->assign('id', $_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$originalKey=$this->md5Row($rows ['originalId']);
		$this->assign ( 'orderCode', $rows ['orderCode'] );
		$this->assign ( 'originalId', $rows ['originalId'] );
		$this->assign ( 'originKey', $originalKey);
		$this->display('read-tab');
	}


	/**
	 * δ����json
	 */
	function c_pageJsonNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �������б�ҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����04:20:30
	 * @author qian
	 */
	function c_toApprovalYes () {
		$this->display( 'approvalyes' );
	}

	/**
	 * ������json
	 */
	function c_pageJsonYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


/***************************************************************************************************************/

	/**
	 * ��ͬ��ϸ���ݿ�
	 */
	function c_toViewRContract(){
		$this->show->display( $this->objPath . '_' .$this->objName .'-view' );
	}

	/**
	 * ��ת�����޺�ͬTabҳ��
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����02:58:32
	 * @author qian
	 */
	function c_toRentalConTab () {
		$this->show->display( $this->objPath . '_' . $this->objName . '-tab-add' );
	}

	/**
	 * ��ת���½������ͬҳ��
	 * ��ָ�������ⶫ���ĺ�ͬҳ��
	 */
	function c_toAddRContract () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-add' );
	}


	/**
	 * ��ת���༭���޺�ͬҳ��
	 */
	function c_toEditRContract () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-edit' );
	}

	/**
	 * ���б��
	 *
	 * @return return_type
	 * @date 2011-1-22 ����01:45:06
	 * @author qian
	 */
	function c_toAppChange () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-change' );
	}

	/**
	 * ��ת���б�ҳ��Tabҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����04:03:54
	 * @author qian
	 */
	function c_toRentConTabList () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-tab-list' );
	}

	/**
	 * ���ύ�����б�ҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����04:20:30
	 * @author qian
	 */
	function c_toWaitApp () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list-wait' );
	}

	/**
	 * ������б�ҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����04:20:30
	 * @author qian
	 */
//	function c_toChange () {
//		$this->show->display( $this->objPath . '_' .$this->objName .'-list-change' );
//	}

	/**
	 * �ҵ�����������б�ҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����04:20:30
	 * @author qian
	 */
	function c_toMyChangeList () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list-mychange' );
	}

	/**
	 * @author qian
	 * ��ɺ�ͬ��Ҫȷ��
	 */
	function c_toVerify(){
		$this->show->display( $this->objPath . '_' . $this->objName . '-verify' );
	}

	/**
	 * ������б�ҳ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����04:20:30
	 * @author qian
	 */
	function c_toFinished () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list-finish' );
	}

	/**
	 * ��ת����ͬ�б�--������ĺ�ͬ
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 ����05:09:44
	 * @author qian
	 */
	function c_toMyRContractList () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list' );
	}

	/**
	 * �鿴�������
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 ����02:23:49
	 * @author qian
	 */
	function c_toViewChange () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-view-change' );
	}

	/**
	 * �ҵ����޺�ͬ�������--Tab
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 ����03:10:34
	 * @author qian
	 */
	function c_toChangeTab () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-tab-change' );
	}

	/**
	 * �ҵ����޺�ͬ�������--δ����
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 ����03:12:08
	 * @author qian
	 */
	function c_toChangeNo () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-changeNo' );
	}

	/**
	 * �ҵ����޺�ͬ�������--������
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 ����03:13:21
	 * @author qian
	 */
	function c_toChangeYes () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-changeYes' );
	}

	/**
	 * @author qian
	 * ��ת��������������ѡ��ҳ��
	 */
	 function c_toChooseWorkFlow(){
	 	$this->show->display( $this->objPath . '_' .$this->objName .'-toChoose' );
	 }

	/**
	 * @author qian
	 * �ҵ����޺�ͬ����������
	 */
	 function c_toWorkFlow(){
	 	$this->show->display( $this->objPath . '_' .$this->objName .'-workflow' );
	 }

	 /**
	 * @author qian
	 * ������������Ĳ鿴ҳ��
	 */
	 function c_toViewWorkFlow(){
	 	$this->show->display( $this->objPath . '_' . $this->objName . '-view2' );
	 }

	 /**
	 * @author qian
	 * ����
	 */
	 function c_approvalWorkFlow(){
	 	$this->show->display( $this->objPath . '_' . $this->objName . '-approval' );
	 }

	 /************************************************************************************************/
	     /**
     * �ϲ���ʱ��ͬ ��ȡ��ͬ����
     */
	function c_ajaxList() {
		$rows = $this->service->get_d ( $_POST ['id'] );
		$rows = util_jsonUtil::encode ( $rows );
		echo $rows;
	}


	/**
	 * @ ajax�ж���
	 * ��ʱ��ͬ��
	 */
	function c_ajaxOrderTempCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderTempCode']) ? $_GET['orderTempCode'] : false;
        $projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	function c_ajaxOrderCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderCode']) ? $_GET['orderCode'] : false;

		 $projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/*
	 * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
	 */
	function c_ajaxdeletesOrder() {
		//$this->permDelCheck ();
		$orderId = $_POST ['id'];
		$orderType = $_POST ['type'];
		try {
			//�����Ƿ��� ������ת���۵Ĺ������� ����ɾ��
			$dao = new model_projectmanagent_borrow_toorder();
			$dao->getRelOrderequ($orderId,$orderType);
			$del = $this->service->deletes_d ( $orderId );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
   /************************************************************************************************/
   /**
    * ��������鿴
    */
   function c_toShipView(){
	    $rows = $this->service->get_d($_GET['id']);
		//��Ⱦ�ӱ�
			//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
		$rows = $this->service->initView($rows);


		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
            if ($rows['sign'] == '��') {
					$this->assign('sign', '��');
				} else
					if ($rows['sign'] == '��') {
						$this->assign('sign', '��');
					};

			if ($rows['orderstate'] == '���ύ') {
					$this->assign('orderstate', '���ύ');
				} else
					if ($rows['orderstate'] == '���õ�') {
						$this->assign('orderstate', '���õ�');
					};
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view-ship');
		}
		/*******************************************************************************************************/

    /**********************��Ʊ�տ�Ȩ�޲���*********************************/
    /**
     * ��Ʊtab
     */
    function c_toInvoiceTab(){
        $obj = $_GET['obj'];
    	$this->permCheck($obj['objId']);
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['���޺�ͬ��Ʊtab'])){
            $url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
        }
    }

    /**
	 * ��Ʊ����tab
	 */
	function c_toInvoiceApplyTab() {
		$obj = $_GET ['obj'];
		if ($this->service->isKeyMan_d ( $obj ['objId'] ) || ! empty ( $this->service->this_limit ['���޺�ͬ��Ʊtab'] )) {
			$url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj ['objType'] . '&obj[objCode]=' . $obj ['objCode'] . '&obj[objId]=' . $obj ['objId'] . '&skey=' . $_GET ['skey'];
			succ_show ( $url );
		} else {
			echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
		}
	}


    /**
     * ����tab
     */
    function c_toIncomeTab(){
        $obj = $_GET['obj'];
    	$this->permCheck($obj['objId']);
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['���޺�ͬ����tab'])){
            $url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
        }
    }

    /**********************��Ʊ�տ�Ȩ�޲���*********************************/
    /*
     * by MaiZhenPeng
     */
     function c_toContractListAll(){
		$this->display("listAll");
	}
	/***************************************/


/**
 * �ҵĺ�ͬ -- xxx �����ڵ���Ȩ�ޣ�
 */
 function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$orderDao = new model_projectmanagent_order_order();
//        $export = isset ( $service->this_limit ['������ͬ����'] ) ? $service->this_limit ['������ͬ����'] : null;

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
//		foreach($rows as $k => $v){
//             $rows[$k]['exportOrder'] = $export;
//		   }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myOrderPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $orderDao = new model_projectmanagent_order_order();
        $export = isset ( $service->this_limit ['������ͬ����'] ) ? $service->this_limit ['������ͬ����'] : null;
		//$service->asc = false;
		$rows = $service->pageBySqlId('select_default');

		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$rows = $service->isGoodsReturn ( $rows);
		foreach($rows as $k => $v){
             $rows[$k]['exportOrder'] = $export;
		   }
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
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		if($_REQUEST['prinvipalId'] != $_SESSION['USER_ID']){
			$orderDao = new model_projectmanagent_order_order();
			$rows = $orderDao->filterContractMoney_d ($rows,$this->service->tbl_name);
		}
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
	 * �������ʹ��
	 */
	function c_orderForIncomePj(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		$orderDao = new model_projectmanagent_order_order();
		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �ӱ�ѡ�����ϴ�������
	 */
     function c_ajaxorder(){
        $isEdit = isset($_GET['isEdit'])?$_GET['isEdit'] : null;
        $configInfo = $this->service->c_configuration( $_GET['id'],$_GET['Num'],$_GET['trId'],$isEdit);
         echo $configInfo[0];
     }

    /**
     * ����ͨ�������ʼ��������б�
     */
     function c_configOrder(){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
       $this->service->configOrder_d($objId);
       $contract = $this->service->get_d ( $objId );
        if ($contract ['ExaStatus'] == "���") {
          $toorderDao = new model_projectmanagent_borrow_toorder();
          $toorderDao->findLoan($objId,"lease");
        }
       echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
     }

     /**********************************************************************************************/
/**
 * ��֤��ͬ�ŵ�Ψһ��
 */
function c_ajaxCode(){
		$service = $this->service;
		$projectName = isset ( $_GET ['ajaxOrderCode'] ) ? $_GET ['ajaxOrderCode'] : false;
		$orderId = isset ( $_GET ['id'] ) ? $_GET ['id'] : false;
		$projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );
		$isRepeat = $service->isRepeat ( $searchArr, $orderId );

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		}
		 }
function c_ajaxTempCode(){

		$service = $this->service;
		$projectName = isset ( $_GET ['ajaxOrderTempCode'] ) ? $_GET ['ajaxOrderTempCode'] : false;
		$orderId = isset ( $_GET ['id'] ) ? $_GET ['id'] : false;
		$projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );
		$isRepeat = $service->isRepeat ( $searchArr, $orderId );

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		} }
}
?>