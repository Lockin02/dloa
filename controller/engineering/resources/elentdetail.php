<?php
/**
 * @author show
 * @Date 2013��12��9�� 19:17:33
 * @version 1.0
 * @description:ת���豸��ϸ���Ʋ� 
 */
class controller_engineering_resources_elentdetail extends controller_base_action {

	function __construct() {
		$this->objName = "elentdetail";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	 }
	/**
	 * �����豸�Ƿ��Ѵ���ת���¼
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