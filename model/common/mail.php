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
	 * 业务对象数组
	 */
	private $objNameArr = array(
		'oa_finance_invoiceapply' => '开票申请单' ,
		'oa_esm_project' => '工程项目',
		'oa_finance_income' => '到款单',
		'oa_finance_payables' => '付款单',
		'oa_rd_task_over' => '研发任务审核',
		'oa_rd_task' => '新增研发任务',
		'oa_purch_plan_basic' => '采购申请单',
		'oa_purch_task_basic' => '采购任务',
		'oa_purch_apply_basic' => '采购订单',
		'oa_purchase_arrival_info' => '采购到货通知',
        'oa_finance_invoice' => '开票记录',
        'oa_stock_outplan' => '发货计划',
        'oa_stock_ship' => '发货单',
        'oa_mail_info' => '邮寄信息',
        'oa_timeTask' => '定时任务',
        'oa_sale_order' => '销售合同信息',
        'oa_sale_service' => '服务合同信息',
        'oa_sale_lease' => '租赁合同信息',
        'oa_sale_rdproject' => '研发合同信息',
        'asset_pushPurch' => '资产采购申请',
        'oa_mail_sign' => '发票邮寄信息',
        'oa_purch_inquiry' => '采购询价单',
        'oa_borrow_borrow' => '借试用申请单',
        'storageAffirmInfo' => '员工借试用确认物料信息',
        'storageAffirmInfoBack' => '借试用仓管确认回复信息',
        'backBorrowInfo' => '借试用申请退回信息',
        'borrowToExedeptInfo' => '借试用单据转至执行部处理信息',
        'exedpptToStorageInfo' => '执行部回复员工借试用单据处理信息',
        'borrowToOrder' => '借试用转销售录入归还单信息',
        'subProBorrowMail' => '短期借试用提交申请',
        'subTenancyInfo' => '员工转借处理信息',
        'PickingRemind' => '员工借试用领料提醒',
        'contractBeomce' => '合同转正提醒',
        'purchTaskFeedback' => '采购任务反馈',
        'purchPlanClose' => '采购申请关闭',
        'purchPlanFeedback' => '采购申请反馈',
        'purchSpeed' => '采购进度反馈',
        'purchPlanChange' => '采购申请变更',
        'purchPlanTask' => '采购申请物料确认任务',
        'purchPlanBack' => '采购申请打回',
        'trialproject' => '试用项目申请',
        'trialproject_delay' => '试用项目延期申请',
        'oa_supp_suppasses_task' => '供应商评估任务',
        'shipconditon' => '合同发货通知',
        'oa_contract_change' => '合同变更通知',
        'oa_contract_contract' => '合同审批通过提醒',
        'hr_recruitment_recommend' => '内部推荐提醒',
        'recommend_passed' => '内部推荐通过提醒',
        'recommend_backed' => '内部推荐反馈提醒',
        'resumepassed_info' => '面试通知',
        'oa_contract_equ'=>'合同物料确认',
        'oa_borrow_equ'=>'借试用物料确认',
        'oa_present_equ'=>'赠送物料确认',
        'oa_contract_exchange_equ'=>'换货物料确认',
        'oa_sale_chance_support' => '售前支持申请',
        'contractInfo' => '沟通板信息',
        'sendLeave'=>'员工离职通知',
        'sendLeaveStaff'=>'离职申请审批通知',
        'oa_sale_chance'=>'销售商机',
        'oa_contract_deduct' =>'扣款申请',
        'cost_estimates' => '确认成本概算',
        'contractClose' => '合同异常关闭申请',
        'oa_contract_deductEnd' => '扣款处理完成信息',
        'oa_asset_allocation' => '固定资产调拨信息',
        'assetPurchase_feedback' => '资产采购反馈信息',
        'contractSignEdit'=> '合同签收信息',
        'hr_permanent_examine'=> '员工试用考核评估通知',
        'oa_asset_requirement'=>'固定资产需求申请通知',
        'oa_asset_requirement_back'=>'打回固定资产需求通知',
        'oa_asset_requirement_add'=>'固定资产需求申请通知',
        'oa_trialproject_trialproject' => '试用项目成本确认',
        'esmClose' => '项目关闭',
        'produceApplyPurch' => '生产采购申请审批',
        'certifyapplyBack' => '任职资格认证申请',
        'oa_asset_purchase_apply' => '固定资产采购',
        'fillupAudit' => '补库申请审批',
        'hr_recruitment_entryNotice'=>'录用通知消息',
        'interview_notice'=>'面试通知',
        'apply_passed'=>'增员申请负责人通知',
        'hr_recruitment_entryNotice_Date'=>'入职时间修改信息',
        'backRelContractInfo' => '合同物料确认申请退回信息',
		'backRelContractInfo2' => '合同成本概算确认退回信息',
	);

	/**
	 * 业务对象
	 */
	private function returnObjName($objCode){
		return $this->objNameArr[$objCode];
	}

	/**
	 * 获取邮件组地址
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
	 * 通用批量邮件发送方法
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function batchEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null,$titleExtInfo = ''){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />    ".$user ."，已经 <font color=blue>".$status."</font> <<".$title.">> :<font color=red>".$mission."</font><br />附加信息:<br/> $addmsg ";
			$email = new includes_class_sendmail();
			$emailstr = $this->getemailinfogroup($receiveuser);
			$title = $titleExtInfo.$title;
			if(!empty($addIds)){
				$addRows = $this->getemailinfogroup($addIds);
			}

			// 添加邮件记录
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
	 * 到货发送邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function arrivalEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$status.":<font color=blue>".$mission."</font><br />详细信息:<br/> $addmsg  ";
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
	 * 到货发送邮件(主题显示物料)
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function arrivalEmailWithEqu($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $objCode;
			$content = "您好！<br /> ".$status.":<font color=blue>".$mission."</font><br />详细信息:<br/> $addmsg  ";
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
	 * 下达采购任务发送邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function purchTaskEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> 你有新的采购任务".$status.$mission.",请查看和接收。<br />详细信息：<br/>".$addmsg;
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
	 * 采购任务反馈发送邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function purchTaskFeedbackEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br />该邮件由".$user."发送".$status.$mission.",请查收。<br />详细信息：<br/>".$addmsg;
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
	 * 资产采购下达采购时，发送邮件通知
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function pushPurch($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> 有新的资产采购申请".$status.$mission.",请查看。<br />详细信息：<br/>".$addmsg;
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
	 * 采购询价单审批通过时，发送邮件通知
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function emailInquiry($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$status.$mission.",请查看。<br />详细信息：<br/>".$addmsg;
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
	 * 任职资格申请审批通过时，发送邮件通知
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function certifyapply($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$status.$mission."<br />详细信息：<br/>".$addmsg;
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
	 * 采购订单修改签约状态发送邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function contractEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$status.$mission.",请查看。<br />";
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
	 * 通用批量邮件发送方法 - 不包含业务关联，只有邮件发送
	 * 邮件标题，邮件接收人id串，邮件内容，抄送人
	 */
	public function mailClear($title = '无题',$receiveuser,$content =null,$addIds = null){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 通用批量邮件发送方法（内容不自动添加空行）- 不包含业务关联，只有邮件发送
	 * 邮件标题，邮件接收人id串，邮件内容，抄送人
	 */
	 public function mailGeneral($title = '无题',$receiveuser,$content =null,$addIds = null){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 通用批量邮件发送方法 - 包含附件
	 * 邮件标题，邮件接收人id串，邮件内容，抄送人
	 */
	public function mailWithFile($title = '无题',$receiveuser,$content =null,$addIds = null,$attachment=""){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 通用批量邮件发送方法(内容不自动添加空行) - 包含附件
	 * 邮件标题，邮件接收人id串，邮件内容，抄送人
	 */
	public function mailWithFileGeneral($title = '无题',$receiveuser,$content =null,$addIds = null,$attachment=""){
		$addRows = null;
		if(empty($receiveuser)){
			return ;
		}
		//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 通用批量邮件发送方法
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function specialEmail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null,$head){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />    ".$user ."，已经 <font color=blue>".$status."</font> <<".$title.">> :<font color=red>".$mission."</font><br />附加信息:<br/> $addmsg ";
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
	 * 员工借试用 发送给仓库确认 --- 邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 */
	public function toStorageEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />   ".$user ."，提交了一个员工借试用申请需要您确认信息。 单据编号为： ”".$code."“<br />附加信息:<br/> $addmsg ";
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
	 * 员工借试用 仓管确认后回复 邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 */
	public function toStorageBackEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />    ".$user ."，确认了您的借试用申请信息。 单据编号为： ”".$code."“<br />附加信息:<br/> $addmsg ";
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
	 * 员工借试用 仓管处理 退货申请发送 邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 */
	public function toExeBackEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = '您好！<br />【'.$user .'】退回了您的借试用申请信息。 单据编号为： "'.$code.'"<br />附加信息:<br/> '.$addmsg;
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
	 * 员工借试用 仓管处理 转至执行不 发送 邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 */
	public function toExeEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />    ".$user ."，对员工借试用单据 ”".$code."“ 进行了“转至执行部”操作 ，您需要对此单据处理进行“下达生产”或“下达采购”操作。 单据编号为： ”".$code."“<br />附加信息:<br/> $addmsg ";
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
	 * 员工借试用 执行部 回致仓管  发送邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 */
	public function toshipbackEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />  执行部  ".$user ."，对员工借试用单据“".$code."” 进行了“回致仓库” 操作，单据需要后续处理。 单据编号为： ”".$code."“<br />附加信息:<br/> $addmsg ";
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
	 * 借试用转销售  录入归还单 邮件信息
	 */
	public function borrowToOrderEmail($ismail,$user,$userEmail,$objCode,$serial,$outLoanInfo,$borrowCodeInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> 一个借试用转销售单据已审批通过，您需要手工录入借试用归还单<br /><b>借试用单据编号</b>：<br/>".$borrowCodeInfo." <br/><b>借出调拨单信息</b>：<br/>".$outLoanInfo." <b>归还物料信息</b>：<br/> ".$serial;
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
	 * 短期借试用 提交申请 邮件提醒
	 */
	public function subBorrowEmail($ismail,$user,$userEmail,$objCode,$code,$outLoanInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$user ." 提交了一条短期借试用申请，请您及时处理。 单据编号 ： “".$code."” ";
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
	 * 员工转借申请 确认后 发送给仓管的邮件
	 */
	 public function subtenancyEmail($ismail,$user,$userEmail,$objCode,$subCode,$Code,$receiveuser,$Item,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> 一条员工借试用转借申请，已经审批并确认完成。需要您录入一张调拨归还单，并在“借试用处理”列表下推一张借出调拨单 <br/>" .
					   "转借源单（需要录入归还单的借试用单据） ：“".$subCode."” <br/> " .
					   "借出源单（需要录入\下推借出单的借试用单据）： “".$Code."” <br/>" .
					   "物料信息 ： <br/> ".$Item;
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
	 * 员工借试用 领料通知 邮件
	 */
	public function pickingRemindMail($ismail,$user,$userEmail,$objCode,$code,$outLoanInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$user ." 提醒您，您申请的借试用单据 “".$code."” 已可以领取物料，请您及时去仓库领取物料。".$addmsg;
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
	 * 采购任务下达时，发送邮件通知
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function purchasePlanMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0,$addMsgBeforeContent = '' ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "";
			if($addMsgBeforeContent != ''){
				$title = $addMsgBeforeContent;
			}
			$content .= "您好！<br /> ".$status.$mission.",请查看。<br />详细信息：<br/>".$addmsg;
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
     * 合同转正时发送邮件通知财务
     * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
     */
   public function contractBecomeEmail($type,$ismail,$user,$userEmail,$objCode,$code,$outLoanInfo,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$user ." 已将“".$type."”编号为 “".$code."” 的临时合同转为正式合同";
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
	 * 面试通知
	 * 发送人，发送人邮箱，title,收件人,附加信息
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
     * offer通知
     * 发送人，发送人邮箱，title,收件人,附加信息
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
	 * 合同变更审批通知
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，状态（动作），业务名称，接收人（以,号结尾）,附加信息
	 */
	public function contractChangeMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$content = "您好！<br /> ".$addmsg;
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
	 * 试用项目审批通过后邮件通知
	 */
	public function trialprojectEmail($ismail,$user,$userEmail,$objCode,$Code,$receiveuser,$content_msg,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 售前支持申请审批通过后 发送给 交流人员的邮件
	 */
		public function supportMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
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
    * 销售商机相关邮件模板
    */
   public function chanceMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 扣款申请审批通过后发送邮件
	 */
	public function deductMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 合同 成本概算 发送至工程不邮件模板
	 */
	public function estimatesMail($ismail,$user,$userEmail,$objCode,$status,$mission,$receiveuser,$addmsg=null,$isSender = 0 ){
		if($ismail){
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
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
	 * 试用项目 提交后发送给成本确认人的邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 */
	public function toStrialprojectEmail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />   ".$user ."，提交了一个试用项目需要您确认成本。 单据编号为： ”".$code."“<br />";
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
	 * 试用项目转合同 关闭项目邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 */
	public function toCloseEsmMail($ismail,$user,$userEmail,$objCode,$code,$mission,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$addRows = null;
			if(empty($receiveuser)){
				return ;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = "您好！<br />试用项目转合同”".$code."“已审批通过，相关项目已关闭<br />";
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
	 * 合同物料确认打回发送 邮件
	 * 是否进行邮件操作，发送人，发送人邮箱，业务对象代码，单据号，业务名称，接收人（以,号结尾）,附加信息
	 * @param $ismail 【1:销售物料确认打回; 2:服务成本概算确认打回】
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
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}
			$title = $this->returnObjName($objCode);
			$addmsg = nl2br($addmsg);
			$content = ($ismail == 1)? '您好！<br />【'.$user .'】退回了您的合同物料确认申请信息。 单据编号为： "'.$code.'"<br />附加信息:<br/> '.$addmsg :
				'您好！<br />【'.$user .'】退回了您的合同成本概算确认申请。 单据编号为： "'.$code.'"<br />打回原因:<br/> '.$addmsg;
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
	 * 发起供应商评分后发送 邮件
	 * 是否进行邮件操作，发送人，标题，单据号，供应商名称，考核类型，接收人（以,号结尾）,附加信息
	 */
	public function toApplySuppasses($ismail,$user,$title = "",$code,$suppName,$assesType,$receiveuser,$addmsg=null,$isSender = 0 ,$addIds = null){
		if($ismail){
			$title = ($title == "")? "供应商评估通知" : $title;
			$addRows = null;
			if(empty($receiveuser)){
				return false;
			}
			//判断最后一个字符是否逗号,是的话则出去逗号
			if(substr($receiveuser,-1) == ','){
				$receiveuser = substr($receiveuser,0,-1);
			}

			$addmsg = nl2br($addmsg);
			$content = '单据【'.$code.'】，供应商【'.$suppName.'】发起【'.$assesType.'】，请参与考核评分负责人尽快评分，谢谢！';
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
