<?php
/*
 *评估供应商controller类方法
 * */
class controller_supplierManage_assess_norm extends controller_base_action {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-11-10 下午04:33:17
	 */
	function __construct () {
		$this->objName = "norm";
		$this->objPath = "supplierManage_assess";
		parent::__construct();
	}

	/**
	 * @desription 评估指标添加列表
	 * @param tags
	 * @date 2010-11-11 下午05:39:22
	 */
	function c_sanAddList () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->assign ( 'assId', $assId );
		$this->display ( 'add-list' );
	}

	/**
	 * @desription 跳转添加方法
	 * @param tags
	 * @date 2010-11-11 下午07:52:57
	 */
	function c_sanToAdd () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription 添加估指标
	 * @param tags
	 * @date 2010-11-11 下午08:30:27
	 */
	function c_sasAdd () {
		$objArr = $_POST [$this->objName];
		$id = $this->service->sasAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "添加评估指标【" . $objArr ['normName'] . "】";
			$this->behindMethod ( $objArr );
			msg ( '添加成功' );
		}else{
			msg ( '添加失败！' );
		}
	}

	/**
	 * @desription 跳转修改页面
	 * @param tags
	 * @date 2010-11-19 上午10:05:35
	 */
	function c_sasToEdit () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$arr = $this->service->sasArrById_d ( $id );
		$this->arrToShow ( $arr );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * @desription TODO
	 * @param tags
	 * @date 2010-11-19 上午10:51:28
	 */
	function c_sasEdit () {
		$objArr = $_POST [$this->objName];
		$this->service->deletes_d ( $objArr['id'] );
		$id = $this->service->sasAdd_d ( $objArr );
		if ($id) {
			msg ( '编辑成功' );
		}else{
			msg ( '编辑失败！' );
		}
	}

	/**
	 * @desription 查看界面
	 * @param tags
	 * @date 2010-11-17 下午02:45:41
	*/
	function c_sasRead () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$arr = $this->service->sasArrById_d ( $id );
		$this->arrToShow ( $arr );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * @desription 查看界面
	 * @param tags
	 * @date 2010-11-17 下午02:45:41

	function c_sasRead () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		$this->showDatadicts( array("gpglx"),$arr['0']['assesType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}*/

	/**
	 * @desription 指标gridjson数据
	 * @param tags
	 * @date 2010-11-11 下午07:11:00
	 */
	function c_sanPageJson () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = array( "assesId"=>$assId );
		//$rows = $service->page_d ();
		$rows = $service->getPage_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description  指标gridjson数据，重写的方法
	 * @date 2011-03-17
	 */
	function c_pageJson(){
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = array( "assesId"=>$assId );
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );}

	/**
	 * @desription 显示指标
	 * @param tags
	 * @date 2010-11-16 下午07:20:40
	 */
	function c_sanView () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//安全校验
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * @desription 查看结果
	 * @param tags
	 * @date 2010-11-17 上午09:32:29
	 */
	function c_sanViewNormPeo () {
		//$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$suppPjId = isset( $_GET['suppPjId'] )?$_GET['suppPjId']:exit;
		$arr = $this->service->getNormPeo_d ( $assId,$suppPjId );
		$this->show->assign ( 'list', $this->service->getNormPeo_s($arr) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-viewNormPeo' );
	}

}
?>
