<?php
/* �ɹ�����ͳһ�ӿ�Model��
 * Created on 2011-3-9
 * Created by can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_purchase_external_external extends model_base {
	public $_emailID = "";
	public $_emailName = "";
	function __construct() {
		$this->tbl_name = "oa_purch_plan_basic";
		$this->sql_map = "";
		parent::__construct ();

		$this->purchTypeArr = array (//��ͬ���Ͳɹ����������,������Ҫ���������׷��
				"oa_sale_order" => "model_purchase_external_contpurchase", //���ۺ�ͬ�ɹ�����
				"oa_sale_service" => "model_purchase_external_servicepurchase", //�����ͬ�ɹ�����
				"oa_sale_lease" => "model_purchase_external_rentalpurchase", //���޺�ͬ�ɹ�����
				"oa_sale_rdproject" => "model_purchase_external_rdprojectpurchase", //�з���ͬ�ɹ�����
				"oa_borrow_borrow" => "model_purchase_external_borrowpurchase", //�����òɹ�����
				"oa_present_present" => "model_purchase_external_presentpurchase", //���Ͳɹ�����
				"stock" => "model_purchase_external_stock", //����ɹ�����
				"rdproject" => "model_purchase_external_rdproject", //�з��ɹ�����
				"assets" => "model_purchase_external_assets", //�̶��ʲ��ɹ�����
				"order" => "model_purchase_external_orderpurchase", //�����ɹ�����
				"produce" => "model_purchase_external_produce",  //�����ɹ�����
				"HTLX-XSHT" => "model_purchase_external_contract",   //��ͬ�ɹ�
				"HTLX-FWHT" => "model_purchase_external_contract",   //��ͬ�ɹ�
				"HTLX-ZLHT" => "model_purchase_external_contract",   //��ͬ�ɹ�
				"HTLX-YFHT" => "model_purchase_external_contract"  //��ͬ�ɹ�
				);


		//----------
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (0 => array ('statusEName' => 'execute', 'statusCName' => 'ִ����', 'key' => '0' ), 1 => array ('statusEName' => 'Locking', 'statusCName' => '����', 'key' => '1' ), 2 => array ('statusEName' => 'end', 'statusCName' => '���', 'key' => '2' ), 3 => array ('statusEName' => 'close', 'statusCName' => '�ر�', 'key' => '3' ), 4 => array ('statusEName' => 'change', 'statusCName' => '�����', 'key' => '4' ) );
		//���ó�ʼ�����������
		parent::setObjAss ();

		// �´�ɹ���������ռ�����Ϣ
		$this->_emailID = "hao.yuan";
		$this->_emailName = "Ԭ��";
	}

	/*===================================���ýӿڿ�ʼ======================================*/
	/**
	 *�´�ɹ����룬�嵥��ʾģ��
	 *
	 * @param $rows:�����嵥��Ϣ
	 * @param $interface���ɹ�����ӿ�
	 */
	function showAddList_d($rows, $mianRows, $type) {
		//TODO;
		$purchTypeDao = $this->newTypeDao_d ( $type );
		$productDao = new model_stock_productinfo_productinfo ();
		$newRows = array ();
		if($type=="stock"){
			$newRows=$rows;
		}else{
			//ȥ�����������еġ���Ʒ���롰���Ʒ��
			foreach ( $rows as $key => $val ) {
				$productRow = $productDao->get_d ( $val ['productId'] );
				if ($productRow ['statType'] != 'TJCP' && $productRow ['statType'] != 'TJBCP') {
					array_push ( $newRows, $val );
				}
			}
		}
		$list = $purchTypeDao->showAddList ( $rows, $mianRows );
		return $list;
	}
	/**
	 *���ݲ�ͬ������ʵ��������
	 *
	 * @param $type		�ɹ�����
	 */
	function newTypeDao_d($type) {
		$purchTypeModel = $this->purchTypeArr [$type];
		return new $purchTypeModel ();
	}

	/**
	 * ���ݲ�ͬ���͵ĵ��ݵ�ID����ȡ������������嵥��Ϣ
	 *
	 * @param $parentId   ���ϱ��������������ID
	 * @return $interface
	 */
	function getItemsByParentId_d($type, $parentId) {
		$purchTypeDao = $this->newTypeDao_d ( $type );
		$itemRows = $purchTypeDao->getItemsByParentId ( $parentId );
		return $itemRows;
	}

	/**
	 * ���ݲɹ����͵ĵ���ID����ȡ����Ϣ
	 *
	 * @param $id  �ɹ����͵ĵ���ID
	 * @return $interface
	 */
	function getInfoList_d($id, $type) {
		$purchTypeDao = $this->newTypeDao_d ( $type );
		$mainRows = $purchTypeDao->getInfoList ( $id );
		return $mainRows;
	}

	/**
	 * ���ݲ�ͬ�����Ͳɹ����룬����ҵ����
	 *
	 * @param $interface
	 * @param $paramArr      ����ҵ����ʱ����Ҫ�Ĳ�������
	 */
	function toDealByPurchType_d($type, $paramArr) {
		$purchTypeDao = $this->newTypeDao_d ( $type );
		return $purchTypeDao->dealInfoAtPlan ( $paramArr );
	}

	/**
	 *�´�ɹ������ҳ�����ת������ҵ��Ĳ�ͬ��ת����ͬ��ҳ��
	 *
	 * @param $purchType   �ɹ���������
	 * @param $type
	 */
	function toShowPage($id, $purchType, $type) {
		$purchTypeDao = $this->newTypeDao_d ( $purchType );
		$purchTypeDao->toShowPage ( $id, $type );
	}

	/*===================================���ýӿڽ���======================================*/

	/*===================================ҵ����ʼ======================================*/
	/**
	 * ��Ӳɹ�����
	 *
	 * @param $object
	 * @return return_type
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			//���������ϢΪ��ͬ�����׳��쳣���´�ɹ�����ʧ��
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '������Ϣ���������´�ʧ�ܣ�' );
			}

			if ($object ['purchType'] == "assets") { //����ɹ���������Ϊ�̶��ʲ��ɹ�,��ΪĿǰ��û�й̶��ʲ�����ѡ��ֻ�̶ܹ�д�������й̶��ʲ������󣬴˲��ֿ���ȥ��
				$object ['objAssName'] = "�̶��ʲ��ɹ�";
				$object ['objAssType'] = "assets";
				$object ['objAssCode'] = "";
				$object ['equObjAssType'] = "assets_equ";
			}
			if ($object ['purchType'] == "assets" || $object ['purchType'] == "rdproject" || $object ['purchType'] == "produce") {
				$object ['ExaStatus'] = "δ�ύ";
			} else {
				$object ['ExaStatus'] = "���";
			}

			//add by chengl 2012-04-06 ����ȷ��״̬,�̶��ʲ����з��ɹ�Ϊ0
			if ($object ['purchType'] == "assets"||$object ['purchType']=="rdproject") {
				$object['productSureStatus']=1;
			}

			$applyEquArr = array();
			$applyEquIds = '';
			//add by chengl 2012-04-20 �Ƿ���Ҫ����ȷ�ϱ�ʶ��������϶��Ǵ����Ͽ���ѡ������������ȷ��
			foreach ( $object ['equipment'] as $key => $equ ) {
				if(empty($equ['productId'])){
					$object['productSureStatus']=0;
				}
				$applyEquIds .= $equ['applyEquId'].",";
				$applyEquArr[$equ['applyEquId']] = $equ['amountAll'];
			}

			// add by huanghaojin 2017-08-04 PMS 2775 �ٴμ�鲹��ɹ����ϵ��´�����,����ͬʱ���˴��´�ҳ���,���µ������´�����
			if($object ['purchType'] == "stock"){
				$applyEquIds = rtrim($applyEquIds,",");
				if($applyEquIds != '' && !empty($applyEquArr)){
					$allEqus = $this->_db->getArray("select id,sequence,amountAllOld,issuedPurNum from oa_stock_fillup_detail where id in ({$applyEquIds});");
					$errorStr = "";
					foreach ($allEqus as $key => $equ){
						if(bcadd($applyEquArr[$equ['id']],$equ['issuedPurNum']) > $equ['amountAllOld']){// ��������Ϊ����������+���´����� >= ԭ������������
							$errorStr .= "����{$equ['sequence']}���´�ɹ�����Ϊ{$equ['issuedPurNum']}, ";
						}
					}
					if($errorStr != ""){
						$errorStr = "�´�ʧ��! ".$errorStr."��ȷ�Ϻ����ԡ�";
						throw new Exception ( $errorStr );
						exit();
					}
				}
			}

			if(isset($object['instruction'])&&trim($object['instruction'])=='����д�ɹ�ʱ��Ҫ�ر�ע�������'){
				$object['instruction']='';
			}

			$object ['state'] = $this->statusDao->statusEtoK ( 'execute' );
			$object ['objCode'] = $this->objass->codeC ( "purch_plan" );
			$codeDao = new model_common_codeRule ();
			$object ['planNumb'] = $codeDao->purchApplyCode ( $this->tbl_name, $object ['purchType'] );
			$object ['isTemp'] = 0;
			$object ['isChange'] = 0;

			//��ȡ������˾����
			$object['formBelong']         = $_SESSION['USER_COM'];
			$object['formBelongName']     = $_SESSION['USER_COM_NAME'];
			$object['businessBelong']     = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$planId = parent::add_d ( $object, true );

			$equDao = new model_purchase_plan_equipment ();
			$addAssObjs = array ();
			$interfObj = new model_common_interface_obj (); //����ӿ���


			if ("rdprojectNew" == $object ['purchType']) { //�з��ɹ�(���ڹ̶��ʲ�ϵͳû�ϣ��з��ɹ���ʱ�û���ǰ��)
				$itemsObj = $this->rdEdit_d ( $object ['equipment'], $planId ,$object ['planNumb']);
				foreach ( $itemsObj as $key => $itemObj ) {
					$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $itemObj ['equObjAssId'], //������ͬ�豸id
'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //�����豸��������
'planEquId' => $itemObj ['id'] );
				}

			} else {
				$datadictDao=new model_system_datadict_datadict();

				foreach ( $object ['equipment'] as $key => $equ ) {
					$isDelTag = isset($equ ['isDelTag']) ? $equ ['isDelTag'] : NULL;
					if(empty($isDelTag) && $isDelTag != 1){
						//������������0���Ҳɹ������������Ʋ�Ϊ�ղŽ��в���
						$productId=$equ ['productId'];
						$productName=$equ ['productName'];
						$inputProductName=$equ ['inputProductName'];
						if ($equ ['amountAll'] > 0 && (!empty($productId)||!empty($productName) ||!empty($inputProductName))) {
							if(empty($productId)&&!empty($productName)){//�з��ɹ��ֹ��������������
								$equ ['inputProductName']=$productName;
							}
							$i = isset ( $i ) ? (++ $i) : 1; //�ж��ж��������ò�Ʒ�嵥
							$equ ['basicId'] = $planId;
							$equ ['basicNumb'] = $object ['planNumb'];
							$equ ['purchType'] = $object ['purchType'];
							$equ ['objCode'] = $this->objass->codeC ( 'purch_plan_equ' );
							$equ ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
							$equ ['amountIssued'] = 0; //ȷ�����´�����һ��ʼΪ0
							$equ ['amountAllOld'] = $equ ['amountAll'];
							$equ ['isTask'] = 0;
							if ($equ ['dateHope'] == '') {
								unset ( $equ ['dateHope'] );
							}
							if ($object ['purchType'] == 'produce') {
								$equ ['batchNumb']=$object ['batchNumb'];
							}

							//add by chengl 2012-04-06 ��Ӳɹ����������ж�
							$equ['productCategoryName']=$datadictDao->getDataNameByCode($equ['productCategoryCode']);
							$equ['qualityName']=$datadictDao->getDataNameByCode($equ['qualityCode']);


							//ͳһ����,���и��ɹ��������͵�ҵ����
							$purchTypeDao = $this->purchTypeArr [$object ['purchType']];
							if ($purchTypeDao) {
								$paramArr = array ('uniqueCode' => $equ ['uniqueCode'], 'issuedPurNum' => $equ ['amountAll'], 'equObjAssId' => $equ ['equObjAssId'] );
							}
							$this->toDealByPurchType_d ( $object ['purchType'], $paramArr );

							if(substr($equ['purchType'],0,5)=='HTLX-'||$equ['purchType']=='oa_borrow_borrow'){
//								$equ ['amountAll'] = 0;
								$equ ['amountAll'] = $equ ['amountAllOld'];
							}
							$equId = $equDao->add_d ( $equ );
							//���òɹ��ܹ���������
							$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $equ ['equObjAssId'], //������ͬ�豸id
	'planCode' => $object ['planNumb'], 'planId' => $planId, 'planEquType' => $object ['equObjAssType'], //�����豸��������
	'planEquId' => $equId );
						}
					}
				}
				//�����ڲ�Ʒʱ���׳��쳣
				if ($i == 0) {
					throw new Exception ( '�ɹ������޿����豸' );
				}
			}


			//���¸���������ϵ
			$this->updateObjWithFile($planId,$object['planNumb']);
			//��������
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $planId, $object ['planNumb'] );
			}

			//����ɹ��ܹ�����
			$this->objass->addModelObjs ( "purch", $addAssObjs );
			$this->commit_d ();
			return $planId;

		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * add by chengl 2012-04-21
	 * �༭����ȷ�ϴ�صĲɹ�����
	 */
	function editBack_d($object){
		try {
			$this->start_d ();

			//���������ϢΪ��ͬ�����׳��쳣���´�ɹ�����ʧ��
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '������Ϣ���������´�ʧ�ܣ�' );
			}
			$planId = parent::edit_d ( $object, true );
			$addAssObjs = array ();


			$equDao = new model_purchase_plan_equipment ();

			$interfObj = new model_common_interface_obj (); //����ӿ���
			$datadictDao=new model_system_datadict_datadict();
			foreach ( $object ['equipment'] as $key => $equ ) {
				//������������0���Ҳɹ������������Ʋ�Ϊ�ղŽ��в���
				$productId=$equ ['productId'];
				$productName=$equ ['productName'];
				$inputProductName=$equ ['inputProductName'];
				if ($equ ['amountAll'] > 0 && (!empty($productId)||!empty($productName) ||!empty($inputProductName))) {
					if(empty($productId)&&!empty($productName)){//�з��ɹ��ֹ��������������
						$equ ['inputProductName']=$productName;
					}
					$equ['isProduce']=$equ['isProduce']=="on"?1:0;
					$i = isset ( $i ) ? (++ $i) : 1; //�ж��ж��������ò�Ʒ�嵥
					$equ ['basicId'] = $object ['id'];
					$equ ['basicNumb'] = $object ['planNumb'];
					$equ ['purchType'] = $object ['purchType'];
					$equ ['objCode'] = $this->objass->codeC ( 'purch_plan_equ' );
					$equ ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
					$equ ['amountIssued'] = 0; //ȷ�����´�����һ��ʼΪ0
					if ($equ ['dateHope'] == '') {
						unset ( $equ ['dateHope'] );
					}
					if ($object ['purchType'] == 'produce') {
						$equ ['batchNumb']=$object ['batchNumb'];
					}
					//add by chengl 2012-04-06 ��Ӳɹ����������ж�
					$equ['productCategoryName']=$datadictDao->getDataNameByCode($equ['productCategoryCode']);
					$equ['qualityName']=$datadictDao->getDataNameByCode($equ['qualityCode']);
					$equId=$equ['id'];
					if(empty($equId)){
						$equId = $equDao->add_d ( $equ );
						//���òɹ��ܹ���������
						$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $equ ['equObjAssId'], //������ͬ�豸id
						'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //�����豸��������
						'planEquId' => $equId );
					}else{
						$equDao->edit_d ( $equ );
					}

				}
				//�����ڲ�Ʒʱ���׳��쳣
				if ($i == 0) {
					throw new Exception ( '�ɹ������޿����豸' );
				}
			}

			//����ɹ��ܹ�����
			$this->objass->addModelObjs ( "purch", $addAssObjs );
			$this->commit_d ();
			return true;

		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * �༭�ɹ�����
	 *
	 * @param $object  �༭����
	 * @return return_type
	 */
	function edit_d($object) {
		try {
			$this->start_d ();

			//���������ϢΪ��ͬ�����׳��쳣���´�ɹ�����ʧ��
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '������Ϣ���������´�ʧ�ܣ�' );
			}
			$planId = parent::edit_d ( $object, true );
			$sql = "delete from oa_purch_objass where planId='" . $object ['id'] . "'";
			$this->query ( $sql );
			$addAssObjs = array ();

			if ("rdprojectNew" == $object ['purchType']) { //�з��ɹ�
				$itemsObj = $this->rdEdit_d ( $object ['equipment'], $object ['id'],$object ['planNumb'] );
				foreach ( $itemsObj as $key => $itemObj ) {
					$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $itemObj ['equObjAssId'], //������ͬ�豸id
'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //�����豸��������
'planEquId' => $itemObj ['id'] );
				}
			} else {
				$equDao = new model_purchase_plan_equipment ();
				//��ɾ���ӱ�����
				$condiction = array ('basicId' => $object ['id'] );
				$equDao->delete ( $condiction );

				$interfObj = new model_common_interface_obj (); //����ӿ���
				$datadictDao=new model_system_datadict_datadict();
				foreach ( $object ['equipment'] as $key => $equ ) {
					unset($equ['id']);
					//������������0���Ҳɹ������������Ʋ�Ϊ�ղŽ��в���
					$productId=$equ ['productId'];
					$productName=$equ ['productName'];
					$inputProductName=$equ ['inputProductName'];
					if ($equ ['amountAll'] > 0 && (!empty($productId)||!empty($productName) ||!empty($inputProductName))) {
						if(empty($productId)&&!empty($productName)){//�з��ɹ��ֹ��������������
							$equ ['inputProductName']=$productName;
						}
						$equ['isProduce']=$equ['isProduce']=="on"?1:0;
						$i = isset ( $i ) ? (++ $i) : 1; //�ж��ж��������ò�Ʒ�嵥
						$equ ['basicId'] = $object ['id'];
						$equ ['basicNumb'] = $object ['planNumb'];
						$equ ['purchType'] = $object ['purchType'];
						$equ ['objCode'] = $this->objass->codeC ( 'purch_plan_equ' );
						$equ ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
						$equ ['amountIssued'] = 0; //ȷ�����´�����һ��ʼΪ0
						$equ ['amountAllOld'] = $equ ['amountAll'];
						if ($equ ['dateHope'] == '') {
							unset ( $equ ['dateHope'] );
						}
						if ($object ['purchType'] == 'produce') {
							$equ ['batchNumb']=$object ['batchNumb'];
						}
						//add by chengl 2012-04-06 ��Ӳɹ����������ж�
						$equ['productCategoryName']=$datadictDao->getDataNameByCode($equ['productCategoryCode']);
						$equ['qualityName']=$datadictDao->getDataNameByCode($equ['qualityCode']);

						$equId = $equDao->add_d ( $equ );
						//���òɹ��ܹ���������
						$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $equ ['equObjAssId'], //������ͬ�豸id
						'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //�����豸��������
						'planEquId' => $equId );
					}
				}
				//�����ڲ�Ʒʱ���׳��쳣
				if ($i == 0) {
					throw new Exception ( '�ɹ������޿����豸' );
				}
			}

			//����ɹ��ܹ�����
			$this->objass->addModelObjs ( "purch", $addAssObjs );
			$this->commit_d ();
			return true;

		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 *
	 * �з�ȷ�ϱ���
	 */
	function confirm_d($object) {
		try {
			$this->start_d ();
			//���������ϢΪ��ͬ�����׳��쳣���´�ɹ�����ʧ��
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '������Ϣ���������´�ʧ�ܣ�' );
			}
			$planId = parent::edit_d ( $object, true );
			$sql = "delete from oa_purch_objass where planId='" . $object ['id'] . "'";
			$this->query ( $sql );
			$addAssObjs = array ();

			$itemsObj = $this->rdEdit_d ( $object ['equipment'], $object ['id'] );

			$object = $this->get_d ( $object ['id'] );
			$assetApplyObj = array ("applyDetId" => $object ['departId'], //
			"applyDetName" => $object ['department'], //
			"purchCategory"=>"CGZL-YFL",
			"applicantId" => $object ['sendUserId'], //
			"applicantName" => $object ['sendName'], "applyTime" => $object ['sendTime'], //
			//"planCode" => $object ['departId'], "planYear" => $object ['departId'], //
			"useDetId" => $object ['departId'], "useDetName" => $object ['department'], //
			//"userId" => $object ['sendUserId'], "userName" => $object ['sendName'], //
			"userTel" => $object ['phone'], "createName" => $object ['createName'], //
			"createId" => $object ['createId'], "createTime" => $object ['createTime'], //
			"updateName" => $object ['updateName'], "updateId" => $object ['updateId'], //
			"projectId" => $object ['projectId'], "projectName" => $object ['projectName'], "projectCode" => $object ['projectCode'], //
			"state"=>"δ�ύ",
			"updateTime" => $object ['updateTime'], "ExaStatus" => $object ['ExaStatus'], "ExaDT" => $object ['ExaDT'], "assetUse" => $object ['assetUse'], "remark" => $object ['instruction'], "applyItem" => "" );

						foreach ( $itemsObj as $key => $itemObj ) {
							$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $itemObj ['equObjAssId'], //������ͬ�豸id
			'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //�����豸��������
			'planEquId' => $itemObj ['id'] );

							if ($itemObj ['isAsset'] == "on") {
								$assetApplyItem = array ("productCode" => $itemObj ['productNumb'], "productName" => $itemObj ['productName'], "pattem" => $itemObj ['pattem'], //
			"unitName" => $itemObj ['unitName'], "supplierName" => $itemObj ['surpplierName'], //
			"supplierId" => $itemObj ['surpplierId'], "isAsset" => $itemObj ['isAsset'], //
			"planPrice" => $itemObj ['planPrice'], "equUseYear" => $itemObj ['equUseYear'], //
			"dateHope" => $itemObj ['dateHope'], "remark" => $itemObj ['remark'], "applyAmount" => $itemObj ['amountAll'] );
					$assetApplyObj ['applyItem'] [] = $assetApplyItem;

		//					array_push ( $assetApplyObj ['applyItem'], $assetApplyItem );
				}
			}
			//����ɹ��ܹ�����
			$this->objass->addModelObjs ( "purch", $addAssObjs );

			//copy���ݵ��̶��ʲ��ɹ�
			$assetApplyDao = new model_asset_purchase_apply_apply ();
			if (is_array ( $assetApplyObj ['applyItem'] )) {
				$assetApplyDao->add_d ( $assetApplyObj );
			}

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	/**
	 *
	 *�з��ɹ��༭����
	 */
	function rdEdit_d($itemArr, $id,$basicNumb) {
		$equDao = new model_purchase_plan_equipment ();
		$resultArr = array ();
		foreach ( $itemArr as $key => $value ) {
			$value ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
			$value ['basicId'] = $id;
			$value ['basicNumb'] = $basicNumb;
			$value ['purchType'] = "rdproject";
			$value ['amountIssued'] = 0; //ȷ�����´�����һ��ʼΪ0
			//add by chengl 2012-04-06 ��Ӳɹ����������ж�
			$value['productCategoryName']=$datadictDao->getDataNameByCode($value['productCategoryCode']);
			array_push ( $resultArr, $value );
		}
//		$itemSaveArr = util_arrayUtil::setItemMainId ( "basicId", $id, $itemArr );
		return $equDao->saveDelBatch ( $resultArr );
	}

    /**
	 *  ��ȡĬ���ʼ�������
	 */
	function getSendMen_d(){
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : array('TO_ID'=>'',
				'TO_NAME'=>'');
		return $mailArr;
	}

	/**
	 * �´�ɹ����룬�����ʼ�
	 *@param $id �ɹ�����id
	 *
	 */
	 function sendEmail_d($id,$emailArr){
		try{
			$this->start_d();
			//��ȡ�ɹ����뵥��Ϣ��������Ϣ
			$basicDao = new model_purchase_plan_basic();
			$rows=$basicDao->getPlan_d($id);
			if($emailArr['TO_ID']!=""){
				$addmsg="";

//				$addMsgBeforeContent = ($emailArr['typeName'] == '')? '' : '<table width="98%" border="0" cellpadding="0" cellspacing="0" align="center"><tbody><tr>'.
//				'<td align="center">'.$emailArr['typeName'].'</td></tr></tbody></table>';
				$addMsgBeforeContent = $emailArr['typeName'];
				if(is_array($rows['childArr'])){
					$testTypeArr = array(
						'0'=>'ȫ��',
						'1'=>'���',
						'2'=>'���',
					);
					foreach( $rows['childArr'] as $key => $val ){
						if( $val['testType']!='' ){
							$testType = $val['testType'];
							$rows['childArr'][$key]['testType']=$testTypeArr[$testType];
						}
					}
					$j=0;
					//��������ϸ��Ϣ
					$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>���ϱ���</b></td><td><b>���鷽ʽ</b></td><td><b>����</b></td><td><b>�´�����</b></td><td><b>�����������</b></td><td><b>��ע</b></td></tr>";
					foreach($rows['childArr'] as $key => $equ ){
						$j++;
						$productName=$equ['productName'];
						$productCode=$equ ['productNumb'];
						$testType=$equ ['testType'];
						$amountAll=$equ ['amountAll'];
						$dateIssued=$equ ['dateIssued'];
						$dateHope=$equ['dateHope'];
						$remark=$equ['remark'];
						$addmsg .=<<<EOT
						<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$productCode</td>
									<td>$testType</td>
									<td>$amountAll</td>
									<td>$dateIssued</td>
									<td>$dateHope</td>
									<td>$remark</td>
								</tr>
EOT;
						}
						$addmsg.="</table>";
				}
				$operator = (isset($emailArr['operator']))? $emailArr['operator'] : $_SESSION['USERNAME'];
				$emailDao = new model_common_mail();
				$emailInfo = $emailDao->purchasePlanMail('y',$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$operator.'�´��µĲɹ�����,���Ϊ��<font color=red><b>'.$rows['planNumb'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1,$addMsgBeforeContent);

			}
			$this->commit_d();
			return true;
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}

	 }
/*===================================ҵ�������======================================*/
}
?>
