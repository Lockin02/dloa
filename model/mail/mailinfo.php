<?php

/**
 * �ʼ���Ϣmodel����
 */
class model_mail_mailinfo extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_info";
		$this->sql_map = "mail/mailinfoSql.php";
		parent::__construct ();

		$this->mailStatus = array ('δȷ��','��ȷ��' ); //�ʼ�����״̬
	}

	/**
	 * �ʼ���������
	 */
	private $mailType = array(
		'YJSQDLX-FPYJ' => 'invoice'
	);

	/**
	 * ��ȡ�ʼ����Ͷ�Ӧ����
	 */
	function getObjCode($thisVal){
		return $this->mailType[$thisVal];
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
		$str = ""; //���ص�ģ���ַ���
		if ($rows) {
			$i = 0; //�б��¼���
			$datadictDao = new model_system_datadict_datadict ();

			foreach ( $rows as $key => $val ) {
				$mailType = $datadictDao->getDataNameByCode ( $val ['mailType'] );
				$mailStatus = $this->mailStatus [$val ['mailStatus'] - 1];
				$i ++;
				$str .= <<<EOT
						<tr id="tr_$val[id]">
							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td align="center">$i</td>
							<td align="center">$val[mailNo]</td>
							<td align="center">$val[receiver]</td>
							<td align="center">$val[tel]</td>
							<td align="center">$val[mailTime]</td>
							<td align="center">$mailType</td>
							<td align="center">$mailStatus</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=mail_mailinfo&action=init&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�޸��ʼ���Ϣ" class="thickbox">�޸�</a>
								<a href="?model=mail_mailsign&action=toAdd&mailInfoId=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�ͻ�ǩ��" class="thickbox">�ͻ�ǩ��</a>

							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/**
	 * ҳ����ʾ��̬�ʼ������Ʒ���÷���,�����ַ�����ҳ��ģ���滻�������޸ĵ�������
	 */
	function showproductsEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						<input type="hidden" id="productId$j" name="mailinfo[productsdetail][$j][productId]" value="$val[productId]"/>
						<input type="hidden" id="productNo$j" name="mailinfo[productsdetail][$j][productNo]" value="$val[productNo]"/>
						<input type="text" id="productName$j" class="txtlong" name="mailinfo[productsdetail][$j][productName]" value="$val[productName]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][mailNum]" value="$val[number]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][remark]" value="$val[remark]"/>
					</td>
					<td align="center">
						<img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='ɾ����'>
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}


	/**
	 * �����ʼı༭ҳ���嵥��ʾ
	 */
	function showShipEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						<input type="hidden" id="mailInfoId" name="mailinfo[productsdetail][mailInfoId]" value="$val[mailInfoId]"/>
						<input type="hidden" id="mailInfoId$j" name="mailinfo[productsdetail][$j][mailInfoId]" value="$val[mailInfoId]"/>
						<input type="hidden" id="productId$j" name="mailinfo[productsdetail][$j][productId]" value="$val[productId]"/>
						<input type="hidden" id="productNo$j" name="mailinfo[productsdetail][$j][productNo]" value="$val[productNo]"/>
						<input type="text" id="productName$j" class="txtlong" name="mailinfo[productsdetail][$j][productName]" value="$val[productName]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][mailNum]" value="$val[mailNum]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][remark]" value="$val[remark]"/>
					</td>
					<td align="center">
						<img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='ɾ����'>
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}

	/**�鿴�ʼ���Ϣģ��
	*author can
	*2011-4-19
	*/
	function showMailInfo($rows){
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						$val[productName]
					</td>
					<td align="center">
						$val[mailNum]
					</td>
					<td align="center">
						$val[remark]
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
		//print_r($object);
		try {
			$this->start_d ();
			//$object ['mailStatus'] = 1;
			if (empty ( $object ['mailApplyId'] )) {
				unset ( $object ['mailApplyId'] );
			}
			$productsDetailDao = new model_mail_mailproductsdetail ();
			$mailapplyDao = new model_mail_mailapply();
			$searchArr = array( 'id' => $object['mailApplyId'] );
			$mailinfoId = parent::add_d ( $object, true );
			if (! empty ( $object ['productsdetail'] )) {
				foreach ( $object ['productsdetail']  as $key => $value ) {
					$value ['mailInfoId'] = $mailinfoId;
					$productsDetailDao->add_d ( $value );
				}

			}
			$mailapplyDao->updateField( $searchArr , 'status', '2' );
			$this->commit_d ();
			return $mailinfoId;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}


	/**
	 * ��Ӷ���
	 */
	function addShip_d($object) {
		try {
			$this->start_d ();
			$object ['mailStatus'] = 0;

            //��ȡ�ʼļ�¼
            $emailArr = $object['email'];
            unset($object['email']);

			$productsDetailDao = new model_mail_mailproductsdetail ();
			$shipDao = new model_stock_outplan_ship();
			$searchArr = array(
				'id' => $object['docId']
			);
			$shipDao->updateField( $searchArr,'mailCode',$object['mailNo'] );
			$auditNameStr = str_replace('��',',',$object['mailNo']);
			$auditNameStr1 = str_replace(',','/',$auditNameStr);
			$mailCodeArr = explode('/',$auditNameStr1);
			$mailObjArr = array();
			foreach ( $mailCodeArr as $key=>$val ){
				$mailObjArr[$key]=$object;
				$mailObjArr[$key]['mailNo'] = $mailCodeArr[$key];
				if($key!=0){
					unset($mailObjArr[$key]['productsdetail']);
				}
			}
			foreach( $mailObjArr as $key=>$val ){
				$mailinfoId = parent::add_d ( $mailObjArr[$key], true );
				if ( $key == 0 && !empty ( $mailObjArr[$key] ['productsdetail'] )) {
					foreach ( $mailObjArr[$key] ['productsdetail']  as $key => $value ) {
						$value ['mailInfoId'] = $mailinfoId;
						$productsDetailDao->add_d ( $value );
					}

				}
			}
            //�����ʼ�
            if( $object['ismail'] == '1'&& !empty($emailArr['TO_ID'])){
                $this->thisMailForShip_d($emailArr,$object);
            }
			$this->commit_d ();
			return $mailinfoId;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}


	/**
	 * ���������޸Ķ���
	 */
	function edit_d($mail) {
		try {
			$this->start_d ();
			$productsDetailDao = new model_mail_mailproductsdetail ();
			//ɾ���ʼĲ�Ʒ��Ϣ
			$productsDetailDao->deleteProductsByMailId ( $mail ['id'] );
			//�ʼĲ�Ʒ��ϸ
			if (is_array ( $mail ['productsdetail'] )) {
				foreach ( $mail ['productsdetail'] as $key => $mailproduct ) {
					if (! empty ( $mailproduct ['productName'] )) {
						$mailproduct ['mailInfoId'] = $mail [id];
						$productsDetailDao->add_d ( $mailproduct );
					}
				}

			}
			$apply = parent::edit_d ( $mail, true );

			$this->commit_d ();
			return $apply;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/**
	 * ���������޸Ķ���
	 */
	function shipEdit_d($mail) {
		try {
//			echo "<pre>";
//			print_R( $mail );
			$this->start_d ();
			$productsDetailDao = new model_mail_mailproductsdetail ();
			//ɾ���ʼĲ�Ʒ��Ϣ
			$productsDetailDao->deleteProductsByMailId ( $mail['productsdetail']['mailInfoId'] );
			unset( $mail ['productsdetail']['mailInfoId'] );
			//�ʼĲ�Ʒ��ϸ
			if (is_array ( $mail ['productsdetail'] )) {
				foreach ( $mail ['productsdetail'] as $key => $mailproduct ) {
					if (! empty ( $mailproduct ['productName'] )) {
						$productsDetailDao->add_d ( $mailproduct );
					}
				}

			}
			$apply = parent::edit_d ( $mail, true );

			$this->commit_d ();
			return $apply;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/*
	 * ��ȡ�ʼ���Ϣ���ʼĲ�Ʒ
	 */
	function get_d($id) {
		$mailproductDao = new model_mail_mailproductsdetail ();
		$mailproducts = $mailproductDao->getProductsDetail ( $id );
		$mailinfo = parent::get_d ( $id );

		$mailinfo ['mailproducts'] = $mailproducts;
		return $mailinfo;
	}


	/**�����û�ID������������
	*author can
	*2011-4-19
	*/
	function getDeptByUserId($userId){
		//�����û����ڵĲ���ID
		$sql="select DEPT_ID from user where USER_ID='" .$userId. "' ";
		$row=$this->_db->query($sql);
		$deptID=mysql_fetch_row($row);
		//���Ҳ�������
		$sql2="select DEPT_NAME from department where DEPT_ID='" .$deptID[0]. "' ";
		$res=$this->_db->query($sql2);
		$deptName=mysql_fetch_row($res);
//		print_r($deptName[0]);
		return $deptName[0];
	}
	/**
	 * ���ݷ�����Id��ȡ����������
	 */
	 function getShipMessage_d( $shipId ){
	 	$shipDao = new model_stock_outplan_ship();
	 	$shipInfo = $shipDao->get_d( $shipId );
	 	return $shipInfo;
	 }

	 /**************************��Ʊ�ʼĲ���****************************/

	/**
	 * �����ʼļ�¼ ��ͬʱ�ı䷢Ʊ״̬
	 */
	function addInvoice_d($object){
		try{
			$this->start_d();

            //��ȡ�ʼļ�¼
            $emailArr = $object['email'];
            unset($object['email']);

			$newId = parent::add_d($object,true);
			$invoiceDao = new model_finance_invoice_invoice();
			$invoiceDao->changeMailStatus_d($object['docId'],1);

            //�����ʼ�
            if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
                $this->thisMailForInvoice_d($emailArr,$object);
            }

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

    /**
     * ��ȡҵ����Ϣ
     */
    function getObjInfo_d($objId,$objType){
        if($objType == 'YJSQDLX-FPYJ'){
            $invoiceDao = new model_finance_invoice_invoice();
            $object = $invoiceDao->getInvoiceAndApply_d($objId,$objType);
        }
        return $object;
    }

	/**************************��Ʊ�ʼĲ���****************************/

	/**
	 * ȷ���ʼ���Ϣ
	 */
	function confirm_d($id){
		return $this->updateById( array('id' => $id ,'mailStatus' => 1) );
	}

    /**
     * �ʼ�����
     */
    function thisMailForInvoice_d($emailArr,$object,$thisAct = '����'){
        $addMsg = '��Ʊ����Ϊ �� ' . $object['docCode'] .'<br/>�ռ���λ �� '.$object['customerName'].'<br/>�ռ��� ��' . $object['receiver'].'<br/>'.
        '��ݹ�˾Ϊ ��' .$object['logisticsName'] .'<br/>�ʼĵ���Ϊ : ' .$object['mailNo'];

        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$thisAct,$object['invoiceNo'],$emailArr['TO_ID'],$addMsg,'1');
    }
    /**
     * �����ʼ�-�ʼ�����
     * 2011��7��28�� 15:18:01
     * zengzx
     */
    function thisMailForShip_d($emailArr,$object,$thisAct = '����'){
    	$shipDao = new model_stock_outplan_ship();
    	if($object['docId']){
    		$shipObj = $shipDao->get_d($object['docId']);
    	}else{
    		return 0;
    	}
//    	echo "<pre>";
//    	print_R($shipObj);
    	$docType = $shipObj['docType'];
    	$planDaoName = $shipDao->relatedStrategyArr[$docType];
    	$planObj = $shipDao->getDocInfo($shipObj['planId'],new $planDaoName());
//    	echo "<pre>";
//    	print_R($planObj);
		$type='';
		if( $docType ){
			switch($docType){
			case"oa_contract_contract":$type='��ͬ����';break;
			case"oa_borrow_borrow":$type='���÷���';break;
			case"oa_present_present":$type='���ͷ���';break;
			case"oa_contract_exchangeapply":$type='��������';break;
			case"oa_service_accessorder":$type='������������������';break;
			case"oa_service_repair_apply":$type='�������ά�����뵥����';break;
			case"independent":$type='��������';break;
			}
		}
    	$title = $planObj['docCode'].",".$planObj['docName'].";�ʼ���Ϣ";
//    	echo $title;
    	$products = $object['productsdetail'];
		$productMsg = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>�ʼ���Ʒ����</td><td>����</td><td>��ע</td></tr>";
		$i=1;
    	foreach( $products as $key=>$val ){
    		$productMsg .=<<<EOT
    		<tr bgcolor='#7AD730' align="center" ><td>$i</td><td>$val[productName]</td><td>$val[mailNum]</td><td>$val[remark]</td></tr><br>
EOT;
    		$i++;
    	}
        $addMsg = '<br/>�������� �� ' . $type .'<br/>���ݱ�� �� ' . $planObj['docCode'] .'<br/>�������� �� ' . $planObj['docName'] .'<br/>��������Ϊ �� '
        . $object['docCode'] .'<br/>�ռ���λ �� '.$object['customerName'].'<br/>�ռ��� ��' . $object['receiver'].'<br/>��ݹ�˾ ��' . $object['logisticsName'].
        '<br/>��ݵ��ţ�' .$object['mailNo'] .'<br/>������'.$object['number'] .'<br/>�ʼĵ�ַ��'.$object['address'] .'<br/>�ռ��˵绰��'.$object['tel']. $productMsg;
//        echo "<pre>";
//        print_R($addMsg);
        $emailDao = new model_common_mail();
        $emailDao->mailClear($title,$emailArr['TO_ID'],$addMsg);
        $emailInfo = $emailDao->batchEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$thisAct,$object['invoiceNo'],$emailArr['TO_ID'],$addMsg,'1');
    }

    function getMailNo($mailObj){
    	$sql = " select c.mailNo from " . $this->tbl_name . " c where docType='".$mailObj['docType']."' and docId=".$mailObj['docId'];
    	$mailNoArr = $this->_db->getArray($sql);
    	$mailCodeArr = array();
    	foreach( $mailNoArr as $key => $val){
    		$mailCodeArr[$key] = $mailNoArr[$key]['mailNo'];
    	}
    	foreach( $mailCodeArr as $key=>$val ){
    		if( $mailCodeArr[$key] == $mailObj['mailNo'] ){
    			unset($mailCodeArr[$key]);
    		}
    	}
    	$mailNoStr = implode( ',',$mailCodeArr );
    	return $mailNoStr;
    }
    /**
     * ����mailId��ȡ�ӱ���Ϣ
     */
     function getEqu($mailObj){
     	$sql="select c.id from ".$this->tbl_name." c where c.docType='".$mailObj['docType']."' and c.docId=".$mailObj['docId']." limit 1";
     	$mailIdArr = $this->_db->getArray($sql);
     	$mailEquDao = new model_mail_mailproductsdetail();
     	$condiction=array(
     		'mailInfoId'=>$mailIdArr[0]['id']
     	);
     	$mailEquObj = $mailEquDao->findAll($condiction);
     	return $mailEquObj;
     }

     /**
      * ���ݷ�������ȡ�ʼ�������
      */
      function getMailman($shipObj){
      	$docType=$shipObj['docType'];
      	$planId=$shipObj['planId'];
      	$outplanDao = new model_stock_outplan_outplan();
      	$shipDao = new model_stock_outplan_ship();
      	$relatedStrategy=$shipDao->relatedStrategyArr[$docType];
      	$saleman = $shipDao->getSaleman($shipObj['docId'],$docType,new $relatedStrategy);
      	$condition = array(
			'id'=>$planId
      	);
      	//�����ƻ�Id
      	$createArr = $outplanDao->findAll($condition,null,'createName,createId',null);
//      	$createArr = $planDao->findBy('id',$planId);
      	$mailmanArr=array();
      	$mailmanIdArr=array();
      	$mailmanArr['saleman'] = $saleman['responsible'];
      	$mailmanIdArr['salemanId'] = $saleman['responsibleId'];
      	if( $mailmanIdArr['salemanId'] != $createArr[0]['createId'] ){
	      	$mailmanArr['createName'] = $createArr[0]['createName'];
	      	$mailmanIdArr['createId'] = $createArr[0]['createId'];
      	}
      	$mailmanStr = implode(',',$mailmanArr);
      	$mailmanIdStr = implode(',',$mailmanIdArr);
      	$mailman['TO_NAME'] = $mailmanStr.",".$_SESSION['USERNAME'];
      	$mailman['TO_ID'] = $mailmanIdStr.",".$_SESSION['USER_ID'];
      	return $mailman;
      }

	/**
	 * �ʼķ�����Ϣ���봦��
	 * 2011��7��30�� 14:34:10
	 * zengzx
	 */
	 function addExecelDatabypro_d( $objKeyArr ){
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();
		$objectArr = array();
		$excelData = array ();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_mail_mailExcelUtil::upReadExcelData ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//�ж��Ƿ��������Ч����
			if ($excelData) {
				foreach ($excelData as $key=>$val){
					//����̱��ƻ����ƺ��������Ƹ�ʽ����ɾ������Ŀո����������Ϊ�գ���������ݲ�����Ч��
					$excelData[$key][0] = str_replace( ' ','',$val[0]);
					if( $excelData[$key][0] == '' ){
						$tempArr['docCode'] = $val[0];
						$tempArr['result'] = '����ʧ�ܣ����ʼĵ���Ϊ�գ��޷����룩';
						array_push( $resultArr,$tempArr );
						unset( $excelData[$key] );
					}
				}
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objKeyArr as $index => $fieldName ) {
						//��ֵ������Ӧ���ֶ�
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				foreach( $objectArr as $key=>$val ){
					$condition = array(
						'mailNo' => $objectArr[$key]['mailNo']
					);
					$rows = array(
						'number' => $objectArr[$key]['number'],
						'weight' => $objectArr[$key]['weight'],
						'serviceType' => $objectArr[$key]['serviceType'],
						'fare' => $objectArr[$key]['fare'],
						'anotherfare' => $objectArr[$key]['anotherfare'],
						'mailMoney' => $objectArr[$key]['mailMoney']
					);
					$this->update( $condition,$rows );
					$tempArr['docCode']=$val['mailNo'];
					if ($this->_db->affected_rows () == 0) {
						$tempArr['result']='����ʧ�ܣ������Ų����ڻ�������Ч��';
					}else{
						$tempArr['result']='����ɹ���';
					}
					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
//				echo ( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
//			echo ( "�ϴ��ļ����Ͳ���EXECEL!");
		}

	}

	/**
	 * ����Դ��Id��Դ������ɾ���ʼ���Ϣ
	 */
	function deleteByDoc($docId,$docType){
		if( $docId && $docType ){
			$condiction = array(
				'docType'=>$docType,
				'docId'=>$docId
			);
			$mailObj = $this->findAll($condiction);
			$mailIdArr = array();
			if(is_array($mailObj)&&count($mailObj)>0){
				foreach ( $mailObj as $key=>$val){
					$mailIdArr[$key] = $val['id'];
				}
				$mailIdStr = implode(',',$mailIdArr);
				$mailEquDao = new model_mail_mailproductsdetail();
				mysql_query ( "delete from " . $mailEquDao->tbl_name . " where mailInfoId in(" . $mailIdStr . ")" );
				$this->deletes($mailIdStr);
			}
			return 1;
		}
	}

	function getDocIdByOrder_d($orderId,$type){
		//��ȡ�ú�ͬ�����ķ�Ʊ
		$invoiceDao = new model_finance_invoice_invoice();
		$invoiceIds = $invoiceDao->getInvId_d( $orderId,$type );
		$invIdStr = implode(',',$invoiceIds);
		//��ȡ�ú�ͬ�����ķ����ƻ�
		$outplanDao = new model_stock_outplan_ship();
		$outplanIds = $outplanDao->getShipId_d($orderId,$type);
		$outIdStr = implode(',',$outplanIds);
		$docIdArr = array(
			'invoiceIds' => $invIdStr,
			'outplanIds' => $outIdStr,
		);
		return $docIdArr;
	}
}
?>