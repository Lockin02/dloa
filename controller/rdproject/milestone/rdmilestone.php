<?php
/**
 * @description: 项目里程碑计划action
 * @date 2010-9-18 上午11:20:15
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_milestone_rdmilestone extends controller_base_action {

	/**
	 * @desription 构造函数
	 * @date 2010-9-11 下午12:51:57
	 */
	function __construct() {
		$this->objName = "rdmilestone";
		$this->objPath = "rdproject_milestone";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 里程碑计划修改页面
	 * @param tags
	 * @date 2010-10-3 下午07:37:33
	 */
	function c_miestUpdateTo () {
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$arr = $this->service->rmArrBypjId_d($pjId);
		$this->arrToShow($arr);
		$str = "";
		$pointDao = new model_rdproject_milestone_rdmilespoint();
		$str = $pointDao->rmPointU_s( $arr['0']['pointArr'],$pjId );
		$arrBasic = $pointDao->rmBasicArr_d($arr['0']['pointArr'],$pjId);
		$strBasic = $pointDao->rmPointU2_s( $arrBasic,$pjId );

		$this->show->assign ( "pjId", $pjId );
		$this->show->assign ( "list", $str );
		$this->show->assign ( "list2", $strBasic );
		$this->show->display($this->objPath . '_' . $this->objName . '-update');
	}

	/**
	 * @desription 里程碑列表
	 * @param tags
	 * @date 2010-9-26 下午07:14:14
	 */
	function c_rmList () {
		$service = $this->service;
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$rows = $service->rmArrBypjId_d($pjId);
//		echo "<pre>";
//		print_r($rows['0']['pointArr']);
		if( isset($rows['0']['pointArr']) && is_array($rows['0']['pointArr']) ){
			$this->show->assign ( 'list', $service->rmList_s( $rows['0']['pointArr'] ) );
		}else{
			$this->show->assign ( 'list', "<tr><td colspan='50'>暂无相关记录</td></tr>" );
		}
		$this->show->assign ( "pjId", $pjId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * @desription 里程碑中心列表
	 * @param tags
	 * @return
	 * @date 2010-10-5 上午09:15:59
	 */
	function c_rmListCenter(){
		$service = $this->service;
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$rows = $service->rmArrBypjId_d($pjId);
		if( isset($rows['0']['pointArr']) && is_array($rows['0']['pointArr']) ){
			$this->show->assign ( 'list', $service->rmListCenter_s( $rows['0']['pointArr'] ) );
		}else{
			$this->show->assign ( 'list', "<tr><td colspan='50'>暂无相关记录</td></tr>" );
		}
		$this->show->assign ( "pjId", $pjId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listCenter' );
	}

	/** @desription 里程碑列表
	 * @param tags
	 * @date 2010-9-26 下午07:14:14
	 */
	function c_rmListRead () {
		$service = $this->service;
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$rows = $service->rmArrBypjId_d($pjId);
		if( isset($rows['0']['pointArr']) && is_array($rows['0']['pointArr']) ){
			$this->show->assign ( 'list', $service->rmListRead_s( $rows['0']['pointArr'] ) );
		}else{
			$this->show->assign ( 'list', "<tr><td colspan='50'>暂无相关记录</td></tr>" );
		}
		$this->show->assign ( "pjId", $pjId );
		$actType=isset($_GET['readType'])?$_GET['readType']:null;
		$this->show->assign("actType",$actType);//操作页面(一般的查看页面、内嵌在审批表单中)
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listRead' );
	}

	/**
	 * @desription 里程碑管理
	 * @param tags
	 * @date 2010-10-13 上午11:42:25
	 */
	function c_rmListManage () {
		$service = $this->service;
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$rows = $service->rmArrBypjId_d($pjId);
		if( isset($rows['0']['pointArr']) && is_array($rows['0']['pointArr']) ){
			$this->show->assign ( 'list', $service->rmListRead_s( $rows['0']['pointArr'] ) );
		}else{
			$this->show->assign ( 'list', "<tr><td colspan='50'>暂无相关记录</td></tr>" );
		}
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->show->assign ( "pjId", $pjId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listManage' );
	}


	/***************************************************************************************************
	 * ------------------------------以下为ajax返回json方法---------------------------------------------*
	 **************************************************************************************************/



}

?>
