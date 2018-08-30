<?php
/*
 *������Ӧ��controller�෽��
 * */
class controller_supplierManage_assess_norm extends controller_base_action {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-11-10 ����04:33:17
	 */
	function __construct () {
		$this->objName = "norm";
		$this->objPath = "supplierManage_assess";
		parent::__construct();
	}

	/**
	 * @desription ����ָ������б�
	 * @param tags
	 * @date 2010-11-11 ����05:39:22
	 */
	function c_sanAddList () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->assign ( 'assId', $assId );
		$this->display ( 'add-list' );
	}

	/**
	 * @desription ��ת��ӷ���
	 * @param tags
	 * @date 2010-11-11 ����07:52:57
	 */
	function c_sanToAdd () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription ��ӹ�ָ��
	 * @param tags
	 * @date 2010-11-11 ����08:30:27
	 */
	function c_sasAdd () {
		$objArr = $_POST [$this->objName];
		$id = $this->service->sasAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "�������ָ�꡾" . $objArr ['normName'] . "��";
			$this->behindMethod ( $objArr );
			msg ( '��ӳɹ�' );
		}else{
			msg ( '���ʧ�ܣ�' );
		}
	}

	/**
	 * @desription ��ת�޸�ҳ��
	 * @param tags
	 * @date 2010-11-19 ����10:05:35
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
	 * @date 2010-11-19 ����10:51:28
	 */
	function c_sasEdit () {
		$objArr = $_POST [$this->objName];
		$this->service->deletes_d ( $objArr['id'] );
		$id = $this->service->sasAdd_d ( $objArr );
		if ($id) {
			msg ( '�༭�ɹ�' );
		}else{
			msg ( '�༭ʧ�ܣ�' );
		}
	}

	/**
	 * @desription �鿴����
	 * @param tags
	 * @date 2010-11-17 ����02:45:41
	*/
	function c_sasRead () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$arr = $this->service->sasArrById_d ( $id );
		$this->arrToShow ( $arr );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * @desription �鿴����
	 * @param tags
	 * @date 2010-11-17 ����02:45:41

	function c_sasRead () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		$this->showDatadicts( array("gpglx"),$arr['0']['assesType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}*/

	/**
	 * @desription ָ��gridjson����
	 * @param tags
	 * @date 2010-11-11 ����07:11:00
	 */
	function c_sanPageJson () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * @description  ָ��gridjson���ݣ���д�ķ���
	 * @date 2011-03-17
	 */
	function c_pageJson(){
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = array( "assesId"=>$assId );
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );}

	/**
	 * @desription ��ʾָ��
	 * @param tags
	 * @date 2010-11-16 ����07:20:40
	 */
	function c_sanView () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//��ȫУ��
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * @desription �鿴���
	 * @param tags
	 * @date 2010-11-17 ����09:32:29
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
