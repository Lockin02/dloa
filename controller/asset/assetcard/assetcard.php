<?php
/**
 * @author Administrator
 * @Date 2011��11��16�� 14:35:53
 * @version 1.0
 * @description:�̶��ʲ���Ƭ���Ʋ�
 */
class controller_asset_assetcard_assetcard extends controller_base_action {

	function __construct() {
		$this->objName = "assetcard";
		$this->objPath = "asset_assetcard";
		parent::__construct ();
	 }

	/**
	 * ��ת���̶��ʲ���Ƭ
	 */
    function c_page() {
      	$this->view('list');
    }

	/**
	 * ��ת�����˹̶��ʲ���Ƭ
	 */
    function c_mypage() {
    	$this->assign('userId',$_SESSION['USER_ID']);
    	$this->assign('userName',$_SESSION['USERNAME']);
      	$this->view('mylist');
    }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );

		//�ʲ���Ƭ-�����б�
		if(!isset($_REQUEST['currentId']) && !isset($_REQUEST['userId'])){
			//��ȡ����Ȩ���ַ���
			$agencyStr = $service->getAgencyStr_d();
			if(!empty($agencyStr)){//����Ȩ����Ϊ������ʾ����
				if($agencyStr != ';;'){//����Ȩ�޲�Ϊȫ��
					//ƴװ�Զ���sql
					$sqlStr = "sql: and c.agencyCode in (".$agencyStr.")";
					$service->searchArr['agencyCondition'] = $sqlStr;
				}
				$rows = $service->pageBySqlId ('select_list');
			}		
		}else{//�ʲ���Ƭ-�����ʲ��б�
			$rows = $service->pageBySqlId ('select_list');
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['listSql'] = $service->listSql;
		session_start();
		$_SESSION['listSql']=$service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_selectPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$service->groupBy = 'assetCode';
		$rows = $service->pageBySqlId ('select_list');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['listSql'] = $service->listSql;
		session_start();
		$_SESSION['listSql']=$service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���̶��ʲ���Ƭ
	 */
    function c_pageByPro() {
		$projectId = isset ($_GET['projectId']) ? $_GET['projectId'] : "";
		$this->assign('projectId',$projectId);
		$this->display('listbypro');
    }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageByProJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$this->service->searchArr['hasUseProId'] = 1;
		$service->groupBy = 'assetCode';
		$rows = $service->pageBySqlId ('select_list');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���̶��ʲ���Ƭ --  ����productId����
	 */
    function c_pageByProduct() {
		$productId = isset ($_GET['productId']) ? $_GET['productId'] : "";
		$this->assign('productId',$productId);
		$this->display('listbyproduct');
    }

	/**
	 * ��ת���̶��ʲ���Ƭ
	 */
    function c_pageToView() {
		$this->view('list-view');
    }

	/**
	 * ��ת������ҳ��
	 */
	function c_toCreat() {
		$tempDao = new model_asset_assetcard_assetTemp();
		$tempInfo = $tempDao->get_d($_GET['id']);
		//��ȡ����������Ϣ
		$option = $this->service->getBaseDate_d();
		$this->assign('tempId',$_GET['id']);//�ʲ�����
		$this->assign('dirOption',$option['dirOption']);//�ʲ�����
		$this->assign('chnOption',$option['chnOption']);//�䶯��ʽ
		$this->assign('deprOption',$option['deprOption']);//�۾ɷ�ʽ
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//ʹ��״̬ -- �����ֵ�

		foreach ( $tempInfo as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$tempInfo['assetSource'] );//�ʲ���Դ -- �����ֵ�


		$this->view ( 'update' );
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		//��ȡ����������Ϣ
		$option = $this->service->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);//�ʲ�����
		$this->assign('chnOption',$option['chnOption']);//�䶯��ʽ
		$this->assign('deprOption',$option['deprOption']);//�۾ɷ�ʽ
		$this->showDatadicts ( array ('useOption' => 'SYZT' ) );//ʹ��״̬ -- �����ֵ�
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ) );//ʹ��״̬ -- �����ֵ�


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
		$this->view ( 'add' );
	}
	
	/**
	 * �����������
	 */
	function c_add() {
//		echo $_POST [$this->objName][num];
		if($_POST [$this->objName]['num']!=""){
			$this->service->addCard_d($_POST [$this->objName]);
			msg ( '�ɹ������ʲ���Ƭ��' );
		}else{
			$id = $this->service->add_d ( $_POST [$this->objName], true );
		}
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
		//$this->listDataDict();
	}

	/**
	 * �����������
	 */
	function c_addBeach() {
//		echo "<pre>";
//		print_R($_POST [$this->objName]);
		if($_POST [$this->objName]['num']!=""){
			$this->service->addCard_d($_POST [$this->objName]);
			msg ( '�ɹ������ʲ���Ƭ��' );
		}else{
			$id = $this->service->addBeach_d ( $_POST [$this->objName], true );
		}
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
		//$this->listDataDict();
	}

	/**
	 * �������¿�Ƭ
	 */
	function c_updateBeach() {
//		echo "<pre>";
//		print_R($_POST [$this->objName]);
		$flag = $this->service->updateBeach_d($_POST [$this->objName]);
//		$id = $this->service->addBeach_d ( $_POST [$this->objName], true );
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '��ӳɹ���';
		$tempId = $_POST [$this->objName]['tempId'];
		if($tempId){
			$tempDao = new model_asset_assetcard_assetTemp();
		 	$statusInfo = array(
		 		'id' => $_POST [$this->objName]['tempId'],
		 		'isFinancial' => 1,
		 	);
		 	$tempDao->updateById( $statusInfo );
		}
		if ($flag) {
			msg ( $msg );
		}
		//$this->listDataDict();
	}
	
	/**
	 * �����������
	 */
	function c_createBeach() {
//		echo "<pre>";
//		print_R($_POST [$this->objName]);
		$id = $this->service->addBeach_d ( $_POST [$this->objName], true );
		$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '��ӳɹ���';
		$tempId = $_POST [$this->objName]['tempId'];
		if($tempId){
			$tempDao = new model_asset_assetcard_assetTemp();
		 	$statusInfo = array(
		 		'id' => $_POST [$this->objName]['tempId'],
		 		'isCreate' => 1,
		 	);
		 	$tempDao->updateById( $statusInfo );
		}
		if ($id) {
			msg ( $msg );
		}
		//$this->listDataDict();
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
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$option = $this->service->getBaseDate_d();
			$this->showDatadicts ( array ('useOption' => 'SYZT' ),$obj['useStatusCode'] );
			$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//�ʲ���Դ -- �����ֵ�
			$this->assign('dirOption',$option['dirOption']);
			$this->assign('chnOption',$option['chnOption']);
			$this->assign('deprOption',$option['deprOption']);
			$this->view ( 'edit' );
		}
	}

	/**
	 * ��ʼ������
	 */
	function c_toEditByAdmin() {
		$this->permCheck (); //��ȫУ��
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($obj['property']=='0'){
			$this->assign('assetProperty','�̶��ʲ�');
		}else{
			$this->assign('assetProperty','��ֵ����Ʒ');
		}
		$option = $service->getBaseDate_d();
		$this->showDatadicts ( array ('useOption' => 'SYZT' ),$obj['useStatusCode'] );
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//�ʲ���Դ -- �����ֵ�
		$this->assign('agencyLimit',$service->this_limit['����䶯Ȩ��']);//���Ʋ���Ա�Ƿ���Զ�����������б䶯
		$this->assign('dirOption',$option['dirOption']);
		$this->assign('chnOption',$option['chnOption']);
		$this->assign('deprOption',$option['deprOption']);
		$this->view ( 'editbyadmin' );
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '�༭�ɹ���';
			msg ( $msg );
		}
	}

	/**
	 * ��ʼ������
	 */
	function c_toViewDetail() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$dailyDao = new model_asset_daily_dailyCommon();
		$changeDao = new model_asset_change_assetchange();
		$changeInfo = $changeDao->getChangeInfoByAssetId($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch ($changeInfo['businessType']) {
			case 'allocation' :
				$this->assign ( 'relDocTypeName', '������' );
				break;
			case 'borrow' :
				$this->assign ( 'relDocTypeName', '���õ�' );
				break;
			case 'return' :
				$this->assign ( 'relDocTypeName', '�黹��' );
				break;
			case 'rent' :
				$this->assign ( 'relDocTypeName', '���õ�' );
				break;
			case 'keep' :
				$this->assign ( 'relDocTypeName', 'ά����' );
				break;
			case 'lose' :
				$this->assign ( 'relDocTypeName', '��ʧ��' );
				break;
			case 'charge' :
				$this->assign ( 'relDocTypeName', '���õ�' );
				break;
			default :
				break;
		}
		$businessCodeArr = $dailyDao->find(array('changeCode'=>$changeInfo['businessType']),null,'businessCode');
		$businessCode = $businessCodeArr['businessCode'];
		$className = $dailyDao->relatedClassNameArr[$businessCode];
		$this->assign('businessType',$className);
		$this->assign('businessId',$changeInfo['businessId']);
		$this->view ( 'viewdetails' );
	}
	
	/**
	 * �䶯��תҳ��
	 */
	function c_tochange(){
		$this->permCheck (); //��ȫУ��
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		//�汾��+1
		$obj['version']=$obj['version']*1+1;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//��ȡ������Ϣ
		$option = $service->getBaseDate_d();
		$this->assign('dirOption',$option['dirOption']);
		$this->assign('chnOption',$option['chnOption']);
		$this->assign('deprOption',$option['deprOption']);
		$this->showDatadicts ( array ('assetSource' => 'ZCLY' ),$obj['assetSource'] );//�ʲ���Դ -- �����ֵ�
		$this->showDatadicts ( array ('useOption' => 'SYZT' ),$obj['useStatusCode'] );
		$this->assign('agencyLimit',$service->this_limit['����䶯Ȩ��']);//���Ʋ���Ա�Ƿ���Զ�����������б䶯
		$this->view ( 'change' );
	}

	/**
	 * �䶯����
	 */
	function c_change($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->change_d ( $object, $isEditInfo )) {
			msg ( '�䶯�ɹ���' );
		}
	}

	/**
	 * ��ת��ѡ��̶��ʲ���ѡҳ��
	 */
	function c_selectAsset(){
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}
	
	/**
	 * ��ת��ѡ��̶��ʲ���ѡҳ��
	 */
	function c_selectCard(){
		$this->assign ( 'showType', $_GET ['showType'] );
		$this->assign ( 'agencyCode', $_GET ['agencyCode'] );
		$this->assign ( 'deptId', $_GET ['deptId'] );
		
		$this->view ( "selectCard" );
	}
	
	/**
	 * �Ƿ����ҵ��
	 */
	 function c_isRelated(){
        $assetId = isset ($_POST['id']) ? $_POST['id'] : null;
        $this->service->isRelated_d($assetId);
	 }
	 
	/****************************************���뵼������****************************************/
	/**
	 * ��ת���ʲ���Ϣ����ҳ�� ---��ʼ����Ϣ
	 * @create 2012��7��9�� 09:51:04
	 * @author zengzx
	 */
    function c_toOldImport() {
      $this->view( 'oldimport' );
    }

	/**
	 * ��ת���ʲ���Ϣ����ҳ��
	 * @create 2012��1��30�� 14:32:59
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * �ʲ���Ƭ��Ϣ����
	 * @create 2012��1��30�� 10:08:59
	 * @author zengzx
	 */
	function c_import(){
		$this->service->import_d();
	}

	/**
	 * �ʲ���Ƭ��Ϣ����
	 * @create 2012��1��30�� 10:08:59
	 * @author zengzx
	 */
	function c_oldImport(){
		$objKeyArr = array (
			0 => 'serial',//���
			1 => 'assetCode',//��Ƭ���
			2 => 'brand',//Ʒ��
			3 => 'spec',//�ͺ�
			4 => 'assetName',//����
			5 => 'machineCode',//������
			6 => 'unit',//��λ
			7 => 'number',//����
			8 => 'origina',//����
			9 => 'buyDate',//��������
			10 => 'belongMan',//������
			11 => 'orgName',//��������
			12 => 'temp',//���
			13 => 'remark',//��ע
			14 => 'assetTypeName',//�ʲ�����
			15 => 'agencyName'//���´�
		); //�ֶ�����
		$this->service->oldImport_d ( $objKeyArr );
	}

	function c_checkAsset(){
		$code = $_POST['assetCode'];
		if($code){
			echo $this->service->checkAsset_d($code);
		}else{
			echo 0;
		}
	}
	
	/**
	 * ������Ƭ��Ϣ
	 */
	function c_exportExcel() {
		set_time_limit(0);	//ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        ini_set('memory_limit', '1024M');	//�����ڴ�
        $rows = $this->service->_db->getArray($_SESSION['listSql']);

        if (empty($rows)) exit(util_jsonUtil::iconvGB2UTF('û�п��Ե���������'));

		//��ͷId����
		$colIdArr = array_filter(explode(',', $_GET['colId']));

		//��ͷName����
		if(util_jsonUtil::is_utf8($_GET['colName'])){
			$colNameArr = explode(',', util_jsonUtil::iconvUTF2GB($_GET['colName']));
		}else{
			$colNameArr = explode(',', $_GET['colName']);
		}
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);

		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
        unset($rows);
        unset($colIdArr);
		foreach ($dataArr as $key => $val) {
			if ($val['property'] == 0) {
				$dataArr[$key]['property'] = "�̶��ʲ�";
			}else{
				$dataArr[$key]['property'] = "��ֵ����Ʒ";
			}
		}
		return model_asset_assetcard_assetExcelUtil :: export2ExcelUtil($colArr, $dataArr);
	}
	
	/**
	 * ������Ƭ��Ϣ(CSV)
	 */
	function c_exportCSV() {
		set_time_limit(0);	//ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		$rows = $this->service->_db->getArray($_SESSION['listSql']);
	
		if (empty($rows)) exit(util_jsonUtil::iconvGB2UTF('û�п��Ե���������'));
	
		//��ͷId����
		$colIdArr = array_filter(explode(',', $_GET['colId']));
	
		//��ͷName����
		if(util_jsonUtil::is_utf8($_GET['colName'])){
			$colNameArr = explode(',', util_jsonUtil::iconvUTF2GB($_GET['colName']));
		}else{
			$colNameArr = explode(',', $_GET['colName']);
		}
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
	
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		unset($rows);
		unset($colIdArr);
		foreach ($dataArr as $key => $val) {
			if ($val['property'] == 0) {
				$dataArr[$key]['property'] = "�̶��ʲ�";
			}else{
				$dataArr[$key]['property'] = "��ֵ����Ʒ";
			}
		}
		return model_asset_assetcard_assetExcelUtil::exportCSV($colArr, $dataArr, '��Ƭ��Ϣ');
	}
	
	/**
	 * �����̵���Ϣ
	 */
	function c_exportCheckExcel(){
		set_time_limit(0);	//ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		ini_set('memory_limit', '1024M');	//�����ڴ�
	
		$service = $this->service;
		$service->sort = 'c.agencyName,c.createTime';
		$rows = $this->service->_db->getArray($_SESSION['listSql']);
		return model_asset_assetcard_assetExcelUtil::exportCheckExcel ( $rows );
	}
	
	/**
	 * �����̵���Ϣ(CSV)
	 */
	function c_exportCheckCSV() {
		set_time_limit(0);	//ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
			
		$service = $this->service;
		$service->sort = 'c.agencyName,c.createTime';
		$rows = $this->service->_db->getArray($_SESSION['listSql']);	
		return model_asset_assetcard_assetExcelUtil::exportCSV(array(
				'assetCode' => '�ʲ����', 'assetName' => '�ʲ�����', 'machineCode' => '�����', 'userName' => 'ʹ����',
				'useOrgName' => 'ʹ�ò���', 'brand' => 'Ʒ��', 'spec' => '�ͺ�', 'deploy' => '����',
				'belongMan' => '������', 'orgName' => '��������', 'agencyName' => '��������', 'wirteDate' => '��������',
				'origina' => 'ԭֵ', 'remark' => '��ע'
		), $rows, '�̶��ʲ��̵��');
	}
	
	/**
	 * �������Ȩ��
	 */
    function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * ��ȡ��ͬ��������
	 */
	function c_selectPayCon(){
		$this->assign('checkIds',$_GET['checkIds']);
		//ѡ��ģʽ
		$modeType = isset($_GET['modeType']) ? $_GET['modeType'] : 0;
		$this->assign('modeType',$modeType);

		$this->view('selectlist');
	}
	
	/**
	 * ת���ʲ���Ƭ����������
	 */
	function c_toUpdateBelongMan() {
		$this->view( 'updateBelongMan' );
	}
	
	/**
	 * �ʲ���Ƭ����������
	 */
	function c_updateBelongMan() {
		$resultArr = $this->service->updateBelongMan_d();
		$title = '�ʲ���Ƭ��¼�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	
	/**
	 * ת�����Ͽ�Ƭ��
	 */
	function c_scrapStock(){
		$this->view('scrap-stock');
	}
	
	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_getScrapListJson() {
		$assetIdArr = $_GET['assetIdArr'];
		$idArr = explode(",",$assetIdArr);
		$cardInfos = $this->service->getCardsByIdArr($idArr,true);
		echo util_jsonUtil::encode ( $cardInfos );
	}
	
	/**
	 * ��鿨Ƭ״̬
	 */
	function c_checkCardStatus() {
		$idArr = $_POST['assetIdArr'];
		$cardInfos = $this->service->getCardsByIdArr($idArr,true);
		$cardsUsed = array();
		foreach ($cardInfos as $key => $val){
			if($val['useStatusCode'] != 'SYZT-XZ'){
				array_push($cardsUsed, $val['assetCode']);
			}
		}
		echo util_jsonUtil::encode ( $cardsUsed );
	}
	
	/**
	 * ת�����������ʲ���Ƭ
	 */
	function c_toUpdateCard() {
		$this->view( 'updateCard' );
	}
	
	/**
	 * ���������ʲ���Ƭ
	 */
	function c_updateCard() {
		if($this->service->updateCard_d($_POST[$this->objName])){
            msg('���³ɹ�');
        }else{
        	msg('���κθ���');
        }
	}
	
	/**
	 * ��ȡ��Ҫ�������µĿ�Ƭ��Ϣ
	 */
	function c_getUpdateData(){
		echo util_jsonUtil::iconvGB2UTF($this->service->getUpdateData_d($_POST));
	}
	
	/**
	 * ��ת���޸Ĳ�������ҳ��
	 * Ŀǰֻ�޸�ԭֵ��Ԥ��ʹ���ڼ���
	 */
	function c_toEditfinancial(){
		$this->permCheck(); //��ȫУ��
		$id = $_GET ['id'];
		$obj = $this->service->get_d($id);
		$this->assign('id',$id);
		$this->assign('origina',$obj['origina']);
		$this->assign('estimateDay',$obj['estimateDay']);

		$this->view('editfinancial');
	}
	
	/**
	 * �޸Ĳ�������
	 */
	function c_editfinancial(){
		$object = $_POST [$this->objName];
		if($this->service->updateById($object,true)){
			$msg = isset($_POST["msg"]) ? $_POST ["msg"] : '�༭�ɹ���';
			msg ( $msg );
		}
	}
	
	/**
	 *�����ʲ����Ʊ�����Json
	 */
	function c_comboAssetInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//��������
		$searchType = $_REQUEST['searchType'];
		$service->searchArr['comboAssetInfo'] = "sql: and ".$searchType." <> '' and ".$searchType." is not null";
		$service->groupBy = $searchType;
		
		$rows = $service->page_d ();
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
	 *��������Ȩ�޻�ȡ������Ϣ
	 */
	function c_getAgency() {
		$agencyDao = new model_asset_basic_agency();
		//��ȡ����Ȩ���ַ���
		$agencyStr = $this->service->getAgencyStr_d();
		if(!empty($agencyStr)){
			if($agencyStr != ';;'){//����Ȩ�޲�Ϊȫ��
				$sql = "SELECT agencyCode,agencyName FROM oa_asset_agency WHERE agencyCode IN($agencyStr)";
				$rows = $agencyDao->listBySql ($sql);
			}else{
				$rows = $agencyDao->list_d ();
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ($rows);
		echo util_jsonUtil::encode ($rows);
	}
	
	/**
	 *��������Ȩ�޻�ȡ�������ַ���
	 */
	function c_getAgencyStr() {
		$agencyStr = $this->service->getAgencyStr_d();
		echo $agencyStr;
	}
	
	/****************************************�����ֵ����Ʒҵ�񲿷�****************************************/
	/**
	 * ��ת���������ֵ����Ʒҳ��
	 */
	function c_toCleanLowValueGoods(){
		$this->view('cleanlowvaluegoods');
	}
	
	/**
	 * ��ѯ�������ֵ����Ʒ
	 */
	function c_searchCleanLowValueGoods(){
		set_time_limit(0);
		$service = $this->service;
		$rows = $service->searchCleanLowValueGoods_d ($_POST);
		//����תhtml
		$rows = $service->serachHtml_d($rows);
		echo util_jsonUtil::iconvGB2UTF ( $rows );
	}
	
	/**
	 * ajax��ʽ�����ֵ����Ʒ
	 */
	function c_ajaxCleanLowValueGoods() {
		$ids =  $_POST['id'];
		if($this->service->cleanLowValueGoods_d($ids)){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * ��ת���������ֵ����Ʒҳ��
	 */
	function c_cleanedLowValueGoods(){
		$this->view('cleanedlowvaluegoods');
	}
	
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonOther() {
		$service = $this->service;
	
		$service->getParam ( $_REQUEST );
		$rows = $service->pageBySqlId ('select_other');
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
	 * �黹�ʲ�ʱ,��֤��Ƭ�Ƿ����ύ���黹
	 */
	function c_isReturning(){
		if($this->service->isReturning_d($_POST['id'],$_SESSION['USER_ID'])){
			echo 1;
		}else{
			echo 0;
		}
	}
 }