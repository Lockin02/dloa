<?php

/**
 * @author Show
 * @Date 2012��8��24�� ������ 11:43:39
 * @version 1.0
 * @description:��ְ�ʸ���ί��ֱ� - ������Ʋ�
 */
class controller_hr_certifyapply_score extends controller_base_action {

	function __construct() {
		$this->objName = "score";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}
	/******************* �б��� ********************/

	/**
	 * ��ת����ְ�ʸ���ί��ֱ� - �����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * �����б�
	 */
	function c_myList(){
		$this->view('listmy');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_REQUEST['assessUser'] = $_SESSION['USER_ID'];
		$_REQUEST['scoreUser'] = $_SESSION['USER_ID'];
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$sql = "select
				s.id ,s.userName ,s.userAccount , s.managerName,s.managerId ,s.createId ,s.createName ,
				s.createTime ,s.updateId ,s.updateName ,s.updateTime ,s.sysCompanyName ,s.sysCompanyId,s.status,
				c.id as scoreId,ifnull(c.id,0) as scoreStatus,c.score,c.assessDate,c.managerId as scoreManagerId
			from
				oa_hr_certifyapplyassess s
					left join
				(select c.id,c.score,c.assessDate,c.managerId,c.cassessId from oa_hr_certifyapplyassess_score c where c.managerId = '".$_SESSION['USER_ID']."' ) c
					on s.id = c.cassessId
			where
				1";
		$rows = $service->pageBySql($sql);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/******************* ��ɾ�Ĳ� *******************/
	/**
	 * ��ת��������ְ�ʸ���ί��ֱ� - ����ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��ְ�ʸ���ί��ֱ� - ����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴��ְ�ʸ���ί��ֱ� - ����ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * �½�����
	 */
	function c_toScore(){
		//��ȡ���۱���Ϣ
		$assessInfo = $this->service->getAssess_d($_GET['cassessId']);
		$this->assignFunc($assessInfo);

		$this->assign('thisUserName',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('thisDate',day_date);

		$this->view('score');
	}
}
?>