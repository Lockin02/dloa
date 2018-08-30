<?php

/**
 * 资产借用控制层类
 *  @author chenzb
 */
class controller_asset_daily_borrow extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "borrow";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}
	/**
	 * 跳转到用信息列表
	 */
	function c_page() {
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
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$codeDao = new model_common_codeRule ();
		$requireDao = new model_asset_require_requirement();
		$requireId = isset ($_GET['requireId']) ? $_GET['requireId'] : null;
		$requireCode = isset ($_GET['requireCode']) ? $_GET['requireCode'] : null;
		$requireObj = $requireDao->get_d($requireId);
		$chargeManId =  $requireObj['userId'];
		$chargeMan =  $requireObj['userName'];
		$applyCompanyCode =  $requireObj['userCompanyCode'];
		$applyCompanyName =  $requireObj['userCompanyName'];
		$chargeDeptId =  $requireObj['userDeptId'];
		$chargeDept =  $requireObj['userDeptName'];
		$reposeManId =  $requireObj['applyId'];
		$reposeMan =  $requireObj['applyName'];
		$reposeDeptId =  $requireObj['applyDeptId'];
		$reposeDept =  $requireObj['applyDeptName'];
		$this->assign ( 'deptId', $chargeDeptId );
		$this->assign ( 'deptName', $chargeDept );
		$this->assign ( 'applyCompanyCode', $applyCompanyCode );
		$this->assign ( 'applyCompanyName', $applyCompanyName );
		$this->assign ( 'chargeManId', $chargeManId );
		$this->assign ( 'chargeMan', $chargeMan );
		$this->assign ( 'reposeManId', $reposeManId );
		$this->assign ( 'reposeMan', $reposeMan );
		$this->assign ( 'reposeDeptId', $reposeDeptId );
		$this->assign ( 'reposeDept', $reposeDept );
		$this->assign ( 'remark', '收货地址：'.$requireObj['address'] );
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo=$deptDao->getDeptName_d( $_SESSION['DEPT_ID'] );
		$this->assign ( 'deptName', $deptInfo['DEPT_NAME']);
		$this->assign ( 'borrowDate', day_date );
		$this->assign ( 'returnDate', $requireObj['returnDate'] );
		$this->assign ( 'requireId', $requireId );
		$this->assign ( 'requireCode', $requireCode );
		$this->view ( 'add',true );
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		//附件
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		if(isset($_GET['btn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}

		foreach ( $obj as $key => $val ) {
				$this->show->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

 /**
	  * ajax删除借用主表及借用清单从表信息
	  */
	  function c_deletes(){
		$message = "";
		try {
            $borrowObj = $this->service->get_d ( $_GET ['id'] );
			$borrowitemDao = new model_asset_daily_borrowitem();
	  		$condition = array(
	  			'borrowId'=>$borrowObj['id']
	  		);
	  		$borrowitemDao->delete($condition);
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
				succ_show ( 'controller/asset/daily/ewf_index.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_asset_borrow&formName=资产借用审批' );
			}else{
				msgRf('新增成功');
			}
		}else{
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
      	 $borrowObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($borrowObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/asset/daily/ewf_index.php?actTo=ewfSelect&billId='.$borrowObj['id'].'&examCode=oa_asset_borrow&formName=资产借用审批' );
			} else {
				msgRf('编辑成功');
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				msgRf('编辑失败');
			}
		}
	}


	/**
	 *默认action跳转函数
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
	   * ajax签收借用申请
	   */
	   function c_toSign(){
		  	echo $this->service->sign_d($_POST['id']);
	   }

}