<?php
/**
 * @author Administrator
 * @Date 2012年10月7日 星期日 15:16:42
 * @version 1.0
 * @description:导师考核方案控制层
 */
class controller_hr_tutor_tutorScheme extends controller_base_action {

	function __construct() {
		$this->objName = "tutorScheme";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * 跳转到导师考核方案列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增导师考核方案页面
	 */
	function c_toAdd() {
		$this->permCheck (); //安全校验
		$obj = $this->service->find(null,'id desc',null);
		if(is_array($obj)){
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
		}else{
			$this->assign('schemeName','');
			$this->assign('id','');
			$this->assign('tutProportion','');
			$this->assign('deptProportion','');
			$this->assign('hrProportion','');
			$this->assign('supProportion','');
			$this->assign('remark','');
		}
        $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑导师考核方案页面
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
	 * 跳转到查看导师考核方案页面
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
	 * 重写新增方法
	 */
	function c_add() {
		try{
			$id = $this->service->add_d ( $_POST [$this->objName]);
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
			if ($id) {
				msg ( $msg );
			}
		}catch (Exception $e){
			msg($e->getMessage());
		}
	}
 }
?>