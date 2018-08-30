<?php
/**
 * �ʲ�������Ʋ���
 * @linzx
 */
class controller_asset_basic_directory extends controller_base_action {

	function __construct() {
		$this->objName = "directory";
		$this->objPath = "asset_basic";
		parent::__construct ();
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		//$this->permCheck (); //��ȫУ��
		$methodDao=new model_asset_basic_deprMethod();
		$names=$methodDao->list_d();
		$list=$this->service->showSelectList_d($names);
		$this->assign("listOption",$list);
       $this->view ('add' );
	}

	/*
	 * ��ת���༭ҳ��
	 * */
	function c_toEdit($isEditInfo = false) {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$methodDao=new model_asset_basic_deprMethod();
		$names=$methodDao->list_d();
		$list=$this->service->showSelectList_d($names);
		$this->assign("listOption",$list);
      	$this->view ('edit' );
	}


	/**
	 * ��ת���ʲ����ർ��ҳ��
	 * @create 2012��1��13�� 10:17:25
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * �ʲ����ർ��
	 * @create 2012��1��13�� 11:16:32
	 * @author zengzx
	 */
	function c_import(){
		$objKeyArr = array (
			0 => 'name',
			1 => 'code',
			2 => 'limitYears',
			3 => 'salvage',
			4 => 'unit',
			5 => 'depr',
			6 => 'isDepr'
		); //�ֶ�����
		$resultArr = $this->service->import_d ( $objKeyArr );
	}


	/**
	 * grid�б���������
	 */
	function c_getSelection(){
		$rows = $this->service->list_d();
		$datas = array();
		foreach( $rows as $key=>$val ){
			$datas[$key]['text']=$val['name'];
			$datas[$key]['value']=$val['id'];
		}
		echo util_jsonUtil::encode ( $datas );
	}

	function c_getRate(){
		$id = $_POST['id'];
		$obj = $this->service->get_d($id);
		echo util_jsonUtil::encode ( $obj );
	}

}
?>