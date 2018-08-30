<?php
/**
 * @author Administrator
 * @Date 2012-08-09 15:38:12
 * @version 1.0
 * @description:离职交接清单明细 Model层
 */
 class model_hr_leave_handoverlist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_handover_list";
		$this->sql_map = "hr/leave/handoverlistSql.php";
		parent::__construct ();
	}

	/**
	 * 离职交接清单重启操作
	 */
	function restart_d($arr){
		try {
			$this->start_d();
			if(is_array($arr['formwork'])){
				foreach($arr['formwork'] as $k => $v){
					if($v['restart'] == "on"){
						$updateSql = "update oa_hr_handover_list set affstate = '0' where id='".$v['id']."'";
						$this->query($updateSql);

						//发送邮件通知接收人重新确认
						$emailDao = new model_common_mail ();
						$mailContent = '<span style="color:blue">'.$arr['deptName'].'</span>部门的<span style="color:blue">'.$arr['userName'].'</span>离职交接事项<span style="color:blue">'.$v['items'].'</span>重启确认，请到以下OA路径进行确认：</br>导航栏--->个人办公--->工作任务--->人事类--->离职交接确认';
						$emailDao->mailClear("离职交接事项重启",$v['recipientId'], $mailContent);
					}
				}
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/*
	 * 离职交接清单修改
	 */
	function alterHand_d( $object ) {
		try {
			$this->start_d();
			$handoverId = $object['id'];
			$obj = ($object['formwork']);
			$handoverMemberDao = new model_hr_leave_handoverMember();

			foreach ($obj as $k => $v) {

				$recipientName=explode(",",$v['recipientName']);
				$recipientId=explode(",",$v['recipientId']);

				if ($v['isDelTag'] == '1') { //如果数据库不存在数据就删除
					$this->delete( array('id'=>$v['id']) );
					continue;

				}else if ($v['id'] == '') { //如果数据不存在数据库就新增
					$v['handoverId'] = $handoverId;
					$listId = $this->add_d( $v ,true );

					//添加成功发邮件通知接收人
					if ($listId) {
						$this->alterHandMail_d($object ,$v);
					}
					foreach($recipientId as $mKey =>$mVal){
						if($mVal!=""){
							$newArr=array('handoverId' => $handoverId,
										  'parentId' => $listId,
										  'recipientId' => $mVal,
										  'recipientName' => $recipientName[$mKey],
										  'affstate' => 0
									);
							$handoverMemberDao->add_d($newArr,true);
						}
					}
					continue;

				}else { //如果数据存在数据库就更新
					if (!$v['isKey']) {
						$v['isKey'] = '';
					}
					if (!$v['mailAffirm']) {
						$v['mailAffirm'] = '';
					}
					$objArr = $this->get_d($v['id']);
					//修改交接人发邮件通知
					if ($objArr['recipientId'] != $v['recipientId']) {
						$this->alterHandMail_d($object ,$v);
					}
					$this->updateById ( $v ,true );
					$handoverMemberDao->delete( array('parentId'=>$v['id']) );
					foreach($recipientId as $mKey =>$mVal){
						if($mVal!=""){
							$newArr=array('handoverId' => $handoverId,
										  'parentId' => $v['id'],
										  'recipientId' => $mVal,
										  'recipientName' => $recipientName[$mKey],
										  'affstate' => 0
									);
							$handoverMemberDao->add_d($newArr,true);
						}
					}
				}
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/*
	 * 离职交接清单修改接收人发邮件通知
	 */
	 function alterHandMail_d($obj ,$listArr) {
	 	$emailDao = new model_common_mail ();
		$mailContent = '您好！<br><span style="color:blue">'.$obj['deptName'].'</span>部门的<span style="color:blue">'.$obj['userName'].'</span>离职交接事项<span style="color:blue">'.$listArr['items'].'</span>等待确认，请到以下OA路径进行确认<br>导航栏--->个人办公--->工作任务--->人事类--->离职交接确认';
		$emailDao->mailClear("离职交接单确认" ,$listArr['recipientId'] ,$mailContent);
	 }

	 /*
	 * 根据离职单ID判断确认的事项是否已经确认完毕（包括不用提前确认的）
	 */
	 function isAffirmAll_d($leaveId){
	 	$handoverDao=new model_hr_leave_handover();
	 	$handoverObj = $handoverDao->find(array ('leaveId' => $leaveId));
	 	$num = $this->findCount(array ('handoverId' => $handoverObj['id'] ,'affstate' => '0'));
	 	return $num;
	 }
 }
?>