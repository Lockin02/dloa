<?php
/**
 * @filename	generalmail.php
 * @function	�ʼĹ������action
 * @author		ouyang
 * @version	1.0
 * @datetime	2011-1-24
 * @lastmodify	2011-1-24
 * @package	oae/controller/mail/external
 * @link		no
 */
class controller_mail_external_generalmail extends controller_base_action {

	/**
	 * ���캯��
	 *
	 */
	function __construct() {
		$this->objName = "generalmail";
		$this->objPath = "mail_external";
		parent::__construct ();
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		//���������ֵ�
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**��ӷ���
	*author can
	*2011-4-19
	*/
	function c_add(){
		$id=$this->service->add_d($_POST[$this->objName]);
		if($id){
			msgGo("����ɹ���");
		}

	}
}
?>