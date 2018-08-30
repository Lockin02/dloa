<?php
/**
 * @author huangzf
 * @Date 2011��11��1�� 11:21:38
 * @version 1.0
 * @description:������־�ֶο��Ʋ�
 */
class controller_syslog_operation_logoperationItem extends controller_base_action {

	function __construct() {
		$this->objName = "logoperationItem";
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
		$this->service->sort="columnCname desc,createTime ";
		$rows = $this->service->listBySqlId ( "select_detail" );

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
	 * ��ȡ���һ�β�����־��Json
	 */
	function c_LastListJson() {
		$service = $this->service;
		$logsettingDao = new model_syslog_setting_logsetting();
		$logObj = $logsettingDao->find(array('tableName' => $_POST['tableName']));
		$rows = $service->findByLogAndPk($logObj['id'] ,$_POST['id']);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>