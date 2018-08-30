<?php

/**
 * @author Show
 * @Date 2012��10��11�� ������ 10:01:33
 * @version 1.0
 * @description:��������������Ʋ�
 */
class controller_finance_expense_exsummary extends controller_base_action
{

    function __construct()
    {
        $this->objName = "exsummary";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * ��ת���������������б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��ת������������������ҳ��
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * ��ת���༭������������ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴������������ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getInfo_d($_GET['id']);
        $sourceType = $this->service->getSourceType($_GET['id']);
        $this->assign("sourceType",$sourceType);

        $CostMan = isset($obj['CostMan'])? $obj['CostMan'] : '';
        $CostManName = isset($obj['CostManName'])? $obj['CostManName'] : '';
        $personnelDao = new model_hr_personnel_personnel();
        $CostManInfo = $personnelDao->getPersonInfoByUserId($CostMan);
        $billBaseInfo = $this->service->getBillBaseInfo($obj['BillNo']);
        // echo "<pre>";print_r($CostManInfo);print_r($billBaseInfo);exit();
        $isDiffBillInfo = 0;
        if(isset($billBaseInfo['payeeIsEmpty']) && $billBaseInfo['payeeIsEmpty'] == 1){
            $payeeName = explode("(",$billBaseInfo['payeeName']);
            $payeeName = $payeeName[0];
        }else{
            $payeeName = $billBaseInfo['payeeName'];
        }
        if($CostManName != $payeeName ||
            $CostManInfo['oftenAccount'] != $billBaseInfo['account']){
            $isDiffBillInfo = 1;
        }
        $isDiffBillInfoMsg = ($isDiffBillInfo == 1)? " *����Ϊ������" : "";
        $this->assign("isDiffBillInfoMsg",$isDiffBillInfoMsg);

        if ($obj['isNew'] == 1) {
            $this->assignFunc($obj);
            if ($obj['Amount'] != $obj['CheckAmount']) {
                $this->assign('Amount', $obj['CheckAmount']);
            }

            // �鿴�Ƿ���ڹ����⳵�ǼǷ������¼��
            $carRentalRelativeId = '';
            $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where expenseId = {$obj['ID']}";
            $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
            if($carRentalRelativeTmpObj){
                $carRentalRelativeId = $carRentalRelativeTmpObj['id'];
            }
            $this->assign('tempExpenseId',$carRentalRelativeId);

            //��Ⱦ��������
            $this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

            //�������{file}
            $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

            $approvalPid = (isset($obj['allregisterid']) && $obj['allregisterid'] != 0)? $obj['allregisterid'] : $_GET['id'];
            $approvalType = (isset($obj['allregisterid']) && $obj['allregisterid'] != 0)? "oa_outsourcing_allregister" : "cost_summary_list";
            $this->assign("approvalPid",$approvalPid);
            $this->assign("approvalType",$approvalType);

            $this->view('view');
        } else {
            succ_show('general/costmanage/reim/summary_detail.php?status=���ɸ���&BillNo=' . $obj['BillNo']);
        }
    }

    /**
     * �����鿴
     */
    function c_toAudit()
    {
        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
        $editLimit = isset($limitArr['����ҳ��༭Ȩ��'])? $limitArr['����ҳ��༭Ȩ��'] : '';
        $addCostLimit = isset($limitArr['����������Ȩ��'])? $limitArr['����������Ȩ��'] : '';
        $this->assign("addCostLimit",$addCostLimit);
        if($editLimit == 1){// �ж��Ƿ����޸ķ�Ʊ��Ȩ��,�еĻ���ʾ�ɱ༭����ҳ��
            $this->c_toAuditEdit();
        }else{
            $this->permCheck(); //��ȫУ��
            $obj = $this->service->getInfo_d($_GET['id']);

            $CostMan = isset($obj['CostMan'])? $obj['CostMan'] : '';
            $CostManName = isset($obj['CostManName'])? $obj['CostManName'] : '';
            $personnelDao = new model_hr_personnel_personnel();
            $CostManInfo = $personnelDao->getPersonInfoByUserId($CostMan);
            $billBaseInfo = $this->service->getBillBaseInfo($obj['BillNo']);
            // echo "<pre>";print_r($CostManInfo);print_r($billBaseInfo);exit();
            $isDiffBillInfo = 0;
            if(isset($billBaseInfo['payeeIsEmpty']) && $billBaseInfo['payeeIsEmpty'] == 1){
                $payeeName = explode("(",$billBaseInfo['payeeName']);
                $payeeName = $payeeName[0];
            }else{
                $payeeName = $billBaseInfo['payeeName'];
            }
            if($CostManName != $payeeName ||
                $CostManInfo['oftenAccount'] != $billBaseInfo['account']){
                $isDiffBillInfo = 1;
            }
            $isDiffBillInfoMsg = ($isDiffBillInfo == 1)? " *����Ϊ������" : "";
            $this->assign("isDiffBillInfoMsg",$isDiffBillInfoMsg);

            $sourceType = $this->service->getSourceType($_GET['id']);
            $this->assign("sourceType",$sourceType);

// 		$areaId = $obj['salesAreaId'];	//���ù�������
// 		$this->showBudget($areaId);  //��ʾ�����Ԥ��
            //ֻ����ǰ���ۺ������ʾͳ��ֵ
            if ($obj['DetailType'] == '4' || $obj['DetailType'] == '5') {
                $this->showStatistic(array('userId' => $obj['feeManId'], 'userName' => $obj['feeMan'],
                    'areaId' => $obj['salesAreaId'], 'areaName' => $obj['salesArea'], 'costBelongerId' => $obj['CostBelongerId']));
            }
            if ($obj['isNew'] == 1) {
                foreach ($obj as $key => $val) {
                    $this->assign($key, $val);
                }

                // �鿴�Ƿ���ڹ����⳵�ǼǷ������¼��
                $carRentalRelativeId = '';
                $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where expenseId = {$obj['ID']}";
                $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
                if($carRentalRelativeTmpObj){
                    $carRentalRelativeId = $carRentalRelativeTmpObj['id'];
                }
                $this->assign('tempExpenseId',$carRentalRelativeId);

                //��Ⱦ��������
                $this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

                //�������{file}
                $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

                $this->assign("showProjectSee",($obj['DetailType'] == 1)? "style='display:none';" : "");// ���ŷ��õĲ���ʾ ���鿴��Ŀ������ ����

                $this->view('audit');
            } else {
                succ_show('general/costmanage/reim/summary_detail.php?status=���ɸ���&BillNo=' . $obj['BillNo']);
            }
        }
    }

    /**
     * �����鿴 - �ɱ༭
     */
    function c_toAuditEdit()
    {
        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
        $editLimit = isset($limitArr['����ҳ��༭Ȩ��'])? $limitArr['����ҳ��༭Ȩ��'] : '';
        $addCostLimit = isset($limitArr['����������Ȩ��'])? $limitArr['����������Ȩ��'] : '';
        $this->assign("addCostLimit",$addCostLimit);
        if($editLimit != 1){// �ж��Ƿ����޸ķ�Ʊ��Ȩ��,�еĻ���ʾ�ɱ༭����ҳ��
            $this->c_toAudit();
        }else{
            $this->permCheck(); //��ȫУ��
            $obj = $this->service->getInfoEdit_d($_GET['id']);

            $CostMan = isset($obj['CostMan'])? $obj['CostMan'] : '';
            $CostManName = isset($obj['CostManName'])? $obj['CostManName'] : '';
            $personnelDao = new model_hr_personnel_personnel();
            $CostManInfo = $personnelDao->getPersonInfoByUserId($CostMan);
            $billBaseInfo = $this->service->getBillBaseInfo($obj['BillNo']);
            // echo "<pre>";print_r($CostManInfo);print_r($billBaseInfo);exit();
            $isDiffBillInfo = 0;
            if(isset($billBaseInfo['payeeIsEmpty']) && $billBaseInfo['payeeIsEmpty'] == 1){
                $payeeName = explode("(",$billBaseInfo['payeeName']);
                $payeeName = $payeeName[0];
            }else{
                $payeeName = $billBaseInfo['payeeName'];
            }
            if($CostManName != $payeeName ||
                $CostManInfo['oftenAccount'] != $billBaseInfo['account']){
                $isDiffBillInfo = 1;
            }
            $isDiffBillInfoMsg = ($isDiffBillInfo == 1)? " *����Ϊ������" : "";
            $this->assign("isDiffBillInfoMsg",$isDiffBillInfoMsg);

            $sourceType = $this->service->getSourceType($_GET['id']);
            $this->assign("sourceType",$sourceType);

            if ($obj) {
// 			$areaId = $obj['salesAreaId'];	//���ù�������
// 			$this->showBudget($areaId);  //��ʾ�����Ԥ��
                foreach ($obj as $key => $val) {
                    $this->assign($key, $val);
                }

                // �鿴�Ƿ���ڹ����⳵�ǼǷ������¼��
                $carRentalRelativeId = '';
                $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where expenseId = {$obj['ID']}";
                $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
                if($carRentalRelativeTmpObj){
                    $carRentalRelativeId = $carRentalRelativeTmpObj['id'];
                }
                $this->assign('tempExpenseId',$carRentalRelativeId);

                //��Ⱦ��������
                $this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

                //�������{file}
                $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

                $this->assign("showProjectSee",($obj['DetailType'] == 1)? "style='display:none';" : "");// ���ŷ��õĲ���ʾ ���鿴��Ŀ������ ����

                $this->view('auditedit');
            } else {
                echo 'û���ҵ���ر�������';
            }
        }
    }

    /**
     * ��������ID��ȡ����Ԥ������ʾ��ҳ��
     */
    function showBudget($areaId)
    {
        if (isset($areaId)) {
            $budgetDetailDao = new model_finance_budget_budgetDetail();
            $budgetDetail = $budgetDetailDao->getByParam(2015, $areaId);

            $currentSeason = $budgetDetailDao->currentSeason();
            $totalBudget = $budgetDetail['totalBudget'];  //����Ԥ��
            if ($totalBudget > 0) { // �������Ԥ�������������ʱ����ʾԤ��
                $currentBudget = $budgetDetail[$currentSeason . 'Budget']; //��ǰ����Ԥ��
                $yearFinal = $budgetDetail['final'];    //�������
                $seasonFinal = $budgetDetail[$currentSeason . 'Final']; //��ǰ���Ⱦ���
                $seasonPercent = $seasonFinal / $currentBudget;  //��ǰ���Ⱦ���ռԤ�����
                $yearPercent = $yearFinal / $totalBudget;  //��ǰ���Ⱦ���ռԤ�����

                //Ԥ����ɫ�ж�
                if ($seasonPercent > 1 || $currentBudget <= 0) {
                    $seasonColor = 'red';
                } else if ($seasonPercent > 0.9) {
                    $seasonColor = 'purple';
                } else if ($seasonPercent > 0.7) {
                    $seasonColor = 'yellow';
                }
                if ($yearPercent > 1 || $totalBudget <= 0) {
                    $yearColor = 'red';
                } else if ($yearPercent > 0.9) {
                    $yearColor = 'purple';
                } else if ($yearPercent > 0.7) {
                    $yearColor = 'yellow';
                }
                $budgetHtml = "<td width='15%'>" . $budgetDetail['area'] . "</td><td width='15%'>" . $budgetDetail[$currentSeason . 'Budget'] . "</td><td width='15%'>" . $budgetDetail['totalBudget'] . "</td>" .
                    "<td width='15%' style='color:$seasonColor;'>$seasonFinal</td><td width='15%' style='color:$yearColor;'>$yearFinal</td>";

                $this->assign('budgetDetail', $budgetHtml);
            }
        }
    }

    /**
     * ���ݱ�����ɾ�����ܱ�
     */
    function c_delByBillNo()
    {
        try {
            $this->service->delByBillNo_d($_POST ['BillNo']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ������ɺ���÷���
     */
    function c_dealAfterAudit()
    {
        $spid = $_GET['spid'];
        $this->service->dealAfterAudit_d($spid);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * ��ȡͳ��ֵ����ʾ��ҳ��
     * @param $obj
     */
    function showStatistic($obj)
    {
        //��ȡͳ��ֵ
        $rs = $this->service->getStatistic($obj['userId'], $obj['areaId']);
        $userFeeArr = $rs['userFee'];//���˷���
        $areaFeeArr = $rs['areaFee'];//�������
        //������óе��˷ֹܲ�ͬ������Ҫ�ֿ��������ͳ�ƣ�������ʱ�����۸�����ֻ�ܲ鿴��������ķ���ͳ�ƣ�
// 		if($obj['costBelongerId'] == $_SESSION['USER_ID']){
// 			//��ȡ���۸����˹�Ͻ������
// 			$salepersonDao = new model_system_saleperson_saleperson();
// 			$salesAreaArr = $salepersonDao->getSaleArea($_SESSION['USER_ID']);
// 			if(empty($salesAreaArr)){
// 				$userFeeArr = '';
// 			}else{
// 				$idArr = array();
// 				foreach ($salesAreaArr as $v){
// 					array_push($idArr, $v['salesAreaId']);
// 				}
// 				foreach ($userFeeArr as $k => $v){
// 					if(!in_array($k, $idArr)){
// 						unset($userFeeArr[$k]);
// 					}
// 				}
// 			}
// 		}
        $budgetHtml = "";
        //���˷���ͳ��
        if (!empty($userFeeArr)) {
// 			$regionDao = new model_system_region_region();
            foreach ($userFeeArr as $k => $v) {
// 				$rs = $regionDao->find(array('id' => $k),null,'areaName');
// 				$areaName = $rs['areaName'];
                $userFee = empty($v['fee']) ? 0 : $v['fee'];//�������۷���
                $userConMoney = empty($v['contract']) ? 0 : $v['contract'];//������ǩ��ͬ��
                $userRate = round($userFee / ($userConMoney * 0.0002), 2);//���˷������ͬͬ����
                $userColor = $userRate >= 100 ? 'red' : 'green';//������ʾ��ɫ
                $userColor = ($userFee <= 0 || $userConMoney <= 0) ? 'red' : $userColor;//ֻ�з��ö�û�к�ͬ����ǩ��ͬ��Ϊ0������ֻ�к�ͬ��û�з���ʱ����� PMS2173
                $budgetHtml .= <<<EOT
					<tr>
						<td width="15%">$obj[userName]</td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('$obj[userId]','$k','fee')" class="formatMoney">$userFee</a></td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('$obj[userId]','$k','contract')" class="formatMoney">$userConMoney</a></td>
						<td width="15%" style="color:$userColor;">$userRate%</td>
					</tr>
EOT;
            }
        }
        //�������ͳ��
        if (!empty($areaFeeArr)) {
            foreach ($areaFeeArr as $k => $v) {
                $areaFee = empty($v['fee']) ? 0 : $v['fee'];//�������۷���
                $areaConMoney = empty($v['contract']) ? 0 : $v['contract'];//������ǩ��ͬ��
                $areaRate = round($areaFee / ($areaConMoney * 0.0002), 2);//����������ͬͬ����
                $areaColor = $areaRate >= 100 ? 'red' : 'green';//������ʾ��ɫ
                $areaColor = ($areaFee <= 0 || $areaConMoney <= 0) ? 'red' : $areaColor;//ֻ�з��ö�û�к�ͬ����ǩ��ͬ��Ϊ0������ֻ�к�ͬ��û�з���ʱ����� PMS2173
                $budgetHtml .= <<<EOT
					<tr>
						<td width="15%">$obj[areaName]</td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('','$k','fee')" class="formatMoney">$areaFee</a></td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('','$k','contract')" class="formatMoney">$areaConMoney</a></td>
						<td width="15%" style="color:$areaColor;">$areaRate%</td>
					</tr>
EOT;
            }
        }

        $this->assign('statistic', $budgetHtml);
    }

    /**
     * �鿴����ͳ����ϸ
     */
    function c_showStatisticDetail()
    {
        $this->assign('userId', $_GET['userId']);
        $this->assign('areaId', $_GET['areaId']);
        $this->assign('thisYear', $_GET['thisYear']);
        $this->assign('exeDeptCode', $_GET['exeDeptCode']);
        if (isset($_GET['view_type'])) {
            $this->assign('view_type', 'view_type=' . $_GET['view_type']);
        }


        $this->view('statistictab-' . $_GET['feeType']);
    }

    /**
     * ��ȡ��ǩ��ͬ��ϸ��Ϣ
     */
    function c_getStatisticDetailForContract(){
        $storeYear = (isset($_REQUEST['ExaYear']) && $_REQUEST['ExaYear'] != '')? " and m.storeYear = '{$_REQUEST['ExaYear']}'" : "";
        $areaCode = (isset($_REQUEST['areaCode']) && $_REQUEST['areaCode'] != '')? " and m.areaCode = '{$_REQUEST['areaCode']}'" : "";
        $sql = <<<EOT
        select 
            m.id,
            c.id as contractId,
            case c.contractType  when 'HTLX-XSHT' then '���ۺ�ͬ' when 'HTLX-FWHT' then '�����ͬ' when 'HTLX-ZLHT' then '���޺�ͬ' when 'HTLX-YFHT' then '�з���ͬ' when 'HTLX-PJGH' then '�������ͬ'
            end as contType,
            case c.state  when 0 then '����' when 1 then '������' when 2 then 'ִ����' when 4 then '�����' when 3 then '�ѹر�' when 7 then '�쳣�ر�'
            end as contState,
            if(c.id is null,m.contractAllMoney,sum(m.contractMoney)) as contractAllMoney,c.contractMoney,c.contractName,m.contractCode from oa_bi_conproduct_month m
        left join oa_contract_contract c on c.id = m.contractId
        where 1=1 $areaCode $storeYear
        group by m.contractId order by m.id desc
EOT;
        $rst = $this->service->_db->getArray($sql);
        $countMoney = 0;
        foreach ($rst as $row){
            $countMoney = bcadd($countMoney,$row['contractAllMoney'],5);
        }
        $countMoney = sprintf("%.4f", $countMoney);
        $rst[] = array("contType" => "�ϼ�","contractAllMoney" => $countMoney);
        $rst = $this->sconfig->md5Rows($rst);

        //���ݼ��밲ȫ��
        echo util_jsonUtil::encode($rst);
    }

    /**
     * �鿴����ͳ������ҳ��
     */
    function c_testGetStit()
    {
        $rs = $this->service->getStatisticSpl($_GET['show_type'], $_GET['aid'], '2017');
        echo "<pre>";
        var_dump($rs);
    }

    /**
     * ����չʾ
     */
    function c_showStatisticAll()
    {
        $this->assign('show_type', isset($_GET['show_type']) ? $_GET['show_type'] : 'byGroup');
        $this->view('showStatisticAll');
    }

    /**
     * ��ȡ��������������������
     */
    function c_getFinancePeriodYear()
    {
        $sql = "select thisYear from oa_finance_accountingperiod group by thisYear order by thisYear desc";
        $obj = $this->service->_db->getArray($sql);
        $data = array();
        $data['thisYear'] = date("Y");
        $data['allYears'] = array();
        foreach ($obj as $k => $v) {
            $arr['value'] = $v['thisYear'];
            $arr['text'] = $v['thisYear'] . "��";
            array_push($data['allYears'], $arr);
        }
        echo util_jsonUtil:: encode($data);
    }

    /**
     * �鿴����ͳ��ҳ������
     */
    function c_pageStatisticAll()
    {
        $type = isset($_REQUEST['show_type']) ? $_REQUEST['show_type'] : '';// ��ʾ���ͣ�����Ա/������
        $year = isset($_REQUEST['theYear']) ? $_REQUEST['theYear'] : '';
        $SalesAreaId = isset($_REQUEST['SalesAreaId']) ? $_REQUEST['SalesAreaId'] : '';
        $isBig = isset($_REQUEST['isBigGroup']) ? $_REQUEST['isBigGroup'] : 0;
        $isAreaDetail = isset($_REQUEST['isAreaDetail']) ? $_REQUEST['isAreaDetail'] : 0;

        if ($type != '') {
            $ext_condition = '';
            $personFilter = '';
            // ҳ����������
            if ($_REQUEST['isSearchTag_'] == 'true' || $SalesAreaId) {
                $condition = $group = $order = $limit = '';
                $pageNum = $_REQUEST['page'];
                $pageSize = $_REQUEST['pageSize'];
                $startNum = ($pageNum - 1) * $pageSize;
                $limit = $SalesAreaId ? "" : "limit {$startNum},{$pageSize}";
                $arr['page'] = $pageNum;

                if ($_REQUEST['show_type'] == 'byMan') {

                } else if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != ''){
                    $dir = ($_REQUEST['dir'] != '')? $_REQUEST['dir'] : '';
                    $order = ' ORDER BY t.'.$_REQUEST['sort'].' '.$dir;
                }else {
                    $order = ' ORDER BY a.exeDeptCode, t.SalesAreaId';
                }

                switch ($_REQUEST['show_type']) {
                    case 'byMan':
                        if (!empty($_REQUEST['feeMan'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
                            $condition = "and t.feeMan like '%{$cond}%'";
                        } else if (!empty($_REQUEST['SalesArea'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
                            $condition = "and t.SalesArea like '%{$cond}%'";
                        } else if (!empty($_REQUEST['exeDeptName'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
                            $condition = "and a.exeDeptName like '%{$cond}%'";
                        } else if (!empty($_REQUEST['feeMan,exeDeptName,SalesArea'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan,exeDeptName,SalesArea']);
                            $condition = "and (a.exeDeptName like '%{$cond}%' OR t.SalesArea like '%{$cond}%' OR t.feeMan like '%{$cond}%')";
                        }

                        // �������� $SalesAreaId ��һ���������������Ҫ��һ�¹���
                        if ($SalesAreaId) {
                            $SalesAreaIdArr = explode('|', $SalesAreaId);
                            $condition .= "and t.SalesAreaId = '{$SalesAreaIdArr[0]}'";
                            if ($SalesAreaIdArr[1]) {
                                $year = $SalesAreaIdArr[1];
                            }
                            if ($SalesAreaIdArr[2]) {
                                $personFilter = util_jsonUtil::iconvUTF2GB($SalesAreaIdArr[2]);
                            }
                        }
                        $group = " group by t.feeManId ";
                        break;
                    case 'byArea':
                        $condition = "and t.SalesAreaId = '{$SalesAreaId}'";
                        break;
                    default :
                        if (!empty($_REQUEST['feeMan'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
                            $personFilter = $cond;
                            $group .= " HAVING totalContract > 0 OR totalFee > 0";
                        } else if (!empty($_REQUEST['SalesArea'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
                            $condition = "and t.SalesArea like '%{$cond}%'";
                        } else if (!empty($_REQUEST['exeDeptName'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
                            $condition = "and a.exeDeptName like '%{$cond}%'";
                        }
                        break;
                }

                // ��ȡ�û������������ID
                $regionDao = new model_system_region_region();
                $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

                // ��Ӱ��Ȩ�޹���
                $limitArrSql = "";
                if($isAreaDetail != 1){
                    if ($this->service->this_limit['���Ȩ��'] != '' && !strstr($this->service->this_limit['���Ȩ��'], ";;")) {
                        if($AreaIds == ''){
                            $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['���Ȩ��']}')>0))";
                        }else{
                            $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['���Ȩ��']}')>0) or (a.salesAreaId in ({$AreaIds})))";
                        }
                    }else if($this->service->this_limit['���Ȩ��'] == ''){
                        if($AreaIds != ''){
                            $limitArrSql = " and a.salesAreaId in ({$AreaIds})";
                        }else{
                            $ext_condition = "none";// ��û�κ�Ȩ�޲鿴����
                        }
                    }
                }

                if($ext_condition != 'none'){
                    $condition .= $limitArrSql;

                    // ������Χ������
                    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != ''){
                        $dir = ($_REQUEST['dir'] != '')? $_REQUEST['dir'] : '';
                        $bigOrder = ' ORDER BY sum(t.'.$_REQUEST['sort'].') '.$dir;
                    }
                    $bigExtSql = ($isBig == 1)? " group by T.exeDeptCode " . $bigOrder : " ";

                    $_SESSION['mainCondition'] = $condition;
                    $_SESSION['mainOrder'] = $order;

                    $ext_condition = $condition . " " . $group . " " . $order;
                    $obj = $this->service->getStatisticSpl($type, $ext_condition, $year, $personFilter, $isBig, $bigExtSql);
                    //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
                    $arr['totalSize'] = $this->service->count ? $this->service->count : ($obj ? count($obj) : 0);
                    $ext_condition = $condition . " " . $group . " " . $order;
                    $bigExtSql = ($isBig == 1)? " group by T.exeDeptCode " . $bigOrder." ".$limit : " ";
                }
            }

            if($ext_condition != 'none'){
                $obj = $this->service->getStatisticSpl($type, $ext_condition, $year, $personFilter, $isBig, $bigExtSql);
                $personAppend = isset($personFilter) ? "|" . $personFilter : "";
                $totalFee = 0;
                $totalContract = 0;
                foreach ($obj as $k => $v) {
                    if(!isset($v['id'])){$obj[$k]['id'] = ($k+1);}
                    $obj[$k]['rate'] = round($v['totalFee'] / ($v['totalContract'] * 0.0002), 2);//ͬ����
                    $obj[$k]['detailId'] = $v['detailId'] . $personAppend;// ƴ����Ա����
                    $totalFee = bcadd($totalFee, $v['totalFee'], 2);
                    $totalContract = bcadd($totalContract, $v['totalContract'], 2);
                }
                if ($type != 'byMan') {
                    $obj[] = array(
                        'id' => 'noId',
                        'detailId' => 'noId',
                        'moduleName' => '�ϼ�',
                        'totalFee' => $totalFee,
                        'totalContract' => $totalContract,
                        'rate' => round($totalFee / ($totalContract * 0.0002), 2)
                    );
                }
            }else{
                $obj = "";
            }
            $rows = $obj;
            $arr['totalSize'] = ($obj && $obj != "") ? $arr['totalSize'] : '0';
            $arr['collection'] = $rows;
            echo util_jsonUtil:: encode($arr);
        } else {
            echo "ȱ���ֶ�{show_type}";
        }
    }

    /**
     * ��ȡ�����������µ���������ķ�����Ϣ
     */
    function c_getSubStatisticByExeDep()
    {
        $exeDepFilter = isset($_REQUEST['exeDepFilter']) ? $_REQUEST['exeDepFilter'] : '';
        $exeDepFilterArr = explode("|",$exeDepFilter);
        $exeDepCode = isset($exeDepFilterArr[0])? $exeDepFilterArr[0] : '';
        $year = isset($exeDepFilterArr[1])? $exeDepFilterArr[1] : '';

        $condition = isset($_SESSION['mainCondition'])? $_SESSION['mainCondition'] : '';

        if($condition == ''){
            // ��ȡ�û������������ID
            $regionDao = new model_system_region_region();
            $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

            // ���Ȩ�޹���
            $limitArrSql = "";
            if ($this->service->this_limit['���Ȩ��'] != '' && !strstr($this->service->this_limit['���Ȩ��'], ";;")) {
                $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['���Ȩ��']}')>0) or (a.salesAreaId in ({$AreaIds})))";
            }else if($this->service->this_limit['���Ȩ��'] == ''){
                $limitArrSql = " and a.salesAreaId in ({$AreaIds})";
            }

            $condition = $limitArrSql;
        }

        $condition .= ($exeDepCode != '')? " and a.exeDeptCode = '{$exeDepCode}'" : "";

        $order = isset($_SESSION['mainOrder'])? $_SESSION['mainOrder'] : ' ORDER BY a.exeDeptCode';

        $group = " group by t.SalesArea";

        $ext_condition = $condition . " " . $group . " " . $order;
        $obj = $this->service->getStatisticSpl("byGroup", $ext_condition, $year);
        $arr['totalSize'] = $this->service->count ? $this->service->count : ($obj ? count($obj) : 0);

        foreach ($obj as $k => $v) {
            $obj[$k]['rate'] = round($v['totalFee'] / ($v['totalContract'] * 0.0002), 2);//ͬ����
        }

        $rows = $obj;
        $arr['totalSize'] = ($obj) ? $arr['totalSize'] : '0';
        $arr['collection'] = $rows;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * ��������ͳ�Ƶ����������ϸ��Ա
     */
    function c_toAreaDetail()
    {
        $this->assign("areaId",isset($_GET['areaId'])? $_GET['areaId'] : '');
        $this->assign("thisYear",isset($_GET['thisYear'])? $_GET['thisYear'] : '');
        $this->view('showAreaDetail');
    }


    /* ======================== ���������µ��� ======================== */
    /**
     * ���ݴ����������Լ���ݶ������������������ͳ�ƽ��
     * @param $exeDepCodes
     * @param $year
     * @return mixed
     */
    function c_getSubStatisticByExeDeps($exeDepCodes,$year){
        // ��ȡ�û������������ID
        $regionDao = new model_system_region_region();
        $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

        // ���Ȩ�޹���
        $limitArrSql = "";
        if ($this->service->this_limit['���Ȩ��'] != '' && !strstr($this->service->this_limit['���Ȩ��'], ";;")) {
            $AreaIdsLimit = ($AreaIds == '')? '' : "or (a.salesAreaId in ({$AreaIds}))";
            $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['���Ȩ��']}')>0) {$AreaIdsLimit})";
        }else if($this->service->this_limit['���Ȩ��'] == ''){
            $limitArrSql = ($AreaIds == '')? "" : " and a.salesAreaId in ({$AreaIds})";
        }

        $condition = $limitArrSql;

        $condition .= ($exeDepCodes != '')? " and a.exeDeptCode in ({$exeDepCodes})" : "";

        $order = ' ORDER BY re.moduleName,a.exeDeptCode';

        $group = " group by t.SalesArea,a.exeDeptCode";

        $ext_condition = $condition . " " . $group . " " . $order;
        $obj = $this->service->getStatisticSpl("byGroup", $ext_condition, $year);
        $salesAreaIds = array();
        $year = "";
        $totalFee = $totalContract = 0;
        foreach ($obj as $k => $v) {
            $rate = round(bcdiv($v['totalFee'],($v['totalContract'] * 0.0002), 3),2);//ͬ����
            $obj[$k]['rate'] = ($rate == 0)? '0.00' : $rate;
            $totalFee = bcadd($totalFee, $v['totalFee'], 2);
            $totalContract = bcadd($totalContract, $v['totalContract'], 2);
            if(!in_array($v['SalesAreaId'],$salesAreaIds)){
                $salesAreaIds[] = $v['SalesAreaId'];
            }
            $year = ($v['thisYear'] != '')? $v['thisYear'] : $year;
        }

        $totalRate = bcdiv($totalFee,($totalContract * 0.0002), 2);
        $obj[] = array(
            'id' => 'noId',
            'moduleName' => '�ϼ�',
            'totalFee' => $totalFee,
            'totalContract' => $totalContract,
            'rate' => ($totalRate == 0)? '0.00' : $totalRate
        );

        $backData['obj'] = $obj;
        $backData['salesAreaIds'] = implode($salesAreaIds,",");
        $backData['year'] = $year;
//        echo"<prE>";print_r();exit();
        return $backData;
    }

    /**
     * ���ݴ�������µ����������Լ���ݶ�������������Ա����ͳ�ƽ��
     * @param $salesAreaIds
     * @param $year
     * @return array
     */
    function c_getSubStatisticBySalesareaIds($salesAreaIds,$year){
        if (!empty($_REQUEST['feeMan'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
            $condition = "and t.feeMan like '%{$cond}%'";
        } else if (!empty($_REQUEST['SalesArea'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
            $condition = "and t.SalesArea like '%{$cond}%'";
        } else if (!empty($_REQUEST['exeDeptName'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
            $condition = "and a.exeDeptName like '%{$cond}%'";
        } else if (!empty($_REQUEST['feeMan,exeDeptName,SalesArea'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan,exeDeptName,SalesArea']);
            $condition = "and (a.exeDeptName like '%{$cond}%' OR t.SalesArea like '%{$cond}%' OR t.feeMan like '%{$cond}%')";
        }

        // �������� $SalesAreaId ��һ���������������Ҫ��һ�¹���
        if ($salesAreaIds != '') {
            $condition .= "and t.SalesAreaId in ({$salesAreaIds})";

            // ��ȡ�û������������ID
            $regionDao = new model_system_region_region();
            $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

            // ��Ӱ��Ȩ�޹���
            $limitArrSql = "";
            if ($this->service->this_limit['���Ȩ��'] != '' && !strstr($this->service->this_limit['���Ȩ��'], ";;")) {
                if($AreaIds == ''){
                    $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['���Ȩ��']}')>0))";
                }else{
                    $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['���Ȩ��']}')>0) or (a.salesAreaId in ({$AreaIds})))";
                }
            }else if($this->service->this_limit['���Ȩ��'] == ''){
                if($AreaIds != ''){
                    $limitArrSql = " and a.salesAreaId in ({$AreaIds})";
                }else{
                    $ext_condition = "none";// ��û�κ�Ȩ�޲鿴����
                }
            }
        }
        $group = " group by re.moduleName,a.exeDeptCode,t.SalesAreaId,t.feeManId ";
        $order = ' order by re.moduleName,a.exeDeptCode,t.SalesAreaId,t.feeManId ';

        if($ext_condition != 'none'){
            $condition .= $limitArrSql;

            $ext_condition = $condition . " " . $group . " " . $order;
            $obj = $this->service->getStatisticSpl('byMan', $ext_condition, $year);
        }

        $totalFee = $totalContract = 0;
        foreach ($obj as $k => $v) {
            $rate = round(bcdiv($v['totalFee'],($v['totalContract'] * 0.0002), 3),2);//ͬ����
            $obj[$k]['rate'] = ($rate == 0)? '0.00' : $rate;
            $obj[$k]['totalFee'] = bcadd($v['totalFee'], 0, 2);
            $obj[$k]['totalContract'] = bcadd($v['totalContract'], 0, 2);
            $totalFee = bcadd($totalFee, $v['totalFee'], 2);
            $totalContract = bcadd($totalContract, $v['totalContract'], 2);
        }

        $totalRate = bcdiv($totalFee,($totalContract * 0.0002), 2);
        $obj[] = array(
            'id' => 'noId',
            'moduleName' => '�ϼ�',
            'totalFee' => $totalFee,
            'totalContract' => $totalContract,
            'rate' => ($totalRate == 0)? '0.00' : $totalRate
        );
//        echo "<pre>";print_r();exit();
        return $obj;
    }

    /**
     * ����Ƿ���ڵ���Ȩ��
     */
    function c_chkExportLimit() {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($sysLimit['����Ȩ��']) && $sysLimit['����Ȩ��'] == 1){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * ���ݵ���
     */
    function c_export() {
        set_time_limit(0); // ���ò���ʱ

        if(isset($_SESSION['exsummary_mainSql'])){
            $sheet1Sql = $_SESSION['exsummary_mainSql'];
            $sheet1Sql = ($sheet1Sql != "")? $sheet1Sql." ORDER BY T.module" : "";
            $sheet1Arr = $this->service->_db->getArray($sheet1Sql);
            $totalFee = $totalContract = 0;
            $exeDeptCodes = $year = "";
            foreach ($sheet1Arr as $k => $v) {
                $rate = round(bcdiv($v['totalFee'],($v['totalContract'] * 0.0002), 3),2);//ͬ����
                if(!isset($v['id'])){$sheet1Arr[$k]['id'] = ($k+1);}
                $sheet1Arr[$k]['rate'] = ($rate == 0)? '0.00' : $rate;
                $totalFee = bcadd($totalFee, $v['totalFee'], 2);
                $totalContract = bcadd($totalContract, $v['totalContract'], 2);
                $exeDeptCodes .= ($k == 0)? "'{$v['exeDeptCode']}'" : ",'{$v['exeDeptCode']}'";
                $year = ($v['thisYear'] != '')? $v['thisYear'] : $year;
            }

            $totalRate = bcdiv($totalFee,($totalContract * 0.0002), 2);
            $sheet1Arr[] = array(
                'id' => 'noId',
                'moduleName' => '�ϼ�',
                'totalFee' => $totalFee,
                'totalContract' => $totalContract,
                'rate' => ($totalRate == 0)? '0.00' : $totalRate
            );

            $byExeDeptArr = $this->c_getSubStatisticByExeDeps($exeDeptCodes,$year);
            $bySalesAreaArr = ($byExeDeptArr['salesAreaIds'] != '')? $this->c_getSubStatisticBySalesareaIds($byExeDeptArr['salesAreaIds'],$byExeDeptArr['year']) : array();

            $tableDataArr = array(
                "sheet1" => $sheet1Arr,
                "sheet2" => $byExeDeptArr['obj'],
                "sheet3" => $bySalesAreaArr,
            );
            //echo "<pre>";print_r($tableDataArr);exit();

            $tableThArr = array(
                "sheet1" => array('moduleName' => '���', 'exeDeptName' => '��������',
                    'totalFee' => '�������۷����ۼ�', 'totalContract' => '������ǩ��ͬ��', 'rate' => '�������ͬͬ����'),
                "sheet2" => array('moduleName' => '���', 'exeDeptName' => '��������','SalesArea' => '��������',
                    'totalFee' => '�������۷����ۼ�', 'totalContract' => '������ǩ��ͬ��', 'rate' => '�������ͬͬ����'),
                "sheet3" => array('moduleName' => '���', 'exeDeptName' => '��������','SalesArea' => '��������', 'feeMan' => '������Ա',
                    'totalFee' => '�������۷����ۼ�', 'totalContract' => '������ǩ��ͬ��', 'rate' => '�������ͬͬ����'),
            );

            $sheetName = array(
                "sheet1" => "��1-����",
                "sheet2" => "��2-����",
                "sheet3" => "��3-��Ա",
            );

            model_finance_common_financeExcelUtil::export2ExcelUtilForSummary($tableThArr,$tableDataArr,$sheetName,'���۷���ͳ��',array("rate"),array('totalFee','totalContract'));
        }
    }

    /* ======================== ���������µ��� ======================== */

    function c_exportOld() {
        set_time_limit(0); // ���ò���ʱ

        // ������ȡ
        $personFilter = '';
        $condition = $group = $limit = '';
        if (!empty($_REQUEST['feeMan'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
            $personFilter = $cond;
            $group .= " HAVING totalContract > 0 OR totalFee > 0";
        } else if (!empty($_REQUEST['SalesArea'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
            $condition = "and t.SalesArea like CONCAT('%{$cond}%')";
        } else if (!empty($_REQUEST['exeDeptName'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
            $condition = "and a.exeDeptName like CONCAT('%{$cond}%')";
        }
        $order = ' ORDER BY a.exeDeptName, t.SalesArea, t.feeMan';
        $ext_condition = $condition . " " . $group . " " . $order;

        $rows = $this->service->getStatisticSpl('byMan', $ext_condition, $_REQUEST['theYear'], $personFilter);
        foreach ($rows as $k => $v) {
            $rows[$k]['rate'] = $v['totalContract'] > 0 && $v['totalFee'] > 0 ?
                round($v['totalFee'] / ($v['totalContract'] * 0.0002), 2) : '0.00';//ͬ����
        }

        if ($rows) {
            model_finance_common_financeExcelUtil::export2ExcelUtil(array(
                'exeDeptName' => '��������', 'SalesArea' => '��������', 'feeMan' => '������Ա',
                'totalFee' => '�������۷����ۼ�', 'totalContract' => '������ǩ��ͬ��', 'rate' => '�������ͬͬ����'
            ), $rows, '���۷���ͳ��', array('rate'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

	/**
	 * ǰ�˲�ѯҳ��򵥰�ťȨ��
	 */
    function c_chkPrintLimit(){
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($sysLimit['ҳ���Ȩ��']) && $sysLimit['ҳ���Ȩ��'] == 1){
            echo 1;
        }else{
            echo 0;
        }
    }
}