<?php
/**
 * @description: ��Ŀ��̱��ƻ�action
 * @date 2010-9-18 ����11:20:15
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_milestone_rdmilestone extends controller_base_action {

	/**
	 * @desription ���캯��
	 * @date 2010-9-11 ����12:51:57
	 */
	function __construct() {
		$this->objName = "rdmilestone";
		$this->objPath = "rdproject_milestone";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ��̱��ƻ��޸�ҳ��
	 * @param tags
	 * @date 2010-10-3 ����07:37:33
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
	 * @desription ��̱��б�
	 * @param tags
	 * @date 2010-9-26 ����07:14:14
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
			$this->show->assign ( 'list', "<tr><td colspan='50'>������ؼ�¼</td></tr>" );
		}
		$this->show->assign ( "pjId", $pjId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * @desription ��̱������б�
	 * @param tags
	 * @return
	 * @date 2010-10-5 ����09:15:59
	 */
	function c_rmListCenter(){
		$service = $this->service;
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$rows = $service->rmArrBypjId_d($pjId);
		if( isset($rows['0']['pointArr']) && is_array($rows['0']['pointArr']) ){
			$this->show->assign ( 'list', $service->rmListCenter_s( $rows['0']['pointArr'] ) );
		}else{
			$this->show->assign ( 'list', "<tr><td colspan='50'>������ؼ�¼</td></tr>" );
		}
		$this->show->assign ( "pjId", $pjId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listCenter' );
	}

	/** @desription ��̱��б�
	 * @param tags
	 * @date 2010-9-26 ����07:14:14
	 */
	function c_rmListRead () {
		$service = $this->service;
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$rows = $service->rmArrBypjId_d($pjId);
		if( isset($rows['0']['pointArr']) && is_array($rows['0']['pointArr']) ){
			$this->show->assign ( 'list', $service->rmListRead_s( $rows['0']['pointArr'] ) );
		}else{
			$this->show->assign ( 'list', "<tr><td colspan='50'>������ؼ�¼</td></tr>" );
		}
		$this->show->assign ( "pjId", $pjId );
		$actType=isset($_GET['readType'])?$_GET['readType']:null;
		$this->show->assign("actType",$actType);//����ҳ��(һ��Ĳ鿴ҳ�桢��Ƕ����������)
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listRead' );
	}

	/**
	 * @desription ��̱�����
	 * @param tags
	 * @date 2010-10-13 ����11:42:25
	 */
	function c_rmListManage () {
		$service = $this->service;
		$pjId = isset( $_GET['pjId'] )?$_GET['pjId']:exit;
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$rows = $service->rmArrBypjId_d($pjId);
		if( isset($rows['0']['pointArr']) && is_array($rows['0']['pointArr']) ){
			$this->show->assign ( 'list', $service->rmListRead_s( $rows['0']['pointArr'] ) );
		}else{
			$this->show->assign ( 'list', "<tr><td colspan='50'>������ؼ�¼</td></tr>" );
		}
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->show->assign ( "pjId", $pjId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listManage' );
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊajax����json����---------------------------------------------*
	 **************************************************************************************************/



}

?>
