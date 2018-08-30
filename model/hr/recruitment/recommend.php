<?php
/**
 * @author Administrator
 * @Date 2012��7��11�� ������ 16:13:46
 * @version 1.0
 * @description:�ڲ��Ƽ� Model��
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
				'statusCName' => '����',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'nocheck',
				'statusCName' => 'δ���',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'give',
				'statusCName' => '�ѷ���',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'failed',
				'statusCName' => '��ͨ��',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'interviewing',
				'statusCName' => '������',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'onwork',
				'statusCName' => '����ְ',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'closed',
				'statusCName' => '�ر�',
				'key' => '6'
			),
			7 => array (
				'statusEName' => 'black',
				'statusCName' => '������',
				'key' => '7'
			),
			8 => array (
				'statusEName' => 'waitwork',
				'statusCName' => '����ְ',
				'key' => '8'
			),
			9 => array (
				'statusEName' => 'nowork',
				'statusCName' => '������ְ',
				'key' => '9'
			)
		);
		parent::__construct ();
	}

	/**
	 * ��дadd
	 */
	function add_d($object){
        $object['formCode'] = date ( "YmdHis" );
		$object['formDate'] = date('y-m-d');
		$id=parent::add_d($object,true);

		//���¸���������ϵ
		$this->updateObjWithFile ( $id );

		$uploadFile = new model_file_uploadfile_management ();
		$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
		//��������
		if($object['state']==$this->statusDao->statusEtoK ( 'nocheck' ))
			$this->postEmail_d($object);
		return $id;
	}

	/**
	 * �޸Ķ���
	 */
	 function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}

		//δ���
		if($object['state']==$this->statusDao->statusEtoK ( 'nocheck' )) {
			$this->postEmail_d($object);
		}
		//�ѷ���
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
			//��ͨ��
		}else if($object['state']==$this->statusDao->statusEtoK ( 'failed' )){
			$this->failedEmail_d($object);
		}
		return $this->updateById ( $object );
	}

	/**
	 * ������ڲ��Ƽ����
	 */
	function back_d($object){
		//�����ʼ�
		$this->myFailedEmail_d($object);
		return $this->updateById ( $object );

	}

	/**
	 * �޸ĸ�����
	 */
	function changeHead_d( $obj ) {
		try {
			$this->start_d();

			parent::edit_d($obj); //�޸�������Ϣ

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

			//���ʼ�֪ͨ
			$emailDao = new model_common_mail();
			$newObj = $this->get_d( $obj['id'] );
			$receiverId = $newObj['recruitManId'].','.$newObj['assesManId'];
			$mailContent = '���ã�<font color="blue">'.$_SESSION['USERNAME'].'</font>�޸��˵���Ϊ��<font color="blue">'.$obj['formCode'].'</font>�����ڲ��Ƽ������ˣ���ϸ��Ϣ���£�<br>';
			$mailContent .= <<<EOT
				<table width="500px" border="1px" cellspacing="0px">
					<thead>
						<tr align="center">
							<td><b>���Ƽ���</b></td>
							<td><b>�Ƽ�ְλ</b></td>
							<td><b>��Դ</b></td>
							<td><b>�Ƽ���</b></td>
							<td width="200px"><b>�Ƽ�����</b></td>
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
				<br>�������ˣ�<font color='blue'>$newObj[recruitManName]</font>
				<br>Э���ˣ�<font color='blue'>$newObj[assistManName]</font><br>;
EOT;

			$emailDao->mailGeneral("�ڲ��Ƽ��޸ĸ�����" ,$receiverId ,$mailContent);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * �ύ�Ƽ�ְλ�����󣬷����ʼ�
	 *@param $object �ڲ��Ƽ����ݶ���
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
							<td><b>���Ƽ���</b></td>
							<td><b>�Ƽ�ְλ</b></td>
							<td><b>��Դ</b></td>
							<td><b>�Ƽ���</b></td>
							<td width="200px"><b>�Ƽ�����</b></td>
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
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'hr_recruitment_recommend', '���ʼ�Ϊ�ڲ��Ƽ�֪ͨ', '', $emailArr['TO_ID'], $addmsg, 1);

			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}

	/**
	 * �������������������ʼ�
	 *@param $id �ڲ��Ƽ�ID
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
				$addmsg .= "�����ڲ��Ƽ���������HRͬ�����������������������ڵ�½OAϵͳ�鿴��ֱ����ѯ��Ƹ��ͬ�£�лл��";
				$addmsg .=  <<<EOT
				<table width="500px" border="1px" cellspacing="0px">
					<thead>
						<tr align="center">
							<td><b>���Ƽ���</b></td>
							<td><b>�Ƽ�ְλ</b></td>
							<td><b>��Դ</b></td>
							<td><b>�Ƽ���</b></td>
							<td width="200px"><b>�Ƽ�����</b></td>
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
				$addmsg .= "<br>��˽����";
				$addmsg .= "<font color='blue'>ͨ��</font>";
				$addmsg .= "<br>�����ˣ�";
				$addmsg .= "<font color='blue'>$recruitManName</font><br><br>";
				$addmsg .=  <<<EOT
					<table width="500px">
					<div><strong style="font-size:15px;">&nbsp;�ǳ���л��Ϊ��˾�Ƽ������˲ţ����Ķ������Ƽ�ԭ��</strong></div>
					<div style="font-size:13px;">
						1. ��˾��ӭ�����ڲ�Ա���Ƽ������˲ż����ҹ�˾����˾�����к�ѡ�˽��й�ƽ�������Ŀ�������ͬ���������������ȿ����ɹ�˾�ڲ�Ա���Ƽ��ĺ�ѡ�ˡ�</div>
					<div  style="font-size:13px;">
						2. ��˾�Ա���ʽ¼�ú�ѡ�˵���Ӧ�Ƽ��˸������ʽ�������������ǰ��������</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.1 �����˲���Ϊ�Ƽ��˵�ֱϵ������</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.2 �����˵Ļ�������Ҫ���Ϲ�˾����Ա��Ƹ�Ļ���Ҫ�󣨰���ѧ�������顢���ܵ�Ҫ�󣩣�</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.3 ���������ṩ�ĸ��˼�������������Ӧȷ����ʵ��Ч�������跢���Ƽ�����</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.4 �Ƽ��˶Ա����˵ľ��顢���ܵ����й���Ӧ�ĽӴ����˽⣬����ȷ�����Ƽ����ɡ��Ƽ����뱻���˹�ϵ��</div>
					<div  style="font-size:13px;padding-left:26px;">
						2.5 ����ְλ�辭���淢�����������ʽ�����ְλ֮�С�</div>
					<div  style="font-size:13px;">
						3. ��˾��ӭ�������ڲ�Ա��ͨ�����Ϸ������Ѽ�ͬ�������˲����ϣ����������Ϣ�ṩ������Դ�����ж�����Ƹ���������Ƽ��������ڲ��Ƽ����룬����Ƹ�ɹ��ģ���˾���ڲ����ڷ������ڼ����������Ա��ﲢ�������и���һ��������</div>
					<div  style="font-size:13px;">
						4. ���Ϲ涨����Ȩ��������Դ�����С�</div>
					</table>
EOT;
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$emailDao->mailGeneral('�ڲ��Ƽ�֪ͨ' ,$emailArr['TO_ID'] ,$addmsg);
			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}

	/**
	 * �������������������ʼ�
	 *@param $id �ڲ��Ƽ�ID
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
							<td><b>���Ƽ���</b></td>
							<td><b>�Ƽ�ְλ</b></td>
							<td><b>��Դ</b></td>
							<td><b>�Ƽ���</b></td>
							<td width="200px"><b>�Ƽ�����</b></td>
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
				$addmsg .= "<br>��˽����";
				$addmsg .= "<font color='blue'>���</font>";
				$addmsg .= "<br>���ԭ��";
				$addmsg .= "<br><font color='red'>$closeRemark</font>";
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'recommend_backed', '���ʼ�Ϊ�ڲ��Ƽ�ʧ��֪ͨ', '', $emailArr['TO_ID'], $addmsg, 1);

			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}
	/**
	 * ��¼�û�������ڲ��Ƽ� ������������ʼ�
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
							<td><b>���Ƽ���</b></td>
							<td><b>�Ƽ�ְλ</b></td>
							<td><b>��Դ</b></td>
							<td><b>�Ƽ���</b></td>
							<td width="200px"><b>�Ƽ�����</b></td>
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
					$result = '�Ƽ�ʧ�ܴ��';
				} else if ($object['state'] == 5){
					$result = '�Ƽ�¼��';
				}

				$addmsg .= "<br>���������";
				$addmsg .= "<font color='blue'>$result</font>";
				$addmsg .= "<br>�������ݣ�";
				$addmsg .= "<br><font color='red'>$closeRemark</font>";
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'recommend_backed', '���ʼ�Ϊ�ڲ��Ƽ�����֪ͨ', '', $emailArr['TO_ID'], $addmsg, 1);

			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}
	}

	/**
	 * �´������ƸרԱ�󣬷��ʼ�֪ͨ��ƸרԱ
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
						<td><b>���Ƽ���</b></td>
						<td><b>�Ƽ�ְλ</b></td>
						<td><b>��Դ</b></td>
						<td><b>�Ƽ���</b></td>
						<td width="200px"><b>�Ƽ�����</b></td>
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
			$addmsg .= "<br>��˽����";
			$addmsg .= "<font color='blue'>ͨ��</font>";
			$addmsg .= "<br>�����ˣ�";
			$addmsg .= "<font color='blue'>$recruitManName</font>";
			$addmsg .= "<br>Э���ˣ�";
			$addmsg .= "<font color='blue'>$assistManName</font>";
			$emailDao = new model_common_mail();
			$emailDao -> emailInquiry($emailArr['issend'] ,$_SESSION['USERNAME'], $_SESSION['EMAIL'] ,'recommend_passed' ,'���ʼ�Ϊ�ڲ��Ƽ�ͨ��֪ͨ' ,'' ,$receiveuser ,$addmsg ,1);

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}
	}

	/**
	 * ת���ʼ�
	 */
	function forwardMail_d($id ,$mail) {
		try {
			$emailDao = new model_common_mail();
			$uploadFile = new model_file_uploadfile_management();
			$attachment = $uploadFile->getFilesByObjId($id ,'oa_hr_recruitment_recommend');

			$obj = $this->get_d($id);
			$content = <<<EOT
				�����ˣ�<font color="blue">$obj[isRecommendName]</font><br>
				�Ƽ�ְλ��$obj[positionName]<br>
				�Ƽ��ˣ�$obj[recommendName]<br>
				�뱾�˹�ϵ��$obj[recommendRelation]<br>
				�Ƽ����ۣ�$obj[recommendReason]<br>
				��  ע��$mail[content]
EOT;
			if (is_array($attachment)) {
				$mail['attachment'] = array();
				$filePath = str_replace('\\','/',UPLOADPATH);
				$destDir = $filePath."oa_hr_recruitment_recommend/";
				//����һ���������Ƶĸ���
				foreach ($attachment as $key => $val) {
//					if (copy($destDir.$val['newName'] ,$destDir.$val['originalName'])) {
//						$mail['attachment'][$key] = $destDir.$val['originalName'];
//					}
                    $mail['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
				}
			}

			if ($emailDao->mailWithFile($mail['title'] ,$mail['receiverId'] ,$content ,null ,$mail['attachment'])) {
				//�������ɾ�����Ƶ����ĸ���
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