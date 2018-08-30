<?php
/*
 * Created on 2010-12-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_purchase_inquiry_inquirysheet extends model_base{

 	private $state;     //״̬λ
 	public $statusDao;  //״̬��

	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_purch_inquiry";
		$this->sql_map = "purchase/inquiry/inquirysheetSql.php";
		$this->mailArr=$mailUser['oa_purchase_speed'];
		parent :: __construct();

		$this->state=array(
		    0=>array(
		       "stateEName"=>"save",
		       "stateCName"=>"����",
		       "stateVal"=>"0"
		    ),
		    1=>array(
		       "stateEName"=>"wait",
		       "stateCName"=>"��ָ��",
		       "stateVal"=>"1"
		    ),
		    2=>array(
		       "stateEName"=>"assign",
		       "stateCName"=>"��ָ��",
		       "stateVal"=>"2"
		    ),
		    3=>array(
		       "stateEName"=>"close",
		       "stateCName"=>"�ѹر�",
		       "stateVal"=>"3"
		    ),
		   4=>array(
		       "stateEName"=>"done",
		       "stateCName"=>"�����ɶ���",
		       "stateVal"=>"4"
		    ),
		   5=>array(
		       "stateEName"=>"backtask",
		       "stateCName"=>"���˻�����",
		       "stateVal"=>"5"
		    )
		);

		$this->statusDao=new model_common_status();
		$this->statusDao->status=array(
		    0=>array(
		       "statusEName"=>"save",
		       "statusCName"=>"����",
		       "key"=>"0"
		    ),
		    1=>array(
		       "statusEName"=>"wait",
		       "statusCName"=>"��ָ��",
		       "key"=>"1"
		    ),
		    2=>array(
		       "statusEName"=>"assign",
		       "statusCName"=>"��ָ��",
		       "key"=>"2"
		    ),
		    3=>array(
		       "statusEName"=>"close",
		       "statusCName"=>"�ѹر�",
		       "key"=>"3"
		    ),
		    4=>array(
		       "statusEName"=>"done",
		       "statusCName"=>"�����ɶ���",
		       "key"=>"4"
		    ),
		    5=>array(
		       "statusEName"=>"backtask",
		       "statusCName"=>"���˻�����",
		       "key"=>"5"
		    )
		);

		//���ó�ʼ�����������
		parent::setObjAss();
	}
/*****************************************ҳ��ģ����ʾ��ʼ********************************************/
	/**
	 * �鿴ѯ�۵��Ĺ�Ӧ�̵ı�����ϸ.
	 *
	 * @param $suppRows ��Ӧ�̱�����������
	 * @param $inuqiryEqu ѯ�۵���������
	 */
	function showSupp_s ( $suppRows ,$inuqiryEqu ) {
		$orderEquDao=new model_purchase_contract_equipment();
		$str = '';
		if($inuqiryEqu){
			$orderDateTime=$this->get_table_fields($this->tbl_name,'id='.$inuqiryEqu['0']['parentId'],'createTime');//ѯ������
		foreach( $inuqiryEqu as $key => $val ){
			$rows=$orderEquDao->getHistoryInfo_d( $val['productNumb'],$orderDateTime);//��ȡ������ʷ�۸�

			$str.= "<tr><td>";
			$str.=  $val['productNumb']."<br>";
			$str.= $inuqiryEqu[$key]['name'] = $val['productName'];
			$str.= "</td>";
			foreach( $suppRows as $suppKey => $suppVal ){
				//�жϹ�Ӧ���Ƿ��б��ۣ����û�б�������Ϊ�����ޱ��ۡ�
				if(is_array($suppVal['child'])){
					foreach( $suppVal['child'] as $equKey => $equVal ){
						if( $val['productName'] == $equVal['productName']&&$val['id']==$equVal['inquiryEquId']&& $equVal['parentId']==$suppVal['id'] ){
							/*if($equVal[tax]>0){

							}else{
								$tax="";
							}*/
							$tax="<<span class='formatMoney'>$equVal[taxRate]</span>%>";
							$str.= <<<EOT
							 <td>
							<font color='blue' class='formatMoneySix'>$equVal[price]</font>$tax
							</td>
EOT;
						}
					}
				}else{
							$str.= "<td>���ޱ���</td>";
				}
			}
			if(is_array($rows)){
				$str.= <<<EOT
				 <td>
					<font color='blue' class='formatMoneySix'>$rows[applyPrice]</font><<span class='formatMoney'>$rows[taxRate]</span>%>
				</td>
				 <td class='form_text_right'>
					��Ӧ�̣�<b>$rows[suppName]</b><br/>
					����������<b>$rows[paymentConditionName]  $rows[payRatio]</b><br/>
					�������ڣ�<b>$rows[orderTime]</b><br/>
					��  ����<b>$rows[amountAll]</b><br/>
				</td>
EOT;
			}else{
				$str.= <<<EOT
				 <td>
					����ʷ�۸�
				</td>
				 <td>
				</td>
EOT;
			}
			$str.= <<<EOT
				<td class='form_text_right'>
					���µ�������<b>$val[amount]</b><br/>
					$val[referPrice]
					</b><br/>
				</td>
EOT;

		}
			$str.= "</tr>";
		}else{
			$str="<tr align='center'><td colspan='50'>������Ӧ��Ϣ</td></tr>";
		}
		return $str;
	}
/*****************************************ҳ��ģ����ʾ����********************************************/

/*****************************************ҵ����ʼ********************************************/

	/**�ɹ�ѯ�۵���ӷ���
	*author can
	*2010-12-28
	* @param $object ѯ�۵�����
	*/
	function add_d ($object) {

		try{
			$this->start_d();
			$object['state']=$this->statusDao->statusEtoK('save');
			$object['ExaStatus']="δ�ύ";
			$object['objCode']=$this->objass->codeC("purch-inquirysheet");
			$codeDao=new model_common_codeRule();
			$object['inquiryCode']=$codeDao->purchaseCode("oa_purch_inquiry");
			$object['isUse']="0";   //��ʼֵΪ0����ѯ�۵���û���ɲɹ���ͬ�����Ϊ1������Ѹ��ݲɹ�ѯ�۵����ɲɹ���ͬ
			$id=parent::add_d($object,true);
            $equmentInquiryDao=new model_purchase_inquiry_equmentInquiry();

			//�����Ʒ�嵥
			if(is_array($object['equmentInquiry'])){   //��������Ϣ�����жϣ�������������򱣴棬�����׳��쳣
				foreach($object['equmentInquiry'] as $val){
					if(!empty($val['productName'])){
						$val['parentId']=$id;
						$equId=$equmentInquiryDao->add_d($val);

						//�����ܹ��������ݣ���������豸�嵥idȥ���ܹ��������Ҫ���µ�����Ϊ�գ������update�����������Ʋ�Ϊ�յ����ݽ�����������
						$this->objass->saveModelObjs("purch",array("taskEquId"=>$val['taskEquId']),array("inquiryCode"=>$object['inquiryCode'],"inquiryId"=>$id,"inquiryEquId"=>$equId));
						//���²ɹ������豸�����´�/��������
						$taskEquDao=new model_purchase_task_equipment();
						$taskEquDao->updateAmountIssued($val['taskEquId'],$val['amount']);
					}
				}
			}else{
				throw new Exception( '��������Ϣ' );
			}

			//���¸���������ϵ
			$this->updateObjWithFile($id,$object['inquiryCode']);



            //��������
			if(isset($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])){
				$uploadFile = new model_file_uploadfile_management();
				$uploadFile->updateFileAndObj($_POST['fileuploadIds'],$id,$object['inquiryCode']);
			}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
//			throw $e;
			return null;
		}

	}

	/**��ӹ�Ӧ��
	*author can
	*2010-12-29
	 * @param $supp ��Ӧ������
	*/
    function addSupp_d($supp){
    	$arr['parentId']=$supp['parentId'];
    	$arr['suppId']=$supp['supplierId'];
    	if (util_jsonUtil::is_utf8 ( $supp['supplierName'] )) {
			$arr['suppName'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierName'] );
		}else{
    		$arr['suppName']=$supp['supplierName'];
    	}
    	if (util_jsonUtil::is_utf8 ( $supp['supplierPro'] )) {
			$arr['suppTel'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierPro'] );
		}else{
    		$arr['suppTel']=$supp['supplierPro'];
    	}
    	$supplierDao=new model_purchase_inquiry_inquirysupp();
    	$suppId=$supplierDao->add_d($arr);
    	return $suppId;

    }

    /**�����ѡ��Ӧ�̣������±���
	*author can
	*2011-1-3
	*/
	function suppAdd_d($supp){
		$id=$supp['suppIds'];
		$arr['parentId']=$supp['parentId'];
    	$arr['suppId']=$supp['supplierId'];
    	if (util_jsonUtil::is_utf8 ( $supp['supplierName'] )) {
			$arr['suppName'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierName'] );
		}else{
    		$arr['suppName']=$supp['supplierName'];
    	}
    	if (util_jsonUtil::is_utf8 ( $supp['supplierPro'] )) {
			$arr['suppTel'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierPro'] );
		}else{
    		$arr['suppTel']=$supp['supplierPro'];
    	}
    	$condiction=array('id'=>$id);
    	$supplierDao=new model_purchase_inquiry_inquirysupp();
		$supplierDao->updateField($condiction,'suppName',$arr['suppName']);
		$supplierDao->updateField($condiction,'suppId',$arr['suppId']);
		$supplierDao->updateField($condiction,'parentId',$arr['parentId']);
		$supplierDao->updateField($condiction,'suppTel',$arr['suppTel']);
    	return $id;
	}
    /**ɾ��ѯ�۵�
	*author can
	*2010-12-29
	 * @param $id ѯ�۵�ID
	*/
	function deletesInfo_d($id) {
		try {
			$this->start_d();
			//��ȥ�����ɹ������豸�嵥������������

			$equDao=new model_purchase_inquiry_equmentInquiry();
			$equDao->del_d($id);

			$this->deletes ($id);
			$this->commit_d();
			return true;
		}catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}
    /**�˻�ѯ�۵�
	*author can
	*2010-12-29
	 * @param $id ѯ�۵�ID
	*/
	function backToTask_d($id) {
		try {
			$this->start_d();
			//��ȥ�����ɹ������豸�嵥������������

			$equDao=new model_purchase_inquiry_equmentInquiry();
			$equDao->del_d($id);

			$object['id']=$id;
			$object['state']=5;
			$this->edit_d ($object);
			$this->commit_d();
			return true;
		}catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}

	/**��ȡ�޸ĵĶ���
	*author can
	*2010-12-30
	 * @param $id ѯ�۵�ID
	*/
	function get_d($id ,$perm = null){
		$inquiry=parent::get_d($id);

		//��ȡѯ�۲�Ʒ
		$inquiryProDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryProDao->getUniqueByParentId($id);
		$equs=$inquiryProDao->getEqusByParentId($id);
		if(is_array($equs)){
			$equs=$inquiryProDao->getPurchName($equs);
		}
		$inquiry['equs']=$equs;
		if(!empty($perm)){
			$arr=$inquiryProDao->rowsShow($equs,$uniqueEquRows);
		}else{
			$arr=$inquiryProDao->rowsEdit($equs);
		}
		$inquiry['productLidt']=$arr;

		//��ȡָ����Ӧ����Ϣ
		if($inquiry['suppId']){
			$flibraryDao=new model_supplierManage_formal_flibrary();
			$supplier=$flibraryDao->get_d($inquiry['suppId']);
			$inquiry['supplier']=$supplier;
		}
		return $inquiry;
	}

	/**�޸�ѯ�۵�
	*author can
	*2010-12-30
	*$object ǰ̨���͹���������
	*/
	function edit_d($object){
		try{
			$this->start_d();
			$inquiryProDao=new model_purchase_inquiry_equmentInquiry();
			$inquirySupDao=new model_purchase_inquiry_inquirysupp();
			$inquirySupproDao=new model_purchase_inquiry_inquirysupppro();

			$suppROws=$inquirySupDao->getSuppByParentId($object['id']);

			$taskEquDao=new model_purchase_task_equipment();
			$equmentRows=$object['equmentInquiry'];
			unset($object['equmentInquiry']);
			//�޸����������
			$newId=parent::edit_d($object,true);
			//ɾ���ӱ�����ݣ������������
//			$deleteCondition=array('parentId'=>$object['id']);
//			$inquiryProDao->delete($deleteCondition);
			if($equmentRows){
				foreach($equmentRows as $val){
					if(!empty($val['productName'])){
						$taskEquDao->updateAmountIssued($val['taskEquId'],$val['amount'],$val['amountOld']);
						$val['parentId']=$object['id'];
						$equId=$inquiryProDao->edit_d($val);

						//�����ܹ��������ݣ���������豸�嵥idȥ���ܹ��������Ҫ���µ�����Ϊ�գ������update�����������Ʋ�Ϊ�յ����ݽ�����������
//						$this->objass->saveModelObjs("purch",array("taskEquId"=>$val['taskEquId']),array("inquiryCode"=>$object['inquiryCode'],"inquiryId"=>$object['id'],"inquiryEquId"=>$equId));

						//���ݹ�Ӧ�̱���ID����ȡ����ص�������Ϣ�������¹�Ӧ��������Ϣ�е�ѯ������ID
//						foreach($suppROws as $supKey=>$supVal){
//							$supproRows=$inquirySupproDao->getProByParentId($supVal['id']);
//							foreach($supproRows as $proKsy=>$proVal){
//								if($val['productName']==$proVal['productName']&&$val['taskEquId']==$proVal['takeEquId']){
//									$condiction=array('id'=>$proVal['id']);
//									$inquirySupproDao->updateField($condiction,'inquiryEquId',$equId);
//								}
//							}
//						}
					}
				}
			}

			$this->commit_d();

            return $object['id'];
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}
	}

	/**ָ����Ӧ��
	*author can
	*2011-1-3
	*/
	function assignSupp_d($objcet){
		try{
			$this->start_d();
			$condiction=array('id'=>$objcet['id']);
			parent::edit_d($objcet);
			$this->updateField($condiction,'state','2');
			$this->commit_d();
			return true;
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}

	}

	/**
	 * �ɹ�ѯ�۵�����ͨ���󣬷����ʼ�
	 *@param $id �ɹ�ѯ�۵�ID
	 */
	 function sendEmail_d($id){
		try{
			$this->start_d();
			//��ȡ�ɹ�ѯ�۵���Ϣ��������Ϣ
			$rows=$this->get_d($id);

			$emailArr=array();
			$sendIdArr=array();
			$sendNameArr=array();
			$emailArr['issend'] = 'y';

			$emailArr['TO_ID']=$rows['purcherId'];
			if($emailArr['TO_ID']){
				$addmsg="";
				if(is_array($rows['equs'])){
					$planDao=new model_purchase_plan_basic();
					$interfObj = new model_common_interface_obj();
//					$orderContractDao = new model_projectmanagent_order_order();
					$j=0;
					//��������ϸ��Ϣ
					$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>ѯ������</b></td><td><b>Ԥ�Ƶ���ʱ��</b></td><td><b>�ɹ�����</b></td><td><b>������</b></td><td><b>������</b></td><td><b>Դ��(��ͬ)���</b></td><td><b>�ͻ�����</b></td><td><b>������</b></td></tr>";
					foreach($rows['equs'] as $key => $equ ){
						$planNumb="";
						$applyUser="";
						$dateHope="";
						$sourceNumb="";
						$customer="";
						//��ȡ�ɹ�����ID
						$planId=$this->get_table_fields('oa_purch_task_equ','id='.$equ ['taskEquId'],'planId');
						if($planId){//��ȡ�ɹ�������Ϣ
							$planRow=$planDao->get_d($planId);
							$planNumb=$planRow['planNumb'];
							$applyUser=$planRow['sendName'];
							$sourceNumb=$planRow['sourceNumb'];
							array_push($sendIdArr,$planRow['sendUserId']);
							array_push($sendNameArr,$planRow['sendName']);
							if($equ['purchType']=="oa_sale_order"||$equ['purchType']=="oa_sale_lease"||$equ['purchType']=="oa_sale_service"||$equ['purchType']=="oa_sale_rdproject"){
								$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //��ȡ�ɹ����Ͷ�������
								$supDao = new $supDaoName();	//ʵ��������
								$sourceRow=$orderContractDao->getCusinfoByorder($equ['purchType'],$planRow['sourceID']);
								$customer=$sourceRow['customerName'];
							}else if($equ['purchType']=="oa_present_present"||$equ['purchType']=="oa_borrow_borrow"){
								$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //��ȡ�ɹ����Ͷ�������
								$supDao = new $supDaoName();	//ʵ��������
								$sourceRow=$supDao->getInfoList($planRow['sourceID']);
								$customer=$sourceRow['customerName'];
							}
							if($equ['purchType']=="HTLX-XSHT"||$equ['purchType']=="HTLX-ZLHT"||$equ['purchType']=="HTLX-FWHT"||$equ['purchType']=="HTLX-YFHT"){
								$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //��ȡ�ɹ����Ͷ�������
								$supDao = new $supDaoName();	//ʵ��������
								$sourceRow=$supDao->getInfoList($planRow['sourceID']);
								$customer=$sourceRow['customerName'];
							}
						}
						//��ȡ���۹�Ӧ��ID
						$iqnuirySuppId=$this->get_table_fields('oa_purch_inquiry_supp','parentId='.$id.' and suppId='.$rows['suppId'],'id');
						if($iqnuirySuppId){
							//��ȡ���۹�Ӧ��ID
							$dateHope=$this->get_table_fields('oa_purch_inquiry_suppequ','parentId='.$iqnuirySuppId.' and inquiryEquId='.$equ['id'],'deliveryDate');

						}
						$j++;
						$productName=$equ['productName'];
						$pattem=$equ ['pattem'];
						$unitName=$equ ['units'];
						$amountAll=$equ ['amount'];
						$purchTypeCn=$equ['purchTypeCn'];
						$applyNumb=$planNumb;
						$applyer=$applyUser;
						$deliveryDate=$dateHope;
						$purcher=$rows['purcherName'];
						$addmsg .=<<<EOT
						<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$pattem</td>
									<td>$unitName</td>
									<td>$amountAll</td>
									<td>$deliveryDate</td>
									<td>$purchTypeCn</td>
									<td>$purcher</td>
									<td>$applyNumb</td>
									<td>$sourceNumb</td>
									<td>$customer</td>
									<td>$applyer</td>
								</tr>
EOT;
						}
						$addmsg.="</table>";
				}
				$mailArr=$this->mailArr;
				$sendIds=implode(',',array_unique($sendIdArr)).",".$mailArr['sendUserId'];
				$emailDao = new model_common_mail();
//				$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'�ɹ�ѯ�۵�������ͨ��,ѯ�۵����Ϊ��<font color=red><b>'.$rows['inquiryCode'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
				$emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchSpeed','���ʼ�Ϊ�ɹ�������Ϣ����','',$sendIds,$addmsg,1);

			}

			$this->commit_d();
			return true;
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}

	 }

	/**
	 * ��ȡ�ɹ�ѯ�۵����豸�嵥
	 * @param $inquiryId ѯ�۵�ID
	 */
	function getInquirysheetWithEqus($inquiryId){
		$inquiry=$this->get_d($inquiryId);

	}

	/**
	 * @desription ��Ӳɹ���ͬʱ����½ӿ�
	 * @param tags     by qian
	 * @date 2011-1-12 ����02:49:11
	 */
	function isAddPurchOrder ( $inquiryId,$state) {
		//����ѯ�۵�״̬
		$condiction = array('id'=>$inquiryId);
		$this->updateField($condiction,'state',$state);
	}

	//�ж���������Ӧ�������Ƿ����´ﶩ��
	function isEndInquiry_d($id){
		$inquiryProDao=new model_purchase_inquiry_equmentInquiry();
		$equs=$inquiryProDao->getEqusByParentId($id);
		if(is_array($equs)){
			$flag=1;
			foreach($equs as $key=>$val){
				$contractAmount=$this->get_table_fields('oa_purch_task_equ','id='.$val ['taskEquId'],'contractAmount');
				if($val['amount']!=$contractAmount){
					$flag=0;
					break;
				}
			}
			return $flag;
		}else{
			return 0;
		}

	}

	/**
	 * �ύѯ�۵������������ʼ�֪ͨ������
	 *$id �ɹ�ѯ�۵�ID
	 */
	 function sendEmailByAduit_d($id){
		$taskEquDao=new model_purchase_task_equipment();
		$planDao=new model_purchase_plan_basic();
		//��ȡ�ɹ�ѯ������
		$rows=$this->get_d($id);
		print_r($rows);
		$addmsg="";
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$rows['purcherId'];
		$emailArr['TO_NAME']=$rows['purcherName'];
		if(is_array($rows['equs'])){
			$j=0;
			//��������ϸ��Ϣ
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>��λ</b></td><td><b>ѯ������</b></td><td><b>�ɹ�����</b></td></tr>";
			foreach($rows['equs'] as $key => $equ ){
				//��ȡ�ɹ�����ID
				$planId=$this->get_table_fields();
				$j++;
				$productName=$equ['productName'];
				$pattem=$equ ['pattem'];
				$unitName=$equ ['units'];
				$amountAll=$equ ['amount'];
				$purchTypeCn=$equ['purchTypeCn'];
				$addmsg .=<<<EOT
				<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$pattem</td>
									<td>$unitName</td>
									<td>$amountAll</td>
									<td>$purchTypeCn</td>
								</tr>
EOT;
						}
						$addmsg.="</table>";
		}
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'���ʼ���'.$_SESSION['USERNAME'].'���͡�<br>�ɹ�ѯ�۵����ύ����,ѯ�۵����Ϊ��<font color=red><b>'.$rows['inquiryCode'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);


	 }
/*****************************************ҳ��ģ����ʾ����********************************************/

    /**
     * �������ص�����
     */
    function workflowCallBack($spid,$inquirysheetRows){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);

		if(is_array($inquirysheetRows)&&sizeof($inquirysheetRows)>0&&$folowInfo['examines']=="ok"){  //����ͨ����ָ����Ӧ��
			$assignSupp=$this->assignSupp_d($inquirysheetRows);
			//�����ʼ�֪ͨ�ɹ�Ա
			$this->sendEmail_d($inquirysheetRows['id']);
		}
    }


}
?>
