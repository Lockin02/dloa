<?php
/**
 * @author Administrator
 * @Date 2012��7��27�� ������ 13:22:05
 * @version 1.0
 * @description:��ְ֪ͨ���Ʋ�
 */
class controller_hr_recruitment_entryNotice extends controller_base_action {

	function __construct() {
		$this->objName = "entryNotice";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_myPage() {
		$this->assign("userid" ,$_SESSION['USER_ID']);
		$this->view('mytablist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_myWaitPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('mywaitlist');
	}

	/**
	 * ��ְ֪ͨ��--�����鿴tabҳ
	 */
	function c_viewList() {
		$this->assign('resumeId',$_GET['resumeId']);
		$this->view('viewList');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_myPassedPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('mypassedlist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_deptPage() {
		$this->view('depttablist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_deptWaitPage() {
		$this->assign("deptId" ,$_SESSION['DEPT_ID']);
		$this->view('deptwaitlist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_deptPassedPage() {
		$this->assign("deptId",$_SESSION['DEPT_ID']);
		$this->view('deptpassedlist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_hrPage() {
		$this->view('hrtablist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_hrWaitPage() {
		$this->view('hrwaitlist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_hrPassedPage() {
		$this->view('hrpassedlist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_newdeptPage() {
		$this->view('newdepttablist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_newdeptWaitPage() {
		$this->assign("deptId",$_SESSION['DEPT_ID']);
		$this->view('newdeptwaitlist');
	}

	/**
	 * ��ת����ְ֪ͨ�б�
	 */
	function c_newdeptPassedPage() {
		$this->assign("deptId",$_SESSION['DEPT_ID']);
		$this->view('newdeptpassedlist');
	}

    /**
     * �б�Ȩ��
     */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * ��ת��������ְ֪ͨҳ��
	 */
	function c_toAdd() {
		$this->permCheck (); //��ȫУ��
		$interviewDao = new model_hr_recruitment_interview();
		$obj = $interviewDao->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		if($obj['interviewType'] == 1 || $obj['interviewType'] == 3) { //3�Ǽ������͹�������ְ֪ͨ
			$this->assign ( 'interviewTypeName', '��Ա����' );
		} else {
			$this->assign ( 'interviewTypeName', '�ڲ��Ƽ�' );
		}

		//����
		if($obj['postType'] == 'YPZW-WY') {
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($obj['positionLevel']);
			$this->assign('positionLevelName', $WYlevel['personLevel']);
		} else {
			switch ($obj['positionLevel']) {
				case '1' :
					$this->assign('positionLevelName' ,'����');
					break;
				case '2' :
					$this->assign('positionLevelName' ,'�м�');
					break;
				case '3' :
					$this->assign('positionLevelName' ,'�߼�');
					break;
				default : $this->assign('positionLevelName' ,'');
					break;
			}
		}

		//�������š��������ź��ļ�����
		$deptDao = new model_deptuser_dept_dept();
		$deptObj = $deptDao->getSuperiorDeptById_d($obj['deptId']);
		$this->assign('deptNameS' ,$deptObj['deptNameS']);
		$this->assign('deptNameT' ,$deptObj['deptNameT']);
		$this->assign('deptNameF' ,$deptObj['deptNameF']);

		//����Э���ˣ�ȡ��������豸��Ϣ�����������ռ��ˣ�
		$xqdnMailUser = $this->service->getMailUser_d('interviewAddOffer_xqdn');
		$this->assign('xqdnUser' ,$xqdnMailUser['defaultUserName']);

		//��ȡ�ʼ����ݼ�����
		$object = $this->service->find(array('parentId' => $obj['id']));
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true ,'oa_hr_entryNotice_email')); //��ʾ������Ϣ
		$this->assign('content' ,$object['content']);

		$this->assign('interviewId' ,$obj ['id']);
		$this->assign('interviewCode' ,$obj ['formCode']);
		$this->assign('user' ,$_SESSION['USERNAME']);
		$this->assign('userId' ,$_SESSION['USER_ID']);

		//�ʼ�����
		$this->assign('toMail' ,$obj['email']);
		switch ($obj['sysCompanyName']) {
			case '���ݱ�Ѷ' :
				include(WEB_TOR."model/common/mailConfig.php");
				$toccMailId = $mailUser['oa_hr_recruitment_entrynotice_beixun']['sendUserId'];
				$toccMail = $mailUser['oa_hr_recruitment_entrynotice_beixun']['sendName'];
				break;
			default :
				$toccMailId = $this->service->mailArr['sendUserId'];
				$toccMail = $this->service->mailArr['sendName'];
				break;
		}
		//�����ߵĳ��͸�������
		if ((($obj['deptId'] >= 161 && $obj['deptId'] <= 168) || $obj['deptId'] == 130)
				&& $obj['postType'] == 'YPZW-WY'
				&& $obj['workProvinceId'] > 0) { //�����ߺ��Ե�ר�����������͡�ʡ��
			$managerId = $this->service->get_table_fields('oa_esm_office_managerinfo' ,"provinceId='".$obj['workProvinceId']."'" ,'managerId');

			if (!empty($managerId)) {
				$managerName = $this->service->get_table_fields('oa_esm_office_managerinfo' ,"provinceId='".$obj['workProvinceId']."'" ,'managerName');

				$toccMailId .= ','.$managerId;
				$toccMail .= ','.$managerName;
			}
		}
		$title = "$obj[deptName]��Ա��($obj[userName])��ְ֪ͨ"; //�ʼ�����
		$this->assign('toccMail' ,$toccMail);
		$this->assign('toccMailId' ,$toccMailId);
		$this->assign('title' ,$title);

		if($object['assistManName']) { //��ְЭ����
			$this->assign('assistManName' ,$object['assistManName']);
			$this->assign('assistManId' ,$object['assistManId']);
		} else {
			$this->assign('assistManName' ,'');
			$this->assign('assistManId' ,'');
		}
		$this->view ('add' ,true);
	}

	/**
	 * ���ݲ���id��ȡ�����ܼ�͸��ܼ����Ϣ
	 */
	function c_getDirector($deptId) {
		$otherDao = new model_common_otherdatas();
		$otherObj = $otherDao->getDeptById_d($deptId);

		$userDao = new model_deptuser_user_user();
		$MajorId = $otherObj['MajorId'];
		//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
		if(substr($MajorId ,-1) == ',') {
			$MajorId = substr($MajorId ,0 ,-1);
		}
		$MajorInfo = $userDao->getUserName_d($MajorId);

		$ViceManager = $otherObj['ViceManager'];
		//�ж����һ���ַ��Ƿ񶺺�,�ǵĻ����ȥ����
		if(substr($ViceManager ,-1) == ',') {
			$ViceManager = substr($ViceManager ,0 ,-1);
		}
		$ViceManagerInfo = $userDao->getUserName_d($ViceManager);

		$info = array('directorId' => $otherObj['MajorId'].$otherObj['ViceManager'],
					'directorName' => $MajorInfo['USER_NAME'].','.$ViceManagerInfo['USER_NAME']);
		return $info;
	}

	/**
	 * ��ת���༭��ְ֪ͨҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('edit' ,true);
	}

	/**
	 * ��ת����ְ���ȱ�עҳ��
	 */
	function c_toEntryRemark() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('entryremark' ,true);
	}

	/**
	 * ��ת����ְ����ְλ�����ҳ��
	 */
	function c_toLinkApply() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('linkapply' ,true);
	}

	/**
	 * @author Administrator
	 *
	 */
	function c_changeEntryDate(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('change-edit');
	}

	/**
	 * �ʼ�֪ͨ
	 */
	function c_toCompanyEmail(){
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}

		$this->assign('probation' ,$obj['probation'] ? $obj['probation'] : 0); //������
		$this->assign('socialPlace' ,$obj['socialPlace'] ? $obj['socialPlace'] : '������'); //�籣�����

		//����
		if($obj['postType'] == 'YPZW-WY') {
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($obj['positionLevel']);
			$this->assign('positionLevelName', $WYlevel['personLevel']);
		} else {
			switch ($obj['positionLevel']) {
				case '1' :
					$this->assign('positionLevelName' ,'����');
					break;
				case '2' :
					$this->assign('positionLevelName' ,'�м�');
					break;
				case '3' :
					$this->assign('positionLevelName' ,'�߼�');
					break;
				default : $this->assign('positionLevelName' ,'');
					break;
			}
		}

		//�������š��������ź��ļ�����
		$deptDao = new model_deptuser_dept_dept();
		$deptObj = $deptDao->getSuperiorDeptById_d($obj['deptId']);
		$this->assign('deptNameS' ,$deptObj['deptNameS']);
		$this->assign('deptNameT' ,$deptObj['deptNameT']);
		$this->assign('deptNameF' ,$deptObj['deptNameF']);

		//����Э���ˣ�ȡ��������豸��Ϣ�����������ռ��ˣ�
		$xqdnMailUser = $this->service->getMailUser_d('interviewAddOffer_xqdn');
		$this->assign('xqdnUser' ,$xqdnMailUser['defaultUserName']);

		//�ʼ�����
		$toccMailId = $this->service->mailArr['sendUserId'];
		$toccMail = $this->service->mailArr['sendName'];
		$this->assign('toccMail' ,$toccMail);
		$this->assign('toccMailId' ,$toccMailId);
		$this->assign('title' ,"$obj[deptName]��Ա��($obj[userName])��ְ֪ͨ");
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true ,'oa_hr_entryNotice_email')); //��ʾ������Ϣ
		$this->view ('compmail' ,true);
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $_POST [$this->objName];
		$obj['state'] = $this->service->statusDao->statusEtoK ( 'save' ); //��ʼ��Ϊ����״̬
		$datadict = new model_system_datadict_datadict();
		$obj['addType'] = $datadict->getDataNameByCode($obj['addTypeCode']);
		$obj['wageLevelName'] = $datadict->getDataNameByCode($obj['wageLevelCode']);
		//��ֹ�Զ�ת��
		$obj['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//��������
		$obj['content'] = str_replace("<p>","",$obj['content']);
		$obj['content'] = str_replace("</p>","",$obj['content']);
		if($obj['parentId'] > 0) { //�ж��Ƿ��Ǵ�����������¼��֪ͨ������¼��֪ͨ
			$entryNoticeId = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"parentId='".$obj['parentId']."'",'id');
			if($entryNoticeId){
				$obj['id'] = $entryNoticeId;
			}
		} else if ($obj['applyResumeId'] > 0) { //�ж��Ƿ��Ǵ���Ա���뷢��¼��֪ͨ
			$entryNoticeId = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"applyResumeId='".$obj['applyResumeId']."'",'id');
			if($entryNoticeId){
				$obj['id'] = $entryNoticeId;
			}
		} else if ($obj['resumeId'] > 0) { //�ж��Ƿ��ǴӼ���������¼��֪ͨ
			$entryNoticeId = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"resumeId='".$obj['resumeId']."'",'id');
			if($entryNoticeId){
				$obj['id'] = $entryNoticeId;
			}
		}

		if($obj['id']) { //����༭
			$obj['isSave'] = '1'; //����Ϊ����״̬
			$editId = $this->service->edit_d($obj ,$isAddInfo);
		} else { //������Ӳ���
			$obj['isSave'] = '1'; //����Ϊ����״̬
			$id = $this->service->add_d ($obj, $isAddInfo);
		}

		if($_GET['isSave'] != 1) { //���з����ʼ�����
			$mailinfo = $_POST["interMail"];
			//��ֹ�Զ�ת��
			$mailinfo['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
			//��������
			$mailinfo['content'] = str_replace("<p>","",$mailinfo['content']);
			$mailinfo['content'] = str_replace("</p>","<br>",$mailinfo['content']);
			$mailinfo['isSender'] = 1;

			$uploadFile = new model_file_uploadfile_management ();
			$file = $uploadFile->getFilesByObjId ( $mailinfo['id'], 'oa_hr_entryNotice_email' );
			if ($file) {
				foreach ($file as $key => $val) {
                    $mailinfo['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
				}
			}

			$this->service->postEmail_d($_POST[$this->objName] ,$mailinfo);


			if(!$obj['id']) {
				$obj['id'] = $id;
			}
			$this->service->updateById(array('id' => $obj['id'] ,'isSave' => 0)); //�ʼ����ͺ�ȡ������״̬
		}

		if ($id) {
			if ($_GET['isSave'] != 1) {
				msg ( '���ͳɹ���' );
			} else {
				msg ( '����ɹ�' );
			}
		} else if($editId) {
			if ($_GET['isSave'] != 1) {
				msg ( '���ͳɹ���' );
			} else {
				msg ( '����ɹ�' );
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * �޸Ķ������
	 */
	function c_editDate($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ

		$object = $_POST [$this->objName];
		if(!empty($object['email']['TO_ID'])) {
			$this->service->postDateEmail_d($object);
		}
		unset($object['email']);

		if($this->service->updateById($object)){
			msg("�޸�ʱ��ɹ�");
		}
	}

	/**
	 * �޸Ķ������
	 */
	function c_compEmail() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST[$this->objName];

		$rs = true;
		if ($object['oldEntryDate'] != $object['entryDate']) { //�޸�����ְʱ��
			$rs = $this->service->updateById($object);
		}

		$mailinfo = $_POST["interMail"];
		//��ֹ�Զ�ת��
		$mailinfo['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//��������
		$mailinfo['content'] = str_replace("<p>","",$mailinfo['content']);
		$mailinfo['content'] = str_replace("</p>","<br>",$mailinfo['content']);
		$mailinfo['isSender'] = 1;

		$uploadFile = new model_file_uploadfile_management ();
		$file = $uploadFile->getFilesByObjId ( $object['id'], 'oa_hr_entryNotice_email' );
		if ($file) {
			foreach ($file as $key => $val) {
                $mailinfo['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
			}
		}

		$emailDao = new model_common_mail();
		$emailDao->mailWithFileGeneral($mailinfo['title'] ,$mailinfo['email']['TO_ID'] ,$mailinfo['content'] ,null ,$mailinfo['attachment']);


		if ($rs) {
			msg('���ͳɹ���');
		} else {
			msg('����ʧ�ܣ�');
		}
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		if(isset($_POST ['stateSearch'])) {
			switch($_POST ['stateSearch']) {
				case '1':
					$service->searchArr ['state'] = '1';
					$service->searchArr ['accountState'] = 0;
					$service->searchArr ['staffFileState'] = 0;
					$service->searchArr ['contractState'] = 0;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '2':
					$service->searchArr ['accountState'] = 1;
					$service->searchArr ['staffFileState'] = 0;
					$service->searchArr ['contractState'] = 0;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '3':
					$service->searchArr ['accountState'] = 1;
					$service->searchArr ['staffFileState'] = 1;
					$service->searchArr ['contractState'] = 0;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '4':
					$service->searchArr ['accountState'] = 1;
					$service->searchArr ['staffFileState'] = 1;
					$service->searchArr ['contractState'] = 1;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '5':
					$service->searchArr ['isSave'] = 1;
					$service->searchArr ['state'] = 1;
					break;
			}
		}
		$rows = $service->page_d ('select_list');
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)) {
			$leaveDao = new model_hr_leave_leave();
			foreach( $rows as $key => $val ){
				//ת��������
				if($rows[$key]['isSave'] == '1' && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '�������ʼ�';
				}else if($rows[$key]['accountState'] == 1 && $rows[$key]['staffFileState'] == 0 && $rows[$key]['contractState'] == 0 && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '�ѽ��˺�';
				}else if($rows[$key]['accountState'] == 1 && $rows[$key]['staffFileState'] == 1 && $rows[$key]['contractState'] == 0 && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '�ѽ�����';
				}else if($rows[$key]['accountState'] == 1 && $rows[$key]['staffFileState'] == 1 && $rows[$key]['contractState'] == 1 && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '��ǩ��ͬ';
				}else{
					$rows[$key]['stateC'] = $service->statusDao->statusKtoC($rows[$key]['state'] );
				}

				//��ȡ��ְ��Ϣ
				if ($val["state"] == 2 && !empty($val["userAccount"])) {
					$leaveObj = array();
					$leaveObj = $leaveDao->find(" userAccount='$val[userAccount]' AND ExaStatus='���' AND state<>4 ");
					//��ȡ��ְԭ���������
					$str = substr($leaveObj['quitReson'] ,-5);
					if ($str == "^nbsp") { //û�а�������ԭ��
						$leaveObj['quitReson'] = str_replace('^nbsp' ,"�� " ,$leaveObj['quitReson']);
					} else {
						$quitReson = '';
						$arr = explode("^nbsp" ,$leaveObj['quitReson']);
						for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //����������ԭ��
							$quitReson .= $arr[$i]."��";
						}
						$leaveObj['quitReson'] = $quitReson.$arr[$i]."��".$arr[$i + 1];
					}
					$rows[$key]["leaveReason"] = $leaveObj["quitReson"];
				} else {
					$rows[$key]["leaveReason"] = '';
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���鿴��ְ֪ͨҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		if($obj['interviewType'] == 1 || $obj['interviewType'] == 3 ){   //3�Ǽ������͹�������ְ֪ͨ

			$this->assign ( 'interviewTypeName', '��Ա����' );
		} else {
			$this->assign ( 'interviewTypeName', '�ڲ��Ƽ�' );
		}
		$this->view('view' );
	}

	/**
	 * �����ְ
	 */
	function c_doneEntry() {
		if($this->service->doneEntry_d( $_POST ['id'])) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ��ת��������ְҳ��
	 */
	function c_toCancelEntry() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view('cancelEntry' ,true);
	}

	/**
	 * ������ְ
	 */
	function c_cancelEntry() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		if($this->service->cancelEntry_d($_POST[$this->objName])) {
			msg("����ɹ�");
		} else {
			msg("����ʧ��");
		}
	}

	/**
	 * ��ת���鿴������ְҳ��
	 */
	function c_toViewCancel() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view('view-cancel');
	}

	/**
	 * ��ת���༭������ְԭ��ҳ��
	 */
	function c_toEditCancel() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		//����Ƹ�޸ķ�����ְԭ����Ҫ���ʼ�֪ͨ������
		$this->assign('mailInfo' ,isset($_GET['mailInfo']) ? 'yes' : 'no');
		$this->view('edit-cancel' ,true);
	}

	/**
	 * �༭������ְԭ��
	 */
	function c_editCancel() {
		$this->checkSubmit();
		if($this->service->editCancel_d($_POST[$this->objName])) {
			msg("����ɹ�");
		} else {
			msg("����ʧ��");
		}
	}

	function c_toExport(){
		$this->view('exportview');
	}

	function c_EntryExport(){
		$object = $_POST[$this->objName];

		if(!empty($object['beginDate'])) {
			$this->service->searchArr['preDateHope'] = $object['beginDate'];
		}
		if(!empty($object['endDate'])) {
			$this->service->searchArr['afterDateHope'] = $object['endDate'];
		}

		if(!empty($object['formCode'])) {
			$this->service->searchArr['formCode'] = $object['formCode'];
		}

		if(!empty($object['userName'])) {
			$this->service->searchArr['userNameSame'] = $object['userName'];
		}

		if(!empty($object['deptName'])) {
			$this->service->searchArr['deptName'] = $object['deptName'];
		}

		if(!empty($object['entryDateBegin'])) {
			$this->service->searchArr['entryDatefrom'] = $object['entryDateBegin'];
		}

		if(!empty($object['entryDateEnd'])) {
			$this->service->searchArr['entryDateto'] = $object['entryDateEnd'];
		}

		if(!empty($object['state'])) { //����״̬
			$state = implode(',' ,$object['state']);
			$this->service->searchArr['stateArr'] = $state;
		}

		set_time_limit(0); // ִ��ʱ��Ϊ������
		$rows = $this->service->listBySqlId('select_list');

		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$exportData = array();
		$applyObjs = array();
		$applyDao = new model_hr_recruitment_apply();
		foreach ( $rows as $key => $val ) {
			//��������
			switch ($val['interviewType']) {
				case '1':
					$val['interviewType'] = '��Ա����';
					break;
				case '2':
					$val['interviewType'] = '�ڲ��Ƽ�';
					break;
				// case '3':
				// 	$val['interviewType'] = '��Ա����'; //3�Ǽ������͹�������ְ֪ͨ
				// 	break;
				default:
					$val['interviewType'] = '';
					break;
			}

			//��Ա������Ϣ
			if ($val['sourceId'] > 0) {
				if (empty($applyObjs[$val['sourceId']])) {
					$applyObj = $applyDao->get_d($val['sourceId']);
					$applyObjs[$val['sourceId']] = $applyObj;
					$val['workArrange'] = $applyObj['workArrange']; //�������ڵĹ�������
					$val['assessmentIndex'] = $applyObj['assessmentIndex']; //�����ڽ��������Ҫ����ָ��
				} else {
					$val['workArrange'] = $applyObjs[$val['sourceId']]['workArrange']; //�������ڵĹ�������
					$val['assessmentIndex'] = $applyObjs[$val['sourceId']]['assessmentIndex']; //�����ڽ��������Ҫ����ָ��
				}
			} else {
				$val['workArrange'] = ''; //�������ڵĹ�������
				$val['assessmentIndex'] = ''; //�����ڽ��������Ҫ����ָ��
			}

			$exportData[$key]['formCode'] = $val['formCode'];
			$exportData[$key]['formDate'] = $val['formDate'];
			$exportData[$key]['userAccount'] = $val['userAccount'];
			$exportData[$key]['userName'] = $val['userName'];
			$exportData[$key]['sex'] = $val['sex'];
			$exportData[$key]['phone'] = $val['phone'];
			$exportData[$key]['email'] = $val['email'];
			$exportData[$key]['interviewType'] = $val['interviewType'];
			$exportData[$key]['resumeCode'] = $val['resumeCode'];
			$exportData[$key]['hrSourceType2Name'] = $val['hrSourceType2Name'];
			$exportData[$key]['entryDate'] = $val['entryDate'];
			$exportData[$key]['state'] = $this->service->statusDao->statusKtoC($val['state']);
			$exportData[$key]['applyCode'] = $val['applyCode'];
			$exportData[$key]['positionsName'] = $val['positionsName'];
			$exportData[$key]['developPositionName'] = $val['developPositionName'];
			$exportData[$key]['deptName'] = $val['deptName'];
			$exportData[$key]['workPlace'] = $val['workProvince'].'-'.$val['workCity'];
			$exportData[$key]['useHireTypeName'] = $val['useHireTypeName'];
			$exportData[$key]['useAreaName'] = $val['useAreaName'];
			$exportData[$key]['sysCompanyName'] = $val['sysCompanyName'];
			$exportData[$key]['assistManName'] = $val['assistManName'];
			$exportData[$key]['assistManPhone'] = $this->service->get_table_fields('oa_hr_personnel' ,"userAccount='".$val['assistManId']."'" ,'mobile');
			$exportData[$key]['useDemandEqu'] = $val['useDemandEqu'];
			$exportData[$key]['useSign'] = $val['useSign'];
			$exportData[$key]['probation'] = $val['probation'];
			$exportData[$key]['contractYear'] = $val['contractYear'];
			$exportData[$key]['hrSourceType1Name'] = $val['hrSourceType1Name'];
			$exportData[$key]['hrJobName'] = $val['hrJobName'];
			$exportData[$key]['hrIsManageJob'] = $val['hrIsManageJob'];
			$exportData[$key]['workArrange'] = $val['workArrange'];
			$exportData[$key]['assessmentIndex'] = $val['assessmentIndex'];
		}
		return model_hr_recruitment_entryNoticeExportUtil::export2ExcelUtil ( $exportData );
	}


	/**
	 * ��������
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['entryNotice']['listSql']))));
		$this->view('excelout-select');
	}

	/**
	 * ��������
	 */
	function c_selectExcelOut(){
		$rows = array();//���ݼ�
		$listSql = str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				if($rows[$key]['accountState']==1&&$rows[$key]['staffFileState']==0&&$rows[$key]['contractState']==0&&$rows[$key]['state']!=2){
					$rows[$key]['state']='�ѽ��˺�';
				}else if($rows[$key]['accountState']==1&&$rows[$key]['staffFileState']==1&&$rows[$key]['contractState']==0&&$rows[$key]['state']!=2){
					$rows[$key]['state']='�ѽ�����';
				}else if($rows[$key]['accountState']==1&&$rows[$key]['staffFileState']==1&&$rows[$key]['contractState']==1&&$rows[$key]['state']!=2){
					$rows[$key]['state']='��ǩ��ͬ';
				}else{
					$rows[$key]['state']=$this->service->statusDao->statusKtoC($rows[$key]['state'] );
				}
			}
		}
		$colNameArr = array();//��������
		include_once ("model/hr/recruitment/entryNoticeFieldArr.php");
		if(is_array($_POST['entryNotice'])){
			foreach($_POST['entryNotice'] as $key=>$val){
					foreach($entryNoticeFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
		$newColArr = array_combine($_POST['entryNotice'],$colNameArr);//�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['entryNotice']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		};

		return model_hr_personnel_personnelExcelUtil::excelOutEntryNotice($newColArr,$dataArr);
	}

	/**
	 * ������ְID���´���ְ����
	 */
	function c_updateBeEntryNumById( $id ) {
		return $this->service->updateBeEntryNumById_d( $id );
	}

	/**
	 * ��ת����д��ְԭ��ҳ��
	 */
	function c_toAddDepart() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		//����Ƹ��д��ְԭ����Ҫ���ʼ�֪ͨ������
		$this->assign('mailInfo' ,isset($_GET['mailInfo']) ? 'yes' : 'no');
		$this->view('add-depart' ,true);
	}

	/**
	 * ��д��ְԭ��
	 */
	function c_addDepart() {
		$this->checkSubmit();
		if($this->service->addDepart_d($_POST[$this->objName])) {
			msg("����ɹ�");
		} else {
			msg("����ʧ��");
		}
	}

	/**
	 * ��ת���鿴������ְҳ��
	 */
	function c_toViewDepart() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view('view-depart');
	}
 }
?>