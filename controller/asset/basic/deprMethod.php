<?php
/**
 *
 * �۾ɷ�ʽ���Ʋ���
 * @author fengxw
 *
 */
class controller_asset_basic_deprMethod extends controller_base_action {

	function __construct() {
		$this->objName = "deprMethod";
		$this->objPath = "asset_basic";
		parent::__construct ();
	}

	/*
	 * ��ת���۾ɷ�ʽ
	 */
    function c_page() {
      $this->view( 'list' );
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
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * ��ת���۾ɷ�ʽ����ҳ��
	 * @create 2012��1��13�� 10:17:25
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * �۾ɷ�ʽ����
	 * @create 2012��1��13�� 11:16:32
	 * @author zengzx
	 */
	function c_import(){
		$objKeyArr = array (
			0 => 'code',
			1 => 'name',
			2 => 'describes',
			3 => 'expression',
			4 => 'remark'
		); //�ֶ�����
		$resultArr = $this->service->import_d ( $objKeyArr );
		$title = '�ʼķ�����Ϣ�������б�';
		$thead = array( '�ʼĵ���','������' );
//		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


}

?>