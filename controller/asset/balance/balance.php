<?php
/**
 *
 * �����Ʋ���
 * @author fengxw
 *
 */
class controller_asset_balance_balance extends controller_base_action {

	function __construct() {
		$this->objName = "balance";
		$this->objPath = "asset_balance";
		parent::__construct ();
	}

	/*
	 * ��ת���ʲ��۾�
	 */
    function c_page() {
	  $this->assign('assetId',$_GET['assetId']);
      $this->view("list");
    }

    /**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->assign('flag',$_GET['flag']);
		$this->assign('deprTime', date("Y-m-d"));
		$this->view ( 'add' );
	}
	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$assetCode = isset ($_GET['assetCode']) ? $_GET['assetCode'] : null;
		$this->assign('assetCode',$assetCode);
		$assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName',$assetName);
//		$origina = isset ($_GET['origina']) ? $_GET['origina'] : null;
//		$this->assign('origina',$origina);
//		$netValue = isset ($_GET['netValue']) ? $_GET['netValue'] : null;
//		$this->assign('netValue',$netValue);

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if($id){
			if($_POST['flag']){
				msg('�ʲ��۾ɳɹ�');
			}else{
				msgGo('�ʲ��۾ɳɹ�');
			}
		}else{
			msgGo('�ʲ��۾�ʧ��');
		}
		//$this->listDataDict();
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->pageBySqlId ('select_balance_assetcard');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}

?>