<?php
/**
 * @author show
 * @Date 2013��12��9�� 19:17:51
 * @version 1.0
 * @description:����������ϸ���Ʋ� 
 */
class controller_engineering_resources_erenewdetail extends controller_base_action {
	function __construct() {
		$this->objName = "erenewdetail";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	}
	
	/**
	 * ��ת������������ϸ
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * �����豸�Ƿ��Ѵ��������¼
	 */
	function c_checkRepeat(){
		$resourceIdArr = explode(',',$_POST['resourceIdStr']);
		$data = 0;
		foreach ($resourceIdArr as $key => $val){
			$rs = $this->service->findAll(array('resourceId' => $val,'status' => 1),null,'id');
			if(!empty($rs)){
				$data = 1;
				break;
			}
		}
		echo $data;
	}
}