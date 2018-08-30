<?php
/**
 * �ʼ���Ϣ���Ʋ���
 */
class controller_mail_mailinfo extends controller_base_action {

	function __construct() {
		$this->objName = "mailinfo";
		$this->objPath = "mail";
		parent::__construct ();
	}
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->show->assign ( 'mailApplyId', $_GET ['mailApplyId'] );
		$mailapplyDao = new model_mail_mailapply ();
		$mailapply = $mailapplyDao->get_d ( $_GET ['mailApplyId'] );
		$user = $_SESSION['USERNAME'];
		$this->assign( 'mailMan',$user );
//		echo "<pre>";
//		print_r( $mailapply );
		if ($mailapply ['id']) {
			foreach ( $mailapply as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->showproductslist ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
		} else {
			$this->show->assign ( 'mailproducts', "" );
			$this->show->assign ( 'linkman', '' );
			$this->show->assign ( 'tel', '' );
		}

		//���������ֵ�
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ),$mailapply['mailType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * ��ʾ�����ҳ�б�
	 */
	function c_page() {
		$this->display ( 'list' );
	}

	/**
	 * ��ʾ�����ҳ�б�
	 */
	function c_pageByApplyId() {
		$mailApplyId = $_GET['mailApplyId'];
		$this->assign( 'mailApplyId',$mailApplyId );
		$this->display ( 'listByApplyId' );
	}

	/**�ҵ��ʼ�����-�鿴�ʼ���Ϣ
	*author can
	*2011-4-20
	*/
	function c_toMailInfo() {
		$mailApplyId = $_GET['mailApplyId'];
		$this->assign( 'mailApplyId',$mailApplyId );
		$this->display ( 'apply-list' );
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$mail = $this->service->get_d ( $_GET ['id'] );
		if(!empty($_GET['perm'])){
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->service->showMailInfo ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			//�ʼķ�ʽ
			$mailType=$this->getDataNameByCode($mail ['mailType']);
			$this->assign('mailType',$mailType);
			//�ʼ�״̬
			if($mail['mailStatus']==0){
				$mail['mailStatus']="δǩ��";
			}if($mail['mailStatus']==1){
				$mail['mailStatus']="��ǩ��";
			}
			$this->assign('mailStatus',$mail['mailStatus']);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		}else{
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->showproductslist ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mail ['mailType'] );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * ҳ����ʾ��̬�ʼ������Ʒ���÷���,�����ַ�����ҳ��ģ���滻�������޸ĵ�������
	 */
	function showproductslist($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						<input type="hidden" id="productId$j" name="mailinfo[productsdetail][$j][productId]" value="$val[productId]"/>
						<input type="hidden" id="productNo$j" name="mailinfo[productsdetail][$j][productNo]" value="$val[productNo]"/>
						<input type="text" id="productName$j" class="txtlong" name="mailinfo[productsdetail][$j][productName]" value="$val[productName]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][mailNum]" value="$val[mailNum]"/>
					</td>
					<td align="center">
						<img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='ɾ����'>
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_mylogpageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �ʼ�ȷ��
	 */
	function c_confirm(){
		if($this->service->confirm_d($_POST['id'])){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}


	/**
	 * �����ʼ����ͺ�id�鿴�ʼļ�¼
	 * url����Ҫ����docId��docType
	 */
	function c_viewByDoc(){
		$obj = $this->service->find ( array( 'docId'=> $_GET['docId'],'docType'=> $_GET['docType']) );
		$thisObj = $this->service->getObjCode($_GET['docType']);
		$this->assignFunc($obj);
		$this->assign('mailTypeCN',$this->getDataNameByCode($obj['mailType']));
		$this->display ( $thisObj.'-view' );
	}


	/***************************�����ʼĲ���*****************************/

	/**
	 * �����ʼ�
	 */
	function c_toAddByShip() {
		$service = $this->service;
		$shipInfo = $service->getShipMessage_d( $_GET['shipId'] );
		if($shipInfo['docType'] != 'independent'){
			$mailTo = $service->getMailman($shipInfo);
		}else{
			$mailTo=array();
		}
		foreach ( $shipInfo as $key => $val ){
			$mail = array(
				'docId' => $_GET['shipId'],
				'docCode' => $_GET['shipCode'],
				'docType' => $shipInfo['docType'],
				'linkman' => $shipInfo['linkman'],
				'tel' => $shipInfo['mobil'],
				'mailMan' => $shipInfo['shipman'],
				'mailManId' => $shipInfo['shipmanId'],
				'customerId' => $shipInfo['customerId'],
				'customerName' => $shipInfo['customerName'],
				'companyId' => $shipInfo['companyId'],
				'companyName' => $shipInfo['companyName'],
				'address' => $shipInfo['address'],
				'mailTime' => $shipInfo['shipDate'],
				'mailproducts' => $shipInfo['details'],
				'TO_NAME'=>$mailTo['TO_NAME'],
				'TO_ID'=>$mailTo['TO_ID']
			 );
		}
		foreach( $mail as $key => $val ){
			if ($key == 'mailproducts') {
				$str = $this->service->showproductsEdit ( $val );
				$this->show->assign ( 'mailproducts', $str[0] );
				$this->assign( 'rowNum',$str[1] );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		//���������ֵ�
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
		$this->display ( 'addbyship' );
	}

//
//	/**
//	 * �༭�ʼļ�¼
//	 */
//	function c_shipInit(){
//		$obj = $this->service->get_d ( $_GET ['id'] );
//		$this->assignFunc($obj);
//		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
//			$this->assign('mailTypeCN',$this->getDataNameByCode($obj['mailType']));
//			$this->display ( 'ship-view' );
//		} else {
//			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ),$obj['mailType'] );
//			$this->display ( 'ship-edit' );
//		}
//	}

	/**
	 * �����ʼ���Ϣ�б�
	 */
	 function c_shipList(){
	 	$docType = "YJSQDLX-FHYJ";
	 	$this->assign( 'docType',$docType );
	 	$this->display( 'ship-list' );
	 }

	/**
	 * �������ʼļ�¼�б�
	 */
	 function c_listByShip(){
	 	$docType = "YJSQDLX-FHYJ";
	 	$docId = $_GET['docId'];
	 	$this->assign( 'docId',$docId );
	 	$this->assign( 'docType',$docType );
	 	$this->display( 'listbyship' );
	 }

	 /**
	  * ��ͬ�ʼ��б�ҳ��
	  */
	 function c_listByOrderId(){
	 	$docType = $_GET['type'];
	 	$docId = $_GET['id'];
	 	$this->assign( 'docId',$docId );
	 	$this->assign( 'docType',$docType );
	 	$this->display( 'listbyorder' );
	 }

	/**
	 * ��ȡ��ҳ����ת��Json--��ͬ�ʼ��б�
	 */
	function c_orderJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$docIdArr = $service->getDocIdByOrder_d($service->searchArr['docId'],$service->searchArr['docType']);
		if($docIdArr['invoiceIds']){
			$this->service->searchArr['invoiceIds']=$docIdArr['invoiceIds'];
		}
		if($docIdArr['outplanIds']){
			$this->service->searchArr['outplanIds']=$docIdArr['outplanIds'];
		}
		$service->asc = true;
		$rows = $service->pageBySqlId("select_order");
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_shipJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = true;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['sql'] = $service->listSql;
        $_SESSION['shipJsonSql'] = substr_replace($service->listSql, '', strpos($service->listSql,'where'))."where 1=1 ";
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*****************����Ʊ�ʼĲ���*******************/
	/**
	 * ��Ʊ�ʼ���Ϣ�б�
	 * by show
	 */
	function c_invoiceList(){
		$thisObj = $this->service->getObjCode($_GET['docType']);
		$this->assign( 'docType' ,$_GET['docType'] );
	 	$this->display( $thisObj.'-list' );
	}

	/**
	 * �ʼļ�¼����ҳ��
	 */
	function c_invoiceAdd(){
		$thisObj = $this->service->getObjCode($_GET['docType']);

		$this->assign( 'mailManId',$_SESSION['USER_ID']);
		$this->assign( 'mailMan',$_SESSION['USERNAME']);
		$this->assign( 'docType' ,$_GET['docType'] );
		$this->assign( 'mailTime',day_date );
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
	 	$this->display( $thisObj.'-add' );
	}

	/**
	 * ������Ʊ�ʼ�ҳ��
	 */
	function c_addInvoice(){
		$rs = $this->service->addInvoice_d($_POST[$this->objName]);
		if($rs){
			msg('��ӳɹ�');
		}else{
			msg('���ʧ��');
		}
	}

	/**
	 * ���������ʼ�ҳ��
	 */
	function c_addShip(){
		$rs = $this->service->addShip_d($_POST[$this->objName]);
		if($rs){
			msg('��ӳɹ�');
		}else{
			msg('���ʧ��');
		}
	}


	/**
	 * ��ʼ������
	 */
	function c_shipInit() {
		$service=$this->service;
		$mail = $this->service->get_d ( $_GET ['id'] );
		$mailNo = $service->getMailNo($mail);
		$mailEqu = $service->getEqu($mail);
		$mail['mailproducts']=$mailEqu;
//		echo "<pre>";
//		print_R( $mail );
		if(!empty($_GET['perm'])){
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->service->showMailInfo ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				}elseif($key == 'mailStatus'){
					$mailStatus = $mail['mailStatus']==1?'��ȷ��':'δȷ��';
					$this->assign('mailStatus',$mailStatus);
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			//�ʼķ�ʽ
			$this->assign( 'mailNoStr',$mailNo );
			$this->assign('mailType',$this->getDataNameByCode($mail['mailType']));
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		}else{
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->service->showShipEdit ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			$this->assign( 'mailNoStr',$mailNo );
			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mail ['mailType'] );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * �����ʼ��޸�
	 */
	function c_shipEdit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->shipEdit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * �༭�ʼļ�¼
	 */
	function c_invoiceInit(){
		$obj = $this->service->get_d ( $_GET ['id'] );
		$thisObj = $this->service->getObjCode($_GET['docType']);
		$this->assignFunc($obj);
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign('mailTypeCN',$this->getDataNameByCode($obj['mailType']));
			$this->display ( $thisObj.'-view' );
		} else {
			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ),$obj['mailType'] );
			$this->display ( $thisObj.'-edit' );
		}
	}

	/**
	 * �ʼ����� - ������ҵ����������
	 */
	function c_addByPush(){
		$this->permCheck($_GET['docId'],'finance_invoice_invoice');
		$thisObj = $this->service->getObjCode($_GET['docType']);

		$docId = isset($_GET['docId']) ? $_GET['docId'] : null;
		$this->assign( 'docId' , $docId);

		$docCode = isset($_GET['docCode']) ? $_GET['docCode'] : null;
		$this->assign( 'docCode' , $docCode);

        $object = $this->service->getObjInfo_d($docId,$_GET['docType']);
        $object['thisAddress'] = empty($object['linkAddress']) ? $object['unitAddress'] : $object['linkAddress'];
        $this->assignFunc($object);

		$this->assign( 'mailManId',$_SESSION['USER_ID']);
		$this->assign( 'mailMan',$_SESSION['USERNAME']);
		$this->assign( 'docType' ,$_GET['docType'] );
		$this->assign( 'mailTime',day_date );
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
	 	$this->display( $thisObj.'-addbypush' );
	}

	/**
	 * �ʼķ�����Ϣ��������
	 * 2011��7��30�� 13:53:20
	 * zengzx
	 */
	 function c_toFareImport(){
	 	$this->display('excelimport');
	 }
	/*
	 * �ϴ�EXCEL
	 */
	function c_upFareExcel() {
		$objKeyArr = array (
			0 => 'mailNo',
			1 => 'number',
			2 => 'weight',
			3 => 'serviceType',
			4 => 'fare',
			5 => 'anotherfare',
			6 => 'mailMoney',
		); //�ֶ�����
		$resultArr = $this->service->addExecelDatabypro_d ( $objKeyArr );
		$title = '�ʼķ�����Ϣ�������б�';
		$thead = array( '�ʼĵ���','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

    /**
     * ����excel����
     */
    function c_toExportExcel(){
        $colId = isset($_REQUEST['colId'])? $_REQUEST['colId'] : '';
        $colName = isset($_REQUEST['colName'])? $_REQUEST['colName'] : '';
        $searchConditionKey = isset($_REQUEST['searchConditionKey'])? $_REQUEST['searchConditionKey'] : '';
        $searchConditionVal = isset($_REQUEST['searchConditionVal'])? $_REQUEST['searchConditionVal'] : '';
        $docType = isset($_REQUEST['docType'])? $_REQUEST['docType'] : '';
        $this->assign('colId',$colId);
        $this->assign('colName',$colName);
        $this->assign('searchConditionKey',$searchConditionKey);
        $this->assign('searchConditionVal',$searchConditionVal);
        $this->assign('docType',$docType);

        $year = date("Y");
        $yearStr = "";
        while ($year >= 2010) {
            $yearStr .="<option value='$year'>" . $year . "��</option>";
            $year --;
        }
        $this->assign('year',$yearStr);

        $month = date("m");
        $monthStr = '';
        $beginMonth = 12;
        while ($beginMonth > 0) {
            $selected = $beginMonth == $month ? 'selected="selected"' : '';
            $beginMonthVal = ($beginMonth < 10)? '0'.$beginMonth : $beginMonth;
            $monthStr .="<option value='$beginMonthVal' " . $selected . ">" . $beginMonth . "��</option>";
            $beginMonth --;
        }
        $this->assign('month',$monthStr);
        $this->view("toExportExcel");
    }

    /**
     * ����EXCEL
     */
	function c_exportExcel(){
	    $mailStatusArr = array(
	        "0" => "δȷ��",
            "1" => "��ȷ��"
        );
        $statusArr = array(
            "0" => "δǩ��",
            "1" => "��ǩ��"
        );
        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(600);
        $rows = array ();
        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $searchConditionKey = isset($_GET['searchConditionKey'])? $_GET['searchConditionKey'] : ''; //��ͨ������Key
        $searchConditionVal = isset($_GET['searchConditionVal'])? $_GET['searchConditionVal'] : ''; //��ͨ������Val

        if( isset($_SESSION['advSql']) ){
            $_REQUEST['advSql'] = $_SESSION['advSql'];
        }

        // ������ǰ̨һ�µ�����ѯ��Ӧ������
        $service->getParam($_REQUEST);
        $service->asc = true;
        if($searchConditionKey != '') {
            $service->searchArr[$searchConditionKey] = $searchConditionVal;
        }

        // ������ڻ������,���û�������ѯ,�����д��������ѯ
        if(isset($_SESSION['shipJsonSql']) && $_SESSION['shipJsonSql'] != ''){
            $rows = $service->listBySql($_SESSION['shipJsonSql']);
        }else{
            $rows = $service->list_d ();
        }

        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);

        //ƥ�䵼����
        $dataArr = array ();
        $colIdArr = array_flip($colIdArr);

        foreach ($rows as $key => $row) {
            foreach ($colIdArr as $index => $val) {
                if($index == 'mailStatus'){
                    $colIdArr[$index] = $mailStatusArr[$row[$index]];
                }else if($index == 'status'){
                    $colIdArr[$index] = $statusArr[$row[$index]];
                }else{
                    $colIdArr[$index] = $row[$index];
                }
            }
            array_push($dataArr, $colIdArr);
        }
//        echo "<pre>";print_r($dataArr);exit();
        $mailExcelDao = new model_mail_mailExcelUtil();
        return $mailExcelDao->export2ExcelUtil($colArr, $dataArr);
    }
}
?>