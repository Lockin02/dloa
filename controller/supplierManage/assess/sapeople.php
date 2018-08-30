<?php
/*
 *������Ӧ����Աcontroller�෽��
 * */
class controller_supplierManage_assess_sapeople extends controller_base_action {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-11-10 ����04:33:17
	 */
	function __construct () {
		$this->objName = "sapeople";
		$this->objPath = "supplierManage_assess";
		parent::__construct();
	}

/**********************************�ҵ�����********************************************/

	/**
	 * @desription �ҵ�����
	 * @param tags
	 * @date 2010-11-15 ����09:57:32
	 */
	function c_sapMyAssTab () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyAssTab' );
	}

	/**
	 * @desription �ҵ������б�
	 * @param tags
	 * @date 2010-11-15 ����10:02:30
	 */
	function c_sapMyAssList () {
		if( isset($_GET['pj'])&&$_GET['pj']==1  ){
			$assDao = new model_supplierManage_assess_assessment();
			$service = $this->service;
			$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * @desription �ҵ������б�(�ر�)
	 * @param tags
	 * @date 2010-11-15 ����10:44:01
	 */
	function c_sapMyAssListClose () {
		if( isset($_GET['pj'])&&$_GET['pj']==1  ){
			$service = $this->service;
			$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * @desription ��ת�����б�
	 * @param tags
	 * @date 2010-11-15 ����02:42:46
	 */
	function c_assessment () {
		if( isset($_GET['pj'])&&$_GET['pj']==1  ){
			$assesId = isset( $_GET['assesId'] )?$_GET['assesId']:exit;
			$service = $this->service;
			$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * @desription ��������
	 * @param tags
	 * @date 2010-11-15 ����03:34:49
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
	 * @desription ��������
	 * @param tags
	 * @date 2010-11-16 ����10:05:26
	 */
	function c_assessmentWork () {
		if( $this->service->saveAssessmentWork_d($_POST['obj']  ) ){
			msg("�����ɹ�");
		}else{
			msg("����ʧ��");
		}
	}

}
?>
