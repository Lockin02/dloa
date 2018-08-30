<?php

/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 20:39:05
 * @version 1.0
 * @description:Ӧ��������Ʊ���Ʋ� ���״̬ ExaStatus
 * 0.δ���
 * 1.�����
 */
class controller_finance_invother_invother extends controller_base_action
{

    function __construct() {
        $this->objName = "invother";
        $this->objPath = "finance_invother";
        parent::__construct();

        $this->redLimit =
            isset($this->service->this_limit['���ַ�Ʊ']) && $this->service->this_limit['���ַ�Ʊ'] == 1 ? 1 : 0;
    }

    private $redLimit;

    /**
     * ��ת��Ӧ��������Ʊ�б�
     */
    function c_page() {
        $this->view('list');
    }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		if (isset($_REQUEST['sourceType']) && $_REQUEST['sourceType'] == 'none') {
			unset($_REQUEST['sourceType']);
			$_REQUEST['sourceTypeNone'] = 1;
		}
		$service->getParam($_REQUEST);

		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

    /**
     * ��ת������Ӧ��������Ʊ�б�
     */
    function c_myList() {
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**************************�����б�ҳ��***************************/
    /**
     * �����߲�ѯ�б�
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
     * �߼�����
     */
    function c_toListInfoSearch() {
        $this->showDatadicts(array('invType' => 'FPLX'), $_GET['invType']);
        unset($_GET['invType']);

        $this->assignFunc($_GET);
        $this->display('listinfo-search');
    }
    /**************************�����б�ҳ��***************************/

    /**
     * ��ת������Ӧ��������Ʊҳ��
     */
    function c_toAdd() {
        $this->assign('thisDate', day_date);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'));
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), null, null, array('expand4No' => '1'));
        }

        //�ʼ���Ϣ��Ⱦ
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->assign('redLimit', $this->redLimit);

        $this->view('add', true);
    }

    /**
     * �������ɷ�Ʊ��¼
     */
    function c_toAddObj() {
        $thisObj = $_GET;

        //���ò���
        $newClass = $this->service->getClass($thisObj['objType']);
        $initObj = new $newClass();
        //��ȡ��Ӧҵ����Ϣ
        $rs = $this->service->getObjInfo_d($thisObj, $initObj);

        //��Ⱦ����
        $this->assignFunc($rs);

        //��Ⱦ������Ϣ
        $this->assignFunc($thisObj);

        //ҳ��������Ⱦ
        $this->assign('invTypeCN', $this->getDataNameByCode($rs['invoiceType']));
        //$this->showDatadicts(array('invType' => 'FPLX'), $rs['invoiceType']);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $rs['invoiceType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $rs['invoiceType'], null, array('expand4No' => '1'));
        }

        $this->assign('thisDate', day_date);

        //�ʼ���Ϣ��Ⱦ
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //�Ƿ������˹���
        $this->assign('verify', isset($_GET['isAudit']) && $_GET['isAudit'] ? 1 : 0);
//        $this->assign('isShare', PAYISSHARE);//�Ƿ����÷��÷�̯
        $this->assign('isShare', (isset($_GET['shareCost']) && $_GET['shareCost'] == '0')? '0' : PAYISSHARE);

        $this->assign('periodStr', $this->periodDeal());

        $this->assign('redLimit', $this->redLimit);

        $this->view($this->service->getBusinessCode($thisObj['objType']) . '-add', true);
    }

    /**
     * ��д�༭����
     */
    function c_add() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName], $_GET['act'])) {
            msgRf($this->service->getMsg_d($_GET['act']));
        }
    }

    /**
     * ��ת���༭Ӧ��������Ʊҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('invTypeCN', $this->getDataNameByCode($obj['invType']));
        // $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType']);
        $otherDataDao = new model_common_otherdatas();
        $invoiceLimit = $otherDataDao->getUserPriv('finance_invoiceapply_invoiceapply', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($invoiceLimit['��������']) && $invoiceLimit['��������'] > 0){
            $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType']);
        }else{
            $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType'], null, array('expand4No' => '1'));
        }

        $this->assign('sourceTypeCN', $this->getDataNameByCode($obj['sourceType']));
//        $this->assign('isShare', PAYISSHARE);//�Ƿ����÷��÷�̯
        $this->assign('isShare', ($obj['isShareCost'] == 'no')? '0' : PAYISSHARE);

        //�ʼ���Ϣ��Ⱦ
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);

        //�������{file}
        $this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

        $this->assign('periodStr', $this->periodDeal($obj['period']));
        
        if(!empty($obj['sourceType'])){
        	//���ò���
        	$newClass = $this->service->getClass($obj['sourceType']);
        	$initObj = new $newClass();
        	//��ȡ��Ӧҵ����Ϣ
        	$rs = $this->service->getObjInfo_d($obj, $initObj, $obj['menuNo']);
        	$this->assign('sourceId', $rs['id']);//Դ��id
        }else{
        	$this->assign('sourceId', '');//Դ��id
        }

        $this->view('edit', true);
    }

    /**
     * ��ת�����Ӧ��������Ʊҳ��
     */
    function c_toVerify() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('invTypeCN', $this->getDataNameByCode($obj['invType']));
        $this->showDatadicts(array('invType' => 'FPLX'), $obj['invType']);
        $this->assign('sourceTypeCN', $this->getDataNameByCode($obj['sourceType']));
//        $this->assign('isShare', PAYISSHARE);//�Ƿ����÷��÷�̯
        $this->assign('isShare', ($obj['isShareCost'] == 'no')? '0' : PAYISSHARE);

        //�������{file}
        $this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

        $this->assign('periodStr', $this->periodDeal($obj['period']));
        
        if(!empty($obj['sourceType'])){
        	//���ò���
        	$newClass = $this->service->getClass($obj['sourceType']);
        	$initObj = new $newClass();
        	//��ȡ��Ӧҵ����Ϣ
        	$rs = $this->service->getObjInfo_d($obj, $initObj, $obj['menuNo']);
        	$this->assign('sourceId', $rs['id']);//Դ��id
        }else{
        	$this->assign('sourceId', '');//Դ��id
        }
        
        $this->view('verify', true);
    }

    /**
     * ��ת���鿴Ӧ��������Ʊҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('invTypeCN', $this->getDataNameByCode($obj['invType']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($obj['sourceType']));

        //�������{file}
        $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

        $this->view('view');
    }

    /**
     * ��д�༭����
     */
    function c_edit() {
        $this->checkSubmit();
        if ($this->service->edit_d($_POST[$this->objName], $_GET['act'])) {
            msgRf($this->service->getMsg_d($_GET['act']));
        }
    }

    /**
     * �����
     */
    function c_unaudit() {
        echo $this->service->unaudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �ҵ�������Ʊ
     */
    function c_myInvotherListPageJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��Ʊ��¼��ʷ
     */
    function c_toHistoryForObj() {
        $this->assign('skey', $_GET['skey']);
		$this->assign('userId', $_SESSION['USER_ID']);
        $this->assignFunc($_GET['obj']);
        $this->view('viewlist');
    }

    /**
     * ����������ʷjson
     */
    function c_historyJson() {
        $service = $this->service;
        $service->setCompany(0);
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->pageBySqlId('select_history');
        if (!empty($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);
            $rsArr = array('amount' => 0, 'formAssessment' => 0, 'formCount' => 0);
            $rsArr['invoiceCode'] = 'ѡ��ϼ�';
            $rsArr['id'] = 'noId2';
            $rows[] = $rsArr;

            //�ܼ�������
            //$service->groupBy = 'd.objId,d.objType';
            $service->sort = null;
            $objArr = $service->listBySqlId('select_sum');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['invoiceCode'] = '�ϼ�';
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
     * ��ת�������ʼ�ҳ��
     */
    function c_toEmail() {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        //ʵ����һ���ʼ���
        $mailconfigDao = new model_system_mailconfig_mailconfig();
        $thisInfo = $mailconfigDao->getMailAllInfo_d('invotherBackMail', null, array('invoiceNo' => $obj['invoiceNo']));
        $this->assignFunc($thisInfo);

        $this->view('sendemail');
    }

    /**
     * �����ʼ�����
     */
    function c_email() {
        $this->service->thisMailSend_d($_POST[$this->objName]);
        echo "<script>alert('���ͳɹ���'); self.close();</script>";
    }

    /**
     * ����excel����
     */
    function c_toExportExcel() {
        $year = date("Y");
        $yearStr = "";
        while ($year >= 2010) {
            $yearStr .="<option value='$year'>" . $year . "��</option>";
            $year --;
        }
        $this->assign('year',$yearStr);

        $month = date("m");
        $monthStr = '';
        $beginMonth = 12;
        while ($beginMonth > 0) {
            $selected = $beginMonth == $month ? 'selected="selected"' : '';
            $monthStr .="<option value='$beginMonth' " . $selected . ">" . $beginMonth . "��</option>";
            $beginMonth --;
        }
        $this->assign('month',$monthStr);
        $this->view('exportExcel');
    }

    /**
     * ����excel
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
                'menuNo' => '��ͬ��', 'supplierName' => '��Ӧ��', 'invTypeCN' => '��Ʊ����', 'taxRate' => '��Ʊ˰��',
                'formCount' => '��Ʊ��˰���', 'amount' => '��Ʊ����˰���', 'hookMoney' => '�������',
                'formAssessment' => '˰��', 'periodNo' => '�������', 'businessBelongName' => '������˾'
            ), $data, '������Ʊ');
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

    /**
     * �����·ݴ���
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