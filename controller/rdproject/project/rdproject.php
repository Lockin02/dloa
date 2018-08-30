<?php
/**
 * @description: ��Ŀaction
 * @date 2010-9-13 ����05:21:37
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_project_rdproject extends controller_base_action {

	/**
	 * @desription ���캯��
	 * @date 2010-9-13 ����05:22:39
	 */
	function __construct() {
		$this->objName = "rdproject";
		$this->objPath = "rdproject_project";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/

	/****************************������********************************/
	/**
	 * @desription ������תҳ��
	 * @param tags
	 * @date 2010-9-26 ����06:45:20
	 */
	function c_rpToWork() {
		$this->show->display ( 'common_myApproval' );
	}

	/**
	 * @desription ������תҳ��
	 * @param tags
	 * @date 2010-9-26 ����06:45:20
	 */
	function c_menu() {
		$this->show->display ( 'common_myApprovalMenu' );
	}

	/**
	 * @desription ������
	 * @param tags
	 * @date 2010-9-26 ����07:14:14
	 */
	function c_rpApprovalNo() {
		$seachPjName = isset ( $_GET ['searchPjName'] ) ? $_GET ['searchPjName'] : "";
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		if ($seachPjName) {
			$service->searchArr ['seachProjectName'] = $seachPjName;
		}
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 0;
		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$rows = $service->rpApprovalPage_d ();
		$this->pageShowAssign (); //���÷�ҳ��ʾ
		$this->show->assign ( 'searchPjName', $seachPjName );
		$this->show->assign ( 'list', $service->rpApprovalNo_s ( $rows ) );
		$this->display ( 'my-ApprovalNo' );
	}

	/**
	 * @desription ������
	 * @param tags
	 * @date 2010-9-26 ����07:14:14
	 */
	function c_rpApprovalYes() {
		$seachPjName = isset ( $_GET ['searchPjName'] ) ? $_GET ['searchPjName'] : "";
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		if ($seachPjName) {
			$service->searchArr ['seachProjectName'] = $seachPjName;
		}
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$rows = $service->rpApprovalPage_d ();
		$this->pageShowAssign (); //���÷�ҳ��ʾ
		$this->show->assign ( 'searchPjName', $seachPjName );
		$this->show->assign ( 'list', $service->rpApprovalYes_s ( $rows ) );
		$this->display ( 'my-ApprovalYes' );
	}

	/*****************************************************************/

	/**
	 * @desription ��ת�����Ŀaction
	 * @date 2010-9-19 ����11:22:49
	 */
	function c_rpToAdd() {
		$pjId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : "";

		$this->showDatadicts ( array ('projectType' => 'YFXMGL' ) );
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ) );
		if ($pjId != "") {
			$arr = $this->service->rpParentById_d ( $pjId );
			foreach ( $arr as $key => $val ) {
				$this->show->assign ( "id", $arr [$key] ['id'] );
				$this->show->assign ( "groupName", $arr [$key] ['groupName'] );
				$this->show->assign ( "groupCode", $arr [$key] ['groupCode'] );
			}
			$this->display ( 'add' );
		} else {
			$this->display ( 'upadd' );
		}
	}

	/**
	 * @desription ��ת�����Ŀaction��������������
	 * @date 2010-9-19 ����11:22:49
	 */
	function c_toAddNoApproval() {
			if(!$this->service->this_limit['�����Ŀ']){
				echo "<script>alert('û��Ȩ�޽��в���!');self.parent.tb_remove();</script>";
				exit();
			}
		$pjId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : "";

		$this->showDatadicts ( array ('projectType' => 'YFXMGL' ) );
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ) );
		if ($pjId != "") {
			$arr = $this->service->rpParentById_d ( $pjId );
			foreach ( $arr as $key => $val ) {
				$this->show->assign ( "id", $arr [$key] ['id'] );
				$this->show->assign ( "groupName", $arr [$key] ['groupName'] );
				$this->show->assign ( "groupCode", $arr [$key] ['groupCode'] );
			}
			$this->display ( 'noapproval-add' );
		} else {
			$this->display ( 'noapproval-upadd' );
		}
	}

	/**
	 * @desription ��ӱ�����Ŀaction
	 * @date 2010-9-15 ����07:19:11
	 */
	function c_rpAdd() {
		$objArr = $_POST [$this->objName];
		if ($objArr ['groupSName'] == "root" || $objArr ['groupSName'] == "") {
			$objArr ['groupSName'] = "root";
			$objArr ['groupId'] = - 1;
		}
		$objArr ['businessCode'] = $this->businessCode ();
		$objArr ['status'] = $this->service->statusDao->statusEtoK ( 'save' );
		$objArr ['effortRate'] = 0;
		$objArr ['warpRate'] = 0;
		$id = $this->service->rpAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "�����Ŀ��" . $objArr ['projectName'] . "��"; //��������
			$this->behindMethod ( $objArr );
			msgGo ( '��ӳɹ���', "?model=rdproject_project_rdproject&action=addSuccessTo&pjId=$id" );
		} else {
			msgGo ( '���ʧ�ܣ�' );
		}
	}

	/**
	 * @desription ��ӱ�����Ŀaction(��������)
	 * @date 2010-9-15 ����07:19:11
	 */
	function c_rpAddNoApproval() {
		$objArr = $_POST [$this->objName];
		if ($objArr ['groupSName'] == "root" || $objArr ['groupSName'] == "") {
			$objArr ['groupSName'] = "root";
			$objArr ['groupId'] = - 1;
		}
		$objArr ['businessCode'] = $this->businessCode ();
		$objArr ['status'] = $this->service->statusDao->statusEtoK ( 'execute' );
		$objArr ['ExaStatus'] ="���";
		$objArr ['effortRate'] = 0;
		$objArr ['warpRate'] = 0;
		$id = $this->service->rpAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "�����Ŀ��" . $objArr ['projectName'] . "��"; //��������
			$this->behindMethod ( $objArr );
			msg ( '��ӳɹ���' );
		} else {
			msg ( '���ʧ�ܣ�' );
		}
	}

	/**
	 * @desription ������Ŀ����ת��ʱҳ�棬���б��ˢ�¼���ת���޸�ҳ��
	 */
	function c_addSuccessTo() {
		$this->show->assign ( "id", $_GET ['pjId'] );
		$this->display ( 'addsuccess' );
	}

	/**
	 * @desription �޸���Ŀ���ȼ���ת
	 * @author zengzx
	 * @date 2011��10��25�� 15:43:36
	 * TODO:
	 */
	function c_rpModifyLevel() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->display ( 'modifylevel' );

	}

	/**
	 * @desription �޸���Ŀ���ȼ�
	 * @author zengzx
	 * @date 2011��10��25�� 15:43:36
	 */
	function c_rpUpdateLevel() {
		$project = $_POST [$this->objName];
		$projectObj = $this->service->rpArrById_d($project['id']);
		if ($this->service->edit_d ( $project, true )) {
			$projectTemp[0] = $project;
			$projectTemp = $this->service->datadictArrName ( $projectTemp, 'projectLevel', 'projectLevelC', 'XMYXJ' );
			$project = $projectTemp[0];
			$project ['operType_'] = "�޸���Ŀ��" . $project ['projectName'] . "�����ȼ�"; //��������
			$project ['operateLog_'] = "����Ŀ���ȼ��ɡ�".$projectObj[0]['projectLevelC']."���޸ĳɡ�".$project['projectLevelC']."��"; //��������
			$this->behindMethod ( $project );
			echo "<script>alert('����ɹ�');parent.location.reload();</script>";
		} else {
			msg ( "����ʧ��" );
		}
	}
	/**
	 * @desription �޸���Ŀ��תaction
	 * @date 2010-9-16 ����03:02:07
	 */
	function c_rpUpdateTo() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->show->assign ( "groupId", $arr [0] ['groupId'] );
		$this->show->assign ( "groupName", $arr [0] ['groupSName'] );
		$this->display ( 'update' );

	}

	/**
	 * @desription �޸���Ŀ��תaction
	 * @date 2010-9-16 ����03:02:07
	 */
	function c_toEditProject() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] :null;
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->display ( 'edit' );

	}

	/**
	 * @desription �޸���������Ŀ������Ϣ
	 */
	function c_editAfterAduit() {
		$project = $_POST [$this->objName];

		if ($this->service->edit_d ( $project, true )) {
			msg( "����ɹ�" );
		} else {
			msg ( "����ʧ��" );
		}
	}

	/**
	 * @desription �޸���Ŀaction
	 * @date 2010-9-16 ����03:02:07
	 */
	function c_rpUpdate() {
		$project = $_POST [$this->objName];
		//Ȩ���ж�
		$this->isCurUserPerm ( $project ["id"], 'project_baseInfo_update' );

		if ($this->service->edit_d ( $project, true )) {
			$project ['operType_'] = "�޸���Ŀ��" . $project ['projectName'] . "��"; //��������
			$this->behindMethod ( $project );
//			echo "<script>self.window.parent.show_page();</script>";
			echo "<script>if(self.window.opener){self.window.opener.show_page();}else{self.window.parent.show_page();}</script>";
			msgGo ( "����ɹ�" );
		} else {
			msgGo ( "����ʧ��" );
		}
	}

	/**
	 * @desription ��ת�ر���Ŀaction
	 * @param tags
	 * @date 2010-10-5 ����02:52:10
	 */
	function c_rpCloseTo() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		$this->show->assign ( "dateTime", date ( "Y-m-d" ) );
		$this->show->assign ( "userName", $_SESSION ['USERNAME'] );
		$this->show->assign ( "pjId", $pjId );
		$this->display ( 'close' );
	}

	/**
	 * @desription �ر���Ŀ
	 * @param tags
	 * @date 2010-10-5 ����03:19:55
	 */
	function c_rpClose() {
		$object = $_POST;
		$this->isCurUserPerm ( $object ["id"], 'project_close' );
		$updateArr = array ();
		if ($object ['closeType'] == 0) {
			$updateArr ['status'] = $this->service->statusDao->statusEtoK ( "end" );
		} else {
			$updateArr ['status'] = $this->service->statusDao->statusEtoK ( "close" );
		}
		$updateArr ['closeDescription'] = $object ['closeText'];
		$updateArr ['actEndDate'] = date ( "Y-m-d" );
		$updateArr ['id'] = $object ['id'];
		if ($this->service->edit_d ( $updateArr, true )) {
			$object ['operType_'] = "�ر���Ŀ��" . $object ['projectName'] . "��"; //��������
			$this->behindMethod ( $object );
			msg ( "�������" );
		} else {
			msg ( "����ʧ��" );
		}
	}

	/**
	 * @desription ɾ����Ŀ
	 * @param tags
	 * @date 2010-10-6 ����04:03:29
	 */
	function c_rpDel() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$this->isCurUserPerm ( $pjId, 'project_del' );
		$objArr = $this->service->rpArrById_d ( $pjId );
		if ($this->service->deletes ( $pjId )) {
			$objArr ['0'] ['operType_'] = "ɾ����Ŀ��" . $objArr ['0'] ['projectName'] . "��";
			$this->behindMethod ( $objArr ['0'] );
			msgGo ( 'ɾ���ɹ���' );
		} else {
			msgGo ( 'ɾ��ʧ�ܣ�' );
		}
	}

	/**
	 * @desription �ҵ���Ŀ
	 * @date 2010-9-25 ����09:53:19
	 */
	function c_rpPageMy() {
		$service = $this->service;
		$service->resetParam ();
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['managerUser'] = $_SESSION ['USER_ID'];
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$rows = $service->rpPage_d ();
		$this->pageShowAssign (); //���÷�ҳ��ʾ
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'my-list' );
	}

	/*******************************************/

	/**
	 * @desription ��Ŀ����������б�
	 * @param tags
	 * @date 2010-9-20 ����09:55:13
	 */
	function c_rpPagePending() {
		$this->display ( 'listPending' );
	}

	/**
	 * @desription ����������ҳ��
	 * @param tags
	 * @date 2010-10-2 ����11:31:12
	 */
	function rpPagePendingSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$seachArr ['createUser'] = $_SESSION ['USER_ID'];
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" );
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //���÷�ҳ��ʾ
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachPending' );
	}

	/*******************************************/

	/**
	 * @desription ��Ŀ�����������б�
	 * @param tags
	 * @date 2010-9-27 ����06:23:35
	 */
	function c_rpPageApproved() {
		$this->display ( 'listApproved' );
	}

	/**
	 * @desription ����������ҳ��
	 * @param tags
	 * @date 2010-10-2 ����11:31:12
	 */
	function rpPageApprovedSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$seachArr ['createUser'] = $_SESSION ['USER_ID'];
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "close" ) . "," . $statusDao->statusEtoK ( "end" );
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //���÷�ҳ��ʾ
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachApproved' );
	}

	/*******************************************/


	/**
	 * @desription ��ת����Ŀ�б�ҳ
	 */
	function c_rpPageCenter() {
		$this->assign('projectType',$_GET['projectType']);
		$this->assign('statusStr','');
		if(isset($_GET['ondoStatus'])){
			$this->assign('statusStr','ondoStatus');
		}
		if(isset($_GET['finishedStatus'])){
			$this->assign('statusStr','finishedStatus');
		}
		$this->display ( 'listCenter' );
	}

	/**
	 * @desription ��Ŀ�б�����ҳ��
	 * @param tags
	 * @date 2010-10-2 ����11:31:12
	 */
	function rpPageCenterSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" );
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //���÷�ҳ��ʾ
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachCenter' );
	}

	/*******************************************/

	/**
	 * @desription ��ת���ҵ���Ŀ�б�ҳ
	 */
	function c_rpPageMine() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('projectType',$_GET['projectType']);
		$this->assign('statusStr','');
		if(isset($_GET['ondoStatus'])){
			$this->assign('statusStr','ondoStatus');
		}
		if(isset($_GET['finishedStatus'])){
			$this->assign('statusStr','finishedStatus');
		}
		$this->display ( 'listMine' );
	}

	/**
	 * @desription �ҵ���Ŀ����ҳ��
	 * @param tags
	 * @date 2010-10-2 ����11:31:12
	 */
	function rpPageMineSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$seachArr ['myPUser'] = $_SESSION ['USER_ID'];
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" );
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //���÷�ҳ��ʾ
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachMine' );
	}

	/*******************************************/

	/**
	 * @desription ��ת����Ŀ�б�ҳ
	 */
	function c_page() {
		$this->display ( 'list' );
	}

	/**
	 * @desription ��Ŀ�����б�action
	 * @date 2010-9-17 ����10:15:37
	 */
	function c_rpPage() {
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->rpPage_d ();
		$this->pageShowAssign (); //���÷�ҳ��ʾ
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'center-list' );
	}

	/**
	 * @desription ��ת�߼�����ҳ��
	 * @date 2010-10-2 ����10:51:06
	 */
	function c_rpSeachAdvanced() {
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : exit ();
		$this->show->assign ( 'type', $type );
		$this->showDatadicts ( array ('projectType' => 'YFXMGL' ) );
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ) );
		$this->display ( 'seachAdvanced' );
	}

	/**
	 * @desription ����ͳһ��ת�б�
	 * @param tags
	 * @date 2010-10-2 ����11:37:26
	 */
	function c_rpSeachPage() {
		//		echo "<pre>";
		$type = isset ( $_POST ) ? $_POST ['type'] : exit ();
		$arr = isset ( $_POST ['rdproject'] ) ? $_POST ['rdproject'] : exit ();
		$seachArr = array ();
		if ($this->seachNull ( $arr ['groupSName'] ) && $this->seachNull ( $arr ['groupId'] )) {
			$seachArr ['seachGroupSName'] = $arr ['groupSName'];
		}

		if ($this->seachNull ( $arr ['projectCode'] )) {
			$seachArr ['seachProjectCode'] = $arr ['projectCode'];
		}

		if ($this->seachNull ( $arr ['projectName'] )) {
			$seachArr ['seachProjectName'] = $arr ['projectName'];
		}

		if ($this->seachNull ( $arr ['simpleName'] )) {
			$seachArr ['seachSimpleName'] = $arr ['simpleName'];
		}

		if ($this->seachNull ( $arr ['projectType'] )) {
			$seachArr ['seachProjectType'] = $arr ['projectType'];
		}

		if ($this->seachNull ( $arr ['projectLevel'] )) {
			$seachArr ['seachProjectLevel'] = $arr ['projectLevel'];
		}

		if ($this->seachNull ( $arr ['planDateStartS'] )) {
			$seachArr ['seachPlanDateStartS'] = $arr ['planDateStartS'];
		}

		if ($this->seachNull ( $arr ['planDateStartB'] )) {
			$seachArr ['seachPlanDateStartB'] = $arr ['planDateStartB'];
		}

		if ($this->seachNull ( $arr ['planDateCloseS'] )) {
			$seachArr ['seachPlanDateCloseS'] = $arr ['planDateCloseS'];
		}

		if ($this->seachNull ( $arr ['planDateCloseB'] )) {
			$seachArr ['seachPlanDateCloseB'] = $arr ['planDateCloseB'];
		}

		if ($this->seachNull ( $arr ['managerName'] )) {
			$seachArr ['seachManagerName'] = $arr ['managerName'];
		}

		if ($this->seachNull ( $arr ['assistantName'] )) {
			$seachArr ['seachAssistantName'] = $arr ['assistantName'];
		}

		if ($this->seachNull ( $arr ['description'] )) {
			$seachArr ['seachDescription'] = $arr ['description'];
		}

		$this->show->assign ( 'backUrl', $_POST ['basicUrl'] );
		if ($type == "pending") {
			$this->rpPagePendingSeach ( $seachArr );
		} else if ($type == "approved") {
			$this->rpPageApprovedSeach ( $seachArr );
		} else if ($type == "mine") {
			$this->rpPageMineSeach ( $seachArr );
		} else if ($type == "center") {
			$this->rpPageCenterSeach ( $seachArr );
		}
	}

	function seachNull($val) {
		if (isset ( $val ) && $val != "") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @desription �鿴��Ŀ
	 * @date 2010-9-17 ����11:41:59
	 */
	function c_rpRead() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		$this->show->assign ( 'pjId', $pjId );
		$actType=isset($_GET['readType'])?$_GET['readType']:null;
		$this->show->assign("actType",$actType);//����ҳ��(һ��Ĳ鿴ҳ�桢��Ƕ����������)
		$this->display ( 'read' );
	}

	/**
	 * @desription �鿴��Ŀ������Ϣ
	 * @param tags
	 * @date 2010-10-5 ����05:19:50
	 */
	function c_rpBasicMsg() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		$this->show->assign ( 'pjId', $pjId );
		$actType=isset($_GET['readType'])?$_GET['readType']:null;
		$this->show->assign("actType",$actType);//����ҳ��(һ��Ĳ鿴ҳ�桢��Ƕ����������)
		$this->display ( 'basicMsg' );
	}

	/**
	 * @desription �򿪹�����Ŀ
	 * @param tags
	 * @date 2010-10-6 ����02:40:09
	 */
	function c_rpOpenManage() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->show->assign ( 'pjId', $pjId );
		$this->display ( 'openManage' );
	}

	/**
	 * @desription ���������Ϣ
	 * @param tags
	 * @date 2010-10-6 ����03:43:42
	 */
	function c_rpManageBasic() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		//Ȩ���ж�
		$role = $this->service->isCurUserPerm (  $_GET ['pjId'], 'project_level_modify' );
		$modifyLevel = '';
		if($role){
			$modifyLevel = "<span id='modifyLevel' onclick='mLevelFun();'><font color=green>  �޸�</font></span>";
		}
		$this->assign( 'rdproject[projectLevelC]',$arr[0]['projectLevelC'].$modifyLevel );
		$this->show->assign ( 'pjId', $pjId );
		$this->display ( 'manageBasic' );
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊajax����json����---------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ajax�ж���Ŀ�����Ƿ��ظ�
	 * @date 2010-9-13 ����02:22:04
	 */
	function c_ajaxProjectName() {
		$projectName = isset ( $_GET ['projectName'] ) ? $_GET ['projectName'] : false;
		$searchArr = array ("ajaxProjectName" => $projectName );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**�ж���Ŀ����Ƿ��ظ�
	*author can
	*2011-7-28
	*/
	function c_checkProjectCode() {
		$projectCode = isset ( $_GET ['projectCode'] ) ? $_GET ['projectCode'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$searchArr = array ("yprojectCode" => $projectCode );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * @desription �ҵĽ��ȼƻ�-��Ŀ
	 * @date 2010-9-25 ����09:53:19
	 */
	function c_rpAjaxMyPlan() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ȡȨ��
		$service->searchArr = $this->service->filterCustom ( array ('��Ŀ����', '�ƻ����ȼ�' ), array ('ft_projectType', 'ft_projectLevel' ), $service->searchArr );
		$service->searchArr ['myPUser'] = $_SESSION ['USER_ID'];
		$statusDao = $service->statusDao;
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "execute" );
		$rows = $service->rpPage_d ();
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_rpAjaxAllPlan() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//TODO:
		if (isset ( $func_limit ['��Ŀ����'] )) {
			$service->searchArr ['projectType'] = $func_limit ['��Ŀ����'];
		}
		$service->searchArr ['statusArr'] = $service->statusDao->statusEtoK ( "execute" );
		$rows = $service->rpPage_d ();
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_treeNodeWork() {
		echo "��ʼ��";
		$treeDao = new model_treeNode2 ();
		if ($treeDao->createTreeLRValue ()) {
			echo "<br>�ɹ���";
		} else {
			echo "<br>ʧ����";
		}
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	//	function c_pageJson() {
	//		$service = $this->service;
	//		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
	//		$statusDao = $service->statusDao;
	//		$seachArr ['statusArr'] =  $statusDao->statusEtoK ( "execute" ) ;
	//		$serv xice->searchArr = $seachArr;
	//		$rows = $service->page_d ();
	//		$arr = array ();
	//		$arr ['collection'] = $rows;
	//		$arr ['totalSize'] = $service->count;
	//		echo util_jsonUtil::encode ( $arr );
	//	}


	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$statusDao = $service->statusDao;
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "execute" );
		$service->searchArr = $seachArr;
		$rows = $service->pageBySqlId ( "select_pjdata" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		echo util_jsonUtil::encode ( $arr );
	}


	function c_pageJson2() {   //combogrid����ѡ��
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$statusDao = $service->statusDao;
		$service->searchArr['statusArr'] = $statusDao->statusEtoK ( "execute" );

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**һ��ͨ�����б�pageJson
	 * add by suxc    2011-08-16
	 */
	function c_pageJsonByOnekey() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$statusDao = $service->statusDao;
		if($this->service->this_limit['ѡ����Ŀ']){
			$service->searchArr['statusArr'] = $statusDao->statusEtoK ( "execute" );
		}else{
			$service->searchArr['statusArr'] = $statusDao->statusEtoK ( "execute" );
			$service->searchArr['myPUser'] = $_SESSION ['USER_ID'];
		}
		$rows = $service->pageBySqlId ( "select_pjdata" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ������Ŀ�б����ݷ���(����û����Ŀ��ϵ���Ŀ��һ����Ŀ���)
	 * add by chengl 2011-04-07
	 */
	function c_projectAndGroup() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='c.type desc,c.statusGroup desc,c.createTime';
		$service->asc=true;
		$rows = $service->projectAndGroup_d ();
		if ($_GET ['parentId'] == PARENT_ID) {
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
		} else {
			echo util_jsonUtil::encode ( $rows );
		}
	}
	/**
	 * �����ҵ���Ŀ�б����ݷ���(����û����Ŀ��ϵ���Ŀ��һ����Ŀ���)
	 * add by chengl 2011-04-09
	 */
	function c_myProjectAndGroup() {
		$service = $this->service;
		$service->sort='c.type desc,c.statusGroup desc,c.createTime';
		$service->asc=true;
		$service->getParam ( $_REQUEST );
		$service->searchArr ['myPUser'] = $_SESSION ['USER_ID'];
//		$service->searchArr ['projectType'] = $_GET['projectType'];
		if(isset($_GET['ondoStatus'])){
			$service->searchArr ['ondoStatus'] = $_GET['ondoStatus'];
		}
		if(isset($_GET['finishedStatus'])){
			$service->searchArr ['finishedStatus'] = $_GET['finishedStatus'];
		}
		$rows = $service->projectAndGroup_d (true);
//		if ($_GET ['parentId'] == PARENT_ID) {//��Ŀ���parentId=-1��Ϊ��Ŀ���
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
//		} else {
//			echo util_jsonUtil::encode ( $rows );
//		}
	}

	/**
	 * ���ش�������Ŀ�б����ݷ���(����û����Ŀ��ϵ���Ŀ��һ����Ŀ���)
	 * add by chengl 2011-04-09
	 */
	function c_projectAndGroupPending() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$statusDao = $service->statusDao;
		$service->searchArr ['createUser'] = $_SESSION ['USER_ID'];
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" );
		$service->searchArr ['ortype'] = "(c.type=2 and c.parentId=" . $_GET ['parentId'] . ")";
		$rows = $service->projectAndGroup_d ();
		if ($_GET ['parentId'] == PARENT_ID) {
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
		} else {
			echo util_jsonUtil::encode ( $rows );
		}
	}

	/**
	 * ���ش�������Ŀ�б����ݷ���(����û����Ŀ��ϵ���Ŀ��һ����Ŀ���)
	 * add by chengl 2011-04-09
	 */
	function c_projectAndGroupApproved() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$statusDao = $service->statusDao;
		$searchArr = $service->searchArr;
		$service->searchArr ['createUser'] = $_SESSION ['USER_ID'];
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" );
		$service->searchArr ['ortype'] = "(c.type=2 and c.parentId=" . $_GET ['parentId'] . ")"; //or����Ҫ�ŵ����
		$rows = $service->projectAndGroup_d ();
		if ($_GET ['parentId'] == PARENT_ID) {
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
		} else {
			echo util_jsonUtil::encode ( $rows );
		}
	}



	/**
	 * @desription �޸���Ŀ��תaction
	 * @date 2011��6��28�� 14:34:31
	 */
	function c_rpUpdateToOld() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->show->assign ( "groupId", $arr [0] ['groupId'] );
		$this->show->assign ( "groupName", $arr [0] ['groupSName'] );
		$this->display ( 'updateOld' );

	}


	/**
	 * @desription �޸���Ŀaction
	 * @date 2011��6��28�� 14:29:51
	 */
	function c_rpUpdateOld() {
		$project = $_POST [$this->objName];
		//Ȩ���ж�
//		$this->isCurUserPerm ( $project ["id"], 'project_baseInfo_update' );

		if ($this->service->edit_d ( $project, false )) {
			echo "<script>if(self.window.opener){self.window.opener.show_page();}else{self.window.parent.show_page();}</script>";
			msgGo ( "����ɹ�" );
		} else {
			msgGo ( "����ʧ��" );
		}
	}

	/**
	 * �з���Ŀѡ��
	 */
	function c_pageForDL(){
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}

	/**
	 * �з���Ŀѡ�� - �Ӷ����ı���ѡȡ����
	 */
	function c_pageJsonForDL(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );

		//$service->asc = false;
		$rows = $service->page_d ('select_DL');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ͬ��Ŀѡ�� - ������ͬ��Ĺ�����Ŀ�ͺ�ͬ����з���Ŀ
	 */
	function c_pageForAll(){
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "selectall" );
	}

	/**
	 * �з���Ŀѡ�� - �Ӷ����ı���ѡȡ����
	 */
	function c_pageJsonForAll(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );

		//$service->asc = false;
		$rows = $service->page_d ('select_all');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
}
?>
