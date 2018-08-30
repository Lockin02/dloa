<?php
/**
 * @description: ��Ӧ����ʽ����ϵ�˿��Ʋ���
 * @date 2010-11-9 ����02:51:01
 */
class controller_supplierManage_formal_sfcontact extends controller_base_action{
  function __construct() {
	$this->objName = "sfcontact";
	$this->objPath = "supplierManage_formal";
	parent::__construct();
  }
  	/**
	 * @desription ��ת���鿴ҳ��
	 */
 	function c_toRead () {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}
/*
 * ��ʽ����ϵ���б����ݻ�ȡ
 */
	function c_pageJson () {
		$service = $this->service;
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET['parentId'] : null;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$this->searchVal( 'name');
		$rows = $this->service->conInSupp($parentId);
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
}
/*
 *��ת����ʽ����ϵ���б�
  */
	function c_tosfconlist () {
		$this->permCheck ($_GET['parentId'],'supplierManage_formal_flibrary');//��ȫУ��
		$this->show->assign('parentId',$_GET['parentId']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
/*
 * ��ת���༭������ϵ���޸��б�ҳ��
 */

	 function c_toConEdit () {
		$this->permCheck ($_GET['parentId'],'supplierManage_formal_flibrary');//��ȫУ��
	 	$this->show->assign('parentCode',$_GET['parentCode']);
//	 	echo $_GET['parentCode'];
		$this->show->assign('parentId', $_GET ['parentId']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-conE' );
	}
  	/**
	 * @desription ��ת�����ҳ��
	 */
 	function c_toAdd () {
		$sysCode = generatorSerial();
		$objCode = generatorSerial();

		$this->show->assign ( 'parentCode', $_GET['parentCode'] );
		$this->show->assign ( 'parentId', $_GET['parentId'] );
		$this->show->assign('objCode',$objCode);
		$this->show->assign('systemCode',$sysCode);
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
     * �޸Ĺ�Ӧ�������ϵ����Ϣ����
	 * @date 2010-9-20 ����02:06:22
	 */
	 	function c_addcontact() {
		$con = $_POST [$this->objName];
        if($_GET['id']){
        	$linkman['createId'] = $_SESSION['USER_ID'];
        	$linkman['createName'] = $_SESSION['USERNAME'];
        	$linkman ['defaultContact'] = $_SESSION ['USERNAME'];
        }
		$id = $this->service->add_d ( $con, true );

		if ($id) {
			msg( '������ϵ�˳ɹ���' );
		}

	}
	/**
	 *��ȡ��һ����Ӧ����ϵ��
	 *
	 */
	function c_getLinkmanInfo(){
		$suppId=isset($_POST['suppId'])?$_POST['suppId']:"";
		$rows=$this->service->conInSupp($suppId);
		if(is_array($rows)){
			echo util_jsonUtil::encode ( $rows['0'] );
		}else{
			echo "";
		}
	}
}
?>
