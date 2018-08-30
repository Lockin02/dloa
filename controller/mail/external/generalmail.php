<?php
/**
 * @filename	generalmail.php
 * @function	邮寄公用入口action
 * @author		ouyang
 * @version	1.0
 * @datetime	2011-1-24
 * @lastmodify	2011-1-24
 * @package	oae/controller/mail/external
 * @link		no
 */
class controller_mail_external_generalmail extends controller_base_action {

	/**
	 * 构造函数
	 *
	 */
	function __construct() {
		$this->objName = "generalmail";
		$this->objPath = "mail_external";
		parent::__construct ();
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		//设置数据字典
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**添加方法
	*author can
	*2011-4-19
	*/
	function c_add(){
		$id=$this->service->add_d($_POST[$this->objName]);
		if($id){
			msgGo("保存成功！");
		}

	}
}
?>