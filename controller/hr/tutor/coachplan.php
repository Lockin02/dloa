<?php

/**
 * @author Administrator
 * @Date 2012-08-23 17:15:29
 * @version 1.0
 * @description:员工辅导计划表控制层
 */
class controller_hr_tutor_coachplan extends controller_base_action {

	function __construct() {
		$this->objName = "coachplan";
		$this->objPath = "hr_tutor";
		parent :: __construct();
	}

	/**
	 * 跳转到员工辅导计划表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增员工辅导计划表页面
	 */
	function c_toAdd() {
		$tutorId = $_GET['tutorId'];
		$dao = new model_hr_tutor_tutorrecords();
		$tutorInfo = $dao->get_d($tutorId);
		foreach ($tutorInfo as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('add' ,true);
	}

	/**
	 * 跳转到编辑员工辅导计划表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit' ,true);
	}

	/**
	 * 跳转到查看员工辅导计划表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		if(isset($_GET['id'])){
	        $obj = $this->service->get_d($_GET['id']);
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
	      	$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		    $this->assign ( 'actType', $actType );
			$this->view('view');
		}else{
			$tutorId = $_GET['tutorId'] ;
			//判断是否有辅导计划
			$sql = "select id  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
			$flagArr = $this->service->_db->getArray($sql);
			if(empty ($flagArr[0]['id'])){
				echo "<br><div style='text-align:center'><b>暂无辅导计划</b></div>";
			}else{
		      	$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
			    $this->assign ( 'actType', $actType );
		        $obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('view');
			}
		}
	}

   /**
    * read
    */
   function c_toRead() {
		$this->permCheck(); //安全校验
        $tutorId = $_GET['id'];
        $obj = $this->service->get_d($tutorId);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
    * 审批流查看单据调用的方法
    */
	function c_toSimpleRead() {
		$this->permCheck(); //安全校验
		$tutorId = $_GET['id'];
		$obj = $this->service->get_d($tutorId);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('simpleview');
	}

	/**
	 * 员工辅导计划---学员
	 */
    function c_toStudentView(){
		$tutorId = $_GET['tutorId'];
		//判断是否有辅导计划
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if(empty ($flagArr[0]['id'])){
      	 echo "<br><div style='text-align:center'><b>暂无辅导计划</b></div>";
        }else{
        	if($flagArr[0]['ExaStatus'] == '完成'){
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('studentScore');
			}else{
				$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	            $this->assign ( 'actType', $actType );
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('view');
			}
        }
    }

    //判断是否已制定辅导计划
    function c_isAddPlan(){

		$tutorId = $_POST['tutorId'];
		//判断是否有辅导计划
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			echo 0;
		} else {
			echo 1;
		}

    }

	/**
	 * 员工辅导计划
	 */
	function c_toCoachplan() {

		$tutorId = $_GET['tutorId'];
		//判断是否有辅导计划
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->service->_db->getArray($sql);
		$dao = new model_hr_tutor_tutorrecords();
		$tutorInfo = $dao->get_d($tutorId);
		if (empty ($flagArr[0]['id'])) {
			foreach ($tutorInfo as $key => $val) {
				$this->assign($key, $val);
			}
			$this->view('add');
		} else {
			if($flagArr[0]['ExaStatus'] == '部门审批'){
				$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	            $this->assign ( 'actType', $actType );
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('view');
			}else if($flagArr[0]['ExaStatus'] == '打回' || $flagArr[0]['ExaStatus'] == ''){
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('edit');
			}else if($flagArr[0]['ExaStatus'] == '完成'){
				$obj = $this->service->get_d($flagArr[0]['id']);
				foreach ($obj as $key => $val) {
					$this->assign($key, $val);
				}
				$this->view('tutorScore');
			}
		}
	}


	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
	    $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '提交成功！';
		if ($id) {
			$this->service->subPlanMail_d( $_POST [$this->objName]);

			if ("audit" == $actType) {//提交工作流
           		succ_show('controller/hr/tutor/ewf_index.php?actTo=ewfSelect&billId=' . $id.'&billUser='.$_POST [$this->objName]['studentSuperiorId']);
			} else {
				msg('保存成功');
			}
		}else{
				msg('保存失败');
		}
	}

	function c_subPlanEmail(){
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->service->subPlanMail_d( $obj);

		echo "function reload(self){if(self.parent.show_page){self.parent.tb_remove();self.parent.show_page();}else if(window.opener){self.close();self.opener.show_page();}else{reload(self.parent);}};reload(self);";
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
	    $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object, $isEditInfo );
		if ($id) {
			$this->service->subPlanMail_d( $object);

			if ("audit" == $actType) {//提交工作流
            	succ_show('controller/hr/tutor/ewf_index.php?actTo=ewfSelect&billId=' . $id.'&billUser='.$_POST [$this->objName]['studentSuperiorId']);
			} else {
				msg('保存成功');
			}
		}else{
				msg('保存失败');
		}
	}

    /**
     * 辅导计划 评分
     */
    function c_coachplanScore($isEditInfo = false){
		$this->checkSubmit(); //检查是否重复提交
  		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object, $isEditInfo );
		if ($id) {
			msg ( '评分完成！' );
//            succ_show('controller/hr/tutor/ewf_index.php?actTo=ewfSelect&billId=' . $id);
		}
    }

    /**
     * 辅导计划确认后反写 导师管理状态
     */
     function c_confirmExa(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );

			if($folowInfo['examines']=="ok"){  //审批通过则指定供应商
				$obj = $this->service->get_d ( $folowInfo['objId'] );
			  $tutorId = $obj['tutorId'];
              $sql ="update oa_hr_tutor_records set status=1 where id=".$tutorId."";
              $this->service->query($sql);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}
	/**
	 * 获取员工辅导计划列表数据 (添加两列:导师是否已经确认辅导计划达成情况，学员是否已经确认辅导计划达成情况)
	 */
	 function c_pageJsonForCoachplan(){
	 	$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $this->service->page_d();
		if(is_array($rows)){
			$rows = $this->service->pageForCoachplan_d($rows);
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	 }
}
?>