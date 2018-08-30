<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author Administrator
 * @Date 2011��5��9�� 15:19:33
 * @version 1.0
 * @description:�����ÿ��Ʋ�
 */
class controller_projectmanagent_borrow_borrow extends controller_base_action
{

    function __construct() {
        $this->objName = "borrow";
        $this->objPath = "projectmanagent_borrow";
        parent::__construct();
    }

    /**
     * ��ת������ҳ��
     */
    function c_toAdd() {
        $this->assign('borrowInput', BORROW_INPUT);
        $chanceId = isset($_GET['id']) ? $_GET['id'] : null;
        if ($chanceId) {
            $this->permCheck($chanceId, 'projectmanagent_chance_chance');
            $chanceDao = new model_projectmanagent_chance_chance();
            $rows = $chanceDao->get_d($chanceId);
            //����license����
            $licenseDao = new model_yxlicense_license_tempKey();
            $rows = $licenseDao->copyLicense($rows);

            foreach ($rows as $key => $val) {
                $this->assign($key, $val);
            }
            //��������Ҳ Դ������
            $chanceType = "chance";
        }
		//��ȡ���������ۼƽ�����ʽ��Ϊǧ��λ
		$rs = $this->service->getPersonalEquMoney($_SESSION['USERNAME']);
		$equMoney = number_format($rs[0]['equMoney'],2);

		$this->assign('equMoney', $equMoney);
		$this->assign('chanceId', $chanceId);
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->assign('salesName', $_SESSION['USERNAME']);
        $this->assign('salesNameId', $_SESSION['USER_ID']);
        $this->assign('createTime', day_date);
        $this->assign('customerName', isset($rows['customerName']) ? $rows['customerName'] : null);
        $this->assign('customerId', isset($rows['customerId']) ? $rows['customerId'] : null);
        $this->assign('customerType', isset($rows['customerType']) ? $rows['customerType'] : null);
        $this->assign('SingleType', isset($chanceType) ? $chanceType : null);
        /*************�̻����ƽ�����������Ϣ*******************/
        $this->assign('chanceCode', isset($rows['chanceCode']) ? $rows['chanceCode'] : null);
        $this->assign('chanceId', isset($rows['id']) ? $rows['id'] : null);
        /***************************************************/
        $this->view('add');
    }

    /*
	 * ��ת��������
	 */
    function c_toBorrowList() {
        $this->assign('returnLimit', $this->service->this_limit['�豸�黹'] ? 1 : 0);
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_borrowGridJson() {
        $service = $this->service;

        if(util_jsonUtil::iconvUTF2GB($_REQUEST['ExaStatus']) == "���������"){
            $_REQUEST['changingExaStatus'] = "���������";
            unset($_REQUEST['ExaStatus']);
        }

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $privlimit = isset ($sysLimit ['����']) ? $sysLimit ['����'] : null;
        if (!empty($privlimit)) {
            $service->searchArr['mySearchCondition'] = "sql: and ( u.DEPT_ID in(".$privlimit.") or c.createId='".$_SESSION['USER_ID']."')";
        } else {
            $service->searchArr['createId'] = $_SESSION['USER_ID'];
        }

        //������Ȩ������
//		$limit = $this->initLimit();
        $limit = true;
        if ($limit == true) {
            //$service->asc = false;
            $rows = $service->page_d();
            //�黹״̬
            foreach ($rows as $k => $v) {
                $backStatus = $service->backStatus($v['id']);
                $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
            }
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $_SESSION['kjy_borrowJsonSql'] = $service->listSql;
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    function c_ajaxChkExportLimit(){
//        echo "<pre>";print_r($this->service->this_limit);exit();
        if(isset($this->service->this_limit['����Ȩ��']) && $this->service->this_limit['����Ȩ��'] == 1){
            echo 1;
        }else{
            echo 0;
        }
    }

    function c_exportExcel(){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $colIdStr = isset($_GET['colId'])? $_GET['colId'] :'';
        $colNameStr = isset($_GET['colName'])? $_GET['colName'] :'';

        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);

        $sql = isset($_SESSION['kjy_borrowJsonSql'])? $_SESSION['kjy_borrowJsonSql'] : '';
        $dataArr = $this->service->_db->getArray($sql);

        foreach ($dataArr as $k => $v){
            $dataArr[$k]['checkFile'] = ($v['checkFile'] == '��')? '��' : '��';

            // ����״̬ת��
            switch($v['DeliveryStatus']){
                case 'WFH':
                    $dataArr[$k]['DeliveryStatus'] = 'δ����';
                    break;
                case 'YFH':
                    $dataArr[$k]['DeliveryStatus'] = '�ѷ���';
                    break;
                case 'BFFH':
                    $dataArr[$k]['DeliveryStatus'] = '���ַ���';
                    break;
                case 'TZFH':
                    $dataArr[$k]['DeliveryStatus'] = 'ֹͣ����';
                    break;
            }

            // �黹״̬ת��
            switch($v['backStatusCode']){
                case '0':
                    $dataArr[$k]['backStatus'] ='δ�黹';
                    break;
                case '1':
                    $dataArr[$k]['backStatus'] ='�ѹ黹';
                    break;
                case '2':
                    $dataArr[$k]['backStatus'] ='���ֹ黹';
                    break;
            }
        }

        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr, "�ͻ������õ���");
    }

    /**
     * Ȩ������
     * Ȩ�޷��ؽ������:
     * �������Ȩ�ޣ�����true
     * �����Ȩ��,����false
     */
    function initLimit() {
        $service = $this->service;
        //Ȩ����������
        $limitConfigArr = array(
            'customerTypeLimit' => 'customerType'
        );
        //Ȩ������
        $limitArr = array();
        //Ȩ��ϵͳ
        if (isset ($this->service->this_limit['�ͻ�����']) && !empty ($this->service->this_limit['�ͻ�����']))
            $limitArr['customerTypeLimit'] = $this->service->this_limit['�ͻ�����'];
        if (strstr($limitArr['customerTypeLimit'], ';;')) {
            return true;
        } else {
            if (empty ($limitArr)) {
                return false;
            } else {
                //���û��Ȩ��
                $i = 0;
                $sqlStr = "sql:and ( ";
                $k = 0;
                foreach ($limitArr as $key => $val) {
                    $arr = explode(',', $val);
                    if (is_array($arr)) {
                        $val = "";
                        foreach ($arr as $v) {
                            $val .= "'" . $v . "',";
                        }
                        $val = substr($val, 0, -1);
                    }
                    if ($i == 0) {
                        $sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
                    } else {
                        $sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
                    }
                    $i++;
                }
                $sqlStr .= ")";
                if (empty($limitArr)) {
                    $sqlStr = "";
                }
                if (!empty($goodsLimitStr)) {
                    $sqlStr .= $goodsLimitStr;
                }
                $service->searchArr['mySearchCondition'] = $sqlStr;
                return true;
            }
        }
    }

    /**
     * ��תԱ�����˽�����-tab
     */
    function c_toProBorrowListTab() {
    	$this->display('proborrowlist-tab');
    }

    /**
     * ��תԱ�����˽�����
     */
    function c_toProBorrowList() {
        $this->assign('returnLimit', $this->service->this_limit['�豸�黹'] ? 1 : 0);
        $this->display('proborrowlist');
    }

    /**
     * ��תԱ�����˽������豸�嵥
     */
    function c_toProBorrowEquList() {
    	$this->display('proborrowequlist');
    }

    /**
     * Ա������������ҳ��
     */
    function c_toProAdd() {
        $this->assign('borrowInput', BORROW_INPUT);
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('createId', $_SESSION['USER_ID']);

        $dept = new model_common_otherdatas();
        $this->assign('createSection', $dept->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'));
        $this->assign('createSectionId', $_SESSION['DEPT_ID']);
        //�ж�Ա���������Ƿ���Ҫ�߷�������
        $isProShipcondition = isproShipcondition;
        $isProShipcondition = explode(",", $isProShipcondition);
        if (in_array($_SESSION['DEPT_ID'], $isProShipcondition)) {
            $this->assign('isShipTip', "1");
        }
        $this->assign('createTime', day_date);

        //��ȡĬ�Ϸ�����
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('tostorageName', $mailUser['oa_borrow_borrow']['tostorageName']);
        $this->assign('tostorageNameId', $mailUser['oa_borrow_borrow']['tostorageNameId']);
        $this->view('proadd');
    }

    /**
     * Ա�������û����б�
     */
    function c_toProBorrowAll() {
        $this->assign('returnLimit', $this->service->this_limit['�豸�黹'] ? 1 : 0);
        $this->display('proborrowall');
    }

    /**
     * Ա��������---�ֹ�ȷ���б�
     */
    function c_toStorage() {
        $this->display('tostorage');
    }

    /**
     * �ҵ����� - tab
     */
    function c_auditTab() {
        $this->display('audittab');
    }

    /**
     * �ҵ����� �� δ����ҳ��
     */
    function c_toAuditNo() {
        $this->display('auditno');
    }

    /**
     * �ҵ����� �� ��������ҳ��
     */
    function c_toAuditYes() {
        $this->display('audityes');
    }

    /**
     * �ҵĽ�����-tab
     */
    function c_toMyBorrowListTab() {
    	$this->display('mylist-tab');
    }

    /**
     * �ҵĽ�����
     */
    function c_toMyBorrowList() {
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->assign('salesNameId', $_SESSION['USER_ID']);
        $this->assign('deptName',$_SESSION['DEPT_NAME']);
        $this->display('mylist');
    }

    /**
     * �ҵĽ�����-�豸�嵥
     */
    function c_toMyBorrowEquList() {
    	$this->display('myequlist');
    }

    /**
     * ��ͬ�鿴tab ---ת����Դ����Ϣ
     */
    function c_toBocomeCon() {
        $contractId = $_GET['contractId'];
        $conDao = new model_contract_contract_equ();
        $borrowIdarr = $conDao->getBorrowIds($contractId);
        foreach ($borrowIdarr as $k => $v) {
            $borrowIds .= $v['toBorrowId'] . ",";
        }
        $borrowIds = Trim($borrowIds, ",");
        $this->assign("ids", $borrowIds);
        $this->display("tobecomecon");
    }

    /****************************************���÷����б�start*******************************************************/

    /**
     * ���˽����÷���tab
     */
    function c_personShipTab() {
        $this->assign('limits', $_GET['limits']);
        $this->display('person-shiptab');
    }


    /**
     * ����������ȷ������
     */
    function c_assignment() {
        $this->assign('limits', $_GET['limits']);
        $this->display('assignments');
    }

    /**
     * �����÷���
     */
    function c_toBorrowShipments() {
        if (isset($_GET['finish']) && $_GET['finish'] == 1) {
            $this->assign('listJS', 'borrow-shipped.js');
        } else {
            $this->assign('listJS', 'borrow-shipments.js');
        }
        $this->assign('inSeaIds', isproShipcondition);
        $this->assign('limits', $_GET['limits']);
        $this->display('shipments');
    }


    /**
     * �����÷���
     */
    function c_toSeaShipments() {
        $this->assign('outSeaIds', isproShipcondition);
        $this->assign('limits', 'Ա��');
        $this->display('seashipments');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_shipmentsPageJson() {
        $rateDao = new model_stock_outplan_contractrate();
        $service = $this->service;
        // ��������  -����������̡���Ȩ�ޣ���д+����ȷ��-�´﷢���ƻ�-���۹����̻�-�ύ���� By weijb 2015.10.20
        $ExaStatus = $_REQUEST['ExaStatus'];
        unset($_REQUEST['ExaStatus']);
        $ExaStatusArr = $_REQUEST['ExaStatusArr'];
        unset($_REQUEST['ExaStatusArr']);
        $lExaStatusArr = $_REQUEST['lExaStatusArr'];
        unset($_REQUEST['lExaStatusArr']);
        $DeliveryStatus2 = $_REQUEST['DeliveryStatus2'];
        unset($_REQUEST['DeliveryStatus2']);
        $limits = $_REQUEST['limits'];
        unset($_REQUEST['limits']);
        $isproShipcondition = $_REQUEST['isproShipcondition'];
        unset($_REQUEST['isproShipcondition']);
        $isproShipconditionAs = $_REQUEST['isproShipconditionAs'];
        unset($_REQUEST['isproShipconditionAs']);
        $service->getParam($_REQUEST);
        $mySql = "sql:";
        if(!empty($ExaStatus)){
        	$mySql .= " and ((c.ExaStatus ='".$ExaStatus."'";
        }
        if(!empty($ExaStatusArr)){
        	$mySql .= " and ((c.ExaStatus in(".util_jsonUtil::strBuild($ExaStatusArr).")";
        }
        if(!empty($lExaStatusArr)){
        	$mySql .= " and l.ExaStatus in(".util_jsonUtil::strBuild($lExaStatusArr).")";
        }
        if(!empty($DeliveryStatus2)){
            // �����������
            if(isset($_REQUEST['isDelayApply']) && $_REQUEST['isDelayApply'] == 1){
                $mySql .= " and ( c.DeliveryStatus in (".util_jsonUtil::strBuild($DeliveryStatus2).") or (c.isDelayApply = 1))";
            }else{
                $mySql .= " and c.DeliveryStatus in (".util_jsonUtil::strBuild($DeliveryStatus2).")";
            }
        }
        if(empty($DeliveryStatus2) && (isset($_REQUEST['isDelayApply']) && $_REQUEST['isDelayApply'] == 1)){
            $mySql .= " and (c.isDelayApply = 0 or c.isDelayApply = 1)";
        }
        if(!empty($limits)){
        	$mySql .= " and c.limits='".$limits."'";
        }
        if(!empty($isproShipcondition)){
        	$mySql .= " and c.isproShipcondition='".$isproShipcondition."'";
        }
        if(!empty($isproShipconditionAs)){
        	$mySql .= " and (c.isproShipcondition='".$isproShipconditionAs."' or c.isship='".$isproShipconditionAs."') ";
        }
        //PMS 164 ȥ��������
//        $mySql .= ") or c.createId = 'quanzhou.luo') ";// ����Ȩ�������ĵ���,����д��
        $mySql .= ") ) ";

        $service->searchArr['mySearchCondition'] = $mySql;
        //$service->asc = false;

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $sysLimit2 = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $sqlId = '';
        if(!empty($sysLimit['��������']) && !empty($sysLimit2['�����˲���Ȩ��'])){
            if(!strstr($sysLimit['��������'],";;") || !strstr($sysLimit2['�����˲���Ȩ��'],";;")){
                $applyDeptLimit = ($sysLimit2['�����˲���Ȩ��'] == '')? "" :  "or u.DEPT_ID IN(".$sysLimit2['�����˲���Ȩ��'].")";
                $areaCodeSql = "sql: and (ce.areaCode IN(".$sysLimit['��������'].") ".$applyDeptLimit.")";
                $areaCodeSql = str_replace(',*,',',',$areaCodeSql);
                $areaCodeSql = str_replace(',,',',',$areaCodeSql);
                $service->searchArr['areaCodeSql'] = $areaCodeSql;
            }
            $sqlId = 'select_assignment';
        }else if(!empty($sysLimit['��������'])){
            if(!strstr($sysLimit['��������'],";;")){
                $service->searchArr['areaCodeSql'] = "sql: and (ce.areaCode IN(".$sysLimit['��������'].") or limits='Ա��')";
            }
            $sqlId = 'select_assignment';
        }else if(!empty($sysLimit2['�����˲���Ȩ��'])){
            if(!strstr($sysLimit2['�����˲���Ȩ��'],";;")){
                $areaCodeSql = "sql: and u.DEPT_ID IN(".$sysLimit2['�����˲���Ȩ��'].")";
                $areaCodeSql = str_replace(',*,',',',$areaCodeSql);
                $areaCodeSql = str_replace(',,',',',$areaCodeSql);
                $service->searchArr['areaCodeSql'] = $areaCodeSql;
            }
            $sqlId = 'select_assignment';
        }else{
        	$rows = "";
        }
        if($sqlId != ''){
            $rows = $service->pageBySqlId($sqlId);
        }

        $rows = $this->sconfig->md5Rows($rows);
        //����������ȱ�ע
        $orderIdArr = array();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $orderIdArr[$key] = $rows[$key]['id'];
            }
        }
        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $rows[$key]['rate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_borrow_borrow' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['rate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
            }
        }
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }


    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_assignmentJson() {
        //Ȩ��ϵͳ
        $rateDao = new model_stock_outplan_assignrate();
        $service = $this->service;
		// ��������  -����������̡���Ȩ�ޣ���д+����ȷ��-�´﷢���ƻ�-���۹����̻�-�ύ���� By weijb 2015.10.20
        $ExaStatusArr = $_REQUEST['ExaStatusArr'];
        unset($_REQUEST['ExaStatusArr']);
        $limits = $_REQUEST['limits'];
        unset($_REQUEST['limits']);
        $service->getParam($_REQUEST);
        $mySql = "sql: ";
        if(!empty($ExaStatusArr)){
        	$mySql .= " and ((c.ExaStatus in(".util_jsonUtil::strBuild($ExaStatusArr).")";
        }
        if($_REQUEST['dealStatusArr'] == '0,2'){//δ�����б�����ʾ��ؼ��رյĵ���
        	$mySql .= " and c.`status` not in('2','3')";
        }else{//�Ѵ����б�����ʾ��صĵ���
        	$mySql .= " and c.`status` not in('2','3')";
        }
        if(!empty($limits)){
        	$mySql .= " and c.limits='".$limits."'";
        }
        $mySql .= ") or c.createId = 'quanzhou.luo') ";// ����Ȩ�������ĵ���,����д��
        $service->searchArr['mySearchCondition'] = $mySql;
        if (isset ($this->service->this_limit['��������']) && !empty ($this->service->this_limit['��������']))
            $limit = $this->service->this_limit['��������'];
        $limitsSql = "sql: and (";
        $limitArr = explode(',', $limit);
        if (is_array($limitArr) && count($limitArr) > 0 && $limit != '') {
            $flag = true;
            foreach ($limitArr as $key => $val) {
                if ($val == '1' && $flag) {
                    $limitsSql .= " limits='�ͻ�' ";
                    $flag = false;
                } else if ($val == '1' && !$flag) {
                    $limitsSql .= " or limits='�ͻ�' ";
                }
                if ($val == '2' && $flag) {
                    $limitsSql .= " limits='Ա��' and isproShipcondition='0'";
                    $flag = false;
                } else if ($val == '2' && !$flag) {
                    $limitsSql .= " or (limits='Ա��' and isproShipcondition='0') ";
                }
                if ($val == '3' && $flag) {
                    $limitsSql .= " limits='Ա��' and isproShipcondition='1' ";
                    $flag = false;
                } else if ($val == '3' && !$flag) {
                    $limitsSql .= " or (limits='Ա��' and isproShipcondition='1')";
                }
            }
            $limitsSql .= ") ";
            $service->searchArr['advSql'] = $limitsSql;
        }
        //$service->asc = false;
//		$rows = $service->pageBySqlId('select_shipments');

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $sysLimit2 = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        if(!empty($sysLimit['��������']) && !empty($sysLimit2['�����˲���Ȩ��'])){
            if(!strstr($sysLimit['��������'],";;") || !strstr($sysLimit2['�����˲���Ȩ��'],";;")){
                $applyDeptLimit = ($sysLimit2['�����˲���Ȩ��'] == '')? "" :  "or FIND_IN_SET(u.DEPT_ID,'".$sysLimit2['�����˲���Ȩ��']."')";
                $service->searchArr['areaCodeSql'] = "sql: and (FIND_IN_SET(ce.areaCode,'".$sysLimit['��������']."') ".$applyDeptLimit.")";
            }
            $rows = $service->pageBySqlId('select_assignment');
        }else if(!empty($sysLimit['��������'])){
            if(!strstr($sysLimit['��������'],";;")){
                $service->searchArr['areaCodeSql'] = "sql: and (FIND_IN_SET(ce.areaCode,'".$sysLimit['��������']."') or limits='Ա��')";
            }
            $rows = $service->pageBySqlId('select_assignment');
        }else if(!empty($sysLimit2['�����˲���Ȩ��'])){
            if(!strstr($sysLimit2['�����˲���Ȩ��'],";;")){
                $service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(u.DEPT_ID,'".$sysLimit2['�����˲���Ȩ��']."')";
            }
            $rows = $service->pageBySqlId('select_assignment');
        }
        else{
            $rows = "";
        }


//		echo "<pre>";
//		print_R($rows);

        //����������ȱ�ע
        $orderIdArr = array();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $orderIdArr[$key] = $rows[$key]['id'];
                //�ж�����Ǳ���ĵ��ݣ����Ҳ��滻����ID
                if ($val['isSubAppChange'] == '1') {
                    $mid = $this->service->findChangeId($val['id']);
                    $rows[$key]['id'] = $mid;
                    $rows[$key]['linkId'] = "";
                    $rows[$key]['oldId'] = $val['id'];
                }
            }
        }
        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->searchArr['relDocType'] = 'oa_borrow_borrow';
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $key => $val) {
                $rows[$key]['rate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_borrow_borrow' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['rate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
            }
        }

        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }


    /**
     * �ͻ������÷���tab
     */
    function c_customerShipTab() {
        $this->assign('limits', $_GET['limits']);
        $this->display('person-shiptab');
    }

    /**
     * �����÷���
     */
    function c_toBorrowShipped() {
        $this->assign('limits', $_GET['limits']);
        $this->display('shipped');
    }


    /****************************************���÷����б�end*******************************************************/

    /**
     * �̻�--�鿴--������
     */
    function c_toListForChance() {

        $this->assign('chanceId', $_GET['chanceId']);
        $this->display('listforchance');
    }

    /************************************************************************************************/

    /**
     * �����������
     */
    function c_add($isAddInfo = false) {
        $borrowInfo = $_POST [$this->objName];
        $act = isset ($_GET ['act']) ? $_GET ['act'] : null;
        $sto = isset ($_GET ['sto']) ? $_GET ['sto'] : null;
        $con = isset ($_GET ['con']) ? $_GET ['con'] : null;
        //Ա�������òֹ�ȷ�ϱ�ʾ
        if ($sto == 'sto') {
            $borrowInfo['tostorage'] = '1';
        }
        if ($borrowInfo ['borrowInput'] == "1") {
            $chanceCodeDao = new model_common_codeRule();
            if ($borrowInfo['limits'] == '�ͻ�') {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "cus");
            } else {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "pro");
            }
            $id = $this->service->add_d($borrowInfo,$act);
        } else if ($borrowInfo ['borrowInput'] == "0") {
            $id = $this->service->add_d($borrowInfo,$act);
        } else {
            msgGo('���ҹ���Աȷ�Ͽ��Ʊ�����ɹ����"BORROW_INPUT"ֵ�Ƿ���ȷ');
        }
        if ($id) {
            if ($act == 'app') {
                $result = $this->service->updateById(array("id"=> $id,"status"=> "0","dealStatus"=> "0","ExaStatus" => "����ȷ��"));
                if($result){
                    msgRF('���ύ����ȷ��!');
                }else{
                    msgRF('�ύʧ��,������!');
                }
                //if ($borrowInfo['limits'] == 'Ա��') {
                    //succ_show('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId=' . $id);
                //} else {
                    //succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $id);
                //}
            } else if ($con == 'con') {
                $result = $this->service->updateById(array("id"=> $id,"status"=> "0","dealStatus"=> "0","ExaStatus" => "����ȷ��"));
                if($result){
                    msgRF('���ύ����ȷ��!');
                }else{
                    msgRF('�ύʧ��,������!');
                }
//                $this->service->shortBorrowSub($id);
//                msgRF('��ӳɹ���');
            } else {
                msgRF('��ӳɹ���');
            }
        }

        //$this->listDataDict();
    }

    /**
     * �޸Ķ���
     */
    function c_edit($isEditInfo = false) {
        $object = $_POST [$this->objName];
        $act = isset ($_GET ['act']) ? $_GET ['act'] : null;
        $sto = isset ($_GET ['sto']) ? $_GET ['sto'] : null;
        //Ա�������òֹ�ȷ�ϱ�ʾ
        if ($sto == 'sto') {
            $object['tostorage'] = '1';
        }
        if ($act == 'act') {
            $object['tostorage'] = '2';
        }
        if ($this->service->edit_d($object, $isEditInfo, $_GET['act'])) {
            if ($_GET['act'] == 'app') {
                $result = $this->service->updateById(array("id"=>$object['id'],"status"=> "0","dealStatus"=> "0","ExaStatus" => "����ȷ��"));
                if($result){
                    msgRF('���ύ����ȷ��!');
                }else{
                    msgRF('�ύʧ��,������!');
                }
//                if ($object['limits'] == 'Ա��') {
//                    succ_show('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId=' . $object['id']);
//                } else {
//                    succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $object['id']);
//                }
            }
            if ($act == 'act') {
                msgRF('ȷ����ɣ�');
            } else {
                msgRF('�༭�ɹ���');
            }

        }
    }

    /**
     * �б�ֱ���ύ����
     */
    function c_ajaxSubForm(){
        $id = isset($_POST['id'])? $_POST['id'] : '';
        if($id != ""){
            $result = $this->service->updateById(array("id"=>$id,"status"=> "0","dealStatus"=> "0","ExaStatus" => "����ȷ��"));
            if($result){
                //�����������ʼ�
                $rows = $this->service->get_d($id);
                $infoArr = array(
                    'code' => $rows['Code'],
                    'type' => '����'
                );
                //ͨ���ʼ������ݲ�֧��ҵ����������д���ʼ������� ���ݶ���
                $otherdatas = new model_common_otherdatas ();
                $objdeptName = $otherdatas->getUserDatas($rows['createId'], 'DEPT_NAME');
                $toUser = $rows['createId'];
                // ����ķ��͸�������,������ͳһ���͸���Ȩ��
                if ($objdeptName == '����ҵ��') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",xianzhen.yang";
                    $this->service->mailDeal_d("borrowToShip_HY", $toUser, $infoArr);
                } else if ($rows['limits'] == 'Ա��') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",quanzhou.luo";
                    $this->service->mailDeal_d("borrowToShip_YG", $toUser, $infoArr);
                } else if ($rows['limits'] == '�ͻ�') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",quanzhou.luo";
                    $this->service->mailDeal_d("borrowToShip_KH", $toUser, $infoArr);
                }
            }
            echo '���ύ����ȷ��!';
        }else{
            echo '�ύʧ��,������!';
        }
    }

    /**
     * �����ò鿴Tabҳ
     */
    function c_viewTab() {
        $this->assign("borrowId", $_GET['id']);
        $this->display("viewTab");
    }

    /**
     * �����ñ�� �鿴tab�������ʷ��
     */
    function c_toViewTab() {
        $change = isset($_GET['change']) ? $_GET['change'] : null;
        $rows = $this->service->get_d($_GET ['id']);
        if ($change == '1') {
            $this->assign('change', "1");
        } else {
            $this->assign('change', "2");
        }
        $this->assign('id', $_GET ['id']);
        $this->assign('originalId', $rows ['originalId']);
        $this->assign("borrowId", $_GET['id']);
        $this->assign("initTip", $rows['initTip']);
        $this->display('view-tab');
    }

    /**
     * ��дint
     */
    function c_init() {
//		$this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $rows = $this->service->get_d($_GET['id']);

        $file3 = $this->service->getFilesByObjId($_GET['id'], false, 'oa_borrow_borrow3');
        $this->assign('file3', $file3);

        //��ȡ���������ۼƽ�����ʽ��Ϊǧ��λ
        $rs = $this->service->getPersonalEquMoney($rows['createName']);
        $equMoney = number_format($rs[0]['equMoney'],2);
        //�����鿴ҳ��
        if($perm == 'view' && $actType == 'audit'){
        	$equMoney = $equMoney."--<a href='javascript:void(0)' onclick='showModalWin(\"?model=projectmanagent_orderreport_orderreport&action=borrowDetailReport&searchType=sales&countType=money&searchKey=" . $rows['createName'] . '",1,'. $rows['id'].')\'>' . "�鿴��ϸ" ."</a>";
        }
        $this->assign('equMoney', $equMoney);

        //��Ⱦ�ӱ�
        if ($perm == 'view') {
            $rows = $this->service->initView($rows);
        } else {
            $rows = $this->service->initEdit($rows);
        }
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($perm == 'view') {
            $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
            $this->assign('actType', $actType);
            if ($rows['limits'] == 'Ա��') {
            	$this->assign('module', $this->getDataNameByCode($rows['module']));
                $this->view('proview');
            } else {
                $SingleType = ($rows['SingleType'] == "NULL")? "" : $rows['SingleType'];
                switch ($SingleType) {
                    case "" :
                        $this->assign('SingleType', "��");
                        $this->assign('singleCode', "��");
                        break;
                    case "chance" :
                        $this->assign('SingleType', "�̻�");
                        $chacneId = $rows['chanceId'];
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $rows['chanceCode'] . '</span>';
                        $this->assign('singleCode', $code);
                        break;
                    case "order" :
                        $this->assign('SingleType', "��ͬ");
                        $orderId = $rows['contractId'];
                        $orderCode = $rows['contractNum'];
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';

                        $this->assign('singleCode', $code);
                        break;
                }
                $turnCon = $this->service->getTurnCon($_GET['id']);
                $this->assign('turnContract',$turnCon);
                $this->assign('module', $this->getDataNameByCode($rows['module']));

                if($rows['isTemp'] == 1){// ����Ǳ��ҳ��Ļ�,��ȡ�ϴα���޸ĵ�����
                    $changeLogFields = array('beginTime','closeTime','deliveryDate','salesName','salesNameId','scienceName','scienceNameId','shipaddress','status','reason','remark','remarkapp','module');
                    $changeLogDao = new model_common_changeLog();
                    $changeValArr = $changeLogDao->getChangeInformation_d($rows['id'],"borrow");
                    if(is_array($changeValArr) && count($changeValArr) > 0){
                        foreach ($changeLogFields as $fieldCode){
                            $catchCode = $changeShowStr = '';
                            foreach ($changeValArr as $changeRecord){
                                if($fieldCode == $changeRecord['changeField']){
                                    $catchCode = $fieldCode;
                                    $changeShowStr = "{$changeRecord['oldValue']} => {$changeRecord['newValue']}";
                                    break;
                                }
                            }
                            if($catchCode != ""){
                                $this->assign("{$catchCode}_changeShow",$changeShowStr);
                            }else{
                                $this->assign("{$fieldCode}_changeShow",$changeShowStr);
                            }
                        }
                    }
                }

                $this->view('view');
            }
        } else {
            $SingleType = $rows['SingleType'];
            switch ($SingleType) {
                case "" :
                    $this->assign('SingleType', "��");
                    $this->assign('singleCode', "��");
                    break;
                case "chance" :
                    $this->assign('SingleType', "�̻�");
                    $chacneId = $rows['chanceId'];
                    $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $rows['chanceCode'] . '</span>';
                    $this->assign('singleCode', $code);
                    break;
                case "order" :
                    $this->assign('SingleType', "��ͬ");
                    $orderId = $rows['contractId'];
                    $orderCode = $rows['contractNum'];
                    $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';

                    $this->assign('singleCode', $code);
                    break;
            }
            $this->assign('createId', $_SESSION['USER_ID']);
            $this->showDatadicts(array ('module' => 'HTBK'), $rows['module']);
            $this->view('edit');
        }
    }

    /**
     * �����޸�
     */
    function c_productEdit() {
        if (!isset($this->service->this_limit['�����޸�']) || $this->service->this_limit['�����޸�'] != 1) {
            echo "û�������޸ĵ�Ȩ�ޣ�����ϵOA����Ա��ͨ";
            exit();
        }
        $icon = isset($_GET['icon']) ? $_GET['icon'] : null;
        $this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $rows = $this->service->get_d($_GET['id']);
        $rows = $this->service->editProduct($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        if ($icon == "Pro") {
            $this->display('producteditPro');
        } else {
            $this->display('productedit');
        }

    }

    /**
     * Ա�������ò鿴ҳ��
     */
    function c_proView() {
//          $this->permCheck();
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        //echo $_GET['id'];
        $rows = $this->service->get_d($_GET['id']);

        $rows = $this->service->initView($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        $this->assign('actType', $actType);
        $this->assign('module', $this->getDataNameByCode($rows['module']));
        $this->view('proview');
    }

    /**
     * Ա�����ò鿴tabҳ
     */
    function c_proviewTab() {
        $rows = $this->service->get_d($_GET ['id']);
        $this->assign('id', $_GET ['id']);
        $this->assign('originalId', $rows ['originalId']);
        $this->assign("borrowId", $_GET['id']);
        $this->assign("initTip", $rows['initTip']);
        $this->assign("isproShipcondition", $rows['isproShipcondition']);
        $this->display('proviewTab');
    }

    /**
     * �����ô����б� �鿴tabҳ
     */
    function c_proDisViewTab() {
        $this->assign("borrowId", $_GET['id']);
        $this->display('prodisviewTab');
    }

    /**
     * Ա�������ñ༭
     */
    function c_proEdit() {
        $this->permCheck();
        $this->assign('borrowInput', BORROW_INPUT);
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $rows = $this->service->get_d($_GET['id']);
        $rows = $this->service->initEdit($rows);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
//		//��ȡĬ�Ϸ�����
//	   include (WEB_TOR."model/common/mailConfig.php");
//	    $this->assign('tostorageName' ,$mailUser['oa_borrow_borrow']['tostorageName']);
//	    $this->assign('tostorageNameId' ,$mailUser['oa_borrow_borrow']['tostorageNameId']);
        $this->showDatadicts(array ('module' => 'HTBK'), $rows['module']);
        $this->view('proedit');
    }

    /**
     * Ա��������-�ֹ�ȷ�ϱ༭ҳ
     */
    function c_storageEdit() {
        $perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
        $rows = $this->service->get_d($_GET['id']);
        $rows = $this->service->initEdit($rows);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('storageedit');
    }

    /**
     * Ա�������� ��治�㴦��ҳ
     */
    function c_borrowDispose() {
        $this->assign('borrowId', $_GET['id']);

        $rows = $this->service->get_d($_GET['id']);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡĬ�Ϸ�����
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('executeName', $mailUser['borrow_execute']['executeName']);
        $this->assign('executeNameId', $mailUser['borrow_execute']['executeNameId']);
        $this->assign('proposer', $rows['createName']);
        $this->assign('proposerId', $rows['createId']);
        $this->display('borrowdispose');
    }

    /**
     * Ա�������� �ֹܴ��� ����
     */
    function c_executeBorrow() {
        $object = $_POST [$this->objName];
        if ($object['type'] == "back") {
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->toExeBackEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "backBorrowInfo", $object['Code'], "ͨ��", $object['proposerId'], $object['remark']);
            $this->service->ajaxBorrowBackR($object['borrowId'],$object['tempId']);
        } else if ($object['type'] == "exe") {
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->toExeEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "borrowToExedeptInfo", $object['Code'], "ͨ��", $object['executeNameId'], $object['exeRemark']);
            $this->service->ajaxBorrowShipR($object['borrowId']);
        }
        msg('������ɣ�');
    }

    /**
     * Ա�������� ��������
     */
    function c_borrowRenew() {
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $dao = new model_projectmanagent_borrow_borrowequ();
        $borrowequ = $dao->renewTableEdit($rows['borrowequ']);
        $this->assign('borrowequ', $borrowequ[1]);
        $this->assign('productNumber', $borrowequ[0]);
        $this->assign("date", date("Y-m-d"));
        $this->display('borrowrenew');
    }

    /**
     * �����޸�
     */
    function c_pedit($isEditInfo = false) {
        $object = $_POST [$this->objName];
        $id = $this->service->proedit_d($object, $isEditInfo);

        if ($id) {
            $this->service->updateOrderShipStatus_d($id);
            msgRF('�༭�ɹ�');
        }
    }

    /***********************************************************************************************/

    /**
     * ����ɾ������
     */
    function c_deletesInfo() {
        $deleteId = isset($_GET['id']) ? $_GET['id'] : exit;
        $delete = $this->service->deletesInfo_d($deleteId);
        if ($delete) {
            echo 1;
        } else {
            echo 0;
        }
        exit();
    }

    /***********************************************************************************************/

    /**
     * δ����Json
     */
    function c_pageJsonAuditNo() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr['workFlowCode'] = $service->tbl_name;
        $service->asc = true;
        $rows = $service->pageBySqlId('select_auditing');
        $rows = $this->sconfig->md5Rows($rows, "borrowId");
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ������Json
     */
    function c_pageJsonAuditYes() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr ['workFlowCode'] = $this->service->tbl_name;
        $rows = $service->pageBySqlId('select_audited');
        $rows = $this->sconfig->md5Rows($rows, "borrowId");
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * �ҵĽ�����PageJson
     */

    function c_MyBorrowPageJson() {
        $service = $this->service;
        if(util_jsonUtil::iconvUTF2GB($_POST['ExaStatus']) == "���������"){
            $_POST['changingExaStatus'] = "���������";
            unset($_POST['ExaStatus']);
        }
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->setCompany(0);//�����ù�˾����
        $userId = $_SESSION['USER_ID'];
        $service->searchArr['pageUser'] = "sql:and (c.createId =  '$userId' or c.salesNameId = '$userId')";
//		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
        $rows = $service->pageBySqlId('select_borrow_renew');
        //�黹״̬
        foreach ($rows as $k => $v) {
            $backStatus = $service->backStatus($v['id']);
            $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
        }
        //�ж��Ƿ���
        foreach ($rows as $key => $val) {
            $newDate = date("Y-m-d");
            if ($newDate > $rows[$key]['closeTime']) {
                $rows[$key]['isExceed'] = '1';
            } else {
                $rows[$key]['isExceed'] = '0';
            }
        }

        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil :: encode($arr);
    }

    function c_listForChance() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->searchArr['chanceId'] = $_GET['chanceId'];
//		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
        $rows = $service->pageBySqlId('select_default');
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ���н�����(Ա��)
     */
    function c_pageJsonStaff() {
        $service = $this->service;
        $rows = array();
        if(util_jsonUtil::iconvUTF2GB($_POST['ExaStatus']) == "���������"){
            $_POST['changingExaStatus'] = "���������";
            unset($_POST['ExaStatus']);
        }
        $service->getParam($_POST);
        $otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('projectmanagent_borrow_borrow',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $privlimit = isset ($sysLimit ['����']) ? $sysLimit ['����'] : null;
        if (!empty($privlimit)) {
//            $service->searchArr['createSections'] = $privlimit;
//            $service->searchArr['createIdOr'] = $_SESSION['USER_ID'];
            $service->searchArr['mySearchCondition'] = "sql: and ( u.DEPT_ID in(".$privlimit.") or c.createId='".$_SESSION['USER_ID']."')";
        } else {
            $service->searchArr['createId'] = $_SESSION['USER_ID'];
        }
        //$service->asc = false;
        $rows = $service->page_d();
        //�黹״̬
        foreach ($rows as $k => $v) {
            $backStatus = $service->backStatus($v['id']);
            $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * Ա�������÷���pagejson
     */
    function c_toStoragePageJson() {
        $service = $this->service;
        $rows = array();

        $service->getParam($_POST);
        $service->searchArr['sto'] = "sql:and c.initTip <> '1' and c.dealStatus in (1,2,3) and ((c.limits = 'Ա��' and (c.ExaStatus = '���' or c.ExaStatus = '����')) or (c.limits = 'Ա��' and c.toStorage = '1') or (c.limits = '�ͻ�' and c.subtip = '1'))";

        $rows = $service->pageBySqlId('select_default');
        //�黹״̬
        foreach ($rows as $k => $v) {
            $backStatus = $service->backStatus($v['id']);
            $rows[$k]['backStatus'] = $backStatus;
//			if($backStatus == 1){
//               $rows[$k]['status'] = 2;
//			}
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �ӱ�ѡ�����ϴ�������
     */
    function c_ajaxorder() {
        $isEdit = isset($_GET['isEdit']) ? $_GET['isEdit'] : null;
        $configInfo = $this->service->c_configuration($_GET['id'], $_GET['Num'], $_GET['trId'], $isEdit);
        echo $configInfo[0];
    }

    /**
     * ���ڽ����ò�������-ֱ���ύ ������״̬
     */
    function c_ajaxShortBorrowSub() {
        try {
            $this->service->shortBorrowSub($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * �б�ҳ �ύ�ֹ�ȷ��
     */
    function c_ajaxCounSub() {
        try {
            $this->service->ajaxCounSubS($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * Ա�������� �˻�
     */
    function c_borrowBack() {
        try {
            $this->service->ajaxBorrowBackR($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * yԱ��������  ת��ִ�в�
     */
    function c_borrowShip() {
        try {
            $this->service->ajaxBorrowShipR($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ִ�в� ���²ֿ� ��תҳ
     */
    function c_toBackStorage() {
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //��ȡĬ�Ϸ�����
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('exeName', $mailUser['exeBackStorage']['exeName']);
        $this->assign('exeNameId', $mailUser['exeBackStorage']['exeNameId']);
        $this->assign('borrowId', $_GET['id']);

        $this->display("backstorage");
    }

    /**
     * ִ�в� ���²ֿ� ����
     */
    function c_toBackStorageDis() {
        $object = $_POST [$this->objName];
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->toshipbackEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "exedpptToStorageInfo", $object['Code'], "ͨ��", $object['exeNameId'], $object['exeRemark']);
        $this->service->BackStorageDisR($object['borrowId']);

        msg('������ɣ�');
    }

    /**
     * ��ת�������ý������ҳ��
     */
    function c_toConfig() {
        $this->view("config");
    }

    /**
     * ������ ajax ��ȡ�����ӱ�������Ϣ
     */
    function c_ajaxSingle() {
        $id = $_GET['id'];
        $type = $_GET['type'];
        $orderType = isset($_GET['orderType']) ? $_GET['orderType'] : null;
        switch ($type) {
            case "chance" :
                $dao = new model_projectmanagent_chance_chanceequ();
                $chanceequ = $dao->getDetail_d($id);
                $chance = $dao->borrowTableEdit($chanceequ);
                echo $chance[1];
                break;
            case "order"  :
                switch ($orderType) {
                    case "oa_sale_order" :
                        $orderDao = new model_projectmanagent_order_orderequ();
                        break;
                    case "oa_sale_service" :
                        $orderDao = new model_engineering_serviceContract_serviceequ();
                        break;
                    case "oa_sale_lease" :
                        $orderDao = new model_contract_rental_tentalcontractequ();
                        break;
                    case "oa_sale_rdproject" :
                        $orderDao = new model_rdproject_yxrdproject_rdprojectequ();
                        break;
                        break;
                }

                $orderequ = $orderDao->getDetail_d($id);
                $order = $orderDao->borrowTableEdit($orderequ);
                echo $order[1];
                break;
        }
    }

    /**
     * ajax ���·���״̬
     */
    function c_ajaxUpdateDeliveryStatus() {
        try {
            $this->service->ajaxUpdateDeliveryStatus_d();
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ���������� �������� �����ʼ�
     */
    function c_toRemindMail() {
        try {
            $borrowId = $_GET['id'];
            $borrowInfo = $this->service->get_d($borrowId);
            $this->service->toremindMail_d($borrowInfo['createId'], $borrowInfo['Code'], $borrowInfo);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ��֤��ŵ�Ψһ��
     */
    function c_ajaxCode() {
        $service = $this->service;
        $Code = isset ($_GET ['ajaxCode']) ? $_GET ['ajaxCode'] : false;
        $Id = isset ($_GET ['id']) ? $_GET ['id'] : false;
        $searchArr = array("ajaxCode" => $Code);
        $isRepeat = $service->isRepeat($searchArr, $Id);

        if ($isRepeat) {
            echo "1";
        } else {
            echo "0";
        }
    }

    /*********************************������ת���� ��ʼ*******************************************************************************/

    /**
     * �ͻ������� ת���� ����ҳ
     */
    function c_borrowToOrder() {
        $this->assign('borrowId', $_GET['id']);
        $row = $this->service->get_d($_GET['id']);
        $isNum = array(); //������֤�������Ƿ��� ���Ͽ���תΪ����
        foreach ($row['borrowequ'] as $k => $v) {
//            $dao = new model_stock_allocation_allocation();
//		    $broNum = $dao->getApplyDocNotBackNum($v['borrowId'],$v['productId'],"DBDYDLXFH");
            $contractDao = new model_contract_contract_equ();
            $exeNum = $contractDao->getBorrowToContractNum($v['borrowId']);
            $Num = $v['executedNum'] - $v['backNum'] - $exeNum;
            if ($Num != 0) {
                $isNum[$k] = $v['productId'];
            }
        }
        if (!empty($isNum)) {
            $this->assign('isNum', '1');
        } else {
            $this->assign('isNum', '0');
        }
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->display('toorder');
    }

    /*
       * ������ת���� --�޹�����ͬ ��������
       */
    function c_ToOrder() {
        $borrowId = $_GET['borrowId'];
        $this->assign('createTime', date('Y-m-d'));
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('contractSigner', $_SESSION['USERNAME']);
        $this->assign('contractSignerId', $_SESSION['USER_ID']);
        $this->assign('prinvipalName', $_SESSION['USERNAME']);
        $this->assign('prinvipalId', $_SESSION['USER_ID']);
        $this->assign('prinvipalDept', $_SESSION['DEPT_NAME']); //û�к����ڼ�
        $this->assign('prinvipalDeptId', $_SESSION['DEPT_ID']);
        $this->assign('borrowId', $borrowId);
        $customerIdarr = $this->service->find(array("id" => $borrowId), null, 'customerId');
        $this->assign('customerId', $customerIdarr['customerId']);
        //���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption('stampType', null, true, $stampArr); //��������

        //��ͬ����Ƿ��ֹ�����
        $this->assign('contractInput', ORDER_INPUT);
        $this->view('to-contract');
    }

    /**
     * ������ת����---�������ۺ�ͬ����
     */
    function c_toOrderAdd() {
        $contractInfo = $_POST ['contract'];
        $contractDao = new model_contract_contract_contract();
        $contractId = $contractDao->add_d($contractInfo);
        if (!empty($contractInfo['borrowtoCon'])) {
//		//���� �м����Ϣ
//        $toOrderDao = new model_projectmanagent_borrow_toorder();
//        $toOrderId = $toOrderDao->createBatch($contractInfo['borrowtoCon'],array('contractId' => $contractId,'contractType' => $contractInfo['contractType']));
            foreach ($contractInfo['borrowtoCon'] as $k => $v) {
                $contractInfo['borrowtoCon'][$k]['toBorrowequId'] = $v['id'];
                $contractInfo['borrowtoCon'][$k]['productCode'] = $v['productNo'];
                unset($contractInfo['borrowtoCon'][$k]['id']);
                unset($contractInfo['borrowtoCon'][$k]['productNo']);
            }
            //���� ��ͬ���ڵ�������Ϣ
            $orderequDao = new model_contract_contract_equ();
            $orderequDao->createBatch($contractInfo['borrowtoCon'], array('contractId' => $contractId, 'isBorrowToorder' => '1', 'toBorrowId' => $contractInfo['borrowId']));
        }
        //�ж��Ƿ�ֱ���ύ����
        if ($contractId && $_GET ['act'] == "app") {
            if ($contractId == "confirm") {
                msg("��ͬ���ύȷ�ϳɱ�����");
            }
        } else if ($contractId) {
            msg('��ӳɹ���');
        } else {
            msg('��ת����������Ϣ');
        }
    }

    /**
     * ������ת���� --�й�����ͬ �޸Ļ�������
     */
    function c_toOrderBecome() {
        $contractId = isset ($_GET ['contractId']) ? $_GET ['contractId'] : null;
        $borrowId = isset ($_GET['borrowId']) ? $_GET['borrowId'] : null;
        //��ͬ����״̬
        $contractExaType = $this->service->getOrderExaType($contractId);
        $Dao = new model_contract_contract_contract();
        if ($contractExaType == 'δ����') {
            //��ȡ��ͬ��Ϣ
            $obj = $Dao->get_d($contractId);
            //������Ⱦ
            $this->assignFunc($obj);
            $this->assign('borrowId', $borrowId);
            if ($obj['sign'] == 1) {
                $this->assign('signYes', 'checked');
            } else {
                $this->assign('signNo', 'checked');
            }
            //����
            $file = $this->service->getFilesByObjId($obj['id'], true);
            $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
            $this->assign('file', $file);
            $this->assign('file2', $file2);
            $this->showDatadicts(array(
                'contractType' => 'HTLX'
            ), $obj['contractType']);
            switch ($obj['contractType']) {
                case 'HTLX-XSHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'XSHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-FWHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'FWHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-ZLHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'ZLHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-YFHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'YFHTSX'
                    ), $obj['contractNature']);
                    break;
            }
            $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
            $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);
            $this->showDatadicts(array('signSubject' => 'QYZT'), $obj['signSubject']);
//					//���ø�������
//					$stampConfigDao = new model_system_stamp_stampconfig();
//					$stampArr = $stampConfigDao->getStampType_d();
//					$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//��������
            $this->view('tocontract-edit');
        } else if ($contractExaType == '���') {
            $obj = $Dao->get_d($contractId);
            foreach ($obj as $key => $val) {
                $this->assign($key, $val);
            }
            if ($obj['sign'] == 1) {
                $this->assign('signYes', 'checked');
            } else {
                $this->assign('signNo', 'checked');
            }
            //����
            $file = $this->service->getFilesByObjId($obj['id'], true);
            $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
            $this->assign('file', $file);
            $this->assign('file2', $file2);
            $this->showDatadicts(array(
                'contractType' => 'HTLX'
            ), $obj['contractType']);
            switch ($obj['contractType']) {
                case 'HTLX-XSHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'XSHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-FWHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'FWHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-ZLHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'ZLHTSX'
                    ), $obj['contractNature']);
                    break;
                case 'HTLX-YFHT' :
                    $this->showDatadicts(array(
                        'contractNature' => 'YFHTSX'
                    ), $obj['contractNature']);
                    break;

            }
            $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
            $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);
            $this->showDatadicts(array('signSubject' => 'QYZT'), $obj['signSubject']);

            $this->assign('borrowId', $borrowId);
            $this->view('tocontract-change');
        }
    }

    /**
     * ������ת���� --�޸�
     */
    function c_toOrderEdit($isEditInfo = false) {
        $object = $_POST['contract'];
        $editDao = new model_contract_contract_contract();

//		//���� �м����Ϣ
//        $toOrderDao = new model_projectmanagent_borrow_toorder();
//        $toOrderId = $toOrderDao->createBatch($object['borrowequ'],array('contractId' => $object['id'],'contractType' => $orderType));
        //���� ��ͬ���ڵ�������Ϣ
        foreach ($object['borrowtoCon'] as $k => $v) {
            $object['borrowtoCon'][$k]['toBorrowequId'] = $v['id'];
            $object['borrowtoCon'][$k]['productCode'] = $v['productNo'];
            unset($object['borrowtoCon'][$k]['id']);
            unset($object['borrowtoCon'][$k]['productNo']);
        }
        $orderequDao = new model_contract_contract_equ();
        $orderequDao->createBatch($object['borrowtoCon'], array('contractId' => $object['id'], 'isBorrowToorder' => '1', 'toBorrowId' => $object['borrowId']));

        if ($editDao->edit_d($object, $isEditInfo)) {
            msg('�༭�ɹ���');
        }
    }

    /**
     * ��������ת����--���
     */
    function c_toOrderChange() {
        $changeDao = new model_contract_contract_contract;
        $object = $_POST['contract'];

        try {
////			//���� �м����Ϣ
//	        $toOrderDao = new model_projectmanagent_borrow_toorder();
//	        $toOrderId = $toOrderDao->createBatch($object['borrowequ'],array('contractId' => $object['oldId'],'contractType' => $orderType,'contractChangeId' => $id));

            foreach ($object['borrowtoCon'] as $k => $v) {
                $object['borrowtoCon'][$k]['toBorrowequId'] = $v['id'];
                $object['borrowtoCon'][$k]['isBorrowToorder'] = "1";
                $object['borrowtoCon'][$k]['toBorrowId'] = $object['borrowId'];
                $object['borrowtoCon'][$k]['productCode'] = $v['productNo'];
                unset($object['borrowtoCon'][$k]['id']);
                unset($object['borrowtoCon'][$k]['productNo']);
            }
            $object['equ'] = $object['borrowtoCon'];
            $id = $changeDao->change_d($object);
            if ($id) {
                echo "<script>this.location='controller/contract/contract/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
            }

        } catch (Exception $e) {
            msgBack2("���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage());
        }
    }


    /**
     * ������ת���� ѡ��ҳ��
     */
    function c_borrowTurnChoose() {
        $customerId = $_GET['customerId'];
        $salesNameId = $_GET['salesNameId'];
        $this->assign('customerId', $customerId);
        $this->assign('salesNameId', $salesNameId);
        $this->view('borrowturn-choose');
    }

    /**
     * ������ת������Ϣҳ
     */
    function c_borrowTurnInfo() {
        $customerId = $_GET['customerId'];
        $salesNameId = $_GET['salesNameId'];
        $checkIdsArr = explode(",",$_GET['checkIds']);
        $chanceId = $_GET['chanceId'];
        $i = 0;
        foreach($checkIdsArr as $k=>$v){
        	if($v != "undefined" && !empty($v)){
        		$newCheckIdsArr[$i] = $v;
        	}
        	$i++;
        }
        $newCheckIdsStr = implode(",",$newCheckIdsArr);
        $this->assign('customerId', $customerId);
        $this->assign('salesNameId', $salesNameId);
        $this->assign("checkIds",$newCheckIdsStr);
        $this->assign("chanceId",$chanceId);
        //��ʾ���н����õ���ʶ
        $this->assign("showAll",isset($_GET['showAll']) ? 1 : 0);
//     	 $this->view('borrowturn-info');
        $this->view('borrowturn-choose');
    }

    //������ת���ۻ�ȡ����json
    function c_borrowequJson() {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        //$service->asc = false;
        $service->setCompany(0);//�����ù�˾����
        $rows = $service->pageBySqlId('borrowequ_choose');
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
     * ������ת���ۻ�ȡ����id��
     * ������Ϸ�ҳ��ȫѡ����
     */
    function c_borrowequIds() {
    	$service = $this->service;
    	$service->getParam ( $_REQUEST );
        $service->setCompany(0);//�����ù�˾����
    	$rows = $service->list_d ('borrowequ_choose');
    	if($rows){
    		$ids = '';
    		foreach ($rows as $v){
    			$ids .= $v['id'].',';
    		}
    		$ids = rtrim($ids,',');
    		echo $ids;
    	}
    	echo '';
    }

    //������ת���ۻ�ȡ����json
    function c_borrowequJsons() {
    	$service = $this->service;
    	$service->getParam($_REQUEST);
        $service->setCompany(0);//�����ù�˾����
        if($_REQUEST['objType']){
            $service->searchArr['chanceId']=$_REQUEST['objType'];
        }elseif($_REQUEST['showAll'] && $_REQUEST['showAll'] == '1'){//�����ʾ���н����õ��������ֿͻ�
        	unset($service->searchArr['customerId']);
        }
    	$service->searchArr['limits']="�ͻ�";
    	$service->searchArr['ExaStatus']="���";
    	//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


    	//$service->asc = false;
    	$service->groupBy = "c.id";
    	$rows = $service->pageBySqlId('select_borrowTosale');
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
     * ������ת���� ѡ��ͻ�������ҳ
     */
    function c_borrowTurnList() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!empty($id)) {
            //��ȡ�����õ�����Ϣ
            $borrowArr = $this->service->get_d($id);
            $this->assign('customerId', $borrowArr['customerId']);
            $this->assign('customerName', $borrowArr['customerName']);
            $this->assign('borrowId', $id);
            $this->assign('borrowCode', $borrowArr['Code']);
        } else {
            $this->assign('customerId', "");
            $this->assign('customerName', "");
            $this->assign('borrowId', "");
            $this->assign('borrowCode', "");
        }
        $this->assign('salesName', $_SESSION['USERNAME']);
        $this->assign('salesNameId', $_SESSION['USER_ID']);
        $this->view("borrowturnlist");
    }

    //ѡ���Ƿ������ͬ
    function c_tochooseCon() {
        $ids = $_GET['ids'];
        $this->assign('ids', $ids);
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view("tochooseCon");
    }

    /*
      * ��ȡ������ԭ��������Ϣ
      */
    function c_getOriginalBorrowEquInfo() {
        $id = $_POST['id'];
        $sql = "select id,borrowId,executedNum,backNum from oa_borrow_equ where id = '{$id}';";
        $equData = $this->service->_db->getArray($sql);
        if($equData){
            echo util_jsonUtil::encode($equData);
        }else{
            echo '';
        }
    }

    /*
      * ��ȡ������ת��������
      */
    function c_getBorrowequInfo() {
        $ids = $_POST['ids'];
        $service = $this->service;
        $service->searchArr['ids'] = $ids;
        $service->setCompany(0);//�����ù�˾����
        $rows = $service->list_d('borrowequ_choose');
        echo util_jsonUtil::encode($rows);
    }

    /*
      * �жϺ�ͬ״̬
      */
    function c_toconExastatusType() {
        $contractId = $_POST['contractId'];
        //��ͬ����״̬
        $contractExaType = $this->service->getOrderExaType($contractId);
        echo $contractExaType;
    }

    /*********************************������ת���� ����*******************************************************************************/

    /**
     * ������ �������� �ж� �������������
     */
    function c_borrowExa() {
        if (!empty ($_GET ['spid'])) {
        	$this->service->workflowCallBack($_GET ['spid']);
        }
        if ($_GET['urlType']) {
            echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

        } else {
            //��ֹ�ظ�ˢ��,���������תҳ��
            echo "<script>this.location='?model=projectmanagent_borrow_borrow&action=toProBorrowAll'</script>";
        }
    }

    /*******************************���   ��ʼ***************************************************/
    function c_toChange() {
        $change = isset($_GET['change']) ? $_GET['change'] : null;
        $changeLogDao = new model_common_changeLog ('borrow');
        if ($changeLogDao->isChanging($_GET['id'])) {
            msgGo("�ú�ͬ���ڱ�������У��޷����.");
        }
        $sql = "select count(id) as num from oa_borrow_order_equ where businessId = " . $_GET['id'] . "";
        $toOrder = $this->service->_db->getArray($sql);
        if ($toOrder[0]['num'] != 0) {
            msgGO("���������������Ѿ��ύת�������룬�޷����.");
        }
        //��ʱ��¼id
        $tempId = isset($_GET['tempId']) ? $_GET['tempId'] : '';
        //�ж��Ƿ������ʱ����ļ�¼
        if(empty($tempId)){
        	$sql = "select tempId,ExaStatus from oa_borrow_changlog where id = (select max(id) as id from oa_borrow_changlog " .
        			"where objType = 'borrow' and objId = ". $_GET['id'] ." and changeManId = '" . $_SESSION['USER_ID'] . "')";
        	$rs = $this->service->_db->getArray($sql);
        	$tempId = !empty($rs) && $rs[0]['ExaStatus'] != AUDITED ? $rs[0]['tempId'] : '';
        }
        $this->assign('tempId', $tempId);
        $borrowId = isset($_GET['tempId']) ? $_GET['tempId'] : $_GET['id'];
        $this->assign('borrowId', $borrowId);
        $rows = $this->service->get_d($borrowId);
        //����
        $rows ['file'] = $this->service->getFilesByObjId($rows ['id'], true);
        $rows = $this->service->initChange($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        //idʼ��ΪԴ��id
        if(isset($_GET['tempId'])){
        	$this->assign('id', $_GET['id']);
        }

        $SingleType = ($rows['SingleType'] == "NULL")? "" : $rows['SingleType'];
        switch ($SingleType) {
            case "" :
                $this->assign("SingleType", "��");
                $this->assign("singleCode", "��");
                break;
            case "chance" :
                $this->assign("SingleType", "�̻�");
                $chacneId = $rows['chanceId'];
                $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $rows['chanceCode'] . '</span>';
                $this->assign('singleCode', $code);
                break;
            case "order" :
                $this->assign('SingleType', "��ͬ");
                $orderId = $rows['contractId'];
                $orerType = $rows['contractType'];
                $orderCode = $rows['contractNum'];
                switch ($orerType) {
                    case "oa_sale_order" :
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=projectmanagent_order_order&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_service" :
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=engineering_serviceContract_serviceContract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_lease":
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_rdproject" :
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                }
                $this->assign('singleCode', $code);
                break;
        }


//		$this->showDatadicts ( array ('customerType' => 'KHLX' ), $rows ['customerType'] );
//		$this->showDatadicts ( array ('invoiceType' => 'FPLX' ), $rows ['invoiceType'] );
//		$this->showDatadicts ( array ('orderNature' => 'XSHTSX' ), $rows ['orderNature'] );
        $this->showDatadicts(array ('module' => 'HTBK'), $rows['module']);
        if ($change == "proChange") {
            $this->view('prochange');
        } else {
            $this->view('change');
        }


    }

    /**
     * �������
     */
    function c_change() {
        try {
            $change = isset($_GET['change']) ? $_GET['change'] : null;
            $row = $_POST ['borrow'];
            //����
            if($row['isSub'] == '0'){
            	$this->service->change_d($row);
            	msgGo("����ɹ���",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
            }else{
            	$oldrow = $this->service->getBorrowInfo($row['oldId'],array(0 => 'product'));
            	//�Ƚϱ������
            	$isDeff = $this->service->getDeff($row,$oldrow);
            	if($isDeff == 1){
            		//ֻ���±�ע
            		$f = $this->service->changeNoApp($row);
            		msgGo("����ɹ���",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
            	}else if($isDeff == 2){
            		msgGo("���κα����",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
            	}else{
            		$id = $this->service->change_d($row,'audit');
                    if(!empty($id)){
                        if ($change == "prochange") {
                            msgGo("���ύ����ȷ�ϣ�",'?model=projectmanagent_borrow_borrow&action=toProBorrowList');
//            			echo "<script>this.location='controller/projectmanagent/borrow/ewf_prochange_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
                        } else {
                            msgGo("���ύ����ȷ�ϣ�",'?model=projectmanagent_borrow_borrow&action=toMyBorrowList');
//            			echo "<script>this.location='controller/projectmanagent/borrow/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
                        }
                    }
            	}
            }
        } catch (Exception $e) {
            msgBack2("���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage());
        }
    }

    /**
     * �������ͨ���� ������
     */
    function c_confirmChangeToApprovalNo() {
        if (!empty ($_GET ['spid'])) {
        	//$this->service->workflowCallBack_change($_GET ['spid']);
            $this->service->workflowCallBack_changeNew($_GET ['spid']);
        }
        $urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
        //��ֹ�ظ�ˢ��
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /*******************************���  end***************************************************/

    /**
     * ���ݿͻ���ȡ�����ѽ���
     */
    function c_getAreaMoneyByCustomerId() {
        $areaMoney = $this->service->getAreaMoneyByCustomerId($_POST['customerId']);
        echo util_jsonUtil::encode($areaMoney);
    }

    /**
     * ����Ա����ȡ�ѽ�����Ϣ
     */
    function c_getUserDeptMoneyByUserId() {
        $moneyArr = $this->service->getUserMoney($_POST['userId']);
        echo util_jsonUtil::encode($moneyArr);
    }

    /*******************************���к�  ����***************************************************/

    /**
     * �����ù黹���� -���к�
     */
    function c_serialNumBorrowReturn() {
        $borrowLimit = $_GET['borrowLimit'];
        $serialnoNameStr = $_GET['serialName'];
        $serialnoIdStr = $_GET['serialId'];
        $serNameArr = array(); //���к�����
        $serIdArr = array(); //���к�ID
        $nameArr = explode(",", $serialnoNameStr);
        $IdArr = explode(",", $serialnoIdStr);
        foreach ($nameArr as $k1 => $v1) {
            array_push($serNameArr, $v1);
        }
        foreach ($IdArr as $k2 => $v2) {
            array_push($serIdArr, $v2);
        }
        foreach ($serNameArr as $key => $val) {
            $serialArr[$key]['name'] = $val;
            $serialArr[$key]['id'] = $serIdArr[$key];
        }
        $serial = $this->service->serialNum_d($serialArr);
        $this->assign("num", $_GET['num']);
        $this->assign("amount", $_GET['num']);
        $this->assign("serial", $serial);
        $this->assign("inputId", $_GET['inputId']);
        $this->assign("sid", $_GET['sid']);
        $this->display("serialNum");

    }

    //�ͻ�ת����
    function c_serialNum() {
        $borrowLimit = $_GET['borrowLimit'];
        if ($borrowLimit == "Ա��") {
            $type = "DBDYDLXJY";
        } else {
            $type = "DBDYDLXFH";
        }
        $allocationDao = new model_stock_allocation_allocation();
        $loanArr = $allocationDao->findLendDoc($type, $_GET['borrowId']);
        $serNameArr = array(); //���к�����
        $serIdArr = array(); //���к�ID
        foreach ($loanArr as $k => $v) {
            foreach ($v['items'] as $key => $val) {
                if ($val['productId'] == $_GET['productId']) {
                    $nameArr = explode(",", $val['serialnoName']);
                    $IdArr = explode(",", $val['serialnoId']);
                    foreach ($nameArr as $k1 => $v1) {
                        array_push($serNameArr, $v1);
                    }
                    foreach ($IdArr as $k2 => $v2) {
                        array_push($serIdArr, $v2);
                    }
                }

            }
        }
        foreach ($serNameArr as $key => $val) {
            $serialArr[$key]['name'] = $val;
            $serialArr[$key]['id'] = $serIdArr[$key];
        }
        $serial = $this->service->serialNum_d($serialArr);
        $this->assign("num", $_GET['num']);
        $this->assign("amount", $_GET['num']);
        $this->assign("serial", $serial);
        $this->assign("inputId", $_GET['inputId']);
        $this->assign("sid", $_GET['sid']);
        $this->display("serialNum");

    }

    //��ʾ��ѡ���к�
    function c_serialShow() {
        $serialArr = $_GET['serial'];
        $serialArr = explode(",", $serialArr);
        $serial = $this->service->serialshow_d($serialArr);
        $this->assign("serial", $serial);
        $this->display("serialShow");
    }

    //�鿴ҳ �鿴������ϵ� ���к�
    function c_serialNo() {
        $borrowId = $_GET['borrowId'];
        $ItemId = $_GET['itemId'];
        $productId = $_GET['productId'];
        $renew = isset($_GET['renew']) ? $_GET['renew'] : null;
        $allocationDao = new model_stock_allocation_allocation();
        $findSerSql = "select serialName,serialId from oa_borrow_equ where borrowId = " . $borrowId . " and id= " . $ItemId . "";
        $serialInfo = $this->service->_db->getArray($findSerSql);
        $serNameArr = array(); //���к�����
        $serIdArr = array(); //���к�ID
        if ($serialInfo[0]['serialName'] != '') {
            $nameArr = explode(",", $serialInfo[0]['serialName']);
            $IdArr = explode(",", $serialInfo[0]['serialId']);
            foreach ($nameArr as $k1 => $v1) {
                array_push($serNameArr, $v1);
            }
            foreach ($IdArr as $k2 => $v2) {
                array_push($serIdArr, $v2);
            }
        } else {
            $loanArr = $allocationDao->findLendDoc("DBDYDLXJY", $borrowId);
            if (empty($loanArr)) {
                $loanArr = $allocationDao->findLendDoc("DBDYDLXFH", $borrowId);
            }
            foreach ($loanArr as $k => $v) {
                foreach ($v['items'] as $key => $val) {
                    if ($val['productId'] == $productId) {

                        $nameArr = explode(",", $val['serialnoName']);
                        $IdArr = explode(",", $val['serialnoId']);
                        foreach ($nameArr as $k1 => $v1) {
                            array_push($serNameArr, $v1);
                        }
                        foreach ($IdArr as $k2 => $v2) {
                            array_push($serIdArr, $v2);
                        }
                    }

                }
            }
        };
        foreach ($serNameArr as $key => $val) {
            $serialArr[$key]['name'] = $val;
            $serialArr[$key]['id'] = $serIdArr[$k];
        }
        $serial = $this->service->serialNum_d($serialArr);

        $this->assign("num", $_GET['num']);
        $this->assign("amount", $_GET['amount']);
        $this->assign("renew", $renew);
        $this->assign("serial", $serial);
        $this->display("serialNo");
    }

    /***********************************ת��  ��ʼ*******************************************************/
    /**
     * ת������ҳ
     */
    function c_subtenancyApply() {
        $proBorrowId = $_GET['borrowId'];
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $dao = new model_projectmanagent_borrow_borrowequ();
        $borrowequ = $dao->subtenancyTable($rows['borrowequ']);
        $this->assign('borrowequ', $borrowequ[1]);
        $this->assign('productNumber', $borrowequ[0]);

        $this->assign('ApplyBeginTime', day_date);
        $this->assign('borrowInput', BORROW_INPUT);

        $this->display('subtenancyapply');
    }

    /**
     * ת����������
     */
    function c_subtenancyAdd() {
        $borrowInfo = $_POST [$this->objName];
        if ($borrowInfo ['borrowInput'] == "1") {
            $chanceCodeDao = new model_common_codeRule();
            if ($borrowInfo['limits'] == '�ͻ�') {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "cus");
                $borrowInfo['createName'] = $borrowInfo['salesName'];
                $borrowInfo['createId'] = $borrowInfo['salesNameId'];
            } else {
                $borrowInfo['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "pro");
            }
            $id = $this->service->add_d($borrowInfo);
        } else if ($borrowInfo ['borrowInput'] == "0") {
            $id = $this->service->add_d($borrowInfo);
        } else {
            msgGo('���ҹ���Աȷ�Ͽ��Ʊ�����ɹ����"BORROW_INPUT"ֵ�Ƿ���ȷ');
        }
        if ($id) {
            succ_show('controller/projectmanagent/borrow/ewf_subtenancyBorrow.php?actTo=ewfSelect&billId=' . $id);
        } else {
//				msgRF ( '���ʧ�ܣ�');
        }
    }

    /**
     * ת�� �޸�
     */
    function c_subtenancyEdit() {
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $dao = new model_projectmanagent_borrow_borrowequ();
        $borrowequ = $dao->subtenancyTable($rows['borrowequ']);
        $this->assign('borrowequ', $borrowequ[1]);
        $this->assign('productNumber', $borrowequ[0]);
        $this->display('subtenancyedit');
    }

    /**
     * ת��鿴ҳ
     */
    function c_subtenancyView() {
        $rows = $this->service->get_d($_GET['id']);

        //��Ⱦ�ӱ�
        $rows = $this->service->initView($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('subtenancyView');
    }

    /**
     * ת���޸Ĵ���
     */
    function c_subEdit($isEditInfo = false) {
        $object = $_POST [$this->objName];
        if ($this->service->edit_d($object)) {
            msgRF('�޸ĳɹ���');
        }
    }

    /**
     * ת��ȷ��
     */
    function c_subtenancyAff() {
        $rows = $this->service->get_d($_GET['id']);

        //��Ⱦ�ӱ�
        $rows = $this->service->initView($rows);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }

        $this->display('subtenancyAff');
    }

    //ת�� ȷ�Ϸ���
    function c_subAff() {
        $borrowId = $_POST ["borrowId"];
        $this->service->updateSubAff($borrowId); //ȷ�Ϻ�ı䵥��״̬

        $rows = $this->service->get_d($borrowId);
        $exaStatus = $rows['ExaStatus']; //��ȡת�赥������״̬

        if ($exaStatus == '���') {
            $this->service->updateExaTomail($borrowId);
        }
        msgRF('ȷ�ϳɹ���');
    }


    /**
     * ת����������ͨ���� ���õķ���
     */
    function c_updateSubtenancy() {
    	$this->service->workflowCallBack_sub($_GET ['spid']);
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * ת��Tab �鿴ҳ
     */
    function c_subtenancyViewList() {
        $this->assign("subBorrowId", $_GET['id']);
        $this->display('subtenancyViewList');
    }

    /***********************************ת��  ����*******************************************************/
    /***********************************�����ñ���******BEGIN*************************************************/
    function c_borrowReport() {
        $this->display('borrowReport');
    }

    //����json-����
    function c_borrowReportJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
//		$service->searchArr['workFlowCode'] = $service->tbl_name;
        $service->asc = false;
        $rows = $service->pageBySqlId('borrowReport_master');
        foreach ($rows as $k => $v) {
            //��ȡԱ�����ý����
            $moneyLimit = $service->moneyConfig($v['userId']);
            $rows[$k]['moneyLimit'] = $moneyLimit;
            $money = $rows[$k]['allMoney'] - $rows[$k]['moneyLimit'];
            if ($money > 0) {
                $rows[$k]['isOverrun'] = "��";
                $rows[$k]['overrunMoeny'] = $money;
            } else {
                $rows[$k]['isOverrun'] = "��";
                $rows[$k]['overrunMoeny'] = 0;
            }
        }
        //��ȡ����������ñ���ĳ�ʼ������
        $rows = $service->getInitializeInfo($rows);
        foreach ($rows as $key => $val) {
            if (empty($val['id'])) {
                unset($rows[$key]);
            }
        }

        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    //����json--�ӱ�
    function c_borrowreportTable() {
        $service = $this->service;

        $service->getParam($_REQUEST);

        $rows = $service->pageBySqlId('borrowReport_table');

        //��ȡ����������ñ���ĳ�ʼ������
        $rows = $service->getInitializeInfoTable($rows, $service->searchArr['createId']);

        $arr = array();
        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }

    /***********************************�����ñ���******END*************************************************/

    /**********************************�����õ���**********************************************************/
    /**
     * �ϴ�EXCEL
     */
    function c_upExcel() {
        set_time_limit(0);
        $objNameArr = array(
            0 => 'beginTime', //���ʱ�䣨���ÿ�ʼ���ڣ�
            1 => 'closeTime', //�黹ʱ��
            2 => 'useType', //��;
            3 => 'K3Code', //K3����
            4 => 'productName', //��������
            5 => 'productModel', //�ͺ�
            6 => 'number', //����
            7 => 'deptName', //��������
            8 => 'customerName', //�ͻ�����
            9 => 'userName', //������
            10 => 'seriesNumber', //��Ʒ���к�
            11 => 'remark', //��ע
            23 => 'rowsIndex'
        );

        $this->c_addExecel($objNameArr);

    }

    /**
     * �ϴ�EXCEl������������
     */
    function c_addExecel($objNameArr) {
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $customerDao = new model_customer_customer_customer ();
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $upexcel = new model_contract_common_allcontract ();
            $excelData = $upexcel->upExcelData($filename, $temp_name);
            spl_autoload_register('__autoload'); //�ı������ķ�ʽ

            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }

//                echo "<pre>";print_r($objectArr);exit;

                $arrinfo = array(); //������Ϣ
                $cusArr = array(); //�ͻ�����������
                $proArr = array(); //Ա������������
                foreach ($objectArr as $val) {
                    if (empty($val['K3Code'])) {
                        array_push($arrinfo, array("rowsIndex" => $val['rowsIndex'], "orderCode" => "�������ƣ�" . $val['productName'], "cusName" => $val['userName'], "result" => "����ʧ�ܣ�K3���ϱ���Ϊ��"));
                        continue;
                    }
                    if (empty($val['userName'])) {
                        array_push($arrinfo, array("rowsIndex" => $val['rowsIndex'], "orderCode" => "�������ƣ�" . $val['productName'], "cusName" => $val['userName'], "result" => "����ʧ�ܣ�û��Ա������"));
                        continue;
                    }
                    if (!empty($val['customerName'])) { //�пͻ��Ŀͻ�������
                        $cusArr[] = $val;
                    }
                    if (empty($val['customerName'])) { // û�пͻ���Ա��������
                        $proArr[] = $val;
                    }
                }
                $cusBorrowArr = $this->CusdisposeData($cusArr);
                $proBorrowArr = $this->ProdisposeData($proArr);
                //����ͻ������ò�������Ϣ
                foreach ($cusBorrowArr as $val) {
                    //�жϿͻ��Ƿ����
                    $customerId = $customerDao->findCid($val ['customerName']);
                    if (empty($customerId)) {
                        $rowIndexStr = $val['rowIndexStr'];
                        $rowsIndexArr = explode(",", $rowIndexStr);
                        foreach ($rowsIndexArr as $rowsIndex) {
                            if (!empty($rowsIndex)) {
                                array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "�ͻ����ƣ� " . $val['customerName'] . " , " . "���ʱ�䣺" . $val['beginTime'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ��ͻ�Ϊ�ջ򲻴���"));
                            }
                        }
                    } else {
                        $tempIndexArr = array();
                        foreach ($val['borrowequ'] as $k => $v) {
                            if (empty($v['productId']) || empty($v['productNoKS'])) {
                                $rowsIndexArr = explode(",", $v['rowsIndex']);
                                foreach ($rowsIndexArr as $rowsIndex) {
                                    if (!empty($rowsIndex)) {
                                        array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "���ϱ���: " . $v['productNoKS'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ�������Ϣ�����ڻ�K3���ϴ������"));
                                        unset($val['borrowequ'][$k]);
                                    }
                                }
                            }else{
                                array_push($tempIndexArr,$v['rowsIndex']);
                            }
                        }
                        if (empty($val['borrowequ'])) {
                            continue;
                        }
                        $val['rowIndexStr'] = implode(',',$tempIndexArr);
                        if (!empty($val)) {
                            //�жϽ������Ƿ���ְ���ظ�
                            $userId = $this->borrower($val['applyUserName']);
                            $cusBorrowAdd = $this->cusBorrowInfo($val, $userId, $customerId);
                            $id = $this->service->add_d($cusBorrowAdd);
                            if ($id) {
                                $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                                $rowsIndexArr = explode(",", $rowIndexStr);
                                foreach ($rowsIndexArr as $rowsIndex) {
                                    if (!empty($rowsIndex)) {
                                        array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "����ɹ�", "cusName" => $val['applyUserName'], "result" => "����ɹ�"));
                                    }
                                }
                            } else {
                                $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                                $rowsIndexArr = explode(",", $rowIndexStr);
                                foreach ($rowsIndexArr as $rowsIndex) {
                                    if (!empty($rowsIndex)) {
                                        array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "����ʱ�䣺" . $val['createTime'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ�δ֪ԭ��"));
                                    }
                                }
                            }
                        }
                    }
                }
                //����Ա�������ò�������Ϣ
                foreach ($proBorrowArr as $val) {
                    $tempIndexArr = array();
                    foreach ($val['borrowequ'] as $k => $v) {
                        if (empty($v['productId']) || empty($v['productNoKS'])) {
                            $rowsIndexArr = explode(",", $v['rowsIndex']);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "���ϱ���: " . $v['productNoKS'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ�������Ϣ�����ڻ�K3���ϴ������"));
                                    unset($val['borrowequ'][$k]);
                                }
                            }
                        }else{
                            array_push($tempIndexArr,$v['rowsIndex']);
                        }
                    }
                    if (empty($val['borrowequ'])) {
                        continue;
                    }
                    $val['rowIndexStr'] = implode(',',$tempIndexArr);
                    if (!empty($val)) {
                        // ��ѯ�û�id
                        $userId = $this->borrower($val['applyUserName']);
                        $cusBorrowAdd = $this->proBorrowInfo($val, $userId, $customerId);
                        $id = $this->service->add_d($cusBorrowAdd);
                        if ($id) {
                            $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "����ɹ�", "cusName" => $val['applyUserName'], "result" => "����ɹ�"));
                                }
                            }
                        } else {
                            $rowIndexStr = $cusBorrowAdd['rowIndexStr'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => "����ʱ�䣺" . $val['createTime'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ�δ֪ԭ��"));
                                }
                            }
                        }
                    }
                }
                $result = array();
                foreach ($arrinfo as $value) {
                    $result[$value['rowsIndex']] = $value;
                }
                sort($result);
                if ($result) {
                    echo util_excelUtil::showResultBorrow($this->sysSortArray($arrinfo, "rowsIndex"), "������", array("�к�", "�����Ϣ", "������", "���"));
                }
            } else {
                echo "�ļ������ڿ�ʶ������!";
            }
        } else {
            echo "�ϴ��ļ����Ͳ���EXCEL!";
        }
    }

    function sysSortArray($ArrayData, $KeyName1, $SortOrder1 = "SORT_ASC", $SortType1 = "SORT_REGULAR") {
        if (!is_array($ArrayData)) {
            return $ArrayData;
        }

        // Get args number.
        $ArgCount = func_num_args();

        // Get keys to sort by and put them to SortRule array.
        for ($I = 1; $I < $ArgCount; $I++) {
            $Arg = func_get_arg($I);
            if (!eregi("SORT", $Arg)) {
                $KeyNameList[] = $Arg;
                $SortRule[] = '$' . $Arg;
            } else {
                $SortRule[] = $Arg;
            }
        }

        // Get the values according to the keys and put them to array.
        foreach ($ArrayData AS $Key => $Info) {
            foreach ($KeyNameList AS $KeyName) {
                ${$KeyName}[$Key] = $Info[$KeyName];
            }
        }

        // Create the eval string and eval it.
        $EvalString = 'array_multisort(' . join(",", $SortRule) . ',$ArrayData);';
        eval ($EvalString);
        return $ArrayData;
    }

    /**
     * ת��ʱ���
     * @param $timestamp
     * @return bool|string
     */
    function transitionTime($timestamp) {
        $time = "";
        if (!empty($timestamp)) {
            if (mktime(0, 0, 0, 1, $timestamp - 1, 1900) > '2000-01-01') {
                $time = date("Y-m-d", mktime(0, 0, 0, 1, $timestamp - 1, 1900));
            } else {
                $time = $timestamp;
            }
        }
        return $time;
    }

    /**
     * �жϽ������Ƿ��ظ�����ְ
     * @param $name
     * @return string
     */
    function borrower($name) {
        if($name == '������') return 'ruan.mingyan';
        if($name == '����') return 'xing.wang';
        $Dao = new model_common_otherdatas();
        $userId = $Dao->getUserID($name);
        $userInfoArr = $Dao->getUserDatas($userId[0]['USER_ID']);
        if ($userInfoArr['HAS_LEFT'] == 1) {
            return "left";
        } else {
            return $userId[0]['USER_ID'];
        }
    }

    /**
     * ����ͻ�����������
     * @param $cusBorrow
     * @param $userId
     * @param $customerId
     * @return array
     */
    function cusBorrowInfo($cusBorrow, $userId, $customerId) {
        $userId = $userId ? $userId : $cusBorrow['applyUserName'];
        $addArr = array();
        $chanceCodeDao = new model_common_codeRule();

        $addArr['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "cus");
        $addArr['Type'] = $cusBorrow['Type'];
        $addArr['customerName'] = $cusBorrow['customerName'];
        $addArr['limits'] = "�ͻ�";
        $addArr['beginTime'] = $cusBorrow['beginTime'];
        $addArr['closeTime'] = $cusBorrow['closeTime'];
        $addArr['salesName'] = $cusBorrow['applyUserName'];
        $addArr['salesNameId'] = $userId;
        $addArr['remark'] = $cusBorrow['remark'];
        $addArr['reason'] = $cusBorrow['reason'];
        $addArr['createName'] = $cusBorrow['applyUserName'];
        $addArr['createId'] = $userId;
        $addArr['createTime'] = $cusBorrow['createTime'];
        $addArr['ExaStatus'] = "���";
        if (empty($customerId[0]['id'])) {
            $addArr['customerId'] = 0;
        } else {
            $addArr['customerId'] = $customerId[0]['id'];
        }

        $addArr['DeliveryStatus'] = "YFH";
        $addArr['initTip'] = "1";
        $addArr['rowIndexStr'] = $cusBorrow['rowIndexStr'];
        $addArr['borrowequ'] = $cusBorrow['borrowequ'];

        return $addArr;
    }

    /**
     * ����Ա������������
     * @param $proBorrow
     * @param $userId
     * @param $customerId
     * @return array
     */
    function proBorrowInfo($proBorrow, $userId, $customerId) {
        $userId = $userId ? $userId : $proBorrow['applyUserName'];
        $Dao = new model_common_otherdatas();
        $userInfoArr = $Dao->getUserDatas($userId);
        $chanceCodeDao = new model_common_codeRule();

        $addArr = array();
        $addArr['Code'] = $chanceCodeDao->borrowCode("oa_borrow_borrow", "pro");
        $addArr['Type'] = $proBorrow['Type'];
        $addArr['limits'] = "Ա��";
        $addArr['beginTime'] = $proBorrow['beginTime'];
        $addArr['closeTime'] = $proBorrow['closeTime'];
        $addArr['remark'] = $proBorrow['remark'];
        $addArr['createName'] = $proBorrow['applyUserName'];
        $addArr['createId'] = $userId;
        $addArr['createTime'] = $proBorrow['createTime'];
        $addArr['ExaStatus'] = "���";
        $addArr['customerId'] = $customerId[0]['id'];
        $addArr['reason'] = $proBorrow['reason'];
        $addArr['createSection'] = $userInfoArr['DEPT_NAME'];
        $addArr['createSectionId'] = $userInfoArr['DEPT_ID'];
        $addArr['DeliveryStatus'] = "YFH";
        $addArr['initTip'] = "1";
        $addArr['rowIndexStr'] = $proBorrow['rowIndexStr'];
        $addArr['borrowequ'] = $proBorrow['borrowequ'];

        return $addArr;
    }

    /**
     * �����ñ��Ψһ���ж�
     * @param $Code
     * @return mixed
     */
    function borrowCodeOne($Code) {
        return $this->service->_db->getArray("select id from oa_borrow_borrow where Code = '$Code'");
    }

    /**
     * ��������
     * @param $objectArr
     * @return array
     */
    function CusdisposeData($objectArr) {
        $codeArr = array(); //��������
        $codeInfoArr = array();
        foreach ($objectArr as $key => $val) {
            $yearMonth = date("Ym", strtotime($val['beginTime']));
            $codeArr[$key] = $yearMonth . $val['userName'] . $val['customerName'];
        }
        $codeArr = array_flip($codeArr);

        //����������Ϣ
        $productInfoTempArr = $this->service->findProductInfo();
        $productInfoArr = array();
        foreach ($productInfoTempArr as $val) {
            $productInfoArr[$val['ext2']] = $val;
        }
        foreach ($codeArr as $k => $v) {
            $seriesNumberArr = array();
            $remarkArr = array();
            foreach ($objectArr as $val) {
                $yearMonth = date("Ym", strtotime($val['beginTime']));
                if ($yearMonth . $val['userName'] . $val['customerName'] == $k) {
                    $codeInfoArr[$k]['KCode'] = "";
                    $codeInfoArr[$k]['createTime'] = date("Y-m-d"); //���ݴ������ڣ��ݶ����뵱��
                    $codeInfoArr[$k]['Type'] = $val['useType'];
                    $codeInfoArr[$k]['applyUserName'] = $val['userName'];
                    $codeInfoArr[$k]['beginTime'] = $this->transitionTime($val['beginTime']);
                    $codeInfoArr[$k]['closeTime'] = $this->transitionTime($val['closeTime']);
                    $codeInfoArr[$k]['closeTimeTrue'] = ""; //����ģ����û�У���ʱΪ��
                    $codeInfoArr[$k]['dept'] = $val['deptName'];
                    $codeInfoArr[$k]['customerName'] = $val['customerName'];
                    if ($val['seriesNumber'] && !in_array($val['seriesNumber'], $seriesNumberArr)) {
                        $seriesNumberArr[] = mysql_real_escape_string($val['seriesNumber']);
                    }
                    if ($val['remark'] && !in_array($val['remark'], $remarkArr)) {
                        $remarkArr[] = mysql_real_escape_string($val['remark']);
                    }
                    if (!isset($codeInfoArr[$k]['rowIndexStr'])) {
                        $codeInfoArr[$k]['rowIndexStr'] = $val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['rowIndexStr'] .= ",".$val['rowsIndex'];
                    }

                    if (!empty($codeInfoArr[$k]['borrowequ'][$val['K3Code']])) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['number'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['executedNum'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['rowsIndex'] .= ",".$val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']] = array(
                            "productNoKS" => $val['K3Code'],
                            "productNameKS" => $val['productName'],
                            "productId" => $productInfoArr[$val['K3Code']]['id'],
                            "productName" => $productInfoArr[$val['K3Code']]['productName'],
                            "productNo" => $productInfoArr[$val['K3Code']]['productCode'],
                            "productModel" => $productInfoArr[$val['K3Code']]['pattern'],
                            "number" => $val['number'],
                            "executedNum" => $val['number'],
                            "lentDate" => $val['beginTime'],
                            "lentType" => $val['useType'],
                            "rowsIndex" => $val['rowsIndex']
                        );
                    }

                    if ($val['seriesNumber']) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] .=
                            $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] ? '/' . $val['seriesNumber'] :
                                $val['seriesNumber'];
                    }
                }
            }
            if (!empty($seriesNumberArr)) {
                $codeInfoArr[$k]['reason'] = implode('/', $seriesNumberArr);
            }
            if (!empty($remarkArr)) {
                $codeInfoArr[$k]['remark'] = implode('/', $remarkArr);
            }
        }
        return $codeInfoArr;
    }

    function ProdisposeData($objectArr) {
        $codeArr = array(); //��������
        $codeInfoArr = array();
        foreach ($objectArr as $key => $val) {
            $yearMonth = date("Ym", strtotime($val['beginTime']));
            $codeArr[$key] = $yearMonth . $val['userName'];
        }
        $codeArr = array_flip($codeArr);
        //����������Ϣ
        $productInfoTempArr = $this->service->findProductInfo();
        $productInfoArr = array();
        foreach ($productInfoTempArr as $val) {
            $productInfoArr[$val['ext2']] = $val;
        }

        foreach ($codeArr as $k => $v) {
            $seriesNumberArr = array();
            $remarkArr = array();
            foreach ($objectArr as $val) {
                $yearMonth = date("Ym", strtotime($val['beginTime']));
                if ($yearMonth . $val['userName'] == $k) {
                    $codeInfoArr[$k]['KCode'] = "";
                    $codeInfoArr[$k]['createTime'] = date("Y-m-d"); //���ݴ������ڣ��ݶ����뵱��
                    $codeInfoArr[$k]['Type'] = $val['useType'];
                    $codeInfoArr[$k]['applyUserName'] = $val['userName'];
                    $codeInfoArr[$k]['beginTime'] = $this->transitionTime($val['beginTime']);
                    $codeInfoArr[$k]['closeTime'] = $this->transitionTime($val['closeTime']);
                    $codeInfoArr[$k]['closeTimeTrue'] = ""; //����ģ����û�У���ʱΪ��
                    $codeInfoArr[$k]['dept'] = $val['deptName'];
                    $codeInfoArr[$k]['customerName'] = $val['customerName'];
                    if ($val['seriesNumber'] && !in_array($val['seriesNumber'], $seriesNumberArr)) {
                        $seriesNumberArr[] = mysql_real_escape_string($val['seriesNumber']);
                    }
                    if ($val['remark'] && !in_array($val['remark'], $remarkArr)) {
                        $remarkArr[] = mysql_real_escape_string($val['remark']);
                    }
                    if (!isset($codeInfoArr[$k]['rowIndexStr'])) {
                        $codeInfoArr[$k]['rowIndexStr'] = $val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['rowIndexStr'] .= ",".$val['rowsIndex'];
                    }
                    if (!empty($codeInfoArr[$k]['borrowequ'][$val['K3Code']])) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['number'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['executedNum'] += $val['number'];
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['rowsIndex'] .= ",".$val['rowsIndex'];
                    } else {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']] = array(
                            "productNoKS" => $val['K3Code'],
                            "productNameKS" => $val['productName'],
                            "productId" => $productInfoArr[$val['K3Code']]['id'],
                            "productName" => $productInfoArr[$val['K3Code']]['productName'],
                            "productNo" => $productInfoArr[$val['K3Code']]['productCode'],
                            "productModel" => $productInfoArr[$val['K3Code']]['pattern'],
                            "remark" => $val['remark'],
                            "number" => $val['number'],
                            "executedNum" => $val['number'],
                            "lentDate" => $val['beginTime'],
                            "lentType" => $val['useType'],
                            "rowsIndex" => $val['rowsIndex']
                        );
                    }

                    if ($val['seriesNumber']) {
                        $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] .=
                            $codeInfoArr[$k]['borrowequ'][$val['K3Code']]['serialName'] ? '/' . $val['seriesNumber'] :
                                $val['seriesNumber'];
                    }
                }
            }
            if (!empty($seriesNumberArr)) {
                $codeInfoArr[$k]['reason'] = implode('/', $seriesNumberArr);
            }
            if (!empty($remarkArr)) {
                $codeInfoArr[$k]['remark'] = implode('/', $remarkArr);
            }
        }
        return $codeInfoArr;
    }

    /**********************************�����õ���*******END*************************************************/

    /**
     * ��ʼ������������
     */
    function c_initializeBorrowData() {
        $this->service->initializeBorrowData_d();
    }

    /*************************************�豸�ܻ�� start **************************************/
    /**
     * ��ͬ�����豸-�ƻ�ͳ���б�
     */
    function c_shipEquList() {
        $limits = isset($_GET['limits']) ? $_GET['limits'] : "";
        $equNo = isset($_GET['productCode']) ? $_GET['productCode'] : "";
        $equName = isset($_GET['productName']) ? $_GET['productName'] : "";
        $searchArr = array();
        if ($equNo != "") {
            $searchArr['productCodeEqu'] = $equNo;
        }
        if ($equName != "") {
            $searchArr['productNameEqu'] = $equName;
        }
        $searchArr['limits2'] = $_GET['limits'];
        $service = $this->service;
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
        $service->__SET('groupBy', "p.productId,p.productNumb");
        $rows = $service->pageEqu_d();
        $this->pageShowAssign();

        $this->assign('equNumb', $equNo);
        $this->assign('equName', $equName);
        $this->assign('limits', $limits);
        $this->assign('list', $this->service->showEqulist_s($rows));
        $this->display('list-equ');
        unset($this->show);
        unset($service);
    }

    /***********************************�豸�ܻ�� end *********************************/


    /**
     * Ա�������� ���
     */
    function c_rollBack() {
        $rows = $this->service->get_d($_GET['id']);

        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }//echo"<pre>";print_r($rows);exit();
        $tempId = '';
        if($rows['isTemp'] == 1){
            $tempId = $rows['id'];
            $this->assign('borrowId', $rows['originalId']);
        }else{
            if($rows['isSubAppChange'] == 1 && $rows['dealStatus'] == 2){
                $tempId = $this->service->findChangeId($rows['id']);
            }
            $this->assign('borrowId', $_GET['id']);
        }

        //��ȡĬ�Ϸ�����
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->assign('executeName', $mailUser['borrow_execute']['executeName']);
        $this->assign('executeNameId', $mailUser['borrow_execute']['executeNameId']);
        $this->assign('proposer', $rows['createName']);
        $this->assign('proposerId', $rows['createId']);
        $this->assign('tempId', $tempId);
        $this->display('rollback');
    }

    /**
     * �߼�����
     */
    function c_search() {
        $this->assign('gridName', $_GET['gridName']);
        $this->assign('gridType', $_GET['gridType']);
        $this->view('search');
    }

    /**
     * �������豸�б�ӱ�
     */
    function c_listPageJson(){
    	$service = $this->service;
    	$service->getParam ( $_POST );
    	$rows=$service->list_d();
    	$arr ['collection'] = $rows;
    	echo util_jsonUtil::encode ( $arr);
    }

    /**
     * �����̻�(������Ȩ���½�����,���ۺ�������̻�)
     */
    function c_toRelateChance() {
    	$service = $this->service;
    	$rows = $service->get_d($_GET['id']);
    	//��ȡ���������ۼƽ�����ʽ��Ϊǧ��λ
    	$rs = $service->getPersonalEquMoney($rows['createName']);
    	$equMoney = number_format($rs[0]['equMoney'],2);
    	$this->assign('id', $_GET['id']);
    	$this->assign('equMoney', $equMoney);
    	$this->assign('SingleType', $rows['SingleType']);
    	$this->assign('singleCode', $rows['singleCode']);
    	$this->assign('chanceCode', $rows['chanceCode']);
    	$this->assign('chanceId', $rows['chanceId']);
    	$this->view('relatetochance');
    }

    /**
     * �����̻�(������Ȩ���½�����,���ۺ�������̻�)
     */
    function c_relateChance() {
        $object = $_POST [$this->objName];
        $act = isset ($_GET ['act']) ? $_GET ['act'] : null;
        if ($this->service->updateById($object)) {
            if ($_GET['act'] == 'app') {
 				succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $object['id']);
            }
            if ($act == 'app') {
                msg('�ύ�ɹ���');
            } else {
                msg('����ɹ���');
            }

        }
    }

    /**
     * �ж��Ƿ���ڷ�����¼
     */
    function c_isExistOutplan() {
    	$outplan = new model_stock_outplan_outplan();
    	$rs = $outplan->find(array('docId' => $_POST ['id'],'docType' => 'oa_borrow_borrow'));
    	if(!empty($rs)){
    		echo 1;
    	}else{
    		echo 0;
    	}
    }

    /**
     * ��дajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
     */
    function c_ajaxdeletes() {
    	//$this->permDelCheck ();
    	try {
    		$this->service->deletes_d ( $_POST ['id'] );
    		echo 1;
    	} catch ( Exception $e ) {
    		echo 0;
    	}
    }

    /**
     * �����̻���Ϣ
     */
    function c_pageJsonWithChance() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ('select_withChance');
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

    /**
     * �ж��Ƿ��ܹر�
     */
    function c_isCanClose() {
		echo $this->service->isCanClose_d($_POST ['id']);
    }

    /**
     *  �������� �������������ϴ���������£�
     */
    function c_handleDispose()
    {
        $handleType = $_GET['handleType'];
        if ($handleType == "YSWJ") {
            $this->assign("handle", "1");
            $this->c_toCheckFile();
        }
    }

    function c_toCheckFile()
    {
        $id = isset($_GET['id'])? str_replace("K","",$_GET['id']) : '';
        $this->assign("serviceId", $id);
        $text = $_GET['type'] . "3";
        $this->assign("serviceType3", $text);
        $this->display('checkfile');
    }

    /**
     * ���������ϴ�����
     */
    function c_uploadfile()
    {
        $row = $_POST[$this->objName];
        if(isset($row['checkFile'])){
            $upSql = "update oa_borrow_borrow set checkFile = '��' where id= ".$row['serviceId'];
            $this->service->_db->query($upSql);
        }
        $id = $this->service->uploadfile_d($row);
        if ($id && $_GET['handle'] == "1") {
            $dao = new model_contract_contract_aidhandle();
            $dao->add_d(array("contractId" => $row['serviceId'], "handleType" => "KJYYSWJ"));
            msg('��ӳɹ���');
        } else {
            if ($id) {
                msg('��ӳɹ���');
            } else {
                msg('���ʧ�ܣ�');
            }
        }
    }

    /**
     * ��ת����ȷ������ҳ��
     */
    function c_toConfirmEqu(){
//        ini_set("display_errors",1);
        $needSalesConfirm = isset($_GET['needSalesConfirm'])? $_GET['needSalesConfirm'] : '';
        $salesConfirmId = isset($_GET['salesConfirmId'])? $_GET['salesConfirmId'] : '';
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $borrowequDao = new model_projectmanagent_borrow_borrowequ();
        $borrowOldId = 0;

        switch($needSalesConfirm){
            case '1':
                $obj = $this->service->getBorrowInfo($id);
                $obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $id . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
                $singleArr = $this->service->getSingleCodeURL($obj);
                $obj['SingleType'] = $singleArr['SingleType'];
                $obj['singleCode'] = $singleArr['singleCode'];
                $products = $borrowequDao->showItemView($obj['product']);
                $this->assign('docType', 'oa_borrow_borrow');
                $this->assign("products", $products);
                break;
            case '2':
                $borrowOldId = $id;
                $id = $salesConfirmId;
                $obj = $this->service->getBorrowInfoWithTemp($id);
                $obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $id . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
                $products = $borrowequDao->showItemView($obj['product']);
                $singleArr = $this->service->getSingleCodeURL($obj);
                $obj['SingleType'] = $singleArr['SingleType'];
                $obj['singleCode'] = $singleArr['singleCode'];
                $this->assign('docType', 'oa_borrow_borrow');
                $this->assign("products", $products);
                break;
            case '3':
                $obj = $this->service->getBorrowInfo($id);
                $obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $id . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
                $singleArr = $this->service->getSingleCodeURL($obj);
                $obj['SingleType'] = $singleArr['SingleType'];
                $obj['singleCode'] = $singleArr['singleCode'];
                $products = $borrowequDao->showItemView($obj['product']);
                $this->assign('docType', 'oa_borrow_borrow');
                $this->assign("products", $products);

                unset($obj['equEstimateTax']);
                $costConfirmSql = "select * from oa_borrow_cost where borrowId = '{$id}' and linkId = '{$salesConfirmId}'";
                $costConfirm = $this->service->_db->get_one($costConfirmSql);
                $confirmMoneyTax = $costConfirm['confirmMoneyTax'];
                $this->assign("equEstimateTax", $confirmMoneyTax);
                break;
        }

        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if(isset($obj['borrowequ'])){
            $linkId = "";
            foreach ($obj['borrowequ'] as $k => $equ){
                if(isset($equ['linkId']) && !empty($equ['linkId']) && isset($equ['isTemp']) && $equ['isTemp'] == 0){
                    $linkId = $equ['linkId'];
                }else{
                    if(!empty($salesConfirmId) && $needSalesConfirm == 3){
                        $linkId = $salesConfirmId;
                    }
                }
            }
            $this->assign("linkId", $linkId);
        }

        $this->assign("borrowOldId",$borrowOldId);
        $this->assign("needSalesConfirm",$needSalesConfirm);
        $this->assign("salesConfirmId",$salesConfirmId);
        $this->view('toConfirmEqu');
    }

    /**
     * ����ȷ�Ϸ�������
     */
    function c_confirmEqu(){
        $service = $this->service;
        $postForm = $_POST['equConfirm'];
        $act = $postForm['confirmAct'];
        $needSalesConfirm = $postForm['needSalesConfirm'];// ����ȷ������
        $salesConfirmId = $postForm['salesConfirmId'];// ��Ҫ������ҵ������ID������:ԭ��ID, ���:��ʱ��ID, �������:��ʱlinkId��
        switch($act){
            case 'audit':// �ύ
                $resultArr = $service->salesConfirmEqu($needSalesConfirm,$salesConfirmId);
                if($resultArr['result'] && isset($resultArr['url'])){
                    succ_show($resultArr['url']);
                }
                break;
            case 'back':// ���
                $result = $service->salesBackEqu($needSalesConfirm,$salesConfirmId);
                if($result){
                    msgRF('�Ѵ��, �ȴ���������ȷ������!');
                }else{
                    msg('���ʧ��,�����ԣ�');
                }
                break;
        }
    }

    /**
     * ��ת��������������ҳ��
     */
    function c_toNoticeDelayApply(){
        $rows = $this->service->get_d($_GET['id']);
        foreach ($rows as $key => $val) {
            $this->assign($key, $val);
        }
        // Ĭ���ʼ���Ϣ
        $mailTitle = $rows['Code'].'��������';
        $mailContent = "��λ�ã���{$rows['salesName']}������Ľ����ã���{$rows['Code']}����{$rows['customerName']}�����������ڣ���ֹ���ڸ���Ϊ��{$rows['closeTime']}��";

        // ��������嵥��Ϣ
        $borrowEquDao = new model_projectmanagent_borrow_borrowequ();
        $borrowEqu = $borrowEquDao->findAll(array("borrowId"=>$_GET['id'],"isTemp" => 0,"isDel" => 0));
        if($borrowEqu){
            $detailStr = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center>".
                "<tr><th>���</th><th>���ϱ��</th><th>��������</th><th>���ϰ汾/�ͺ�</th><th>����</th><th>��ִ������</th><th>�ѹ黹����</th></tr>";
            foreach ($borrowEqu as $k => $v){
                $num = $k + 1;
                $detailStr .= "<tr style='text-align: center'><td>{$num}</td><td>{$v['productNo']}</td><td>{$v['productName']}</td><td>{$v['productModel']}</td><td>{$v['number']}</td><td>{$v['executedNum']}</td><td>{$v['backNum']}</td></tr>";
            }
            $detailStr .= "</table>";
            $mailContent .= "<br>��ϸ��������:<br>".$detailStr;
        }

        $this->assign("mailTitle", $mailTitle);
        $this->assign("mailContent", $mailContent);

        // Ĭ���ʼ�������
        $mailReceiverNames = $rows['salesName'].",֣���,������,�ֽ���,�ƽ�,���޻�,��Ȩ��";
        $mailReceiverIds = $rows['salesNameId'].",jinhua.huang,jianjing.lin,yanhua.liu,dongdong.lv,quanzhou.luo,juanjuan.zheng";
        $this->assign("mailReceiverNames", $mailReceiverNames);
        $this->assign("mailReceiverIds", $mailReceiverIds);

        // Ĭ���ʼ�������
        $mailCReceiverNames = '������,�����';
        $mailCReceiverIds = 'dyj,honghui.liu';
        $this->assign("mailCReceiverNames", $mailCReceiverNames);
        $this->assign("mailCReceiverIds", $mailCReceiverIds);

        $this->view("noticeDelayApply");
    }

    /**
     * ������������ҳ��
     */
    function c_sendNoticeDelayApply(){
        $postData = $_POST [$this->objName];

        $borrowId = isset($postData['borrowId'])? $postData['borrowId'] : '';
        $title = isset($postData['mailTitle'])? $postData['mailTitle'] : '';
        $content = isset($postData['mailContent'])? $postData['mailContent'] : '';
        $mailUser = isset($postData['mailReceiverIds'])? $postData['mailReceiverIds'] : '';
        $ccMailUser = isset($postData['mailCReceiverIds'])? $postData['mailCReceiverIds'] : '';

        // ����ʼ���¼
        $mailconfigDao = new model_system_mailconfig_mailconfig();
        $mailconfigDao->addMailRecord($title,$content,$mailUser,$ccMailUser);

        // �ʼ���
        $emailDao = new model_common_mail();
        $result = $emailDao->mailGeneral($title, $mailUser, $content, $ccMailUser);

        if($result){// ���ͳɹ�
            $updateObj = array(
                "id" => $borrowId,
                "isDelayApply" => 0
            );
            $result = $this->service->updateById($updateObj);
        }

        if($result) {// ���ͳɹ�
            msg('���ͳɹ�!');
        }else{// ����ʧ��
            msg('����ʧ��,�����ԣ�');
        }
    }
}