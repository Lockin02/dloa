<?php
/**
 * @description: 供应商评估
 * @date 2010-11-9 下午02:51:01
 */
class controller_supplierManage_assess_assessment extends controller_base_action{
 	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-11-9 下午08:53:05
	 */
	function __construct() {
		$this->objName = "assessment";
		$this->objPath = "supplierManage_assess";
		//统一注册监控字段，如果不同方法有不同的监控字段，在各自方法里面更改此数组
		$this->operArr = array ("name" => "任务名称", "status" => "状态" );
		parent::__construct();
	}

	/**
	 * @desription 跳转新增供应商评估
	 * @param tags
	 * @date 2010-11-8 下午02:18:04
	 */
	function c_saaToAdd () {
		$this->showDatadicts( array("gpglx") );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
     * 注册供应商信息评估
	 * @date 2010-9-20 下午02:06:22
	 */
	function c_saaAdd() {
		$objArr = $_POST [$this->objName];
		$objArr['status'] = $this->service->statusDao->statusEtoK("save");
		$id = $this->service->add_d ( $objArr,true );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "添加评估方案【" . $objArr ['assesName'] . "】";
			$this->behindMethod ( $objArr );
			succ_show ( '?model=supplierManage_assess_suppassess&action=sasAddList&assId='.$id );
		}else{
			msg ( '添加失败！' );
		}
	}

	/**
	 * @desription 跳转修改
	 * @param tags
	 * @date 2010-11-14 上午10:07:25
	 */
	function c_saaToEdit () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		$this->showDatadicts( array("gpglx"),$arr['0']['assesType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * @desription 修改
	 * @param tags
	 * @date 2010-11-14 上午10:20:18
	 */
	function c_saaEdit () {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			succ_show ( '?model=supplierManage_assess_suppassess&action=sasAddList&assId='.$object['id'] );
		}else{
			msg ( '修改失败！' );
		}
	}

	/**
	 * @desription 查看
	 * @param tags
	 * @date 2010-11-12 下午04:13:04
	 */
	function c_saaRead () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$suppDao = new model_supplierManage_assess_suppassess();
		$suppArr = $suppDao->getAllByAssesId($assId);
		$suppStr = $suppDao->sasReadList_d($suppArr);

		$normDao = new model_supplierManage_assess_norm();
		$normArr = $normDao->getAllByAssesId_d($assId);
		$normStr = $normDao->sasReadList_d($normArr);
		$this->arrToShow ( $arr );

		$this->show->assign ( 'suppStr',  $suppStr );
		$this->show->assign ( 'normStr',  $normStr );
		$this->show->assign ( 'assId',  $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * @desription 完成评估跳转
	 * @param tags
	 * @date 2010-11-14 上午11:36:47
	 */
	function c_assToClose () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		$closeArr = $this->service->showClose($assId);
		$this->show->assign ( 'list',  $this->service->showClose_s($closeArr) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-close' );
	}

	/**
	 * @desription 关闭评估
	 * @param tags
	 * @date 2010-11-14 上午11:36:47
	 */
	function c_assClose () {
		$assId = isset( $_POST['assId'] )?$_POST['assId']:exit;
		$object = array(
			"id" => $assId
		);
		$this->beforeMethod( $object );
		$val = $this->service->assClose_d( $_POST );
		if ( $val ) {
			$object ['operType_'] = '关闭评估';
			$this->behindMethod ( $object );
			msgBack2 ( '关闭评估成功！');
		}else{
			msgBack2 ( '关闭评估失败！');
		}
	}

	/**
	 * @desription 启动评估
	 * @param tags
	 * @date 2010-11-13 上午10:52:28
	 */
	function c_saaStart () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		if( $this->service->saaStartIs_d($assId) ){
			$object = array(
				"id" => $assId,
				"status" => $this->service->statusDao->statusEtoK("ongoing")
			);
			$this->beforeMethod( $object );
			$val = $this->service->edit_d($object, true);
			if ( $val ) {
				$object ['operType_'] = '启动评估';
				$this->behindMethod ( $object );
				msgGo ( '启动评估成功！','?model=supplierManage_assess_assessment&action=saaMyMangeTab' );
			}
		}else{
			msgGo ( '请先完善评估方案再进行提交！','?model=supplierManage_assess_assessment&action=saaMyMangeTab' );
		}
	}

/**********************************我的评估管理********************************************/

	/**
	 * @desription 我负责的tab页
	 * @param tags
	 * @date 2010-11-13 下午02:46:16
	 */
	function c_saaMyMangeTab () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyManageTab' );
	}

	/**
	 * @desription 我负责的评估（未关闭）
	 * @param tags
	 * @date 2010-11-13 下午02:03:57
	 */
	function c_saaMyMangeList () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyMangeList' );
	}

	/**
	 * @desription 我负责的评估列表Json（未关闭）
	 * @param tags
	 * @date 2010-11-13 下午02:11:22
	 */
	function c_saaPJMyManage () {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = array(
			"manageId" => $_SESSION['USER_ID'],
			"statusArr" => $service->statusDao->statusEtoK("save"). "," .$service->statusDao->statusEtoK("ongoing")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 我负责的评估（关闭）
	 * @param tags
	 * @date 2010-11-13 下午03:08:54
	 */
	function c_saaMyMangeListClose () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyMangeListClose' );
	}

	/**
	 * @desription 我负责的评估列表Json（已关闭）
	 * @param tags
	 * @date 2010-11-13 下午02:11:22
	 */
	function c_saaPJMyManageClose () {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = array(
			"manageId" => $_SESSION['USER_ID'],
			"statusArr" => $service->statusDao->statusEtoK("close"). "," .$service->statusDao->statusEtoK("end")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

/**********************************评估管理********************************************/

	/**
	 * @desription 评估管理的tab页
	 * @param tags
	 * @date 2010-11-13 下午02:46:16
	 */
	function c_saaMangeTab () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-ManageTab' );
	}

	/**
	 * @desription 评估管理的评估（未关闭）
	 * @param tags
	 * @date 2010-11-13 下午02:03:57
	 */
	function c_saaMangeList () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MangeList' );
	}

	/**
	 * @desription 评估管理的评估列表Json（未关闭）
	 * @param tags
	 * @date 2010-11-13 下午02:11:22
	 */
	function c_saaPJManage () {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = array(
			"statusArr" => $service->statusDao->statusEtoK("save"). "," .$service->statusDao->statusEtoK("ongoing")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 评估管理的评估（关闭）
	 * @param tags
	 * @date 2010-11-13 下午03:08:54
	 */
	function c_saaMangeListClose () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MangeListClose' );
	}

	/**
	 * @desription 评估管理的评估列表Json（已关闭）
	 * @param tags
	 * @date 2010-11-13 下午02:11:22
	 */
	function c_saaPJManageClose () {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = array(
			"statusArr" => $service->statusDao->statusEtoK("close"). "," .$service->statusDao->statusEtoK("end")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

/**********************************评估管理********************************************/
	/**
	 * @desription 提交
	 * @param tags
	 * @date 2010-11-16 下午02:42:06
	 */
	function c_submit () {
		$id = isset($_GET['assId'])?$_GET['assId']:exit;
		$val = $this->service->submit_d( $id );
		if( $val==0 ){
			msgGo ( '请先填写完整你的评估表再进行提交！');
		}else if($val==1){
			msgGo ( '提交成功！');
		}
	}

	/**
	 * @desription 查看页面Tab
	 * @param tags
	 * @date 2010-11-16 下午04:49:16
	 */
	function c_saaViewTab () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//安全校验
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId',  $assId );
		$this->show->assign ( 'viewPerm',  "1" );
		$this->show->assign ( 'skey_',  $_GET['skey'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-saaViewTab' );
	}

	/**
	 * @desription 查看页面
	 * @param tags
	 * @date 2010-11-16 下午07:07:33
	 */
	function c_saaView () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//安全校验
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts( array("gpglx"),$arr['0']['assesType'] );
		$this->show->assign ( 'gpglx',  $this->getDataNameByCode($arr['0']['assesType']) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

}

?>
