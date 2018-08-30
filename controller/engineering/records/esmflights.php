<?php

/**
 * @author show
 * @Date 2016��05��12�� 15:43:22
 * @version 1.0
 * @description:��Ŀ��Ʊ������Ʋ�
 */
class controller_engineering_records_esmflights extends controller_base_action
{

	function __construct() {
		$this->objName = "esmflights";
		$this->objPath = "engineering_records";
		parent::__construct();
	}

	/**
	 * ��ѯҳ��
	 */
	function c_toSearchList() {
		$this->assign('projectId', isset($_GET['projectId']) ? $_GET['projectId'] : exit('can\'t find project id'));
		$this->view('list');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_searchJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->sort = "c.thisYear, c.thisMonth";
		$rows = $service->list_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);

        // ��ȡ�ֳ���������Ļ�Ʊ����
        $esmfieldDao = new model_engineering_records_esmfieldrecord();
        $esmfieldList = $esmfieldDao->businessFeeList_d('flightsShare', '', '',
            array('projectId' => $_REQUEST['projectId']));

        $fieldCount = 0;
        if (!empty($esmfieldList)) {
            foreach ($esmfieldList as $v) {
                $rows[] = array(
                    'thisYear' => $v['thisYear'],
                    'thisMonth' => $v['thisMonth'],
                    'fee' => $v['feeField'],
                    'from' => '��̯ϵͳ'
                );
                $fieldCount = bcadd($fieldCount, $v['feeField'], 2);
            }
        }

		if (!empty($rows)) {
			//������Ŀ�ϼ�
			$objArr = $service->listBySqlId('select_count');
			$rsArr = array();
			if ($objArr[0]['fee'] || $fieldCount) {
				$rsArr['fee'] = bcadd($objArr[0]['fee'], $fieldCount, 2);
				$rsArr['costType'] = '�� ��';
				$rsArr['id'] = 'noId';
                $rsArr['from'] = '';
			}
			$rows[] = $rsArr;
		}
		echo util_jsonUtil::encode($rows);
	}
}