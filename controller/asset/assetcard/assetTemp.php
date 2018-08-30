<?php
/**
 * @author Administrator
 * @Date 2012��10��7�� 10:36:31
 * @version 1.0
 * @description:��Ƭ������ʱ����Ʋ�
 */
class controller_asset_assetcard_assetTemp extends controller_base_action {

	function __construct() {
		$this->objName = "assetTemp";
		$this->objPath = "asset_assetcard";
		parent::__construct ();
	 }

	/*
	 * ��ת����Ƭ������ʱ���б�
	 */
    function c_page() {
      $this->view('list');
    }

	/*
	 * ��ת����Ƭ����ȷ���б�ҳ--ȷ������
	 */
    function c_pageToFinancial() {
      $this->view('listtofinancial');
    }

	/*
	 * ��ת����Ƭ����ȷ���б�ҳ--ȷ������
	 */
    function c_pageToAdmin() {
      $this->view('listtoadmin');
    }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_adminJson() {
		$service = $this->service;
		$agencyDao = new model_asset_basic_agency();
		$agencyRows = $agencyDao->getByChargeId($_SESSION['USER_ID']);
		if(is_array($agencyRows)&&count($agencyRows)>0){
			$chargeIdArr = array();
			foreach($agencyRows as $key=>$val){
				$agencyCodeArr[]=$val['agencyCode'];
			}
			$agencyCodeStr = implode($agencyCodeArr);
		}
		if(!$agencyCodeStr){
			$agencyCodeStr='999999999';
		}
		$service->getParam ( $_REQUEST );
		$service->searchArr['agencyCodeArr']=$agencyCodeStr;
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

   /**
	 * ��ת��������Ƭ������ʱ��ҳ��
	 */
	function c_toAdd() {
		//��ȡ����������Ϣ
		$assetDao = new model_asset_assetcard_assetcard();
		$option = $assetDao->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);//�ʲ�����
		$this->assign('chnOption',$option['chnOption']);//�䶯��ʽ
		$this->assign('deprOption',$option['deprOption']);//�۾ɷ�ʽ
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//ʹ��״̬ -- �����ֵ�
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ) );//ʹ��״̬ -- �����ֵ�


		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_asset_card_temp_admin']['TO_ID'];
		$mailName = $mailUser['oa_asset_card_temp_admin']['TO_NAME'];
		$this->assign('TO_ID',$mailId);
		$this->assign('TO_NAME',$mailName);

        $assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName',$assetName);
        $spec = isset ($_GET['spec']) ? $_GET['spec'] : null;
		$this->assign('spec',$spec);
        $origina = isset ($_GET['origina']) ? $_GET['origina'] : 0;
		$this->assign('origina',$origina);

        $num = isset ($_GET['num']) ? $_GET['num'] : null;
		$this->assign('number',$num);

        $isPurch = isset ($_GET['isPurch']) ? $_GET['isPurch'] : null;
		$this->assign('isPurch',$isPurch);

        $receiveItemId = isset ($_GET['receiveItemId']) ? $_GET['receiveItemId'] : null;
		$this->assign('receiveItemId',$receiveItemId);

		$this->assign('companyCode',$_SESSION['COM_BRN_PT']);
		$this->assign('companyName',$_SESSION['COM_BRN_CN']);
		
		/******************����ת�ʲ���Ϣ*******************/
		//����id
		$requireinId = isset ($_GET['requireinId']) ? $_GET['requireinId'] : null;
		$this->assign('requireinId',$requireinId);
		//������ϸid
		$requireinItemId = isset ($_GET['requireinItemId']) ? $_GET['requireinItemId'] : null;
		$this->assign('requireinItemId',$requireinItemId);
		$this->assign('maxNum',$num);
		//������Ϣ
		$productId = isset ($_GET['productId']) ? $_GET['productId'] : null;
		$this->assign('productId',$productId);
		$productCode = isset ($_GET['productCode']) ? $_GET['productCode'] : null;
		$this->assign('productCode',$productCode);
		$productName = isset ($_GET['productName']) ? $_GET['productName'] : null;
		$this->assign('productName',$productName);
		//Ʒ��
		$brand = isset ($_GET['brand']) ? $_GET['brand'] : null;
		$this->assign('brand',$brand);

		$this->view ( 'add' );
	}

   /**
	 * ��ת��������Ƭ������ʱ��ҳ��
	 */
	function c_toAddByPurch() {
		//��ȡ����������Ϣ
		$assetDao = new model_asset_assetcard_assetcard();
		$option = $assetDao->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);//�ʲ�����
		$this->assign('chnOption',$option['chnOption']);//�䶯��ʽ
		$this->assign('deprOption',$option['deprOption']);//�۾ɷ�ʽ
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//ʹ��״̬ -- �����ֵ�
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ) );//ʹ��״̬ -- �����ֵ�


        $price = isset ($_GET['price']) ? $_GET['price'] : null;
		$this->assign('price',$price);
        $assetName = isset ($_GET['assetName']) ? $_GET['assetName'] : null;
		$this->assign('assetName',$assetName);
        $spec = isset ($_GET['spec']) ? $_GET['spec'] : null;
		$this->assign('spec',$spec);
        $origina = isset ($_GET['origina']) ? $_GET['origina'] : null;
		$this->assign('origina',$origina);

        $num = isset ($_GET['num']) ? $_GET['num'] : null;
		$this->assign('number',$num);

        $receiveItemId = isset ($_GET['receiveItemId']) ? $_GET['receiveItemId'] : null;
		$this->assign('receiveItemId',$receiveItemId);


		$this->view ( 'addbypurch' );
	}

   /**
	 * ��ת���༭��Ƭ������ʱ��ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��Ƭ������ʱ��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }


   /**
	 * ��ת���鿴��Ƭ������ʱ��ҳ��
	 */
	function c_toType() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_asset_card_temp_finance']['TO_ID'];
		$mailName = $mailUser['oa_asset_card_temp_finance']['TO_NAME'];
		$this->assign('TO_ID',$mailId);
		$this->assign('TO_NAME',$mailName);

		$this->view ( 'type' );
   }

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$obj = $_POST [$this->objName];
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign('propertyName',$obj['property']==1?"��ֵ����Ʒ":"�̶��ʲ�");
			$this->display ( 'view' );
		} else {
			$assetDao = new model_asset_assetcard_assetcard();
			$option = $assetDao->getBaseDate_d();
			$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//�ʲ���Դ -- �����ֵ�
			$this->assign('dirOption',$option['dirOption']);
			$this->assign('chnOption',$option['chnOption']);
			$this->assign('deprOption',$option['deprOption']);
			$this->view ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
//		echo "<pre>";
//		print_R($object);
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}
	
	/**
	 * ���¿�Ƭ���
	 */
	function c_updateAssetCode(){
		$this->service->updateAssetCode_d();
	}
	//ת���ʲ���Ƭ��¼����
	function c_toImport(){
		$this->view( 'import' );
	}
	//�ʲ���Ƭ��¼����
	function c_import(){
		$resultArr = $this->service->import_d();
		$title = '�ʲ���Ƭ��¼�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
 }