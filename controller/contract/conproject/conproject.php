<?php
/**
 * @author liub
 * @Date 2014��5��29�� 14:50:09
 * @version 1.0
 * @description:��ͬ��Ŀ����Ʋ�
 */
class controller_contract_conproject_conproject  extends controller_base_action
{

    function __construct() {
        $this->objName = "conproject";
        $this->objPath = "contract_conproject";
        parent::__construct();

    }

   /****************������Ŀ�б� ��� ��Ʒ��Ŀ***********************************************************/
	/**
	 * ������Ŀtabҳ - �鿴
	 */
	function c_viewTab() {
        $row = $this->service->get_d($_GET['id']);
        $this->assignFunc($row);
		$this->assign('id', $_GET['id']);
		$this->display('viewtab');
	}
	function c_viewProject(){
		$cid = $_GET ['id'];
		$row = $this->service->getProView($cid);
		$this->assignFunc($row);
		$this->display('viewProject');
	}
	//����汾
	function c_incomeList(){
		$this->assign('id', $_GET['id']);
		$this->view('incomeList');
	}
	function c_incomeListJson()
    {
        $condition = "";
        $isMax = ($_POST['belongYear'] == 'max')? true : false;
        $service = $this->service;
        $pid = $_POST['projectId'];
        if(isset($_POST['belongYear']) && $_POST['belongYear'] != '' && $_POST['belongYear'] != 'max'){
            $isMax = false;
            $condition .= " and storeYear = {$_POST['belongYear']}";
        }

        if(isset($_POST['belongMonth']) && $_POST['belongMonth'] != '' && $_POST['belongMonth'] != 'max'){
            $isMax = false;
            $condition .= " and storeMon = {$_POST['belongMonth']}";
        }

        $rows = $service->getIncomeList($pid,$condition,$isMax);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = ($rows)? count($rows) : 0;
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }
    //�ɱ��汾
    function c_costList(){
		$this->assign('id', $_GET['id']);
		$this->view('costList');
	}
	function c_costListJson()
    {
        $service = $this->service;
        $pid = $_POST['projectId'];
        $rows = $service->getCostList($pid);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }
    //��Ʒ�ɱ�
    function c_productList(){
    	$this->assign('id', $_GET['id']);
		$this->view('productList');
    }
    function c_productListJson()
    {
		$service = $this->service;
		$projectId = $_POST['projectId'];
		$pArr = $service->get_d($projectId);
		$dao = new model_contract_contract_product();
		$rows = $dao->getPro_d($pArr['contractId'],$pArr['proLineCode']);
		if($rows){
			$equDao = new model_contract_contract_equ();
			foreach($rows as $key => $val){
                $equArr = $equDao->getByPcid_d($val['id'],$val['contractId']);
                $equCost = 0;
                foreach($equArr as $k => $v){
                	$equCost += $v['money'];
                	$equNumber += $v['number'];
                	$equExeNumber += $v['executedNum'];
                	$equBackNum += $v['backNum'];
                }
                $rows[$key]['cost'] = $equCost;
                if($equNumber - $equExeNumber + $equBackNum > 0){
                	$rows[$key]['isDone'] = "��";
                }else{
                	$rows[$key]['isDone'] = "��";
                }
			}
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}
	//�����嵥
	function c_equList(){
    	$this->assign('id', $_GET['id']);
		$this->view('equList');
    }
    function c_equListJson()
    {
		$service = $this->service;
		$projectId = $_POST['projectId'];
		$pArr = $service->get_d($projectId);
		$rows = $service->getEquList($pArr['contractId'],$pArr['proLineCode']);

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

    //��������汾
	function c_confirmIncome() {
		set_time_limit(0);
		echo $this->service->confirmIncome_d();
	}
	//���³ɱ��汾
	function c_confirmCost() {
		set_time_limit(0);
		echo $this->service->confirmCost_d();
	}

	// ����֧��
    function c_feeCostMainList(){
        $cid = isset($_GET['cid'])? $_GET['cid'] : '';
        $proLineCode = isset($_GET['proLineCode'])? $_GET['proLineCode'] : '';

        $this->assign('proLineCode',$proLineCode);
        $this->assign('contractId',$cid);
        $this->assign('showType',"main");
        $this->view('feeCostList');
    }

    function c_feeCostDetailList(){
        $cid = isset($_GET['contractId'])? $_GET['contractId'] : '';
        $costTypeId = isset($_GET['costTypeId'])? $_GET['costTypeId'] : '';
        $proLineCode = isset($_GET['proLineCode'])? $_GET['proLineCode'] : '';

        $this->assign('proLineCode',$proLineCode);
        $this->assign('contractId',$cid);
        $this->assign('costTypeId',$costTypeId);
        $this->assign('showType',"detail");
        $this->view('feeCostList');
    }

    function c_feeCostListJson(){
        $cid = isset($_POST['cId'])? $_POST['cId'] : '';
        $costTypeId = isset($_POST['costTypeId'])? $_POST['costTypeId'] : '';
        $proLineCode = isset($_POST['proLineCode'])? $_POST['proLineCode'] : '';
        $arr = array();

        if($cid != ''){
            if($costTypeId != '' && $costTypeId != 'undefined'){
                $extSql = " and costTypeId = {$costTypeId} ";
                $rows = $this->service->getFeeCostCount($cid,$extSql,"detail");
            }else{
                $rows = $this->service->getFeeCostCount($cid);
            }
            $arr['collection'] = $cid;
        }else{
            $rows = array();
        }

        $proportion = $this->service->getAccBycid($cid, $proLineCode, 11);// ��Ŀռ��
        $workRate = round($proportion, 2);

        if($rows){
            foreach ($rows as $k => $v){
                $rows[$k]['costMoney'] = round($v['costMoney'] * $workRate / 100,2);
                if($rows[$k]['costMoney'] == 0){
                    unset($rows[$k]);// ��ռ���Ϊ0�����ݲ���ʾ
                }
            }
        }

        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil :: encode($rows);
    }

    //
    function c_listOsAndOtherCost(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $obj = $this->service->get_d($id);

        $otherCost = $this->service->getPotherCost($obj['projectCode']);
        $otherCost = bcadd($otherCost,0,2);

        $this->assign('otherCost',$otherCost);
        $this->view('listOsAndOtherCost');
    }

	/****************������Ŀ�б� ��� ��Ʒ��Ŀ***********����************************************************/



    /**
     * ��ת����ͬ��Ŀ���б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ת��������ͬ��Ŀ��ҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ת���༭��ͬ��Ŀ��ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴��ͬ��Ŀ��ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ͬ��Ŀ�б�(ʵʱ)
     */
    function c_conProjectList() {
        $this->view('conprojectList');
    }

    /**
     * ��ͬ��Ŀ�б� ���ݱ�
     */
    function c_conProjectStoreList() {
        $recordDao = new model_contract_conproject_conprojectRecord();
        $maxNum = $recordDao->getMaxVersion(1) - 1;
        $this->assign("maxVersion", $maxNum);
        $this->view('conprojectStoreList');
    }

    /**
     * ָ���������� tab
     */
    function c_indicatorTab() {
        $this->view('indicatorlTab');
    }

    /**
     * ��ͬ��Ŀ���� tab
     */
    function c_conProjectCollectTab() {
        $this->view('conProjectCollectTab');
    }


    /**
     * ������ ��Ʒ���ɺ�ͬ��Ŀ�� TEST��
     */
    function c_createProjectBySale($cid) {
        $this->service->updateEstimatesByEsmId($cid);

        //   	   $this->service->updateConProScheduleByCid(3543);

    }

    /**
     * ��ͬ��Ŀ�б� ��ȡ����json
     */
    function c_conprojectJson() {
        $service = $this->service;
        //ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        //Ȩ�޴���
        $limitArr = array();
        if (isset ($service->this_limit['��Ʒ��Ȩ��']) && !empty ($service->this_limit['��Ʒ��Ȩ��']))
            $limitArr['proLine'] = $service->this_limit['��Ʒ��Ȩ��'];
        if (isset ($service->this_limit['ִ������Ȩ��']) && !empty ($service->this_limit['ִ������Ȩ��']))
        	$limitArr['exeDept'] = $service->this_limit['ִ������Ȩ��'];
        if (isset ($service->this_limit['���Ȩ��']) && !empty ($service->this_limit['���Ȩ��']))
        	$limitArr['module'] = $service->this_limit['���Ȩ��'];
        //����ͬʱ��3��Ȩ�޲��ܿ�������
        if(!empty($limitArr['proLine']) && !empty($limitArr['exeDept']) && !empty($limitArr['module'])){
        	$service->getParam($_REQUEST);
        	$sqlStr = "sql: ";
        	//��Ʒ��Ȩ�޴���
        	if (strstr($limitArr['proLine'], ';;') == false) {
        		$LimitArr = explode(",", $limitArr['proLine']);
        		foreach ($LimitArr as $k => $v) {
        			if ($k == 0) {
        				$sqlStr .= " AND ( FIND_IN_SET('$v',c.proLineCode)";
        			} else {
        				$sqlStr .= " OR FIND_IN_SET('$v',c.proLineCode)";
        			}
        			$k++;
        		}
        		$sqlStr .= ")";
        	}
        	//ִ������Ȩ�޴���
        	if (strstr($limitArr['exeDept'], ';;') == false) {
        		$LimitArr = explode(",", $limitArr['exeDept']);
        		foreach ($LimitArr as $k => $v) {
	        		if ($k == 0) {
	        			$sqlStr .= " AND ( FIND_IN_SET('$v',projectProLine) OR (projectProLine IS NULL AND FIND_IN_SET('$v', conproExeDeptId))";
	        		} else {
	        			$sqlStr .= " OR ( FIND_IN_SET('$v',projectProLine) OR (projectProLine IS NULL AND FIND_IN_SET('$v', conproExeDeptId)))";
	        		}
        			$k++;
        		}
        		$sqlStr .= ")";
        	}
        	//���Ȩ�޴���
        	if (strstr($limitArr['module'], ';;') == false) {
        		$LimitArr = explode(",", $limitArr['module']);
        		foreach ($LimitArr as $k => $v) {
        			if ($k == 0) {
        				$sqlStr .= " AND ( FIND_IN_SET('$v',conModule)";
        			} else {
        				$sqlStr .= " OR FIND_IN_SET('$v',conModule)";
        			}
        			$k++;
        		}
        		$sqlStr .= ")";
        	}
        	$service->searchArr['mySearchCondition'] = $sqlStr;
        	$service->sort = "c.id";
        	$rows = $service->page_d("select_defaultNew");
        	// 		if($this->service->sort){
        	// 			$this->service->sort=str_replace('reserveEarnings','earnings',$this->service->sort);
        	// 			$this->service->sort=str_replace('exgrossTrue','(earnings-cost)',$this->service->sort);
        	// 			if($this->service->sort='grossTrue'){
        	// 				$this->service->sort=str_replace('grossTrue','(earnings-cost)',$this->service->sort);
        	// 			}
        	// 		}
        	//ѭ����ȡ��ͬID���飬����ȡ��Ŀʵʱ��Ϣ
        	$esmDao = new model_engineering_project_esmproject();
        	//        foreach($rows as $v){
        	//            $cidArr[]= $v['contractId'];
        	//        }
        	//        $esmArr = $esmDao->getProjectList_d($cidArr);
        	//ѭ�����ع�����
        	foreach ($rows as $k => $v) {
        		if(!empty($v['contractId'])){//���ڹ�����ͬ,�����ȡ���ʵʱ����
        			$esmId = $v['esmProjectId'];
        			$esmArr = $esmDao->getProjectList_d(array($v['contractId']));
        			$conArr = $service->getConPorjectNowInfoByCid($v, $esmArr[$esmId]);
        			$rows[$k]['proportion'] = $conArr['proportion'];
        			$rows[$k]['proportionTrue'] = $conArr['proportionTrue'];
        			$rows[$k]['proMoney'] = $conArr['proMoney'];
        			$rows[$k]['contractMoney'] = $conArr['contractMoney'];
        			$rows[$k]['txaRate'] = $conArr['txaRate'];
        			$rows[$k]['rateMoney'] = $conArr['rateMoney'];
        			$rows[$k]['exgross'] = $conArr['exgross'];
        			$rows[$k]['gross'] = $conArr['gross'];
        			$rows[$k]['estimates'] = $conArr['estimates'];
        			$rows[$k]['schedule'] = $conArr['schedule']; //����
        			$rows[$k]['earnings'] = $conArr['earnings']; //����
        			$rows[$k]['reserveEarnings'] = $conArr['earnings']*0.02; //Ԥ��Ӫ�� add by zzx 2016-1-26 17:37:59

        			$rows[$k]['deductMoney'] = $conArr['deductMoney']; //�ۿ�
        			$rows[$k]['badMoney'] = $conArr['badMoney'];; //����

        			$rows[$k]['budget'] = $conArr['budget']; //Ԥ��
        			$rows[$k]['cost'] = $conArr['cost']; //����
        			$rows[$k]['planBeginDate'] = $conArr['planBeginDate'];
        			$rows[$k]['planEndDate'] = $conArr['planEndDate'];
        			$rows[$k]['actBeginDate'] = $conArr['actBeginDate'];
        			$rows[$k]['actEndDate'] = $conArr['actEndDate'];

        			$rows[$k]['grossTrue'] = $conArr['earnings'] - $conArr['cost'] - $v['costAct']; //ë��
        			$rows[$k]['exgrossTrue'] = bcdiv($conArr['earnings'] - $conArr['cost'] - $v['costAct'], $conArr['earnings'], 2) * 100; //ë����
        			$rows[$k]['module'] = $conArr['module']; //������
        			$rows[$k]['moduleName'] = $conArr['moduleName']; //���
        			$rows[$k]['officeId'] = $conArr['officeId']; //����id
        			$rows[$k]['officeName'] = $conArr['officeName']; //����
        		}else{
        			$rows[$k]['grossTrue'] = $rows[$k]['earnings'] - $rows[$k]['cost']; //ë��
        			$rows[$k]['exgrossTrue'] = bcdiv($rows[$k]['earnings'] - $rows[$k]['cost'], $rows[$k]['earnings'], 2) * 100; //ë����
        			$rows[$k]['reserveEarnings'] = $rows[$k]['earnings']*0.02; //Ԥ��Ӫ�� add by zzx 2016-1-26 17:37:59
        		}
        	}
        }

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
     * ��ͬ��Ŀ�б� -- ���ݱ�
     */
    function c_conprojectStoreJson() {
        $service = $this->service;
        if(isset($_REQUEST['warningStr'])){
            if($_REQUEST['warningStr'] == '1'){
                $conditionSql = "1";
            }
            unset ($_REQUEST['warningStr']);
        }

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $recordDao = new model_contract_conproject_conprojectRecord();
        $maxNum = $recordDao->getMaxVersion() - 1;

        //������Ȩ������
        $limit = $this->service->initLimit();
        if ($limit == true) {
            //            $service->searchArr['version'] = $maxNum;
            if(!empty($conditionSql)){
                $service->searchArr['warningStr'] = $conditionSql;
            }
            $rows = $service->pageBySqlId("select_store");
        }
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
     * �б���
     */
    function c_exportExcel() {
        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $rows = array();

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $version = $_GET['version'];
        $searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
        $searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
        $searchArr[$searchConditionKey] = $searchConditionVal;
        $searchArr['version'] = $version;
        if (isset($_SESSION['advSql'])) {
            $_REQUEST['advSql'] = $_SESSION['advSql'];
        }
        $service->getParam($_REQUEST);
        //		//��¼��
        //		$appId = $_SESSION['USER_ID'];
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
        //������Ȩ������
        $limit = $this->service->initLimit();
        if ($limit == true) {
            $service->sort = "c.pid";
            $rows = $service->listBySqlId("select_store");
        }
        foreach ($rows as $key => $row) {
            if(in_array("��Ŀ����",$colArr)){
                if(empty($row['esmProjectId'])){
                    $rows[$key]['proType'] = '������';
                }else{
                    $rows[$key]['proType'] = '������';
                }
            }
            if(in_array("����ռ��",$colArr)) $rows[$key]['proportionTrue'] /= 100;
            if(in_array("��Ŀռ��",$colArr)) $rows[$key]['proportion'] /= 100;
            if(in_array("��Ŀ����",$colArr)) $rows[$key]['schedule'] /= 100;
            if(in_array("Ԥ��ë����",$colArr)) $rows[$key]['exgross'] /= 100;
            if(in_array("ë����",$colArr)) $rows[$key]['exgrossTrue'] /= 100;
            if(in_array("˰��",$colArr)) $rows[$key]['txaRate'] /= 100;
        }
        //ƥ�䵼����
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);
        foreach ($rows as $key => $row) {
            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }
            array_push($dataArr, $colIdArr);
        }

        foreach ($dataArr as $key => $val) {
            if (!empty($val['status'])) {
                $dataArr[$key]['status'] = $this->getDataNameByCode($val['status']);
            }
            if ($val['checkTip'] == '0') {
                $dataArr[$key]['checkTip'] = '-';
            } else {
                $dataArr[$key]['checkTip'] = '��';
            }
        }
        return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);
    }

    /**
     * ��ȡȨ�޷���
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }


    /**
     * ȷ��������㷽ʽ
     */
    function c_incomeAcc() {
        $dao = new model_contract_conproject_conprojectRecord();
        $row = $dao->get_d($_GET['id']);
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign("pid", $_GET['pid']);
        $this->assign("id", $_GET['id']);
        $this->view('incomeAcc');
    }

    function c_incomeAccEdit() {
        $rows = $_POST ['acc'];
        $id = $this->service->incomeAccEdit_d($rows);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : 'ȷ�ϳɹ���';
        if ($id) {
            msg($msg);
        }
    }

    /**
     * ajax ���¿��˱�ʶ
     */
    function c_ajaxCheckTip() {
        $id = $_POST ['id'];
        $pid = $_POST ['pid'];
        $val = $_POST ['val'];


        $sql = "update oa_contract_project set checkTip='" . $val . "' where id = '" . $pid . "'";
        $sql2 = "update oa_contract_project_record set checkTip='" . $val . "' where id = '" . $id . "'";
        $this->service->_db->query($sql);
        $this->service->_db->query($sql2);
        echo 1;
    }

    //�����ͬ��Ŀ
    function c_toExcel() {
        $this->view("excel");
    }

    function c_upExcel() {
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $service = $this->service;
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $dao = new model_contract_conproject_importConprojectUtil ();
            $excelData = $dao->readExcelData($filename, $temp_name);
            spl_autoload_register('__autoload');
            $resultArr = $service->importProInfo_d($excelData);
            if ($resultArr)
                echo util_excelUtil :: finalceResult($resultArr, "������", array(
                    "��ͬ���",
                    "���"
                ));
            else
                echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove();</script>";
        }
    }

    //����ȷ�Ϸ�ʽ�� ���˱�ʶ���� ��չ
    function c_toExcelExtend() {
        $this->view("excelExtend");
    }

    function c_upExcelExtend(){
            set_time_limit(0);
            ini_set('memory_limit', '128M');
            $service = $this->service;
            $filename = $_FILES ["inputExcel"] ["name"];
            $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
            $fileType = $_FILES ["inputExcel"] ["type"];
            if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $dao = new model_contract_conproject_importConprojectUtil ();
                $excelData = $dao->readExcelData($filename, $temp_name);
                spl_autoload_register('__autoload');
                $resultArr = $service->importExtend_d($excelData);
                if ($resultArr)
                    echo util_excelUtil :: finalceResult($resultArr, "������", array(
                        "��ͬ���",
                        "���"
                    ));
                else
                    echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
            } else {
                echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove();</script>";
            }
        }


    /**
     *  ���ݸ��½���
     */
    function c_progressView() {
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        $service = $this->service;
        $esmDao = new model_engineering_project_esmproject();
        $arr = $service->list_d();
        foreach ($arr as $k => $v) {
        	if(!empty($v['contractId'])){//���ڹ�����ͬ,�����ȡ���ʵʱ����
        		$esmId = $v['esmProjectId'];
        		$esmArr = $esmDao->getProjectList_d(array($v['contractId']));
        		$conArr = $service->getConPorjectNowInfoByCid($v, $esmArr[$esmId]);
        		$arr[$k]['proportion'] = $conArr['proportion'];
        		$arr[$k]['proportionTrue'] = $conArr['proportionTrue'];
        		$arr[$k]['proMoney'] = $conArr['proMoney'];
        		$arr[$k]['contractMoney'] = $conArr['contractMoney'];
        		$arr[$k]['txaRate'] = $conArr['txaRate'];
        		$arr[$k]['rateMoney'] = $conArr['rateMoney'];
        		$arr[$k]['exgross'] = $conArr['exgross'];
        		$arr[$k]['gross'] = $conArr['gross'];
        		$arr[$k]['estimates'] = $conArr['estimates'];
        		$arr[$k]['schedule'] = $conArr['schedule']; //����
        		$arr[$k]['earnings'] = $conArr['earnings']; //����
        		$rows[$k]['reserveEarnings'] = $conArr['earnings']*0.02; //Ԥ��Ӫ�� add by zzx 2016-1-26 17:37:59

        		$arr[$k]['deductMoney'] =  $conArr['deductMoney']; //�ۿ�
        		$arr[$k]['badMoney'] =  $conArr['badMoney']; //����

        		$arr[$k]['budget'] = $conArr['budget']; //Ԥ��
        		$arr[$k]['cost'] = $conArr['cost']; //����
        		$arr[$k]['planBeginDate'] = $conArr['planBeginDate'];
        		$arr[$k]['planEndDate'] = $conArr['planEndDate'];
        		$arr[$k]['actBeginDate'] = $conArr['actBeginDate'];
        		$arr[$k]['actEndDate'] = $conArr['actEndDate'];

        		$arr[$k]['grossTrue'] = $conArr['earnings'] - $conArr['cost'] - $v['costAct']; //ë��
        		$arr[$k]['exgrossTrue'] = bcdiv($conArr['earnings'] - $conArr['cost'] - $v['costAct'], $conArr['earnings'], 2) * 100; //ë����
        		$arr[$k]['module'] = $conArr['module']; //������
        		$arr[$k]['moduleName'] = $conArr['moduleName']; //���
        		$arr[$k]['officeId'] = $conArr['officeId']; //����id
        		$arr[$k]['officeName'] = $conArr['officeName']; //����
        	}else{
        		$arr[$k]['grossTrue'] = $arr[$k]['earnings'] - $arr[$k]['cost']; //ë��
        		$arr[$k]['exgrossTrue'] = bcdiv($arr[$k]['earnings'] - $arr[$k]['cost'], $arr[$k]['earnings'], 2) * 100; //ë����
        		$rows[$k]['reserveEarnings'] = $rows[$k]['earnings']*0.02; //Ԥ��Ӫ�� add by zzx 2016-1-26 17:37:59
        	}
        }
        $rows['rows'] = $arr;
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'progressView', $rows);
    }

    /**
     * �жϵ�ǰ�·��Ƿ� ��������
     */
    function c_ajaxIsUse() {
        $date = $_POST['date'];
        $year = substr($date, 0, 4);
        $mon = substr($date, -2);

        $sql = "select * from oa_contract_project_record where storeYear='" . $year . "' and storeMon='" . $mon . "' and isUse=1";
        $arr = $this->service->_db->getArray($sql);
        if (!empty($arr)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * ����汾
     */
    function c_saveVersionView() {
        $this->assign("versionNum",$_GET['versionNum']);
        $this->view("saveVersionView");
    }

    /**
     * ��¼�汾
     */
    function c_saveVersion() {

        $rows = $_POST ['acc'];
        $year = substr($rows['storeMon'], 0, 4);
        $mon = substr($rows['storeMon'], -2);
        $recordDao = new model_contract_conproject_conprojectRecord();
        $maxNum = $recordDao->getMaxVersion() - 1;
        if($rows['versionNum'] == 'undefined'){
            $versionNum = $maxNum;
        }else{
            $versionNum = $rows['versionNum'];
        }
        $sql = "update oa_contract_project_record set isUse='0' where storeYear='" . $year . "' and storeMon='" . $mon . "' and isUse=1";
        $sql1 = "update oa_contract_project_record set isUse='1',storeYear='" . $year . "',storeMon='" . $mon . "' where version='" . $versionNum . "'";

        $this->service->query($sql);
        $this->service->query($sql1);

        msg("����ɹ���");
    }

    /**
     * ���� �꣬�� ��ȡ�汾����
     */
    function c_getVarsionArr() {
        $year = $_POST['year'];
        $mon = $_POST['mon'];

        $sql = "select version,isuse from oa_contract_project_record where storeYear='" . $year . "' and storeMon='" . $mon . "' GROUP BY version order by version desc";
        $arr = $this->service->_db->getArray($sql);
        if (!empty($arr)) {
            $versionOption = <<<EOT
                  <option value="0" style="color:black">......</option>
EOT;
            foreach ($arr as $k => $v) {
                if ($v['isuse'] == '1') {
                    $tep = "red";
                    $tepC = "(��)";
                } else {
                    $tep = "black";
                    $tepC = "";
                }
                $versionOption .= <<<EOT
                  <option value="$v[version]" style="color:$tep">$v[version]$tepC</option>
EOT;
            }
            echo util_jsonUtil :: iconvGB2UTF($versionOption);
        } else {
            echo 0;
        }
    }

    /**
     * �ɱ༭��� �ӱ�����
     */
    function c_conProsubJson() {
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $lockDao = new model_stock_lock_lock ();
        $contractDao = new model_contract_contract_equ();
        $service = $this->service;
        $service->getParam($_POST);

        $pid = $_POST['pid'];
        $rows = $service->conProsubJson_d($pid);

        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }


    /****************** ��Ʒ����Ŀ���ܱ� *********************/
    /**
     * �б���ʾ
     */
    function c_toLineProjectView() {
        if (isset ($_GET ['thisYear'])) {
            $initArr = $_GET;
        } else {
            $thisYear = date('Y');
            $thisMonth = date('m') * 1;

            //��ʼ������
            $initArr = array(
                "thisYear" => $thisYear, "beginMonth" => $thisMonth, 'endMonth' => $thisMonth,
                'company' => 'all', 'DetailType' => 'all'
            );
        }


        $this->assignFunc($initArr);
        $this->view('lineProjectView');
    }


    /**
     *  ���ݺ�ͬid����Ʒ�߱��룬�жϲ�Ʒ���ں�ͬ���Ƿ����
     * @param $cid ,$proLineCode
     *  return  0 - ���� 1 - ������
     */
    function c_getisExistByLine() {
        $cid = $_POST['cid'];
        $proLineCode = $_POST['productLine'];
        $reStr = $this->service->getisExistByLine($cid, $proLineCode);
        echo $reStr;
    }

    /*
     * ������ĿID��ȡ��ع켣ʱ��
     */
    function c_listProjectTrack(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $trackArr = $this->service->getTrackAndTime($id);
        $pointTrStr = "";
        if(is_array($trackArr['dateInfo'])){
            $catchArr = array();
            $pointCodeArr = array('createDate','shipFirstDate','shipFinishDate','firstInvoiceDate','invoiceCompleteDate','changeDate');
            foreach ($trackArr['dateInfo'] as $date){
                if($date['time'] != ''){
                    $catchArr[$date['time']][] = $date['key'];
                }
            }
            foreach ($catchArr as $key => $val){
                $rowStr = "<tr><td>{$key}</td>";
                foreach ($pointCodeArr as $v){
                    $rowStr .= (in_array($v,$val))? "<td><img src='images/icon/icon088.gif'></td>" : "<td></td>";
                }
                $rowStr .= "</tr>";
                $pointTrStr .= $rowStr;
            }
        }

        $this->assign('pointTrStr',$pointTrStr);
        $this->view("listProjectTrack");
    }

    /**
     * ��ͬ��Ŀ  �������ݵ��� ���� ֻ�ṩ���㵼�룩
     */
    function c_toLeadfinanceMoney() {
        $this->assign("dateTime", date("Y-m-d"));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= $thisYear - 5; $i--) {
            $yearStr .= "<option value=$i>" . $i . "��</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->view("toleadfinanceMoney");
    }


    /**
     * �ϴ�EXCEL
     */
    function c_finalceMoneyImport() {
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $import = $_POST['import'];
        $objNameArr = array(
            0 => 'projectCode', //���
            1 => 'money', //���
        );
        $infoArr = array(
            "importMonth" => $import['Year'] . $import['Month'], //�����·�
            "moneyType" => $import['importInfo'], //�������
            "importName" => $_SESSION['USERNAME'], //������
            "importNameId" => $_SESSION['USER_ID'], //������ID
            "importDate" => date("Y-m-d:h:m:s"), //����ʱ��
        );
        $this->service->addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $import['normType']);

        //        if ($import['importType'] == "��ʼ������") {
        //            $this->service->addFinalceMoneyExecel_d($objNameArr, $import['importInfo'], $import['normType']);
        //        } else {
        //������Ϣ ����
        //        }
    }

    /**
     * ��֤����Ľ���Ƿ��Ѵ���
     */
    function c_getFimancialImport() {
        $importType = util_jsonUtil :: iconvUTF2GB($_POST['importType']);
        $importMonth = util_jsonUtil :: iconvUTF2GB($_POST['importMonth']);
        $importSub = util_jsonUtil :: iconvUTF2GB($_POST['importSub']);

        if ($importType == "���µ���") {
            $num = $this->service->getFimancialImport_d($importMonth, $importSub);
            echo $num;
        } else {
            echo "0";
        }
    }

    /**
     * ��������ϸ�б�Tab
     */
    function c_financialDetailTab() {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetailTab");
    }

    /**
     * ��������Ϣ
     */
    function c_financialDetail() {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetail");
    }

    function c_financialdetailpageJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $rows = $service->getFinancialDetailInfo($conId, $tablename, $moneyType);
        //echo "<pre>";
        //print_R($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��������Ϣ
     */
    function c_financialImportDetail() {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialImportDetail");
    }

    function c_financialImportDetailpageJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $sql = $service->getFinancialImportDetailInfo($conId, $tablename, $moneyType);
        $service->sort = false;
        $rows = $service->pageBySql($sql);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��Ʒ��ָ���  --json����
     */
    function c_productLineReportJson() {
        $service = $this->service;
        //        $service->getParam ( $_REQUEST );
        $rows = $service->lineProjectData($_REQUEST);
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);

    }

    /**
     * ��ƷӪ��--��ʱ  --json����
     */
    function c_contractProReportJson() {
        $service = $this->service;
        if(isset($_REQUEST['warningStr'])){
            if($_REQUEST['warningStr'] == '1'){
                $conditionSql = "sql: and (
         sr <> 0 and cb <> 0
       )";
            }
            unset ($_REQUEST['warningStr']);
        }
                $service->getParam ( $_REQUEST );
//        $sql = $service->contractProData();
        $service->sort = false;
        $sql = $this->service->getProSql();
        if(!empty($conditionSql)){
            $service->searchArr['warningStr'] = $conditionSql;
        }
        $row = $service->pageBySql($sql);
        //����ռ�ȣ�Ȼ���ȡͳ�Ʊ����� �õ�������뼰�ɱ�����ʱ��������
        $rows = $service->handleProRow($row);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);

    }
    //
    function c_toViewContract(){
        $idStr = $_GET['ids'];
        $idArr = explode(",",$idStr);
        $idArr = array_flip($idArr);
        $idArr = array_flip($idArr);
        $idStr = implode(",",$idArr);
        $sql = "select * from oa_contract_contract where id in ($idStr)";
        $row = $this->service->_db->getArray($sql);
        $html = "<table><tr><td>��ͬ���</td></tr>";
        foreach($row as $v){
            $html .= "<tr><td>".$v['contractCode']."</td></tr>";
        }
        $html .= "</table>";
        echo $html;
    }

    /**************************ͼ��*******************************************************************/

    // ͼ�� -- Echarts
    function c_conProjectEcharts() {
        //��ȡ����ʱ������
        $dateArr = $this->service->getSection();
        $this->assign("section",$dateArr['startMonth']." ~ ".$dateArr['endMonth']);
        $this->view("conProjectEcharts");
    }

    /**********ajax ��ȡ���ݷ���**********************/
    //��Ŀ����
    function c_getConNumChart() {
        $rows = $this->service->conProEchartsJson();
        echo util_jsonUtil::encode($rows);
    }

    //��Ŀ����-ͼ��
    function c_getConNumChartPie() {
        $rows = $this->service->conProEchartsPieJson();
        echo util_jsonUtil::encode($rows);
    }

    //Ӫ��״��
    function c_getRevenueChart() {
        $rows = $this->service->conProRevenueChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //ë��״��
    function c_getGrossChart() {
        $rows = $this->service->conProGrossChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //ë���ʶԱ�
    function c_getRateGrossChart() {
        $rows = $this->service->conProRateGrossChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //��Ŀ�����ֲ�
    function c_getProNumMapChart() {
        $rows = $this->service->conProNumMapChartJson();
        echo util_jsonUtil::encode($rows);
    }

    //��ȡ�����ڵ����汾��\
    function c_getMaxNum(){
        $endMonth = $_POST['endMonth'];
        $endYear = substr($endMonth, 0, 4);
        $endMon = substr($endMonth, -2);
        $sql = "select max(version) as maxNum from oa_contract_project_record where storeYear <= '".$endYear."' and storeMon <= '".$endMon."' and isUse=1";
        $arr = $this->service->_db->getArray($sql);
        echo $arr[0]['maxNum'];
    }

    /**
     * ���� ��ͬ��Ʒ��Ŀ����ֵ
     */
    function c_updateSaleProjectVal(){
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : '';
        $this->service->updateSaleProjectVal_d($projectCode);
        echo 1;
    }

    /**
     * ���� ��ͬ��Ʒ��Ŀ���°汾�浵
     */
    function c_updateConprojectVersion(){
        set_time_limit(0);
        $service =  $this->service;
        $service->autoUpdateConprojectVersion_d();
        echo 1;
    }
}