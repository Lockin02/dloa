<?php

/**
 * @author show
 * @Date 2016年05月12日 15:43:22
 * @version 1.0
 * @description:项目机票决算控制层
 */
class controller_engineering_records_esmflights extends controller_base_action
{

	function __construct() {
		$this->objName = "esmflights";
		$this->objPath = "engineering_records";
		parent::__construct();
	}

	/**
	 * 查询页面
	 */
	function c_toSearchList() {
		$this->assign('projectId', isset($_GET['projectId']) ? $_GET['projectId'] : exit('can\'t find project id'));
		$this->view('list');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_searchJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->sort = "c.thisYear, c.thisMonth";
		$rows = $service->list_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);

        // 获取现场决算里面的机票部分
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
                    'from' => '分摊系统'
                );
                $fieldCount = bcadd($fieldCount, $v['feeField'], 2);
            }
        }

		if (!empty($rows)) {
			//加载项目合计
			$objArr = $service->listBySqlId('select_count');
			$rsArr = array();
			if ($objArr[0]['fee'] || $fieldCount) {
				$rsArr['fee'] = bcadd($objArr[0]['fee'], $fieldCount, 2);
				$rsArr['costType'] = '合 计';
				$rsArr['id'] = 'noId';
                $rsArr['from'] = '';
			}
			$rows[] = $rsArr;
		}
		echo util_jsonUtil::encode($rows);
	}
}