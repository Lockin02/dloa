<?php
/**
 * @author show
 * @Date 2013年12月9日 19:17:33
 * @version 1.0
 * @description:转借设备明细控制层 
 */
class controller_engineering_resources_elentdetail extends controller_base_action {

	function __construct() {
		$this->objName = "elentdetail";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	 }
	/**
	 * 检查该设备是否已存在转借记录
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