<?php
/**
 * @author Administrator
 * @Date 2011年11月22日 17:08:26
 * @version 1.0
 * @description:借试用物料金额设置控制层
 */
class controller_projectmanagent_borrow_money extends controller_base_action {

	function __construct() {
		$this->objName = "money";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }


/***********************************客户借试用 金额控制********开始*********************************************/
	/**
	 * 客户借试用金额控制
	 */
	 function c_customerMoney(){
         $this->display("customermoney");
	 }
	 /*
	  * 客户借试用金额控制新增页
	  */
	 function c_toAdd(){

        $dao = new model_system_region_region();
        $areaArr = $dao->list_d();
        $initArr = $this->service->initTable($areaArr);
        $this->assign("areaMoney" , $initArr[0] );
        $this->display('add');
	 }
     /*
      *客户借试用金额限制 初始化
      */
     function c_initMoney(){
         $object = $_POST [$this->objName];
		if ($this->service->initMoney_d ( $object )) {
			msgRF ( '初始化完成！' );
		}
     }

     /*
      * 修改金额跳转页
      */
     function c_editMoney(){
         $obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display("cus-editMoney");
     }

     /*
      * 修改金额
      */
     function c_editM(){
         $object = $_POST [$this->objName];
		if ($this->service->editM_d ( $object )) {
			msgGo ( '修改完成！');
		}
     }



/***********************************客户借试用 金额控制********结束*********************************************/
/***********************************员工借试用 金额控制********开始*********************************************/
	 /**
	  * 员工借试用金额控制 -- tab页
	  */
	  function c_proMoney(){
         $this->display("promoney");
	 }

	 //部门
	 function c_prodept(){
	 	$this->display("prodept");
	 }
	 //角色
	 function c_prorole(){
	 	$this->display("prorole");
	 }
	 //个人
	 function c_propersonal(){
	 	$this->display("propersonal");
	 }

	 /*
	  * 新增
	  */
	 function c_toProAdd(){
	 	$type = $_GET['type'];
	 	switch($type){
            case "dept": $this->assign("type" , "部门");break;
            case "role": $this->assign("type" , "角色");break;
            case "person": $this->assign("type" , "个人");break;
	 	}
       $this->display('proadd');
	 }
	 /*
	  * 新增保存方法
	  */
	 function c_proadd(){
	        $object = $_POST [$this->objName];
			if ($this->service->add_d ( $object )) {
				msg ( '添加完成！');
			}
	 }
	 /**
	  * 修改跳转页
	  */
	 function c_proeditMoney(){
	 	   $obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
           $this->display("proeditMoney");
	 }
	 /*
	  *修改金额
	  */
	 function c_proedit(){
	 	  $object = $_POST [$this->objName];
			if ($this->service->edit_d ( $object )) {
				msg ( '修改完成！');
			}
	 }

	 /**
	  * ajax 验证部门、角色、员工是否重复
	  */
     function c_ajaxCheckingDept(){
         $name = $_POST['name'];
        $searchArr = array ("deptName" => $name );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
     }
     function c_ajaxCheckingRole(){
         $name = $_POST['name'];
        $searchArr = array ("roleName" => $name );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
     }
     function c_ajaxCheckingUser(){
         $name = $_POST['name'];
        $searchArr = array ("userName" => $name );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
     }
/***********************************员工借试用 金额控制********结束*********************************************/
 }
?>