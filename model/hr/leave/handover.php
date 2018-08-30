<?php
/**
 * @author Administrator
 * @Date 2012-08-09 13:56:00
 * @version 1.0
 * @description:离职交接清单 Model层
 */
class model_hr_leave_handover  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_leave_handover";
		$this->sql_map = "hr/leave/handoverSql.php";
		parent::__construct ();
	}

	/**
	 * 重写add_d方法
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			//插入主表信息
			$newId = parent :: add_d($object, true);
			//插入从表信息
			//模板
			if (!empty ($object['formwork'])) {
				$Dao = new model_hr_leave_handoverlist();
				$handoverMemberDao=new model_hr_leave_handoverMember();
				$mailStr="";
				$mailArr=array();
				foreach($object['formwork'] as $key=>$val){
					if(!empty($val['items'])){
						$val['handoverId']=$newId;
						$listId=$Dao->add_d($val);
						if(!empty($val['recipientId'])){
							$emailStr.=$val['recipientId'].",";
							$recipientName=explode(",",$val['recipientName']);
							$recipientId=explode(",",$val['recipientId']);
							//循环面谈者，分别插入从表
							foreach($recipientId as $mKey =>$mVal){
								if($mVal!=""){
									array_push($mailArr,$mVal);
									$newArr=array("handoverId"=>$newId,'parentId'=>$listId);
									$itemsArr=array();
									$interviewerArray = array();
									$interviewerArray=array("recipientId"=>$mVal,"recipientName"=>$recipientName[$mKey]);
									$itemsArr=array_merge($newArr,$interviewerArray);
									$handoverMemberDao->add_d($itemsArr,true);
								}
							}
						}
					}
				}
				if(!empty($mailArr)){//发送邮件通知确认人
					$newMailArr=array_unique($mailArr);
					$mailStr=implode(',',$newMailArr);
					$this->mailToConfirm($newId,$mailStr);
				}

				$this->departureTransferMail_d($object); //发邮件通知员工
			}
			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据离职申请单ID，发送邮件通知员工，附带离职交接清单和温馨提醒
	 */
	function departureTransferMail_d( $object ) {
		$emailDao = new model_common_mail();
		$leaveDao = new model_hr_leave_leave();
		$leaveObj = $leaveDao->get_d( $object['leaveId'] );
		$mailContent = "您好！<br>"
				."您的离职申请已审批通过。离职日期为：<span style='color:blue'>".$leaveObj['comfirmQuitDate']
				."</span>，并已指定具体工作任务及资料交接事项,请于<span style='color:blue'>".$leaveObj['comfirmQuitDate']."</span>前据下表跟工作接收人完成工作交接。<br>"
				."详细信息如下：<br>";
		if (!empty ($object['formwork']) && is_array($object['formwork'])) {
			$mailContent .= '<table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td width="600px">工作及设备交接事项</td><td width="300px">交接人</td></tr>';
			foreach ($object['formwork'] as $key => $val) {
				$mailContent .= '<tr><td>'.$val['items'].'</td><td>'.$val['recipientName'].'</td></tr>';
			}
			$mailContent .= '</table><br><br>';
		}
		$mailContent .= "<span style='color:blue'>您的办公工具将在<span style='color:red'>".$leaveObj['comfirmQuitDate']."</span>关闭，特温馨提醒：为使您顺利办理交接手续，敬请配合如下工作，以便相关部门及时核算金额或回收设备：<br>"
					."<br>1）如您有所需报销的费用，请在正式离职前如实填写报销单并按要求提供相应票据；<br>"
					."<br>2）如您尚借用公司费用，请在正式离职前联系财务部予以归还；<br>"
					."<br>3）如您尚借用公司设备，请参考设备管理同事发出的清单，在正式离职前予以归还；<br>"
					."<br>4）若您归属服务执行中心或试点专区，敬请在正式离职前填写完整当月工作日志，以便后期为您结算当月浮动薪酬；（计算数据以日志为准，漏填的将不再进行计算发放） <br>"
					."<br><br>全部工作交接及离职手续办理完毕后，可参与每月15日或月底的离职结算，将由人力资源部结算离职工资，届时将发送结算明细至您个人邮箱，敬请及时查阅。<br>"
					."<br><br>手续办理过程中，如有疑问，欢迎及时联系人力资源部0756-3639105<br>"
					."<br><br>感谢您在公司的努力工作及付出，祝生活幸福，万事如意！<br>"
					."<br><br><br>珠海世纪鼎利科技股份有限公司</span>";
		$emailDao->mailGeneral("离职申请审批通知" ,$leaveObj['userAccount'] ,$mailContent);
	}

	/**
	 * 根据离职申请单ID，查找离职交接清单各交接人，并发送邮件
	 */
	 function mailByLeaveId($leaveId){
		//查找是否存在离职交接清单
		$row=$this->getnfo_d($leaveId);
		if(is_array($row)){
			//获取交接清单信息
		     $Dao = new model_hr_leave_handoverlist();
		     $Dao->searchArr['handoverId'] = $row['id'];
		     $fromworkInfo = $Dao->list_d ("select_default");
		     if(is_array($fromworkInfo)){
				$mailStr="";
				$mailArr=array();
				foreach($fromworkInfo as $key=>$val){
					if(!empty($val['recipientId'])){
							$emailStr.=$val['recipientId'].",";
							$recipientName=explode(",",$val['recipientName']);
							$recipientId=explode(",",$val['recipientId']);
							//循环面谈者，分别插入从表
							foreach($recipientId as $mKey =>$mVal){
								if($mVal!=""){
									array_push($mailArr,$mVal);
								}
							}
					}
				}
				if(!empty($mailArr)){//发送邮件通知确认人
					$newMailArr=array_unique($mailArr);
					$mailStr=implode(',',$newMailArr);
					$this->mailToConfirm($row['id'],$mailStr);
				}

		     }
		}
	 }

	/**
	 * 发送邮件通知确认人进行确认
	 */
	 function mailToConfirm($id,$mailIds){
		//发送邮件通知员工进行确认
		$emailDao = new model_common_mail ();
		$leaveObj=$this->get_d($id);
		$msg="您好！【".$leaveObj['deptName']."-".$leaveObj['userName']."】的离职交接清单已发起确认，请于<font color='blue''>".$leaveObj['quitDate']."</font>前对您负责的交接项进行确认，如员工有相关费用、设备借用，请发送清单通知员工及时归还。若有遗失，请在OA确认时填写相应扣除额。OA确认路径：个人办公-工作任务-人事类-离职交接确认.";
		$emailDao->mailClear("离职交接项确认",$mailIds,$msg);
	 }

	/**
	 * 获取含有当前登录人为确认人的交接清单ID
	 */
	function affirmUserInfo($userId){
		$sql = "select handoverId from oa_hr_handover_member where recipientId = '".$userId."' and affstate=0 group by handoverId";
		$arr = $this->_db->getArray($sql);

		if(is_array($arr)){
			foreach ($arr as $k => $v){
				$ids .= $v['handoverId'].",";
			}
			$ids = rtrim($ids, ',');
		}

		return $ids;
	}
	/**
	 * 离职交接清单确认操作
	 */
	function affirmCon_d($arr){
		try {
			$this->start_d();
			if(is_array($arr['formwork'])){
				foreach($arr['formwork'] as $k => $v){
					if($v['affstate']=="0"){
						$updateSql = "update oa_hr_handover_list set handoverCondition = '".$v['handoverCondition']."',lose = '".$v['lose']."',deduct = '".$v['deduct']."',remark = '".$v['remark']."',affstate = '1',affTime = now() where id='".$v['id']."'";
						$this->query($updateSql);
					}
				}
			}
			if(is_array($arr['formwork'])){

				$handoverlistDao = new model_hr_leave_handoverlist();
			    $handoverlistRow = $handoverlistDao->findAll ( array('handoverId' => $arr['id']) );
				foreach($handoverlistRow as $k => $v) {
					//判断事件是否满足所有发送前提条件
					if(!$v['affstate'] && $v['mailAffirm'] == 'on' && trim($v['sendPremise']) != ''){
						$a = trim($v['sendPremise']);
						$sendPremise = str_replace('，',',',$a);//检查是否为中文符号
						if($handoverlistDao->findCount("affstate='1' and handoverId=".$arr['id']." and sort in(".$sendPremise.")") == count(explode("," ,$sendPremise))) {
							//发送前提全部完成，发送邮件通知下一个事项接受者
							$emailDao_1 = new model_common_mail ();
							$mailContent = '<span style="color:blue">'.$arr['deptName'].'</span>部门的<span style="color:blue">'.$arr['userName'].'</span>离职交接事项<span style="color:blue">'.$v['items'].'</span>待确认，请到以下OA路径进行确认：</br>导航栏--->个人办公--->工作任务--->人事类--->离职交接确认';
							$emailDao_1->mailClear("离职交接信息确认!",$v['recipientId'], $mailContent);
						}
					}
				}

				//判断是否是最后一条确认信息,更改离职单的交接确认状态
				$handOverListDao=new model_hr_leave_handoverlist();
				if($handOverListDao->findCount("affstate<>'1' and handoverId=".$arr['id']." and isKey='on'")=="0"){
					$leaveDao=new model_hr_leave_leave();
					$leaveDao->updateById(array("id"=>$arr['leaveId'],"handoverCstatus"=>"YQR"));
					//发送邮件通知员工进行确认
					$emailDao = new model_common_mail ();
					$leaveObj=$leaveDao->get_d($arr['leaveId']);
					$emailDao->mailClear("离职交接信息确认!",$leaveObj['userAccount'], "您的离职交接清单已经确认完毕，请您登陆OA查看并确认。");
				}
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 个人确认离职交接清单确认操作
	 */
	function startAffirmPro_d($arr){
		try {
			$this->start_d();
			$updateSql = "update oa_leave_handover set staffAffCon = '".$arr['staffAffCon']."',staffAffDT = '".$arr['staffAffDT']."',staffConRemark = '".$arr['staffConRemark']."' where id='".$arr['handoverId']."'";
			$this->query($updateSql);
			//同时更新离职申请单的确认状态
			$leaveDao=new model_hr_leave_leave();
			$leaveDao->updateById(array("id"=>$arr['leaveId'],"userSelfCstatus"=>"YQR"));
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据交接单ID 查看是否确认完成
	 */
	function getLeaveInfo_d($handoverId){
		$sql = "select count(*) as num from oa_hr_handover_list where handoverId = '".$handoverId."' and affstate=0 and isKey='0'";
		$arr = $this->_db->getArray($sql);
		if($arr[0]['num'] == '0'){
			return "0";
		}else{
			return "1";
		}
	}

	/**
	 * 根据交接单ID 查看是否确认完成
	 */
	function isDone_d($handoverId){
		//判断是否是最后一条确认信息,更改离职单的交接确认状态
		$handOverListDao=new model_hr_leave_handoverlist();
		if($handOverListDao->findCount("affstate<>'1' and handoverId=".$handoverId." and isKey='on'")=="0"){
	     	return  1;
		}else{
	     	return  0;
		}
	}

	/**
	 * 处理离职交接详细
	 */
	function fromworkInfo_d($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<table class='tableA' id='formtable'><tr><td width='30'>操作<input type='hidden' id='num' value='{$num}'></td><td>工作及设备交接事项</td><td>接收人</td><td>遗失财务</td><td >金额</td><td width='75'>是否必须确认</td><td width='30'>排序</td><td width='75'>是否发送邮件</td><td width='50'>发送前提</td></tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$recipientId = $v['recipientId'];
			$list .= "<tr><td> <img align='absmiddle' src='images/removeline.png' onclick='delItem(this);' title='删除行' /></td><td>
             		     <input type ='hidden' class='rimless_textB' readonly name='handover[formwork][$k][items]' value='{$items}'/>$items
             		  </td>
             		  <td><input type ='txt' id='recipientName$i' class='rimless_textB' readonly name='handover[formwork][$k][recipientName]' value='{$recipientName}' title='双击选择人员'>
             		  	  <input type ='hidden' id='recipientId$i' class='txt' readonly name='handover[formwork][$k][recipientId]' value='{$recipientId}'></td>
             		  <td></td><td ></td><td ><input type ='checkbox' id='isKey$i'  readonly name='handover[formwork][$k][isKey]' checked></td>
             		  <td><input type ='txt' class='rimless_textB' name='handover[formwork][$k][sort]' style='width:20px'/></td>
             		  <td><input type ='checkbox' name='handover[formwork][$k][mailAffirm]' checked /></td>
             		  <td><input type ='txt' class='rimless_textB' name='handover[formwork][$k][sendPremise]' /></td>
             		 </tr>";
		}
		$list.="<tr  id='appendHtml'></tr></table>";
		return $listA.$list;
	}

	/**
	 * 编辑离职交接详细
	 */
	function editHandoverList_d($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<tr><td>操作<input type='hidden' id='num' value='{$num}'></td><td>工作及设备交接事项</td><td>接收人</td><td>遗失财务</td><td >扣款金额</td><td>是否必须确认</td></tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$recipientId = $v['recipientId'];
			if( $v['affstate']==1){
				if($v['isKey'] == "on"){
					$isKey = "<span style='color:red'>是</span>";
				}else{
					$isKey = "<span style='color:blue'>否</span>";
				}
				$list .= "<tr><td></td><td>
             		     $items
             		  </td>
             		  <td>{$recipientName}</td>
             		  <td></td>
             		  <td ></td>
             		  <td >$isKey</td></tr>";

			}else{
				$list .= "<tr><td> <img align='absmiddle' src='images/removeline.png' onclick='delItem(this);' title='删除行' /></td><td>
             		     <input type ='hidden' class='rimless_textB' readonly name='handover[formwork][$k][items]' value='{$items}'/>$items
             		  </td>
             		  <td><input type ='txt' id='recipientName$i' class='rimless_textB' readonly name='handover[formwork][$k][recipientName]' value='{$recipientName}' title='双击选择人员'>
             		  	  <input type ='hidden' id='recipientId$i' class='txt' readonly name='handover[formwork][$k][recipientId]' value='{$recipientId}'></td>
             		  <td></td><td ></td><td ><input type ='checkbox' id='isKey$i'  readonly name='handover[formwork][$k][isKey]' checked></td></tr>";
			}
		}
		$list.="<tr  id='appendHtml'></tr>";
		return $listA.$list;
	}

	//查看
	function fromworkInfo_view($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<table class='tableA'><tr bgcolor='#ECECFF'><td  width='25'>序号<input type='hidden' id='num' value='{$num}'></td><td>工作及设备交接事项</td><td width='100'>接收人</td><td>遗失财务</td><td  width='80'>金额</td><td>备注</td><td width='100'>是否必须提前确认</td><td width='50'>确认状态<td width='25'>排序</td><td width='75'>是否发送邮件</td><td width='50'>发送前提</td></tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$handoverCondition = $v['handoverCondition'];
			$lose = $v['lose'];
			$remark = $v['remark'];
			$sort = $v['sort'];
			$sendPremise = $v['sendPremise'];
			if($v['affstate'] == "1"){
				$affstate = "<span style='color:blue'>√</span>";
			}else{
				$affstate = "<span style='color:red'>×</span>";
			}
			if($v['isKey'] == "on"){
				$isKey = "<span style='color:red'>是</span>";
			}else{
				$isKey = "<span style='color:blue'>否</span>";
			}
			if($v['deduct']==0){
				$deduct = '';
			}else{
				$deduct = $v['deduct'];
			}
			if ($v['mailAffirm'] == 'on') {
				$mailAffirm = "<span style='color:red'>是</span>";
			} else{
				$mailAffirm = "<span style='color:blue'>否</span>";
			}
			$list .= "<tr><td>$i</td><td>$items</td><td>$recipientName</td><td>$lose</td><td>$deduct</td><td  align='left'>$remark</td><td>$isKey</td><td>$affstate</td><td>$sort</td><td>$mailAffirm</td><td>$sendPremise</td></tr>";
		}
		$listB =  "<tr><td colspan='11' style='text-align:left'><span style='color:blue;'>特别说明</span>：劳动关系自离职日期终止；经办理完离职结算手续后，不再处理任何报销单据；离职交接清单经各方确认后，劳动关系存续期间的劳动报酬等权利义务均已结清，离职人员所发生的劳动及法律纠纷均与本公司无关；与我公司签订的保密协议仍然有效，如有违反必追究相关责任。</td></tr></table>";
		return $listA.$list.$listB;
	}

	/*
	 * 打印页面
	 * 没有排序、发送前提、是否发送
	 */
	function fromworkInfo_print($fromworkInfo){
		$num = count($fromworkInfo);
		$listA = "<table class='tableA'><tr bgcolor='#ECECFF'><td  width='25'>序号<input type='hidden' id='num' value='{$num}'></td><td>工作及设备交接事项</td><td width='100'>接收人</td><td>遗失财务</td><td  width='80'>金额</td><td>备注</td><td width='50'>是否必须提前确认</td><td width='50'>确认状态</tr>";
		foreach($fromworkInfo as $k => $v){
			$i = $k + 1;
			$items = $v['items'];
			$recipientName = $v['recipientName'];
			$handoverCondition = $v['handoverCondition'];
			$lose = $v['lose'];
			$remark = $v['remark'];
			$sort = $v['sort'];
			$sendPremise = $v['sendPremise'];
			if($v['affstate'] == "1"){
				$affstate = "<span style='color:blue'>√</span>";
			}else{
				$affstate = "<span style='color:red'>×</span>";
			}
			if($v['isKey'] == "on"){
				$isKey = "<span style='color:red'>是</span>";
			}else{
				$isKey = "<span style='color:blue'>否</span>";
			}
			if($v['deduct']==0){
				$deduct = '';
			}else{
				$deduct = $v['deduct'];
			}
			if ($v['mailAffirm'] == 'on') {
				$mailAffirm = "<span style='color:red'>是</span>";
			} else{
				$mailAffirm = "<span style='color:blue'>否</span>";
			}
			$list .= "<tr><td>$i</td><td>$items</td><td>$recipientName</td><td>$lose</td><td>$deduct</td><td  align='left'>$remark</td><td>$isKey</td><td>$affstate</td></tr>";
		}
		$listB =  "<tr><td colspan='11' style='text-align:left'><span style='color:blue;'>特别说明</span>：劳动关系自离职日期终止；经办理完离职结算手续后，不再处理任何报销单据；离职交接清单经各方确认后，劳动关系存续期间的劳动报酬等权利义务均已结清，离职人员所发生的劳动及法律纠纷均与本公司无关；与我公司签订的保密协议仍然有效，如有违反必追究相关责任。</td></tr></table>";
		return $listA.$list.$listB;
	}

	/**
	 * 根据用户账号获取信息
	 */
	function getnfo_d($leaveId){
		$this->searchArr = array ('leaveId' => $leaveId );
		$personnelRow= $this->listBySqlId ( "select_default" );
		return $personnelRow['0'];
	}

}
?>