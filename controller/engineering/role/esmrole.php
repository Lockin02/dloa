<?php
/**
 * @author Show
 * @Date 2012��7��13�� ������ 10:48:12
 * @version 1.0
 * @description:��Ŀ��ɫ(oa_esm_project_role)���Ʋ�
 */
class controller_engineering_role_esmrole extends controller_base_action {

	function __construct() {
		$this->objName = "esmrole";
		$this->objPath = "engineering_role";
		parent :: __construct();
	}

	/*********************** �б��� *****************************/

	/*
	 * ��ת����Ŀ��ɫ(oa_esm_project_role)�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonOrg() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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

	/*
	 * ��Ŀ��ɫ�༭�б�
	 */
    function c_toEditList() {
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$project = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assign('parentId',$parentId);
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('list');
    }


    /*��Ŀ��ԱTabҳ��(�鿴)*/
    function c_proMemberTab(){
    	$this->assign('projectId',$_GET['projectId']);
    	$this->view('promembertab');
    }

    /*������Ŀ��ԱTabҳ��*/
    function c_proMemberTreeTab(){
    	$this->assign('projectId',$_GET['projectId']);
    	$this->view('promembertreetab');
    }


    /**
     * ��Ŀ��ɫ����
     */
    function c_toTreeList(){
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$project = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assignFunc($project);
		$this->assign('parentId',$parentId);
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('treelist');
    }

    /**
     * ��������
     */
    function c_treeJson(){
		$service = $this->service;
		$arrs = array();
		$projectId = isset($_REQUEST['projectId'])? $_REQUEST['projectId']:"";
		if(empty($projectId)){
			return false;
		}
		$service->searchArr['projectId'] = $projectId;
		$service->sort = 'c.lft';
		$service->asc = false;
		$arrs = $service->listBySqlId('treelist');

		if(!empty($arrs)){
			//��ȥ_parentId
			foreach($arrs as $key => $val){
				if($val['_parentId'] == -1){
					unset($arrs[$key]['_parentId']);
				}
			}
		}
		//������ֵ
		$rows['rows'] = $arrs;
		echo util_jsonUtil :: encode ($rows);
    }

    /**
     * ��Ŀ��ɫ����
     */
    function c_toTreeViewList(){
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$project = $this->service->getObjInfo_d($_GET['projectId']);
		$this->assignFunc($project);
		$this->assign('parentId',$parentId);
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('treeviewlist');
    }

	/************************* ��ɾ�Ĳ� **************************/

	/**
	 * ��ת��������Ŀ��ɫ(oa_esm_project_role)ҳ��
	 */
	function c_toAdd() {
		$this->assignFunc($_GET);
		$this->view('add');
	}


	/**
	 * �첽����
	 */
	function c_ajaxAdd(){
		$object = util_jsonUtil:: iconvUTF2GBArr($_POST);
		$rs = $this->service->add_d($object);
		if($rs){
			echo $rs;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * ��ת���༭��Ŀ��ɫ(oa_esm_project_role)ҳ��
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
	 * �޸Ķ���
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		$leaveMember = $this->service->getLeaveMember_d($object);//����޸�ǰ��Ŀ��Աid
		if ($this->service->edit_d ( $object )) {
			if($object['orgMemberId'] != $object['memberId']){
				if(!empty($leaveMember)){
					$leaveMemberStr = implode(',', $leaveMember);
					echo '<script>if(confirm("�༭�ɹ�,���Ƴ���'.count($leaveMember).'���Ѳ�����Ŀ�ĳ�Ա,�Ƿ���д������뿪���ڣ�")){
							location.href = "?model=engineering_member_esmentry&action=toLeaveList&ids='.$leaveMemberStr.'";
							}else{self.parent.tb_remove();self.parent.show_page();}</script>';
				}else{
					msg ( '�༭�ɹ���' );
				}
			}else{
				msg ( '�༭�ɹ���' );
			}
		}
	}

	/**
	 * ��дɾ������
	 */
	function c_ajaxdeletes() {
		$arr = $this->service->find(array('id' => $_POST ['id']),null,'memberId,projectId');
		$object['orgMemberId'] = $arr['memberId'];
		$object['memberId'] = '';
		$object['projectId'] = $arr['projectId'];
		$leaveMember = $this->service->getLeaveMember_d($object);//����޸�ǰ��Ŀ��Աid
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			if(!empty($leaveMember)){
				$leaveMemberStr = implode(',', $leaveMember);
				$result = array('0' => array('count' => count($leaveMember),'ids' => $leaveMemberStr));
				echo util_jsonUtil::encode($result);
			}else{
				echo true;
			}
		} catch ( Exception $e ) {
			echo false;
		}
	}

	/**
	 * ��ת���鿴��Ŀ��ɫ(oa_esm_project_role)ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/************************* �������� *****************************/
	/**
	 * ��֤�Ƿ��и��ڵ㣬û��������
	 */
	function c_checkParent(){
		if($this->service->checkParent_d()){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	//��ʼ����Ŀ��ɫ�Լ���Ա
	function c_initProjectRoleAneMember(){
		$rs = $this->service->initProjectRoleAneMember_d();
		if($rs){
			echo "completed";
		}else{
			echo "error";
		}
	}

	//��Ա�ܹ�����
	function c_toEportExcelIn(){
		$this->assignFunc($_GET);
		$this->view('importExcel');
	}

	//��Ա�ܹ�����
	function c_importExcel() {
		$service = $this->service;
		//��ȡ����INPUT
		$projectId = $_POST["projectId"];
		$projectCode = $_POST["projectCode"];
		$projectName = $_POST["projectName"];

		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		if ($fileType == "applicationnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.ms-excel") {

			$dao = new model_engineering_role_importTesttable();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			$objNameArr =  array(
					0 => 'roleName',//��ɫ����
					1 => 'memberName',//��Ա����
					2 => 'activityName',//��������
					3 => 'jobDescription',//��ע˵��

			);
			$objectArr = array ();
			foreach ( $excelData as $rNum => $row ) {
				foreach ( $objNameArr as $index => $fieldName ) {
					$objectArr [$rNum] [$fieldName] = $row [$index];
				}
			}

			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importExcel ( $objectArr, $projectId, $projectCode, $projectName);

			if (is_array ( $resultArr ))
				echo util_excelUtil::showResult ( $resultArr, "��Ϣ������", array ("�����ļ�����", "���" ) );

			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 * ��֤��Ŀ��ɫ�Ƿ�ɱ༭�̶�Ͷ�����
	 */
	function c_isEditFixedRate(){
		echo $this->service->isEditFixedRate_d($_POST['id']);
	}
	/**
	 * ��ȡ�������̶�Ͷ�����
	 */
	function c_getMaxFixedRate(){
		//����memberId����
		$memberIdArr = explode(',',$_POST['memberId']);
		$memberIdStrArr = array();
		foreach ($memberIdArr as $key => $val){
			array_push($memberIdStrArr, "'".$val."'");
		}
		$memberIdStr = implode(',', $memberIdStrArr);
		$maxFixedRate = $this->service->getMaxFixedRate_d($_POST['projectId'],$memberIdStr);
		//����ؼ�¼����������̶�Ͷ�����Ϊ100
		if(empty($maxFixedRate)){
			echo 100;
		}else{
			echo $maxFixedRate;
		}
	}
}