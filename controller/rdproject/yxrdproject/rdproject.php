<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @description: �з���ͬController��
 * @date 2010-12-1 ����04:22:23 for LiuB
 */
class controller_rdproject_yxrdproject_rdproject extends controller_base_action {
	function __construct(){
		$this->objName = "rdproject";
		$this->objPath = "rdproject_yxrdproject";
		parent::__construct();
	}

    /**
     * �����з���ͬ��ת
     */
     function c_toAddResearch(){
     	$this->assign('orderInput' , ORDER_INPUT );
     	$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('saleman', $_SESSION['USERNAME']);
		$this->assign('salemanId', $_SESSION['USER_ID']);
		$this->assign('orderPrincipal', $_SESSION['USERNAME']);
		$this->assign('orderPrincipalId', $_SESSION['USER_ID']);
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
			$this->showDatadicts(array ( 'orderNature' => 'YFHTSX' ), $rows['orderNature']);
            $orderChance = new model_rdproject_yxrdproject_rdprojectequ();
			$chanceequ = $orderChance->ChanceOrderEqu($rows['chanceequ']);

			$this->assign('chance', $chanceequ[0]);
			$this->assign('productNumber', $chanceequ[1]);
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
/**********************************************************************************/
    /**
     * δ�����ĺ�ͬ
     */
    function c_toListWtj(){
    	$this->assign("userId" , $_SESSION['USER_ID']);
		$this->display("listwtj");
    }
	/**
	 * �����еĺ�ͬ�б�
	 */
	function c_toListWsp() {
		$this->assign("userId" , $_SESSION['USER_ID']);
		$this->display("listwsp");
	}
   /**
    * ��ִ�е��з���ͬ
    */
    function c_toListZzx(){
    	$this->assign("userId" , $_SESSION['USER_ID']);
    	$this->display('listzzx');
    }
     /**
    * ����ɵ��з���ͬ
    */
    function c_toListYwc(){
    	$this->assign("userId" , $_SESSION['USER_ID']);
    	$this->display('listywc');
    }
    /**
     * �ѹرյ��з���ͬ
     */
    function c_toListYwg(){
    	$this->assign("userId" , $_SESSION['USER_ID']);
    	$this->display('listywg');
    }
 /**********************************************************************************/

	/**
	 * �����������
	 */
	function c_add() {
        $orderInfo = $_POST[$this->objName];
//        if($orderInfo['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $orderInfo['sign'] == "��" ){
//				$orderInfo['orderTempCode'] = "LS".$orderInfo['orderTempCode'];
//			   $orderInfo['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_rdproject",$orderInfo['cusNameId']);
//			}else if($orderInfo['sign'] == "��"){
//				$orderInfo['orderCode'] = $orderCodeDao->contractCode ("oa_sale_rdproject",$orderInfo['cusNameId']);
//			}
//			$id = $this->service->add_d($orderInfo);
//		}else if($orderInfo['orderInput'] == "0"){
//			if(!empty($orderInfo['orderTempCode'])){
//				$orderInfo['orderTempCode'] = $orderInfo['orderTempCode'];
//			}
//			$id = $this->service->add_d($orderInfo);
//		}else {
//			msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
//		}

		$id = $this->service->add_d($orderInfo);

        //�ж��Ƿ�ֱ���ύ����
		if($id && $_GET['act'] == "app"){
            succ_show('controller/rdproject/yxrdproject/ewf_index1.php?actTo=ewfSelect&billId=' . $id );
        }else if ($id) {
			msgGo('��ӳɹ���','?model=rdproject_yxrdproject_rdproject&action=toAddResearch');
		}
//		$this->listDataDict();
	}


	/**
	 * �̻�ת�з���ͬ
	 */
	function c_chanceAdd() {
		$orderInfo = $_POST[$this->objName];
//		if($orderInfo['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $orderInfo['sign'] == "��" ){
//			   $orderInfo['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_rdproject",$orderInfo['cusNameId']);
//			}else if($orderInfo['sign'] == "��"){
//				$orderInfo['orderCode'] = $orderCodeDao->contractCode ("oa_sale_rdproject",$orderInfo['cusNameId']);
//			}
//			$id = $this->service->add_d($orderInfo);
//		}else if($orderInfo['orderInput'] == "0"){
//			$id = $this->service->add_d($orderInfo);
//		}else {
//			msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
//		}
		$id = $this->service->add_d($orderInfo);
        //�ж��Ƿ�ֱ���ύ����
		if($id && $_GET['act'] == "app"){
            succ_show('controller/rdproject/yxrdproject/ewf_index.php?actTo=ewfSelect&billId=' . $id );
        }else if ($id) {
			msgRF('��ӳɹ���');
		}
//		$this->listDataDict();
	}



	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		$orderCodeDao = new model_common_codeRule();
	    if($object['sign'] == "��" && $object['orderCode'] == ''){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_rdproject",$object['cusNameId']);
		}
		if ($this->service->edit_d($object, $isEditInfo)) {
			msgRF('�༭�ɹ���');
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
	 * ��дinit
	 */
   function c_init() {
//   	  $this->permCheck();
   	  $perm = isset ($_GET['perm'])? $_GET['perm'] :null;
   	  $rows = $this->service->get_d($_GET['id']);

		if ($perm == 'view') {
            //Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
            if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['orderPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
                $rows = $this->service->filterWithoutField('�з���ͬ���',$rows,'form',array('orderMoney','orderTempMoney'));
                $rows['rdprojectequ'] = $this->service->filterWithoutField('�з��豸���',$rows['rdprojectequ'],'list',array('price','money'));
            }

            $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
            //��ͬ�ı�Ȩ��
            if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['orderPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
	             if(!empty($this->service->this_limit ['�з���ͬ���'])){
					$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_rdproject2' );
				}else{
					$rows ['file2']="";
				}
            }else{
            	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_rdproject2' );
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
			$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_rdproject2' );
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
				$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
				$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
				$this->showDatadicts(array ( 'orderNature' => 'YFHTSX' ), $rows['orderNature']);
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
    			$this->showDatadicts(array ( 'orderNature' => 'YFHTSX' ), $rows['orderNature']);
    			$this->display('edit');
    		}
        }
   }

   /**
	 * ��дinit
	 */
   function c_toViewForAudit() {
//   	  $this->permCheck();
		$perm = isset ($_GET['perm'])? $_GET['perm'] :null;
		$rows = $this->service->get_d($_GET['id']);

        $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
        //��ͬ�ı�Ȩ��
    	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_rdproject2' );

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
   	    $perm = isset ($_GET['perm'])? $_GET['perm'] :null;
   	    $rows = $this->service->get_d($_GET['id']);
//   	    foreach ($rows['rdprojectequ'] as $k => $v){
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
	/**��ת���з���ͬ���ҳ��
	*author can
	*2011-6-2
	*/
	function c_toChange(){
   	  $changer = isset ($_GET['changer'])? $_GET['changer'] :null;
   	  $changeC = isset ($_GET['changeC'])? $_GET['changeC'] :null;
   	  $changeLogDao = new model_common_changeLog ('rdproject');
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
			$this->showDatadicts(array ( 'orderNature' => 'YFHTSX' ), $rows['orderNature']);
		$this->assign('changer', $changer);
		$this->assign('changeC', $changeC);
		$this->display('change');

	}

	/**����з���ͬ
	*author can
	*2011-6-1
	*/
	function c_change() {
		try {
			$changer=isset($_GET['changer'])?$_GET['changer']:null;
			$changeC=isset($_GET['changeC'])?$_GET['changeC']:null;
			$infoArr =  $_POST ['rdproject'];
			$isDel="0";
				foreach($infoArr['rdprojectequ'] as $key =>$val){
					if (empty($val['productName'])){
						unset($infoArr['rdprojectequ'][$key]);
					}
					if(array_key_exists ("isDel",$val)){
						//�ж������Ƿ����´﷢�����´�ɹ����´�����
                    $fsql="select count(id) as isExe from oa_rdproject_equ where (issuedPurNum > 0 or issuedProNum >0 or issuedShipNum>0) and id=".$val['oldId']." ";
                    $isExeArr = $this->service->_db->getArray($fsql);
                     $isExe += $isExeArr[0]['isExe'];
					   $isDel="1";
					   $purchaseArr[] = $this->service->purchaseMail($val['oldId']);
				    }
				}
				//���ɾ�����������´�ɹ��ģ����ʼ����ɹ���
            if(!empty($purchaseArr[0])){
               //��ȡĬ�Ϸ�����
		       include (WEB_TOR."model/common/mailConfig.php");
		       $toMailId = $mailUser['contractChangepurchase']['sendUserId'];
		       $emailDao = new model_common_mail();
		       if(empty($infoArr['orderCode'])){
		       	  $orderCode = $infoArr['orderTempCode'];
		       }else{
		       	  $orderCode = $infoArr['orderCode'];
		       }
		       //�����ˣ����������䣬title,�ռ���,������Ϣ
			   $emailInfo = $emailDao->contractChangepurchaseMail($_SESSION['USERNAME'],$_SESSION['EMAIL'],"���۱����������",$toMailId,$purchaseArr,$orderCode);
            }
				foreach($infoArr['rdprojectequTemp'] as $key =>$val){
					if (empty($val['productName'])){
						unset($infoArr['rdprojectequTemp'][$key]);
					}
				}
			if($isDel=="1" && $isExe != "0"){
	         $formName="�з���ͬ�����ɾ���ϣ�";
//	         $formName="�з���ͬ����";
           }else{
           	 $formName="�з���ͬ����";
           }
			$id = $this->service->change_d ($infoArr );
			if($changer=="changer"){
				if($changeC == "changeC"){
                    echo "<script>this.location='controller/rdproject/yxrdproject/ewf_mychangeC_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				}else{
					echo "<script>this.location='controller/rdproject/yxrdproject/ewf_mychange_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				}

			}else{
				echo "<script>this.location='controller/rdproject/yxrdproject/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
			}
		} catch ( Exception $e ) {
			msgBack2 ( "���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage () );
		}
	}


/**********************************************************************************************************/

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
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_rdproject2' );
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
		if(!empty($this->service->this_limit ['�з���ͬ���'])){
				$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_rdproject2' );
			}else{
				$rows ['file2']="";
			}
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
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_rdproject",$object['cusNameId']);
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
           succ_show('controller/rdproject/yxrdproject/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
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
		$service->searchArr['workFlow'] = '�з���ͬ�쳣�ر�����';
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
		$service->searchArr['workFlow'] = '�з���ͬ�쳣�ر�����';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
/**************************************************************/

	/*****************************��������***************************/



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

				$changeLogDao = new model_common_changeLog ( 'rdproject' );
				$changeLogDao->confirmChange_d ( $contract );
				   if ($contract ['ExaStatus'] == "���") { //������
				            $sql = "update oa_sale_rdproject set isBecome = 0 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
							//�������޽�����ת�������ϣ�����ɾ�����һ��
							$dao = new model_projectmanagent_borrow_toorder();
			                $dao->getRelOrderequ($contract['originalId'],"rdproject","change",$objId);
					}else{
						$orderInfo = $this->service->get_d ( $contract['originalId'] );
					    foreach ( $contract['rdprojectequ'] as $k => $v){
		                     $contractEqu1[$k] = $v['productId'];
		                     $contractNum1[$k] = $v['number'];
						}
						foreach ( $contract1['rdprojectequ'] as $k => $v){
		                     $contractEqu2[$k] = $v['productId'];
		                     $contractNum2[$k] = $v['number'];
						}
					      //�������޽�����ת�������ϣ�����ɾ�����һ��
							$dao = new model_projectmanagent_borrow_toorder();
			                $borrowChange = $dao->getRelOrderequ($contract['originalId'],"rdproject","change",$objId);
						if (($contractEqu1 != $contractEqu2) || ($contractNum1 != $contractNum2)){
							   $this->service->updateOrderShipStatus_d($contract['originalId']);
						}else if($borrowChange == '1'){
							$sql = "update oa_sale_lease set state = 2,DeliveryStatus = 10 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
						}
						//�������� ������������Զ����ɹ黹��
				          $toorderDao = new model_projectmanagent_borrow_toorder();
				          $toorderDao->findLoan($objId,"rdproject");
					}
			}
		}
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//��ֹ�ظ�ˢ��
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=rdproject_yxrdproject_rdproject&action=allAuditingList'</script>";
		}
		//��ֹ�ظ�ˢ��
	}

   /**
     * ȡ���������
     */
    function c_cancelBecome(){
        $orderId = $_GET['id'];
        $sql = "update oa_sale_rdproject set isBecome = 0 where id = $orderId";
        $this->service->query($sql);
        return $orderId;
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
	 /************************************************************************************************/
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
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['�з���ͬ��Ʊtab'])){
            $url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
        }
    }

    /**
     * ����tab
     */
    function c_toIncomeTab(){
        $obj = $_GET['obj'];
    	$this->permCheck($obj['objId']);
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['�з���ͬ����tab'])){
            $url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
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
		if ($this->service->isKeyMan_d ( $obj ['objId'] ) || ! empty ( $this->service->this_limit ['�з���ͬ��Ʊtab'] )) {
			$url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj ['objType'] . '&obj[objCode]=' . $obj ['objCode'] . '&obj[objId]=' . $obj ['objId'] . '&skey=' . $_GET ['skey'];
			succ_show ( $url );
		} else {
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
	/***********************************************/

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
//        $export = isset ( $service->this_limit ['������ͬ����'] ) ? $service->this_limit ['������ͬ����'] : null;

		//$service->asc = false;
		$rows = $service->pageBySqlId('select_default');
		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$rows = $service->isGoodsReturn ( $rows);
//		foreach($rows as $k => $v){
//             $rows[$k]['exportOrder'] = $export;
//		   }
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
          $toorderDao->findLoan($objId,"rdproject");
        }
       echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
     }


/**********************************************************************************************/
 /**
  * �ϲ���ʱ��ͬ ��ȡ��ͬ����
  */
	function c_ajaxList() {
		$rows = $this->service->get_d ( $_POST ['id'] );
		$rows = util_jsonUtil::encode ( $rows );
		echo $rows;
	}
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
		$isRepeat = $service->isRepeatAll ( $searchArr, $orderId );

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
		$isRepeat = $service->isRepeatAll ( $searchArr, $orderId );

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		} }
}

?>
