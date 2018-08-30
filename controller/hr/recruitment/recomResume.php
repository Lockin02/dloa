<?php
/**
 * @author Administrator
 * @Date 2012��7��17�� ���ڶ� 14:29:10
 * @version 1.0
 * @description:�ڲ��Ƽ���������Ʋ�
 */
class controller_hr_recruitment_recomResume extends controller_base_action {

	function __construct() {
		$this->objName = "recomResume";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת���ڲ��Ƽ��������б�
	 */
	function c_page() {
		$this->assign("id",$_GET['id']);
		$this->assign("stateC",$_GET['stateC']);  //״̬
		$this->view('list');
	}
	/*
	 * ѡ��������Ŀ
	 */
	function c_toSelect(){
		$this->assign("id",$_GET['id']);
		$this->view('select');
	}

	/**
	 * ������ݵ�������
	 */
	function c_toBlack(){
		$applyresume = $this->service->get_d($_POST['id']);
		$resume = new model_hr_recruitment_resume();
		if($applyresume['state']==$this->service->statusDao->statusEtoK('black')){echo 3;return;}
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
	 * �����������
	 */
	function c_ajaxadds() {
		$getit = $this->service->ajaxadd_d();
		echo $getit;
	}
	/*
	 * ������ѯ����
	 */
	function c_checkit() {
		$getit = $this->service->checkit_d();
		echo $getit;
	}

	/**
	 * ��ת�������ڲ��Ƽ�������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭�ڲ��Ƽ�������ҳ��
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
		$id = $this->service->add_recomd ( $_POST ['resume'], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
	/**
	 * ��ת���鿴�ڲ��Ƽ�������ҳ��
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