<?php
/**
 * @author Administrator
 * @Date 2012��7��11�� ������ 16:13:46
 * @version 1.0
 * @description:�ڲ��Ƽ����Ʋ�
 */
class controller_hr_recruitment_recommend extends controller_base_action {

	function __construct() {
		$this->objName = "recommend";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת���ڲ��Ƽ��б�
	 */
	function c_page() {
		$this->view('list');
	}

    /**
	 * ��ת���ڲ��Ƽ��б�
	 */
	function c_mypage() {
		$this->assign ( "id", $_GET['id'] );
		$this->view('mylist');
	}

	/**
	 * ��ת���ڲ��Ƽ��б�
	 */
	function c_toTabPage() {
		$this->assign ( "id", $_GET['id'] );
		$this->assign ( "stateC", $_GET['stateC'] );
		$this->view('tablist');
	}

	/**
	 * ��ת���ڲ��Ƽ��б�
	 */
	function c_myassistpage() {
		$this->view('myassistlist');
	}

	/**
	 * ��ת���ڲ��Ƽ��б�
	 */
	function c_mymainpage() {
		$this->view('mymainlist');
	}

	/**
	 * ��ת�������ڲ��Ƽ�ҳ��
	 */
	function c_toAdd() {
		$this->assign ( "recommendName", $_SESSION['USERNAME'] );
		$this->assign ( "recommendId", $_SESSION['USER_ID'] );
		$this->view ('add' ,true);
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			msg( '����ɹ�');
		}else{
			msg( '����ʧ��' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_handup($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		unset($object['state']);
		$object['state']=1;
		if(empty($object['id'])){
			if ($this->service->add_d ( $object, true )) {
				msg( '�ύ��˳ɹ�' );
			}
		} else{
			if ($this->service->edit_d ( $object, true )) {
				msg( '�ύ��˳ɹ�' );
			}
		}
	}

	/**
	 * �޸�״̬��Ϣ
	 */
	function c_change($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['state'] = $_GET['state'];
		if ($this->service->edit_d ( $obj, $isEditInfo )) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312' /><script >alert('�ύ�ɹ�!');self.history.back(-1);</script>";
		}
	}

	/**
	 * ���为����
	 */
	function c_toGive() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$datestr = date('Y-m-d');
		$this->assign("assignedManName",$_SESSION['USERNAME']);
		$this->assign("assignedManId",$_SESSION['USER_ID']);
		$this->assign("assignedDate",$datestr);
		$this->view ('give' ,true);
	}

	/**
	 * ��ת���޸ĸ�����ҳ��
	 */
	function c_toChangeHead() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );

		$datestr = date('Y-m-d');
		$this->assign("assignedManName" ,$_SESSION['USERNAME']);
		$this->assign("assignedManId" ,$_SESSION['USER_ID']);
		$this->assign("assignedDate" ,$datestr);
		$this->view ('changeHead' ,true);
	}

	/**
	 * �޸ĸ�����
	 */
	function c_changeHead() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$rs = $this->service->changeHead_d( $_POST[$this->objName] );
		if ($rs) {
			msg('�޸ĳɹ���');
		} else {
			msg('�޸�ʧ�ܣ�');
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '����ɹ���' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_back($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];

		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '��سɹ���' );
		}
	}

	/**
	 * ������ڲ��Ƽ�����
	 */
	function c_goback($isEditInfo = false){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];

		if ($this->service->back_d( $object)) {
			msg ( '�����ɹ���' );
		}
	}

	/**
	 * ��ת�������ڲ��Ƽ�ҳ��
	 */
	function c_toCheck() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ('check' ,true);
	}

	/**
	 * ��ת���༭�ڲ��Ƽ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//��ʾ������Ϣ
		$this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
		$this->view ('edit' ,true);
	}

	/**
	* ��ת���鿴�ڲ��Ƽ�ҳ��
	*/
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ( 'view' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		$this->service->searchArr['createId'] = $_SESSION['USER_ID'];

		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡЭ�����Է�ҳ����ת��Json
	 */
	function c_myHelpPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$this->service->searchArr['myjoinId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ�����Է�ҳ����ת��Json
	 */
	function c_myMainPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		$this->service->searchArr['recruitManId'] = $_SESSION['USER_ID'];

		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת��Ա���ڲ��Ƽ��Ľ���ǰ�ĶԻ���
	 */
	function c_addBefore() {
		$this->view ( 'addBefore' );
	}

	/**
	 * ��תexcel����ҳ��
	 */
	function c_toExcelOut() {
		$this->view ( 'excelout' );
	}

	/**
	 * excel����
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['formCode'])) //���ݱ��
			$this->service->searchArr['formCode'] = $formData['formCode'];

		if(!empty($formData['formDateSta'])) //��ʼ��������
			$this->service->searchArr['formDateSta'] = $formData['formDateSta'];
		if(!empty($formData['formDateEnd'])) //������������
			$this->service->searchArr['formDateEnd'] = $formData['formDateEnd'];

		if(!empty($formData['positionName'])) //�Ƽ�ְλ
			$this->service->searchArr['positionName'] = $formData['positionName'];

		if(!empty($formData['recommendName'])) //�Ƽ���
			$this->service->searchArr['recommendNameArr'] = $formData['recommendName'];

		if(!empty($formData['recruitManName'])) //������
			$this->service->searchArr['recruitManNameArr'] = $formData['recruitManName'];

		//����״̬
		$this->service->searchArr['stateArr'] = '1,2,3,4,5,6,8,9';
		$this->service->groupBy = 'c.id';
		$rows = $this->service->listBySqlId();
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$v['stateC'] = $this->service->statusDao->statusKtoC($v['state']);
			$rowData[$k]['formCode'] = $v['formCode'];
			$rowData[$k]['formDate'] = $v['formDate'];
			$rowData[$k]['isRecommendName'] = $v['isRecommendName'];
			$rowData[$k]['positionName'] = $v['positionName'];
			$rowData[$k]['recommendName'] = $v['recommendName'];
			$rowData[$k]['stateC'] = $v['stateC'];
			$rowData[$k]['hrJobName'] = $v['hrJobName'];
			$rowData[$k]['becomeDate'] = $v['becomeDate'];
			$rowData[$k]['realBecomeDate'] = $v['realBecomeDate'];
			$rowData[$k]['quitDate'] = $v['quitDate'];
			$rowData[$k]['recruitManName'] = $v['recruitManName'];
			$rowData[$k]['assistManName'] = $v['assistManName'];
			$rowData[$k]['isBonus'] = $v['isBonus'] == 1 ? '��' : '��';
			$rowData[$k]['bonus'] = $v['bonus'];
			$rowData[$k]['bonusProprotion'] = $v['bonusProprotion'];
			$rowData[$k]['recommendReason'] = $v['recommendReason'];
			$rowData[$k]['closeRemark'] = $v['closeRemark'];
		}

		$colArr  = array();
		$modelName = '����-�ڲ��Ƽ�';
		return model_hr_recruitment_importHrUtil::exportExcelUtil($colArr, $rowData, $modelName );
	}

	/**
	 * ��ת��ת���ʼ�ҳ��
	 */
	function c_toForwardMail() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ('forwardMail' ,true);
	}

	/**
	 * ת���ʼ�
	 */
	function c_forwardMail() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		if ($this->service->forwardMail_d($obj['id'] ,$_POST['mail'])) {
			msg ( '���ͳɹ���' );
		} else {
			msg ( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonSelect() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d('select_interview');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
}
?>