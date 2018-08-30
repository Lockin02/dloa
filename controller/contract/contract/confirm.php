<?php

class controller_contract_contract_confirm extends controller_base_action {

	function __construct() {
		$this->objName = "confirm";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * ��ת���������������¼�б�
	 */
    function c_page() {
		$sql = "select count(id) as num from oa_contract_confirm where state='δȷ��'";
		$arr = $this->service->_db->getArray($sql);
		$this->assign("num",$arr[0]['num']);
      $this->view('list');
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
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_ajaxCheckTip() {
		$id = $_POST ['id'];
		$val = $_POST ['val'];
        if($val == '1'){
			$setVal = "��ȷ��";
		}else{
			$setVal = "δȷ��";
		}

		$sql = "update oa_contract_confirm set state='" . $setVal . "',handleId='".$_SESSION ['USER_ID']."',handleName='".$_SESSION ['USERNAME']."',handleDate='".date("Y-m-d H:i:s")."' where id = '" . $id . "'";
		$this->service->_db->query($sql);
		echo 1;
	}

	/**
	 * ����
	 */
	function c_exportExcel()
	{
		$service = $this->service;
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		$rows = array();

		if(!isset($_GET['colId']) && !isset($_GET['colName'])){// ���ǰ��û�����Ӧ����ID�Լ�����,��SESSION�л�ȡ
			if(isset($_SESSION['exportCol'])){
				$colIdStr = $_SESSION['exportCol']['ColId'];
				$colNameStr = $_SESSION['exportCol']['ColName'];
				unset($_SESSION['exportCol']);
			}else{
				$colIdStr = '';
				$colNameStr = '';
			}
		}else{
			$colIdStr = $_GET['colId'];
			$colNameStr = $_GET['colName'];
		}

		$searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
		$searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
		$searchArr[$searchConditionKey] = $searchConditionVal;

		$service->getParam($_REQUEST);
		//��¼��
//         $appId = $_SESSION['USER_ID'];
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);

		if (!empty($this->service->searchArr)) {
			$this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
		} else {
			$this->service->searchArr = $searchArr;
		}

//         $rows = $service->page_d();
		ini_set('memory_limit', '1024M');
		$rows = $service->page_d ();

		$arr = array();
		$arr['collection'] = $rows;

		//ƥ�䵼����
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);

		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}


		return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr);
	}


 }
?>