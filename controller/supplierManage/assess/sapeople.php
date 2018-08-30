<?php
/*
 *评估供应商人员controller类方法
 * */
class controller_supplierManage_assess_sapeople extends controller_base_action {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-11-10 下午04:33:17
	 */
	function __construct () {
		$this->objName = "sapeople";
		$this->objPath = "supplierManage_assess";
		parent::__construct();
	}

/**********************************我的评估********************************************/

	/**
	 * @desription 我的评估
	 * @param tags
	 * @date 2010-11-15 上午09:57:32
	 */
	function c_sapMyAssTab () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyAssTab' );
	}

	/**
	 * @desription 我的评估列表
	 * @param tags
	 * @date 2010-11-15 上午10:02:30
	 */
	function c_sapMyAssList () {
		if( isset($_GET['pj'])&&$_GET['pj']==1  ){
			$assDao = new model_supplierManage_assess_assessment();
			$service = $this->service;
			$service->getParam ( $_POST ); //设置前台获取的参数信息
			$service->groupBy = " a.id ";
			$service->searchArr = array(
				"assesStatusArr" => $assDao->statusDao->statusEtoK("ongoing"),
				"statusArr" => $service->statusDao->statusEtoK("save"),
				"asseserId" => $_SESSION['USER_ID']
			);
			$this->searchVal( 'assesName' );
			$this->searchVal( 'createName' );
			$rows = $service->sapPage_d ("select_MyAss2");
			$arr = array ();
			$arr ['collection'] = $rows;
			$arr ['totalSize'] = $service->count;
			$arr ['page'] = $service->page;
			echo util_jsonUtil::encode ( $arr );
		}else{
			$this->show->display ( $this->objPath . '_' . $this->objName . '-MyAssList' );
		}
	}

	/**
	 * @desription 我的评估列表(关闭)
	 * @param tags
	 * @date 2010-11-15 上午10:44:01
	 */
	function c_sapMyAssListClose () {
		if( isset($_GET['pj'])&&$_GET['pj']==1  ){
			$service = $this->service;
			$service->getParam ( $_POST ); //设置前台获取的参数信息
			$service->groupBy = " c.id ";
			$service->searchArr = array(
				"statusArr" => $service->statusDao->statusEtoK("submit").",".$service->statusDao->statusEtoK("close"),
				//"pAssUser" => $_SESSION['USER_ID']
				"asseserId" => $_SESSION['USER_ID']
			);
			$this->searchVal( 'assesName' );
			$this->searchVal( 'createName' );
			$rows = $service->sapPage_d ("select_MyAss2");
			$arr = array ();
			$arr ['collection'] = $rows;
			$arr ['totalSize'] = $service->count;
			$arr ['page'] = $service->page;
			echo util_jsonUtil::encode ( $arr );
		}else{
			$this->show->display ( $this->objPath . '_' . $this->objName . '-MyAssListClose' );
		}
	}

	/**
	 * @desription 跳转评估列表
	 * @param tags
	 * @date 2010-11-15 下午02:42:46
	 */
	function c_assessment () {
		if( isset($_GET['pj'])&&$_GET['pj']==1  ){
			$assesId = isset( $_GET['assesId'] )?$_GET['assesId']:exit;
			$service = $this->service;
			$service->getParam ( $_POST ); //设置前台获取的参数信息
			$service->groupBy = " c.id ";
			$service->searchArr = array(
				"asseserId" => $_SESSION['USER_ID'],
				"assesId" => $assesId
			);
			$rows = $service->sapPage_d ("select_Supp");
			$arr = array ();
			$arr ['collection'] = $rows;
			$arr ['totalSize'] = $service->count;
			$arr ['page'] = $service->page;
			echo util_jsonUtil::encode ( $arr );
		}else{
			$this->show->assign("assId",$_GET['assId']);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-assessment' );
		}
	}

	/**
	 * @desription 评估工作
	 * @param tags
	 * @date 2010-11-15 下午03:34:49
	 */
	function c_assessmentToWork () {
		$suppId = isset($_GET['suppId'])?$_GET['suppId']:exit;
		$assesId = isset($_GET['assesId'])?$_GET['assesId']:exit;
		$service = $this->service;
		$service->searchArr = array(
			"asseserId" => $_SESSION['USER_ID'],
			"assesId" => $assesId
		);
		$peoArr = $service->sapPage_d ();
		$normDao = new model_supplierManage_assess_norm();
		$this->show->assign("list",  $normDao->getNorm( $assesId ,$suppId) );
		$this->show->assign("suppId",$suppId);
		$this->show->assign("assesId",$assesId);
		$this->show->assign("peopleId",$peoArr['0']['id']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-assessmentWork' );
	}

	/**
	 * @desription 评估保存
	 * @param tags
	 * @date 2010-11-16 上午10:05:26
	 */
	function c_assessmentWork () {
		if( $this->service->saveAssessmentWork_d($_POST['obj']  ) ){
			msg("操作成功");
		}else{
			msg("操作失败");
		}
	}

}
?>
