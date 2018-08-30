<?php
/**
 * @author Administrator
 * @Date 2013年9月14日 15:51:51
 * @version 1.0
 * @description:人员租借详细控制层
 */
class controller_outsourcing_outsourcing_person extends controller_base_action {

	function __construct() {
		$this->objName = "person";
		$this->objPath = "outsourcing_outsourcing";
		parent::__construct ();
	 }

	/**
	 * 跳转到人员租借详细列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增人员租借详细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑人员租借详细页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看人员租借详细页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

   /**
	 * 跳转到查看人员租借详细页面
	 */
   function c_selectPersonnel(){
        $applyId=$_POST['applyId'];//申请ID
        $riskCode= $_POST['riskCode'];//人员状态
        //数据获取
        $rows = $this->service->selectPersonnel_d(array('applyId'=>$applyId,'riskCode'=>$riskCode));
        if(is_array($rows['0'] )){
        	echo util_jsonUtil::encode ( $rows['0'] );
        }else{
        	echo 0;
        }
    }


 }
?>