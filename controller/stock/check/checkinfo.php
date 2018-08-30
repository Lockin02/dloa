<?php

/**
 * @author Administrator
 * @Date 2011年8月9日 19:37:07
 * @version 1.0
 * @description:盘点基本信息控制层
 */
class controller_stock_check_checkinfo extends controller_base_action {
	
	function __construct() {
		$this->objName = "checkinfo";
		$this->objPath = "stock_check";
		parent::__construct ();
	}
	
	/**
	 * 跳转到列表页
	 */
	function c_toList() {
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->view ( "overage-list" );
				break; //盘盈
			

			case 'SHORTAGE' :
				$this->view ( "shortage-list" );
				break; //盘亏毁损
			default :
				break;
		}
	}
	
	/**
	 * @desription 跳转到查看页面
	 */
	function c_init() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$checkinfo = $this->service->get_d ( $_GET ['id'] );
		foreach ( $checkinfo as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showViewDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		
		if ($checkinfo ['checkType'] == "OVERAGE")
			$this->show->assign ( "checkType", "盘盈入库" );
		else {
			$this->show->assign ( "checkType", "盘亏毁损" );
		}
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->display ( 'overage-view' );
				break;
			
			case 'SHORTAGE' :
				$this->display ( 'shortage-view' );
				break;
			default :
				break;
		}
	
	}
	
	/**
	 * @desription 跳转到修改页面
	 */
	function c_toEdit() {
		$this->permCheck ();
		$id = $_GET ['id'];
		$checkType = $_GET ['checkType'];
		$this->checkAuditLimit ( $checkType );
		$check = $this->service->get_d ( $_GET ['id'] );
		
		foreach ( $check as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showEditDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$this->show->assign ( "itemscount", count ( $check ['details'] ) );
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->view ( 'overage-edit' );
				break;
			case 'SHORTAGE' :
				$this->view ( 'shortage-edit' );
				break;
			default :
				break;
		}
	}
	
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		
		$checkType = $_GET ['checkType'];
		$this->checkAuditLimit ( $checkType );
		if ("OVERAGE" == $checkType) {
			$this->view ( 'overage-add' );
		} else {
			$this->view ( 'shortage-add' );
		}
	}
	
	/**
	 * 跳转到打印页面
	 */
	function c_toPrint() {
		$this->permCheck ();
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$checkinfo = $this->service->get_d ( $_GET ['id'] );
		foreach ( $checkinfo as $key => $val ) {
			if ($key == 'details') {
				$str = $this->service->showViewDePro ( $val );
				$this->show->assign ( 'list', $str );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$checkType = isset ( $_GET ['checkType'] ) ? $_GET ['checkType'] : null;
		switch ($checkType) {
			case 'OVERAGE' :
				$this->view ( 'overage-print' );
				break;
			case 'SHORTAGE' :
				$this->view ( 'shortage-print' );
				break;
			
			default :
				break;
		}
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
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	
	//========================================================业务处理===================================//
	

	/**
	 * 新增盘点单
	 * @author chenzb
	 */
	function c_add() {
		$service = $this->service;
		$checkinfoObject = $_POST [$this->objName];
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$checkType = $checkinfoObject ['checkType'];
		/*s:--------------审核权限控制----------------*/
		if ("audit" == $actType) {
			if ($checkType == "OVERAGE") {
				if (! $service->this_limit ['盘盈入库审核']) {
					echo "<script>alert('没有权限进行审核!');window.close();</script>";
					exit ();
				}
			}
			if ($checkType == "SHORTAGE") {
				if (! $service->this_limit ['盘亏毁损审核']) {
					echo "<script>alert('没有权限进行审核!');window.close();</script>";
					exit ();
				}
			}
			$checkinfoObject ['auditUserName'] = $_SESSION ['USERNAME'];
			$checkinfoObject ['auditUserId'] = $_SESSION ['USER_ID'];
		}
		/*e:--------------审核权限控制----------------*/
		
		$id = $service->add_d ( $checkinfoObject );
		
		if ($id) {
			if ("audit" == $actType) {
				echo "<script>alert('审核单据成功!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('新增单据成功!'); window.opener.window.show_page();window.close();  </script>";
			}
		} else {
			if ("audit" == $actType) {
				
				echo "<script>alert('审核盘点失败,请确认该物料是否足够!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('新增盘点单失败,请确认单据信息是否完整!'); window.opener.window.show_page();window.close();  </script>";
			}
		}
	}
	
	/**
	 * 修改入库单
	 * @author chenzb
	 */
	function c_edit() {
		$service = $this->service;
		$checkinfoObject = $_POST [$this->objName];
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$checkType = $checkinfoObject ['checkType'];
		/*s:--------------审核权限控制----------------*/
		if ("audit" == $actType) {
			if ($checkType == "OVERAGE") {
				if (! $service->this_limit ['盘盈入库审核']) {
					echo "<script>alert('没有权限进行审核!');window.close();</script>";
					exit ();
				}
			}
			if ($checkType == "SHORTAGE") {
				if (! $service->this_limit ['盘亏毁损审核']) {
					echo "<script>alert('没有权限进行审核!');window.close();</script>";
					exit ();
				}
			}
			$checkinfoObject ['auditUserName'] = $_SESSION ['USERNAME'];
			$checkinfoObject ['auditUserId'] = $_SESSION ['USER_ID'];
		}
		/*e:--------------审核权限控制----------------*/
		
		$id = $service->edit_d ( $checkinfoObject );
		
		if ($id) {
			if ("audit" == $actType) {
				echo "<script>alert('审核盘点单成功!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('修改盘点单成功!'); window.opener.window.show_page();window.close();  </script>";
			}
		
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审核盘点失败,请确认该物料是否足够!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('修改盘点失败,请确认单据信息是否完整!'); window.opener.window.show_page();window.close();  </script>";
			}
		}
	}
	
	/*****************************核算部分结束**************************************/
	
	/**
	 *
	 * 盘点审核权限判断
	 * @author chenzb
	 */
	function checkAuditLimit($checkType) {
		switch ($checkType) {
			case 'OVERAGE' :
				if ($this->service->this_limit ['盘盈入库审核']) {
					$this->assign ( "auditLimit", "1" );
				} else {
					$this->assign ( "auditLimit", "0" );
				}
				break;
			case 'SHORTAGE' :
				//审核权限判断
				if ($this->service->this_limit ['盘亏毁损审核']) {
					$this->assign ( "auditLimit", "1" );
				} else {
					$this->assign ( "auditLimit", "0" );
				}
				break;
			default :
				break;
		}
	}
	
	/**
	 * 反审核
	 * @author chenzb
	 */
	function c_cancelAudit() {
		$service = $this->service;
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$checkType = isset ( $_POST ['checkType'] ) ? $_POST ['checkType'] : null;
		if ($service->ctCancelAudit ( $id, $checkType )) {
			echo 1;
		} else
			echo 0;
	}
}
?>