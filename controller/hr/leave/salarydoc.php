<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 16:33:08
 * @version 1.0
 * @description:工资交接单控制层
 */
class controller_hr_leave_salarydoc extends controller_base_action {

	function __construct() {
		$this->objName = "salarydoc";
		$this->objPath = "hr_leave";
		parent::__construct ();
	}

	/**
	 * 跳转到工资交接单列表
	 */
	function c_page() {
		$this->view('list');
	}
	/**
	 *
	 * 离职交接清单
	 */
	function c_toCheck(){
		$leaveId = $_GET['leaveId'];
		//判断是否存在面谈记录
		$sql = "select id  from oa_hr_leave_salarydoc where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$leaveDao = new model_hr_leave_leave();
		if(empty($flagArr[0]['id'])){
			//获取编制
			$obj = $leaveDao->get_d ( $_GET['leaveId'] );
			$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
			$obj['companyName'] = $info['companyName'];
			$obj['companyId'] = $info['companyId'];
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->showDatadicts(array ( 'quitTypeCode' => 'HRLZLX' ), $obj['quitTypeCode']);
			$this->assign("leaveId",$_GET['leaveId']);

			$this->assign('fromworkList',"<tr id='appendHtml'><td>序号<td colspan='2'>工资内容</td><td colspan='3'>备注</td></tr>");
			$this->view ('add');
		}else{
			$obj = $this->service->get_d ( $flagArr[0]['id'] );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			//处理渲染离职交接清单详细
			$Dao = new model_hr_leave_salarydocitem();
			$Dao->asc=false;
			$Dao->searchArr['mainId'] = $flagArr[0]['id'];
			$itemArr = $Dao->list_d ("select_default");
			$this->assign('itemList',$this->service->showItemAtEdit($itemArr));
			$this->view ('edit');
		}
	}

	/**
	 * 跳转到新增工资交接单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑工资交接单页面
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
	 * 跳转到查看工资交接单页面
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
	 * 新增保存
	 * @see controller_base_action::c_add()
	 */
	function c_add(){
		$obj=$_POST[$this->objName];
		if($_GET['actType']=="audit"){
			$obj['ExaStatus']='YSH';
		}
		$id = $this->service->add_d ( $obj,true );
		$msgInfo = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msgInfo );
		}
	}
	/**
	 * 修改保存
	 * @see controller_base_action::c_add()
	 */
	function c_edit(){
		$obj=$_POST[$this->objName];
		if($_GET['actType']=="audit"){
			$obj['ExaStatus']='YSH';
		}
		$id = $this->service->edit_d ( $obj,true );
		$msgInfo = $_POST ["msg"] ? $_POST ["msg"] : '修改成功！';
		if ($id) {
			msg ( $msgInfo );
		}
	}
}
?>