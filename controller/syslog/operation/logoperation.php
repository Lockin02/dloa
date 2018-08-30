<?php
/**
 * @author huangzf
 * @Date 2011��11��1�� 11:21:38
 * @version 1.0
 * @description:������־���Ʋ�
 */
class controller_syslog_operation_logoperation extends controller_base_action {

	function __construct() {
		$this->objName = "logoperation";
		$this->objPath = "syslog_operation";
		parent::__construct ();
	}

	/*
	 * ��ת��������־
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 *
	 * ͨ��ҵ����Ϣ�鿴������־��¼ҳ��
	 */
	function c_businessView() {
		$pkValue = $_GET ['pkValue'] ? $_GET ['pkValue'] : null;
		$tableName = $_GET ['tableName'] ? $_GET ['tableName'] : null;

		$this->service->searchArr = array ("tableName" => $tableName, "pkValue" => $pkValue );
		$rows = $this->service->listBySqlId ( "select_detail" );


//		foreach ( $rows [0] as $key => $val ) {
//			$this->assign ( $key, $val );
//		}

		$this->assign("logList", $this->service->showAtBusinessView($rows));
		$this->view ( "business-view" );
	}

	/**
	 *
	 * ��ȡ������־��ϸ��Ϣ
	 */
	function c_pageDetailJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ( "select_detail" );
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
	 *
	 * ͨ��ҵ����Ϣ�鿴������־��¼ҳ��
	 */
	function c_businessList() {
		$tableName = $_GET ['tableName'] ? $_GET ['tableName'] : 'oa_finance_income';
		$this->assign('tableName',$tableName);

		$this->view ( "businesslist" );
	}

	/**
	 * �߼�����
	 */
	function c_toSearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "��</option>";
		}
		$this->assign('yearStr',$yearStr);

		$this->view ('search' );
	}


	/**
	 * ��ģ������켣�ϲ�
	 * ���ڹ���ԭ��Ϊ�����з��ն˿���ʱ�䣬��ȡ���ݱ�д��������ͨ�á�
	 */
	function c_businessViewMore() {
//		$pkValue = $_GET ['pkValue'] ? $_GET ['pkValue'] : null;
//		$tableName = $_GET ['tableName'] ? $_GET ['tableName'] : null;
        $tableNameStr = "oa_terminal_product,oa_terminal_terminaltype,oa_terminal_terminalinfo,oa_terminal_functiontype,oa_terminal_functioninfo";

		$this->service->searchArr = array ("tableNameStr" => $tableNameStr);
		$rows = $this->service->listBySqlId ( "select_detail" );


//		foreach ( $rows [0] as $key => $val ) {
//			$this->assign ( $key, $val );
//		}

		$this->assign("logList", $this->service->showAtBusinessViewMore($rows));
		$this->view ( "business-view" );
	}
}
?>