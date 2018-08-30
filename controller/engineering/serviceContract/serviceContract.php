<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @description: �����ͬController��
 * @date 2010-12-1 ����04:22:23 LiuB
 */
class controller_engineering_serviceContract_serviceContract extends controller_base_action {
	function __construct(){
		$this->objName = "serviceContract";
		$this->objPath = "engineering_serviceContract";
		parent::__construct();
	}

/***********************************************��������ͨAction����***********************************************/
    /**
     * ��ͬ��Ϣ----�����ͬ��Ϣ
     * by MaiZP
     */
     function c_toContractListAll(){
		$this->display("listAll");
	}


	/*
	 * ��ת��Tab��ǩ��ҳ
	 */
	function c_toAddContractIndex(){
		$this->display("add-index");
	}

	/*
	 * @desription �½��ķ����ͬ�б�Tabҳ
	 * @param tags
	 * @date 2010-12-2 ����02:16:51
	 */
	function c_toContractListIndex () {
		$this->display('list-index');
	}

	/*
	 * @desription ��ת�������ͬ����ҳ��
	 * @param tags
	 * @date 2010-12-2 ����09:59:41
	 */
	function c_toAddContract () {
        $this->assign('orderInput', ORDER_INPUT);
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('saleman', $_SESSION['USERNAME']);
		$this->assign('salemanId', $_SESSION['USER_ID']);
		$this->assign('orderPrincipal', $_SESSION['USERNAME']);
		$this->assign('orderPrincipalId', $_SESSION['USER_ID']);
		$this->assign('createTime' , day_date );
		//����ϵͳ���
		$systemCode = generatorSerial();
		$this->assign('systemCode',$systemCode);

		//���÷�Ʊ���͵������ֵ�
		$this->showDatadicts(array('invoiceType'=>'FPLX'));
		//Ĭ�Ϻ�ͬ�����״̬Ϊ��δ��ˡ�
			$this->assign('ExaStatus','δ���');
			$this->assign('status','��ͬδ����');
			$this->assign('missionStatus','δ�´�');
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
			$this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
            $orderChance = new model_engineering_serviceContract_serviceequ();
			$chanceequ = $orderChance->ChanceOrderEqu($rows['chanceequ']);
			$orderCus = new model_engineering_serviceContract_customizelist();
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
/*******************************************************************************************/
    /**
     * δ�����ķ����ͬ
     */
   function c_toMyorderWtj(){
   	   $this->assign("userId" , $_SESSION['USER_ID']);
		$this->display('myorder-wtj');
   }
	/*
	 * @desription �����еĺ�ͬ
	 * 	 * @param tags
	 * @date 2010-12-2 ����02:26:19
	 */
	function c_toUnDoContractList () {
		$this->assign("userId" , $_SESSION['USER_ID']);
		$this->display('listUndo');
	}

    /**
     * ��ִ�еķ����ͬ
     */
     function c_toMyorderZzx(){
     	$this->assign("userId" , $_SESSION['USER_ID']);
     	$this->display('myorder-zzx');
     }

     /**
      * �ѹرյķ����ͬ
      */
      function c_toMyorderYwg(){
      	$this->assign("userId" , $_SESSION['USER_ID']);
      	$this->display('myorder-ywg');
      }
     /**
      * ����ɵķ����ͬ
      */
     function c_toMyorderYwc(){
     	$this->assign("userId" , $_SESSION['USER_ID']);
     	$this->display('myorder-ywc');
     }

    /**��ת����ͬ���ҳ��
	*author can
	*2011-6-2
	*/
	function c_toChange(){
	  $changeLogDao = new model_common_changeLog ( 'servicecontract' );
	  if($changeLogDao->isChanging($_GET['id'])){
	  		msgGo ( "�ú�ͬ���ڱ�������У��޷����." );
	  }
   	  $changer = isset ($_GET['changer'])? $_GET['changer'] :null;
   	  $changeC = isset ($_GET['changeC'])? $_GET['changeC'] :null;

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
        if($rows['isShipments'] == '��'){
        	$this->assign('isShipYes' , 'checked');
        }else if($rows['isShipments'] == '��'){
        	$this->assign('isShipNo' , 'checked');
        };
		$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
		$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
			$this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
		$this->assign('changer', $changer);
		$this->assign('changeC', $changeC);
		$this->display('change');

	}

	/**��������ͬ
	*author can
	*2011-6-1
	*/
	function c_change() {
		try {
			$changer=isset($_GET['changer'])?$_GET['changer']:null;
			$changeC=isset($_GET['changeC'])?$_GET['changeC']:null;
             $infoArr =  $_POST ['serviceContract'];
             $isDel="0";
			foreach($infoArr['serviceequ'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['serviceequ'][$key]);
				}
				if(array_key_exists ("isDel",$val)){
					//�ж������Ƿ����´﷢�����´�ɹ����´�����
                   $fsql="select count(id) as isExe from oa_service_equ where (issuedPurNum > 0 or issuedProNum >0 or issuedShipNum>0) and id=".$val['oldId']." ";
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
			foreach($infoArr['serviceequTemp'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['serviceequTemp'][$key]);
				}
			}
		   if($isDel=="1" && $isExe != "0"){
	         $formName="�����ͬ�����ɾ���ϣ�";
//	         $formName="�����ͬ����";
           }else{
           	 $formName="�����ͬ����";
           }
			$id = $this->service->change_d ( $infoArr );
			if($changer=="changer"){
				if($changeC == "changeC"){
					echo "<script>this.location='controller/engineering/serviceContract/ewf_mychangeC_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				}else{
					echo "<script>this.location='controller/engineering/serviceContract/ewf_mychange_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				}
			}else{
				echo "<script>this.location='controller/engineering/serviceContract/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
			}
		} catch ( Exception $e ) {
			msgBack2 ( "���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage () );
		}
	}



/*******************************************************************************************/
/**
 * ��дinit
 */
   function c_init() {
//   	  $this->permCheck();//��ȫУ��
   	  $perm = isset ($_GET['perm'])? $_GET['perm'] :null;
   	  $rows = $this->service->get_d($_GET['id']);


		if ($perm == 'view') {

            //Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
            if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['orderPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
                $rows = $this->service->filterWithoutField('�����ͬ���',$rows,'form',array('orderMoney','orderTempMoney'));
                $rows['serviceequ'] = $this->service->filterWithoutField('�����豸���',$rows['serviceequ'],'list',array('price','money'));
            }

            $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
            //��ͬ�ı�Ȩ��

			 if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['orderPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
	            if(!empty($this->service->this_limit ['�����ͬ���'])){
					$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_service2' );
				}else{
					$rows ['file2']="";
				}
            }else{
            	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_service2' );
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
				$this->assign ( 'shipCondition', '(��������)' );
			}else if($rows['shipCondition'] == '1' ){
				$this->assign ( 'shipCondition', '(֪ͨ����)' );
			}else{
				$this->assign ( 'shipCondition', '' );
			}
			$orderTempCode = explode ( ',', $rows ['orderTempCode'] );
			$this->assign ( 'orderTempCode', $this->service->TempOrderView ( $orderTempCode, $_GET ['skey'] ) );
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view');
		}  else{

            $rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
		    $rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_service2' );
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
                $this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
				$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
				$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
				$this->display('signin');
			}else {
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
                if($rows['isShipments'] == '��'){
                	$this->assign('isShipYes' , 'checked');
                }else if($rows['isShipments'] == '��'){
                	$this->assign('isShipNo' , 'checked');
                };
    			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
    			$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
    			$this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
    			$this->display('edit');
    		}
        }
   }

   /**
	 * �����鿴ҳ��
	 */
	function c_toViewForAudit() {
		$perm = isset ($_GET['perm'])? $_GET['perm'] :null;
		$rows = $this->service->get_d($_GET['id']);

        $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
        //��ͬ�ı�Ȩ��


    	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_service2' );
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
			$this->assign ( 'shipCondition', '(��������)' );
		}else if($rows['shipCondition'] == '1' ){
			$this->assign ( 'shipCondition', '(֪ͨ����)' );
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
    function c_productEdit() {
		if(!isset($this->service->this_limit['�����޸�'])||$this->service->this_limit['�����޸�'] != 1){
			echo "û�������޸ĵ�Ȩ�ޣ�����ϵOA����Ա��ͨ";
			exit();
		}
   	   $this->permCheck();//��ȫУ��
   	   $perm = isset ($_GET['perm'])? $_GET['perm'] :null;
   	   $rows = $this->service->get_d($_GET['id']);
//   	   foreach ($rows['serviceequ'] as $k => $v){
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
                if($rows['isShipments'] == '��'){
                	$this->assign('isShipYes' , 'checked');
                }else if($rows['isShipments'] == '��'){
                	$this->assign('isShipNo' , 'checked');
                };
    			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			    $this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
    			$this->display('productedit');
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
		$this->assign('originalId', $rows['originalId']);
		$this->assign ( 'originKey', $originalKey);
		$this->display('read-tab');
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
/*********************************************************************************************************/

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
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_service2' );
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
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_service2' );
		//��Ⱦ�ӱ�
		$rows = $this->service->becomeEdit($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		 if($rows['isShipments'] == '��'){
                	$this->assign('isShipYes' , 'checked');
                }else if($rows['isShipments'] == '��'){
                	$this->assign('isShipNo' , 'checked');
                };
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
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$object['cusNameId']);
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
           succ_show('controller/engineering/serviceContract/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
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
		$service->searchArr['workFlow'] = '�����ͬ�쳣�ر�����';
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
		$service->searchArr['workFlow'] = '�����ͬ�쳣�ر�����';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/*
	 * @desription ��ת�������ͬ����ҳ��--���йرհ�ť
	 * @param tags
	 * @author qian
	 * @date 2010-12-26 ����02:28:34
	 */
	function c_toAddContract2 () {
		//����ϵͳ���
		$systemCode = generatorSerial();
		$this->assign('systemCode',$systemCode);

		//���÷�Ʊ���͵������ֵ�
		$this->showDatadicts(array('invoiceType'=>'FPLX'));

		//Ĭ�Ϻ�ͬ�����״̬Ϊ��δ��ˡ�
		$this->assign('ExaStatus','δ���');
		$this->assign('status','��ͬδ����');
		$this->assign('missionStatus','δ�´�');
		$this->display("add2");
	}

	/*
	 * @desription �̻�ת�����ͬ�ı��淽��
	 */
	function c_addChance () {
		$service = $this->service;

		//$trainArr = isset( $_POST['serviceTrain'] )?$_POST['serviceTrain']:null;
		$arr = isset( $_POST[$this->objName] )?$_POST[$this->objName]:null;
//		if($arr['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $arr['sign'] == "��" ){
//			   $arr['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}else if($arr['sign'] == "��"){
//				$arr['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}
//			$id = $service->addContract_d($arr);
//		}else if($arr['orderInput'] == "0"){
//			$id = $service->addContract_d($arr);
//		}else {
//			msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
//		}
		$id = $service->addContract_d($arr);
		if($id){
			if($_GET['act']=='app'){
				succ_show('controller/engineering/serviceContract/ewf_index.php?actTo=ewfSelect&billId=' . $id );
			}else{
				//�����ӳɹ�������ת�������ͬ�༭ҳ��
				msgRF('��ӳɹ���');
			}
		}
	}
	/*
	 * @desription �����ͬ�ı��淽��
	 * @param tags
	 * @date 2010-12-2 ����10:19:27
	 */
	function c_addContract () {
		$service = $this->service;

		//$trainArr = isset( $_POST['serviceTrain'] )?$_POST['serviceTrain']:null;
		$arr = isset( $_POST[$this->objName] )?$_POST[$this->objName]:null;
//	    if($arr['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $arr['sign'] == "��" ){
//				$arr['orderTempCode'] = "LS".$arr['orderTempCode'];
//			   $arr['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}else if($arr['sign'] == "��"){
//				$arr['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}
//			$id = $service->addContract_d($arr);
//		}else if($arr['orderInput'] == "0"){
//			if(!empty($arr['orderTempCode'])){
//				$arr['orderTempCode'] = $arr['orderTempCode'];
//			}
//			$id = $service->addContract_d($arr);
//		}else {
//			msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
//		}
		$id = $service->addContract_d($arr);
		if($id){
			if($_GET['act']=='app'){
				succ_show('controller/engineering/serviceContract/ewf_index2.php?actTo=ewfSelect&billId=' . $id );
			}else{
				//�����ӳɹ�������ת�������ͬ�༭ҳ��
				msgGo('����ɹ���','index1.php?model=engineering_serviceContract_serviceContract&action=toAddContract');
			}
		}
	}


	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
        $orderCodeDao = new model_common_codeRule();
        if($object['sign'] == "��" && $object['orderCode'] == ''){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$object['cusNameId']);
		}

		if ($this->service->edit_d($object, $isEditInfo)) {
			msg('�༭�ɹ���');
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

	function c_pageJsonUnLimit() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$orderDao = new model_projectmanagent_order_order();
//        $export = isset ( $orderDao->this_limit ['������ͬ����'] ) ? $orderDao->this_limit ['������ͬ����'] : null;
		$service->asc = true;
		$rows = $service->pageBySqlId ();
//		foreach($rows as $k => $v){
//             $rows[$k]['exportOrder'] = $export;
//		   }
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/*
	 * @desription �����ͬ�༭ҳ��
	 * @param tags
	 * @date 2010-12-2 ����05:26:30
	 */
	function c_toEditContract () {
		$service = $this->service;
		$contractID = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		$service->searchArr = array( 'id'=>$contractID );
		$arr = $service->pageBySqlId();
		$arr[0]['file']=$this->service->getFilesByObjId($contractID);
		foreach($arr[0] as $key=>$val){
			$this->assign($key,$val);
		}
		$trainDao = new model_engineering_serviceTrain_servicetrain() ;
		$this->assign('list',$trainDao->showTrainEditList($contractID));
		$this->display('edit');
	}

	/*
	 * @desription �鿴�����ͬ�Ļ�����Ϣ
	 * @param tags
	 * @date 2010-12-7 ����10:35:58
	 */
	function c_toViewContractInfo () {
		$trainDao = new model_engineering_serviceTrain_servicetrain();
		$contractId = isset( $_GET['id'] )?$_GET['id']:$_GET['contractId'];
		$conSearch = array( 'id' => $contractId );
		$contractRows = $this->service->find($conSearch);
		//����
		$contractRows['file']=$this->service->getFilesByObjId($contractId,false);
		foreach( $contractRows as $key => $val ){
			$this->assign( $key , $val );
		}
		$invoiceType = $this->getDataNameByCode($contractRows['invoiceType']);
		$this->assign('invoiceType',$invoiceType);

		$this->assign('list',$trainDao->showTrainViewList($contractId));
		$this->display( 'view2' );
	}

	/**
	 * ����ɾ������
	 */
	function c_deletesInfo() {
		$deleteId=isset($_GET['id'])?$_GET['id']:exit;
	        $delete=$this->service->deletesInfo_d ($deleteId);
         if($delete){
         	msg('ɾ���ɹ�');
         }
    }


	/**-----------------------------------------------�����ǡ�δִ�еķ����ͬ������----------------------------------------------------*/

	/*
	 * @desription δִ�з����ͬ�鿴ҳ��
	 * @param tags
	 * @date 2010-12-2 ����08:15:35
	 */
	function c_toViewUnDoContract () {
		$service = $this->service;
		$contractID = isset( $_GET['id'] )?$_GET['id']:null;
		$service->searchArr = array( 'id'=>$contractID );
		$arr = $service->pageBySqlId();
		foreach($arr[0] as $key=>$val){
			$this->assign($key,$val);
		}
		$this->display('viewUndo');
	}


	/*
	 * @desription ��������˵ķ����ͬ
	 * @param tags
	 * @date 2010-12-6 ����11:37:45
	 */
	function c_putContractStart () {
		$service = $this->service;
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		$condiction = array( 'id' => $contractId );
		//�����������޸ı������Ӧ״̬�ֶε�ֵ��
		$updateTag = $service->updateField( $condiction,'status','��ִͬ����' );
		if($updateTag){
			//������ˢ���б�ҳ��--���ڡ�δִ�к�ͬ������������ĺ�ͬ�������ô˷���������û��д�Ϲ̶���תҳ�档
			echo "<script>alert('�����ɹ�');history.back();</script>";
		}
	}

	/**-----------------------------------------------�����ǡ�δִ�еķ����ͬ������----------------------------------------------------*/

	/**-----------------------------------------------�����ǡ�ִ���еķ����ͬ������----------------------------------------------------*/
	/*
	 * @desription ��ִ�еĺ�ͬ�б�
	 * @param tags
	 * @date 2010-12-2 ����02:28:00
	 */
	function c_toDoContractList () {
		$this->show->display($this->objPath . '_' . $this->objName . '-listDo');
	}

	/*
	 * @desription �رշ����ͬ
	 * @param tags
	 * @date 2010-12-6 ����02:23:57
	 */
	function c_putContractClose () {
		$service = $this->service;
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		$condiction = array( 'id' => $contractId );
		//�رղ������޸ı������Ӧ״̬�ֶε�ֵ��
		$updateTag = $service->updateField( $condiction,'status','��ɺ�ͬ' );
		if($updateTag){
			//�����ɹ�����ת��ԭ���б�
			msgGo('�رճɹ�',"?model=engineering_serviceContract_serviceContract&action=toDoContractList");
		}
	}


	/**-----------------------------------------------�����ǡ�ִ���еķ����ͬ������----------------------------------------------------*/

	/**-----------------------------------------------�����ǡ��ѹرյķ����ͬ������----------------------------------------------------*/

	/*
	 * @desription �ѹرպ�ͬ�б�
	 * @param tags
	 * @date 2010-12-2 ����02:28:25
	 */
	function c_toCloseContractList () {
		$this->display('listClose');
	}

	/**-----------------------------------------------�����ǡ��ѹرյķ����ͬ������----------------------------------------------------*/

	/**-----------------------------------------------�����ǡ����˰칫-�ҵ�����-��ͬ����������----------------------------------------------------*/
	/*
	 * @desription ��ת�������˰칫-�ҵ�����-��ͬ����������תҳ��
	 * @param tags
	 * @date 2010-12-6 ����08:31:38
	 */
	function c_toApprovalMain () {
		$this->display('approval-main');
	}

	/*
	 * @desription �ҵ������������󵼺���
	 * @param tags
	 * @date 2010-12-6 ����03:12:03
	 */
	function c_toMyApproval () {
		$this->display('approval-menu');
	}

	/*
	 * @desription �����˰칫-�ҵ�����-��ͬ��������Tabҳ
	 * @param tags
	 * @date 2010-12-6 ����07:05:48
	 */
	function c_toMyApprovalTab () {
		$this->display('approval-index');
	}
	/**����ķ����ͬ����
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditTab () {
		$this->display('change-audit-tab');
	}

	/**-----------------------------------------------�����ǡ����˰칫-�ҵ�����-��ͬ����������----------------------------------------------------*/

	/**##########################################��������������##################################################*/

	/*
	 * @desription ������
	 * @param tags
	 * @date 2010-12-4 ����10:10:54
	 */
	function c_toApprovalNo () {
		$this->display( 'approvalNo');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
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

	/*
	 * @desription ������
	 * @param tags
	 * @date 2010-12-4 ����11:05:03
	 */
	function c_toApprovalYes () {
		$this->display('approvalYes');
	}

	/*
	 * @desription ��ȡ�ֲ�����ת��JSON--������
	 * @param tags
	 * @author qian
	 * @date 2010-12-22 ����11:14:18
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
	/**δ�����ı�������ͬ
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditNo(){
		$this->display('change-auditno');
	}
	/**δ�����ı�������ͬPageJson
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
	/**�������ı�������ͬ
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditYes(){
		$this->display('change-audityes');
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
		$service->groupBy = 'c.updateTime';
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

				$changeLogDao = new model_common_changeLog ( 'servicecontract' );
				$changeLogDao->confirmChange_d ( $contract );
				   if ($contract ['ExaStatus'] == "���") { //������
				            $sql = "update oa_sale_service set isBecome = 0 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
							//�������޽�����ת�������ϣ�����ɾ�����һ��
							$dao = new model_projectmanagent_borrow_toorder();
			                $dao->getRelOrderequ($contract['originalId'],"service","change",$objId);
					}else{
						$orderInfo = $this->service->get_d ( $contract['originalId'] );
						foreach ( $contract['serviceequ'] as $k => $v){
		                     $contractEqu1[$k] = $v['productId'];
		                     $contractNum1[$k] = $v['number'];
						}
						foreach ( $contract1['serviceequ'] as $k => $v){
		                     $contractEqu2[$k] = $v['productId'];
		                     $contractNum2[$k] = $v['number'];
						}
					       //�������޽�����ת��������
					      	  $dao = new model_projectmanagent_borrow_toorder();
			                  $borrowChange = $dao->getRelOrderequ($contract['originalId'],"service","changeE",$objId);
						if (($contractEqu1 != $contractEqu2) || ($contractNum1 != $contractNum2)){
							   $this->service->updateOrderShipStatus_d($contract['originalId']);
						}else if($borrowChange == '1'){
							$sql = "update oa_sale_service set state = 2,DeliveryStatus = 10 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
						}
						 //�������� ������������Զ����ɹ黹��
				          $toorderDao = new model_projectmanagent_borrow_toorder();
				          $toorderDao->findLoan($objId,"service");
					}
			}
		}

		//��ֹ�ظ�ˢ��
//		echo "<script>this.location='?model=engineering_serviceContract_serviceContract&action=allAuditingList'</script>";
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//��ֹ�ظ�ˢ��
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=projectmanagent_order_order&action=allAuditingList'</script>";
		}
		//$this->display ( 'change-approvalNo' );
	}

 /**
     * ȡ���������
     */
    function c_cancelBecome(){
        $orderId = $_GET['id'];
        $sql = "update oa_sale_service set isBecome = 0 where id = $orderId";
        $this->service->query($sql);
        return $orderId;
    }
	/**##########################################��������������##################################################*/


/**-----------------------------------------------������������ķ����ͬ------------------------------------------------------------*/

	/*
	 * @desription ������ķ����ͬ�б�--�󵼺������б�
	 * @param tags
	 * @author qian
	 * @date 2010-12-11 ����04:33:07
	 */
	function c_myAppServiceContract () {
		$this->show->display( $this->objPath . '_' . $this->objName . '-myapp-main' );
	}

	/*
	 * @desription menu�˵���ҳ��
	 * @param tags
	 * @author qian
	 * @date 2010-12-11 ����04:38:30
	 */
	function c_toMyApplication () {
		$this->display( 'myapp-menu' );
	}

	/*
	 * @desription ������ķ����ͬ�б��Tabҳ
	 * @param tags
	 * @author qian
	 * @date 2010-12-11 ����04:39:09
	 */
	function c_toMyApplicationTab () {
		$this->display( 'myapp' );
	}


/**-----------------------------------------------������������ķ����ͬ------------------------------------------------------------*/


/***********************************************��������ͨAction����***********************************************/
/***********************************************Ajax��JSON����***********************************************/
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
/****************************************************************************/
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
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['�����ͬ��Ʊtab'])){
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
		if ($this->service->isKeyMan_d ( $obj ['objId'] ) || ! empty ( $this->service->this_limit ['�����ͬ��Ʊtab'] )) {
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
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['�����ͬ����tab'])){
            $url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
        }
    }

    /**********************��Ʊ�տ�Ȩ�޲���*********************************/

	function c_serviceContactInfoJson(){
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


	/********************************************************/

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
		$orderDao = new model_projectmanagent_order_order();
		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$rows = $service->isGoodsReturn ( $rows);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		foreach($rows as $k => $v){
             $rows[$k]['exportOrder'] = $export;
		   }
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
          $toorderDao->findLoan($objId,"service");
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
 	/**
  * ajax ��ȡ�ӱ�����
  */
	function c_ajaxList() {
		$rows = $this->service->get_d ( $_POST ['id'] );
		$rows = util_jsonUtil::encode ( $rows );

		echo $rows;

	}

	/*********************************���̲��ֺ�ͬ����****************************/


	/**
	 * �����ͬ����  ������Ҫ�ģ�
	 */
	function c_exportServiceExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);


		$stateArr = array ('0' => 'δ�ύ', '1' => '������', '2' => 'ִ����', '3' => '�ѹر�', '4' => '�����' );

		$this->service->searchArr["ExaStatusArr"] = "���,���������";
		$this->service->searchArr["isTemp"] = 0;
		$this->service->searchArr["dealStatus"] = 0;
		$rows = $this->service->listBySqlId ( 'select_default' );
		foreach ( $rows as $index => $row ) {
			foreach ( $row as $key => $val ) {
				if ($key == 'state') {
					$rows [$index] [$key] = $stateArr [$val];
				}
			}
		}
		$colIdArr = array("orderCode","orderTempCode","cusName","orderName");
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip ( $colIdArr );
		foreach ( $rows as $key => $row ) {
			foreach ( $colIdArr as $index => $val ) {
				$colIdArr [$index] = $row [$index];
			}
			array_push ( $dataArr, $colIdArr );
		}
		return model_contract_common_contExcelUtil::exportServiceExcelUtil ( $dataArr );
	}

	/**
	 * ���̲��ź�ͬ�鿴�б�
	 * createBy Show
	 * createOn 2011-11-30
	 */
	function c_orderForEngineering(){
		$this->display('listforesm');
	}

	/**
	 * �����ͬ�б� - ���ڹ��̲��鿴
	 */
	function c_pageJsonForESM(){
		$service = $this->service;
		$rows = array();
		//ʡ��Ȩ������
		$provinceArr = array();

		//���Ȼ�ȡ��Ӧ�İ��´�Ȩ��id
		$otherDataDao = new model_common_otherdatas();
		$limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID']);
		$sysLimit = $limitArr['���´�'];

		if(strstr($sysLimit,';;')){
			$service->getParam ( $_REQUEST );
			$service->sort = 'c.createTime';
			$rows = $service->page_d ('select_process');
		}else{
			//��ȡ���´���Ӧʡ��Ȩ��
			$provincesNames = $this->service->getProvinceNames_d($limitArr);
			//��������ڶ�ӦȨ�ޣ�ֱ��ɸ���б�
			if(!empty($provincesNames)){
				$_REQUEST['orderProvinces'] = $provincesNames;

				$service->getParam ( $_REQUEST );
				$service->sort = 'c.createTime';
				$rows = $service->page_d ('select_process');
			}
		}

		$rows = $this->service->filterWithoutField ( '�����ͬ���', $rows, 'list', array ('orderMoney', 'orderTempMoney' ));

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/*********************************���̲��ֺ�ͬ����****************************/
}
?>
