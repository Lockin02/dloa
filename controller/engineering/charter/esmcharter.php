<?php

/**
 * @author Show
 * @Date 2011年11月26日 星期六 17:00:10
 * @version 1.0
 * @description:项目章程(oa_esm_charter)控制层
 */
class controller_engineering_charter_esmcharter extends controller_base_action
{

	function __construct() {
		$this->objName = "esmcharter";
		$this->objPath = "engineering_charter";
		parent::__construct();
	}

	/**
	 * 重写toAdd
	 */
	function c_toAdd() {
		$obj = $this->service->getObjInfo_d($_GET);
		$this->assignFunc($obj);

        $esmDao = new model_engineering_project_esmproject();

		// 板块编码确定
		if ($obj['module']) {
			$moduleDatadict = $this->getDatadicts(array('HTBK'));
			$moduleDatadict = $moduleDatadict['HTBK'];
			$moduleCode = "";
			foreach ($moduleDatadict as $v) {
				if ($v['dataCode'] == $obj['module']) {
					$moduleCode = $v['expand1'];
					break;
				}
			}
			$this->assign('moduleCode', $moduleCode);
		}

		$this->showDatadicts(array('category' => 'XMLB'), null, true);
        $this->showDatadicts(array('incomeType' => 'SRQRFS'), null, true);
		// 执行部门
		if ($obj['exeDeptStr']) {// 执行部门特殊处理,如果合同里面包执行部门，那么只能使用已有执行部门
			$availableProductLine = explode(',', $obj['exeDeptStr']); // 从合同获取的可用的执行部门
			$productLineDict = $this->getDatadicts(array('GCSCX'));
			$productLineDict = $productLineDict['GCSCX'];
			$productLineUse = array();
			foreach ($productLineDict as $k => $v) {
				if (!in_array($v['dataCode'], $availableProductLine))
					unset($productLineDict[$k]);
				else
					array_push($productLineUse, $v['dataCode']);
			}
			$str = '';
			foreach ($productLineDict as $k => $v) {
				$eStr = 'e1="' . $v ['expand1'] . '" e2="' . $v ['expand2'] . '" e3="' . $v ['expand3'] . '" e4="' .
					$v ['expand4'] . '" e5="' . $v ['expand5'] . '"';
				$str .= '<option ' . $eStr . ' value="' . $v ['dataCode'] . '" title="' . $v ['remark'] . '">';
				$str .= $v ['dataName'];
				$str .= '</option>';
			}
			$this->assign('productLineUse', implode(',', $productLineUse));
			$this->assign('productLine', $str);
		} else {
			$this->showDatadicts(array('productLine' => 'GCSCX'));
		}
		//产品线
        $defaultProductLine = "";
		if ($obj['newProLineStr']) {// 产线特殊处理,如果合同里面包含产线，那么只能使用已有产线
			$availableNewProLine = explode(',', $obj['newProLineStr']); // 从合同获取的可用的产线
			$newProLineDict = $this->getDatadicts(array('HTCPX'));
			$newProLineDict = $newProLineDict['HTCPX'];
			$newProLineUse = array();
			foreach ($newProLineDict as $k => $v) {
				if (!in_array($v['dataCode'], $availableNewProLine))
					unset($newProLineDict[$k]);
				else
					array_push($newProLineUse, $v['dataCode']);
			}
			$str = '';
			foreach ($newProLineDict as $k => $v) {
                $defaultProductLine = ($defaultProductLine == "")? $v ['dataCode'] : $defaultProductLine;
				$eStr = 'e1="' . $v ['expand1'] . '" e2="' . $v ['expand2'] . '" e3="' . $v ['expand3'] . '" e4="' .
					$v ['expand4'] . '" e5="' . $v ['expand5'] . '"';
				$str .= '<option ' . $eStr . ' value="' . $v ['dataCode'] . '" title="' . $v ['remark'] . '">';
				$str .= $v ['dataName'];
				$str .= '</option>';
			}
			$this->assign('newProLineName', implode(',', $newProLineUse));
			$this->assign('newProLine', $str);
		} else {
			$this->showDatadicts(array ('newProLine' => 'HTCPX'));
		}

        // 概算占比(%)
        $estimatesRate = '';
        if(isset($_GET['contractId']) && isset($obj['contractType'])){
            $estimatesRate = 0;
            $infoArr = $esmDao->findAll(array("contractId" => $_GET['contractId'],"contractType" => $obj['contractType']),null,"id,estimatesRate");
            if($infoArr){
                foreach ($infoArr as $arr){
                    $estimatesRate = bcadd($estimatesRate,$arr['estimatesRate'],2);
                }
            }

            $estimatesRate = bcsub(100,$estimatesRate,2);
            $estimatesRate = ($estimatesRate < 0)? 0 : $estimatesRate;
        }
        $this->assign("estimatesRate",$estimatesRate);

        // PK成本占比(%)
        $pkEstimatesRate = '';
        if(isset($_GET['contractId']) && isset($obj['contractType'])){
            $pkEstimatesRate = 0;
            $infoArr = ($defaultProductLine == "")?
                $esmDao->findAll(array("contractId" => $_GET['contractId'],"contractType" => $obj['contractType']),null,"id,pkEstimatesRate") :
                $esmDao->findAll(array("contractId" => $_GET['contractId'],"contractType" => $obj['contractType'],"newProLine" => $defaultProductLine),null,"id,pkEstimatesRate");
            foreach ($infoArr as $arr){
                $pkEstimatesRate = bcadd($pkEstimatesRate,$arr['pkEstimatesRate'],2);
            }
            $pkEstimatesRate = bcsub(100,$pkEstimatesRate,2);
            $pkEstimatesRate = ($pkEstimatesRate < 0)? 0 : $pkEstimatesRate;
        }
        // 检查是否存在未关闭的PK项目
        $chkSql = "select e.id,e.status from oa_esm_project e left join oa_trialproject_trialproject t on (t.id = e.contractId and e.contractType = 'GCXMYD-04') left join oa_contract_contract c on t.chanceId = c.chanceId where c.id = '{$_GET['contractId']}' and e.status <> 'GCXMZT03';";
        $chkResult = $this->service->_db->getArray($chkSql);
        $pkEstimatesRateTipsShow = ($chkResult && count($chkResult) >= 1)? '' : 'style="display:none"';
        $this->assign("pkEstimatesRateTipsShow",$pkEstimatesRateTipsShow);
        $this->assign("pkEstimatesRate",$pkEstimatesRate);

		$this->view('add', true);
	}

	/**
	 * 重写c_add方法，新增对象操作
	 */
	function c_add() {
		$this->checkSubmit();
		//增加章程项目
		if ($this->service->add_d($_POST[$this->objName])) {
			msg('发布成功');
		} else {
			msg('发布失败');
		}
	}
}