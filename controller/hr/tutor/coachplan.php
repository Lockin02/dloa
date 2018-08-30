<?php

/**
 * @author Administrator
 * @Date 2012-08-23 17:15:29
 * @version 1.0
 * @description:Ա�������ƻ�����Ʋ�
 */
class controller_hr_tutor_coachplan extends controller_base_action {

	function __construct() {
		$this->objName = "coachplan";
		$this->objPath = "hr_tutor";
		parent :: __construct();
	}

	/**
	 * ��ת��Ա�������ƻ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������Ա�������ƻ���ҳ��
	 */
	function c_toAdd() {
		$tutorId = $_GET['tutorId'];
		$dao = new model_hr_tutor_tutorrecords();
		$tutorInfo = $dao->get_d($tutorId);
		foreach ($tutorInfo as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('add' ,true);
	}

	/**
	 * ��ת���༭Ա�������ƻ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit' ,true);
	}

	/**
	 * ��ת���鿴Ա�������ƻ���ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		if(isset($_GET['id'])){
	        $obj = $this->service->get_d($_GET['id']);
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
	      	$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		    $this->assign ( 'actType', $actType );
			$this->view('view');
		}else{
			$tutorId = $_GET['tutorId'] ;
			//�ж��Ƿ��и����ƻ�
			$sql = "select id  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
			$flagArr = $this->service->_db->getArray($sql);
			if(empty ($flagArr[0]['id'])){
				echo "<br><div style='text-align:center'><b>���޸����ƻ�</b></div>";
			}else{
		      	$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
			    $this->assign ( 'actType', $actType );
		        $obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('view');
			}
		}
	}

   /**
    * read
    */
   function c_toRead() {
		$this->permCheck(); //��ȫУ��
        $tutorId = $_GET['id'];
        $obj = $this->service->get_d($tutorId);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
    * �������鿴���ݵ��õķ���
    */
	function c_toSimpleRead() {
		$this->permCheck(); //��ȫУ��
		$tutorId = $_GET['id'];
		$obj = $this->service->get_d($tutorId);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('simpleview');
	}

	/**
	 * Ա�������ƻ�---ѧԱ
	 */
    function c_toStudentView(){
		$tutorId = $_GET['tutorId'];
		//�ж��Ƿ��и����ƻ�
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if(empty ($flagArr[0]['id'])){
      	 echo "<br><div style='text-align:center'><b>���޸����ƻ�</b></div>";
        }else{
        	if($flagArr[0]['ExaStatus'] == '���'){
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('studentScore');
			}else{
				$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	            $this->assign ( 'actType', $actType );
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('view');
			}
        }
    }

    //�ж��Ƿ����ƶ������ƻ�
    function c_isAddPlan(){

		$tutorId = $_POST['tutorId'];
		//�ж��Ƿ��и����ƻ�
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			echo 0;
		} else {
			echo 1;
		}

    }

	/**
	 * Ա�������ƻ�
	 */
	function c_toCoachplan() {

		$tutorId = $_GET['tutorId'];
		//�ж��Ƿ��и����ƻ�
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		$dao = new model_hr_tutor_tutorrecords();
		$tutorInfo = $dao->get_d($tutorId);
		if (empty ($flagArr[0]['id'])) {
			foreach ($tutorInfo as $key => $val) {
				$this->assign($key, $val);
			}
			$this->view('add');
		} else {
			if($flagArr[0]['ExaStatus'] == '��������'){
				$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	            $this->assign ( 'actType', $actType );
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('view');
			}else if($flagArr[0]['ExaStatus'] == '���' || $flagArr[0]['ExaStatus'] == ''){
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('edit');
			}else if($flagArr[0]['ExaStatus'] == '���'){
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('tutorScore');
			}
		}
	}


	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
	    $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '�ύ�ɹ���';
		if ($id) {
			$this->service->subPlanMail_d( $_POST [$this->objName]);

			if ("audit" == $actType) {//�ύ������
           		succ_show('controller/hr/tutor/ewf_index.php?actTo=ewfSelect&billId=' . $id.'&billUser='.$_POST [$this->objName]['studentSuperiorId']);
			} else {
				msg('����ɹ�');
			}
		}else{
				msg('����ʧ��');
		}
	}

	function c_subPlanEmail(){
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->service->subPlanMail_d( $obj);

		echo "function reload(self){if(self.parent.show_page){self.parent.tb_remove();self.parent.show_page();}else if(window.opener){self.close();self.opener.show_page();}else{reload(self.parent);}};reload(self);";
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
	    $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object, $isEditInfo );
		if ($id) {
			$this->service->subPlanMail_d( $object);

			if ("audit" == $actType) {//�ύ������
            	succ_show('controller/hr/tutor/ewf_index.php?actTo=ewfSelect&billId=' . $id.'&billUser='.$_POST [$this->objName]['studentSuperiorId']);
			} else {
				msg('����ɹ�');
			}
		}else{
				msg('����ʧ��');
		}
	}

    /**
     * �����ƻ� ����
     */
    function c_coachplanScore($isEditInfo = false){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
  		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object, $isEditInfo );
		if ($id) {
			msg ( '������ɣ�' );
//            succ_show('controller/hr/tutor/ewf_index.php?actTo=ewfSelect&billId=' . $id);
		}
    }

    /**
     * �����ƻ�ȷ�Ϻ�д ��ʦ����״̬
     */
     function c_confirmExa(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );

			if($folowInfo['examines']=="ok"){  //����ͨ����ָ����Ӧ��
				$obj = $this->service->get_d ( $folowInfo['objId'] );
			  $tutorId = $obj['tutorId'];
              $sql ="update oa_hr_tutor_records set status=1 where id=".$tutorId."";
              $this->service->query($sql);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}
	/**
	 * ��ȡԱ�������ƻ��б����� (�������:��ʦ�Ƿ��Ѿ�ȷ�ϸ����ƻ���������ѧԱ�Ƿ��Ѿ�ȷ�ϸ����ƻ�������)
	 */
	 function c_pageJsonForCoachplan(){
	 	$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $this->service->page_d();
		if(is_array($rows)){
			$rows = $this->service->pageForCoachplan_d($rows);
		}
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
}
?>