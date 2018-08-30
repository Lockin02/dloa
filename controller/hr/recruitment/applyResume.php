<?php
/**
 * @author Administrator
 * @Date 2012��7��17�� ���ڶ� 10:43:19
 * @version 1.0
 * @description:��Ա�����������Ʋ�
 */
class controller_hr_recruitment_applyResume extends controller_base_action {

	function __construct() {
		$this->objName = "applyResume";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת����Ա����������б�
	 */
	function c_page() {
		$this->assign("id",$_GET['id']);
		$this->assign("stateC",$_GET['stateC']);
		$this->assign("ExaStatus",$_GET['ExaStatus']);
		$this->view('list');
	}

	/**
	 * ��ת��������Ա���������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	/**
	 * ��ת��������Ա���������ҳ��
	 */
	function c_toSelect() {
	 $this->assign("id",$_GET['id']);
	 $this->view ( 'select' );
	}
	/**
	 * ������ݵ�������
	 */
	function c_toBlack(){
		$applyresume = $this->service->get_d($_POST['id']);
		$resume = new model_hr_recruitment_resume();
		if($applyresume['state']==$this->service->statusDao->statusEtoK("black")){echo 3;return;}
		try{
			$getinfo = $resume->get_d($applyresume['resumeId']);
			$getinfo['resumeType'] = 3;
			$resume->edit_d($getinfo);
			$applyresume['state'] = $this->service->statusDao->statusEtoK ( 'black' );
			$this->service->edit_d($applyresume);
		}catch(Exception $e){
			echo 0;
		}
		echo 1;
	}
	/*
	 * ajax��ʽ������������Ӧ�ðѳɹ���־����Ϣ���أ�
	 */
	function c_ajaxadds() {
		$info = explode( ",",$_POST ['id'] );
		$applyid = $_POST['applyid'];
		$resume = new model_hr_recruitment_resume();
		try{
			for ($i=0; $i < count($info); $i++) {
				if($this->service->find(array("resumeId"=>$info[$i],'parentId'=>$applyid))){
					echo 2;return;
				}
				$temp = $resume->get_d($info[$i]);
				$temp['formCode'] = date ( "YmdHis" );
				$temp['parentId'] = $applyid;
				$temp['resumeId'] = $temp['id'];
				$temp['state'] = $this->service->statusDao->statusEtoK ( 'begin' );
				$temp['id'] = NULL;
				$this->service->add_d($temp);

			}
		}catch(Exception $e){
			echo 0;
		}
		echo 1;

	}
	/**
	 * ��ת���༭��Ա���������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}
	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_applyd ( $_POST ['resume'], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
	/**
	 * ��ת���鿴��Ա���������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		//var_dump($rows);
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