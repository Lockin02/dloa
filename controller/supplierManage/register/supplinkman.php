<?php
/**
 * @description: ��Ӧ����ϵ�˿��Ʋ���
 * @date 2010-11-9 ����02:51:01
 */
class controller_supplierManage_register_supplinkman extends controller_base_action{
  function __construct() {
	$this->objName = "supplinkman";
	$this->objPath = "supplierManage_register";
	parent::__construct();
  }
 /**
 * @desription ��ת����ϵ���б�ҳ��
 * @param tags
 * @date 2010-11-8 ����02:18:04
 */
	function c_tolinkmanlist () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}


	/**
	 * @desription ��ת���޸�ҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_toEdit () {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}
	/**
     * ע�ṩӦ����Ϣ����
	 * @date 2010-9-20 ����02:06:22
	 */
	function c_addlinkman() {
		$linkman = $_POST [$this->objName];

		//echo("<pre>");
		//print_r($linkman);
		$id = $this->service->add_d ( $linkman, true );

		if ($id) {
			msg( '������ϵ�˳ɹ���' );
			}

		}
}
?>
