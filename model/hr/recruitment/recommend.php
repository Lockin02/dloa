<?php
/**
 * @author Administrator
 * @Date 2012年7月11日 星期三 16:13:46
 * @version 1.0
 * @description:内部推荐 Model层
 */
 class model_hr_recruitment_recommend  extends model_base {

	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_hr_recruitment_recommend";
		$this->sql_map = "hr/recruitment/recommendSql.php";
		$this->mailArr=$mailUser[$this->tbl_name];
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'save',
				'statusCName' => '保存',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'nocheck',
				'statusCName' => '未审核',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'give',
				'statusCName' => '已分配',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'failed',
				'statusCName' => '不通过',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'interviewing',
				'statusCName' => '面试中',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'onwork',
				'statusCName' => '已入职',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'closed',
				'statusCName' => '关闭',
				'key' => '6'
			),
			7 => array (
				'statusEName' => 'black',
				'statusCName' => '黑名单',
				'key' => '7'
			),
			8 => array (
				'statusEName' => 'waitwork',
				'statusCName' => '待入职',
				'key' => '8'
			),
			9 => array (
				'statusEName' => 'nowork',
				'statusCName' => '放弃入职',
				'key' => '9'
			)
		);
		parent::__construct ();
	}

	/**
	 * 重写add
	 */
	function add_d($object){
        $object['formCode'] = date ( "YmdHis" );
		$object['formDate'] = date('y-m-d');
		$id=parent::add_d($object,true);

		//更新附件关联关系
		$this->updateObjWithFile ( $id );

		$uploadFile = new model_file_uploadfile_management ();
		$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
		//附件处理
		if($object['state']==$this->statusDao->statusEtoK ( 'nocheck' ))
			$this->postEmail_d($object);
		return $id;
	}

	/**
	 * 修改对象
	 */
	 function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}

		//未审核
		if($object['state']==$this->statusDao->statusEtoK ( 'nocheck' )) {
			$this->postEmail_d($object);
		}
		//已分配
		else if($object['state']==$this->statusDao->statusEtoK ( 'give' )){

			if(!empty($object['assistManId'])&&!empty($object['assignedManName'])){
				$linestring['name'] = explode(",",$object['assistManName']);
				$linestring['id'] = explode(",",$object['assistManId']);

				$member = new model_hr_recruitment_recommendmember();

				for ($i=0; $i < count($linestring['name']); $i++) {
					$getinfo = $member->add_d(array('assesManId'=>$linestring['id'][$i],'assesManName'=>$linestring['name'][$i],'parentId'=>$object['id'],'formCode'=>$object['formCode']));
				}
			}
			$this->passedEmail_d($object);
			$this->headEmail_d($object);
			//不通过
		}else if($object['state']==$this->statusDao->statusEtoK ( 'failed' )){
			$this->failedEmail_d($object);
		}
		return $this->updateById ( $object );
	}

	/**
	 * 负责的内部推荐打回
	 */
	function back_d($object){
		//发送邮件
		$this->myFailedEmail_d($object);
		return $this->updateById ( $object );

	}

	/**
	 * 修改负责人
	 */
	function changeHead_d( $obj ) {
		try {
			$this->start_d();

			parent::edit_d($obj); //修改主表信息

			if(!empty($obj['assistManId'])) {
				$equ['name'] = explode("," ,$obj['assistManName']);
				$equ['id'] = explode("," ,$obj['assistManId']);

				$recommendmemberDao = new model_hr_recruitment_recommendmember();
				$recommendmemberDao->delete(array('parentId'=>$obj['id']));

				foreach ($equ['id'] as $key => $val) {
					$recommendmemberDao->add_d(
						array(
							'parentId'=>$obj['id']
							,'formCode'=>$obj['formCode']
							,'assesManId'=>$val
							,'assesManName'=>$equ['name'][$key]
						)
					);
				}
			}

			//发邮件通知
			$emailDao = new model_common_mail();
			$newObj = $this->get_d( $obj['id'] );
			$receiverId = $newObj['recruitManId'].','.$newObj['assesManId'];
			$mailContent = '您好，<font color="blue">'.$_SESSION['USERNAME'].'</font>修改了单号为【<font color="blue">'.$obj['formCode'].'</font>】的内部推荐负责人，详细信息如下：<br>';
			$mailContent .= <<<EOT
				<table width="500px" border="1px" cellspacing="0px">
					<thead>
						<tr align="center">
							<td><b>被推荐人</b></td>
							<td><b>推荐职位</b></td>
							<td><b>来源</b></td>
							<td><b>推荐人</b></td>
							<td width="200px"><b>推荐理由</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center">
							<td>$newObj[isRecommendName]</td>
							<td>$newObj[positionName]</td>
							<td>$newObj[source]</td>
							<td>$newObj[recommendName]</td>
							<td>$newObj[recommendReason]</td>
						</tr>
					</tbody>
				</table>
				<br>主负责人：<font color='blue'>$newObj[recruitManName]</font>
				<br>协助人：<font color='blue'>$newObj[assistManName]</font><br>;
EOT;

			$emailDao->mailGeneral("内部推荐修改负责人" ,$receiverId ,$mailContent);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * 提交推荐职位审批后，发送邮件
	 *@param $object 内部推荐数据对象
	 */
	function postEmail_d($object) {
		try {
			$this -> start_d();
			$recommend = $object;
			$mailRow = $this->mailArr;
			$emailArr = array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$mailRow['sendUserId'];
			$emailArr['TO_NAME']=$mailRow['sendName'];
			if ($emailArr['TO_ID']) {
				$addmsg = "";
				$isRecommendName=$recommend['isRecommendName'];
				$positionName=$recommend ['positionName'];
				$source=$recommend ['source'];
				$recommendName=$recommend ['recommendName'];
				$recommendReason=$recommend['recommendReason'];
				$addmsg .=  <<<EOT
				<table width="500px" border="1px" cellspacing="0px">
					<thead>
						<tr align="center">
							<td><b>被推荐人</b></td>
							<td><b>推荐职位</b></td>
							<td><b>来源</b></td>
							<td><b>推荐人</b></td>
							<td width="200px"><b>推荐理由</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$isRecommendName</td>
							<td>$positionName</td>
							<td>$source</td>
							<td>$recommendName</td>
							<td>$recommendReason</td>
						</tr>
					</tbody>
					</table>
EOT;
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'hr_recruitment_recommend', '该邮件为内部推荐通知', '', $emailArr['TO_ID'], $addmsg, 1);

			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}

	/**
	 * 经理审批后反馈，发送邮件
	 *@param $id 内部推荐ID
	 */
	function passedEmail_d($object) {
		try {
			$this -> start_d();
			$recommend = $this -> get_d($object['id']);
			$emailArr = array();
			$emailArr['issend'] = 'y';
			if(!empty($recommend['assistManId'])&&!empty($recommend['assignedManName'])){
				$emailArr['TO_ID']=$recommend['recommendId'].",".$recommend['recruitManId'].",".$recommend['assignedManId'];
				$emailArr['TO_NAME']=$recommend['recommendName'].",".$recommend['recruitManName'].",".$recommend['assignedManName'];
			}else{
				$emailArr['TO_ID']=$recommend['recommendId'].",".$recommend['recruitManId'];
				$emailArr['TO_NAME']=$recommend['recommendName'].",".$recommend['recruitManName'];
			}

			//$emailArr['TO_ID']=$this->mailArr['sendUserId'];
			//$emailArr['TO_NAME']=$this->mailArr['sendName'];
			if ($emailArr['TO_ID']) {
				$addmsg = "";
				$isRecommendName=$recommend['isRecommendName'];
				$positionName=$recommend ['positionName'];
				$source=$recommend ['source'];
				$recommendName=$recommend ['recommendName'];
				$recommendReason=$recommend['recommendReason'];
				$recruitManName=$object['recruitManName'];
				$addmsg .= "您的内部推荐已由如下HR同事受理跟进，后续事宜请后期登陆OA系统查看或直接咨询招聘组同事，谢谢！";
				$addmsg .=  <<<EOT
				<table width="500px" border="1px" cellspacing="0px">
					<thead>
						<tr align="center">
							<td><b>被推荐人</b></td>
							<td><b>推荐职位</b></td>
							<td><b>来源</b></td>
							<td><b>推荐人</b></td>
							<td width="200px"><b>推荐理由</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center">
							<td>$isRecommendName</td>
							<td>$positionName</td>
							<td>$source</td>
							<td>$recommendName</td>
							<td>$recommendReason</td>
						</tr>
					</tbody>
					</table>
EOT;
				$addmsg .= "<br>审核结果：";
				$addmsg .= "<font color='blue'>通过</font>";
				$addmsg .= "<br>负责人：";
				$addmsg .= "<font color='blue'>$recruitManName</font><br><br>";
				$addmsg .=  <<<EOT
					<table width="500px">
					<div><strong style="font-size:15px;">&nbsp;非常感谢您为公司推荐优秀人才！请阅读如下推荐原则：</strong></div>
					<div style="font-size:13px;">
						1. 公司欢迎所有内部员工推荐优秀人才加入我公司；公司对所有候选人进行公平、公正的考评，在同等资历条件下优先考虑由公司内部员工推荐的候选人。</div>
					<div  style="font-size:13px;">
						2. 公司对被正式录用候选人的相应推荐人给予物质奖励，但有以下前提条件：</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.1 被荐人不能为推荐人的直系亲属；</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.2 被荐人的基本条件要符合公司对人员招聘的基本要求（包括学历、经验、技能等要求）；</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.3 被荐人所提供的个人简历及人事资料应确保真实有效，否则不予发放推荐奖；</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.4 推荐人对被荐人的经验、技能等需有过相应的接触或了解，能明确阐述推荐理由、推荐人与被荐人关系。</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.5 被荐职位需经公告发布，在有物质奖励的职位之列。</div>
					<div  style="font-size:13px;">
						3. 公司欢迎并鼓励内部员工通过各合法渠道搜集同行优秀人才资料，并将相关信息提供人力资源部进行定向招聘，此类型推荐不纳入内部推荐范畴，但招聘成功的，公司将在不定期发布的内荐公告中予以表扬并酌情另行给予一定奖励。</div>
					<div  style="font-size:13px;">
						4. 如上规定解释权归人力资源部所有。</div>
					</table>
EOT;
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$emailDao->mailGeneral('内部推荐通知' ,$emailArr['TO_ID'] ,$addmsg);
			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}

	/**
	 * 经理审批后反馈，发送邮件
	 *@param $id 内部推荐ID
	 */
	function failedEmail_d($object) {
		try {
			$this -> start_d();
			$recommend = $this -> get_d($object['id']);
			$emailArr = array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$recommend['recommendId'].",".$recommend['recruitManId'].",".$recommend['assignedManId'];
			$emailArr['TO_NAME']=$recommend['recommendName'].",".$recommend['recruitManName'].",".$recommend['assignedManName'];
			//$emailArr['TO_ID']=$this->mailArr['sendUserId'];
			//$emailArr['TO_NAME']=$this->mailArr['sendName'];
			if ($emailArr['TO_ID']) {
				$addmsg = "";
				$isRecommendName=$recommend['isRecommendName'];
				$positionName=$recommend ['positionName'];
				$source=$recommend ['source'];
				$recommendName=$recommend ['recommendName'];
				$recommendReason=$recommend['recommendReason'];
				$closeRemark=$object['closeRemark'];
				$addmsg .=  <<<EOT
				<table width="500px" border="1px" cellspacing="0px">
					<thead>
						<tr align="center">
							<td><b>被推荐人</b></td>
							<td><b>推荐职位</b></td>
							<td><b>来源</b></td>
							<td><b>推荐人</b></td>
							<td width="200px"><b>推荐理由</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$isRecommendName</td>
							<td>$positionName</td>
							<td>$source</td>
							<td>$recommendName</td>
							<td>$recommendReason</td>
						</tr>
					</tbody>
					</table>
EOT;
				$addmsg .= "<br>审核结果：";
				$addmsg .= "<font color='blue'>打回</font>";
				$addmsg .= "<br>打回原因：";
				$addmsg .= "<br><font color='red'>$closeRemark</font>";
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'recommend_backed', '该邮件为内部推荐失败通知', '', $emailArr['TO_ID'], $addmsg, 1);

			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}
	/**
	 * 登录用户负责的内部推荐 反馈结果发送邮件
	 */
	function myFailedEmail_d($object){
		try {
			$this -> start_d();
			$recommend = $this -> get_d($object['id']);
			$emailArr = array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$recommend['recommendId'].",".$recommend['assignedManId'];
			$emailArr['TO_NAME']=$recommend['recommendName'].",".$recommend['assignedManName'];
			//$emailArr['TO_ID']=$this->mailArr['sendUserId'];
			//$emailArr['TO_NAME']=$this->mailArr['sendName'];
			if ($emailArr['TO_ID']) {
				$addmsg = "";
				$isRecommendName=$recommend['isRecommendName'];
				$positionName=$recommend ['positionName'];
				$source=$recommend ['source'];
				$recommendName=$recommend ['recommendName'];
				$recommendReason=$recommend['recommendReason'];
				$closeRemark=$object['closeRemark'];
				$addmsg .=  <<<EOT
				<div>
				<table width="500px" border="1px" cellspacing="0px">
					<thead>
						<tr align="center">
							<td><b>被推荐人</b></td>
							<td><b>推荐职位</b></td>
							<td><b>来源</b></td>
							<td><b>推荐人</b></td>
							<td width="200px"><b>推荐理由</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$isRecommendName</td>
							<td>$positionName</td>
							<td>$source</td>
							<td>$recommendName</td>
							<td>$recommendReason</td>
						</tr>
					</tbody>
					</table>
					</div>
EOT;
				if ($object['state'] == 3 ) {
					$result = '推荐失败打回';
				} else if ($object['state'] == 5){
					$result = '推荐录用';
				}

				$addmsg .= "<br>反馈结果：";
				$addmsg .= "<font color='blue'>$result</font>";
				$addmsg .= "<br>反馈内容：";
				$addmsg .= "<br><font color='red'>$closeRemark</font>";
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'recommend_backed', '该邮件为内部推荐反馈通知', '', $emailArr['TO_ID'], $addmsg, 1);

			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}
	}

	/**
	 * 下达分配招聘专员后，发邮件通知招聘专员
	 */
	function headEmail_d($object) {
		try {
			$this -> start_d();
			$recommend = $this -> get_d($object['id']);
			$addmsg = "";
			$isRecommendName = $recommend['isRecommendName'];
			$positionName = $recommend ['positionName'];
			$source = $recommend ['source'];
			$recommendName = $recommend ['recommendName'];
			$recommendReason = $recommend['recommendReason'];

			$recruitManName = $object['recruitManName'];
			$receiveuser = $object['recruitManId'];
			$assistManName = $object['assistManName'];

			$emailArr['issend'] = 'y';
			$addmsg .=  <<<EOT
				<table width="500px" border="1px" cellspacing="0px">
				<thead>
					<tr align="center">
						<td><b>被推荐人</b></td>
						<td><b>推荐职位</b></td>
						<td><b>来源</b></td>
						<td><b>推荐人</b></td>
						<td width="200px"><b>推荐理由</b></td>
					</tr>
				</thead>
				<tbody>
				<tr align="center" >
							<td>$isRecommendName</td>
							<td>$positionName</td>
							<td>$source</td>
							<td>$recommendName</td>
							<td>$recommendReason</td>
						</tr>
				</tbody>
				</table>
EOT;
			$addmsg .= "<br>审核结果：";
			$addmsg .= "<font color='blue'>通过</font>";
			$addmsg .= "<br>负责人：";
			$addmsg .= "<font color='blue'>$recruitManName</font>";
			$addmsg .= "<br>协助人：";
			$addmsg .= "<font color='blue'>$assistManName</font>";
			$emailDao = new model_common_mail();
			$emailDao -> emailInquiry($emailArr['issend'] ,$_SESSION['USERNAME'], $_SESSION['EMAIL'] ,'recommend_passed' ,'该邮件为内部推荐通过通知' ,'' ,$receiveuser ,$addmsg ,1);

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}
	}

	/**
	 * 转发邮件
	 */
	function forwardMail_d($id ,$mail) {
		try {
			$emailDao = new model_common_mail();
			$uploadFile = new model_file_uploadfile_management();
			$attachment = $uploadFile->getFilesByObjId($id ,'oa_hr_recruitment_recommend');

			$obj = $this->get_d($id);
			$content = <<<EOT
				被荐人：<font color="blue">$obj[isRecommendName]</font><br>
				推荐职位：$obj[positionName]<br>
				推荐人：$obj[recommendName]<br>
				与本人关系：$obj[recommendRelation]<br>
				推荐评价：$obj[recommendReason]<br>
				备  注：$mail[content]
EOT;
			if (is_array($attachment)) {
				$mail['attachment'] = array();
				$filePath = str_replace('\\','/',UPLOADPATH);
				$destDir = $filePath."oa_hr_recruitment_recommend/";
				//复制一份中文名称的附件
				foreach ($attachment as $key => $val) {
//					if (copy($destDir.$val['newName'] ,$destDir.$val['originalName'])) {
//						$mail['attachment'][$key] = $destDir.$val['originalName'];
//					}
                    $mail['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
				}
			}

			if ($emailDao->mailWithFile($mail['title'] ,$mail['receiverId'] ,$content ,null ,$mail['attachment'])) {
				//发送完成删除复制的中文附件
//				if (is_array($mail['attachment'])) {
//					foreach ($mail['attachment'] as $key => $val) {
//						unlink($val);
//					}
//				}
			}

			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
?>