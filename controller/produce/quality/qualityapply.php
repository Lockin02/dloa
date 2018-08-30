<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 10:47:28
 * @version 1.0
 * @description:�ʼ����뵥���Ʋ�
 */
class controller_produce_quality_qualityapply extends controller_base_action {

	function __construct() {
		$this->objName = "qualityapply";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}
	/**
	 * ��ת�����Tabҳ��
	 */
	function c_toPageTab() {
		$this->assign('relDocType',$_GET['relDocType']);
		$this->view ( 'list-tab' );
	}
	/**
	 * ��ת���ʼ����뵥�б�
	 */
	function c_page() {
		$this->assign('relDocType',$_GET['relDocType']);
		$this->view('list');
	}
	/**
	 * ��ת�����������ϸ
	 */
	function c_pageDetail(){
		$this->assign('detailStatusArr',$_GET['detailStatusArr']);
		$this->assign('relDocType',$_GET['relDocType']);
		$this->view('list-detail');
	}
	/**
	 * ������ϸ�� - ����Դ
	 */
	function c_jsonDetail(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ('select_detail');
		//Դ������Ϊ�����ģ���ȡ������ͬ�ţ��ͻ���Ϣ
		if($_REQUEST['relDocTypeArr'] == 'ZJSQYDSC'){
			$produceplanDao = new model_produce_plan_produceplan();
			foreach($rows as $k => $v){
				$rs = $produceplanDao->find(array('id' => $v['relDocId']),null,'relDocId,relDocCode,relDocTypeCode,customerId,customerName');
				if(!empty($rs)){
					$rows[$k]['contractId'] = $rs['relDocId'];
					$rows[$k]['contractCode'] = $rs['relDocCode'];
					$rows[$k]['contractTypeCode'] = $rs['relDocTypeCode'];
					$rows[$k]['customerId'] = $rs['customerId'];
					$rows[$k]['customerName'] = $rs['customerName'];
				}
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * ��ת���ʼ�����Tab
	 */
	function c_toMyTab() {
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->view ( 'mylist-tab' );
	}

	/**
	 * ��ת�������ʼ����뵥ҳ��
	 */
	function c_toAdd() {
		$relDocType = isset ( $_GET ['relDocType'] ) ? $_GET ['relDocType'] : exit('�����������');
		$relDocId = isset ( $_GET ['relDocId'] ) ? $_GET ['relDocId'] : exit('�����������');
		$service = $this->service;
		//Դ����Ӧ����model
		$relClass = $service->applyStrategyArr [$relDocType];
		$relClassM = new $relClass ();//����ʵ��
		//��ȡԴ����Ϣ
		$relDocObj = $service->ctGetRelDocInfo ( $relDocId, $relClassM );
        $this->assignFunc($relDocObj);
        $this->assign('relDocType',$relDocType);
        $this->assign('relDocTypeName',$this->getDataNameByCode($relDocType));
        $this->assign('relDocId',$relDocId);
        $this->assign('relDocCode',$_GET['relDocCode']);

	 	$this->view ( 'add' );
	}

	/**
	 * ��ת�������ʼ����뵥ҳ��-ԭ���ϼ���
	 */
	function c_toAddByArrival() {
		$relDocType = isset ( $_GET ['relDocType'] ) ? $_GET ['relDocType'] : exit('�����������');
		$relDocId = isset ( $_GET ['relDocId'] ) ? $_GET ['relDocId'] : exit('�����������');
		//��ȡԴ����Ϣ
		$arrivalDao = new model_purchase_arrival_arrival();
		$object = $arrivalDao->get_d($relDocId);
		$relDocObj['applyUserName'] = $object['purchManName'];
		$relDocObj['applyUserCode'] = $object['purchManId'];
		$relDocObj['supplierName'] = $object['supplierName'];
		$relDocObj['supplierId'] = $object['supplierId'];
		$this->assignFunc($relDocObj);
		$this->assign('relDocType',$relDocType);
		$this->assign('relDocTypeName',$this->getDataNameByCode($relDocType));
		$this->assign('relDocId',$relDocId);
		$this->assign('relDocCode',$_GET['relDocCode']);

		$this->view ( 'add' );
	}

	/**
	 * �����ӱ��� -- �Ӳ���ȡ��
	 */
	function c_toAddDetail(){
		$relDocType = isset ( $_POST ['relDocType'] ) ? $_POST ['relDocType'] : "";
		$relDocId = isset ( $_POST ['relDocId'] ) ? $_POST ['relDocId'] : "";
		$service = $this->service;
		//Դ����Ӧ����model
		$relClass = $service->applyStrategyArr [$relDocType];
		$relClassM = new $relClass ();//����ʵ��
        $detailArr = $service->ctGetRelDetailInfo ( $relDocId, $relClassM );
		//��ȡԴ����Ϣ
		echo util_jsonUtil::encode($detailArr);
	}

    /**
     * �����������
     */
    function c_add($isAddInfo = false) {
        $this->checkSubmit();
        $id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
        if ($id) {
            msg ( $msg );
        }
        //$this->listDataDict();
    }

	/**
	 * ��ת���༭�ʼ����뵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴�ʼ����뵥ҳ��
	 */
	function c_toView($id = null) {
        $id = isset( $_GET ['id']) ?  $_GET ['id'] : $id;
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $id );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('relDocTypeName',$this->getDataNameByCode($obj['relDocType']));
		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴�ʼ����뵥ҳ��
	 */
	function c_toQualityView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        //�ʼ���������Ⱦ
        $this->assignFunc($this->service->getMail_d('purchquality'));
		$this->assign('relDocTypeName',$this->getDataNameByCode($obj['relDocType']));
		$this->view ( 'view-quality' );
	}

    /**
     * �鿴��������ʼ���Ϣ
     */
    function c_searchQuality(){
        $rs = $this->service->findQuality_d($_GET['relDocItemId']);
        if($rs['thisType'] == 'apply'){
            $this->c_toView($rs['mainId']);
        }else{
            succ_show("?model=produce_quality_qualityereport&action=toView&id=".$rs['mainId']);
        }
    }
}
?>