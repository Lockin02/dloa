<?php
/**
 * �ʼ�������Ʋ���
 */
class controller_mail_mailapply extends controller_base_action {

	function __construct() {
		$this->objName = "mailapply";
		$this->objPath = "mail";
		parent::__construct ();
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		//���������ֵ�
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**��ת���ҵ��ʼ������б�
	*author can
	*2011-4-15
	*/
	function c_toMyApplyList(){
		$this->display('mylist');
	}
	/**��ת���ҵ��ʼ����뵥����tabҳ
	*author can
	*2011-4-15
	*/
	function c_toMailApplyAudit(){
		$this->display('audit-tab');
	}

	/**�������ʼ������б�
	*author can
	*2011-4-15
	*/
	function c_toMyAuditList(){
		$this->display('auditno-list');
	}

	/**�������ʼ������б�
	*author can
	*2011-4-15
	*/
	function c_toMyAuditYes(){
		$this->display('audityes-list');
	}


	/**
	 * �ʼ�����
	 */
	function c_mailTask(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$deptName=$this->service->getDeptByUserId($_SESSION['USER_ID']);
		if($deptName=="����"){
			$service->searchArr['applyType']="invoiceapply";
		}else{
			$service->searchArr['applyTypes']="invoiceapply";
		}
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr=array("outApplyExaStatus"=>AUDITED);

		//$service->asc = false;
		$rows = $service->pageBySqlId ('select_mails');
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**�ҵ��ʼ������б�
	*author can
	*2011-4-15
	*/
	function c_myApplyListJson(){
		$service=$this->service;
		$service->getParam($_POST);
		$service->asc = true;
		$service->searchArr['createId']=$_SESSION['USER_ID'];
		$rows=$service->pageBySqlId("select_mails");
		$arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**�������б�Json
	*author can
	*2011-4-15
	*/
	function c_myAuditPj() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ('sql_examine');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**�������б�Json
	*author can
	*2011-4-15
	*/
	function c_pageJsonAuditYes(){
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_audited');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ʼ���༭ҳ��
	 */
	function c_init() {
		$mailApply = $this->service->get_d ( $_GET ['id'] );
//		echo "<pre>";
//		print_r ( $mailApply );
		foreach ( $mailApply as $key => $val ) {
			if ($key == 'mailproducts') {
				$str = $this->showproductslist ( $val );
				$this->show->assign ( 'mailproducts', $str[0] );
				$this->assign( 'rowNum',$str[1] );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mailApply ['mailType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * ��ʼ������-�鿴ҳ��
	 */
	function c_readInfo() {
		$mailApply = $this->service->get_d ( $_GET ['id'] );
		$actType=isset($_GET['actType'])?$_GET['actType']:null;
		$this->show->assign("actType",$actType);//����ҳ��(һ��Ĳ鿴ҳ�桢��Ƕ����������)
		//print_r ( $mailApply );
		foreach ( $mailApply as $key => $val ) {
			if( $key == 'mailType' ){
				$val = $this->getDataNameByCode( $val );
			}
			$this->assign ( $key, $val );
			if( $key == 'mailproducts' ){
				$str = $this->showproductslistView( $val );
				$this->assign( 'mailproducts',$str );
			}
		}
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mailApply [mailType] );
		$this->display ( 'read' );
	}

	/**
	 * �ʼ�����:ֻ��ʾ�������뵥����ͨ�����ʼ��������û�����������뵥���ʼ�����
	 */
	function c_page() {
		$this->display( 'list' );
	}

	/**
	 * �ʼļ�¼�б�
	 */
	function c_mailRecordsList() {
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//��ҳ
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $service->count ) );

		$this->show->assign ( 'list', $service->showListRecords ( $rows, $showpage ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listrecords' );
	}

	/**
	 * ҳ����ʾ��̬�ʼ������Ʒ���÷���,�����ַ�����ҳ��ģ���滻�������޸ĵ�������
	 */
	function showproductslist($rows) {
		$str = ""; //���ص�ģ���ַ���
		if (is_array ( $rows )) {
			$j = 0; //�б��¼���
			foreach ( $rows as $key => $val ) {
				$j++;
				$str .= <<<EOT
						<tr><td align="center">$j</td>
				<td align="center">
					<input type="text" id="productName$j" class="txtlong" name="mailapply[productsdetail][$j][productName]" value="$val[productName]"/>
				</td>
				<td align="center"><input type="text" class="txtmiddle"
					name="mailapply[productsdetail][$j][mailNum]" value="$val[mailNum]"/></td>
				<td align="center"><img
					src='images/closeDiv.gif' onclick="mydel(this,'productslist')"
					title='ɾ����'></td>
			</tr>
EOT;
			}

		}
		return array( $str,$j );
	}



	/**
	 * ҳ����ʾ�ʼ������Ʒ���÷���,�����ַ�����ҳ��ģ���滻�����ڲ鿴�ʼ�����
	 */
	function showproductslistView($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
						<tr>
				<td align="center">$j</td>
				<td align="center">
					$val[productName]
				</td>
				<td align="center">
					$val[mailNum]
				</td>
			</tr>
EOT;
				$i ++;
			}

		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}


}
?>