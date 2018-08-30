<?php
/**
 * @author Administrator
 * @Date 2012年7月11日 星期三 16:13:46
 * @version 1.0
 * @description:内部推荐控制层
 */
class controller_hr_recruitment_recommend extends controller_base_action {

	function __construct() {
		$this->objName = "recommend";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到内部推荐列表
	 */
	function c_page() {
		$this->view('list');
	}

    /**
	 * 跳转到内部推荐列表
	 */
	function c_mypage() {
		$this->assign ( "id", $_GET['id'] );
		$this->view('mylist');
	}

	/**
	 * 跳转到内部推荐列表
	 */
	function c_toTabPage() {
		$this->assign ( "id", $_GET['id'] );
		$this->assign ( "stateC", $_GET['stateC'] );
		$this->view('tablist');
	}

	/**
	 * 跳转到内部推荐列表
	 */
	function c_myassistpage() {
		$this->view('myassistlist');
	}

	/**
	 * 跳转到内部推荐列表
	 */
	function c_mymainpage() {
		$this->view('mymainlist');
	}

	/**
	 * 跳转到新增内部推荐页面
	 */
	function c_toAdd() {
		$this->assign ( "recommendName", $_SESSION['USERNAME'] );
		$this->assign ( "recommendId", $_SESSION['USER_ID'] );
		$this->view ('add' ,true);
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			msg( '保存成功');
		}else{
			msg( '保存失败' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_handup($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];
		unset($object['state']);
		$object['state']=1;
		if(empty($object['id'])){
			if ($this->service->add_d ( $object, true )) {
				msg( '提交审核成功' );
			}
		} else{
			if ($this->service->edit_d ( $object, true )) {
				msg( '提交审核成功' );
			}
		}
	}

	/**
	 * 修改状态信息
	 */
	function c_change($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['state'] = $_GET['state'];
		if ($this->service->edit_d ( $obj, $isEditInfo )) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312' /><script >alert('提交成功!');self.history.back(-1);</script>";
		}
	}

	/**
	 * 分配负责人
	 */
	function c_toGive() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$datestr = date('Y-m-d');
		$this->assign("assignedManName",$_SESSION['USERNAME']);
		$this->assign("assignedManId",$_SESSION['USER_ID']);
		$this->assign("assignedDate",$datestr);
		$this->view ('give' ,true);
	}

	/**
	 * 跳转到修改负责人页面
	 */
	function c_toChangeHead() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );

		$datestr = date('Y-m-d');
		$this->assign("assignedManName" ,$_SESSION['USERNAME']);
		$this->assign("assignedManId" ,$_SESSION['USER_ID']);
		$this->assign("assignedDate" ,$datestr);
		$this->view ('changeHead' ,true);
	}

	/**
	 * 修改负责人
	 */
	function c_changeHead() {
		$this->checkSubmit(); //检查是否重复提交
		$rs = $this->service->changeHead_d( $_POST[$this->objName] );
		if ($rs) {
			msg('修改成功！');
		} else {
			msg('修改失败！');
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '保存成功！' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_back($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];

		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '打回成功！' );
		}
	}

	/**
	 * 负责的内部推荐反馈
	 */
	function c_goback($isEditInfo = false){
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];

		if ($this->service->back_d( $object)) {
			msg ( '反馈成功！' );
		}
	}

	/**
	 * 跳转到新增内部推荐页面
	 */
	function c_toCheck() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ('check' ,true);
	}

	/**
	 * 跳转到编辑内部推荐页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//显示附件信息
		$this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
		$this->view ('edit' ,true);
	}

	/**
	* 跳转到查看内部推荐页面
	*/
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ( 'view' );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		$this->service->searchArr['createId'] = $_SESSION['USER_ID'];

		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取协助面试分页数据转成Json
	 */
	function c_myHelpPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$this->service->searchArr['myjoinId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取主面试分页数据转成Json
	 */
	function c_myMainPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		$this->service->searchArr['recruitManId'] = $_SESSION['USER_ID'];

		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到员工内部推荐的界面前的对话框
	 */
	function c_addBefore() {
		$this->view ( 'addBefore' );
	}

	/**
	 * 跳转excel导出页面
	 */
	function c_toExcelOut() {
		$this->view ( 'excelout' );
	}

	/**
	 * excel导出
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['formCode'])) //单据编号
			$this->service->searchArr['formCode'] = $formData['formCode'];

		if(!empty($formData['formDateSta'])) //开始单据日期
			$this->service->searchArr['formDateSta'] = $formData['formDateSta'];
		if(!empty($formData['formDateEnd'])) //结束单据日期
			$this->service->searchArr['formDateEnd'] = $formData['formDateEnd'];

		if(!empty($formData['positionName'])) //推荐职位
			$this->service->searchArr['positionName'] = $formData['positionName'];

		if(!empty($formData['recommendName'])) //推荐人
			$this->service->searchArr['recommendNameArr'] = $formData['recommendName'];

		if(!empty($formData['recruitManName'])) //负责人
			$this->service->searchArr['recruitManNameArr'] = $formData['recruitManName'];

		//单据状态
		$this->service->searchArr['stateArr'] = '1,2,3,4,5,6,8,9';
		$this->service->groupBy = 'c.id';
		$rows = $this->service->listBySqlId();
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$v['stateC'] = $this->service->statusDao->statusKtoC($v['state']);
			$rowData[$k]['formCode'] = $v['formCode'];
			$rowData[$k]['formDate'] = $v['formDate'];
			$rowData[$k]['isRecommendName'] = $v['isRecommendName'];
			$rowData[$k]['positionName'] = $v['positionName'];
			$rowData[$k]['recommendName'] = $v['recommendName'];
			$rowData[$k]['stateC'] = $v['stateC'];
			$rowData[$k]['hrJobName'] = $v['hrJobName'];
			$rowData[$k]['becomeDate'] = $v['becomeDate'];
			$rowData[$k]['realBecomeDate'] = $v['realBecomeDate'];
			$rowData[$k]['quitDate'] = $v['quitDate'];
			$rowData[$k]['recruitManName'] = $v['recruitManName'];
			$rowData[$k]['assistManName'] = $v['assistManName'];
			$rowData[$k]['isBonus'] = $v['isBonus'] == 1 ? '是' : '否';
			$rowData[$k]['bonus'] = $v['bonus'];
			$rowData[$k]['bonusProprotion'] = $v['bonusProprotion'];
			$rowData[$k]['recommendReason'] = $v['recommendReason'];
			$rowData[$k]['closeRemark'] = $v['closeRemark'];
		}

		$colArr  = array();
		$modelName = '人资-内部推荐';
		return model_hr_recruitment_importHrUtil::exportExcelUtil($colArr, $rowData, $modelName );
	}

	/**
	 * 跳转到转发邮件页面
	 */
	function c_toForwardMail() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ('forwardMail' ,true);
	}

	/**
	 * 转发邮件
	 */
	function c_forwardMail() {
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST[$this->objName];
		if ($this->service->forwardMail_d($obj['id'] ,$_POST['mail'])) {
			msg ( '发送成功！' );
		} else {
			msg ( '发送失败！' );
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonSelect() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d('select_interview');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
}
?>