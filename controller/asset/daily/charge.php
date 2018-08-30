<?php
/**
 * 资产领用控制层类
 *  @author linzx
 */
class controller_asset_daily_charge extends controller_base_action {

	function __construct() {
		$this->objName = "charge";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}

	/**
	 * 跳转列表页面
	 * 不写就调用action的c_list();
	 */
	function c_list() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到用信息列表 --- 关联需求
	 */
	function c_requirelist() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('requireId',$_GET['requireId']);
		$this->view ( 'require-list' );
	}

	/**
	 * 跳转列表页面
	 * 不写就调用action的c_list();
	 */
	function c_myList() {
		$userId = $_SESSION['USER_ID'];
		$this->assign('userId',$userId);
		$this->view ( 'mylist' );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['userId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		//附件
		$obj ['file'] = $this->service->getFilesByObjId ( $obj ['id'], false );
		foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
            //提交审批后查看单据时隐藏关闭按钮
			if(isset($_GET['viewBtn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$requireId = isset ($_GET['requireId']) ? $_GET['requireId'] : null;
		$requireCode = isset ($_GET['requireCode']) ? $_GET['requireCode'] : null;
		$codeDao = new model_common_codeRule ();
		$requireDao = new model_asset_require_requirement();
		$requireObj = $requireDao->get_d($requireId);
		$applyCompanyCode =  $requireObj['userCompanyCode'];
		$applyCompanyName =  $requireObj['userCompanyName'];
		$chargeManId =  $requireObj['userId'];
		$chargeMan =  $requireObj['userName'];
		$chargeDeptId =  $requireObj['userDeptId'];
		$chargeDept =  $requireObj['userDeptName'];
		$reposeManId =  $requireObj['applyId'];
		$reposeDept =  $requireObj['applyDeptName'];
		$object ['billNo']= $codeDao->assetRequireCode ( "oa_asset_charge", "LY" ,day_date,$applyCompanyCode,'固定资产领用单');
		$this->assign ( 'applyCompanyCode', $applyCompanyCode );
		$this->assign ( 'applyCompanyName', $applyCompanyName );
		$this->assign ( 'chargeDate', day_date );
		$this->assign( 'returnDate',$requireObj['returnDate'] );
		$this->assign ( 'deptName', $chargeDept );
		$this->assign ( 'deptId', $chargeDeptId );
		$this->assign ( 'chargeManId', $chargeManId );
		$this->assign ( 'chargeMan', $chargeMan );
		$this->assign ( 'reposeManId', $_SESSION['USER_ID'] );
		$this->assign ( 'reposeMan', $_SESSION['USERNAME'] );
		$this->assign ( 'reposeDeptId', $_SESSION['DEPT_ID'] );
		$this->assign ( 'reposeDept', $_SESSION['DEPT_NAME'] );
		$this->assign ( 'borrowDate', date("Y-m-d") );
		$this->assign ( 'returnDate', $requireObj['returnDate'] );
		$this->assign ( 'requireId', $requireId );
		$this->assign ( 'requireCode', $requireCode );
		$this->assign ( 'remark', '收货地址：'.$requireObj['address'] );
		$this->view ( 'add',true );
	}

	  /**
	   * ajax签收借用申请
	   */
	   function c_toSign(){
		  	echo $this->service->sign_d($_GET['id']);
	   }

 	 /**
	  * ajax删除借用主表及借用清单从表信息
	  */
	  function c_deletes(){
		$message = "";
		try {
            $chargeObj = $this->service->get_d ( $_GET ['id'] );
			$chargeitemDao = new model_asset_daily_chargeitem();
	  		$condition = array(
	  			'allocateID'=>$chargeObj['id']
	  		);
	  		$chargeitemDao->delete($condition);
			$this->service->deletes_d ( $_GET ['id'] );

			$message = '<div style="color:red" align="center">删除成功!</div>';

		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
		}
		if (isset ( $_GET ['url'] )) {
			$event = "document.location='" . iconv ( 'utf-8', 'gb2312', $_GET ['url'] ) . "'";
			showmsg ( $message, $event, 'button' );
		} else if (isset ( $_SERVER [HTTP_REFERER] )) {
			$event = "document.location='" . $_SERVER [HTTP_REFERER] . "'";
			showmsg ( $message, $event, 'button' );
		} else {
			$this->c_page ();
		}

		msg('删除成功！');


	  }

	/**
	 * 新增对象操作
	 * @linzx
	 */
	function c_add() {
		$this->checkSubmit(); //检验是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName], true );
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_charge.php?actTo=ewfSelect&billId='.$id);
			} else {
				msgRf('新增成功');
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('审批新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				msgRf('新增失败');
			}

		}
}
	/**
	 * 修改对象操作
	 * @chenzb
	 */
      function c_edit() {
      	 $chargeObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($chargeObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index_charge.php?actTo=ewfSelect&billId='.$chargeObj['id'].'&examCode=oa_asset_charge&formName=资产领用申请审批' );
			} else {
				msgRf('编辑成功');
			}
		}else{
			if ("audit" == $actType) {
				 echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				msgRf('编辑失败');
			}
		}
	}
	
	/**
	 * 跳转到资产管理员签收时确认转入设备页面
	 */
	function c_toSignToDevice() {
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		//附件
		$obj ['file'] = $service->getFilesByObjId ( $obj ['id'], false );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('signtodevice',true);
	}
	
	/**
	 * 资产管理员签收时确认转入设备
	 */
	function c_signToDevice() {
		$this->checkSubmit(); //检验是否重复提交
		$result = $this->service->signToDevice_d ($_POST [$this->objName]);
		if($result){
			msgRf('确认成功');
		}else{
			msgRf('确认失败');
		}
	}
}