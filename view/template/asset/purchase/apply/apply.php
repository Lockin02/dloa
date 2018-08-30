<?php

/**
 *
 * �ɹ�����model
 * @author fengxw
 *
 */
header("Content-type: text/html; charset=gb2312");
class model_asset_purchase_apply_apply extends model_base {
	//�ɹ��ƻ���������
	private $purchaseType;
	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_asset_purchase_apply";
		$this->sql_map = "asset/purchase/apply/applySql.php";
		$this->mailArr=$mailUser[$this->tbl_name];
		$this->aduitEmail=$mailUser["apply_audit"];//����ͨ�������ʼ�֪ͨ��

		$this->purchaseType = array (
			0 => array (
				'purchCName' => '���۲ɹ�',
				'purchKey' => 'contract_sales',
				'objectEquName' => 'model_purchase_plansales_purchsalesplanequ',
				'funByWayAmount' => 'funByWayAmount',
				'funUpdateBusiExeNum' => 'funUpdateBusiExeNum'
			),
			1 => array (
				'purchCName' => '����ɹ�',
				'purchKey' => 'stock'
			),
			2 => array (
				'purchCName' => '�з��ɹ�',
				'purchKey' => 'rdproject'
			),
			3 => array (
				'purchCName' => '�ʲ��ɹ�',
				'purchKey' => 'assets'
			),
			4 => array (
				'purchCName' => '�����ɹ�',
				'purchKey' => 'order'
			),
			5 => array (
				'purchCName' => '��ͬ�ɹ�',
				'purchKey' => 'contract_sales'
			),
			6 => array (
				'purchCName' => '�����ɹ�',
				'purchKey' => 'produce'
			),
			7=> array (
				'purchCName' => '���ۺ�ͬ�ɹ�',
				'purchKey' => 'oa_sale_order'
			),
			8 => array (
				'purchCName' => '���޺�ͬ�ɹ�',
				'purchKey' => 'oa_sale_lease'
			),
			9 => array (
				'purchCName' => '�����ͬ�ɹ�',
				'purchKey' => 'oa_sale_service'
			),
			10 => array (
				'purchCName' => '�з���ͬ�ɹ�',
				'purchKey' => 'oa_sale_rdproject'
			),
			11 => array (
				'purchCName' => '����ɹ�',
				'purchKey' => 'oa_borrow_borrow'
			),
			12 => array (
				'purchCName' => '����ɹ�',
				'purchKey' => 'oa_present_present'
			)
		);
		parent::__construct ();
	}
		private $mainArr;
		
		private $aduitEmail;
		
		//��˾Ȩ�޴���
		protected $_isSetCompany = 1;
		
	/**
	 * �½�����ɹ����뼰��ϸ��
	 */
	function add_d($object){
		try{
			$this->start_d();
			
			//ȥ��ҳ��ɾ��������
			foreach ($object['applyItem'] as $key => $val){
				if($val['isDelTag'] == 1){
					unset($object['applyItem'][$key]);
				}
			}
			if(empty($object['applyItem'])){
				msg ( '����д�òɹ�������ϸ������Ϣ��' );
				throw new Exception('�ɹ�������Ϣ������������ʧ�ܣ�');
			}
			//����ҵ����
			$codeDao=new model_common_codeRule();
			$object['formCode']=$codeDao->purchApplyCode("oa_purch_plan_basic","asset");
			//����ǽ�������״̬Ϊ�ѷ���
			if($object['purchaseDept'] == "1"){
				$object['state'] = '���ύ';
			}
			$id=parent::add_d($object,true);
			//������ϸ��
			$datadictDao = new model_system_datadict_datadict();
			$applyItemDao=new model_asset_purchase_apply_applyItem();
			foreach($object['applyItem'] as $key => $val){
				if($val['isDelTag']!=1){
					$val['applyId']=$id;
					$val['applyCode']=$object['formCode'];
					$val['purchDept']= $object['purchaseDept'];
					$val['productCategoryName']=$datadictDao->getDataNameByCode($val['productCategoryCode']);
					$applyItemDao->add_d($val);
				}
			}

			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['applyItem'] )) {
				$id = parent::edit_d ( $object, true );
				$applyItemDao=new model_asset_purchase_apply_applyItem();
				//$itemsArr = $this->setItemMainId ( "applyId", $object ['id'], $object ['applyItem'] );
				$mainArr=array("applyId"=>$object ['id']);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object ['applyItem']);
				$itemsObj = $applyItemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function assignPurchaser_d($object) {
		try {
			$this->start_d ();
			$id = parent::edit_d ( $object, true );
			$this->commit_d ();
			return $object ['id'];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * ѯ�۱���
	 */
	function inquire_edit_d($object) {
		try {
			$object['state']='���ύ';
			$this->start_d ();
			if (is_array ( $object ['applyItem'] )) {
					$addArr = array();//��ȷ��Ϣ����
					foreach ( $object['applyItem'] as $key => $obj ) {
						//��ɾ�����
						if($object['applyItem'][$key]['isDelTag'] == 1){
							$object['applyItem'][$key]['isDel'] = 1;
						}
						$object['applyItem'][$key]['isDelTag'] = 0;
					}
//				echo "<pre>";
//				print_r($object);
				$id = parent::edit_d ( $object, true );
				$applyItemDao=new model_asset_purchase_apply_applyItem();
				//$itemsArr = $this->setItemMainId ( "applyId", $object ['id'], $object ['applyItem'] );
				$mainArr=array("applyId"=>$object ['id']);
//				echo "<pre>";
//				print_r($mainArr);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object ['applyItem']);
//				echo "<pre>";
//				print_r($itemsArr);
				$itemsObj = $applyItemDao->saveDelBatch ( $itemsArr );
//				echo "<pre>";
//				print_r($itemsObj);
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/**
	 * ��ֲɹ�ʱ�������ʼ�����֪ͨ
	 *@param $id �ɹ�����Id
	 */
	 function sendEmail_d($id){
		$applyItemDao=new model_asset_purchase_apply_applyItem();
		$itemRow=$applyItemDao->getItemByApplyId($id,1,0);
		if(is_array($itemRow)){
			//�����ʼ�֪ͨ�ɹ�������
			$mailRow=$this->mailArr;
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$mailRow['sendUserId'];
			$emailArr['TO_NAME']=$mailRow['sendName'];
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>�ɹ�����</b></td><td><b>ϣ������ʱ��</b></td><td><b>��ע</b></td></tr>";
				foreach($itemRow as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$amountAll=$equ ['purchAmount'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->pushPurch($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'asset_pushPurch',',�ɹ����뵥�ݺ�Ϊ��<font color=red><b>'.$equ["applyCode"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
		}
	 }

	/**
	 * ȷ�ϲɹ���������
	 * add by chengl 2012-04-07
	 */
	function confirmProduct_d($object) {
		try {
			$flag = false;
			$set = false;
			$id = null;
			foreach ( $object  as $key => $equ ) {
				//������������0���Ҳɹ������������Ʋ�Ϊ�ղŽ��в���
				if ( is_array($equ)&&count($equ)>0&&isset($equ['productId']) ) {
					$productName=$equ ['productName'];
					$productId=$equ ['productId'];
					$productCode=$equ ['productNumb'];
					$equId=$equ['id'];
					$id = $equ['applyId'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					if($equ ['productId']>0){
						$sql="update oa_asset_purchase_apply_item set productId=$productId,productName='$productName'," .
								"productCode='$productCode',pattem='$pattem',unitName='$unitName' where id=$equId";
						$this->query($sql);
					}
					$set = true;
				}
				if(empty($equ ['productId'])){
					$flag = true;
				}
			}
			//ȷ�����
			if($flag&&$set){
				$this->update(array("id"=>$id),array("productSureStatus"=>2));
			}else if(!$flag&&$set){
				$this->update(array("id"=>$id),array("productSureStatus"=>1));
			}else if($flag&&!$set){
				$this->update(array("id"=>$id),array("productSureStatus"=>0));
			}
			$equDao=new model_asset_purchase_apply_applyItem ();
			$row=$this->get_d($object['id']);
			$equRow= $equDao->getPurchItem_d ( $object['id']);
			//�����ʼ�
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['applicantId'];
			$emailArr['TO_NAME']=$row['applicantName'];
			if(is_array($equRow )){
				$j=0;
				$equName=array();
				$addmsg.="�ɹ����뵥��ţ�<font color='red'>".$row['formCode']."</font><br>";
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>�������</b></td><td><b>ȷ������</b></td><td><b>��������</b></td><td><b>���ϱ���</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>��������</b></td><td><b>ϣ����������</b></td><td><b>��ע</b></td></tr>";
				foreach($equRow as $key => $val ){
					$j++;
					$equName[]=$val['inputProductName'];
					$productCategoryName=$val['productCategoryName'];
					$inputProductName=$val ['inputProductName'];
					$productName=$val ['productName'];
					$productCode=$val ['productCode'];
					$amountAll=$val ['applyAmount'];
					$dateHope=$val['dateHope'];
					$remark=$val['remark'];
					$pattem=$val['pattem'];
					$unitName=$val['unitName'];
					$addmsg .=<<<EOT
					<tr align="center" >
								<td>$j</td>
								<td>$productCategoryName</td>
								<td>$inputProductName</td>
								<td>$productName</td>
								<td>$productCode</td>
								<td>$pattem</td>
								<td>$unitName</td>
								<td>$amountAll</td>
								<td>$dateHope</td>
								<td>$remark</td>
						</tr>
EOT;
					}
					$addmsg.="</table><br/>";
			}
			$equName=array_unique($equName);
			$equName=implode(",",$equName);
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->arrivalEmailWithEqu($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'�ɹ�������Ϣȷ��('.$equName.')','',"���ʼ���".$_SESSION['USERNAME']."���з��͡�����գ�",$emailArr['TO_ID'],$addmsg,1);

			return true;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * �鿴�ɹ������豸�б��̶��ʲ�������ɹ�Ա��
	 * add by chengl 2012-04-07
	 * @param	array	��ʾ��������
	 * @return	string	��ʾHTML�ַ���
	 */
	function showConfirmAssetRead_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$productName=empty($val['productName'])?$val['inputProductName']:$val['productName'];
				if(empty($productName)){
					$productName=$val['inputProductName'];
				}
				$isNeedConfirm=empty($val['productId']);
				$isBack=$val['isBack']==1?"checked":"";
				$backReason=$val['backReason'];
				$equId=$val['id'];
				$trstyle="";
				$isBackCheckbox="";
				//$backReasonCheckbox="";
				if($isNeedConfirm){
					$isBackCheckbox="<input type='checkbox' id='isBack$i' name='basic[equ][$i][isBack]' value='1' $isBack/>";
					//$backReasonCheckbox="<input class='txt' name='basic[equ][$i][backReason]'>$backReason</input>";
					$trstyle="style='color:red'";
				}

				$str .= <<<EOT
					<tr class="$iClass" $trstyle>
						<td>$i<input type="hidden" name="apply[equ][$i][id]" value="$equId"/></td>
						<td>$val[productCategoryName]</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[applyAmount]</td>
						<td>$val[applyAmount]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[dateEnd]</td>
						<td>
							<div class='divChangeLine'>$val[remark]</div>
						</td>
						<td>$isBackCheckbox</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>���������嵥��Ϣ</td></tr>";
		}
		return $str;
	}

	function getPlan_d($id) {
		$plan = $this->get_d ( $id );
		//var_dump($plan['id']);
		$i = 0;
		//���òɹ�����
		$plan = $this->purchTypeToCName ( $plan );
		//��ȡ��Ӧ��Ʒ����
		$itemDao = new model_asset_purchase_apply_applyItem ();
		$plan ['childArr'] = $itemDao->findAll ( array('applyId'=>$plan['id'],'purchDept'=>1) );
		return $plan;
	}
	function purchTypeToCName($plan) {
		$showStatus = $this->purchTypeToVal ( $plan ['purchType'] );
		$plan ['purchTypeCName'] = $showStatus;
		return $plan;
	}
	/**
	 * �ɹ�����
	 * ͨ��value����״̬
	 */
	function purchTypeToVal($purchVal) {
		$returnVal = false;
		foreach ( $this->purchaseType as $key => $val ) {
			if ($val ['purchKey'] == $purchVal) {
				$returnVal = $val ['purchCName'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * �鿴�ɹ������豸�б��̶��ʲ���
	 * add by chengl 2012-04-07
	 * @param	array	��ʾ��������
	 * @return	string	��ʾHTML�ַ���
	 */
	function showAssetRead_s($listEqu){
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$productName=empty($val['productName'])?$val['inputProductName']:$val['productName'];
				if(empty($productName)){
					$productName=$val['inputProductName'];
				}
				$trstyle="";
				if($val['isBack']){
					$trstyle="style='color:red'";//��غ�ɫ��ʾ
				}

				$str .= <<<EOT
					<tr class="$iClass" $trstyle>
						<td>$i<input type="hidden" name="apply[equ][$i][id]" value="$equId"/></td>
						<td>$val[productCategoryName]</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[amountAll]</td>
						<td>$val[amountIssued]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[checkDate]</td>
						<td>
							<div class='divChangeLine'>$val[remark]</div>
						</td>
						<td>$isBackCheckbox</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>���������嵥��Ϣ</td></tr>";
		}
		return $str;


	}

	/**
	 * ������뵥��������
	 */
	function backBasicToApplyUser_d($object){
		try {
			$this->start_d ();
			$applyId=$object['id'];
			$backReason=$object['backReason'];
			$sql="update oa_asset_purchase_apply set ExaStatus='����ȷ�ϴ��',backReasion='$backReasion' where id=$applyId";//����Ϊ���״̬
			$this->query($sql);
			$row=$this->get_d($object['id']);
			$equDao=new model_asset_purchase_apply_applyItem ();
			//var_dump($object['equ']);
			foreach($object['equ'] as $key=>$val){
				$equDao->edit_d($val);
			}

			$equRow= $equDao->getItemByApplyId ( $object['id'] );
			//�����ʼ�
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['sendUserId'];
			$emailArr['TO_NAME']=$row['sendName'];
			if(is_array($equRow )){
				$j=0;
				$addmsg.="�ɹ����뵥��ţ�<font color='red'>".$object['planNumb']."</font><br>";
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>�������</b></td><td><b>��������</b></td><td><b>��������</b></td><td><b>��������</b></td><td><b>��������</b></td><td><b>ϣ����������</b></td><td><b>��ע</b></td></tr>";
				foreach($equRow as $key => $equ ){
					$j++;
					$productCategoryName=$equ['productCategoryName'];
					$inputProductName=$equ ['inputProductName'];
					$productName=$equ ['productName'];
					$amountAll=$equ ['amountAll'];
					$dateIssued=$equ['dateIssued'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark'];
					$addmsg .=<<<EOT
					<tr align="center" >
								<td>$j</td>
								<td>$productCategoryName</td>
								<td>$inputProductName</td>
								<td>$productName</td>
								<td>$amountAll</td>
								<td>$dateIssued</td>
								<td>$dateHope</td>
								<td>$remark</td>
						</tr>
EOT;
					}
					$addmsg.="</table><br/>";
					$addmsg.="���ԭ��<br/>     ";
					$addmsg.="<font color='blue'>".$backReason."</font>";
		}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanBack','','',$emailArr['TO_ID'],$addmsg,1);
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	 /**
	 * ����ȷ�ϲɹ����������б�
	 *
	 * @param	array	��ʾ��������
	 * @return	string	��ʾHTML�ַ���
	 */
	 function showConfirmEdit_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				if($val['isBack']==0){
					$i ++;
					$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
					$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>
						$val[productCategoryName]
						<td>
						$val[inputProductName]
						</td>
						<td>
						<input type="hidden"  name="apply[$i][id]" value="$val[id]"/>
						<input type="hidden"  name="apply[$i][applyId]" value="$val[applyId]"/>
						<input type="text" class="txtshort" id="productNumb$i" name="apply[$i][productNumb]" value="$val[productCode]"/>
						<input type="hidden" id="productId$i" name="apply[$i][productId]" value="$val[productId]"/> </td>
						<td>
						<input type="text" class="txt" id="productName$i" name="apply[$i][productName]" value="$val[productName]"/> </td>
						<script>
							processProductCmp($i);
						</script>

						<td>
						<input type="text" class="readOnlyTxtItem" id="pattem$i" name="apply[$i][pattem]" value="$val[pattem]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="unitName$i" name="apply[$i][unitName]" value="$val[unitName]"/></td>
						<td>
						$val[applyAmount]</td>
						<td>
						$val[dateHope]</td>
						<td>
							$val[remark]
						</td>

					</tr>
EOT;
				}
			}
		}else {
			$str = "<tr><td colspan='8'>���������嵥��Ϣ</td></tr>";
		}
		return $str;
	}
	/**
	 * �ɹ�����ͨ����ʱ�������ʼ�����֪ͨ
	 *@param $id �ɹ�����Id
	 */
	 function auditSendEmail_d($id){
		$applyItemDao=new model_asset_purchase_apply_applyItem();
		$itemRow=$applyItemDao->getItem_d($id);
		if(is_array($itemRow)){
			//�����ʼ�֪ͨ�ʲ��ɹ�������
			$mailRow=$this->aduitEmail;
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$mailRow['sendUserId'];
			$emailArr['TO_NAME']=$mailRow['sendName'];
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>��������</b></td><td><b>ϣ������ʱ��</b></td><td><b>��ע</b></td></tr>";
				foreach($itemRow as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$applyAmount=$equ ['applyAmount'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$applyAmount</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->pushPurch($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'asset_pushPurch',',�ɹ����󵥾ݺ�Ϊ��<font color=red><b>'.$equ["applyCode"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
		}
	 }


	/**
	 * �鿴���ҵ����Ϣ
	 * @param $id
	 * @param $skey
	 * @return string
	 */
	function viewRelInfo( $id,$skey ) {
		return '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=asset_require_requirement&action=toView&id=' . $id .'&perm=view&skey='.$skey.'\',1)">';
	}

	/************************* ���ݳ��� *********************/
	/**
	 * �жϵ����Ƿ�ɳ���
	 */
	function canBackForm_d($id){
		$sql = "select sum(applyAmount) as applyAmount,sum(applyAmount - if(issuedAmount is null or issuedAmount = '',0,issuedAmount)) as canBack from oa_asset_purchase_apply_item where applyId = '$id' group by applyId";
		$rs = $this->_db->getArray($sql);
		//����������,��ȫ��
		if($rs[0]['canBack'] == $rs[0]['applyAmount']){
			return 1;
		}elseif($rs[0]['canBack'] == 0){ //����ɳ�������Ϊ0������0
			return 0;
		}else{ //���ֳ���
			return 2;
		}
	}

	/**
	 * ���ز���
	 */
	function backForm_d($id){
		try{
			$this->start_d();
			//������������Ϣ
			$this->update(array('id' => $id),array('state' => '�ѳ���','ifShow'=>'1'));

			//���´ӱ���������
			$applyItemDao = new model_asset_purchase_apply_applyItem();
			//��ȡ���ص�����id������������
			$applyItemInfo = $applyItemDao->findAll(array('applyId' => $id),null,'productId,applyAmount');
			$applyItemDao->update(array('applyId' => $id),array('applyAmount' => 0));
			
			//��ȡ�ʲ���������id
			$rs = $this->find(array('id' => $id),null,'relDocId');
			$requirementId = $rs['relDocId'];
			//ʵ�����ʲ�����������ϸ
			$requireitemDao = new model_asset_require_requireitem();
			//��ȡ�ʲ�����������ϸ
			$requireitemInfo = $requireitemDao->findAll(array('mainId' => $requirementId),null,'productId,purchDept,purchAmount');
			foreach ($requireitemInfo as $key => $val){
				$purchAmount = 0;
				$purchDept = $val['purchDept'];
				foreach ($applyItemInfo as $k => $v){
					if($val['productId'] == $v['productId']){
						//�������� = ԭ������������-���ص���������
						$purchAmount = $val['purchAmount'] - $v['applyAmount'];
					}
				}
				if($purchAmount == 0){
					$purchDept = '';
				}
				//�����ʲ���������ӱ���Ϣ
				$requireitemDao->update(array('mainId'=>$requirementId,'productId'=>$val['productId']),array('purchDept'=>$purchDept,'purchAmount'=>$purchAmount));
			}
			//�����ʲ���������������Ϣ
			$requirementDao = new model_asset_require_requirement();
			$requirementDao->updateRecognize($requirementId);

			$this->commit_d();
			return true;
		}catch(exception $e){
			throw $e;
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ز���
	 */
	function backDetail_d($object){
		try{
			$this->start_d();

			//ʵ������ϸ
			$applyItemDao = new model_asset_purchase_apply_applyItem();
			$allBackNum = 0;
			$applyNum = 0;
			$canBackNum = 0;
			$newApplyNum = 0;
			//��ȡ�ʲ���������id
			$rs = $this->find(array('id' => $object['id']),null,'relDocId');
			$requirementId = $rs['relDocId'];
			//ʵ�����ʲ�����������ϸ
			$requireitemDao = new model_asset_require_requireitem();
			//��ȡ�ʲ�����������ϸ
			$requireitemInfo = $requireitemDao->findAll(array('mainId'=>$requirementId),null,'productId,purchDept,purchAmount');
			foreach ($requireitemInfo as $key => $val){
				$purchAmount = 0;
				$purchDept = $val['purchDept'];
				foreach($object['applyItem'] as $k => $v){
					//ѭ������-�����жϲ����Ƿ�����ȫ������
					$allBackNum = bcadd($allBackNum ,$v['backAmount']);
					$applyNum = bcadd($applyNum ,$v['applyAmount']);
					
					$canBackNum = $v['applyAmount'] - $v['issuedAmount'];
					if($v['backAmount'] <= $canBackNum){
						//����ʣ������
						$newApplyNum = $v['applyAmount'] - $v['backAmount'];
						$applyItemDao->update(array('id' => $v['id']),array('applyAmount' => $newApplyNum));
						$allNewNum += $newApplyNum;
					}
					if($val['productId'] == $v['productId']){
						//�������� = ԭ������������-���ص���������
						$purchAmount = $val['purchAmount'] - $v['backAmount'];
					}
				}
				if($purchAmount == 0){
					$purchDept = '';
				}
				//�����ʲ���������ӱ���Ϣ
				$requireitemDao->update(array('mainId'=>$requirementId,'productId'=>$val['productId']),array('purchDept'=>$purchDept,'purchAmount'=>$purchAmount));
			}
			//�������ȫ�����أ������״̬
			if($allBackNum == $applyNum){
				$this->update(array('id' => $object['id']),array('state' => '�ѳ���','backReason'=>$object['backReason'],'ifShow'=>'1'));
			}
			//�����ʲ���������������Ϣ
			$requirementDao = new model_asset_require_requirement();
			$requirementDao->updateRecognize($requirementId);
			
			$this->commit_d();
			return true;
		}catch(exception $e){
			throw $e;
			$this->rollBack();
			return false;
		}
	}

	//***** ��֤�ܷ�����´�ɹ�����
	function checkComplate_d($id){

	}


	/**
	 * �ύ������ʼ�֪ͨ
	 */
	 function sendMailAtAdd($mainObj){
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_asset_apply_requireAdd']['sendUserId'];
//	 	$outmailStr = implode(',',$outmailArr);
		$addMsg = $this->sendMesAsAdd($mainObj);
//		echo "<pre>";
//		print_R($mailId);
//		echo $addMsg;
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '����','�ʲ��ɹ�����'.$mainObj['formCode'], $mailId, $addMsg, '1');
	 }
	/**
	 * �ʼ��и���������Ϣ
	 */
	 function sendMesAsAdd($object){
		if(is_array($object ['applyItem'])){
			$j=0;
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>��������</td><td>���</td><td>��������</td><td>��λ</td><td>Ԥ�ƽ��</td><td>ѯ�۽��</td><td>ϣ����������</td><td>��ע</td><td>�������</td></tr>";
			foreach($object ['applyItem'] as $key => $equ ){
				$j++;
				$inputProductName=$equ['inputProductName'];
				$pattem=$equ['pattem'];
				$applyAmount=$equ ['applyAmount'];
				$unitName=$equ ['unitName'];
				$amounts=$equ ['amounts'];
				$inquiryAmount=$equ ['inquiryAmount'];
				$dateHope=$equ ['dateHope'];
				$remark=$equ ['remark'];
				$suggestion=$equ ['suggestion'];
				$addmsg .=<<<EOT
					<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$inputProductName</td><td>$pattem</td><td>$applyAmount</td><td>$unitName</td><td>$amounts</td><td>$inquiryAmount</td><td>$dateHope</td><td>$remark</td><td>$suggestion</td></tr>
EOT;
					}
		}
		return $addmsg;
	 }

	 /**
	 *�����ɹ�������������϶����´������򵥾ݸ�Ϊ�ر�״̬
	 *
	 */
	 function updatePurchState_d($id){
		//��ȡ��������
		$equipment = new model_asset_purchase_apply_applyItem ();
		$rows = $equipment->getPurchItem_d ( $id );
		if(is_array($rows)){
			$flag=false;
			//����ÿһ���ɹ����������δ�´�����֮��
			$waitNumSum=0;
			foreach($rows as $equKey=>$equVal){
				$waitNumSum=$waitNumSum+($equVal['applyAmount']-$equVal['issuedAmount']);
				if($waitNumSum>0){
					$flag=false;
					break;
				}else{
					$flag=true;
				}
			}
			if($flag){
				$obj = array ('id' => $id, 'purchState' => '3',  'dateEnd' => date ( 'Y-m-d' ),'updateTime'=>date('Y-m-d H:i:s'));
				return parent::updateById ( $obj );
			}
		}
	 }
	/**
	 * @exclude �رղɹ�����
	 */
	function dealClose_d($object) {
		$equDao = new model_asset_purchase_apply_applyItem ();
		$obj = array ('id' => $object['id'],'purchCloseRemark'=>$object['purchCloseRemark'], 'purchState' => '3', 'dateEnd' => date ( 'Y-m-d' ),'updateTime'=>date('Y-m-d H:i:s'));
		$id=parent::updateById ( $obj );
		$equRow= $equDao->getPurchItem_d ( $object['id'] );
		//�����ʼ�
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$object['applicantId'];
		$emailArr['TO_NAME']=$object['applicantName'];
		if(is_array($equRow )){
			$j=0;
			$addmsg.="�ɹ����뵥��ţ�<font color='red'>".$object['formCode']."</font><br>";
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>ȷ����������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>��������</b></td><td><b>�´���������</b></td><td><b>ϣ����������</b></td><td><b>��ע</b></td></tr>";
			foreach($equRow as $key => $equ ){
				$j++;
				$productCategoryName=$equ['productCategoryName'];
				$productName=$equ['productName'];
				$pattem=$equ ['pattem'];
				$unitName=$equ ['unitName'];
				$amountAll=$equ ['applyAmount'];
				if($equ ['issuedAmount']==''){
					$amountIssued=0;
				}else{
					$amountIssued=$equ ['issuedAmount'];
				}
				$dateIssued=$equ['dateIssued'];
				$dateHope=$equ['dateHope'];
				$remark=$equ['remark'];
				$addmsg .=<<<EOT
				<tr align="center" >
							<td>$j</td>
							<td>$productCategoryName</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$amountIssued</td>
							<td>$dateHope</td>
							<td>$remark</td>
					</tr>
EOT;
					}
					$addmsg.="</table><br/>";
				$addmsg.="�ر�ԭ��<br/>     ";
				$addmsg.="<font color='blue'>".$object['purchCloseRemark']."</font>";
		}
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanClose','','',$emailArr['TO_ID'],$addmsg,1);
		return $id;
	}

			/**
	 * @exclude ���������ɹ�����
	 * @param	$id �ɹ�����ID
	 */
	function startApply_d($id){
		$condition = array(
					"id" => $id
				);
		$obj = array(
					"purchState" =>'0'
				);
		return parent::update ( $condition, $obj );
	}

		/**
	 * ȷ������ȷ�Ϸ�����
	 */
	function confirmProductUser_d($object){
		$equDao=new model_asset_purchase_apply_applyItem ();
		$id=$object['id'];
		$productSureUserId=$object['productSureUserId'];
		$productSureUserName=$object['productSureUserName'];
		$sql="update oa_asset_purchase_apply set productSureUserId='$productSureUserId'," .
				"productSureUserName='$productSureUserName' where id=$id";
		$this->query($sql);
		$row=$this->get_d($object['id']);
		$equRow= $equDao->getPurchItem_d ( $object['id'] );
		//�����ʼ�
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$object['productSureUserId'];
		$emailArr['TO_NAME']=$object['productSureUserName'];
		if(is_array($equRow )){
			$j=0;
			$addmsg.="�ɹ����뵥��ţ�<font color='red'>".$row['formCode']."</font><br>";
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>�������</b></td><td><b>��ȷ������</b></td><td><b>��������</b></td><td><b>��������</b></td><td><b>ϣ����������</b></td><td><b>������</b></td><td><b>��ע</b></td></tr>";
			foreach($equRow as $key => $equ ){
				$j++;
				$productCategoryName=$equ['productCategoryName'];
				$inputProductName=$equ ['inputProductName'];
				$productName=$equ ['productName'];
				$amountAll=$equ ['applyAmount'];
				$dateHope=$equ['dateHope'];
				$remark=$equ['remark'];
				$sendName=$row['applicantName'];
				$addmsg .=<<<EOT
				<tr align="center" >
							<td>$j</td>
							<td>$productCategoryName</td>
							<td>$inputProductName</td>
							<td>$productName</td>
							<td>$amountAll</td>
							<td>$dateHope</td>
							<td>$sendName</td>
							<td>$remark</td>
					</tr>
EOT;
					}
					$addmsg.="</table><br/>";
		}
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanTask','','',$emailArr['TO_ID'],$addmsg,1);
		return true;
	}
	//��ȡ�ɹ������Ӧ���ʲ���������id
	function getRequirementId($id){
		$sql = "
			SELECT
				relDocId
			FROM ".$this->tbl_name."
			WHERE
				id = '".$id."'";
		$rs = $this->_db->get_one($sql);
	
		return $rs['relDocId'];
	}
	//���²ɹ�״̬
	function updatePurchState($id,$purchStateVal){
		$object=array("id"=>$id,"purchState"=>$purchStateVal);
		$this->updateById($object);
	}
	//��ȡĳ���ɹ�������״̬Ϊ���ɹ��С��ļ�¼��
	function countPurch($relDocId){
		$sql = "
			SELECT
				COUNT(*) AS purchAmount
			FROM
				".$this->tbl_name."
			WHERE
				relDocId = '".$relDocId."'
			AND purchState = 0 
			AND ifShow = 0 ";
		$rs = $this->_db->get_one($sql);

		return $rs['purchAmount'];
	}
}