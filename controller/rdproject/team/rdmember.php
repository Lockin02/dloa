<?php
/*
 * Created on 2010-9-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_rdproject_team_rdmember extends controller_base_action {
	function __construct() {
		$this->objName = "rdmember";
		$this->objPath = "rdproject_team";
		parent::__construct ();
	}
	/**
	 * Ĭ���б�ҳ��
	 * ��ʾ��Ա
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		$this->pageShowAssign ();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonProject() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.memberId';
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->page_d ('select_joinproject');
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
	 * �����Ŀ��Ա
	 */
	function c_player() {
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		$service->searchArr ['isUsing'] = '1';
		$rows = $service->page_d ();

		$this->show->assign ( 'rdproject[id]',$_GET ['pjId'] );
		$this->show->assign ( 'id', $_GET ['pjId'] );
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * �ҵ���Ŀ-����Ŀ����ʾ��Ա
	 */
	function c_playerInOption() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$service->searchArr ['isUsing'] = '1';
		$rows = $service->page_d ();

		$this->show->assign ( 'rdproject[id]',$pjId );
		$this->show->assign ( 'id', $_GET ['pjId'] );
		if (! empty ( $_GET['pjId'] )) {
			//Ȩ���ж�
			$isPerm = $this->isCurUserPermAjax ( $_GET['pjId'], 'project_teamMember_admin' );
		}
		$this->show->assign ( 'list', $service->showlist ( $rows ,$isPerm ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-addInOption' );
	}

	/**
	 * ��ʾ����ڲ���Ա
	 */
 	function c_toAddMember(){
 		$proCenter=$_GET['proCenter'];
		if (! empty ( $_GET['projectId'] )&&$proCenter!=1) {
			//Ȩ���ж�
			$this->isCurUserPerm ( $_GET['projectId'], 'project_teamMember_admin' );
		}
 		$this->show->assign ( 'projectId', $_GET ['projectId'] );
 		$this->show->display ( $this->objPath . '_' . $this->objName . '-toAdd' );
 	}

	/**
	 * ����ڲ���Ա+���³�Ա��ɫ��������Է�����������
	 */
	function c_addByGroud() {
		if ($this->service->addByGroudIndpt ( $_POST [$this->objName] )) {
			msg ( '����ɹ�' );
		} else {
			msg ( '����ʧ��' );
		}
	}

	/**
	 * ����ⲿ��Ա-��ʾ
	 */
	function c_inOutsideInfo() {
 		$proCenter=$_GET['proCenter'];
//		if (! empty ( $_GET['projectId'] )&&$proCenter!=1) {
//			//Ȩ���ж�
//			$this->isCurUserPerm ( $_GET['projectId'], 'project_task' );
//		}
		$this->show->assign ( 'projectId', $_GET ['projectId'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-addoutside' );
	}

	/**
	 * ����ⲿ��Ա-����
	 */
	function c_addOutside() {
		if ($this->service->add ( $_POST [$this->objName] )) {
			msg ( '��ӳɹ�' );
		} else {
			msg ( '���ʧ��' );
		}
	}

	/**
	 * ɾ����Ŀ��Ա-ȷ��
	 */
	function c_makeSureDelete() {
		showmsg ( 'ȷ��ɾ����', "location.href='?model=rdproject_team_rdmember&action=deleteThisMember&id=" . $_GET ['id'] . "'", 'button' );
	}

	/**
	 * ɾ����Ŀ��Ա-ɾ��
	 */
	function c_deleteThisMember() {
		//update by chengl 2011-07-29 ֱ�Ӵ����ݿ�ɾ������
		if ($this->service->deletes ( $_GET ['id'] )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * �����Ա��ɫ
	 */
	function c_saveRolesToMember() {
		$rolesId = $_POST ['rolesId'];
		$memberId = $_POST ['memberId'];
		if ($this->service->saveRolesToMember_d ( $rolesId, $memberId )) {
			echo "1";
		} else {
			echo "0";
		}
	}

	/**
	 * ��Ŀ��Ա�鿴ҳ��
	 */
	function c_view(){
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		$service->searchArr ['isUsing'] = '1';
		$rows = $service->page_d ();

		$this->show->assign ( 'rdproject[id]', $_GET ['pjId'] );
		$this->show->assign ( 'list', $service->showviewlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}


	/*
	 * �ж��Ƿ���ִ�� -- ajax
	 * zengzx 2011��9��20��16:41:29
	 */
	function isCurUserPermAjax($projectId, $permCode) {
		//�ж��Ƿ�����Ŀ�����˻�����Ŀ�����ǲ�����Ȩ���ж�
		$projectDao = new model_rdproject_project_rdproject ();
		$project = $projectDao->get_d ( $projectId );
		$curUserId = $_SESSION ['USER_ID'];
		$pos = strpos ( $project ['assistantId'], $curUserId . "," );
		if ($pos != - 1 && $project ['managerId'] != $curUserId && $project ['createId'] != $curUserId) {
			$roleDao = new model_rdproject_role_rdrole ();
			if (! $roleDao->isCurUserPerm ( $projectId, $permCode )) {
				return false;
			}
		}else{
			return true;
		}
	}

}
?>
