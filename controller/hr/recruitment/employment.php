<?php
/**
 * @author Administrator
 * @Date 2012-07-18 19:15:30
 * @version 1.0
 * @description:ְλ�������Ʋ�
 */
class controller_hr_recruitment_employment extends controller_base_action {

	function __construct() {
		$this->objName = "employment";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת��ְλ������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������ְλ�����ҳ��
	 */
	function c_toAdd() {
		$this->permCheck (); //��ȫУ��
		//��ȡ��Ƭ
		$this->assign("photo","images/no_pic.gif");
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		$this->assign("resumeId",$_GET['id']);
		//�ʼ���Ϣ��Ⱦ
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->showDatadicts ( array ('post' => 'YPZW' ) );

		$this->view ('add' ,true);
	}

	/**
	 * ������ת��ְλ�����ҳ��
	 */
	function c_toOuterAdd() {
		$this->permCheck (); //��ȫУ��
		//��ȡ��Ƭ
		$this->assign("photo","images/no_pic.gif");
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		$this->assign("resumeId",$_GET['id']);
		//�ʼ���Ϣ��Ⱦ
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view ('outeradd' ,true);
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			msg('����ְλ�����ѳɹ��ύ��');
		}

		//$this->listDataDict();
	}

	/**
	 * ���������������
	 */
	function c_outeradd($isAddInfo = true) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $_POST [$this->objName];

		$id = $this->service->add_d ( $obj, $isAddInfo );

		if ($id) {
			msg('�ύ�ɹ���');
		}
		//$this->listDataDict();
	}
	/**
	 * ��ת���༭ְλ�����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isMedicalHistory'] == '��') {
			$this->assign ( 'isYes', 'checked' );
		} else if ($obj ['isMedicalHistory'] == '��') {
			$this->assign ( 'isNo', 'checked' );
		}
		if ($obj ['isIT'] == '1') {
			$this->assign ( 'isITY', 'checked' );
		} else{
			$this->assign ( 'isITN', 'checked' );
		}
		//��ȡ��Ƭ
		$photo= $this->service->getFilePhoto_d ( $obj ['id'],'oa_hr_personnel');

		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		//����
		$file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_hr_recruitment_employment2');
		$this->assign("file2",$file2);
		$this->showDatadicts ( array ('healthStateCode' => 'HRJKZK' ), $obj ['healthStateCode'] );
		$this->showDatadicts ( array ('politicsStatusCode' => 'HRZZMM' ), $obj ['politicsStatusCode'] );
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ), $obj ['highEducation'] );
		$this->showDatadicts ( array ('englishSkill' => 'HRYYDJ' ), $obj ['englishSkill'] );
		$this->showDatadicts ( array ('post' => 'YPZW' ), $obj ['post'] );
		$this->view ('edit' ,true);
	}

	/**
	 * ��ת���鿴ְλ�����ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isIT'] == '1') {
			$this->assign ( 'isITName', '��' );
		} else if ($obj ['isIT'] == '0') {
			$this->assign ( 'isITName', '��' );
		}
		//��ȡ��Ƭ
		$photo= $this->service->getFilePhoto_d ( $obj ['id'],'oa_hr_personnel');
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false,'oa_hr_recruitment_employment2' ) );
		$this->assign("address",$obj['appointPro'].$obj['appointCity'].$obj['appointAddress']);
		$this->view ( 'view' );
	}

	/**
	 * ��ְ����� �ر�˵��
	 */
	function c_specialVersion(){
		$this->view("specialVersion");
	}

	/**
	 * ��ְ����ѡ��
	 */
	function c_selectEmployment(){
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}

	/**�ж��Ƿ����ύְλ����
	 *author can
	 *2010-12-29
	 */
	function c_isSumbitForm() {
		$identityCard=isset($_POST['identityCard'])?$_POST['identityCard']:exit;
		$id =$this->service->get_table_fields($this->service->tbl_name, "identityCard='".$identityCard."'", 'id');
		//���ɾ���ɹ����1���������0
		if($id>0){
			echo 0;
		}else{
			echo 1;
		}
	}

	/**
	 * ְλ���뵯������ѡ��
	 */
	function c_selectEmp(){
		$this->view('selectList');
	}
}
?>