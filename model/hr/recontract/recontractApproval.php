<?php


/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息 Model层
 */
class model_hr_recontract_recontractApproval extends model_base {

	function __construct() {
		$this->tbl_name = "hr_recontract_approval";
		$this->sql_map = "hr/recontract/recontractApprovalSql.php";
		parent :: __construct();
		$this->HrManager=array('yu.long','xiujie.zeng','shuyin.lin');
	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['conTypeName'] = $datadictDao->getDataNameByCode(trim($object['conType']));
			$object['conStateName'] = $datadictDao->getDataNameByCode(trim($object['conState']));
			$object['conNumName'] = $datadictDao->getDataNameByCode(trim($object['conNum']));
			$object['sysCompanyName'] = $datadictDao->getDataNameByCode(trim($object['sysCompanyId']));
			if ($object['statusId'] <= 2) {
				$object['isFlag'] == 0;
				$object['isFlagName'] = '无';
				} else {
				if ($object['isFlag'] == 1) {
					$object['isFlagName'] = '同意';
				}
				elseif ($object['isFlag'] == 2) {
					$object['isFlagName'] = '不同意';
				}
			}
			//修改主表信息
			parent :: edit_d($object, true);

			$this->commit_d();
			//			$this->rollBack();
			return true;
		} catch (exception $e) {

			return false;
		}
	}

	/**
	 * 重写新增方法
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['conTypeName'] = $datadictDao->getDataNameByCode(trim($object['conType']));
			$object['conStateName'] = $datadictDao->getDataNameByCode(trim($object['conState']));
			$object['conNumName'] = $datadictDao->getDataNameByCode(trim($object['conNum']));
			$object['sysCompanyName'] = $datadictDao->getDataNameByCode(trim($object['sysCompanyId']));
			if ($object['statusId'] <= 2) {
				$object['isFlag'] == 0;
				$object['isFlagName'] = '无';
			} else {
				if ($object['isFlag'] == 1) {
					$object['isFlagName'] = '同意';
				}
				elseif ($object['isFlag'] == 2) {
					$object['isFlagName'] = '不同意';
				}
			}
			
			//修改主表信息
			$id = parent :: add_d($object, true);
			//更新附件关联关系
			
			$this->updateObjStatus($object['recontractId'], $object['statusId']);
			if($object['statusId']=='5'||$object['statusId']=='4'){
				$this->updateObj($object['recontractId'],'aconNum',$object['conNum']);
				$this->updateObj($object['recontractId'],'aconNumName',$object['conNumName']);
				$this->updateObj($object['recontractId'],'aconState',$object['conState']);
				$this->updateObj($object['recontractId'],'aconStateName',$object['conStateName']);
				$this->updateObj($object['recontractId'],'aisFlag',$object['isFlag']);
				$this->updateObj($object['recontractId'],'aisFlagName',$object['isFlagName']);
			}
			if($object['statusId']=='7'||($object['statusId']=='8'&&$object['isFlag']==2)){
				$this->updateObj($object['recontractId'],'conNum',$object['conNum']);
				$this->updateObj($object['recontractId'],'conNumName',$object['conNumName']);
				$this->updateObj($object['recontractId'],'conState',$object['conState']);
				$this->updateObj($object['recontractId'],'conStateName',$object['conStateName']);
				$this->updateObj($object['recontractId'],'isFlag',$object['isFlag']);
				$this->updateObj($object['recontractId'],'isFlagName',$object['isFlagName']);
				$this->updateObj($object['recontractId'],'beginDate',$object['beginDate']);
				$this->updateObj($object['recontractId'],'closeDate',$object['closeDate']);
			}
			$this->sendMails($object['recontractId'], $object['statusId']);
			$this->commit_d();
			$this->rollBack();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写新增方法
	 */
	function addApploval_d($object) {
		//增加验证
		$ck = $this->checkFlow($object['fspId']);
		if($ck == 'no'){
			return false;
		}
		try {
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['conTypeName'] = $datadictDao->getDataNameByCode(trim($object['conType']));
			$object['conStateName'] = $datadictDao->getDataNameByCode(trim($object['conState']));
			$object['conNumName'] = $datadictDao->getDataNameByCode(trim($object['conNum']));
			$object['sysCompanyName'] = $datadictDao->getDataNameByCode(trim($object['sysCompanyId']));
			if ($object['isFlag'] == 1) {
				$object['isFlagName'] = '同意';
			}
			elseif ($object['isFlag'] == 2) {
				$object['isFlagName'] = '不同意';
			}

			//修改主表信息
			$id = parent :: add_d($object, true);
            $object['approvalId']=$id;
			//更新附件关联关系
			$this->updateFlow_d($object);
			   	$this->updateObj($object['recontractId'],'aconNum',$object['conNum']);
				$this->updateObj($object['recontractId'],'aconNumName',$object['conNumName']);
				$this->updateObj($object['recontractId'],'aconState',$object['conState']);
				$this->updateObj($object['recontractId'],'aconStateName',$object['conStateName']);
				$this->updateObj($object['recontractId'],'aisFlag',$object['isFlag']);
				$this->updateObj($object['recontractId'],'aisFlagName',$object['isFlagName']);
			$this->commit_d();
			$this->rollBack();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
/**
	 * 重写新增方法
	 */
	function addInformStaff_d($object) {
		try {
			
			$this->start_d();
			$gls = new includes_class_global();
		    $semails = new includes_class_sendmail();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['conTypeName'] = $datadictDao->getDataNameByCode(trim($object['conType']));
			$object['conStateName'] = $datadictDao->getDataNameByCode(trim($object['conState']));
			$object['conNumName'] = $datadictDao->getDataNameByCode(trim($object['conNum']));
			$object['sysCompanyId']=trim($object['signCompany']);
			$object['sysCompanyName'] = $datadictDao->getDataNameByCode(trim($object['signCompany']));

			if ($object['isFlag'] == 1) {
				$object['isFlagName'] = '同意';
			}
			elseif ($object['isFlag'] == 2) {
				$object['isFlagName'] = '不同意';
			}
			//修改主表信息
			$id = parent :: add_d($object, true);
			//更新附件关联关系
			//$this->updateObjStatus($object['recontractId'],6);
			$this->updateObj($object['recontractId'],'staffFlag',2);
			$this->updateObj($object['recontractId'],'pconNum',$object['conNum']);
			$this->updateObj($object['recontractId'],'pconNumName',$object['conNumName']);
			$this->updateObj($object['recontractId'],'pconState',$object['conState']);
			$this->updateObj($object['recontractId'],'pconStateName',$object['conStateName']);
			$this->updateObj($object['recontractId'],'pisFlag',$object['isFlag']);
			$this->updateObj($object['recontractId'],'pisFlagName',$object['isFlagName']);
			//$this->updateObj($object['recontractId'],'signCompany',$object['signCompany']);
			//$this->updateObj($object['recontractId'],'signCompanyName',$object['signCompanyName']);
			$this->updateObj($object['recontractId'],'repaAddress',$object['repaAddress']);
			//邮件通知HR
				if ($object['isSend']==1) {
					$Address = $gls->get_email($this->HrManager);
					$body ='<div><span style="font-size: small">您好！</span></div>
										<div>&nbsp;</div><div><span style="font-size: small">' . $object['userName'] . '已确认了续签合同，请登陆OA查阅</span></div><div>&nbsp;</div> <hr /><p><br />
										<font size="2">内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br />
											<br />
									    外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></p>';
					$body .= '<div>请登陆OA查阅</span></div><div>&nbsp;</div> <hr /><p><br />
								<font size="2">内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br />
									<br />
							    外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></p>';
					$flag = $semails->send('续签合同已确认！', $body, $Address);

				}
			
			$this->commit_d();
			$this->rollBack();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**
		 *更新父类状态
		 */
	function updateObjStatus($id, $statusId) {

		if ($id && $statusId) {
			$sql = "UPDATE  hr_recontract SET statusId='$statusId' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}
	/**
		 *更新父类状态
		 */
	function updateObj($id, $conn, $value) {

		if ($id && $value && $conn) {
			$sql = "UPDATE  hr_recontract SET $conn='$value' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}
	function updateFlow_d($object) {
		$gls = new includes_class_global();
		$semails = new includes_class_sendmail();
		if ($object['fspId']) {
			$sql = "select StepID,SmallID,Wf_task_ID from flow_step_partent where ID='" . $object['fspId'] . "' ";
			$objectFsI = $this->_db->getArray($sql);
			if ($objectFsI[0] && is_array($objectFsI[0])) {

				$SmallID = $objectFsI[0]["SmallID"];
				$StepID = $objectFsI[0]["StepID"];
				$taskid = $objectFsI[0]["Wf_task_ID"];
				$result = $object['isFlag'];
				$next_sid = "";
				//update flow_step
				$sql = "update flow_step set Flag='ok',Endtime=now()  where Wf_task_ID='$taskid' and SmallID='$SmallID'";
				//$this->query($sql);
				//is last step?
				$nextSmallid = $SmallID +1;
				$sql = "select ID,User,Flag from flow_step where SmallID='$nextSmallid' and Wf_task_ID=" . $taskid;
				$objectApp = $this->_db->getArray($sql);
				if ($objectApp[0] && is_array($objectApp[0])) {
					//next step
					$nextChecker = $objectApp[0]['User'];
					$nextStepID = $objectApp[0]['ID'];
					$sql = "INSERT flow_step_partent set StepID='$nextStepID',SmallID='$nextSmallid',Wf_task_ID='$taskid',User='$nextChecker',Flag='0',START=now()";
					$this->query($sql);
					$sql = "update flow_step set Flag='0' where Wf_task_ID='$taskid' and SmallID='$nextSmallid'";
					$this->query($sql);
					//邮件通知
					if ($object['isSendNext']==1) {
						$Address = $gls->get_email(trim($nextChecker,','));
						$Address1 = $gls->get_email($this->HrManager);
						$body='<p>尊敬的领导：<br />
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 您好。<br />
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 现有' . $object['deptName'] . ' &nbsp;&nbsp; ' . $object['userName'] . '员工的劳动合同即将期满，现就员工的劳动合同期满续签事宜向您征询意见。敬请您在收到本邮件提醒后<span style="color:#F00">三个工作日内，即'.date('m-d',strtotime('+5 day')).'前</span>登陆OA系统审核并批复续签意见。人力资源部将根据您的意见为员工办理续签或离职手续。<br /><b>OA栏目路径:个人办公-->工作任务-->我的审批-->合同续签审批</b><br />
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 非常感谢。<br />
																		<br />
																		<br />
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;珠海世纪鼎利通信科技股份有限公司<br />
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;人力资源部<br />
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.date('Y').'&nbsp;年&nbsp; '.date('m').'&nbsp; 月&nbsp; '.date('d').'&nbsp; 日<br />
																		&nbsp;</p>';										
						$body .= '<div><hr /><br/><font size="2" >';
					    $body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					    $body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font>';	    
						$flag = $semails->send('您有新合同续签通知', $body, $Address);
						        $semails->send('您有新合同续签通知', $body, $Address1);

					}
					//邮件通知
				if ($object['isSend']==1) {
					$Address = $gls->get_email($this->HrManager);
					$body='<div><span style="font-size: small">您好！</span></div><br/>
											<div>' . $object['deptName'] . '&nbsp;&nbsp; ' . $object['userName'] . '的劳动合同 </div><div><span style="font-size: small">' . $object['recorderName'] . '已审批，</span></div><br />
											';
												
					$body .= '<div>请登陆OA查阅</div><div><hr /><br/><font size="2" >';
					$body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					$body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></div>';	 
					$flag = $semails->send('续签已经审批通知', $body, $Address);

				}

				} else {
					//update wf_task
					$sql = "update wf_task set Status='ok',finish=now() where task='$taskid'";
					$this->query($sql);
					//执行SQL
					$sql = "select PassSqlCode , name from wf_task where task='$taskid'";
					$objectWF = $this->_db->getArray($sql);
					if ($objectWF[0]['PassSqlCode'] != "") {
						$this->query(stripslashes($objectWF[0]['PassSqlCode']));
					}
					
					//邮件通知
					if ($object['isSend']==1) {
						$Address = $gls->get_email($this->HrManager);
						$body='<div><span style="font-size: small">您好！</span></div><br/>
												<div>' . $object['deptName'] . '&nbsp;&nbsp; ' . $object['userName'] . '的劳动合同 </div><div><span style="font-size: small">' . $object['recorderName'] . '已审批，</span></div><br />
												';
													
						$body .= '<div>请登陆OA查阅</div><div><hr /><br/><font size="2" >';
						$body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
						$body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></div>';	 
						$flag = $semails->send('续签审批已完成通知', $body, $Address);
	
					}
					if($object['recontractId']){
						$lv=$this->getFlowType($object['recontractId']);
						if($lv<40){
							$Address1 = $gls->get_email('tianlin.zhang');
							$flag = $semails->send('续签审批已完成通知', $body, $Address1);
						}
					}
					
				}
				//update current step information
				$sql_fsp = "update flow_step_partent set Flag='1',user='".$_SESSION['USER_ID']."',Result='$result',Content='" . $object['conContent'] . "',Endtime=now() where ID='" . $object['fspId']."'";
				$this->query($sql_fsp);
				$sql_hra = "update hr_recontract_approval set flow_step_id='$StepID' where ID=" . $object['approvalId'];
				$this->query($sql_hra);
				

			}

		}

	}
	
	/**
	检查审批流是否生效
	*/
	function checkFlow($pid){
		$sql="SELECT p.id FROM `flow_step_partent` p 
					LEFT JOIN wf_task t on (p.Wf_task_ID = t.task)
					where 
					t.finish is null 
					and p.Endtime is null 
					and p.Flag !=1
					and p.id ='$pid'  ";
		$res = $this->_db->get_one($sql);
		if(!empty($res)){
			return 'yes';
		}else{
			return 'no';
		}
	}
	
	function sendMails($pid, $stat) {
		$gls = new includes_class_global();
		$semails = new includes_class_sendmail();
		if ($pid) {
			$obj = $this->getApprovealInfo($pid);
			if ($stat == 3) {
					$Address1 = $gls->get_email($this->HrManager);
					$Address = $gls->get_email($obj[0]['userAccount']);
					$body = '
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span style="FONT-FAMILY: 宋体">尊敬的</span><span lang="EN-US">' . $obj[0]['userName'] . '</span><span style="FONT-FAMILY: 宋体">：</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span lang="EN-US">&nbsp;</span></p>
<p style="TEXT-ALIGN: justify; TEXT-INDENT: 21pt; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span style="FONT-FAMILY: 宋体">您的劳动合同将于</span><span lang="EN-US">' . $obj[0]['ocloseDate'] . '</span><span style="FONT-FAMILY: 宋体">到期，感谢您一直以来对公司的辛勤付出，以及对公司一如既往的认可。</span></p>
<p style="TEXT-ALIGN: justify; TEXT-INDENT: 21pt; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; TEXT-INDENT: 21pt; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span style="FONT-FAMILY: 宋体">现就合同续签事宜，提前向您做如下反馈并征询您的续签意见。</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;&nbsp;</strong></span><strong><span style="FONT-FAMILY: 宋体">续签的劳动合同有关劳动薪酬、福利、工作条件等内容及约定与目前一致，合同具体签订信息，待您返回个人续签意见后通知。</span></strong></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="FONT-FAMILY: 宋体">敬请您在收到本邮件后<span style="COLOR: rgb(255,0,0)">十个工作日(即：'.date('m-d',strtotime('+14 day')).')</span>内登陆</span><span lang="EN-US">OA</span><span style="FONT-FAMILY: 宋体">系统填写您的续签意见。逾期未登陆填写返回，公司将视为您不同意本次续签。盼您尽早返回个人续签意见，以便我们及时为您办理相关手续，保障您的切身利益不受影响。</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span style="FONT-FAMILY: 宋体">说明：</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span style="FONT-FAMILY: 宋体">劳动合同续签意愿有两个选项供您选择：</span><span lang="EN-US">1</span><span style="FONT-FAMILY: 宋体">、同意；</span><span lang="EN-US">2</span><span style="FONT-FAMILY: 宋体">、不同意。</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span lang="EN-US">1</span><span style="FONT-FAMILY: 宋体">、如果您同意续签，公司将在您期满之日起一个月内与您签订纸质合同，届时还请配合签订。</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span lang="EN-US">2</span><span style="FONT-FAMILY: 宋体">、如果您不同意续签，即将被视为个人申请离职，敬请登录如下路径申请离职（</span><span lang="EN-US">OA--</span><span style="FONT-FAMILY: 宋体">个人办公</span><span lang="EN-US">--</span><span style="FONT-FAMILY: 宋体">申请</span><span lang="EN-US">--</span><span style="FONT-FAMILY: 宋体">人事类</span><span lang="EN-US">--</span><span style="FONT-FAMILY: 宋体">离职）人力资源部将据此为您办理按期终止劳动合同手续，您的工资结算至' . $obj[0]['ocloseDate'] . '</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span style="FONT-FAMILY: 宋体">敬请登陆</span><span lang="EN-US">OA</span><span style="FONT-FAMILY: 宋体">栏目路径</span><span lang="EN-US">:</span><span style="FONT-FAMILY: 宋体;COLOR: rgb(255,0,0)">个人办公</span><span lang="EN-US" style="COLOR: rgb(255,0,0)">--&gt;</span><span style="FONT-FAMILY: 宋体;COLOR: rgb(255,0,0)">申请</span><span lang="EN-US" style="COLOR: rgb(255,0,0)">|</span><span style="FONT-FAMILY: 宋体;COLOR: rgb(255,0,0)">查询</span><span lang="EN-US">--&gt;</span><span style="FONT-FAMILY: 宋体;COLOR: rgb(255,0,0)">人事类</span><span lang="EN-US">--&gt;</span><span style="FONT-FAMILY: 宋体;COLOR: rgb(255,0,0)">合同续签</span>回复您的续签意见，感谢您的配合！</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal">&nbsp;</p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span style="FONT-FAMILY: 宋体">不明之处，敬请联系人力资源部牛雪迪（</span><span lang="EN-US">xuedi.niu@dinglicom.com</span><span style="FONT-FAMILY: 宋体">）</span></p>
<p style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt" class="MsoNormal"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
<p class="MsoNormal" style="text-align: center; margin: 0px 0cm; font-family: Times New Roman; font-size: 10.5pt;"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="FONT-FAMILY: 宋体">珠海世纪鼎利通信科技股份有限公司</span></p>
<p class="MsoNormal" style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
<p class="MsoNormal" style="text-align: center; margin: 0px 0cm; font-family: Times New Roman; font-size: 10.5pt;"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="FONT-FAMILY: 宋体">人力资源部</span></p>
<p class="MsoNormal" style="text-align: center; margin: 0px 0cm; font-family: Times New Roman; font-size: 10.5pt;"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
<p class="MsoNormal" style="text-align: center; margin: 0px 0cm; font-family: Times New Roman; font-size: 10.5pt;">&nbsp;</p>
<p class="MsoNormal" style="text-align: center; margin: 0px 0cm; font-family: Times New Roman; font-size: 10.5pt;"><span lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y').'</span><span style="FONT-FAMILY: 宋体">年</span><span lang="EN-US">&nbsp;&nbsp;'.date('m').'&nbsp;</span><span style="FONT-FAMILY: 宋体">月</span><span lang="EN-US">&nbsp;&nbsp;'.date('d').'&nbsp;&nbsp;</span><span style="FONT-FAMILY: 宋体">日</span></p>
<p class="MsoNormal" style="TEXT-ALIGN: justify; MARGIN: 0px 0cm; FONT-FAMILY: Times New Roman; FONT-SIZE: 10.5pt">&nbsp;</p>';											
					$body .= '<div><hr /><br/><font size="2" >';
					$body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					$body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></div>';
					$flag = $semails->send('劳动合同续签意见征询函', $body, $Address);
					$semails->send('劳动合同续签意见征询函', $body, $Address1);
				
			}else if ($stat == 5) {
				$Address = $gls->get_email($obj[0]['userAccount']);
				$Address1 = $gls->get_email($this->HrManager);
					$body = '<p>尊敬的' . $obj[0]['userName'] . ' ：<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 您的劳动合同将于' . $obj[0]['ocloseDate'] . ' 到期，现就合同续签事宜，征询您的续签意见。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 敬请您在收到<span style="color:#F00">本邮件后一周内</span>登陆OA系统填写您的续签意见。逾期未登陆填写返回，公司将视为您同意本次续签。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;不明之处，敬请联系人力资源部0756-3626163 <br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 珠海世纪鼎利通信科技股份有限公司&nbsp; <br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.date('Y').'&nbsp;年&nbsp; '.date('m').'&nbsp; 月&nbsp; '.date('d').'&nbsp; 日<br />
															&nbsp;</p>';
					$body .= '<br /><div>OA栏目路径:个人办公-->申请|查询-->人事类-->合同续签<br /></div>';												
					$body .= '<div><hr /><br/><font size="2" >';
					$body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					$body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></div>';
					$flag = $semails->send('劳动合同续签意见征询函', $body, $Address);
							 $semails->send('劳动合同续签意见征询函', $body, $Address1);
				

			}
			if ($stat == 7) {
				$Address = $gls->get_email($obj[0]['userAccount']);
				$Address1 = $gls->get_email($this->HrManager);
				if ($obj[0]['isFlag'] == 1) {
					if($obj[0]['conNum']=='N'){
						$body = '<p>尊敬的' . $obj[0]['userName'] . ' ：<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 公司与您签订的上一份合同将于' . $obj[0]['ocloseDate'] . '到期，经征询您本人及公司的续签意见，双方同意续签劳动合同。新合同为无固定期限合同，合同签订方为'.$obj[0]['signCompanyName'].'。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部将于近期联系您办理续签手续，感谢您的配合和对公司的认可。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 珠海世纪鼎利通信科技股份有限公司<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y').'&nbsp;年&nbsp; '.date('m').'&nbsp; 月&nbsp; '.date('d').'&nbsp; 日<br />
															&nbsp;</p>';
					
					}else{
						$body = '<p>尊敬的' . $obj[0]['userName'] . ' ：<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 公司与您签订的上一份合同将于' . $obj[0]['ocloseDate'] . '到期，经征询您本人及公司的续签意见，双方同意续签劳动合同。新合同期限为'.$obj[0]['conNumName'].'，合同签订方为'.$obj[0]['signCompanyName'].' ，合同起止时间为'.$obj[0]['beginDate'].' 到'.$obj[0]['closeDate'].'  。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部将于近期联系您办理续签手续，感谢您的配合和对公司的认可。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 珠海世纪鼎利通信科技股份有限公司<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y').'&nbsp;年&nbsp; '.date('m').'&nbsp; 月&nbsp; '.date('d').'&nbsp; 日<br />
															&nbsp;</p>';
					
					}
					$body .= '<br /><div>OA栏目路径:个人办公-->申请|查询-->人事类-->合同续签<br /></div>';												
					$body .= '<div><hr /><br/><font size="2" >';
					$body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					$body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></div>';

					$flag = $semails->send('劳动合同续签通知书', $body, $Address);
					$flag = $semails->send('劳动合同续签通知书', $body, $Address1);
				}/* else {
					$body = '<p>尊敬的' . $obj[0]['userName'] . ' ：<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 公司与您在' . $obj[0]['obeginDate'] . '日签订的合同期限为' . $obj[0]['oconNumName'] . '的劳动合同，将于' . $obj[0]['ocloseDate'] . '日到期，公司决定不再续签劳动合同，请您于' . $obj[0]['ocloseDate'] . '日之前办理完毕工作交接并到人力资源部及相关部门办理相关离职手续，双方的劳动关系于' . $obj[0]['ocloseDate'] . '日终止。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部将于近期联系您为您办理续签手续，感谢您的配合和对公司的认可。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 珠海世纪鼎利通信科技股份有限公司<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y').'&nbsp;年&nbsp; '.date('m').'&nbsp; 月&nbsp; '.date('d').'&nbsp; 日<br />
															&nbsp;</p>';
					$body .= '<div>请登陆OA查阅</span></div><div>&nbsp;</div> <hr /><p><br />
																										<font size="2">内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br />
																											<br />
																									    外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></p>';

					$flag = $semails->send('劳动合同期满不续签通知书', $body, $Address);
				}
				*/

			}
			if ($stat == '8'&&$obj[0]['isFlag'] =='2') {
				$Address = $gls->get_email($obj[0]['userAccount']);
				$Address1 = $gls->get_email($this->HrManager);
				$body = '<p>尊敬的' . $obj[0]['userName'] . ' ：<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 公司与您在' . $obj[0]['obeginDate'] . '签订的合同期限为' . $obj[0]['oconNumName'] . '的劳动合同，将于' . $obj[0]['ocloseDate'] . '到期，公司决定不再续签劳动合同，请您于' . $obj[0]['ocloseDate'] . '之前办理完毕工作交接并到人力资源部及相关部门办理相关离职手续，双方的劳动关系于' . $obj[0]['ocloseDate'] . '终止。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部将于近期联系您办理续签手续，感谢您的配合和对公司的认可。<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 珠海世纪鼎利通信科技股份有限公司<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 人力资源部<br />
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y').'&nbsp;年&nbsp; '.date('m').'&nbsp; 月&nbsp; '.date('d').'&nbsp; 日<br />
															&nbsp;</p>';
					$body .= '<br /><div>OA栏目路径:个人办公-->申请|查询-->人事类-->合同续签<br /></div>';												
					$body .= '<div><hr /><br/><font size="2" >';
					$body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					$body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></div>';
					$flag = $semails->send('劳动合同期满不续签通知书', $body, $Address);
					$flag = $semails->send('劳动合同期满不续签通知书', $body, $Address1);
			}

		}

	}
  function sendMailToApp($pid,$sendUser){
  	   $gls = new includes_class_global();
		$semails = new includes_class_sendmail();
		if ($pid) {
			$obj = $this->getApprovealInfo($pid);
			$sendUsers=$this->HrManager;
				array_push($sendUsers,$sendUser);
				$Address = $gls->get_email($sendUsers);
					$body = '<p>尊敬的' . $this->getUserStrName($sendUser) . ' ：<br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 您好。<br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 现有' . $obj[0]['deptName'] . '&nbsp;&nbsp;' . $obj[0]['userName'] . '的劳动合同将于<font style="color:#F00">' . $obj[0]['ocloseDate'] . '</font>期满，现就员工的劳动合同期满续签事宜向您征询意见。敬请您在收到本邮件提醒后<span style="color:#F00">十天内，即'.date('m-d',strtotime('+10 day')).'前</span>登陆OA系统审核并批复续签意见。人力资源部将根据您的意见为员工办理续签或离职手续。<b>另HR建议：可藉此续签机会，根据部门实际情况，适当选择与续签员工做面谈沟通。</b><br /><b>OA栏目路径:个人办公-->工作任务-->我的审批-->合同续签审批</b><br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 非常感谢。<br /><br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;珠海世纪鼎利通信科技股份有限公司<br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;人力资源部<br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.date('Y').'&nbsp;年&nbsp; '.date('m').'&nbsp; 月&nbsp; '.date('d').'&nbsp; 日<br />
							&nbsp;</p>';											
					$body .= '<div><hr /><br/><font size="2" >';
					$body .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					$body .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font></div>';
					$flag = $semails->send('劳动合同续签意见征询函', $body, $Address);
			
		}
  	
  }
	/**
		 * 获取采购订单的其他详细信息
		 */
	function getApprovealInfo($pid) {

		if ($pid) {
			$sql = "SELECT  a.statusId,a.userName,a.deptName,a.deptId,a.jobName,a.jobId,a.obeginDate,a.ocloseDate,a.oconNum,a.oconNumName,a.oconState,a.oconStateName,a.companyId,a.signCompanyName,a.aconNum,b.*
					FROM `hr_recontract` a LEFT JOIN hr_recontract_approval b ON  (a.id=b.recontractId)
					WHERE  b.recontractId ='$pid'  ORDER BY b.id DESC  LIMIT 1 ";
			$object = $this->_db->getArray($sql);
		}
		return $object;
	}
function getRecontractInfo($pid) {

		if ($pid) {
			$sql = "SELECT a.*
					FROM `hr_recontract` a 
					WHERE  a.id ='$pid'";
			$object = $this->_db->getArray($sql);
		}
		return $object;
	}
    function getApprovalInfoApp($pid){
        if ( $pid) {
			$sql = "SELECT  a.Item,a.SmallID,a.`User`,c.isFlagName,c.createTime,c.createName,c.conContent
						FROM `flow_step` a  LEFT JOIN wf_task  b ON  (a.Wf_task_ID=b.task)
						LEFT JOIN  hr_recontract_approval  c ON  (c.flow_step_id=a.ID)
						WHERE   b.`name`='合同续签'  AND b.`code`='hr_recontract' AND  b.Pid ='$pid'   ";
			$object = $this->_db->getArray($sql);
			if($object&&is_array($object)){
				foreach($object as $key=>$val){
					$object[$key]['User']=$this->getUserStrName($val['User']);
				}
			}
		}
		return $object;
    	
    }
function getUserStrName($userIdStr){
        if ( $userIdStr) {
        	$userIdStrI=explode(",",$userIdStr);
        	if($userIdStrI&&is_array($userIdStrI)){
        		$userIdStrT=implode("','",$userIdStrI);
        		if($userIdStrT){
        			$sql = "SELECT USER_NAME 
							FROM  user 
							WHERE  user_id in ('$userIdStrT')";	
						$object = $this->_db->getArray($sql);
						foreach($object as $key=>$val){
							$dataI[]=$val['USER_NAME'];
						}
						$userNameStr=implode(',',(array)$dataI);
        		}
        	}
        	
			
		}
		return $userNameStr;
    	
    }

    /*选择工作流
	 * 
	  
	 * */
	function selectFlow($billId, $examCode = '', $flowType = '', $formName = '', $passSqlCode = '', $flowDept = '', $flowMoney ='',$companyId='') {
		if ($billId) {
			$sqlStr = "";
			if ($flowMoney != 0) {
				$sqlStr .= " and  t.MinMoney<=" . $flowMoney . " and ( t.MaxMoney>=" . $flowMoney . " or t.MaxMoney=0  )";
			}
			if ($flowType != "") {
				$sqlStr .= " and t.COST_FLOW_TYPE='$flowType' ";
			}
			if ($formName != "") {
				$sqlStr .= " and ft.FORM_NAME='$formName' ";
			}
			if (isset ($flowDept) && $flowDept != "") {
				$sqlStr .= " and ( find_in_set('$flowDept',t.FLOW_DEPTS)>0 or t.FLOW_DEPTS='ALL_DEPT' or t.FLOW_DEPTS='' )";
			}
			$sql = "select t.FLOW_ID,t.FLOW_NAME from flow_type t,flow_form_type ft where ft.FORM_ID=t.FORM_ID $sqlStr ";
			$objectSel = $this->_db->getArray($sql);
			$flowI['billId'] = $billId;
			$flowI['flowId'] = $objectSel[0]['FLOW_ID'];
			$flowI['examCode'] = $examCode;
			$flowI['flowType'] = $flowType;
			$flowI['formName'] = $formName;
			$flowI['passSqlCode'] = $passSqlCode;
			$flowI['flowDept'] = $flowDept;
			$flowI['companyId'] = $companyId;
			return $flowI;
		}

	}
	/*建立工作流
	 * 
	 * 
	 
	 * 
	 * */
	function ewfBuild($billI) {
		if ($billI&&is_array($billI)) {
			set_time_limit (0);
			$billId=$billI['billId'];
			$FLOW_ID=$billI['flowId'];
			$examCode=$billI['examCode'];
			$flowType=$billI['flowType'];
			$formName=$billI['formName'];
			$passSqlCode=$billI['passSqlCode'];
			$billDept=$billI['flowDept'];
			$billCompanyId=$billI['companyId'];
			$email=true;
			$scFlag=false;
			try {
				$this->query("START TRANSACTION");
				$sql = "select ID from $examCode where ID='$billId' ";
				$chick1 = $this->_db->getArray($sql);
				if (!$chick1[0]['ID']) {
					throw new Exception("审批对象数据不存在！");
				}
				$sql = "select task from wf_task where name='$formName' and code='$examCode' and Pid='$billId' and Status='ok' and DBTable='" . $_SESSION["COM_BRN_SQL"] . "' ";
				$chick2 = $this->_db->getArray($sql);
				if ($chick2[0] && is_array($chick2[0])) {
					throw new Exception("审批对象已经存在同类型为：“ $formName ”的审批单！");
				}
                /*
				//更新信息 给予打回 事务控制
				$sql = "update $examCode set ExaStatus = '部门审批' , ExaDT = now() where ID='$billId' ";
				$this->query($sql);
				$sql = "select ClassID,FORM_ID, FLOW_NAME from flow_type where FLOW_ID=" . $FLOW_ID;
				$chick3 = $this->_db->getArray($sql);
				$form = 0;
				if ($chick3[0]['ClassID']) {
					$sql = "update wf_class set Ccount=Ccount+1 where class_id=" . $chick3[0]['ClassID'];
					$form = $chick3[0]['FLOW_NAME'];
					$this->query($sql);
				}
*/
				//according to the work_flow create work_task,work_step
				$sql = " insert into wf_task set Creator='" . $_SESSION['USER_ID'] . "', Enter_user='" . $_SESSION['USER_ID'] . "', name='$formName', code='$examCode', form='$form', start=now(),train=" . $FLOW_ID . ", Status='ok',Pid='" . $billId . "', PassSqlCode='" . addslashes($passSqlCode) . "',  DBTable='" . $_SESSION["COM_BRN_SQL"] . "' ";
				$this->query($sql);
				$taskid = $this->_db->insert_id();
				//生成 --部门审批---- 每个步骤
				$Flag = 0;
				$sql = "select * from flow_process where FLOW_ID='" . $FLOW_ID . "' order by PRCS_ID";
				$firstChecker = "";
				$r = $this->_db->getArray($sql);
				$Smallval = 1;
				$stepTotals = count($r);
				$stepNum = 0;
				if ($r) {
					$prcsalerts = 0;
					foreach ($r as $ra) {
						$stepNum++;
						//过滤公司步骤
						$prcs_com = $ra['btcom'];
						if (!empty ($prcs_com) && $prcs_com != $_SESSION['USER_COM']) {
							continue;
						}
						$AC_Users = "";
						$wherestr = "";
						//如果经办人为空才根据部门和角色选择审批者
						$AC_Users = "";
						if ($ra['PRCS_USER'] != NULL) {
							$AC_Users = $ra['PRCS_USER'];
						}
						if ($ra['PRCS_PRIV'] != null && $ra['PRCS_DEPT'] != null) {
							$wherestr .= "or USER_PRIV in(" . rtrim($ra['PRCS_PRIV'], ",") . ") or DEPT_ID in (" . rtrim($ra['PRCS_DEPT'], ",") . ") ";
						}
						if ($ra['PRCS_PRIV'] != null) {
							$wherestr .= "or  USER_PRIV in(" . rtrim($ra['PRCS_PRIV'], ",") . ") ";
						}
						if ($ra['PRCS_DEPT'] != null) {
							$wherestr .= "or DEPT_ID in (" . rtrim($ra['PRCS_DEPT'], ",") . ") ";
						}
						if ($ra['PRCS_SPEC'] != NULL) {
							$PRCS_SPEC_ARR = explode(",", rtrim($ra['PRCS_SPEC'], ','));

							$specids = "";
							for ($i = 0; $i < count($PRCS_SPEC_ARR); ++ $i) {
								if ($PRCS_SPEC_ARR[$i] == '@bmld') //部门领导
									{
									if (isset ($billDept)) {
										$tempUserIdX = "";
										$sql = "select a.Leader_id from department a where  a.DEPT_ID='$billDept'";
										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {
											$tempUserIdX .= $row["Leader_id"];
										}
										$specids .= $specids == "" ? $this->towhere($tempUserIdX) : "," . $this->towhere($tempUserIdX);
									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@bmjl') //所有区域经理
									{
									if (isset ($billDept)) {
										$tempUserIdX = "";
										$sql = "select a.LeaderId from area_leader a where  a.DEPT_ID='$billDept' ";
										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {
											$tempUserIdX .= $row["LeaderId"];
										}

										$specids .= $specids == "" ? $this->towhere($tempUserIdX) : "," . $this->towhere($tempUserIdX);
									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@qyjl') {
									//区域经理
									if (isset ($billDept)) {
										if (!empty ($billArea)) {
											$sql = "select a.LeaderId from area_leader a where a.DEPT_ID='$billDept' and a.AreaId='$billArea'";
										} else {
											$sql = "select a.LeaderId from area_leader a,user u where u.AREA=a.AreaId and a.DEPT_ID='$billDept' and u.USER_ID='" . $_SESSION['USER_ID'] . "'";
										}
										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {
											$specids .= $specids == "" ? $this->towhere($row["LeaderId"]) : "," . $this->towhere($row["LeaderId"]);
										}

									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@xmjl') {
									//找出真正的项目经理
									//$xmjlids = $fsql->getrow("select x.Manager from cost_summary_list l,xm_lx x where l.ProjectNo=x.ProjectNo and l.xm_sid = x.SID and l.BillNo='".$BillNo."'");
									if (isset ($proSid) && $proSid != "") {
										$sql = "select x.Manager from xm_lx x where x.ProId='" . $proId . "' order by Flag , BeginDate desc limit 0 , 1  ";
									}
									elseif (isset ($proId) && $proId != "") {
										$sql = "select x.Manager from xm_lx x where x.ProId='" . $proId . "' order by Flag , BeginDate desc limit 0 , 1  ";
									} else {
										continue;
									}
									$query = $this->query($sql);
									while ($row = $this->fetch_array($query)) {
										$specids .= $specids == "" ? $this->towhere($row["Manager"]) : "," . $this->towhere($row["Manager"]);
									}

								}
								if($PRCS_SPEC_ARR[$i]=='@mord')//经理或总监审批流
						            {
						                if(empty($billDept)){
						                    $billDept=$_SESSION['DEPT_ID'];
						                }
						                $billArea=trim($billArea);
						                //部门经理
						                if(!empty($billCompanyId)){
						                    $querya=$this->query("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCompanyId' limit 1");
						                    while ($row = $this->fetch_array($querya)) {
												if($row['userid'])
								                {
								                	if(trim($row['manager'],',')){
							                			 $ckarray[]=trim($row['manager'],',');
								                        $ckname[trim($row['manager'],',')]='部门负责人';
								                     }
								                     if(trim($row['userid'],',')){
							                			 $ckarray[]=trim($row['userid'],',');
								                        $ckname[trim($row['userid'],',')]='部门经理';
								                     }
								                       
								                }
										    } 
						                }
		 			                   $querys=$this->query("select d.MajorId , d.ViceManager , d.otherman  from department d where d.DEPT_ID='$billDept' ");
						                while ($row = $this->fetch_array($querys)) {
						                	 $ckarray[]=trim(($row['MajorId']?$row['MajorId']:$row['ViceManager']),',');
						                     $ckname[trim(($row['MajorId']?$row['MajorId']:$row['ViceManager']),',')]='部门总监';
						                     $om=$row['otherman'];
						                     if(!empty($om)){
						                    	$ckarray[]=trim($om,',');
						                    	$ckname[trim($om,',')]='部门领导';
			                   				 }
			                			}
						                $ckarray = array_diff($ckarray, array(null,''));
						                $ckarray = array_unique($ckarray);
						                $ckarray = array_values($ckarray);
						                if($ckarray[0]&&$ckarray[0]){
						                	 $ra['PRCS_NAME'] = $ckname[$ckarray[0]];
											 $specids .= $specids == "" ? $this->towhere($ckarray[0]) : "," . $this->towhere($ckarray[0]);
						                }
						            }
								if ($PRCS_SPEC_ARR[$i] == '@bmzj') {
									//部门总监
									if (isset ($billDept)) {
										$sql = "select d.MajorId , d.ViceManager  from department d where d.DEPT_ID='$billDept' ";
										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {
											$bmzj = $row["MajorId"];
											if (empty ($bmzj)) {
												$bmzj = $row["ViceManager"];
											}
											$specids .= $specids == "" ? $this->towhere($bmzj) : "," . $this->towhere($bmzj);
										}

									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@bmfz') {
									//部门副总/总经理
									if (isset ($billDept)) {
										$sql = "select a.ViceManager from department a where a.DEPT_ID='$billDept' ";
										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {
											if ($row["ViceManager"] != "") {
												$specids .= $specids == "" ? $this->towhere($row["ViceManager"]) : "," . $this->towhere($fsql->f("ViceManager"));
											} else {
												$sql = "select u.USER_ID from user u, user_priv p where u.USER_PRIV=p.USER_PRIV and p.PRIV_NAME='总经理' ";
												while ($rows = $this->fetch_array($query)) {
													$specids .= $specids == "" ? $this->towhere($rows["USER_ID"]) : "," . $this->towhere($rows["USER_ID"]);
												}

											}
										}

									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@bmfzj') {
									//部门fu总监
									if (isset ($billDept)) {

										$sql = "select d.MajorId , d.vicemagor , d.ViceManager from department d where d.DEPT_ID='$billDept' ";
										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {

											$bmfzj = $row["vicemagor"];
											if (empty ($bmfzj)) {
												$bmfzj = $row["MajorId"];
											}
											if (empty ($bmfzj)) {
												$bmfzj = $row["ViceManager"];
											}
											$specids .= $specids == "" ? $this->towhere($bmfzj) : "," . $this->towhere($bmfzj);
										}
									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@gcqy') {
									//副zongjian
									if (isset ($examCode)) {
										if (trim($examCode) == 'oa_esm_project') {
											$sql = "SELECT m.usercode FROM 
											                                     oa_esm_project p 
											                                    left join oa_esm_office_managerinfo m on (p.officeid=m.officeid) 
											                                    where p.id='" . $billId . "' ";
										} else {
											$sql = "SELECT m.usercode FROM oa_esm_change_baseinfo cb
											                                    left join oa_esm_project p on (cb.projectid=p.id)
											                                    left join oa_esm_office_managerinfo m on (p.officeid=m.officeid)
											                                    where cb.id='" . $billId . "' ";

										}

										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {
											$specids .= $specids == "" ? $this->towhere($row["usercode"]) : "," . $this->towhere($row["usercode"]);
										}

									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@htsp') {
									//副zongjian                
									if ($sellCon) {
										foreach ($sellCon as $ckey => $cval) {
											if (in_array($cktype, $cval)) {
												$specids .= $specids == "" ? $this->towhere($ckey) : "," . $this->towhere($ckey);
											}
										}
									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@wgcqy') {
									//工程区域办事处经理
									if ($billArea) {
										$sql = "SELECT managerCode FROM oa_esm_office_baseinfo 
										                                    where id='$billArea' ";
										$query = $this->query($sql);
										while ($row = $this->fetch_array($query)) {
											$specids .= $specids == "" ? $this->towhere($row["managerCode"]) : "," . $this->towhere($row["managerCode"]);
										}

									} else {
										continue;
									}
								}
								if ($PRCS_SPEC_ARR[$i] == '@bmauto') //部门领导审批流
									{
									$ckarray = array ();
									$ckname = array ();
									$flowMoneyArr = array ();
									$ckMoney = explode(',', $ckMoney);
									$flowMoneyArr['部门经理'] = $ckMoney[0];
									$flowMoneyArr['部门总监'] = $ckMoney[1];
									$flowMoneyArr['部门副总'] = $ckMoney[2];
									if (empty ($billDept)) {
										$billDept = $_SESSION['DEPT_ID'];
									}
									$billArea = trim($billArea);
									//部门经理
									if (!empty ($billCom)) {
										$sql = "select a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'";
									} else {
										$sql = "select a.userid from dept_com a,user u where u.company=a.compt 
										                                    and a.dept='$billDept' and u.USER_ID='" . $_SESSION['USER_ID'] . "'";
									}
									$query = $this->query($sql);
									while ($row = $this->fetch_array($query)) {
										$ckarray[] = trim($row["userid"], ',');
										$ckname[trim($row["userid"], ',')] = '部门经理';
									}

									$sql = "select d.MajorId , d.ViceManager from department d where d.DEPT_ID='$billDept' ";
									$query = $this->query($sql);
									while ($row = $this->fetch_array($query)) {
										$ckarray[] = trim($row['MajorId'], ',');
										$ckname[trim($row['MajorId'], ',')] = '部门总监';
										$ckarray[] = trim($row['ViceManager'], ',');
										$ckname[trim($row['ViceManager'], ',')] = '部门副总';
									}
									if (!empty ($flowMoney) && $flowMoney != ' ') {
										$ckarray[] = 'dafa.yu';
										$ckname['dafa.yu'] = '总经理';
									}
									$ckarray = array_diff($ckarray, array (
										null,
										''
									));
									$ckarray = array_unique($ckarray);
									$ckarray = array_values($ckarray);
									if (!empty ($ckarray)) {
										$specids .= $specids == "" ? $this->towhere($ckarray[0]) : "," . $this->towhere($ckarray[0]);
										$ra['PRCS_NAME'] = $ckname[$ckarray[0]];
										$ckMoney = $flowMoneyArr[$ckname[$ckarray[0]]];
									} else {
										continue;
									}
									//print_r($ckarray);
									//print_r($ckname);
								}

								//            其他部门领导
								if ($PRCS_SPEC_ARR[$i] == '@qtbmld') {
									$billDepts = explode(',', $billDept);
									if (!empty ($billDepts)) {
										$ckarray = array ();
										$ckname = array ();
										foreach ($billDepts as $key => $val) {
											if (isset ($val)) {
												$sql = "select d.MajorId , d.vicemagor , d.ViceManager ,dept_name from department d where d.DEPT_ID='$val' ";
												$query = $this->query($sql);
												while ($row = $this->fetch_array($query)) {
													$bmfzj = $row["vicemagor"];
													$bmfzjn = $row['dept_name'] . '副总监';
													if (empty ($bmfzj)) {
														$bmfzj = $row["MajorId"];
														$bmfzjn = $row['dept_name'] . '总监';
													}
													if (empty ($bmfzj)) {
														$bmfzj = $row["ViceManager"];
														$bmfzjn = $row['dept_name'] . '副总';
													}
													$ckarray[] = trim($bmfzj, ',');
													$ckname[trim($bmfzj, ',')] = $bmfzjn;
												}
											}
										}
										$ckarray = array_diff($ckarray, array (
											null,
											''
										));
										$ckarray = array_unique($ckarray);
										$ckarray = array_values($ckarray);
										$ra['PRCS_NAME'] = $ckname[$ckarray[0]];
										$specids .= $specids == "" ? $this->towhere($ckarray[0]) : "," . $this->towhere($ckarray[0]);
									} else {
										continue;
									}
								}
								//混合部门领导
								if ($PRCS_SPEC_ARR[$i] == '@hhbmld') {
									$billDepts = explode(',', $billDept);
									if (!empty ($billDepts)) {
										$tempu = '';
										$tempn = '业务领导';
										foreach ($billDepts as $key => $val) {
											if (isset ($val)) {
												$sql = "select d.MajorId , d.vicemagor , d.ViceManager ,dept_name from department d where d.DEPT_ID='$val' ";
												$query = $this->query($sql);
												while ($row = $this->fetch_array($query)) {
													$bmfzj = $row["vicemagor"];
													$bmfzjn = $row['dept_name'] . '副总监';
													if (empty ($bmfzj)) {
														$bmfzj = $row["MajorId"];
														$bmfzjn = $row['dept_name'] . '总监';
													}
													if (empty ($bmfzj)) {
														$bmfzj = $row["ViceManager"];
														$bmfzjn = $row['dept_name'] . '副总';
													}
													$ckarray[] = trim($bmfzj, ',');
													$ckname[trim($bmfzj, ',')] = $bmfzjn;
												}

											}
										}
										$ra['PRCS_NAME'] = $tempn;
										$specids .= $specids == "" ? $this->towhere($tempu) : "," . $this->towhere($tempu);
									} else {
										continue;
									}
								}

								//            分步人员
								if ($PRCS_SPEC_ARR[$i] == '@qtry') {
									$billUsers = explode(',', $billUser);
									//print_r($billUsers);
									if (!empty ($billUsers)) {
										$ckarray = array ();
										$ckname = array ();
										foreach ($billUsers as $key => $val) {
											if ($val) {
												$ckarray[] = $val;
												$ckname[$val] = '部门领导';
											}
										}
										$ckarray = array_diff($ckarray, array (
											null,
											''
										));
										$ckarray = array_unique($ckarray);
										$ckarray = array_values($ckarray);
										$ra['PRCS_NAME'] = $ckname[$ckarray[0]];
										$specids .= $specids == "" ? $this->towhere($ckarray[0]) : "," . $this->towhere($ckarray[0]);
									} else {
										continue;
									}
								}
								//混合人员
								if ($PRCS_SPEC_ARR[$i] == '@hhry') {
									if (!empty ($billUser)) {
										$tempu = $billUser;
										$tempn = '业务领导';
										$ra['PRCS_NAME'] = $tempn;
										$specids .= $specids == "" ? $this->towhere($tempu) : "," . $this->towhere($tempu);
									} else {
										continue;
									}
								}

							}
							if ($specids != "") {
								$wherestr .= "or ( USER_ID in ($specids) or USER_NAME in ($specids) ) ";
							}
							if ($specids == $this->towhere($_SESSION['USER_ID']) && $stepNum < $stepTotals) {
								continue;
							}

						}

						if (!empty ($AC_Users)) {
							$wherestr .= "or ( USER_ID in (" . $this->towhere(trim($AC_Users, ',')) . ") ) ";
							$AC_Users = '';
							//writeToLog($wherestr,"yyyyy.txt");
						}
						if ($wherestr == "")
							$wherestr = " and USER_ID ='' ";
						else
							$wherestr = " and ( " .	trim($wherestr, "or") . " ) ";
						$sql = "select USER_ID from user where HAS_LEFT='0' and ( handcom is null or find_in_set('" . $billCompanyId . "',handcom) ) " . $wherestr;
						//echo $sql;br();
						$query = $this->query($sql);
						while ($row = $this->fetch_array($query)) {
							$AC_Users .= $row["USER_ID"] . ",";
						}
						if (empty ($AC_Users)) {
							$sql = "select USER_ID from user where HAS_LEFT='0' 
							              and company='" .$billCompanyId . "' " . $wherestr;
							$query = $this->query($sql);
							while ($row = $this->fetch_array($query)) {
								$AC_Users .= $row["USER_ID"] . ",";
							}
						}
						if (empty ($AC_Users)) {
							throw new Exception("data error" . $sql);
						}
						//
						$sql = "INSERT into flow_step set SmallID='" . $Smallval . "',Wf_task_ID='$taskid',Flow_id='$FLOW_ID',Step='$ra[PRCS_ID]'
						                    ,StepID='$ra[ID]',Item='$ra[PRCS_NAME]',User='$AC_Users',PRCS_ITEM='$ra[PRCS_ITEM]',Flag='$Flag'
						                    ,status='ok',Flow_name='$formName',secrecy='1',speed='1',quickpipe='0'
						                    ,Flow_prop='$ra[PRCS_PROP]',PRCS_ALERT='$ra[PRCS_ALERT]',Flow_doc='1',Flow_type='1'
						                    ,Start=now()  ";
       
						$this->query($sql);
						$stepidx = $this->_db->insert_id();
						if (!$stepidx)
							throw new Exception("dberror" . mysql_error());
						if ($Smallval == 1) {
							//第一步
							$firstChecker = $AC_Users;
							foreach (explode(",", $AC_Users) as $val) {
								if ($val != "") {
									$sql = "INSERT into flow_step_partent set StepID='$stepidx',SmallID='$Smallval',Wf_task_ID='$taskid',User='$val',Flag='$Flag',START=now(),pid='$billId'";
									$sccf=$this->query($sql);
									if($email){
										  $this->sendMailToApp($billId,$val);	
									}
								}
							}
							
							
						}
						//更新状态
						if($stepidx){
							$scFlag=$this->query("update hr_recontract SET ExaStatus='部门审批',statusId='3' where id='$billId' ");
						}
						
						if (!empty ($ckarray) && count($ckarray) > 1) {
							$cktrue = true;
							$cki = 1;
							$Flag++;
							++ $Smallval;
							foreach ($ckarray as $key => $val) {
								if ($cki != '1' && (empty ($flowMoney) || $flowMoney >= $ckMoney)) {
									$cktrue = false;
									$ckMoney = $flowMoneyArr[$ckname[$val]];
									$AC_Users = '';
									$sql = "select USER_ID from user where HAS_LEFT='0' and (user_id in ( ".$this->towhere($val,',').") OR USER_NAME IN ( ".$this->towhere($val,',').")) ";
									$query = $this->query($sql);
									while ($row = $this->fetch_array($query)) {
										$AC_Users .= $row["USER_ID"] . ",";
									}
									if (empty ($AC_Users)) {
										throw new Exception("data error" . $sql);
									}
									$sql = "INSERT into flow_step set SmallID='" . $Smallval . "',Wf_task_ID='$taskid',Flow_id='$FLOW_ID'
									                                ,Step='$ra[PRCS_ID]',StepID='$ra[ID]',Item='$ckname[$val]'
									                                ,User='$AC_Users',PRCS_ITEM='$ra[PRCS_ITEM]',Flag='$Flag',status='ok'
									                                ,Flow_name='$formName',secrecy='1',speed='1'
									                                ,quickpipe='0',Flow_prop='$ra[PRCS_PROP]'
									                                ,PRCS_ALERT='$ra[PRCS_ALERT]',Flow_doc='1',Flow_type='1'
									                                ,Start=now()";
                            
									$this->query($sql);
									$stepid = $this->_db->insert_id();
									if (!$stepid)
										throw new Exception("dberror" . mysql_error());
									$Flag++;
									++ $Smallval;
								}
								$cki++;
							}
							//throw new Exception('xxx'.$ckMoney.'22'.$flowMoney.$ckss) ;
							unset ($ckarray);
						} else {
							$Flag++;
							++ $Smallval;
						}
					}
				}
				$this->query("COMMIT");
				return $scFlag;
			} catch (Exception $e) {
				echo $e;
				$this->query("ROLLBACK");
			}
			
		}

	}
	 function towhere($str)
  {
    return "'".str_replace(",","','",rtrim($str,","))."'";
  }
	function getFlowType($rid){
		if($rid){
			$sql="SELECT  IF(b.UserLevel=0,5,b.UserLevel*10) as UserLevel,companyId  
				  FROM `hr_recontract` a LEFT JOIN hrms b ON  (a.userAccount=b.USER_ID)
				  WHERE  a.id ='$rid'";
			$object=$this->_db->getArray($sql);
		}
		return $object[0];
		
	}
	
	
}
?>