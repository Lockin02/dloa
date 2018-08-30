<?php
/**
 *
 * ͳһԴ������
 * @author chengl
 *
 */
class controller_stock_withdraw_withcommon extends controller_base_action {

	function __construct() {
		$this->objName = "withcommon";
		$this->objPath = "stock_withdraw";
		parent::__construct ();
	}

	/**
	 * ��������ҵ����
	 */
	function c_createObjCode(){
		$objType=$_GET['objType'];
		try{
			$this->service->batchCreateObjCode($objType);
			echo 1;
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	/**
	 * ��ת������ҵ�����ҳ��
	 */
	function c_toUpdateObjCode(){
		$this->view("update");
	}

    /**
     * ���¾�ϵͳ��ͬ��������ϵͳ��תҳ��
     */
     function c_updateOldToNewContract(){
        $this->view("updateOldcontract");
     }
     /**
      * ���¾����ݷ���
      */
      function c_updateOldcontract(){
      	   set_time_limit(0);
      	   $objType=$_GET['objType'];
      	   if($objType=="lock"){//��������
				$msg = $this->service->updateLock_d();
      	   }else if($objType=="change"){//�������
				$msg = $this->service->updateChange_d($_REQUEST['contractType']);
      	   }else if($objType=="signin"){//ǩ�ո���
				$msg = $this->service->updateSignin_d();
      	   }else if($objType=="changerelation"){//�������
				$msg = $this->service->updateChangeRelate();
      	   }else{
      	   		//��ͬ����
	      	   //$msg = $this->service->updateOldcontract_d($objType);
      	   }
          echo $msg;
      }
	/**
	 * ��ͬ�����б�ӱ����ݻ�ȡ
	 */
	function c_equJson() {
		$service = $this->service;
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo ();
		$lockDao = new model_stock_lock_lock ();
		$contType = $_POST ['docType'];
		$daoName = $service->getSourceEquDao($contType);
		$service = new $daoName ();
		$service->asc = false;
		$service->getParam ( $_POST );
		$service->searchArr ['isDel'] = 0;
		$service->searchArr ['isTemp'] = 0;
		$rows = $service->list_d ();
//		echo "<pre>";
//		print_R($service->searchArr);
		if( $contType== 'oa_borrow_borrow' ){
			$stockType='outStockCode';
		}else{
			$stockType='salesStockCode';
		}
		foreach ( $rows as $key => $val ) {
			$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,$_POST ['docType'] );
			if( $rows [$key] ['productId'] ){
				$rows [$key] ['exeNum'] = $inventoryDao->getExeNumsByStockType ( $rows [$key] ['productId'], $stockType );
			}else{
				$rows [$key] ['exeNum']=0;
			}
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת�����÷�����������ҳ��
	 */
	function c_toSetType(){
		$typeDao = new model_projectmanagent_shipment_shipmenttype();
		$fields = "id,type";
		$daoName = $this->service->getSourceBaseDao($_GET['docType']);
		$docDao = new $daoName();
		$docInfo = $docDao->get_d($_GET['id']);
		$typeInfo = $typeDao->findAll(null,null,$fields);
		foreach( $typeInfo as $key=>$val ){
			$typeInfo[$key]['code']=$val['id'];
			$typeInfo[$key]['name']=$val['type'];
		}
		$this->showSelectOption('shipmentType',null,true,$typeInfo);
		$this->assign('customTypeId',$docInfo['customTypeId']);
		$this->assign('customTypeName',$docInfo['customTypeName']);
		$this->assign('warnDate',$docInfo['warnDate']);
		$this->assign('id',$_GET['id']);
		$this->assign('docType',$_GET['docType']);
		$this->view("settype");
	}

	/**
	 * setType
	 */
	 function c_setType(){
	 	$rows = $_POST[$this->objName];
	 	$flag = $this->service->setType_d( $rows['id'],$rows['docType'],$rows );
	 	if($flag){
	 		msg('���óɹ���');
	 	}
	 }

	 function c_clearCusTypeByIds(){
	 	$this->service->clearCusTypeByIds(2);
	 }


	/**
	 * �رշ�������
	 */
	function c_closeCont() {
		switch ($_POST ['type']) {
			case 'oa_contract_contract' :
				$modelName = "contract_contract_contract";
				break;
			case 'oa_borrow_borrow' :
				$modelName = "projectmanagent_borrow_borrow";
				break;
			case 'oa_present_present' :
				$modelName = "projectmanagent_present_present";
				break;
			case 'oa_contract_exchangeapply' :
				$modelName = "model_projectmanagent_exchange_exchange";
				break;
		}
//		$this->permCheck ( $_POST ['id'], $modelName );
		$contType = $_POST ['type'];
		$daoName = $this->service->getSourceBaseDao($contType);
		$service = new $daoName ();
		echo $service->updateDeliveryStatus ( $_POST ['id'] );
	}

	/**
	 * �ָ���������״̬
	 */
	function c_recoverCont() {
		switch ($_POST ['type']) {
			case 'oa_contract_contract' :
				$modelName = "contract_contract_contract";
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
		$daoName = $this->service->getSourceBaseDao($contType);
		$service = new $daoName ();
		echo $service->updateOutStatus_d ( $_POST ['id'] );
	}


	  function c_getBorrwoToOrderequ(){
	  	$docId = $_POST['docId'];
	  	$docType = $_POST['docType'];
	  	$rowNum = $_POST['rowNum'];
        $daoStr = $this->service->getSourceEquDao($docType);
        $dao = new $daoStr();
        $rows = $dao->findAll(array("contractId" => $docId,"isBorrowToorder" => '1'));
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
							<td>$val[productCode]
							<input type="hidden" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productCode]" class='readOnlyTxtItem' readonly/>
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
	 * ��ȡ��Ʒ�µ�������Ϣ(��������ȷ�Ͽ�ʼ����)
	 */
	function c_getProductEqu() {
		$id = $_POST['conProductId'];
		$sourceType = $_GET['sourceType'];//Դ�����ͣ���ͬ�������ã����ͣ�����
		$sourceType=empty($sourceType)?"contract":$sourceType;
		$service = $this->service;
		$equArr = $service->getProductEqu_d($id,$sourceType);
//		echo "<pre>";
//		print_R($equArr);
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		if (is_array($equArr) && count($equArr) > 0) {
			foreach ($equArr as $key => $val) {
				$equArr[$key]['warrantyPeriod'] = $val['warranty'];
//				if ( isset($val['number']) || $val['number']=='') {
//					$equArr[$key]['number'] = 1;
//				}
				$equArr[$key]['money'] = $val['number'] * $val['price'];
				if( $sourceType=='borrow' ){
					$exeNum = $inventoryDao->getExeNumsByStockType($equArr[$key]['productId'], 'outStockCode');
				}else{
					$exeNum = $inventoryDao->getExeNumsByStockType($equArr[$key]['productId'], 'salesStockCode');
				}
				if( empty($exeNum)||!isset($exeNum) ){
					$equArr[$key]['exeNum'] = '0';
				}else{
					$equArr[$key]['exeNum'] = $exeNum;
				}
			}
		}
		$equArr = $this->sconfig->md5Rows($equArr);
		echo util_jsonUtil :: encode($equArr);
	}


/***********************************************��ͬ����***********begin***************************************************/
	/**
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'contractTypeName', //��ͬ����
			1 => 'contractNatureName', //��ͬ����
			2 => 'winRate', //��ͬӯ��
			3 => 'signSubjectName', //ǩԼ��˾
			4 => 'contractName', //��ͬ����
			5 => 'contractCode', //��ͬ���
			6 => 'prinvipalName', //��ͬ������
			7 => 'contractMoney', //��ͬ���
			8 => 'customerName', //�ͻ�����
			9 => 'beginDate', //��ͬ��ʼ����
			10 => 'endDate', //��ͬ��������
			11 => 'contractSigner', //��ͬǩ����
			12 => 'state',//��ͬ״̬
			13 => 'remark' //��ע
		);
		$this->c_addExecel($objNameArr, $ExaDT);
	}

	/**
	 * �ϴ�EXCEl������������
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$winRateArr = array("0.5"=>"50%","0.8"=>"80%","1"=>"100%");//��ͬӮ��
				$conState = array("ִ����"=>"2","�ѹر�"=>"3","�����"=>"4");//��ͬ״̬
				$arrinfo = array();//��������
                //ѭ�������������
                foreach($objectArr as $key => $val){
                    //�жϺ�ͬ���ͺͺ�ͬ����
                    $datadictDao = new model_system_datadict_datadict();
                    $HTLX = $datadictDao->getCodeByName("HTLX", $val['contractTypeName']);
                    $HTSX = $datadictDao->getCodeByName($HTLX, $val['contractNatureName']);
                   if(empty($HTLX) || empty($HTSX)){
                   	  array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ�ܣ���ͬ���ͻ��ͬ���Դ���" ) );
                   }else{
                   	   $objectArr[$key]['contractType'] = $HTLX;
                   	   $objectArr[$key]['contractNature'] = $HTSX;
                   	   //�жϺ�ͬӮ��
                   	   $winRate = $val['winRate'];
                       if(empty($winRateArr[$winRate])){
                          array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,��ͬӮ�ʴ���50%,80%,100%��" ) );
                       }else{
                       	  $objectArr[$key]['winRate'] = $winRateArr[$winRate];
                       	  //�ж�ǩԼ��˾��ǩԼ���壩
                       	  $QYZT = $datadictDao->getCodeByName("QYZT", $val['signSubjectName']);
                       	  if(empty($QYZT)){
                             array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,ǩԼ��˾����" ) );
                       	  }else{
                       	  	 $objectArr[$key]['signSubject'] = $QYZT;
                       	  	 //�жϺ�ͬ���Ƿ����
                       	  	 $isCode = $this->service->getContractbyCode($val['contractCode']);
                             if(!empty($isCode)){
                               array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,��ͬ��Ŵ���" ) );
                             }else{
                                //��ͬ�����˺ͺ�ͬǩ����
                                $prinvipalNameId = $this->service->user($val['prinvipalName']);
                                $contractSignerId = $this->service->user($val['contractSigner']);
                                if(empty($prinvipalNameId) || empty($contractSignerId)){
                                  array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,��ͬ�����˻��ͬǩ���˲�����" ) );
                                }else{
                                	$objectArr[$key]['prinvipalId'] = $prinvipalNameId;
                                	$objectArr[$key]['contractSignerId'] = $contractSignerId;
                                    //�жϿͻ�
                                    $cusIdArr = $this->service->isCus($val['customerName']);
                                    $cusId = $cusIdArr[0]['id'];
                                    if(empty($cusId)){
                                       array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,�ͻ�������" ) );
                                    }else{
                                       //����ͻ������Ϣ
                                      $cusInfo = $this->service->isCusInfo($cusId);
                                      $objectArr[$key]['customerId'] = $cusId;
                                      $objectArr[$key]['customerType'] = $cusInfo['customerType'];
                                      $objectArr[$key]['customerTypeName'] = $datadictDao->getDataNameByCode($cusInfo['customerType']);
                                      $objectArr[$key]['address'] = $cusInfo['address'];
                                      $objectArr[$key]['contractCountry'] = $cusInfo['contractCountry'];
                                      $objectArr[$key]['contractCountryId'] = $cusInfo['contractCountryId'];
                                      $objectArr[$key]['contractProvince'] = $cusInfo['contractProvince'];
                                      $objectArr[$key]['contractProvinceId'] = $cusInfo['contractProvinceId'];
                                      $objectArr[$key]['contractCity'] = $cusInfo['contractCity'];
                                      $objectArr[$key]['contractCityId'] = $cusInfo['contractCityId'];
                                      $objectArr[$key]['areaPrincipal'] = $cusInfo['areaPrincipal'];
                                      $objectArr[$key]['areaPrincipalId'] = $cusInfo['areaPrincipalId'];
                                      $objectArr[$key]['areaName'] = $cusInfo['areaName'];
                                      $objectArr[$key]['areaCode'] = $cusInfo['areaCode'];
                                      //����ʱ���
                                      $objectArr[$key]['beginDate'] = $this->service->transitionTime($val['beginDate']);
                                      $objectArr[$key]['endDate'] = $this->service->transitionTime($val['endDate']);
                                      //�����ͬ״̬
                   	                  $state = $val['state'];
                   	                  if(empty($conState[$state])){
                   	                  	array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,��ͬ״̬����(ִ����,�����,�ѹر�)" ) );
                   	                  }else{
                                          $objectArr[$key]['state'] = $conState[$state];
                                          $objectArr[$key]['ExaDT'] = $ExaDT['ExaDT'];
                                          $objectArr[$key]['ExaDTOne'] = $ExaDT['ExaDT'];
                                          $objectArr[$key]['currency'] = "�����";
                                          $objectArr[$key]['rate'] = "1";
                                          $objectArr[$key]['ExaStatus'] = "���";
                                          $objectArr[$key]['isImport'] = "1";
                                          //ҵ����
                                          $prinvipalId = $objectArr[$key]['prinvipalId'];
											$orderCodeDao = new model_common_codeRule();
											$deptDao = new model_deptuser_dept_dept();
											$dept = $deptDao->getDeptByUserId($prinvipalId);
											$objectArr[$key]['objCode'] = $orderCodeDao->getObjCode($objectArr[$key]['contractType'], $dept['Code']);
                                          $conDao = new  model_contract_contract_contract();
                                          $newId = $conDao->importAdd_d($objectArr[$key], true);
                                          if($newId){
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ɹ���" ) );
                                          }else{
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ�ܣ�" ) );
                                          }
                   	                  }
                                   }
                                }
                             }
                       	  }
                       }
                    }

                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "������", array ("��ͬ���","��ͬ������", "���" ) );
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}


/***********************************************��ͬ����***********end***************************************************/

/***********************************************�̻�����***********begin***************************************************/
	/**
	 * �ϴ�EXCEL
	 */
	function c_upExceltoChance() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'createTime', //����ʱ��
			1 => 'chanceCode', //�̻����
			2 => 'status', //�̻�״̬
			3 => 'customerCode', //�ͻ����
			4 => 'chanceName', //�̻�����
			5 => 'prinvipalName', //�̻�������
			6 => 'chanceTypeName', //�̻�����
			7 => 'chanceNatureName', //�̻�����
			8 => 'chanceMoney', //�̻��ܶ�
			9 => 'winRate', //�̻�Ӯ��
			10 => 'chanceStage', //�̻��׶�
			11 => 'predictContractDate', //Ԥ�ƺ�ͬǩ������
			12 => 'predictExeDate', //Ԥ�ƺ�ִͬ������
			13 => 'contractPeriod', //��ִͬ������
			14 => 'contractCode',//��ͬ��
			15 => 'contractTurnDate', //ת��ͬ����
			16 => 'progress',//��Ŀ��չ����
			17 => 'competitor'//��Ŀ��������
		);
		$this->c_addExeceltoChance($objNameArr);
	}

	/**
	 * �ϴ�EXCEl������������
	 */
	function c_addExeceltoChance($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}

				$winRateArr = array("0"=>"0","0.25"=>"25","0.5"=>"50","0.8"=>"80","1"=>"100");//��ͬӮ��
				$StateArr = array("�ر�"=>"3","�����ɺ�ͬ"=>"4","������"=>"5","��ͣ"=>"6");//״̬
				$arrinfo = array();//��������

                //ѭ�������������
                foreach($objectArr as $key => $val){
                    //�ж��̻����ͺ��̻�����
                    $datadictDao = new model_system_datadict_datadict();
                    $SJLX = $datadictDao->getCodeByName("SJLX", $val['chanceTypeName']);
                    $SJSX = $datadictDao->getCodeByName($SJLX, $val['chanceNatureName']);
                    $SJJD = $datadictDao->getCodeByName("SJJD", $val['chanceStage']);
                    $chanceCode = $this->service->findChanceCode($val['chanceCode']);
                  if(empty($val['chanceCode']) || !empty($chanceCode)){
                  	  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,�̻����Ϊ�ջ��Ѵ���" ) );
                  }else{
                  	if(empty($SJLX) || empty($SJJD)){
                   	  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ�ܣ��̻����ͻ��̻��׶δ���" ) );
                   }else{
                   	   $objectArr[$key]['chanceType'] = $SJLX;
                   	   $objectArr[$key]['chanceNature'] = $SJSX;
                   	   $objectArr[$key]['chanceStage'] = $SJJD;
                   	   //�ж�Ӯ��
                   	   $winRate = $val['winRate'];
//                   	   $winRateName = $datadictDao->getDataNameByCode($winRate,$returnType = 'name');
                       if((empty($winRate)) && $winRate != '0'){
                          array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,Ӯ����д����" ) );
                       }else{

                       	  $objectArr[$key]['winRate'] = $winRate * 100;
                                //������
                                $prinvipalArr = $this->service->userArr($val['prinvipalName']);
                                if(empty($prinvipalArr)){
                                  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,�̻������˲�����" ) );
                                }else{
                                	$objectArr[$key]['prinvipalId'] = $prinvipalArr['USER_ID'];
                                	$objectArr[$key]['prinvipalDept'] = $prinvipalArr['DEPT_NAME'];
                                	$objectArr[$key]['prinvipalDeptId'] = $prinvipalArr['DEPT_ID'];
                                	if(empty($val['customerCode'])){
                                		array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,�ͻ����Ϊ��" ) );
                                	}else{
                                		 //�жϿͻ�
                                    $cusIdArr = $this->service->isCusCode($val['customerCode']);
                                    $cusId = $cusIdArr[0]['id'];
                                    if(empty($cusId)){
                                       array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,�ͻ�������" ) );
                                    }else{
                                       //����ͻ������Ϣ
                                      $cusInfo = $this->service->isCusInfo($cusId);
                                      $objectArr[$key]['customerId'] = $cusId;
                                      $objectArr[$key]['customerName'] = $cusInfo['customerName'];
                                      $objectArr[$key]['customerType'] = $cusInfo['customerType'];
                                      $objectArr[$key]['customerTypeName'] = $datadictDao->getDataNameByCode($cusInfo['customerType']);
                                      $objectArr[$key]['Country'] = $cusInfo['contractCountry'];
                                      $objectArr[$key]['CountryId'] = $cusInfo['contractCountryId'];
                                      $objectArr[$key]['Province'] = $cusInfo['contractProvince'];
                                      $objectArr[$key]['ProvinceId'] = $cusInfo['contractProvinceId'];
                                      $objectArr[$key]['City'] = $cusInfo['contractCity'];
                                      $objectArr[$key]['CityId'] = $cusInfo['contractCityId'];
                                      $objectArr[$key]['areaPrincipal'] = $cusInfo['areaPrincipal'];
                                      $objectArr[$key]['areaPrincipalId'] = $cusInfo['areaPrincipalId'];
                                      $objectArr[$key]['areaName'] = $cusInfo['areaName'];
                                      $objectArr[$key]['areaCode'] = $cusInfo['areaCode'];
                                      //����ʱ���
                                      $objectArr[$key]['createTime'] = $this->service->transitionTime($val['createTime']);
                                      $objectArr[$key]['createName'] = $_SESSION['USERNAME'];
                                      $objectArr[$key]['createId'] = $_SESSION['USER_ID'];

                                      $objectArr[$key]['predictContractDate'] = $this->service->transitionTime($val['predictContractDate']);
                                      $objectArr[$key]['predictExeDate'] = $this->service->transitionTime($val['predictExeDate']);
                                      //�����ͬ״̬
                   	                  $state = $val['status'];
                   	                  if(empty($StateArr[$state])){
                   	                  	array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ��,�̻�״̬����" ) );
                   	                  }else{
                                          $objectArr[$key]['status'] = $StateArr[$state];
                                          $objectArr[$key]['isImport'] = "1";
                                          $chanceDao = new  model_projectmanagent_chance_chance();
                                          $newId = $chanceDao->importAdd_d($objectArr[$key], false);
                                          if($newId){
                                              //����������
                                             if(!empty($val['competitor'])){
                                                $isCom = $this->chanceCompetitor($val['competitor'],$newId);
                                                if(!$isCom){
                                                    array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "�������ָ�ʽ����ȷ���̻��Ѳ���ɹ�������ϵͳ���ֹ����¶�����Ϣ" ) );
                                                }
                                             }
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ɹ���" ) );
                                          }else{
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "����ʧ�ܣ�" ) );
                                          }
                                }
                             }
                                	}
                       	  }
                       }
                    }
                  }
                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "������", array ("�̻����","�̻�������", "���" ) );
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}

/**
 * �����̻���������
 */
function chanceCompetitor($competitor,$newId){
   $competitorArr = explode("/",$competitor);
   //��������������
   foreach($competitorArr as $k=>$v){
   	  $competitorInfo[$k] = array("competitor"=>$v);
   }
  if(is_array($competitorArr)){
    //����
	$competitor = new model_projectmanagent_chance_competitor();
	$competitor->createBatch($competitorInfo, array (
		'chanceId' => $newId
	), 'competitor');
	 return true;
  }
     return false;
}


	/**
	 * �ϴ�EXCEL
	 */
	function c_upExceltoChanceGoods() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'chanceCode', //�̻����
			1 => 'goodsId', //��Ʒ����
			2 => 'goodsName', //��Ʒ����
			3 => 'number', //��Ʒ����
			4 => 'money', //�ܽ��
		);
		$this->c_addExeceltoChanceGoods($objNameArr);
	}

	/**
	 * �ϴ�EXCEl������������
	 */
	function c_addExeceltoChanceGoods($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$arrinfo = array();//��������
                //ѭ�������������
                foreach($objectArr as $key => $val){
                  if(empty($val['chanceCode']) || empty($val['goodsId'])){
                     array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "����ʧ��,�̻����Ϊ�ջ��Ʒ����Ϊ��" ));
                  }else{
                  	  $chanceInfo = $this->service->findChanceCode($val['chanceCode']);
	                  if(empty($chanceInfo[0]['id'])){
	                  	  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "����ʧ��,�̻���Ų�����" ));
	                  }else{
	                  	 $objectArr[$key]['chanceId'] = $chanceInfo[0]['id'];
	                  	 $goodsDao = new model_goods_goods_goodsbaseinfo();
	                  	 $goodsInfo = $goodsDao->get_d($val['goodsId']);
	                  	 if(empty($goodsInfo)){
	                  	 	array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "����ʧ��,��Ʒ��Ϣ������" ));
	                  	 }else{
                            $objectArr[$key]['goodsTypeId'] = $goodsInfo['goodsTypeId'];
                            $objectArr[$key]['goodsTypeName'] = $goodsInfo['goodsTypeName'];
                            $objectArr[$key]['goodsName'] = $goodsInfo['goodsName'];
                            $chanceDao = new  model_projectmanagent_chance_goods();
                            $newId = $chanceDao->add_d($objectArr[$key], false);
                            if($newId){
                              	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "����ɹ���" ) );
                              }else{
                              	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "����ʧ�ܣ�" ) );
                              }
	                  	 }
	                  }
                  }
                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "������", array ("�̻����","��Ʒ����", "���" ) );
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}

	/**
	 * �ϴ�EXCEL
	 */
	function c_updateChance() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'createTime', //����ʱ��
			1 => 'chanceCode', //�̻����
			2 => 'status', //�̻�״̬
			3 => 'customerCode', //�ͻ����
			4 => 'chanceName', //�̻�����
			5 => 'prinvipalName', //�̻�������
			6 => 'chanceTypeName', //�̻�����
			7 => 'chanceNatureName', //�̻�����
			8 => 'chanceMoney', //�̻��ܶ�
			9 => 'winRate', //�̻�Ӯ��
			10 => 'chanceStage', //�̻��׶�
			11 => 'predictContractDate', //Ԥ�ƺ�ͬǩ������
			12 => 'predictExeDate', //Ԥ�ƺ�ִͬ������
			13 => 'contractPeriod', //��ִͬ������
			14 => 'contractCode',//��ͬ��
			15 => 'contractTurnDate', //ת��ͬ����
			16 => 'progress',//��Ŀ��չ����
			17 => 'competitor'//��Ŀ��������
		);
		$this->c_updateChanceDate($objNameArr);
	}

	/**
	 * �ϴ�EXCEl������������
	 */
	function c_updateChanceDate($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}

				$arrinfo = array();//��������

                //ѭ�������������
                foreach($objectArr as $key => $val){

                     //����ʱ���
                     $predictContractDate = $this->service->transitionTime($val['predictContractDate']);//Ԥ�ƺ�ͬǩ������
                     $predictExeDate = $this->service->transitionTime($val['predictExeDate']);//Ԥ�ƺ�ִͬ������
                     $contractTurnDate = $this->service->transitionTime($val['contractTurnDate']);//ת��ͬ����
                    $sql = "update oa_sale_chance set predictContractDate='".$predictContractDate."',predictExeDate='".$predictExeDate."',contractTurnDate='".$contractTurnDate."' where chanceCode='".$val['chanceCode']."'";
                    $sqlTiming = "update oa_sale_chance_timing set predictContractDate='".$predictContractDate."',predictExeDate='".$predictExeDate."',contractTurnDate='".$contractTurnDate."' where chanceCode='".$val['chanceCode']."'";
                    $this->service->query($sql);
                    $this->service->query($sqlTiming);
                }
				  echo "������ɣ�";
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}

/***********************************************�̻�����***********end***************************************************/
	/**
	 * �ж�Դ���Ƿ��в�Ʒ
	 * 2012.09.19
	 * zengzx
	 */
	function c_hasProduct() {
		$docType = $_POST ['type'];
		$daoName = $this->service->getSourceProDao($docType);
		$Dao = new $daoName ();
		$source = $this->service->sourceArr[$docType];
		$mainField = $source['mainField'];
		$sql = "select count(*) as proNum from " . $Dao->tbl_name . " where ".$mainField."= " . $_POST ['id'];
		$cusInfo = $Dao->_db->getArray ( $sql );
		echo $cusInfo [0] ['proNum'];
	}

	/**
	 * �ر�����ȷ��
	 * 2012.09.19
	 * zengzx
	 */
	function c_closeConfirm() {
		$docType = $_POST ['docType'];
		$daoName = $this->service->getSourceBaseDao($docType);
		$Dao = new $daoName ();
		if( isset($_POST['id']) ){
			echo $Dao->setEmailAfterCloseConfirm($_POST['id']);
		}else{
			echo 0;
		}
	}



}
?>
