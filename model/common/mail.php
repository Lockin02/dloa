<?php
/*
 * Created on 2010-11-12
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_common_mail extends model_base{
	function __construct(){
		parent::__construct ();
	}

	/**
	 * ҵ���������
	 */
	private $objNameArr = array(
		'oa_finance_invoiceapply' => '��Ʊ���뵥' ,
		'oa_esm_project' => '������Ŀ',
		'oa_finance_income' => '���',
		'oa_finance_payables' => '���',
		'oa_rd_task_over' => '�з��������',
		'oa_rd_task' => '�����з�����',
		'oa_purch_plan_basic' => '�ɹ����뵥',
		'oa_purch_task_basic' => '�ɹ�����',
		'oa_purch_apply_basic' => '�ɹ�����',
		'oa_purchase_arrival_info' => '�ɹ�����֪ͨ',
        'oa_finance_invoice' => '��Ʊ��¼',
        'oa_stock_outplan' => '�����ƻ�',
        'oa_stock_ship' => '������',
        'oa_mail_info' => '�ʼ���Ϣ',
        'oa_timeTask' => '��ʱ����',
        'oa_sale_order' => '���ۺ�ͬ��Ϣ',
        'oa_sale_service' => '�����ͬ��Ϣ',
        'oa_sale_lease' => '���޺�ͬ��Ϣ',
        'oa_sale_rdproject' => '�з���ͬ��Ϣ',
        'asset_pushPurch' => '�ʲ��ɹ�����',
        'oa_mail_sign' => '��Ʊ�ʼ���Ϣ',
        'oa_purch_inquiry' => '�ɹ�ѯ�۵�',
        'oa_borrow_borrow' => '���������뵥',
        'storageAffirmInfo' => 'Ա��������ȷ��������Ϣ',
        'storageAffirmInfoBack' => '�����òֹ�ȷ�ϻظ���Ϣ',
        'backBorrowInfo' => '�����������˻���Ϣ',
        'borrowToExedeptInfo' => '�����õ���ת��ִ�в�������Ϣ',
        'exedpptToStorageInfo' => 'ִ�в��ظ�Ա�������õ��ݴ�����Ϣ',
        'borrowToOrder' => '������ת����¼��黹����Ϣ',
        'subProBorrowMail' => '���ڽ������ύ����',
        'subTenancyInfo' => 'Ա��ת�账����Ϣ',
        'PickingRemind' => 'Ա����������������',
        'contractBeomce' => '��ͬת������',
        'purchTaskFeedback' => '�ɹ�������',
        'purchPlanClose' => '�ɹ�����ر�',
        'purchPlanFeedback' => '�ɹ����뷴��',
        'purchSpeed' => '�ɹ����ȷ���',
        'purchPlanChange' => '�ɹ�������',
        'purchPlanTask' => '�ɹ���������ȷ������',
        'purchPlanBack' => '�ɹ�������',
        'trialproject' => '������Ŀ����',
        'trialproject_delay' => '������Ŀ��������',
        'oa_supp_suppasses_task' => '��Ӧ����������',
        'shipconditon' => '��ͬ����֪ͨ',
        'oa_contract_change' => '��ͬ���֪ͨ',
        'oa_contract_contract' => '��ͬ����ͨ������',
        'hr_recruitment_recommend' => '�ڲ��Ƽ�����',
        'recommend_passed' => '�ڲ��Ƽ�ͨ������',
        'recommend_backed' => '�ڲ��Ƽ���������',
        'resumepassed_info' => '����֪ͨ',
        'oa_contract_equ'=>'��ͬ����ȷ��',
        'oa_borrow_equ'=>'����������ȷ��',
        'oa_present_equ'=>'��������ȷ��',
        'oa_contract_exchange_equ'=>'��������ȷ��',
        'oa_sale_chance_support' => '��ǰ֧������',
        'contractInfo' => '��ͨ����Ϣ',
        'sendLeave'=>'Ա����ְ֪ͨ',
        'sendLeaveStaff'=>'��ְ��������֪ͨ',
        'oa_sale_chance'=>'�����̻�',
        'oa_contract_deduct' =>'�ۿ�����',
        'cost_estimates' => 'ȷ�ϳɱ�����',
        'contractClose' => '��ͬ�쳣�ر�����',
        'oa_contract_deductEnd' => '�ۿ�������Ϣ',
        'oa_asset_allocation' => '�̶��ʲ�������Ϣ',
        'assetPurchase_feedback' => '�ʲ��ɹ�������Ϣ',
        'contractSignEdit'=> '��ͬǩ����Ϣ',
        'hr_permanent_examine'=> 'Ա�����ÿ�������֪ͨ',
        'oa_asset_requirement'=>'�̶��ʲ���������֪ͨ',
        'oa_asset_requirement_back'=>'��ع̶��ʲ�����֪ͨ',
        'oa_asset_requirement_add'=>'�̶��ʲ���������֪ͨ',
        'oa_trialproject_trialproject' => '������Ŀ�ɱ�ȷ��',
        'esmClose' => '��Ŀ�ر�',
        'produceApplyPurch' => '�����ɹ���������',
        'certifyapplyBack' => '��ְ�ʸ���֤����',
        'oa_asset_purchase_apply' => '�̶��ʲ��ɹ�',
        'fillupAudit' => '������������',
        'hr_recruitment_entryNotice'=>'¼��֪ͨ��Ϣ',
        'interview_notice'=>'����֪ͨ',
        'apply_passed'=>'��Ա���븺����֪ͨ',
        'hr_recruitment_entryNotice_Date'=>'��ְʱ���޸���Ϣ',
        'backRelContractInfo' => '��ͬ����ȷ�������˻���Ϣ',
		'backRelContractInfo2' => '��ͬ�ɱ�����ȷ���˻���Ϣ',
	);

	/**
	 * ҵ�����
	 */
	private function returnObjName($objCode){
		return $this->objNameArr[$objCode];
	}

	/**
	 * ��ȡ�ʼ����ַ
	 */
	private function getemailinfogroup($users){
		$newusers = "";
		$arrusers = explode(",",$users);
		foreach($arrusers as $val){
			$newusers.= "'$val',";
		}
		$newusers = substr($newusers,0,-1);

		$allinfo = "select EMAIL ,USER_NAME from user where USER_ID in (".$newusers.") and has_left=0";
		$emailinfo = array();
		$rows = $this->findSql($allinfo);
		foreach($rows as $key => $val){
			$emailinfo[$val['EMAIL']]=$val['USER_NAME'];
		}
		return $emailinfo;
	}


	/**
	 * ͨ�������ʼ����ͷ���
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function batchEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null,$titleExtInfo = ''){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />    ".$user ."���Ѿ� <font color=blue>".$status."</font> <<".$title.">> :<font color=red>".$mission."</font><br />������Ϣ:<br/> $addmsg ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			$title = $titleExtInfo.$title;
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}

			// ����ʼ���¼
			$mailUser = "";
			foreach ($emailstr as $mail => $name){
				$mailUser .= ($mailUser == "")? "{$mail}:{$name}" : ",{$mail}:{$name}";
			}
			$mailconfigDao = new model_system_mailconfig_mailconfig();
			$mailconfigDao->addMailRecord($title,'',$mailUser);

			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}


	/**
	 * ���������ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function arrivalEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$status.":<font color=blue>".$mission."</font><br />��ϸ��Ϣ:<br/> $addmsg  ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
		/**
	 * ���������ʼ�(������ʾ����)
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function arrivalEmailWithEqu($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $objCode;
			$content = "���ã�<br /> ".$status.":<font color=blue>".$mission."</font><br />��ϸ��Ϣ:<br/> $addmsg  ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * �´�ɹ��������ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function purchTaskEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> �����µĲɹ�����".$status.$mission.",��鿴�ͽ��ա�<br />��ϸ��Ϣ��<br/>".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * �ɹ������������ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function purchTaskFeedbackEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br />���ʼ���".$user."����".$status.$mission.",����ա�<br />��ϸ��Ϣ��<br/>".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * �ʲ��ɹ��´�ɹ�ʱ�������ʼ�֪ͨ
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function pushPurch($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ���µ��ʲ��ɹ�����".$status.$mission.",��鿴��<br />��ϸ��Ϣ��<br/>".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * �ɹ�ѯ�۵�����ͨ��ʱ�������ʼ�֪ͨ
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function emailInquiry($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$status.$mission.",��鿴��<br />��ϸ��Ϣ��<br/>".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * ��ְ�ʸ���������ͨ��ʱ�������ʼ�֪ͨ
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function certifyapply($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$status.$mission."<br />��ϸ��Ϣ��<br/>".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * �ɹ������޸�ǩԼ״̬�����ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function contractEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$status.$mission.",��鿴��<br />";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * ͨ�������ʼ����ͷ��� - ������ҵ�������ֻ���ʼ�����
	 * �ʼ����⣬�ʼ�������id�����ʼ����ݣ�������
	 */
	public function mailClear($title = '����',$receiveuser,$content =null,$addIds = null){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
		if(substr($receiveuser,-1) == ','){
			$receiveuser = substr($receiveuser,0,-1);
		}

		$email = new includes_class_sendmail();
		$emailstr = $this->getemailinfogroup($receiveuser);

		if(!empty($addIds)){
			$addRows = $this->getemailinfogroup($addIds);
		}

		$content = nl2br($content);
		return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
	}
	/*
	 * ͨ�������ʼ����ͷ��������ݲ��Զ���ӿ��У�- ������ҵ�������ֻ���ʼ�����
	 * �ʼ����⣬�ʼ�������id�����ʼ����ݣ�������
	 */
	 public function mailGeneral($title = '����',$receiveuser,$content =null,$addIds = null){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
		if(substr($receiveuser,-1) == ','){
			$receiveuser = substr($receiveuser,0,-1);
		}

		$email = new includes_class_sendmail();
		$emailstr = $this->getemailinfogroup($receiveuser);

		if(!empty($addIds)){
			$addRows = $this->getemailinfogroup($addIds);
		}
		return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
	}

	/**
	 * ͨ�������ʼ����ͷ��� - ��������
	 * �ʼ����⣬�ʼ�������id�����ʼ����ݣ�������
	 */
	public function mailWithFile($title = '����',$receiveuser,$content =null,$addIds = null,$attachment=""){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
		if(substr($receiveuser,-1) == ','){
			$receiveuser = substr($receiveuser,0,-1);
		}

		$email = new includes_class_sendmail();
		$emailstr = $this->getemailinfogroup($receiveuser);

		if(!empty($addIds)){
			$addRows = $this->getemailinfogroup($addIds);
		}

		$content = nl2br($content);
		return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows,$attachment);
	}

	/**
	 * ͨ�������ʼ����ͷ���(���ݲ��Զ���ӿ���) - ��������
	 * �ʼ����⣬�ʼ�������id�����ʼ����ݣ�������
	 */
	public function mailWithFileGeneral($title = '����',$receiveuser,$content =null,$addIds = null,$attachment=""){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
		if(substr($receiveuser,-1) == ','){
			$receiveuser = substr($receiveuser,0,-1);
		}

		$email = new includes_class_sendmail();
		$emailstr = $this->getemailinfogroup($receiveuser);

		if(!empty($addIds)){
			$addRows = $this->getemailinfogroup($addIds);
		}

		return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows,$attachment);
	}

	/**
	 * ͨ�������ʼ����ͷ���
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function specialEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null,$head){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />    ".$user ."���Ѿ� <font color=blue>".$status."</font> <<".$title.">> :<font color=red>".$mission."</font><br />������Ϣ:<br/> $addmsg ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			$title = $title."($head)";
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * Ա�������� ���͸��ֿ�ȷ�� --- �ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toStorageEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />   ".$user ."���ύ��һ��Ա��������������Ҫ��ȷ����Ϣ�� ���ݱ��Ϊ�� ��".$code."��<br />������Ϣ:<br/> $addmsg ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * Ա�������� �ֹ�ȷ�Ϻ�ظ� �ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toStorageBackEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />    ".$user ."��ȷ�������Ľ�����������Ϣ�� ���ݱ��Ϊ�� ��".$code."��<br />������Ϣ:<br/> $addmsg ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * Ա�������� �ֹܴ��� �˻����뷢�� �ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toExeBackEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = '���ã�<br />��'.$user .'���˻������Ľ�����������Ϣ�� ���ݱ��Ϊ�� "'.$code.'"<br />������Ϣ:<br/> '.$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * Ա�������� �ֹܴ��� ת��ִ�в� ���� �ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toExeEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />    ".$user ."����Ա�������õ��� ��".$code."�� �����ˡ�ת��ִ�в������� ������Ҫ�Դ˵��ݴ�����С��´����������´�ɹ��������� ���ݱ��Ϊ�� ��".$code."��<br />������Ϣ:<br/> $addmsg ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * Ա�������� ִ�в� ���²ֹ�  �����ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toshipbackEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />  ִ�в�  ".$user ."����Ա�������õ��ݡ�".$code."�� �����ˡ����²ֿ⡱ ������������Ҫ�������� ���ݱ��Ϊ�� ��".$code."��<br />������Ϣ:<br/> $addmsg ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * ������ת����  ¼��黹�� �ʼ���Ϣ
	 */
	public function borrowToOrderEmail($ismail,$user,$userEmail,$objCode,$serial,$outLoanInfo,$borrowCodeInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> һ��������ת���۵���������ͨ��������Ҫ�ֹ�¼������ù黹��<br /><b>�����õ��ݱ��</b>��<br/>".$borrowCodeInfo." <br/><b>�����������Ϣ</b>��<br/>".$outLoanInfo." <b>�黹������Ϣ</b>��<br/> ".$serial;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
    /**
	 * ���ڽ����� �ύ���� �ʼ�����
	 */
	public function subBorrowEmail($ismail,$user,$userEmail,$objCode,$code,$outLoanInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$user ." �ύ��һ�����ڽ��������룬������ʱ���� ���ݱ�� �� ��".$code."�� ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * Ա��ת������ ȷ�Ϻ� ���͸��ֹܵ��ʼ�
	 */
	 public function subtenancyEmail($ismail,$user,$userEmail,$objCode,$subCode,$Code,$receiveuser,$Item,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> һ��Ա��������ת�����룬�Ѿ�������ȷ����ɡ���Ҫ��¼��һ�ŵ����黹�������ڡ������ô����б�����һ�Ž�������� <br/>" .
					   "ת��Դ������Ҫ¼��黹���Ľ����õ��ݣ� ����".$subCode."�� <br/> " .
					   "���Դ������Ҫ¼��\���ƽ�����Ľ����õ��ݣ��� ��".$Code."�� <br/>" .
					   "������Ϣ �� <br/> ".$Item;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * Ա�������� ����֪ͨ �ʼ�
	 */
	public function pickingRemindMail($ismail,$user,$userEmail,$objCode,$code,$outLoanInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$user ." ��������������Ľ����õ��� ��".$code."�� �ѿ�����ȡ���ϣ�������ʱȥ�ֿ���ȡ���ϡ�".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}


	/**
	 * �ɹ������´�ʱ�������ʼ�֪ͨ
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function purchasePlanMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0,$addMsgBeforeContent = '' ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "";
			if($addMsgBeforeContent != ''){
				$title = $addMsgBeforeContent;
			}
			$content .= "���ã�<br /> ".$status.$mission.",��鿴��<br />��ϸ��Ϣ��<br/>".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

    /**
     * ��ͬת��ʱ�����ʼ�֪ͨ����
     * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
     */
   public function contractBecomeEmail($type,$ismail,$user,$userEmail,$objCode,$code,$outLoanInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$user ." �ѽ���".$type."�����Ϊ ��".$code."�� ����ʱ��ͬתΪ��ʽ��ͬ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * ����֪ͨ
	 * �����ˣ����������䣬title,�ռ���,������Ϣ
	 */
	public function InterviewEmail($user,$userEmail,$title,$emailstr,$content=null,$ccMailId = null ,$bccMailId = null,$isSender = 0 ,$attachment = ''){
			$addRows = null;
			$email = new includes_class_sendmail();
			if(!empty($ccMailId)){
				$ccMailIdRow = $this->getemailinfogroup($ccMailId);
			}
			if(!empty($bccMailId)){
				$bccMailIdRow = $this->getemailinfogroup($bccMailId);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$ccMailIdRow,$attachment);
			}else{
				return $email->send($title,$content,$emailstr,null,null,'GBK','default','',$attachment);
			}
	}

    /**
     * offer֪ͨ
     * �����ˣ����������䣬title,�ռ���,������Ϣ
     */
    public function offerEmail($user,$userEmail,$title,$emailstr,$content=null,$ccMailId = null ,$bccMailId = null,$isSender = 0 ,$attachment = ''){
        $addRows = null;
        $email = new includes_class_sendmail("HR");
        if(!empty($ccMailId)){
            $ccMailIdRow = $this->getemailinfogroup($ccMailId);
        }
        if(!empty($bccMailId)){
            $bccMailIdRow = $this->getemailinfogroup($bccMailId);
        }
        if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
            return $email->send($title,$content,$emailstr,null,null,'GBK','default',$ccMailIdRow,$attachment);
        }else{
            return $email->send($title,$content,$emailstr,null,null,'GBK','default','',$attachment);
        }
    }

	/**
	 * ��ͬ�������֪ͨ
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬״̬����������ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function contractChangeMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "���ã�<br /> ".$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * ������Ŀ����ͨ�����ʼ�֪ͨ
	 */
	public function trialprojectEmail($ismail,$user,$userEmail,$objCode,$Code,$receiveuser,$content_msg,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = $content_msg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * ��ǰ֧����������ͨ���� ���͸� ������Ա���ʼ�
	 */
		public function supportMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = $addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
   /**
    * �����̻�����ʼ�ģ��
    */
   public function chanceMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = $addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * �ۿ���������ͨ�������ʼ�
	 */
	public function deductMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = $addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * ��ͬ �ɱ����� ���������̲��ʼ�ģ��
	 */
	public function estimatesMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = $addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * ������Ŀ �ύ���͸��ɱ�ȷ���˵��ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toStrialprojectEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />   ".$user ."���ύ��һ��������Ŀ��Ҫ��ȷ�ϳɱ��� ���ݱ��Ϊ�� ��".$code."��<br />";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
	/**
	 * ������Ŀת��ͬ �ر���Ŀ�ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toCloseEsmMail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "���ã�<br />������Ŀת��ͬ��".$code."��������ͨ���������Ŀ�ѹر�<br />";
			$content .= $addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * ��ͬ����ȷ�ϴ�ط��� �ʼ�
	 * �Ƿ�����ʼ������������ˣ����������䣬ҵ�������룬���ݺţ�ҵ�����ƣ������ˣ���,�Ž�β��,������Ϣ
	 * @param $ismail ��1:��������ȷ�ϴ��; 2:����ɱ�����ȷ�ϴ�ء�
	 * @param $user
	 * @param $userEmail
	 * @param $objCode
	 * @param $code
	 * @param $mission
	 * @param $receiveuser
	 * @param null $addmsg
	 * @param int $isSender
	 * @param null $addIds
	 * @return bool|void
	 */
	public function toRelContractBack($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = ($ismail == 1)? '���ã�<br />��'.$user .'���˻������ĺ�ͬ����ȷ��������Ϣ�� ���ݱ��Ϊ�� "'.$code.'"<br />������Ϣ:<br/> '.$addmsg :
				'���ã�<br />��'.$user .'���˻������ĺ�ͬ�ɱ�����ȷ�����롣 ���ݱ��Ϊ�� "'.$code.'"<br />���ԭ��:<br/> '.$addmsg;
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}

			$mailConfigDao = new model_system_mailconfig_mailconfig();
			$mailConfigDao->addMailRecord($title,$content,$emailstr,'');
			if($isSender){
				//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}

	/**
	 * ����Ӧ�����ֺ��� �ʼ�
	 * �Ƿ�����ʼ������������ˣ����⣬���ݺţ���Ӧ�����ƣ��������ͣ������ˣ���,�Ž�β��,������Ϣ
	 */
	public function toApplySuppasses($ismail,$user,$title = "",$code,$suppName,$assesType,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$title = ($title == "")? "��Ӧ������֪ͨ" : $title;
			$addRows = null;
			if(empty($receiveuser)){
				return false;
			}
			//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}

			$addmsg = nl2br($addmsg);
			$content = '���ݡ�'.$code.'������Ӧ�̡�'.$suppName.'������'.$assesType.'��������뿼�����ָ����˾������֣�лл��';
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}
			if($isSender){
				//				return $email->send($title,$content,$emailstr,$userEmail,$user);
				return $email->send($title,$content,$emailstr,null,null,'GBK','default',$addRows);
			}else{
				return $email->send($title,$content,$emailstr);
			}
		}
	}
}
?>
