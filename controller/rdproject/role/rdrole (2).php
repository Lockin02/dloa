<?php
/**
 * @description: �Ŷӽ�ɫaction
 * @author chengl
 * @version V1.0
 */
class controller_rdproject_role_rdrole extends controller_base_action {
	function __construct() {
		$this->objName = "rdrole";
		$this->objPath = "rdproject_role";
		parent::__construct ();
	}

	/*
	 * @desription ������Ŀ��ת����ɫ�б�ҳ
	 */
	function c_toTemplateRolePage() {
		$this->showDatadicts ( array ('itemType' => 'YFXMGL' ) );
		$this->show->display ( 'rdproject_role_rdrole-listtemplate' );
	}

	/*
	 * @desription ������Ŀ������ת����ɫ�б�ҳ
	 */
	function c_toProjectRolePage() {
		$this->show->assign ( "projectId", $_GET ['pjId'] );
		$this->show->display ( $this->objPath .'_'.$this->objName .'-list' );
	}

	/**
	 * �ҵ���Ŀ-����Ŀ-��Ŀ��ɫ
	 */
	function c_rolePageInOption() {
		$this->show->assign ( "projectId", $_GET ['pjId'] );
		$this->show->display ( $this->objPath .'_'.$this->objName .'-listinoption' );
	}

	/**
	 * @desription ��ȡĳҳ��ɫ���������ɫ��Ϣ
	 */
	function c_pageAll() {
		$service = $this->service;
		if (! isset ( $_POST ['projectType'] )) {
			//��������treeNode����������ֶ��Զ���������
			$service->treeCondFields = array ('isTemplate', 'projectId' );
		}
		$searchArr = $service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		if (! isset ( $_POST ['projectId'] )) { //���û�д���Ŀid����Ϊģ��ҳ��
			$searchArr ['isTemplate'] = 1;
		}
		//print_r($searchArr);
		$rows = $service->pageAll_d ( $searchArr );

		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription ������Ŀ����/��Ŀid�����Ŀ��ɫ
	 */
	function c_toAdd() {
		$projectType = $_GET ['projectType'];
		$projectId = $_GET ['projectId'];
		if (! empty ( $projectId )) {
			//Ȩ���ж�
			$this->isCurUserPerm ( $projectId, 'project_teamMember_admin' );
		}

		$this->showDatadicts(array('projectType' => 'YFXMGL'));
//		$this->show->assign ( "projectType1", $projectType );
		$this->show->assign ( "projectId", $projectId );

		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * desription ������ɫ
	 */
	function c_add() {
		$role = $_POST ['rdrole'];
		if (! empty ( $role ['projectId'] )) {

			$role ['isTemplate'] = 0;
			$this->service->treeCondFields = array ('projectId' ); //ֻ�������Ŀid�������Ĺ���
		} else {
			unset ( $role ['projectId'] );
			$role ['isTemplate'] = 1;
		}
		$id = $this->service->add_d ( $role, true );
		if ($id) {
			msg ( '��ӳɹ���' );
		}
		//$this->listDataDict();
	}

	/**
	 * @desription ������Ŀ���ͼ����׻�ȡ��ɫ�б�(����Ext����������)
	 */
	function c_ajaxRoleByParent() {
		if (isset ( $_GET ['projectType'] )) {
			$this->service->searchArr = array ("projectType" => $_GET ['projectType'], "isTemplate" => 1, "parentId" => $_GET ['parentId'] );
		} else if (isset ( $_GET ['projectId'] )) {
			$this->service->searchArr = array ("projectId" => $_GET ['projectId'], "isTemplate" => 0, "parentId" => $_GET ['parentId'] );
		}
		$roles = $this->service->list_d ();
		//���Ƿ�Ҷ��ֵ0ת��false��1ת��true
		function toBoolean($row) {
			$row ['leaf'] = ($row ['leaf'] == 0 ? false : true);
			//$row ['checked']=true;
			return $row;
		}
		echo util_jsonUtil::encode ( array_map ( "toBoolean", $roles ) );
		//echo util_jsonUtil::encode ( $roles );
	}

	/**
	 * @desription ��ת������ĳ����ɫȨ��
	 */
	function c_authorize() {
		$roleId = $_GET ['id'];
		//��ȡ��ɫ������Ȩ����Ϣ
		$perms = $this->service->getAuthorizeByRoleId_d ( $roleId );
		$permstr = "";
		if (is_array ( $perms )) {
			foreach ( $perms as $value ) {
				$permstr .= "," . $value ['permCode'];
			}
			if (! empty ( $permstr )) {
				$permstr = substr ( $permstr, 1 );
			}
		}
		//include (CONFIG_SQL . "rdproject/role/permContants.php");
		//print_r ( $perm_arr );
		$this->show->assign ( "permstr", $permstr );
		$this->show->assign ( "roleId", $roleId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-authorize' );
	}

	/**
	 * @desription �����ɫȨ�޹���
	 */
	function c_saveAuthorize() {
		$perms = $_POST ['perms']; //Ȩ������
		$roleId = $_POST ['roleId']; //��ɫid
		if ($this->service->saveAuthorize_d ( $roleId, $perms )) {
			echo 1;
		} else
			echo 0;
	}

	/**
	 * ajax����ɾ������
	 */
	function c_ajaxdeletes() {
		//��ȡɾ���ļ�¼�����������Ŀ������Ҫ�������ù���������
		$role = $this->service->get_d ( $_POST ['id'] );
		if (! empty ( $role ['projectId'] )) {
			$this->service->treeCondFields = array ('isTemplate', 'projectId' );
		}
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

}
?>
