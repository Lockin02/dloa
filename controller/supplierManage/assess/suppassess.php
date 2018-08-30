<?php
/*
 *������Ӧ��controller�෽��
 * */
class controller_supplierManage_assess_suppassess extends controller_base_action {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-11-10 ����04:33:17
	 */
	function __construct () {
		$this->objName = "suppassess";
		$this->objPath = "supplierManage_assess";
		parent::__construct();
	}

	/**
	 * @desription ��Ӧ������б�
	 * @param tags
	 * @date 2010-11-11 ����05:39:22
	 */
	function c_sasAddList () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add-list' );
	}

	/**
	 * @desription ��ת��ӷ���
	 * @param tags
	 * @date 2010-11-11 ����07:52:57
	 */
	function c_sasToAdd () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription ��ӹ�Ӧ��
	 * @param tags
	 * @date 2010-11-11 ����08:30:27
	 */
	function c_sasAdd () {
		$objArr = $_POST [$this->objName];
		$this->service->pk = "id";
//		$aa = $this->service->findCount( array( "assesId"=>$objArr['assesId'],"suppId"=>$objArr['suppId'] ) );
//		if($aa>0){
//			msgGo("�Ѵ��ڹ�Ӧ�̣����ʧ�ܣ�");
//		}else{
			$suppIdArr = explode(",", $objArr['suppId']);
			$suppNameArr = explode(",", $objArr['suppName']);
			$suppArr=array();
			foreach ($suppIdArr as $key => $value) {
				$suppArr[$value]['assesId']=$objArr['assesId'];
				$suppArr[$value]['suppId']=$value;
				$suppArr[$value]['suppName']=$suppNameArr[$key];
			}
			$tag = $this->service->addBatch_d ( $suppArr );
			if ($tag) {
				//$objArr ['id'] = $id;
				//$objArr ['operType_'] = "���������Ӧ�̡�" . $objArr ['suppName'] . "��";
				//$this->behindMethod ( $objArr );
				msg ( '��ӳɹ�' );
			}else{
				msg ( '���ʧ�ܣ�����ѡȡ�Ĺ�Ӧ���Ѿ����ڣ�' );
			}
		//}

	}

	/**
	 * @desription ��Ӧ��gridjson����
	 * @param tags
	 * @date 2010-11-11 ����07:11:00
	 */
	function c_sasPageJson () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = array( "assesId"=>$assId );
		$rows = $service->sasPage_d ();
		$resultArr=array();
		foreach($rows as $key=>$val){
			$val['skey_']=$this->md5Row($val['suppId'],'supplierManage_formal_flibrary');
			array_push($resultArr,$val);
		}
		$arr = array ();
		$arr ['collection'] = $resultArr;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription �鿴
	 * @param tags
	 * @date 2010-11-16 ����07:26:28
	 */
	function c_sasView () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//��ȫУ��
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read-list' );
	}


}
?>
