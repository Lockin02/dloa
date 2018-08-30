<?php

/**
 * @author Show
 * @Date 2011年12月27日 星期二 20:39:05
 * @version 1.0
 * @description:应付其他发票控制层 审核状态 ExaStatus
 * 0.未审核
 * 1.已审核
 */
class controller_finance_invother_invother extends controller_base_action
{

    function __construct() {
        $this->objName = "invother";
        $this->objPath = "finance_invother";
        parent::__construct();

        $this->redLimit =
            isset($this->service->this_limit['红字发票']) && $this->service->this_limit['红字发票'] == 1 ? 1 : 0;
    }

    private $redLimit;

    /**
     * 跳转到应付其他发票列表
     */
    function c_page() {
        $this->view('list');
    }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		if (isset($_REQUEST['sourceType']) && $_REQUEST['sourceType'] == 'none') {
			unset($_REQUEST['sourceType']);
			$_REQUEST['sourceTypeNone'] = 1;
		}
		$service->getParam($_REQUEST);

		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

    /**
     * 跳转到个人应付其他发票列表
     */
    function c_myList() {
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**************************报表列表页面***************************/
    /**
     * 报表工具查询列表
     */
    function c_listInfo() {
        unset($_GET['action']);
        unset($_GET['model']);
        $thisObj = !empty($_GET) ? $_GET : array(
            'formDateBegin' => '', 'formDateEnd' => '', 'supplierName' => '', 'invoiceNo' => '',
            'salesmanId' => '', 'salesman' => '', 'exaManId' => '', 'ExaStatus' => '', 'invType' => '',
            'exaMan' => '', 'productName' => '');
        $this->assignFunc($thisObj);
        $this->display('listinfo');
    }

    /**
     * 高级搜索
     */
    function c_toListInfoSearch() {
        $this->showDatadicts(array('invType' => 'FPLX'), $_GET['invType']);
        unset($_GET['invType']);

        $this->assignFunc($_GET);
        $this->display('listinfo-search');
    }
    /**************************报表列表页面***************************/

    /**
     * 跳转到新增应付其他发票页面
     */
    function c_toAdd() {
        $this->assign('thisDate', day_date);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        //邮件信息渲染
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->assign('redLimit', $this->redLimit);

        $this->view('add', true);
    }

    /**
     * 下推生成发票记录
     */
    function c_toAddObj() {
        $thisObj = $_GET;

        //调用策略
        $newClass = $this->service->getClass($thisObj['objType']);
        $initObj = new $newClass();
        //获取对应业务信息
        $rs = $this->service->getObjInfo_d($thisObj, $initObj);

        //渲染主表单
        $this->assignFunc($rs);

        //渲染关联信息
        $this->assignFunc($thisObj);

        //页面其他渲染
        $this->assign('invTypeCN', $this->getDataNameByCode($rs['invoiceType']));
        //$this->showDatadicts(array('invType' => 'FPLX'), $rs['invoiceType']);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $rs['invoiceType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $rs['invoiceType'], null, array('expand4No' => '1'));
        }

        $this->assign('thisDate', day_date);

        //邮件信息渲染
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //是否包含审核功能
        $this->assign('verify', isset($_GET['isAudit']) && $_GET['isAudit'] ? 1 : 0);
//        $this->assign('isShare', PAYISSHARE);//是否启用费用分摊
        $this->assign('isShare', (isset($_GET['shareCost']) && $_GET['shareCost'] == '0')? '0' : PAYISSHARE);

        $this->assign('periodStr', $this->periodDeal());

        $this->assign('redLimit', $this->redLimit);

        $this->view($this->service->getBusinessCode($thisObj['objType']) . '-add', true);
    }

    /**
     * 重写编辑方法
     */
    function c_add() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName], $_GET['act'])) {
            msgRf($this->service->getMsg_d($_GET['act']));
        }
    }

    /**
     * 跳转到编辑应付其他发票页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('invTypeCN', $this->getDataNameByCode($obj['invType']));
        // $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType']);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['控制申请']) && $invoiceLimit['控制申请'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType'], null, array('expand4No' => '1'));
        }

        $this->assign('sourceTypeCN', $this->getDataNameByCode($obj['sourceType']));
//        $this->assign('isShare', PAYISSHARE);//是否启用费用分摊
        $this->assign('isShare', ($obj['isShareCost'] == 'no')? '0' : PAYISSHARE);

        //邮件信息渲染
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //附件添加{file}
        $this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

        $this->assign('periodStr', $this->periodDeal($obj['period']));
        
        if(!empty($obj['sourceType'])){
        	//调用策略
        	$newClass = $this->service->getClass($obj['sourceType']);
        	$initObj = new $newClass();
        	//获取对应业务信息
        	$rs = $this->service->getObjInfo_d($obj, $initObj, $obj['menuNo']);
        	$this->assign('sourceId', $rs['id']);//源单id
        }else{
        	$this->assign('sourceId', '');//源单id
        }

        $this->view('edit', true);
    }

    /**
     * 跳转到审核应付其他发票页面
     */
    function c_toVerify() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('invTypeCN', $this->getDataNameByCode($obj['invType']));
        $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType']);
        $this->assign('sourceTypeCN', $this->getDataNameByCode($obj['sourceType']));
//        $this->assign('isShare', PAYISSHARE);//是否启用费用分摊
        $this->assign('isShare', ($obj['isShareCost'] == 'no')? '0' : PAYISSHARE);

        //附件添加{file}
        $this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

        $this->assign('periodStr', $this->periodDeal($obj['period']));
        
        if(!empty($obj['sourceType'])){
        	//调用策略
        	$newClass = $this->service->getClass($obj['sourceType']);
        	$initObj = new $newClass();
        	//获取对应业务信息
        	$rs = $this->service->getObjInfo_d($obj, $initObj, $obj['menuNo']);
        	$this->assign('sourceId', $rs['id']);//源单id
        }else{
        	$this->assign('sourceId', '');//源单id
        }
        
        $this->view('verify', true);
    }

    /**
     * 跳转到查看应付其他发票页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('invTypeCN', $this->getDataNameByCode($obj['invType']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($obj['sourceType']));

        //附件添加{file}
        $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

        $this->view('view');
    }

    /**
     * 重写编辑方法
     */
    function c_edit() {
        $this->checkSubmit();
        if ($this->service->edit_d($_POST[$this->objName], $_GET['act'])) {
            msgRf($this->service->getMsg_d($_GET['act']));
        }
    }

    /**
     * 反审核
     */
    function c_unaudit() {
        echo $this->service->unaudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 我的其他发票
     */
    function c_myInvotherListPageJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $rows = $service->page_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 收票记录历史
     */
    function c_toHistoryForObj() {
        $this->assign('skey', $_GET['skey']);
		$this->assign('userId', $_SESSION['USER_ID']);
        $this->assignFunc($_GET['obj']);
        $this->view('viewlist');
    }

    /**
     * 付款申请历史json
     */
    function c_historyJson() {
        $service = $this->service;
        $service->setCompany(0);
        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->pageBySqlId('select_history');
        if (!empty($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);
            $rsArr = array('amount' => 0, 'formAssessment' => 0, 'formCount' => 0);
            $rsArr['invoiceCode'] = '选择合计';
            $rsArr['id'] = 'noId2';
            $rows[] = $rsArr;

            //总计栏加载
            //$service->groupBy = 'd.objId,d.objType';
            $service->sort = null;
            $objArr = $service->listBySqlId('select_sum');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['invoiceCode'] = '合计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到发送邮件页面
     */
    function c_toEmail() {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        //实例化一个邮件类
        $mailconfigDao = new model_system_mailconfig_mailconfig();
        $thisInfo = $mailconfigDao->getMailAllInfo_d('invotherBackMail', null, array('invoiceNo' => $obj['invoiceNo']));
        $this->assignFunc($thisInfo);

        $this->view('sendemail');
    }

    /**
     * 发送邮件方法
     */
    function c_email() {
        $this->service->thisMailSend_d($_POST[$this->objName]);
        echo "<script>alert('发送成功！'); self.close();</script>";
    }

    /**
     * 导出excel界面
     */
    function c_toExportExcel() {
        $year = date("Y");
        $yearStr = "";
        while ($year >= 2010) {
            $yearStr .="<option value='$year'>" . $year . "年</option>";
            $year --;
        }
        $this->assign('year',$yearStr);

        $month = date("m");
        $monthStr = '';
        $beginMonth = 12;
        while ($beginMonth > 0) {
            $selected = $beginMonth == $month ? 'selected="selected"' : '';
            $monthStr .="<option value='$beginMonth' " . $selected . ">" . $beginMonth . "月</option>";
            $beginMonth --;
        }
        $this->assign('month',$monthStr);
        $this->view('exportExcel');
    }

    /**
     * 导出excel
     */
    function c_exportExcel() {
        $this->service->getParam($_GET);
        $this->service->asc = false;
        $data = $this->service->list_d();
        if ($data) {
            $dataArr = $this->getDatadicts(array('FPLX'));
            $newDataArr = array();
            foreach ($dataArr['FPLX'] as $k => $v) {
                $newDataArr[$v['dataCode']] = $v;
            }
            unset($dataArr);
            foreach ($data as $k => $v) {
                $data[$k]['periodNo'] = date('Y.n', strtotime($v['ExaDT']));
                $data[$k]['invTypeCN'] = $newDataArr[$data[$k]['invType']]['dataName'];
            }
            model_finance_common_financeExcelUtil::export2ExcelUtil(array(
                'menuNo' => '合同号', 'supplierName' => '供应商', 'invTypeCN' => '发票类型', 'taxRate' => '发票税点',
                'formCount' => '发票含税金额', 'amount' => '发票不含税金额', 'hookMoney' => '勾稽金额',
                'formAssessment' => '税额', 'periodNo' => '审核账期', 'businessBelongName' => '归属公司'
            ), $data, '其他发票');
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

    /**
     * 归属月份处理
     * @param string $default
     * @return string
     */
    function periodDeal($default = '') {
        $period = $default ? explode('.', $default) : array();
        $defaultYear = empty($period) ? '' : $period[0];
        $defaultMonth = empty($period) ? '' : $period[1];

        $periodStr = "<select id='yearSelector' class='select' style='width:95px;'><option></option>";
        for ($i = 2016; $i <= 2020; $i++) {
            if ($i == $defaultYear) {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '">' . $i . '</option>';
            }
        }
        $periodStr .= "</select> . ";

        $periodStr .= "<select id='monthSelector' class='select' style='width:95px;'><option></option>";
        for ($i = 1; $i <= 12; $i++) {
            if ($i == $defaultMonth) {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '">' . $i . '</option>';
            }
        }
        $periodStr .= "</select>";
        $periodStr .= "<input type='hidden' id='period' name='invother[period]' value='" . $default . "'>";
        $periodStr .=<<<E
            <script type='text/javascript'>
                $(function() {
                    var changePeriod = function() {
                        var year = $("#yearSelector").val();
                        var month = $("#monthSelector").val();
                        if (year != "" && month != "") {
                            $("#period").val(year + '.' + month);
                        } else {
                            $("#period").val('');
                        }
                    }

                    $("#yearSelector").bind('change', changePeriod);
                    $("#monthSelector").bind('change', changePeriod);
                });
            </script>
E;
        return $periodStr;
    }
}