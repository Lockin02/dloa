<?php


/**
 * 资产调拨控制层类
 *  @author chenzb
 */
class controller_asset_daily_allocation extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "allocation";
		$this->objPath = "asset_daily";
		parent :: __construct();
	}
	/**
	 * 跳转到调拨信息列表
	 */
	function c_page() {
		$this->view('list');
	}


	/**
	 * 跳转到调拨信息列表
	 */
	function c_myList() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->view('mylist');
	}
	/**
	 * 跳转到项目调拨列表
	 */
	function c_listByPro() {
		$this->view('prolist');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_allPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		foreach($rows as $key=>$row){
			$rows[$key]['deptId']=$this->service->getBillDept($row);
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
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['userId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		foreach($rows as $key=>$row){
			$rows[$key]['deptId']=$this->service->getBillDept($row);
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
	function c_pageByProJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['projectId'] = $_POST['projectId'];
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
		 * 跳转到新增页面
		 */
	function c_toAddByCard() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign('outDeptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('outDeptName', $deptInfo['DEPT_NAME']);
		$this->assign('moveDate', date("Y-m-d"));
		$this->assign('assetId', $_GET['assetId']);
		$this->view('addbycard');
	}

	/**
		 * 跳转到新增页面
		 */
	function c_toAdd() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign('outDeptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('outDeptName', $deptInfo['DEPT_NAME']);
		$this->assign('moveDate', date("Y-m-d"));
		$this->view('add');
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		if (isset ($_GET['btn'])) {
			$this->assign('showBtn', 1);
		} else {
			$this->assign('showBtn', 0);
		}

		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			//echo"1111";
			$this->view('view');
		} else {
			$this->view('edit');
		}
	}
	//签收方法，修改签收状态
	function c_sign() {
		try {
			$id = isset ($_GET['id']) ? $_GET['id'] : false;
			$allocationobject = array (
				"id" => $id,
				"isSign" => "已签收"
			);
			$this->service->updateById($allocationobject);
			echo 1;
		} catch (Exception $e) {
			throw $e;
			echo 0;
		}
	}
	/**
	  * ajax删除调拨主表及调拨清单从表信息
	  */
	function c_deletes() {
		$message = "";
		try {
			$allocationObj = $this->service->get_d($_GET['id']);
			$allocationitemDao = new model_asset_daily_allocationitem();
			$condition = array (
				'allocateID' => $allocationObj['id']
			);
			$allocationitemDao->delete($condition);
			$this->service->deletes_d($_GET['id']);

			$message = '<div style="color:red" align="center">删除成功!</div>';

		} catch (Exception $e) {
			$message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
		}
		if (isset ($_GET['url'])) {
			$event = "document.location='" . iconv('utf-8', 'gb2312', $_GET['url']) . "'";
			showmsg($message, $event, 'button');
		} else
			if (isset ($_SERVER[HTTP_REFERER])) {
				$event = "document.location='" . $_SERVER[HTTP_REFERER] . "'";
				showmsg($message, $event, 'button');
			} else {
				$this->c_page();
			}

		msg('删除成功！');

	}

	/**
	* 新增对象操作
	* @linzx
	*/
	function c_add() {
		$object = $_POST[$this->objName];
		$userDao = new model_deptuser_user_user();
		$proposerObj = $userDao->getUserById($object['proposerId']);//调出人
		$recipientObj = $userDao->getUserById($object['recipientId']);//调入人
		//调出部门
		$outDeptId = $proposerObj['DEPT_ID'];
		//调入部门
		$inDeptId = $recipientObj['DEPT_ID'];
		$deptId = $outDeptId.','.$inDeptId;
		$id = $this->service->add_d($object, true);
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show('controller/asset/daily/ewf_index_allocation.php?actTo=ewfSelect&billId='
				. $id . '&billDept='.$deptId);
			} else {
				echo "<script>alert('新增成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}

	/**
		 * 修改对象操作
		 * @chenzb
		 */
	function c_edit() {
		$object = $_POST[$this->objName];
		$userDao = new model_deptuser_user_user();
		$proposerObj = $userDao->getUserById($object['proposerId']);
		$recipientObj = $userDao->getUserById($object['recipientId']);
		//调出部门
		$outDeptId = $proposerObj['DEPT_ID'];
		//调入部门
		$inDeptId = $recipientObj['DEPT_ID'];
		$deptId = $outDeptId.','.$inDeptId;
		$id = $this->service->edit_d($object, true);
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show('controller/asset/daily/ewf_index_allocation.php?actTo=ewfSelect&billId='
				. $object['id'] . '&billDept='.$deptId);
			} else {
				echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

}
?>