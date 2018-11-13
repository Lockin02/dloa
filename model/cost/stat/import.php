<?php

class model_cost_stat_import extends model_base
{

    public $db;
    public $xls;
    public $nowm;

    //*******************************构造函数***********************************

    function __construct()
    {
        parent::__construct();
        $this->db = new mysql();
    }

    function getImportData($id, $type = 1)
    {
        set_time_limit(0);
        $data = array();
        if ($id) {
            $sql = "select DISTINCT a.ID, a.HeadID,a.CostDateBegin,a.CostDateEnd,a.Place,a.RNo,u.USER_NAME,a.HeadID,l.CostMan,a.Status,b.projectName,l.ProjectNo,c.CostMoney 
from cost_detail_assistant a LEFT JOIN cost_detail_list l ON a.HeadID=l.HeadID
LEFT JOIN oa_esm_project b ON (l.ProjectNO=b.projectCode )
LEFT JOIN cost_detail_project c ON c.HeadID=a.HeadID AND c.AssID=a.ID
LEFT JOIN user u  ON u.USER_ID=l.CostMan 
where  l.ProjectNO<>'' AND l.imId='$id' AND c.imId='$id' AND a.imId='$id'
order by a.CostDateBegin";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['ProjectNo']]['ProjectNo'] = $row['ProjectNo'];
                $data[$row['ProjectNo']]['projectName'] = $row['projectName'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['CostDateBegin'] = $row['CostDateBegin'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['CostDateEnd'] = $row['CostDateEnd'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['Place'] = $row['Place'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['RNo'] = $row['RNo'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['USER_NAME'] = $row['USER_NAME'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['HeadID'] = $row['HeadID'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['CostMan'] = $row['CostMan'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['ID'] = $row['ID'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['Status'] = $row['Status'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['CostMoney'] = $row['CostMoney'];
                $data[$row['ProjectNo']]['detail'][$row['ID']]['HeadID'] = $row['HeadID'];
            }
            $str = '';
            $CostAmonts = 0;
            if ($data && is_array($data)) {
                if ($type == 1) {
                    $o = 0;
                    foreach ($data as $key => $val) {
                        $o++;
                        if ($val && is_array($val)) {
                            $str .= '<table cellspacing="0" cellpadding="1" border="0" width="100%" align="center"  class="table">
						<tr >
							<td width="50%" nowrap="nowrap" colspan="5">
								<span> <b>' . $o . '.项目编号： </b>' . $val['ProjectNo'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;<b>项目名称： </b>' . $val['projectName'] . '</td>
						</tr>
						<tr>
							<td colspan="5" >        
								<table border="0" width="100%" cellpadding="0" cellspacing="1" align="center" class="table">
									<tr class="">
										<td nowrap align="center">序号</td> 
										<td align="center" nowrap>状态</td> 
										<td align="center" nowrap>姓名</td> 
										<td align="center">开始日期</td> 
										<td align="center">结束日期</td>      
										<td align="center">地点</td>
										<td nowrap align="center">
											补助金额
										</td>
										<td nowrap align="center">
											操作
										</td>    
									</tr>';
                            $i = 0;
                            $CostMoneys = 0;
                            foreach ($val['detail'] as $ky => $vl) {
                                $i++;
                                $str .= '
									<tr >
										<td align="center">' . $i . '</td>
										<td align="center">' . $vl['Status'] . '</td>
										<td align="center">' . $vl['USER_NAME'] . '</td>
										<td align="center" nowrap="nowrap" width="10%">
											' . $vl['CostDateBegin'] . '                </td>       
										<td align="center" nowrap="nowrap" width="10%">
										   ' . $vl['CostDateEnd'] . '
										</td>      
										<td align="right"></td>  
										<td nowrap align="center" width="100px">
														' . $vl['CostMoney'] . '            
										</td>
										<td align="center"><a href="javascript:del(\'' . $vl['HeadID'] . '\')">删除</a></td>   
									</tr>
									';
                                $CostMoneys += $vl['CostMoney'];
                                $CostAmonts += $vl['CostMoney'];
                            }
                            $str .= '
									<tr>
										<td align="center"><b>合计</b></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="right"></td>
										<td align="right" nowrap="nowrap"  title=""><b>' . $CostMoneys . '</b></td>
										<td></td>
									</tr>';

                            $str .= '	
								 </table></td>
						</tr>
					</table>';


                        }

                    }
                } else {
                    $o = 0;
                    foreach ($data as $key => $val) {
                        $o++;
                        if ($val && is_array($val)) {
                            $str .= '<table cellspacing="0" cellpadding="1" border="0" width="100%" align="center"  class="table">
						<tr >
							<td width="50%" nowrap="nowrap" colspan="5">
								<span> <b>' . $o . '.项目编号： </b>' . $val['ProjectNo'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;<b>项目名称： </b>' . $val['projectName'] . '</td>
						</tr>
						<tr>
							<td colspan="5" >        
								<table border="0" width="100%" cellpadding="0" cellspacing="1" align="center" class="table">
									<tr class="">
										<td nowrap align="center">序号</td> 
										<td align="center" nowrap>状态</td> 
										<td align="center" nowrap>姓名</td> 
										<td align="center">开始日期</td> 
										<td align="center">结束日期</td>      
										<td align="center">地点</td>
										<td nowrap align="center">
											补助金额
										</td>
										    
									</tr>';
                            $i = 0;
                            $CostMoneys = 0;
                            foreach ($val['detail'] as $ky => $vl) {
                                $i++;
                                $str .= '
									<tr >
										<td align="center">' . $i . '</td>
										<td align="center">' . $vl['Status'] . '</td>
										<td align="center">' . $vl['USER_NAME'] . '</td>
										<td align="center" nowrap="nowrap" width="10%">
											' . $vl['CostDateBegin'] . '                </td>       
										<td align="center" nowrap="nowrap" width="10%">
										   ' . $vl['CostDateEnd'] . '
										</td>      
										<td align="right"></td>  
										<td nowrap align="center" width="100px">
														' . $vl['CostMoney'] . '            
										</td>
										 
									</tr>
									';
                                $CostMoneys += $vl['CostMoney'];
                                $CostAmonts += $vl['CostMoney'];
                            }
                            $str .= '
									<tr>
										<td align="center"><b>合计</b></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="right"></td>
										<td align="right" nowrap="nowrap"colspan="4"  title=""><b>' . $CostMoneys . '</b></td>
										
									</tr>';

                            $str .= '	
								 </table></td>
						</tr>
					</table>';
                        }

                    }

                }
                $str .= '<table cellspacing="0" cellpadding="1" border="0" width="100%" align="center"  class="table">
		  			<tr >
						<td width="20%" nowrap="nowrap" ><b>合计</b></td>
						<td width="80%" nowrap="nowrap"  align="right"><b>' . $CostAmonts . '</b></td>
					</tr>
				</table>';
            }
        }
        return $str ? $str : "无数据";

    }


    function delImportData($id)
    {
        if ($id) {
            $this->tbl_name = 'cost_detail_assistant';
            $this->delete(array("HeadID" => $id));
            $this->tbl_name = 'cost_detail_list';
            $this->delete(array("HeadID" => $id));
            $this->tbl_name = 'cost_detail_project';
            $this->delete(array("HeadID" => $id));
            return 2;
        }

    }

    function mergerImportData($ids)
    {
        if ($ids) {
            $idI = explode(',', $ids);
            if ($idI && is_array($idI)) {
                $nId = 0;
                foreach ($idI as $key => $val) {
                    if ($key == 1) {
                        $nId = $val;
                    }
                    if ($nId && $val != $nId) {
                        $this->tbl_name = 'cost_detail_assistant';
                        $this->update(array("HeadID" => $val), array("HeadID" => $nId));
                        $this->tbl_name = 'cost_detail_list';
                        $this->update(array("HeadID" => $val), array("HeadID" => $nId));
                        $this->tbl_name = 'cost_detail_project';
                        $this->update(array("HeadID" => $val), array("HeadID" => $nId));
                        $this->tbl_name = 'cost_detail_import';
                        $this->delete(array("id" => $val));
                    }
                }
                return 2;
            }

        }

    }

    function billDocument($imd)
    {
        set_time_limit(0);
        $this->db->query("START TRANSACTION");
        try {

            $sql = " SELECT	l.ProjectNO,l.CostMan,u.dept_id AS CostDepartID,costbelongto,u.company,i.namecn,CostDateBegin,CostDateEnd
				FROM cost_detail_assistant a
				LEFT JOIN cost_detail_list l ON a.HeadID = l.HeadID  AND a.imId ='$imd'  and l.ProjectNO is not null
				LEFT JOIN USER u ON (u.user_id = l.costman)
				LEFT JOIN branch_info i ON (u.company = i.namept)
				WHERE	a.imId ='$imd' and l.ProjectNO is not null
				GROUP BY  l.ProjectNO,l.CostMan";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $billNo = $this->getMaxBillNo($row['CostDepartID']);
                if ($billNo) {
                    $days = round((strtotime($row['CostDateEnd']) - strtotime($row['CostDateBegin'])) / 3600 / 24);
                    $sqlin = "INSERT INTO cost_summary_list set BillNo='" . $billNo . "',InputMan='admin',InputDate=now(),UpdateDT=now(),CostMan='" . $row['CostMan'] . "',CostDepartID='" . $row['CostDepartID'] . "',ProjectNo='" . $row['ProjectNO'] . "'
                         ,CostDates='" . $row['CostDateBegin'] . '~' . $row['CostDateEnd'] . "',Status='部门审批',isProject=1,xm_sid=1,CostBelongTo='4',belongtocom='" . $row['company'] . "' ,costbelongcomid='" . $row['company'] . "',
                         costbelongcom='" . $row['namecn'] . "',CostBelongDeptId='" . $row['CostDepartID'] . "',CostDateBegin='" . $row['CostDateBegin'] . "',CostDateEnd='" . $row['CostDateEnd'] . "',days='" . $days . "',ExaStatus='部门审批',ExaDT=now(),
                         CostManComId='" . $row['company'] . "',daoflag='1'
				 		";
                    $this->db->query($sqlin);
                    $sqlheadid = "select GROUP_CONCAT(cast(HeadID as CHAR(255))) AS HeadIDs from cost_detail_list
						where CostMan='" . $row['CostMan'] . "' and 
						ProjectNo='" . $row['ProjectNO'] . "'  and imId ='$imd' and daoFlag=1
						group by HeadID";
                    $rowHeadID = $this->db->get_one($sqlheadid);
                    if ($rowHeadID['HeadIDs']) {
                        $sql = "update cost_detail_assistant set BillNo='" . $billNo . "' where  HeadID in(" . $rowHeadID['HeadIDs'] . ") and imId ='$imd' ";
                        $this->db->query($sql);
                        //update summary amount
                        $countSql = "select sum(CostMoney*days) as Amount from cost_detail_project d,cost_detail_assistant a where a.BillNo='" . $billNo . "' and  a.HeadID in(" . $rowHeadID['HeadIDs'] . ") AND  d.AssID=a.ID and a.HeadID=d.HeadID and a.imId ='$imd' ";
                        $rowUpdate = $this->db->get_one($countSql);
                        if ($rowUpdate['Amount']) {
                            $updateAmountSql = "update cost_summary_list set Amount='" . $rowUpdate['Amount'] . "',CheckAmount='" . $rowUpdate['Amount'] . "' where BillNo='" . $billNo . "'";
                            $this->db->query($updateAmountSql);
                        }
                    }
                    $AppUserI = $this->getAppUserI($row['ProjectNO'], $row['company']);
                    // bill flow
                    $this->buildApprovalWorkflow($billNo, $AppUserI);
                }
            }
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
        }
        $this->db->query("COMMIT");
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    // 暂时表对象
    public $tbl_name = "";

    /**
     * 补贴审批生成报销单 b1
     */
    function billDocumentNew($imd)
    {
        set_time_limit(0);
        $this->db->query("START TRANSACTION");
        try {
            // 查询出生成的补贴数据
            $sql = "SELECT l.ProjectNo,l.CostMan,u.dept_id AS CostDepartID,costbelongto,u.company,i.namecn,CostDateBegin,CostDateEnd,a.Days
				FROM cost_detail_assistant a
				LEFT JOIN cost_detail_list l ON a.HeadID = l.HeadID  AND a.imId ='$imd' and l.ProjectNo is not null
				LEFT JOIN USER u ON (u.user_id = l.costman)
				LEFT JOIN branch_info i ON (u.company = i.namept)
				WHERE a.imId ='$imd' and l.ProjectNo is not null
				GROUP BY l.ProjectNo,l.CostMan";
            $data = $this->db->getArray($sql);

            if ($data) {
                // 生成时间
                $now = date('Y-m-d H:i:s');

                foreach ($data as $v) {
                    // 单据号生成
                    $billNo = $this->getMaxBillNo($v['CostDepartID']);
                    // 日期间隔
                    $days = round((strtotime($v['CostDateEnd']) - strtotime($v['CostDateBegin'])) / 3600 / 24) + 1;
                    // 报销单信息
                    $info = array(
                        "BillNo" => $billNo, "InputMan" => "admin", "InputDate" => $now, "UpdateDT" => $now,
                        "CostMan" => $v['CostMan'], "CostDepartID" => $v['CostDepartID'], "ProjectNo" => $v['ProjectNo'],
                        "CostDates" => $v['CostDateBegin'] . '~' . $v['CostDateEnd'], "Status" => "部门审批",
                        "isProject" => 1, "xm_sid" => 1, "CostBelongTo" => 4, "belongtocom" => $v['company'],
                        "CostBelongComId" => $v['company'], "CostBelongCom" => $v['namecn'], 'CostManCom' => $v['namecn'],
                        "CostBelongDeptId" => $v['CostDepartID'], "CostDateBegin" => $v['CostDateBegin'],
                        "CostDateEnd" => $v['CostDateEnd'], "days" => $days, "ExaStatus" => "部门审批",
                        "ExaDT" => $now, "CostManComId" => $v['company'], "daoflag" => 1, 'InputManName' => 'admin',
                        "isNew" => 1,"isImptSubsidy" => "是"
                    );
                    $info = $this->infoAppend_d($info);
                    
                    // 声明这个时候处理的是报销单
                    $this->tbl_name = "cost_summary_list";
                    $id = $this->create($info);

                    // 更新补贴记录和报销单的关系
                    $sqlheadid = "select GROUP_CONCAT(cast(HeadID as CHAR(255))) AS HeadIDs from cost_detail_list
						where CostMan='" . $v['CostMan'] . "' and
						ProjectNo='" . $v['ProjectNo'] . "'  and imId ='$imd' and daoFlag=1
						group by HeadID";
                    $rowHeadID = $this->db->get_one($sqlheadid);
                    if ($rowHeadID['HeadIDs']) {
                        $sql = "update cost_detail_assistant set BillNo='" . $billNo . "' where HeadID in(" . $rowHeadID['HeadIDs'] . ") and imId ='$imd' ";
                        $this->db->query($sql);

                        // 获取补贴明细，导入项目明细表
                        $detailSql = "select a.BillNo,d.HeadID,d.CostTypeID,d.CostMoney,d.days,d.Remark,d.AssID
                            from cost_detail_project d,cost_detail_assistant a
                            where a.BillNo='" . $billNo . "' and  a.HeadID in(" . $rowHeadID['HeadIDs'] . ")
                                AND d.AssID=a.ID and a.HeadID=d.HeadID and a.imId ='$imd'";
                        $detailData = $this->db->getArray($detailSql);
                        if ($detailData) {
                            $this->itemBuild($info, $detailData, '1', $v['Days']);
                        }
                    }
                    // 获取原单据的审批流,并取第一步审批人放入报销审批流中
                    $extUserArr = array();
                    $chkSql = "select s.item as appName,p.*  from flow_step_partent p 
                        left join wf_task t on p.Wf_task_ID = t.task
						left join flow_step s on s.ID = p.StepID
                        where t.Pid='$imd'
                        AND t.code='cost_detail_import'
                        order by SmallID limit 1";
                    $chkResult = $this->db->get_one($chkSql);
                    if($chkResult && !empty($chkResult)){
                        $extArr['appName'] = $chkResult['appName'];
                        $extArr['appUser'] = $chkResult['User'];
                        $extArr['createTime'] = $chkResult['Start'];
                        $extArr['appTime'] = $chkResult['Endtime'];
                        $extArr['content'] = $chkResult['Content'];
                        $extUserArr[] = $extArr;
                    }

                    $AppUserI = $this->getAppUserI($v['ProjectNo'], $v['company'],$extUserArr);
                    // bill flow
                    $creatorId = $v['CostMan'];// 审批流提交人去报销单的报销人
                    $this->buildApprovalWorkflow($billNo, $AppUserI, $id, "是",$creatorId);
                }
            }

        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
        }
        $this->db->query("COMMIT");
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 新报销信息补充
     * @param $object
     */
    function infoAppend_d($object)
    {
        // 报销人
        $userDao = new model_deptuser_user_user();
        $object['feeManId'] = $object['CostMan'];
        $object['feeMan'] = $object['CostManName'] = $userDao->getName_d($object['CostMan']);

        // 费用归属部门
        $deptDao = new model_deptuser_dept_dept();
        $object['CostDepartName'] = $object['CostBelongDeptName'] = $deptDao->getName_d($object['CostDepartID']);

        // 项目数据匹配
        $projectDao = new model_engineering_project_esmproject();
        $projectInfo = $projectDao->getProjectInfo_d($object['ProjectNo']);

        // 如果项目存在，则开始进行项目数据处理
        if ($projectInfo) {
            // 关键属性附加
            $object['projectId'] = $projectInfo['id'];
            $object['projectName'] = $projectInfo['projectName'];
            $object['proManagerName'] = $projectInfo['managerName'];
            $object['proManagerId'] = $projectInfo['managerId'];
            $object['projectType'] = 'esm';
            $object['module'] = $projectInfo['module'];
            $object['moduleName'] = $projectInfo['moduleName'];
            $object['province'] = $projectInfo['province'];
            $object['city'] = $projectInfo['city'];
            $object['CustomerType'] = $projectInfo['customerTypeName'];

            // 项目属性判定
            switch ($projectInfo['attribute']) {
                case 'GCXMSS-01' : // PK
                    $object['DetailType'] = '4';

                    // 获取PK相关信息并赋值
                    $trialProjectDao = new model_projectmanagent_trialproject_trialproject();
                    $PKProjectInfo = $trialProjectDao->get_d($projectInfo['contractId']);

                    // PK信息赋值
                    $object['chanceId'] = $PKProjectInfo['chanceId'];
                    $object['chanceCode'] = $PKProjectInfo['chanceCode'];
                    $object['customerId'] = $PKProjectInfo['customerId'];
                    $object['customerName'] = $PKProjectInfo['customerName'];
                    $object['salesArea'] = $PKProjectInfo['areaName'];
                    $object['salesAreaId'] = $PKProjectInfo['areaCode'];

                    // 获取商机信息
                    $chanceDao = new model_projectmanagent_chance_chance();
                    $chanceInfo = $chanceDao->get_d($object['chanceId']);

                    // 商机信息赋值
                    $object['chanceName'] = $chanceInfo['chanceName'];

                    break;
                case 'GCXMSS-02' : // 服务
                    $object['DetailType'] = '2';
                    break;
                case 'GCXMSS-05' : // 研发
                    $object['DetailType'] = '3';
                    break;
                case 'GCXMSS-04' : // 部门项目
                    $object['DetailType'] = '1';
                    break;
                default:
                    $object['DetailType'] = '2';
                    break;
            }
        }
        return $object;
    }

    /**
     * 新报销明细补充
     * @param $detail
     */
    function detailAppend_d($detail)
    {
        $costTypeDao = new model_finance_expense_costtype();
        $costType = $costTypeDao->get2_d($detail['CostTypeID']);
        $detail['CostType'] = $costType['costTypeName'];
        $detail['MainType'] = $costType['parentTypeName'];
        $detail['MainTypeId'] = $costType['parentTypeId'];

        return $detail;
    }

    function getMaxBillNo($dId)
    {
        $row = $this->db->get_one("select max(BillNo) as maxBillNo from cost_summary_list where BillNo like'" . date("Ymd") . str_pad($dId, 2, "0", STR_PAD_LEFT) . "%'");
        if ($row["maxBillNo"]) {
            $billno = substr($row["maxBillNo"], 0, -6) . (substr($row["maxBillNo"], -6) + 1);
        } else {
            $billno = date("Ymd") . str_pad($dId, 2, "0", STR_PAD_LEFT) . str_pad("1", 3, "0", STR_PAD_LEFT);
        }
        return $billno;
    }

    function buildApprovalWorkflow($BillNo, $AppUserI, $id, $isImptSubsidy = "否", $creatorId = '')
    {
        $sql = "update cost_summary_list set Status = '部门审批',ExaStatus = '部门审批' where Status<>'部门审批' and BillNo='" . $BillNo . "'";
        $this->db->query($sql);

        // 补充审批摘要,项目编号等原单的关联信息
        $expenseDao = new model_finance_expense_expense();
        $infomation = $projectCode = "";
        $BillArr = $expenseDao->find(array("ID" => $id));
        if(isset($BillArr['ID']) && $BillArr['ID'] != ''){
            $costMoney = $BillArr['Amount'];
            $infomation = "{$BillArr['ProjectNo']} - {$BillArr['projectName']}, {$costMoney}元";
            $projectCode = $BillArr['ProjectNo'];
        }

        $creatorId = ($creatorId == '')? $_SESSION['USER_ID'] : $creatorId;
        $sql = "insert into wf_task set Creator='" . $creatorId . "',
            Enter_user='" . $creatorId . "', name='报销审批',
            pid='" . $id . "', form='153', start=now(),code='cost_summary_list',
            train=35, Status='ok', DBTable='" . $_SESSION["COM_BRN_SQL"] . "',
            isImptSubsidy ='{$isImptSubsidy}',infomation = '{$infomation}',projectCode = '{$projectCode}',
            PassSqlCode='" . addslashes(addslashes("update cost_summary_list set ExaStatus='完成',ExaDT=now(),Status='出纳付款' where id='$id'")) . "',
            DisPassSqlCode='" . addslashes(addslashes("update cost_summary_list set ExaStatus='打回',Status=if(needExpenseCheck=1,'部门检查','打回'),ExaDT=now(),IsFinRec=0 where id='$id'")) . "',
            objCode='$BillNo'";
        // $sql = "insert into wf_task set Creator='" . $_SESSION['USER_ID'] . "', Enter_user='" . $_SESSION['USER_ID'] . "', name='" . $BillNo . "', form='153', start=now(),code='cost_summary_list',train=35, Status='ok', DBTable='" . $_SESSION["COM_BRN_SQL"] . "'";
        $this->db->query($sql);
        $taskid = $this->db->insert_id();

        foreach ($AppUserI as $key => $val) {
            if ($val['type'] == 1) {
                $contentStr = isset($val['content'])? $val['content'] : '';
                $appUserI = explode(',', $val['appUser']);
                $sqlInOld = "INSERT into flow_step set SmallID='" . ($key + 1) . "',Wf_task_ID='$taskid',Flow_id='153',Step='" . ($key + 1) . "',StepID='" . ($key + 1) . "',Item='" . $val['appName'] . "',User='" . $val['appUser'] . "',Flag='ok',status='ok',Flow_head='" . $BillNo . "',Flow_name='报销审批',secrecy='1',speed='1',quickpipe='0',quickreason='',sendread='',Flow_prop='0',PRCS_ALERT='0',Flow_doc='1',Flow_type='1',ATTACHMENT_MEMO='',Start='" . $val['createTime'] . "',Endtime='" . $val['appTime'] . "'";
                $this->db->query($sqlInOld);
                $stepid = $this->db->insert_id();
                $sqlInOlds = "INSERT into flow_step_partent set StepID='$stepid',SmallID='" . ($key + 1) . "',Wf_task_ID='$taskid',User='" . $appUserI[0] . "',Flag='1',Result='ok',Start='" . $val['createTime'] . "',Endtime='" . $val['appTime'] . "',Content = '{$contentStr}'";
                $this->db->query($sqlInOlds);
            } else if ($val['type'] == 2) {
                $sql = "INSERT into flow_step set isReceive=1,isEditPage=1,SmallID='" . ($key + 1) . "',Wf_task_ID='$taskid',Flow_id='153',Step='" . ($key + 1) . "',StepID='" . ($key + 1) . "',Item='" . $val['appName'] . "',User='" . $val['appUser'] . "',Flag='-1',status='ok',Flow_head='" . $BillNo . "',Flow_name='报销审批',secrecy='1',speed='1',quickpipe='0',quickreason='',sendread='',Flow_prop='0',PRCS_ALERT='0',Flow_doc='1',Flow_type='1',ATTACHMENT_MEMO='',Start=now()";
                $this->db->query($sql);
                $stepid = $this->db->insert_id();
                $appUserI = explode(',', $val['appUser']);
                if ($appUserI && is_array($appUserI)) {
                    foreach ($appUserI as $ky => $vl) {
                        $sqlInOlds = "INSERT into flow_step_partent set isReceive=1,isEditPage=1,StepID='$stepid',SmallID='" . ($key + 1) . "',Wf_task_ID='$taskid',User='" . $vl . "',Flag='0',START=now()";
                        $this->db->query($sqlInOlds);
                    }
                }
                $sqla = "update cost_summary_list set Status = '财务审核' where BillNo='" . $BillNo . "'";
                $this->db->query($sqla);
            } else if ($val['type'] >= 3) {
                $sql = "INSERT into flow_step set SmallID='" . ($key + 1) . "',Wf_task_ID='$taskid',Flow_id='153',Step='" . ($key + 1) . "',StepID='" . ($key + 1) . "',Item='" . $val['appName'] . "',User='" . $val['appUser'] . "',Flag='0',status='ok',Flow_head='" . $BillNo . "',Flow_name='报销审批',secrecy='1',speed='1',quickpipe='0',quickreason='',sendread='',Flow_prop='0',PRCS_ALERT='0',Flow_doc='1',Flow_type='1',ATTACHMENT_MEMO='',Start=now()";
                $this->db->query($sql);
            }
        }
    }

    function getbillNo($spid)
    {
        if ($spid) {
            $sql = "	SELECT a.*
					FROM  cost_detail_import as a
								left join wf_task as e on a.id=e.pid 
								left join flow_step_partent as f on e.task=f.Wf_task_ID
					WHERE 1 and f.id='$spid' and Result='ok' and a.ExaStatus='完成'
					GROUP BY a.id";
            $rs = $this->db->get_one($sql);

        }
        return $rs['id'];
    }

    function getAppUserI($ProjectNO, $company, $extUserArr = array(),$nextTypeNum = 0)
    {
        $appUser = array();
//        if ($ProjectNO) {
//            $araId = $this->getProdectRangeId($ProjectNO);
//            if ($araId) {
//                $row = $this->db->get_one("SELECT mainManagerId,managerId,headId FROM oa_esm_office_range where id='$araId'");
//                if ($row['managerId']) {
//                    $createTime = date('Y-m-d H:i:s', strtotime("-3 day 1 hours 1 seconds"));
//                    $appTime = date('Y-m-d H:i:s', strtotime("-2 day 1 hours 5 minutes 5 seconds"));
//                    $appUser[] = array('appName' => '省份负责人', 'appUser' => trim($row['managerId'], ','), 'createTime' => $createTime, 'appTime' => $appTime, 'type' => 1);
//                }
//                if ($row['mainManagerId'] && $row['mainManagerId'] != $row['managerId']) {
//                    $createTime = date('Y-m-d H:i:s', strtotime("-2 day 1 hours 5 minutes 5 seconds"));
//                    $appTime = date('Y-m-d H:i:s', strtotime("-1 day -2 hours -15 minutes -15 seconds"));
//                    $appUser[] = array('appName' => '区域负责人', 'appUser' => trim($row['mainManagerId'], ','), 'createTime' => $createTime, 'appTime' => $appTime, 'type' => 1);
//                }
//                if ($row['headId'] && $row['headId'] != $row['mainManagerId']) {
//                    $createTime = date('Y-m-d H:i:s', strtotime("-1 day -2 hours -15 minutes -15 seconds"));
//                    $appTime = date('Y-m-d H:i:s', time());
//                    $appUser[] = array('appName' => '部门负责人', 'appUser' => trim($row['headId'], ','), 'createTime' => $createTime, 'appTime' => $appTime, 'type' => 1);
//                }
//            }
//
//        }
        /*
          添加原业务单据审批人数据
          -- 暂时这些业务会用到 【补贴|薪酬 生成审批流时,带的原业务单的第一审批人, 以及租车生成报销单带租车登记审批的所有审批人记录】
        */
        if(!empty($extUserArr)){
            foreach ($extUserArr as $k => $v){
                $appName = isset($v['appName'])? $v['appName'] : '';
                $appUserIdStr = isset($v['appUser'])? $v['appUser'] : '';
                $createTime = isset($v['createTime'])? $v['createTime'] : '';
                $appTime = isset($v['appTime'])? $v['appTime'] : '';
                $content = isset($v['content'])? $v['content'] : '';
                $type = isset($v['type'])? $v['type'] : '1';
                $appUser[] = array('appName' => $appName, 'appUser' => $appUserIdStr, 'createTime' => $createTime, 'appTime' => $appTime, 'content' => $content, 'type' => $type);
            }
        }

        $nextTypeNum = ($nextTypeNum > 0)? $nextTypeNum : 2;
        if ('bx' == $company) {
            $appUser[] = array('appName' => '财务会计', 'appUser' => 'dexia.zhong,lilan.he', 'createTime' => '', 'appTime' => '', 'type' => $nextTypeNum);
            $appUser[] = array('appName' => '财务总监', 'appUser' => 'jie1.wu,', 'createTime' => '', 'appTime' => '', 'type' => ($nextTypeNum + 1));
            $appUser[] = array('appName' => '财务负责人', 'appUser' => 'xinping.gou,', 'createTime' => '', 'appTime' => '', 'type' => ($nextTypeNum + 2));
        } else if ('sy' == $company) {
            $appUser[] = array('appName' => '财务会计', 'appUser' => 'wang.min', 'createTime' => '', 'appTime' => '', 'type' => $nextTypeNum);
            $appUser[] = array('appName' => '财务总监', 'appUser' => 'gaowen.liang', 'createTime' => '', 'appTime' => '', 'type' => ($nextTypeNum + 1));
        } else {
            $appUser[] = array('appName' => '财务会计', 'appUser' => 'weiying.li,xiufang.tang,yongjun.zhuo', 'createTime' => '', 'appTime' => '', 'type' => $nextTypeNum);
            $appUser[] = array('appName' => '财务总监', 'appUser' => 'qiangwu.luo,', 'createTime' => '', 'appTime' => '', 'type' => ($nextTypeNum + 1));
        }
        return $appUser;
    }

    function  getProdectRangeId($prodectId)
    {
        if ($prodectId) {
            $prodectSql = "SELECT  DISTINCT projectCode, provinceId,productLine,officeId FROM oa_esm_project  WHERE projectCode='$prodectId'";
            $prodectInfo = $this->db->get_one($prodectSql);
            $projectCode = $prodectInfo["projectCode"];
            $provinceId = $prodectInfo["provinceId"];
            $productLine = $prodectInfo["productLine"];
            $officeId = $prodectInfo["officeId"];
            if ($provinceId && $productLine && $officeId) {
                $ppoSql = "select id from oa_esm_office_range where proId='" . $provinceId . "' and  productLine='" . $productLine . "' and  officeId='" . $officeId . "' ";
                $ppoInfo = $this->db->get_one($ppoSql);
                $appArea = $ppoInfo["id"];
            }
            if (!$appArea && $officeId && $productLine) {
                $poSql = "select id from oa_esm_office_range where productLine='" . $productLine . "' and  officeId='" . $officeId . "' ";
                $poInfo = $this->db->get_one($poSql);
                $appArea = $poInfo["id"];
            }
        }
        return $appArea;
    }

    function createCostData($costDataI)
    {
        // 固定remark的值
        $costDataI['Remark'] = '日常费用';

        if ($costDataI['ProjectNO'] && $costDataI['Amount'] && $costDataI['UserID']) {
            $sql = " INSERT INTO cost_detail_list ( InputMan, InputDate, CostMan, isProject, ProjectNO, Purpose, `Status`, CostBelongTo, xm_sid, DetailType, daoUser, daoFlag, daoTime )
                SELECT USER_ID, now(), USER_ID, 1 AS isProject, '" . $costDataI['ProjectNO'] . "' AS ProjectNO, '' AS Purpose, '提交' AS `Status`, 4 AS CostBelongTo, 1 AS xm_sid, 2 AS DetailType, '" . $_SESSION[' USER_ID '] . "' AS daoUser, 3 AS daoFlag, now() AS daoTime
                FROM USER WHERE USER_ID = '" . $costDataI['UserID'] . "'";

            $query = $this->db->query_exc($sql);
            $lid = $this->db->insert_id();

            $sql = "insert INTO cost_detail_assistant (HeadID,RNo,Place,Note,CostDateBegin,CostDateEnd,`Status`)
					VALUES ('$lid','$i','" . $costDataI['Place'] . "','" . $costDataI['Remark'] . "' ,'" . date('Y-m-d', strtotime($costDataI['StartDate'])) . "' ,'" . date('Y-m-d', strtotime($costDataI['EndDate'])) . "' , 
					'提交')";
            $query = $this->db->query_exc($sql);
            $aid = $this->db->insert_id();

            $sql = "INSERT INTO cost_detail_project (HeadID,RNo,CostTypeID,CostMoney,days,Remark,AssID) 
					VALUES ('$lid','1',395,'" . $costDataI['Amount'] . "',1,'" . $costDataI['Remark'] . "' , '$aid' )";
            $query = $this->db->query_exc($sql);
            $did = $this->db->insert_id();


            $sql = "INSERT into bill_detail (BillTypeID,Days,Amount,BillDetailID,BillAssID) 
					VALUES (20,1,'" . $costDataI['Amount'] . "','$did','$aid')";

            $query = $this->db->query_exc($sql);

            return $lid;
        }
    }

    /**
     * 薪酬生成报销单 b1
     */
    function billCostNo($xid,$extUserArr = array())
    {
        set_time_limit(0);
        $this->db->query("START TRANSACTION");
        try {
            $sql = " SELECT	l.ProjectNo,l.CostMan,u.dept_id AS CostDepartID,costbelongto,u.company,i.namecn,CostDateBegin,CostDateEnd
				FROM cost_detail_assistant a
				LEFT JOIN cost_detail_list l ON a.HeadID = l.HeadID  AND l.HeadID in ('$xid')  and l.ProjectNo is not null
				LEFT JOIN USER u ON (u.user_id = l.costman)
				LEFT JOIN branch_info i ON (u.company = i.namept)
				WHERE l.HeadID in ('$xid') and l.ProjectNo is not null
				GROUP BY  l.ProjectNo,l.CostMan";
            $data = $this->db->getArray($sql);

            if (!empty($data)) {
                // 生成时间
                $now = date('Y-m-d H:i:s');

                foreach ($data as $v) {
                    $billNo = $this->getMaxBillNo($v['CostDepartID']);
                    // 日期间隔
                    $days = round((strtotime($v['CostDateEnd']) - strtotime($v['CostDateBegin'])) / 86400) + 1;
                    // 报销单信息
                    $info = array(
                        "BillNo" => $billNo, "InputMan" => "admin", "InputDate" => $now, "UpdateDT" => $now,
                        "CostMan" => $v['CostMan'], "CostDepartID" => $v['CostDepartID'], "ProjectNo" => $v['ProjectNo'],
                        "CostDates" => $v['CostDateBegin'] . '~' . $v['CostDateEnd'], "Status" => "部门审批",
                        "isProject" => 1, "xm_sid" => 1, "CostBelongTo" => 4, "belongtocom" => $v['company'],
                        "CostBelongComId" => $v['company'], "CostBelongCom" => $v['namecn'], 'CostManCom' => $v['namecn'],
                        "CostBelongDeptId" => $v['CostDepartID'], "CostDateBegin" => $v['CostDateBegin'],
                        "CostDateEnd" => $v['CostDateEnd'], "days" => $days, "ExaStatus" => "部门审批",
                        "ExaDT" => $now, "CostManComId" => $v['company'], "daoflag" => 1, 'InputManName' => 'admin',
                        "isNew" => 1,"isImptSubsidy" => "是"
                    );
                    $info = $this->infoAppend_d($info);
                    
                    // 声明这个时候处理的是报销单
                    $this->tbl_name = "cost_summary_list";
                    $id = $this->create($info);

                    // 更新补贴记录和报销单的关系
                    $sqlheadid = "select GROUP_CONCAT(cast(HeadID as CHAR(255))) AS HeadIDs from cost_detail_list
                        where CostMan='" . $v['CostMan'] . "' and 
                        ProjectNo='" . $v['ProjectNo'] . "'  AND HeadID in ('$xid')
                        group by HeadID";
                    $rowHeadID = $this->db->get_one($sqlheadid);
                    if ($rowHeadID['HeadIDs']) {
                        $sql = "update cost_detail_assistant set BillNo='" . $billNo . "' where HeadID in ('$xid') ";
                        $this->db->query($sql);

                        // 获取补贴明细，导入项目明细表
                        $detailSql = "select a.BillNo,d.HeadID,d.CostTypeID,d.CostMoney,d.days,d.Remark,d.AssID
                            from cost_detail_project d,cost_detail_assistant a
                            where a.BillNo='" . $billNo . "' and  a.HeadID in(" . $xid . ")
                                AND d.AssID=a.ID and a.HeadID=d.HeadID";
                        $detailData = $this->db->getArray($detailSql);
                        if ($detailData) {
                            $this->itemBuild($info, $detailData);
                        }
                    }
                    $AppUserI = $this->getAppUserI($v['ProjectNo'], $v['company'],$extUserArr);

                    // bill flow
                    $creatorId = $v['CostMan'];// 审批流提交人去报销单的报销人
                    $this->buildApprovalWorkflow($billNo, $AppUserI, $id, "是", $creatorId);
                }
            }
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            throw new Exception($e->getMessage());
        }
        $this->db->query("COMMIT");
        //不需要跳转到报销系统界面
        //succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 报销明细构建
     */
    function itemBuild($info, $detailData, $useNewRule = '0', $days = 0) {
        $assId = $billDetailId = $remark = "";
        $amount = 0; // 单据金额
        foreach ($detailData as $ki => $vi) {
            // 本行金额
            $rowAmount = bcmul($vi['CostMoney'], $vi['days'], 2);

            // 金额计算
            $amount = bcadd($amount, $rowAmount, 2);

            // 这里将报销明细已到相关表中
            $vi = $this->detailAppend_d($vi);
            $this->tbl_name = "cost_detail";
            $detailId = $this->create($vi);

            if (!$assId) {
                $assId = $vi['AssID'];
                $billDetailId = $detailId;
                $remark = $vi['Remark'];
            }

            // 分摊记录生成
            $share = array(
                "CostType" => $vi['CostType'], "CostTypeID" => $vi['CostTypeID'],
                "CostMoney" => $rowAmount, "Remark" => $vi['Remark'], "BillNo" => $info['BillNo'],
                "MainType" => $vi['MainType'], "MainTypeId" => $vi['MainTypeId'],
                "module" => $info['module'], "moduleName" => $info['moduleName']
            );
            $this->tbl_name = "oa_finance_costshare";
            $this->create($share);
        }

        if ($amount > 0) {
            // 设置的发票类型获取
            $otherDataDao = new model_common_otherdatas();
            $cost_subsidy_bill_type = $otherDataDao->getConfig('cost_subsidy_bill_type');
            $cost_subsidy_bill_type_out_limit = $otherDataDao->getConfig('cost_subsidy_bill_type_out_limit');

            // 范围内的发票金额处理
            $billAmount = ($useNewRule == "1" && $days > 0)?
                (( $amount > bcmul($days,100,2) )? bcmul($days,100,2) : $amount ) :
                ( ($amount > 3000) ? 3000 : $amount );
            $invoice = array(
                "BillNo" => $info['BillNo'], "BillTypeID" => $cost_subsidy_bill_type, "Days" => 1,
                "Amount" => $billAmount,
                "BillDetailID" => $billDetailId, "BillAssID" => $assId, 
                "invoiceNumber" => 0, "isSubsidy" => 1
            );
            $this->tbl_name = "bill_detail";
            $this->create($invoice);

            if($useNewRule == "1" && $days > 0){// 如果是导入补贴那边生成的报销单, 用新的规则 PMS 443
                $invoiceMoney = bcsub($amount, bcmul($days,100,2), 2); // 需提票金额 = 补助金额 - 工期(天) * 100
                if($invoiceMoney > 0){
                    $invoiceNumber = 1; // 发票数量
                    $feeRegular = $invoiceMoney; // 常规
                    $feeSubsidy = bcmul($days,100,2); // 补贴

                    // 多出的补贴进入测试话费
                    $invoice = array(
                        "BillNo" => $info['BillNo'], "BillTypeID" => $cost_subsidy_bill_type_out_limit,
                        "Days" => 1, "Amount" => $invoiceMoney, "BillDetailID" => $billDetailId,
                        "BillAssID" => $assId, "invoiceNumber" => $invoiceNumber, "isSubsidy" => 0
                    );
                    $this->tbl_name = "bill_detail";
                    $this->create($invoice);
                }else{
                    $invoiceMoney = 0; // 发票金额
                    $invoiceNumber = 0; // 发票数量
                    $feeRegular = 0; // 常规
                    $feeSubsidy = $amount; // 补贴
                }

            }else{
                // 如果金额大于 3000，则对超额的部分生成发票记录
                if ($amount > 3000) {
                    $invoiceMoney = bcsub($amount, 3000, 2); // 发票金额
                    $invoiceNumber = 1; // 发票数量
                    $feeRegular = $invoiceMoney; // 常规
                    $feeSubsidy = 3000; // 补贴

                    // 多出的补贴进入测试话费
                    $invoice = array(
                        "BillNo" => $info['BillNo'], "BillTypeID" => $cost_subsidy_bill_type_out_limit,
                        "Days" => 1, "Amount" => $invoiceMoney, "BillDetailID" => $billDetailId,
                        "BillAssID" => $assId, "invoiceNumber" => $invoiceNumber, "isSubsidy" => 0
                    );
                    $this->tbl_name = "bill_detail";
                    $this->create($invoice);
                } else {
                    $invoiceMoney = 0; // 发票金额
                    $invoiceNumber = 0; // 发票数量
                    $feeRegular = 0; // 常规
                    $feeSubsidy = $amount; // 补贴
                }
            }

            // 最终更新的数据
            $infoUpdate = array(
                "Amount" => $amount, "CheckAmout" => $amount, "invoiceMoney" => $invoiceMoney,
                "invoiceNumber" => $invoiceNumber, "feeRegular" => $feeRegular, 
                "feeSubsidy" => $feeSubsidy, "Purpose" => $remark
            );
            
            // 声明这个时候处理的是报销单明细
            $this->tbl_name = "cost_summary_list";
            $this->update(array('BillNo' => $info['BillNo']), $infoUpdate);
        }
    }

    function model_account()
    {
        @extract($_GET);
        @extract($_POST);
        $data = array();
        if ($seatype == 'cost') {
            $res = '<tr class="ui-widget-content jqgrow ui-row-ltr"  style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">员工</td>
                        <td>员工部门</td>
                        <td>费用归属部门</td>
                        <td>单号</td>
                        <td>时间</td>
                        <td>地址</td>
                        <td>事由</td>
                        <td>项目信息</td>
                        <td>金额</td>
                    </tr>';
            //员工	报销人员部门	费用归属部门	单号	时间	事由	项目信息	项目编号	金额
            /*
            $sql="select 
                    u.user_name , d.dept_name , l.costbelongtodeptids 
                    , l.billno , l.costdates , l.costclienttype
                    , l.projectno , x.name as prona 
                    , l.amount , group_concat(a.place) plc
                from cost_summary_list l
                    left join user u on (l.costman=u.user_id)
                    left join department d on (u.dept_id=d.dept_id)
                    left join xm_lx x on (l.projectno=x.projectno)
                    left join cost_detail_assistant a on (l.billno=a.billno)
                where 
                    to_days(paydt)>=to_days('".$seadtb."')
                    and to_days(paydt)<=to_days('".$seadte."') 
                    and u.company='".$_SESSION['COM_BRN_PT']."'
                    and l.status='完成' 
                    group by l.billno
                    order by l.billno
                     ";
               
        	$sql = "select
		             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		        where 
		            to_days(paydt)>=to_days('".$seadtb."')
                    and to_days(paydt)<=to_days('".$seadte."') 
		            and a.billno=d.billno
                    and l.status='完成' 
		            group by
			            d.ID,l.CostMan
			        ORDER BY  a.CostDateBegin DESC,l.CostMan,t.CostTypeID";
			        */
            $sql = "(select
		              sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status ,bi.namecn as bi
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		            left join branch_info bi on (bi.namept=u.company)
		        where
		            a.billno=d.billno  
		            and to_days(l.paydt)>=to_days('" . $seadtb . "')
                    and to_days(l.paydt)<=to_days('" . $seadte . "') 
                    and u.company='" . $_SESSION['USER_COM'] . "' 		
		            and a.billno=d.billno
                    and l.status='完成' 
		        group by
		            l.billno , d.costtypeid )UNION(select
		              sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status ,bi.namecn as bi
		        from
		            cost_detail_project d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		            left join branch_info bi on (bi.namept=u.company)
		        where
		            a.billno=d.billno  
		            and to_days(l.paydt)>=to_days('" . $seadtb . "')
                    and to_days(l.paydt)<=to_days('" . $seadte . "')
                     and u.company='" . $_SESSION['USER_COM'] . "' 
		            and a.billno=d.billno
                    and l.status='完成' 
		        group by
		            l.billno , d.costtypeid )";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['bn']][$row['ct']] = $row['sm'];
                $data[$row['bn']]['un'] = $row['un'];
                $data[$row['bn']]['bi'] = $row['bi'];
                $data[$row['bn']]['reson'] = $row['costclienttype'];
                $data[$row['bn']]['dts'] = $row['costdates'];
                $data[$row['bn']]['dpu'] = $row['dpu'];
                $data[$row['bn']]['dp'] = $row['dp'];
                $data[$row['bn']]['pro'] = $row['projectno'];
                $data[$row['bn']]['Place'] = $data[$row['bn']]['Place'] . ';' . $row['Place'];
                $data[$row['bn']]['sts'] = $row['status'];
                if ($row['CostDateBegin'] == $row['CostDateEnd']) {
                    $data[$row['bn']]['bet'] = $data[$row['bn']]['bet'] . ';' . $row['CostDateBegin'];
                } else {
                    $data[$row['bn']]['bet'] = $data[$row['bn']]['bet'] . ';' . $row['CostDateBegin'] . '~' . $row['CostDateEnd'];
                }

                $cost_type[$row['ct']] = $row['cn'];
                $data_type[$row['bn']] = isset($data_type[$row['bn']]) ? $data_type[$row['bn']] + $row['sm'] : $row['sm'];
                $data_all[$row['ct']] = isset($data_all[$row['ct']]) ? $data_all[$row['ct']] + $row['sm'] : $row['sm'];
            }
            $res = '';
            $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>公司</td>
            <td>报销人部门</td>
            <td>费用归属部门</td>
            <td>事由</td>
            <td>地址</td>
            <td>项目信息</td>
            <td>报销时间</td>
            <td>状态</td>
          ';
            foreach ((array)$cost_type as $key => $val) {
                $res .= '<td>' . $val . '</td>';
            }
            $res .= '<td>小计</td>';
            $res .= '</tr>';
            $i = 0;
            foreach ($data as $key => $val) {
                if ($i % 2 == 0) {
                    $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr"  style="background: #F3F3F3;">';
                } else {
                    $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
                }
                $res .= '<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['bi'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['dpu'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['reson'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['Place'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['pro'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['bet'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['sts'] . '</td>';
                foreach ($cost_type as $vkey => $vval) {
                    // $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
                    $res .= '<td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo=' . $key . '&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">' . num_to_maney_format($val[$vkey]) . '</a></td>';

                }
                $res .= '<td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo=' . $key . '&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">' . num_to_maney_format($data_type[$key]) . '</a></td>';

                // $res.='<td>' . num_to_maney_format($data_type[$key]) . '</td>';
                $res .= '</tr>';
                $i++;
            }
            $res .= '<tr  class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="10">小计：</td>';
            foreach ((array)$cost_type as $key => $val) {

                $res .= '<td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo=' . $key . '&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">' . num_to_maney_format($data_all[$key]) . '</a></td>';
            }
            $res .= '<td>' . num_to_maney_format(array_sum((array)$data_all)) . '</td>
            </tr>';


            /*
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['billno']]['u']=$row['user_name'];
                $data[$row['billno']]['d']=$row['dept_name'];
                $data[$row['billno']]['td']=$row['costbelongtodeptids'];
                $data[$row['billno']]['dt']=$row['costdates'];
                $data[$row['billno']]['rea']=$row['costclienttype'];
                $data[$row['billno']]['pro']=$row['projectno'].$row['prona'];
                $data[$row['billno']]['am']=$row['amount'];
                $data[$row['billno']]['plc']=$row['plc'];
            }
             if(!empty($data)){
                $i=0;
                foreach($data as $key => $val){
                    $i++;
                    if ($i % 2 == 1) {
                        $res.='<tr style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr style="background: #FFFFFF;">';
                    }
                    $res.='
                            <td style="height: 19px;">'.$val['u'].'</td>
                            <td>'.$val['d'].'</td>
                            <td>'.$val['td'].'</td>
                            <td>'.$key.'</td>
                            <td width="120">'.$val['dt'].'</td>
                            <td width="120">'.$val['plc'].'</td>
                            <td>'.$val['rea'].'</td>
                            <td>'.$val['pro'].'</td>
                            <td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo='.$key.'&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">'.$val['am'].'</a></td>
                        </tr>';
                }
            }
            */
        } elseif ($seatype == 'bill') {
            $res = '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">制单人</td>
                        <td>流水号</td>
                        <td>记账日期</td>
                        <td>部门</td>
                        <td>款项内容</td>
                        <td>费用类型</td>
                        <td>科目类型</td>
                        <td>项目信息</td>
                        <td>合同信息</td>
                        <td>金额</td>
                    </tr>';
            //制单人	流水号	记账日期	部门	款项内容	费用类型	科目类型	项目信息	合同信息	金额
            $sql = "select 
                    u.user_name , l.serialno , tallydt 
                    , dt.dept_name , l.content , ct.costtypename as ctname
                    , bt.name as btname , d.proname , d.prono
                    , d.contname , d.contno , d.amount , d.id as billno
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join user u on (l.inputman=u.user_id)
                    left join department dt on (d.billdept=dt.dept_id)
                    left join cost_type ct on (d.costtypeid=ct.costtypeid)
                    left join bill_type bt on (d.billtypeid=bt.id)
                where 
                    to_days(tallydt)>=to_days('" . $seadtb . "')
                    and to_days(tallydt)<=to_days('" . $seadte . "') 
                    and u.company='" . $_SESSION['USER_COM'] . "'
		           
                    and l.status='完成' 
                    order by d.id ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['billno']]['u'] = $row['user_name'];
                $data[$row['billno']]['s'] = $row['serialno'];
                $data[$row['billno']]['td'] = $row['tallydt'];
                $data[$row['billno']]['dt'] = $row['dept_name'];
                $data[$row['billno']]['con'] = $row['content'];
                $data[$row['billno']]['ct'] = $row['ctname'];
                $data[$row['billno']]['bt'] = $row['btname'];
                $data[$row['billno']]['pro'] = $row['prono'] . $row['proname'];
                $data[$row['billno']]['cont'] = $row['contno'] . $row['contname'];
                $data[$row['billno']]['am'] = $row['amount'];
            }
            if (!empty($data)) {
                $i = 0;
                foreach ($data as $key => $val) {
                    $i++;
                    if ($i % 2 == 1) {
                        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #F3F3F3;">';
                    } else {
                        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
                    }
                    $res .= '
                            <td style="height: 19px;">' . $val['u'] . '</td>
                            <td>' . $val['s'] . '</td>
                            <td>' . $val['td'] . '</td>
                            <td>' . $val['dt'] . '</td>
                            <td width="120">' . $val['con'] . '</td>
                            <td>' . $val['ct'] . '</td>
                            <td>' . $val['bt'] . '</td>
                            <td>' . $val['pro'] . '</td>
                            <td>' . $val['cont'] . '</td>
                            <td>' . $val['am'] . '</td>
                        </tr>';
                }
            }
        }
        return $res;
    }

    function model_account_detail()
    {
        $billNo = $_POST['billNo'] ? $_POST['billNo'] : $_GET['billNo'];
        if ($billNo) {
            $sql = "(SELECT d.ID, t.CostTypeName,t.ParentCostTypeID,d.CostTypeID,sum(d.CostMoney*d.days) as acount
				 			, group_concat(d.remark separator '；') as rmk
				   FROM cost_detail_project d , cost_type t , cost_detail_assistant a
				   WHERE a.id=d.assid AND a.BillNo='$billNo'
									AND d.CostTypeID=t.CostTypeID  
					GROUP BY d.CostTypeID
					)UNION 
					(SELECT d.ID, t.CostTypeName,t.ParentCostTypeID,d.CostTypeID,sum(d.CostMoney*d.days) as acount
									, group_concat(d.remark separator '；') as rmk
					FROM cost_detail d , cost_type t , cost_detail_assistant a
					WHERE a.id=d.assid and a.BillNo='$billNo'
							 AND d.CostTypeID=t.CostTypeID  
					     GROUP BY  d.CostTypeID
					) 
					ORDER BY ParentCostTypeID";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['ID']]['CostTypeName'] = $row['CostTypeName'];
                $data[$row['ID']]['acount'] = $row['acount'];
                $data[$row['ID']]['rmk'] = $row['rmk'];
            }
            $str = '<tr style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">类型</td>
                        <td>金额</td>
                        <td>备注</td>
                    </tr>';
            if ($data && is_array($data)) {
                $i = 0;
                foreach ($data as $key => $val) {
                    $i++;
                    if ($i % 2 == 1) {
                        $str .= '<tr style="background: #F3F3F3;">';
                    } else {
                        $str .= '<tr style="background: #FFFFFF;">';
                    }
                    $str .= '
                            <td style="height: 20px;color:#000;text-align: left; width:25%">' . $val['CostTypeName'] . '</td>
                            <td style="height: 20px;color:#000;text-align: left;width:25%">' . $val['acount'] . '</td>
                            <td style="height: 20px;color:#000;text-align: left;width:40%">' . $val['rmk'] . '</td>
                        </tr>';
                }

            }

        }
        return $str;

    }

    /**
     * 读取各部门信息
     */
    function model_dept_data($flag = '', $xls = false)
    {
        @extract($_GET);
        @extract($_POST);
        $sqlStr = '';
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $seayear = $seayear ? $seayear : date('Y');
        $nowm = $seayear < date('Y') ? 12 : date('n');
        $this->nowm = $nowm;
        $ckb = 1;
        $cke = $this->nowm;
        if ($seatype == 'bill' || $seatype == 'all') {//非报销统计
            if ($seadept) {
                $sqlStrB .= " and dp.dept_name like '%" . $seadept . "%' ";
            }
            if ($seamonb) {
                $ckb = $seamonb;
                $sqlStrB .= " and month(l.inputdt)>='" . $seamonb . "' ";
            }
            if ($seamone) {
                $cke = $seamone;
                $sqlStrB .= " and month(l.inputdt)<='" . $seamone . "' ";
            }
            if ($seayear == date('Y') && $cke > date('n')) {
                $cke = date('n');
                $this->nowm = $cke;
            }
            if ($seayear) {
                $sqlStrB .= " and year(l.inputdt)='" . $seayear . "' ";
            }
        }
        if ($seatype == 'cost' || $seatype == 'all') {
            if ($seadept) {
                $sqlStr .= " and l.costbelongtodeptids like '%" . $seadept . "%' ";
            }
            if ($seamonb) {
                $ckb = $seamonb;
                $sqlStr .= " and month(l.paydt)>='" . $seamonb . "' ";
            }
            if ($seamone) {
                $cke = $seamone;
                $sqlStr .= " and month(l.paydt)<='" . $seamone . "' ";
            }
            if ($seayear == date('Y') && $cke > date('n')) {
                $cke = date('n');
                $this->nowm = $cke;
            }
            if ($seayear) {
                $sqlStr .= " and year(l.paydt)='" . $seayear . "' ";
            }
            if ($combrn) {
                $sqlStr .= " and (u.company ='" . $combrn . "' or l.belongtocom ='" . $combrn . "' or l.CostBelongComId ='" . $combrn . "') ";
            }
        }
        if ($seatype == 'cost' || $seatype == 'all') {//报销费用统计
            //部门报销-区分PK
            $sql = "(select
                    t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                    , l.costbelongtodeptids as cbd  , d.costtypeid as dct
                from
                    cost_detail d left join cost_type t on ( d.costtypeid=t.costtypeid)
                    left join  cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list l on ( d.billno=l.billno )
                    left join user u on (l.costman=u.user_id)
                    left join department dp on (u.dept_id=dp.dept_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    and  year(l.paydt)='" . $seayear . "'
                    and l.projectno not like 'PK%'
                    $sqlStr
                group by  l.costbelongtodeptids , left(l.paydt,7) , d.costtypeid
                order by t.parentcosttypeid , l.costbelongtodeptids 
                )union (
                select
                    t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                    , 'PK项目'  as cbd  , d.costtypeid as dct
                from
                    cost_detail d left join cost_type t on ( d.costtypeid=t.costtypeid)
                    left join  cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list l on ( d.billno=l.billno )
                    left join user u on (l.costman=u.user_id)
                    left join department dp on (u.dept_id=dp.dept_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    and  year(l.paydt)='" . $seayear . "'
                    and l.projectno like 'PK%'
                    $sqlStr
                group by  left(l.paydt,7) , d.costtypeid
                order by t.parentcosttypeid , l.costbelongtodeptids 
                )";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $row['ct'] = $row['dct'];
                $data[$row['cbd']][$row['mon']][$row['ct']] = isset($data[$row['cbd']][$row['mon']][$row['ct']]) ? $data[$row['cbd']][$row['mon']][$row['ct']] + $row['sm'] : $row['sm'];
                $cost_type[$row['ct']] = 1;
                $total[$row['cbd']][$row['ct']] = isset($total[$row['cbd']][$row['ct']]) ? $total[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
                $total_all[$row['ct']] = isset($total_all[$row['ct']]) ? $total_all[$row['ct']] + $row['sm'] : $row['sm'];
                $total_mon[$row['cbd']][$row['mon']] = isset($total_mon[$row['cbd']][$row['mon']]) ? $total_mon[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            }
            //项目报销
            $sql = "select
                    t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                    ,  if(l.projectno like 'PK%','PK项目','网优工程部（项目）')  as cbd , d.costtypeid as dct
                from
                    cost_detail_project d left join cost_type t on ( d.costtypeid=t.costtypeid)
                    left join  cost_detail_assistant a on (  a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list l on ( d.billno=l.billno )
                    left join user u on (l.costman=u.user_id)
                    left join department dp on (u.dept_id=dp.dept_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    and  year(l.paydt)='" . $seayear . "'
                    $sqlStr
                group by l.billno , l.costbelongtodeptids , left(l.paydt,7) , d.costtypeid
                order by t.parentcosttypeid ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $row['ct'] = $row['dct'];
                $data[$row['cbd']][$row['mon']][$row['ct']] = isset($data[$row['cbd']][$row['mon']][$row['ct']]) ? $data[$row['cbd']][$row['mon']][$row['ct']] + $row['sm'] : $row['sm'];
                $cost_type[$row['ct']] = 1;
                $total[$row['cbd']][$row['ct']] = isset($total[$row['cbd']][$row['ct']]) ? $total[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
                $total_all[$row['ct']] = isset($total_all[$row['ct']]) ? $total_all[$row['ct']] + $row['sm'] : $row['sm'];
                $total_mon[$row['cbd']][$row['mon']] = isset($total_mon[$row['cbd']][$row['mon']]) ? $total_mon[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            }
        }
        if (($seatype == 'bill' || $seatype == 'all') && ($combrn == '' || $combrn == 'dl')) {//非报销统计
            if (empty($seabilltype)) {
                $sql = "select
                    t.parentcosttypeid as ct , month(l.inputdt) as mon , sum(d.days*d.amount) as sm , t.costtypename as cn
                    , dp.dept_name as cbd , d.costtypeid as dct
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                where
                    l.status='完成'
                    $sqlStrB
                    group by d.billdept , left(l.inputdt,7) , d.costtypeid
                    order by dp.dept_name ";
            } else {
                $sql = "select
                    t.parentcosttypeid as ct , month(l.inputdt) as mon , sum(d.days*d.amount) as sm , t.costtypename as cn
                    , dp.dept_name as cbd , d.costtypeid as dct
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                where
                    l.status='完成' and d.billtypeid='" . $seabilltype . "'
                    $sqlStrB
                    group by d.billdept , left(l.inputdt,7) , d.costtypeid
                    order by dp.dept_name ";
            }
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $row['ct'] = $row['dct'];
                $data[$row['cbd']][$row['mon']][$row['ct']] = isset($data[$row['cbd']][$row['mon']][$row['ct']]) ? $data[$row['cbd']][$row['mon']][$row['ct']] + $row['sm'] : $row['sm'];
                if (!empty($row['ct'])) {
                    $cost_type[$row['ct']] = 1;
                }
                $total[$row['cbd']][$row['ct']] = isset($total[$row['cbd']][$row['ct']]) ? $total[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
                $total_all[$row['ct']] = isset($total_all[$row['ct']]) ? $total_all[$row['ct']] + $row['sm'] : $row['sm'];
                $total_mon[$row['cbd']][$row['mon']] = isset($total_mon[$row['cbd']][$row['mon']]) ? $total_mon[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            }
        }
        if (!empty($cost_type)) {
            $sql = "select costtypeid as ct , costtypename as cn
                from cost_type
                where
                    costtypeid in (" . implode(',', array_keys($cost_type)) . ")
                ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $cost_type[$row['ct']] = $row['cn'];
            }
        }
        $res .= '<tr style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;">部门</td>
                    <td>月份</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . $val . '</a></td>';
        }
        $res .= '     <td>合计</td>
                </tr>';
        $cki = 0;
        foreach ($data as $key => $val) {
            $cki++;
            $si = 0;
            for ($i = 1; $i <= $this->nowm; $i++) {
                if ($i >= $ckb && $i <= $cke) {
                    if ($si % 2 == 1) {
                        $res .= '<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #F3F3F3;">';
                    } else {
                        $res .= '<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
                    }
                    if ($si == 0) {
                        $res .= '<td id="head_' . $cki . '" rowspan="' . ($cke - $ckb + 1 + 1) . '" style="text-align: center;">' . $key . '</td>';
                    }
                    $res .= '<td height="20" style="text-align: center;"><a href="javascript:newParentTab(\'' . $key . '\',\'' . $seayear . '\',\'' . $i . '\',\'\',\'' . $seatype . '\',\'' . $seabilltype . '\',\'' . $combrn . '\');">' . $i . '月</a></td>';
                    foreach ($cost_type as $vkey => $vval) {
                        if ($xls) {
                            $res .= '<td>' . num_to_maney_format($val[$i][$vkey]) . '</td>';//$seabilltype
                        } else {
                            $res .= '<td><a href="javascript:newParentTab(\'' . $key . '\',\'' . $seayear . '\',\'' . $i . '\',\'' . $vkey . '\',\'' . $seatype . '\',\'' . $seabilltype . '\',\'' . $combrn . '\');">' . num_to_maney_format($val[$i][$vkey]) . '</a></td>';
                        }
                    }
                    $res .= '<td>' . num_to_maney_format($total_mon[$key][$i]) . '</td>';
                    $si++;
                    $res .= '</tr>';
                }

            }
            $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #2E982E;color:#FFFFFF;">
                <td height="20" style="text-align: center;">小计</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res .= '<td>' . num_to_maney_format($total[$key][$vkey]) . '</td>';
            }
            $res .= '
                <td>' . num_to_maney_format(array_sum($total[$key])) . '</td>
              </tr>
                ';
        }
        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #CCCCFF;">
                <td height="28" style="text-align: center;" colspan="2">合计</td>';
        foreach ($cost_type as $vkey => $vval) {
            $res .= '<td>' . num_to_maney_format($total_all[$vkey]) . '</td>';
        }
        $res .= '
                <td>' . num_to_maney_format(array_sum($total_all)) . '</td>
              </tr>';
        return $res;
    }

    /**
     * 读取各部门信息
     */
    function model_dept_fn_data($flag = '', $xls = false)
    {
        @extract($_GET);
        @extract($_POST);
        $sqlStr = '';
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $seayear = $seayear ? $seayear : date('Y');
        $nowm = $seayear < date('Y') ? 12 : date('n');
        $this->nowm = $nowm;
        $ckb = 1;
        $cke = $this->nowm;
        $seatype = 'all';
        if ($seatype == 'bill' || $seatype == 'all') {//非报销统计
            if ($seadept) {
                $sqlStrB .= " and dp.dept_name like '%" . $seadept . "%' ";
            }
            if ($seamonb) {
                $ckb = $seamonb;
                $sqlStrB .= " and month(l.inputdt)>='" . $seamonb . "' ";
            }
            if ($seamone) {
                $cke = $seamone;
                $sqlStrB .= " and month(l.inputdt)<='" . $seamone . "' ";
            }
            if ($seayear == date('Y') && $cke > date('n')) {
                $cke = date('n');
                $this->nowm = $cke;
            }
        }
        if ($seatype == 'cost' || $seatype == 'all') {
            if ($seadept) {
                $sqlStr .= " and l.costbelongtodeptids like '%" . $seadept . "%' ";
            }
            if ($seamonb) {
                $ckb = $seamonb;
                $sqlStr .= " and month(l.paydt)>='" . $seamonb . "' ";
            }
            if ($seamone) {
                $cke = $seamone;
                $sqlStr .= " and month(l.paydt)<='" . $seamone . "' ";
            }
            if ($seayear == date('Y') && $cke > date('n')) {
                $cke = date('n');
                $this->nowm = $cke;
            }
        }
        $spro = array();
        //项目报销
        $sql = "select 
                    l.projectno as pro , sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as dct
                from 
                    cost_detail_project d 
                    left join cost_type t on ( d.costtypeid=t.costtypeid )
                    left join cost_summary_list l on (d.billno=l.billno)

                where 
                    l.status='完成'
                    and l.isproject='1'
                    and year(l.paydt)='2011'
                group by l.projectno , d.costtypeid 
                order by d.costtypeid";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $spro[$row['pro']] = $row['pro'];
            $data[$row['pro']][$row['dct']] = isset($data[$row['pro']][$row['dct']]) ?
                $data[$row['pro']][$row['dct']] + $row['sm'] : $row['sm'];
            $cost_type[$row['dct']] = $row['cn'];
            $total[$row['dct']] = isset($total[$row['dct']]) ? $total[$row['dct']] + $row['sm'] : $row['sm'];
        }
        //非报销类

        $sql = "select
                    d.contno , d.prono ,  sum(d.days*d.amount) as sm , t.costtypename as cn , d.costtypeid as dct , d.billno 
                    , d.costtypeid as dct
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                where
                    l.status='完成'
                    and d.billtypeid='7'
                    and year(l.tallydt)='2011'
                group by d.contno  , d.prono  , d.costtypeid
                order by d.costtypeid ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $row['pro'] = $row['contno'];
            if (empty($row['contno'])) {
                $row['pro'] = $row['prono'];
            }
            if (empty($row['contno']) && empty($row['prono'])) {
                $row['pro'] = '空';
            }
            $spro[$row['pro']] = $row['pro'];
            $data_b[$row['pro']][$row['dct']] = isset($data_b[$row['pro']][$row['dct']]) ?
                $data_b[$row['pro']][$row['dct']] + $row['sm'] : $row['sm'];
            $cost_type_b[$row['dct']] = $row['cn'];
            $total_b[$row['dct']] = isset($total_b[$row['dct']]) ? $total_b[$row['dct']] + $row['sm'] : $row['sm'];
        }

        $res .= '<tr style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;" rowspan="2">项目</td>';
        $res .= '     <td colspan="' . count($cost_type) . '" >报销费用</td>';
        $res .= '     <td rowspan="2">小计</a></td>';
        $res .= '     <td colspan="' . count($cost_type_b) . '" >非报销费用</td>';
        $res .= '     <td rowspan="2">小计</td>
                </tr>';
        $res .= '<tr style="background: #D3E5FA;text-align: center;">';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . $val . '</a></td>';
        }
        foreach ($cost_type_b as $key => $val) {
            $res .= '<td>' . $val . '</a></td>';
        }
        $res .= '</tr>';
        $cki = 0;
        foreach ($spro as $val) {
            $cki++;
            $res .= '<tr class=" ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
            $res .= '<td>' . $val . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res .= '<td>' . num_to_maney_format($data[$val][$vkey]) . '</td>';
            }
            $res .= '<td>' . num_to_maney_format(array_sum($data[$val])) . '</td>';
            foreach ($cost_type_b as $vkey => $vval) {
                $res .= '<td>' . num_to_maney_format($data_b[$val][$vkey]) . '</td>';
            }
            $res .= '<td>' . num_to_maney_format(array_sum($data_b[$val])) . '</td>';
            $res .= '</tr>';
        }
        return $res;
    }

    /**
     * 读取各部门信息
     */
    function model_dept_dp_data($flag = '', $xls = false)
    {
        @extract($_GET);
        @extract($_POST);
        $sqlStr = '';
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $seayear = $seayear ? $seayear : date('Y');
        $nowm = $seayear < date('Y') ? 12 : date('n');
        $this->nowm = $nowm;
        $ckb = 1;
        $cke = $this->nowm;
        $seatype = 'all';
        if ($seatype == 'bill' || $seatype == 'all') {//非报销统计
            if ($seadept) {
                $sqlStrB .= " and dp.dept_name like '%" . $seadept . "%' ";
            }
            if ($seamonb) {
                $ckb = $seamonb;
                $sqlStrB .= " and month(l.inputdt)>='" . $seamonb . "' ";
            }
            if ($seamone) {
                $cke = $seamone;
                $sqlStrB .= " and month(l.inputdt)<='" . $seamone . "' ";
            }
            if ($seayear == date('Y') && $cke > date('n')) {
                $cke = date('n');
                $this->nowm = $cke;
            }
        }
        if ($seatype == 'cost' || $seatype == 'all') {
            if ($seadept) {
                $sqlStr .= " and l.costbelongtodeptids like '%" . $seadept . "%' ";
            }
            if ($seamonb) {
                $ckb = $seamonb;
                $sqlStr .= " and month(l.paydt)>='" . $seamonb . "' ";
            }
            if ($seamone) {
                $cke = $seamone;
                $sqlStr .= " and month(l.paydt)<='" . $seamone . "' ";
            }
            if ($seayear == date('Y') && $cke > date('n')) {
                $cke = date('n');
                $this->nowm = $cke;
            }
        }
        $spro = array();
        //项目报销
        $sql = "select 
                    l.projectno as pro , sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as dct
                    ,l.paydt
                from 
                    cost_detail d 
                    left join cost_type t on ( d.costtypeid=t.costtypeid )
                    left join cost_summary_list l on (d.billno=l.billno)
                    left join xm x on (x.projectno=l.projectno)
                where 
                    l.status='完成'
                    and l.isproject='0'
                    and (l.projectno <> '' )
                    and year(l.paydt)='2010'
                    and x.id is null 
                    and l.projectno not in ('123','11','DL01','00000')
                group by l.projectno , d.costtypeid 
                order by d.costtypeid ";
        $sql = "select
                    d.contno , d.prono ,  sum(d.days*d.amount) as sm , t.costtypename as cn , d.costtypeid as dct , d.billno 
                    ,  l.serialno , l.tallydt , l.payee , l.content
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                where
                    l.status='完成'
                    and d.billtypeid='7'
		                and year(l.tallydt)='2011'
		            group by d.contno  , d.prono  ,d.billno , t.costtypeid 
                order by d.contno  , d.prono , l.serialno ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $row['pro'] = $row['contno'];
            if (empty($row['contno'])) {
                $row['pro'] = $row['prono'];
            }
            if (empty($row['contno']) && empty($row['prono'])) {
                $row['pro'] = '空';
            }
            $spro[$row['pro']][$row['billno']]['sno'] = $row['serialno'];
            $spro[$row['pro']][$row['billno']]['tdt'] = $row['tallydt'];
            $spro[$row['pro']][$row['billno']]['hm'] = $row['payee'];
            $spro[$row['pro']][$row['billno']]['kx'] = $row['content'];
            $data[$row['pro']][$row['billno']][$row['dct']] = $row['sm'];
            $cost_type[$row['dct']] = $row['cn'];
            $total[$row['dct']] = isset($total[$row['dct']]) ? $total[$row['dct']] + $row['sm'] : $row['sm'];
        }

        $res .= '<tr style="background: #D3E5FA;text-align: center;">';
        $res .= '<td style="height: 23px;">项目</td>';
        $res .= '<td style="height: 23px;">流水号</td>';
        $res .= '<td style="height: 23px;">记帐日期</td>';
        $res .= '<td style="height: 23px;">收款单位</td>';
        $res .= '<td style="height: 23px;">款项内容</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . $val . '</a></td>';
        }
        $res .= '<td>小计</td></tr>';
        $cki = 0;
        foreach ($spro as $val => $key) {

            foreach ($key as $bkey => $bval) {
                $cki++;
                $res .= '<tr class=" ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
                $res .= '<td>' . $val . '</td>';
                $res .= '<td>' . $bval['sno'] . '</td>';
                $res .= '<td>' . $bval['tdt'] . '</td>';
                $res .= '<td>' . $bval['hm'] . '</td>';
                $res .= '<td>' . $bval['kx'] . '</td>';
                foreach ($cost_type as $vkey => $vval) {
                    $res .= '<td>' . num_to_maney_format($data[$val][$bkey][$vkey]) . '</td>';
                }
                $res .= '<td>' . num_to_maney_format(array_sum($data[$val][$bkey])) . '</td>';
                $res .= '</tr>';
            }
        }
        return $res;
    }

    function model_dept_excel()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_data('', true)) . '
            </table>';
    }

    /**
     * 查询借款超时
     */
    function model_loan_overtime()
    {
        $checkdept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        if ($checkdept) {
            $sqlStr .= " and d.dept_name like '%$checkdept%' ";
        }
        if ($checkuser) {
            $sqlStr .= " and u.user_name like '%$checkuser%' ";
        }
        $data = array();
        $datadept = array();
        $deptam = array();
        $sql = "select
                l.debtor , u.user_name, ( l.amount - IFNULL(r.repam,0)) as lam
                , l.id 
                , r.repam 
                , u.email , l.paydt , d.dept_name  , to_days(now())-to_days(l.paydt) as ldt
                , l.no_writeoff
            from
                loan_list l
                left join user u on (l.debtor = u.user_id )
                left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id )
                left join department d on (u.dept_id=d.dept_id)
    
            where
                l.status in ('还款中','已支付')
                and to_days(  DATE_ADD(l.paydt , INTERVAL 60 DAY) ) <=  to_days(now())
                and u.company = '" . $_SESSION['USER_COM'] . "'
                $sqlStr 
            order by l.debtor ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if ($row['lam'] <= 0) {
                continue;
            }
            $data[$row['dept_name']][$row['id']]['name'] = $row['user_name'];
            $data[$row['dept_name']][$row['id']]['lam'] = $row['lam'];
            $data[$row['dept_name']][$row['id']]['email'] = $row['email'];
            $data[$row['dept_name']][$row['id']]['ldt'] = $row['ldt'];
            $data[$row['dept_name']][$row['id']]['ncx'] = ($row['no_writeoff'] == '1' ? '是' : '否');
            //部门
            $datadept[$row['dept_name']] = isset($datadept[$row['dept_name']]) ? $datadept[$row['dept_name']] + $row['lam'] : $row['lam'];
        }
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $key => $val) {
                $y = 0;
                $deptam[$key] = 0;
                $deptdt[$key] = 0;
                foreach ($val as $vkey => $vval) {
                    $deptam[$key] += $vval['lam'];
                    $deptdt[$key] += $vval['ldt'];
                    $i++;
                    $y++;
                    if ($i % 2 == 0) {
                        $res .= '<tr style="background: #F3F3F3;">';
                    } else {
                        $res .= '<tr style="background: #FFFFFF;">';
                    }
                    if ($y == 1) {
                        $res .= '<td nowrap style="text-align:center;height:20px;" rowspan="' . (count($val) + 1) . '">' . $key . '</td>';
                    }
                    $res .= '<td style="text-align:center;">' . $vval['name'] . '</td>';
                    $res .= '<td style="text-align:center;">' . $vkey . '</td>';
                    $res .= '<td>' . num_to_maney_format($vval['lam']) . '</td>';
                    $res .= '<td style="text-align:center;">' . $vval['ldt'] . '</td>';
                    $res .= '<td style="text-align:center;">' . $vval['ncx'] . '</td>';
                    $res .= '</tr>';
                }
                $res .= '<tr style="background: #D3E5FA;">
                       <td style="text-align:center;">小计：</td>
                       <td></td>
                       <td>' . num_to_maney_format($deptam[$key]) . '</td>
                       <td></td>
                       <td></td>
                       </tr>';
            }
        }
        $res = '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td>部门</td>
                <td>员工</td>
                <td>借款单号</td>
                <td>逾期借款总额</td>
                <td>借款天数</td>
                <td>是否已备案为长期借款</td>
              </tr>
            ' . $res . '
              <tr style="background: green;">
                <td></td>
                <td style="text-align:center;">合计：</td>
                <td></td>
                <td>' . num_to_maney_format(array_sum($deptam)) . '</td>
                <td></td>
                <td></td>
              </tr>';
        return $res;
    }

    function model_loan_overtime_xls()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_loan_overtime()) . '
            </table>';
    }

    function model_loan_avg_xls()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_loan_avg()) . '
            </table>';
    }

    function model_loan_avg()
    {
        $seadept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $seauser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seadtb = isset($_POST['seadtb']) ? $_POST['seadtb'] : date('Y') . '-01-01';
        $seadte = isset($_POST['seadte']) ? $_POST['seadte'] : date('Y-m-d');
        $loanarr = array();
        $reparr = array();
        if ($seadept) {
            $sqlStr .= " and d.dept_name like '%" . $seadept . "%' ";
        }
        if ($seauser) {
            $sqlStr .= " and u.user_name like '%" . $seauser . "%'";
        }
        $sql = "SELECT
                l.debtor , sum( l.amount ) as sm
                , u.user_name , d.dept_name
                , (to_days('" . $seadte . "') - to_days('" . $seadtb . "')+1) as dts
            FROM 
                loan_list l 
                left join user u on (l.debtor = u.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join user u1 on (l.payee = u1.user_id)
            where 
                l.status in ('还款中','已支付','已还款')
                and to_days(l.paydt) >= to_days('" . $seadtb . "')
                and to_days(l.paydt) <= to_days('" . $seadte . "')
                and u1.company = '" . $_SESSION['USER_COM'] . "'
                $sqlStr
            group by l.debtor ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $loanarr[$row['dept_name']][$row['debtor']]['name'] = $row['user_name'];
            $loanarr[$row['dept_name']][$row['debtor']]['sm'] = $row['sm'];
            $dts = $row['dts'];
        }
        $sql = "select
                r.loan_id , r.money , (to_days(r.createdt)-to_days(l.paydt)+1) as dt , l.debtor
            from loan_repayment r
                left join loan_list l on (l.id=r.loan_id)
                left join user u on (l.debtor=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                to_days(r.createdt) >= to_days('" . $seadtb . "')
                and to_days(r.createdt) <= to_days('" . $seadte . "')
                and to_days(l.paydt) >= to_days('" . $seadtb . "')
                and to_days(l.paydt) <= to_days('" . $seadte . "')
                $sqlStr  ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $reparr[$row['debtor']][$row['loan_id']]['am'] = $row['money'];
            $reparr[$row['debtor']][$row['loan_id']]['dt'] = $row['dt'];
        }
        if (!empty($loanarr)) {
            $i = 0;
            foreach ($loanarr as $key => $val) {
                $y = 0;
                foreach ($val as $vkey => $vval) {
                    $i++;
                    $y++;
                    $rpam = 0;
                    $rpsm = 0;
                    $rpst = '';
                    if ($reparr[$vkey]) {
                        foreach ($reparr[$vkey] as $rkey => $rval) {
                            $rpam += $rval['am'];
                            $rpsm += $rval['am'] * $rval['dt'];
                            $rpst .= $rval['am'] . '*' . $rval['dt'] . ' | ';
                        }
                    }
                    if ($vval['sm'] - $rpam) {
                        $rpsm += ($vval['sm'] - $rpam) * $dts;
                        $rpst .= ($vval['sm'] - $rpam) . '*' . $dts . ' | ';
                    }
                    $rpsm = ceil($rpsm / $vval['sm']);
                    $rpst = trim($rpst, '| ');
                    if ($i % 2 == 0) {
                        $res .= '<tr style="background: #F3F3F3;">';
                    } else {
                        $res .= '<tr style="background: #FFFFFF;">';
                    }
                    if ($y == 1) {
                        $res .= '<td nowrap style="text-align:center;height:20px;" rowspan="' . count($val) . '">' . $key . '</td>';
                    }
                    $res .= '<td style="text-align:center;">' . $vval['name'] . '</td>';
                    $res .= '<td>' . num_to_maney_format($vval['sm']) . '</td>';
                    $res .= '<td>' . num_to_maney_format($rpam) . '</td>';
                    $res .= '<td>' . num_to_maney_format($vval['sm'] - $rpam) . '</td>';
                    $res .= '<td style="text-align:center;">' . $rpsm . '</td>';
                    $res .= '<td style="text-align:left;">' . $rpst . '</td>';
                    $res .= '</tr>';
                }
            }
        }
        $res = '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td>部门</td>
                <td>借款人</td>
                <td>借款总额</td>
                <td>还款总额</td>
                <td>尚欠总额</td>
                <td>平均还款天数</td>
                <td>明细（还款金额*还款天数）</td>
              </tr>' . $res;
        return $res;
    }

    /**
     *查询详情
     * @return string
     */
    function model_dept_detail_tmp()
    {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype'] : 'cost';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype'] : '';
        $cktype = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : '';
        $data = array();
        $cost_type = array();
        $data_type = array();
        $data_all = array();
        $sqlStr = '';
        if ($checkbill) {
            $sqlStr .= " and l.billno like '%$checkbill%' ";
        }
        if ($checkuser) {
            $sqlStr .= " and u.user_name like '%$checkuser%' ";
        }
        if (!empty($checkt)) {
            $sqlStr .= " and d.costtypeid ='$checkt' ";
        }
        if ($cktype == 'p') {
            $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costdates , l.status , l.costclienttype
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join wf_task wt on (l.BillNo=wt.name)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status<>'打回'
                    and a.billno=d.billno
                    and ( 
                        ( l.costclienttype like '%培训%'
                            and l.costbelongtodeptids ='人力资源部' 
                        )
                    or wt.train='388' )
                group by
                    l.billno , d.costtypeid ";
        } else {
            $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costdates , l.status , l.costclienttype
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                    left join wf_task wt on (l.BillNo=wt.name)
                    left join flow_step_partent s on (wt.task=s.Wf_task_ID)
                where
                    l.status<>'打回'
                    and a.billno=d.billno
                    and s.User='yunxia.zhu'
                group by
                    l.billno , d.costtypeid ";
        }

        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['bn']][$row['ct']] = $row['sm'];
            $data[$row['bn']]['un'] = $row['un'];
            $data[$row['bn']]['cds'] = $row['costdates'];
            $data[$row['bn']]['sta'] = $row['status'];
            $data[$row['bn']]['cct'] = $row['costclienttype'];
            $cost_type[$row['ct']] = $row['cn'];
            $data_type[$row['bn']] = isset($data_type[$row['bn']]) ? $data_type[$row['bn']] + $row['sm'] : $row['sm'];
            $data_all[$row['ct']] = isset($data_all[$row['ct']]) ? $data_all[$row['ct']] + $row['sm'] : $row['sm'];
        }
        //echo $sql;
        $res = '';
        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td width="120">事由</td>
            <td>日期</td>
            <td>状态</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . $val . '</td>';
        }
        $res .= '<td>小计</td>';
        $res .= '</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res .= '<tr style="background: #F3F3F3;">';
            } else {
                $res .= '<tr style="background: #FFFFFF;">';
            }
            $res .= '<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['cct'] . '</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['cds'] . '</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['sta'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res .= '<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res .= '<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res .= '</tr>';
            $i++;
        }
        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="5">小计：</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . num_to_maney_format($data_all[$key]) . '</td>';
        }
        $res .= '<td>' . num_to_maney_format(array_sum($data_all)) . '</td>
            </tr>';
        return $res;
    }

    /**
     *查询详情
     * @return string
     */
    function model_dept_detail()
    {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype'] : 'cost';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype'] : '';
        $combrn = isset($_REQUEST['combrn']) ? $_REQUEST['combrn'] : '';
        $data = array();
        $cost_type = array();
        $data_type = array();
        $data_all = array();
        $sqlStr = '';
        if ($checkbill) {
            $sqlStr .= " and l.billno like '%$checkbill%' ";
        }
        if ($checkuser) {
            $sqlStr .= " and u.user_name like '%$checkuser%' ";
        }
        if (!empty($checkt)) {
            $sqlStr .= " and d.costtypeid ='$checkt' ";
        }
        if ($combrn) {
            $sqlStr .= " and (u.company ='" . $combrn . "' or l.belongtocom ='" . $combrn . "' or l.CostBelongComId ='" . $combrn . "') ";
        }
        if ($seatype == 'cost' || $seatype == 'all') {
            if ($checkd == '网优工程部（项目）') {
                $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail_project d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno)
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno and l.projectno not like 'PK%'
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                group by
                    l.billno , d.costtypeid ";
            } elseif ($checkd == 'PK项目') {
                $sql = "(select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail_project d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno)
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno and l.projectno like 'PK%'
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                group by
                    l.billno , d.costtypeid 
                 )UNION (
                		select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                    and l.projectno like 'PK%'
                group by
                    l.billno , d.costtypeid 
                )";
            } else {
                $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                    and l.costbelongtodeptids='$checkd'
                    and l.projectno not like 'PK%'
                group by
                    l.billno , d.costtypeid ";
            }
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['bn']][$row['ct']] = $row['sm'];
                $data[$row['bn']]['un'] = $row['un'];
                if (empty($row['province'])) {
                    $data[$row['bn']]['ca'] = $row['costclientarea'];
                } else {
                    $data[$row['bn']]['ca'] = $row['province'];
                }
                if (empty($row['purpose'])) {
                    $data[$row['bn']]['pp'] = $row['clientType'];
                } else {
                    $data[$row['bn']]['pp'] = $row['purpose'];
                }

                $cost_type[$row['ct']] = $row['cn'];
                $data_type[$row['bn']] = isset($data_type[$row['bn']]) ? $data_type[$row['bn']] + $row['sm'] : $row['sm'];
                $data_all[$row['ct']] = isset($data_all[$row['ct']]) ? $data_all[$row['ct']] + $row['sm'] : $row['sm'];
            }
        }
        if (($seatype == 'bill' || $seatype == 'all') && ($combrn == '' || $combrn == 'dl')) {
            if (empty($seabilltype)) {
                $sql = "select
                    sum( d.days*d.amount ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un
                from
                    bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                    left join user u on (l.inputman=u.user_id)
                where
                    l.status='完成'
                    $sqlStr
                    and year(l.inputdt)='$checky' and month(l.inputdt)='$checkm'
                    and dp.dept_name='$checkd'
                group by
                    l.billno , d.costtypeid ";
            } else {
                $sql = "select
                    sum( d.days*d.amount ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un
                from
                    bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                    left join user u on (l.inputman=u.user_id)
                where
                    l.status='完成'
                    $sqlStr
                    and year(l.inputdt)='$checky' and month(l.inputdt)='$checkm'
                    and dp.dept_name='$checkd'
                    and d.billtypeid='" . $seabilltype . "'
                group by
                    l.billno , d.costtypeid ";
            }

            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['bn']][$row['ct']] = $row['sm'];
                $data[$row['bn']]['un'] = $row['un'];
                $cost_type[$row['ct']] = $row['cn'];
                $data_type[$row['bn']] = isset($data_type[$row['bn']]) ? $data_type[$row['bn']] + $row['sm'] : $row['sm'];
                $data_all[$row['ct']] = isset($data_all[$row['ct']]) ? $data_all[$row['ct']] + $row['sm'] : $row['sm'];
            }
        }

        $res = '';
        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>地区</td>
            <td>第一审批人</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . $val . '</td>';
        }
        $res .= '<td >小计</td>';
        $res .= '</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            $sql = "SELECT  smallid , u.user_name   FROM flow_step_partent  s 
                  left join wf_task w on (s.wf_task_id=w.task)
                  left join user u on (u.user_id=s.user )
                  where ( w.name = '" . $key . "' or w.objcode='" . $key . "' ) and smallid=1 ";
            $query = $this->db->get_one($sql);
            if ($i % 2 == 0) {
                $res .= '<tr style="background: #F3F3F3;">';
            } else {
                $res .= '<tr style="background: #FFFFFF;">';
            }
            $res .= '<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['ca'] . '</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $query['user_name'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res .= '<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res .= '<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res .= '</tr>';
            $i++;
        }
        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="4">小计：</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . num_to_maney_format($data_all[$key]) . '</td>';
        }
        $res .= '<td>' . num_to_maney_format(array_sum($data_all)) . '</td>
            </tr>';
        return $res;
    }

    function model_dept_detail_sea()
    {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype'] : '';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype'] : '';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $res .= '<select name="seay" >';
        for ($i = 2008; $i <= $nowy; $i++) {
            if ($checky == $i) {
                $res .= '<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res .= '<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res .= '</select> 年 ';
        //开始月
        $res .= '<select name="seam" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkm == $i) {
                $res .= '<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res .= '<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res .= '</select> 月 ';
        $res .= ' 部门 <input type="seabill" name="seabill" size="18" value="' . $checkbill . '" />';
        $res .= ' 报销人 <input type="seabill" name="seauser" size="18" value="' . $checkuser . '" />';
        $res .= ' <input type="hidden" name="sead" value="' . $checkd . '" /> ';
        $res .= ' <input type="hidden" name="seat" value="' . $checkt . '" /> ';
        $res .= ' <input type="hidden" name="seatype" value="' . $seatype . '" /> ';
        $res .= ' <input type="hidden" name="seabilltype" value="' . $seabilltype . '" /> ';
        return $res;
    }

    function model_dept_detail_excel()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_detail()) . '
            </table>';
    }

    function model_dept_detail_excel_tmp()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_detail_tmp()) . '
            </table>';
    }

    /**
     * 分公司费用统计
     */
    function model_cost_com_list()
    {
        $info = array();
        $total = array();
        $billtal = 0;
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $sqlStr = '';
        if ($checkbill) {
            $sqlStr .= " and d.dept_name like '%$checkbill%' ";
        }
        if ($checkuser) {
            $sqlStr .= " and u.user_name like '%$checkuser%' ";
        }
        if ($checky) {
            $sqlStr .= " and year(p.paydt) = '$checky' ";
        }
        if ($checkm) {
            $sqlStr .= " and month(p.paydt) = '$checkm' ";
        }
        $sql = "select d.dept_name , u.user_name , p.paymoney , p.billnos , p.paycom
            from cost_pay p
                left join user u on (p.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where 1 $sqlStr
            order by billnos ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $info[$row['billnos']]['dept'] = $row['dept_name'];
            $info[$row['billnos']]['name'] = $row['user_name'];
            $info[$row['billnos']][$row['paycom']] = $row['paymoney'];
            $total[$row['paycom']] = empty ($total[$row['paycom']]) ? $row['paymoney'] : $total[$row['paycom']] + $row['paymoney'];
        }
        $sql = "select l.billnos , sum(l.money) as sjlr
            from loan_repayment l
                left join cost_pay p on (p.billnos=l.billnos)
                left join user u on (p.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where l.way='0' $sqlStr
            group by l.billnos ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $info[$row['billnos']]['sjlr'] = $row['sjlr'];
            $total['sjlr'] = empty ($total['sjlr']) ? $row['sjlr'] : $total['sjlr'] + $row['sjlr'];
        }
        if (!empty ($info)) {
            $i = 0;
            foreach ($info as $key => $val) {
                $tmp = array();
                if (strpos($key, "','") !== false) {
                    $sql = "select billno , amount from cost_summary_list l where l.billno in ($key) ";
                    $query = $this->db->query($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        $tmp[$row['billno']] = $row['amount'];
                    }
                } else {
                    $tmp[str_replace("'", '', $key)] = $val['世纪'] + $val['鼎利'] + $val['sjlr'];
                }
                $billtal = array_sum($tmp) + $billtal;
                $tmpc = count($tmp);
                $y = 0;
                foreach ($tmp as $tkey => $tval) {
                    if ($i % 2 == 0) {
                        $res .= '<tr style="background: #F3F3F3;">';
                    } else {
                        $res .= '<tr style="background: #FFFFFF;">';
                    }
                    if ($y == 0) {
                        $res .= '<td nowrap style="text-align:center;height:20px;" rowspan="' . $tmpc . '">' . $i . '</td>';
                        $res .= '<td nowrap style="text-align:center" rowspan="' . $tmpc . '"> ' . $val['dept'] . '</td>';
                        $res .= '<td nowrap style="text-align:center" rowspan="' . $tmpc . '"> ' . $val['name'] . '</td>';
                        $res .= '<td rowspan="' . $tmpc . '">' . num_to_maney_format($val['世纪']) . '</td>';
                        $res .= '<td rowspan="' . $tmpc . '">' . num_to_maney_format($val['sjlr']) . '</td>';
                        $res .= '<td rowspan="' . $tmpc . '">' . num_to_maney_format($val['鼎利']) . '</td>';
                        $res .= '<td nowrap style="text-align:center" >&nbsp;' . $tkey . '</td>';
                        $res .= '<td >' . num_to_maney_format($tval) . '</td>';
                    } else {
                        $res .= '<td nowrap style="text-align:center" >&nbsp;' . $tkey . '</td>';
                        $res .= '<td >' . num_to_maney_format($tval) . '</td>';
                    }
                    $res .= '</tr>';
                    $y++;
                }
                $i++;
            }
            $res .= '<tr style="background: #FFFFFF;">';
            $res .= '<td nowrap style="text-align:center;height:20px;" ><font color="red">合计</font></td>';
            $res .= '<td nowrap style="text-align:center"> </td>';
            $res .= '<td nowrap style="text-align:center"> </td>';
            $res .= '<td >' . num_to_maney_format($total['世纪']) . '</td>';
            $res .= '<td >' . num_to_maney_format($total['sjlr']) . '</td>';
            $res .= '<td >' . num_to_maney_format($total['鼎利']) . '</td>';
            $res .= '<td > </td>';
            $res .= '<td > ' . num_to_maney_format($billtal) . '</td>';
            $res .= '</tr>';
        }
        return $res;
    }

    function model_cost_com_excel()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        $str = '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" cellpadding="0" cellspacing="0" border="1" >
                <tr style="background: #FFFFFF;">
                    <td align="center" height="30" rowspan="2" width="10%">序号</td>
                    <td align="center" rowspan="2">部门</td>
                    <td align="center" rowspan="2">姓名</td>
                    <td align="center" colspan="2">世纪</td>
                    <td align="center" rowspan="1">鼎利</td>
                    <td align="center" rowspan="2">报销单</td>
                    <td align="center" rowspan="2">金额</td>
                </tr>
                <tr style="background: #FFFFFF;">
                    <td align="center" height="30" >支付</td>
                    <td align="center" >还款</td>
                    <td align="center" >支付</td>
                </tr>
                ' . ($this->model_cost_com_list()) . '
            </table>';
        echo un_iconv($str);
    }

    function model_dept_other()
    {
        $seadept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $seauser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seacd = isset($_POST['seacd']) ? $_POST['seacd'] : '';
        $seadtb = isset($_POST['seadtb']) ? $_POST['seadtb'] : date('Y-m') . '-01';
        $seadte = isset($_POST['seadte']) ? $_POST['seadte'] : date('Y-m-d');
        if (!empty($seadept)) {
            $sqlStr .= " and d.dept_name like '%" . $seadept . "%'";
        }
        if (!empty($seauser)) {
            $sqlStr .= " and u.user_name like '%" . $seauser . "%'";
        }
        if (!empty($seacd)) {
            $sqlStr .= " and l.costbelongtodeptids like '%" . $seacd . "%'";
        }
        $data = array();
        $sql = "select
                IF(l.isproject = '1','工程项目',l.costbelongtodeptids ) as cd
                , d.dept_name , u.user_name
                , l.billno , l.amount
                , u.user_id
            from
                cost_summary_list l
                left join user u on (u.user_id=l.costman)
                left join department d on (l.costdepartid=d.dept_id)
            where
                ( ( l.costbelongtodeptids != d.dept_name and l.isproject='0' )
                 or ( l.isproject = '1' and d.dept_name <>'网络服务部' )   )
                and to_days(l.paydt)>= to_days('" . $seadtb . "')
                and to_days(l.paydt)<= to_days('" . $seadte . "')
                and l.status='完成'
                and u.company = '" . $_SESSION['USER_COM'] . "'
                $sqlStr
                ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['user_id']]['name'] = $row['user_name'];
            $data[$row['user_id']]['dept'] = $row['dept_name'];
            $data[$row['user_id']]['bill'][$row['billno']]['am'] = $row['amount'];
            $data[$row['user_id']]['bill'][$row['billno']]['cd'] = $row['cd'];
        }
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $key => $val) {
                $y = 0;
                foreach ($val['bill'] as $vkey => $vval) {
                    $cb = count($val['bill']);
                    $i++;
                    $y++;
                    if ($i % 2 == 0) {
                        $res .= '<tr style="background: #F3F3F3;">';
                    } else {
                        $res .= '<tr style="background: #FFFFFF;">';
                    }
                    $res .= '<td nowrap style="text-align:center; height:20px;" > ' . $val['name'] . '</td>';
                    $res .= '<td nowrap style="text-align:center" > ' . $val['dept'] . '</td>';
                    $res .= '<td style="text-align:center">' . $vval['cd'] . '</td>';
                    $res .= '<td style="text-align:center">' . $vkey . '</td>';
                    $res .= '<td style="padding-right:5px;">' . num_to_maney_format($vval['am']) . '</td>';
                    $res .= '</tr>';
                }
            }
        }
        $res = '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td>员工</td>
                <td>部门</td>
                <td>费用归属部门</td>
                <td>单号</td>
                <td>金额</td>
              </tr>' . $res;
        return $res;
    }

    function model_dept_other_xls()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        $str = '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" cellpadding="0" cellspacing="0" border="1" >               
                ' . ($this->model_dept_other()) . '
            </table>';
        echo un_iconv($str);
    }

    function model_bill_type($slt)
    {
        $sql = "select id as id , name as name , parentid as pid
            from bill_type
            where parentid<>0 ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $carr[$row['id']] = $row['name'];
        }
        $res = '';
        foreach ($carr as $key => $val) {
            if ($slt == $key) {
                $res .= '<option value="' . $key . '" selected>' . $val . '</option>';
            } else {
                $res .= '<option value="' . $key . '" >' . $val . '</option>';
            }
        }
        return $res;
    }

    /**
     *研发项目查询
     * @return string
     */
    function model_dept_detail_dev()
    {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype'] : 'cost';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype'] : '';
        $data = array();
        $cost_type = array();
        $data_type = array();
        $data_all = array();
        $sqlStr = '';
        if ($checkbill) {
            //$sqlStr.=" and l.billno like '%$checkbill%' ";
        }
        if ($checkuser) {
            //$sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        if (!empty($checkt)) {
            //$sqlStr.=" and d.costtypeid ='$checkt' ";
        }
        $sql = "select
            sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn 
            , u.user_name as un , l.projectno as pn , dp.dept_name as dp
        from
            cost_detail d
            left join cost_type t on (d.costtypeid=t.costtypeid)
            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
            left join cost_summary_list  l on (l.billno=d.billno)
            left join user u on (l.costman=u.user_id)
            left join department dp on (u.dept_id=dp.dept_id)
        where
            l.status='完成'
            and a.billno=d.billno
            and u.company = '" . $_SESSION['USER_COM'] . "'
            $sqlStr
            and ( l.projectno like '%统一数据管理平台%' or l.projectno like '%统一分析平台%' or l.projectno like '%统一数据集%'  )
        group by
            l.billno , d.costtypeid
        order by l.projectno ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['bn']][$row['ct']] = $row['sm'];
            $data[$row['bn']]['un'] = $row['un'];
            $data[$row['bn']]['pn'] = $row['pn'];
            $data[$row['bn']]['dp'] = $row['dp'];
            $cost_type[$row['ct']] = $row['cn'];
            $data_type[$row['bn']] = isset($data_type[$row['bn']]) ? $data_type[$row['bn']] + $row['sm'] : $row['sm'];
            $data_all[$row['ct']] = isset($data_all[$row['ct']]) ? $data_all[$row['ct']] + $row['sm'] : $row['sm'];
        }
        $res = '';
        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">项目</td>
            <td>报销人</td>
            <td>报销单</td>
            <td>部门</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . $val . '</td>';
        }
        $res .= '<td>小计</td>';
        $res .= '</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res .= '<tr style="background: #F3F3F3;">';
            } else {
                $res .= '<tr style="background: #FFFFFF;">';
            }
            $res .= '<td nowrap style="text-align:center;height:20px;">&nbsp;' . $val['pn'] . '&nbsp;</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $key . '</td>';
            $res .= '<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res .= '<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res .= '<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res .= '</tr>';
            $i++;
        }
        $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="4">小计：</td>';
        foreach ($cost_type as $key => $val) {
            $res .= '<td>' . num_to_maney_format($data_all[$key]) . '</td>';
        }
        $res .= '<td>' . num_to_maney_format(array_sum($data_all)) . '</td>
            </tr>';
        return $res;
    }

    function model_dept_stat()
    {
        global $func_limit;
        $checkm = date('Y-m-d');
        $checkme = date('Y-m-d');

        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $checkm;
        $checkme = isset($_REQUEST['seame']) ? $_REQUEST['seame'] : $checkme;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '0';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $deprtid = isset($_POST['deprtid']) ? $_POST['deprtid'] : $_GET['deprtid'];
        $details = isset($_POST['details']) ? $_POST['details'] : $_GET['details'];
        $statuses = isset($_POST['statuses']) ? $_POST['statuses'] : $_GET['statuses'];
        $isProject = isset($_POST['isProject']) ? $_POST['isProject'] : $_GET['isProject'];
        $dateStatus = isset($_POST['dateStatus']) ? $_POST['dateStatus'] : $_GET['dateStatus'];
        if ($func_limit['浏览部门']) {
            $sead = $func_limit['浏览部门'] . ',' . $_SESSION["DEPT_ID"];
        } else {
            $sead = $_SESSION["DEPT_ID"];
        }

        if ($deprtid && $deprtid != 0) {
            $dept = new model_system_dept();
            $son_id = $dept->GetSon_ID($deprtid);
            if ($son_id && is_array($son_id)) {
                $deprtid = $deprtid . "," . implode(",", (array)$son_id);
            }
        } else {
            $deprtid = $sead;
        }
        $data = array();
        $cost_type = array();
        $data_type = array();
        $data_all = array();
        $sqlStr = '';
        if (empty($dateStatus)) {
            $dateStatus = 1;
        }
        if ($dateStatus == 1) {
            $sqlStr .= " and to_days(a.CostDateBegin)>=to_days('$checkm') and to_days(a.CostDateBegin)<=to_days('$checkme') ";
        } elseif ($dateStatus == 2) {
            $sqlStr .= " and to_days(l.PayDT)>=to_days('$checkm') and to_days(l.PayDT)<=to_days('$checkme') ";
        }
        if ($checkuser) {
            $sqlStr .= " and u.user_name like '%$checkuser%' ";
        }
        if ($statuses) {
            $sqlStr .= " and l.status='$statuses' ";
        }
        if ($details == 1) {
            if ($_SESSION['COM_BRN_PT'] == 'bx' && $isProject == 1) {
                $sql = "	(SELECT
					             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
					            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
					            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
						FROM
							            cost_detail d
							            left join cost_type t on (d.costtypeid=t.costtypeid)
							            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
							            left join cost_summary_list  l on (l.billno=d.billno)
							            left join department dp on (l.costbelongtodeptids=dp.dept_name)
							            left join user u on (l.costman=u.user_id)
							            left join department dpu on (dpu.dept_id=u.dept_id)
					    WHERE
					            u.company = '" . $_SESSION['USER_COM'] . "'
					            and a.billno=d.billno
					            $sqlStr
					            and dp.dept_id in (" . $deprtid . ")
					    GROUP BY  d.ID,l.CostMan
								 ORDER BY  l.isProject,a.CostDateBegin DESC,l.CostMan,t.CostTypeID
					    )UNION(
					    SELECT
							             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
							            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
							            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
					    FROM
							            cost_detail_project d
							            LEFT JOIN cost_type t on (d.costtypeid=t.costtypeid)
							            LEFT JOIN cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
							            LEFT JOIN cost_summary_list  l on (l.billno=d.billno)
													LEFT JOIN xm ON  xm.ProjectNo=l.ProjectNo 
							            LEFT JOIN department dp on (xm.deptId=dp.DEPT_ID)
							            LEFT JOIN user u on (l.costman=u.user_id)
							            LEFT JOIN department dpu on (dpu.dept_id=u.dept_id)
													
					     WHERE
								u.company = '" . $_SESSION['USER_COM'] . "'
					            and a.billno=d.billno
					            $sqlStr
					            and xm.deptId in (" . $deprtid . ")
					     GROUP BY  d.ID,l.CostMan
						 ORDER BY l.isProject,a.CostDateBegin DESC,l.CostMan,t.CostTypeID
					       )";
            } else {
                $sql = "select
		             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		        where
		            u.company = '" . $_SESSION['USER_COM'] . "'
		            and a.billno=d.billno
		            $sqlStr
		            and dp.dept_id in (" . $deprtid . ")
		            group by
			            d.ID,l.CostMan
			        ORDER BY  a.CostDateBegin DESC,l.CostMan,t.CostTypeID";

            }
            $query = $this->db->query($sql);
            $cat = 0;
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['id']]['bn'] = $row['bn'];
                $data[$row['id']]['un'] = $row['un'];
                $data[$row['id']]['sm'] = $row['sm'];
                $data[$row['id']]['reson'] = $row['costclienttype'];
                $data[$row['id']]['dts'] = $row['costdates'];
                $data[$row['id']]['dpu'] = $row['dpu'];
                $data[$row['id']]['dp'] = $row['dp'];
                $data[$row['id']]['pro'] = $row['projectno'];
                $data[$row['id']]['bet'] = $row['CostDateBegin'];
                $data[$row['id']]['cn'] = $row['cn'];
                $data[$row['id']]['nt'] = $row['Note'];
                $data[$row['id']]['pc'] = $row['Place'];
                $data[$row['id']]['sts'] = $row['status'];
                $cat = isset($row['sm']) ? $row['sm'] + $cat : $cat;
                //$data[$row['id']]['ct']=isset($data_all[$row['id']]['ct'])?$data_all[$row['id']['ct']]+$row['sm']:$row['sm'];
            }
            $res = '';
            $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>报销人部门</td>
            <td>费用归属部门</td>
            <td>报销时间</td>
            <td>金额</td>
            <td>类型</td>
            <td>项目信息</td>
            <td>事由</td>
            <td>摘要</td>
            <td>地点</td>
            <td>状态</td>
          ';
            foreach ($cost_type as $key => $val) {
                $res .= '<td>' . $val . '</td>';
            }
            $res .= '</tr>';
            $i = 0;
            foreach ($data as $key => $val) {
                if ($i % 2 == 0) {
                    $res .= '<tr style="background: #F3F3F3;">';
                } else {
                    $res .= '<tr style="background: #FFFFFF;">';
                }
                $res .= '<td nowrap style="text-align:center;height:20px;">&nbsp;' . $val['bn'] . '&nbsp;</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['dpu'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
                //$res.='<td nowrap style="text-align:center"> ' . $val['dts'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['bet'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['sm'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['cn'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['pro'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['reson'] . '</td>';

                $res .= '<td nowrap style="text-align:center"> ' . $val['nt'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['pc'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['sts'] . '</td>';
                $res .= '</tr>';
                $i++;
            }
            $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="6">小计：</td>';

            $res .= '<td>' . num_to_maney_format($cat) . '</td><td colspan="5"></td>
            </tr>';

        } else {
            if ($_SESSION['COM_BRN_PT'] == 'bx') {
                if ($isProject == 1) {
                    $sql = "(SELECT
				                sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
				            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
				            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
				        FROM
				            cost_detail d
				            left join cost_type t on (d.costtypeid=t.costtypeid)
				            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
				            left join cost_summary_list  l on (l.billno=d.billno)
				            left join department dp on (l.costbelongtodeptids=dp.dept_name)
				            left join user u on (l.costman=u.user_id)
				            left join department dpu on (dpu.dept_id=u.dept_id)
				        WHERE
				            u.company = '" . $_SESSION['USER_COM'] . "'
				            and a.billno=d.billno
				            $sqlStr
				            and to_days(a.CostDateBegin)>=to_days('$checkm') and to_days(a.CostDateBegin)<=to_days('$checkme')
				            and dp.dept_id in (" . $deprtid . ")
				        group by
				            l.billno , d.costtypeid 
				        )UNION(
						SELECT
				            sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
				            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
				            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status 
				        FROM
							cost_summary_list  l 
							LEFT JOIN  cost_detail_project d  on (l.BillNo=d.BillNo)
				            LEFT JOIN cost_type t on (d.costtypeid=t.costtypeid)
				            LEFT JOIN cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
				            LEFT JOIN xm ON  xm.ProjectNo=l.ProjectNo 
				            LEFT JOIN department dp on (xm.deptId=dp.DEPT_ID)
				            LEFT JOIN user u on (l.costman=u.user_id)
				            LEFT JOIN department dpu on (dpu.dept_id=u.dept_id)
							LEFT JOIN cost_detail_list cl on (cl.HeadID=a.HeadID)
				        WHERE 
				            u.company = '" . $_SESSION['USER_COM'] . "'
				            $sqlStr
				            and xm.deptId in (" . $deprtid . ")
				        group by
				            l.billno , d.costtypeid 
				            )";

                } else {
                    $sql = "SELECT
				            sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
				            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
				            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
				        FROM
				            cost_detail d
				            left join cost_type t on (d.costtypeid=t.costtypeid)
				            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
				            left join cost_summary_list  l on (l.billno=d.billno)
				            left join department dp on (l.costbelongtodeptids=dp.dept_name)
				            left join user u on (l.costman=u.user_id)
				            left join department dpu on (dpu.dept_id=u.dept_id)
				        WHERE
				            u.company = '" . $_SESSION['USER_COM'] . "'
				            and a.billno=d.billno
				            $sqlStr
				            and dp.dept_id in (" . $deprtid . ")
				        group by
				            l.billno , d.costtypeid 
				        ";

                }
            } else {
                $sql = "select
		              sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status ,bi.namecn as bi
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		            left join branch_info bi on (bi.namept=u.company)
		        where
		            a.billno=d.billno  
		            $sqlStr
		            and dp.dept_id in (" . $deprtid . ")
		        group by
		            l.billno , d.costtypeid ";
            }
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['bn']][$row['ct']] = $row['sm'];
                $data[$row['bn']]['un'] = $row['un'];
                $data[$row['bn']]['bi'] = $row['bi'];
                $data[$row['bn']]['reson'] = $row['costclienttype'];
                $data[$row['bn']]['dts'] = $row['costdates'];
                $data[$row['bn']]['dpu'] = $row['dpu'];
                $data[$row['bn']]['dp'] = $row['dp'];
                $data[$row['bn']]['pro'] = $row['projectno'];
                $data[$row['bn']]['sts'] = $row['status'];
                if ($row['CostDateBegin'] == $row['CostDateEnd']) {
                    $data[$row['bn']]['bet'] = $row['CostDateBegin'];
                } else {
                    $data[$row['bn']]['bet'] = $row['CostDateBegin'] . '~' . $row['CostDateEnd'];
                }

                $cost_type[$row['ct']] = $row['cn'];
                $data_type[$row['bn']] = isset($data_type[$row['bn']]) ? $data_type[$row['bn']] + $row['sm'] : $row['sm'];
                $data_all[$row['ct']] = isset($data_all[$row['ct']]) ? $data_all[$row['ct']] + $row['sm'] : $row['sm'];
            }
            $res = '';
            $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>公司</td>
            <td>报销人部门</td>
            <td>费用归属部门</td>
            <td>事由</td>
            <td>项目信息</td>
            <td>报销时间</td>
            <td>状态</td>
          ';
            foreach ($cost_type as $key => $val) {
                $res .= '<td>' . $val . '</td>';
            }
            $res .= '<td>小计</td>';
            $res .= '</tr>';
            $i = 0;
            foreach ($data as $key => $val) {
                if ($i % 2 == 0) {
                    $res .= '<tr style="background: #F3F3F3;">';
                } else {
                    $res .= '<tr style="background: #FFFFFF;">';
                }
                $res .= '<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['bi'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['dpu'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['reson'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['pro'] . '</td>';
                //$res.='<td nowrap style="text-align:center"> ' . $val['dts'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['bet'] . '</td>';
                $res .= '<td nowrap style="text-align:center"> ' . $val['sts'] . '</td>';
                foreach ($cost_type as $vkey => $vval) {
                    $res .= '<td>' . num_to_maney_format($val[$vkey]) . '</td>';
                }
                $res .= '<td>' . num_to_maney_format($data_type[$key]) . '</td>';
                $res .= '</tr>';
                $i++;
            }
            $res .= '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="9">小计：</td>';
            foreach ($cost_type as $key => $val) {
                $res .= '<td>' . num_to_maney_format($data_all[$key]) . '</td>';
            }
            $res .= '<td>' . num_to_maney_format(array_sum($data_all)) . '</td>
            </tr>';
        }
        return $res;
    }

    function model_dept_stat_excel()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_stat()) . '
            </table>';
    }

    function model_dept_account()
    {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_account()) . '
            </table>';
    }

    //*********************************析构函数************************************

    function model_list_deletes($id)
    {
        if ($id) {
            $this->db->query(" DELETE FROM cost_detail_import WHERE id='$id'");
            $this->db->query(" DELETE FROM cost_detail_list WHERE imId='$id'");
            $sql = "select id from cost_detail_assistant WHERE  imId='$id' ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                if ($row['id']) {
                    $this->db->query(" DELETE FROM bill_detail WHERE BillAssID='" . $row['id'] . "'");
                }
            }
            $this->db->query(" DELETE FROM cost_detail_project WHERE imId='$id'");
            $this->db->query(" DELETE FROM cost_detail_assistant WHERE imId='$id'");
            return 2;
        }
        return 1;

    }


    function model_sum()
    {


        $title = '审批统计' . date('Y-m-d');
        $data[] = array(
            '员工编号',
            '员工姓名',
            '报销总金额',
            '报销总单数',
            '报销被打回单数',
            '报销审批总金额',
            '报销审批总单数',
            '报销审批打回单数'
        );

        $sql = "SELECT userName,userAccount,userNo FROM oa_hr_personnel WHERE 
userNo IN ('03000003','03000447','03000431','01005508','000671','00001365','00240','00063','00025','00002038','00067','00233','00001721','00002127','01001866','03000031','03000147','01005460','00359','000065','00002347','00001432','000533','00001699','00020','00078','00029','01001963','03000170','03000080','03000772','00002531','000723','00071','00002185','00002234','00228','01006231','03000150','00004924','00001854','000368','00002143','00129','00002286','00068','00073','01001965','01001954','00001168','00095','03000396','00004714','00003967','00002545','00002533','00002375','00123','00109','00001545','000916','00176','000568','000449','00001632','000470','000773','000379','00033','00038','00002208','00051','000532','00027')";

        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {

            $uI[$row['userAccount']]['userNo'] = $row['userNo'];
            $uI[$row['userAccount']]['userName'] = $row['userName'];
            $pcost = "SELECT SUM(Amount) AS sm, COUNT(ID) AS sn FROM cost_summary_list WHERE CostMan='" . $row['userAccount'] . "' AND `Status` IN ('完成')";
            $rc = $this->db->get_one($pcost);
            $uI[$row['userAccount']]["报销总金额"] = $rc['sm'];
            $uI[$row['userAccount']]["报销总单数"] = $rc['sn'];
            $pcostn = "SELECT COUNT(b.task) AS sno FROM cost_summary_list a 
			 		 LEFT JOIN wf_task b ON a.ID=b.Pid AND (b.`name`='报销审批' OR b.`name`=a.BillNo) AND b.`code`='cost_summary_list' AND b.examines='no'
			         WHERE a.CostMan='" . $row['userAccount'] . "' AND a.`Status` IN ('完成') AND b.examines='no' AND b.task IS NOT NULL";
            $rcn = $this->db->get_one($pcostn);
            $uI[$row['userAccount']]["报销被打回单数"] = $rcn['sno'];

            $acost = "SELECT SUM(a.Amount) AS sm, COUNT(a.ID) AS sn
					FROM cost_summary_list a LEFT JOIN wf_task b ON a.ID=b.Pid 
					AND (b.`name`='报销审批' OR b.`name`=a.BillNo) AND b.`code`='cost_summary_list' AND b.examines='ok'
					LEFT JOIN flow_step_partent c ON c.Wf_task_ID=b.task AND c.Result='ok'
					WHERE c.`User`='" . $row['userAccount'] . "' AND a.`Status` IN ('完成') 
					AND b.examines='ok' AND c.Result='ok' AND b.task IS NOT NULL AND c.ID IS NOT NULL";
            $rw = $this->db->get_one($acost);
            $uI[$row['userAccount']]["报销审批总金额"] = $rw['sm'];
            $uI[$row['userAccount']]["报销审批总单数"] = $rw['sn'];


            $acostn = "SELECT  COUNT(b.task) AS sno
					FROM cost_summary_list a LEFT JOIN wf_task b ON a.ID=b.Pid 
					AND (b.`name`='报销审批' OR b.`name`=a.BillNo) AND b.`code`='cost_summary_list' AND b.examines='no'
					LEFT JOIN flow_step_partent c ON c.Wf_task_ID=b.task AND c.Result='no'
					WHERE c.`User`='" . $row['userAccount'] . "' AND a.`Status` IN ('完成') 
					AND b.examines='no' AND c.Result='no' AND b.task IS NOT NULL AND c.ID IS NOT NULL";
            $rwn = $this->db->get_one($acostn);
            $uI[$row['userAccount']]["报销审批打回单数"] = $rwn['sno'];

            $data[] = array(
                $uI[$row['userAccount']]['userNo'],
                $uI[$row['userAccount']]['userName'],
                round($uI[$row['userAccount']]['报销总金额'], 2),
                $uI[$row['userAccount']]['报销总单数'],
                $uI[$row['userAccount']]['报销被打回单数'],
                round($uI[$row['userAccount']]['报销审批总金额'], 2),
                $uI[$row['userAccount']]['报销审批总单数'],
                $uI[$row['userAccount']]['报销审批打回单数']
            );


        }


        $Title = array(
            array(
                $title
            )
        );

        $xls = new includes_class_excel ($title . '.xls');
        $xls->SetTitle(array(
            $title
        ), $Title);
        $xls->SetContent(array(
            $data
        ));
        $xls->objActSheet[0]->mergeCells('A1:F1');
        $xls->objActSheet[0]->getStyle('A2:F2')->getFont()->setBold(true);
        $xls->objActSheet[0]->getStyle('A1:P500')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $xls->objActSheet[0]->getColumnDimension('A')->setWidth(20);
        $xls->objActSheet[0]->getColumnDimension('B')->setWidth(20);
        $xls->objActSheet[0]->getColumnDimension('C')->setWidth(15);
        $xls->objActSheet[0]->getColumnDimension('D')->setWidth(50);
        $xls->objActSheet[0]->getColumnDimension('E')->setWidth(15);
        $xls->objActSheet[0]->getColumnDimension('F')->setWidth(15);

        $xls->OutPut();


    }
    
    function changState2Apply($id) {
        $this->db->query(" update cost_detail_import set isCreateApply = 1 WHERE id='$id'");
    }


    function __destruct()
    {

    }

}

?>