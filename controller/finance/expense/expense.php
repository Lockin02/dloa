<?php

/**
 * @author Show
 * @Date 2012��9��21�� ������ 13:47:38
 * @version 1.0
 * @description:���ù�����Ʋ�
 */
class controller_finance_expense_expense extends controller_base_action
{
    private $unSltDeptFilter = "";// PMS68 ���ù������Ž�ֹѡ��Ĳ���ID����
    private $DenyFegsdeptId = ""; // PMS772 ���ù������Ž�ֹѡ��Ĳ���ID,ͨ�����ö�����
    private $unDeptExtFilter = "";// PMS377 ��ģ����Ҫ�������صĲ���ѡ��
    private $bindId = "";
	function __construct() {
		$this->objName = "expense";
		$this->objPath = "finance_expense";
		parent:: __construct();

        $otherDataDao = new model_common_otherdatas();
        $subsidyArr = $otherDataDao->getConfig('unSltDeptFilter');
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unSltDeptFilter = $subsidyArr;
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");
        $DenyFegsdept = $otherDataDao->getDenyFegsdept();
        if(isset($DenyFegsdept['0']) && !empty($DenyFegsdept['0'])){
            $this->DenyFegsdeptId = $DenyFegsdept['0']['belongDeptIds'];
        }
        $this->bindId = "ba12e822-b58e-43ae-a4f9-431c9e0dfe6e";
	}

	/*
	 * ��ת������������÷�̯��ϸ���б�
	 */
	function c_page() {
		//��Ⱦ��Ȩ��
		$printLimit = isset($this->service->this_limit['��Ȩ��']) ? $this->service->this_limit['��Ȩ��'] : 0;
		$this->assign('printLimit', $printLimit);
		//��Ⱦ��ϸȨ��
		$detailLimit = isset($this->service->this_limit['��ϸȨ��']) ? $this->service->this_limit['��ϸȨ��'] : 0;
		$this->assign('detailLimit', $detailLimit);

        $funType = isset($_GET['funType'])? $_GET['funType'] : '';
        $userId = isset($_GET['userId'])? $_GET['userId'] : '';
        $this->assign("funType",$funType);
        $this->assign("userId",$userId);

		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json - Ĭ��
	 */
	function c_pageJson() {
		$service = $this->service;

		//����״̬����
		if (isset($_POST['checkList']) && $_POST['checkList'] == 1) {
			$thisStaus = util_jsonUtil::iconvUTF2GB($_POST['Status']);
			if ($thisStaus == '�������') {
				$_POST['StatusFin'] = '��������';
				unset($_POST['Status']);
			} elseif ($thisStaus == '��������') {
				$_POST['StatusNor'] = $_POST['Status'];
				unset($_POST['Status']);
			}
		}

		$service->getParam($_POST);
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ������Ŀ��Ա������¼��json������ѡ��ϼơ��ϼ���
	 */
	function c_myEsmmemberPageJson() {
		$service = $this->service;

		//����״̬����
		if (isset($_POST['checkList']) && $_POST['checkList'] == 1) {
			$thisStaus = util_jsonUtil::iconvUTF2GB($_POST['Status']);
			if ($thisStaus == '�������') {
				$_POST['StatusFin'] = '��������';
				unset($_POST['Status']);
			} elseif ($thisStaus == '��������') {
				$_POST['StatusNor'] = $_POST['Status'];
				unset($_POST['Status']);
			}
		}

		$service->getParam($_POST);
		$rows = $service->page_d();

		if (!empty($rows)) {
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows($rows);

			$rsArr['CostBelongDeptName'] = 'ѡ��ϼ�';
			$rsArr['Amount'] = 0;
			$rsArr['id'] = 'noId2';
			$rows[] = $rsArr;

			//�ܼ�������
			$objArr = $service->listBySqlId('select_amount');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['CostBelongDeptName'] = '�ϼ�';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��������ѯjson,����Ȩ�޺ͺϼ���
	 */
	function c_pageJsonAll() {
		$service = $this->service;
		$rows = array();

        // ����ҳ��鿴���5�ʱ���ʱ,��������
        if(isset($_POST['funType']) && $_POST['funType'] == "seeLastFive"){
            $service->setCompany(0);
            $_POST['costUser'] = isset($_POST['costMan'])? $_POST['costMan'] : '';
            $_POST['pageSize'] = 5;
            $_POST['sort'] = "c.id";
            $_POST['dir'] = "DESC";
            unset($_POST['costMan']);
            $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
            unset($service->searchArr['Status']);

            $rows = $service->page_d();
        }else{
            $deptLimit = isset($service->this_limit['����Ȩ��']) ? $service->this_limit['����Ȩ��'] : "";
            //�жϲ���Ȩ��
            if (!empty($deptLimit)) {
                if (strstr($deptLimit, ';;')) {
                    $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
                    $rows = $service->page_d();
                } else {
                    $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
                    $service->searchArr['CostBelongDeptIds'] = $deptLimit;
                    $rows = $service->page_d();
                }

                //����в�ѯ���ݣ������
                if ($rows) {
                    //���ݼ��밲ȫ��
                    $rows = $this->sconfig->md5Rows($rows);

                    //�ܼ�������
                    $objArr = $service->listBySqlId('count_all');
                    if (is_array($objArr)) {
                        $rsArr = $objArr[0];
                        $rsArr['CostManName'] = 'ȫ���ϼ�';
                        $rsArr['id'] = 'noId';
                        $rows[] = $rsArr;
                    }
                }
            }
        }

		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
		$arr['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/* ====================== �����������ݲ�ѯ PMS 661��START��====================== */
    /**
     * �鿴��Ӧ�û��İ������÷�����ϸ�б�
     */
	function c_seeAliTripCostRecords(){
	    $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
        $CostDateBegin = isset($_REQUEST['CostDateBegin'])? $_REQUEST['CostDateBegin'] : '';
        $CostDateEnd = isset($_REQUEST['CostDateEnd'])? $_REQUEST['CostDateEnd'] : '';

        $this->assign("userId",$userId);
        $this->assign("beginDate",$CostDateBegin);
        $this->assign("endDate",$CostDateEnd);
        $this->view('listAliTripCostRecords');
    }

    /**
     * �����ϵİ������õ�������ȡ������
     */
    function c_autoSaveAliTripDate(){
        $aliDao = new model_finance_expense_alibusinesstrip();
        $aliDao->saveAliTripDateToLocal_d();
    }

    /**
     * �鿴����
     */
    function c_searchAliGridJson(){
        $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
        $CostDateBegin = isset($_REQUEST['beginDate'])? $_REQUEST['beginDate'] : '';
        $CostDateEnd = isset($_REQUEST['endDate'])? $_REQUEST['endDate'] : '';
        $dateRange = array("CostDateBegin" => $CostDateBegin,"CostDateEnd" => $CostDateEnd);
        $aliDao = new model_finance_expense_alibusinesstrip();

//        $jsonUtilObj = new util_jsonUtil();// �����ڵ���Ali��SDK֮ǰ��ʵ����һ�±��ص�util,��������
//        $dataRows = $aliDao->getAliTripHotelOrder($userId,$dateRange);// �Ƶ��¼
//        $flightDataRows = $aliDao->getAliTripFlightOrder($userId,$dateRange);// ��Ʊ��¼
//        $trainDataRows = $aliDao->getAliTripTrainOrder($userId,$dateRange);// ��Ʊ��¼
//        if(!empty($flightDataRows)){// �ϲ���Ʊ��¼
//            foreach ($flightDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }
//        if(!empty($trainDataRows)){// �ϲ���Ʊ��¼
//            foreach ($trainDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }

        $dataRows = $aliDao->searchLocalAliDataForGrid_d($userId,$dateRange);
        $dataRows = util_jsonUtil::iconvGB2UTFArr($dataRows);

        // �ϼ�
        $countRow = array(
            "id" => "totalSum",
            "useNname" => "<span style='font-weight: bolder;font-size: 13px'>".util_jsonUtil::iconvGB2UTF("�ϼ�")."</span>",
            "beginDate" => "",
            "endDate" => "",
            "category" => "",
            "description" => "",
            "cost" => 0,
        );

        foreach ($dataRows as $row){
            $countRow['cost'] = round(bcadd($row['cost'],$countRow['cost'],3),2);
        }
        $dataRows[] = $countRow;
        $htmlStr = $aliDao->searchAliGridHtml_d($dataRows);
        echo $htmlStr;
    }

    /**
     * ��������
     */
    function c_exportAliGridJson(){
        $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
        $CostDateBegin = isset($_REQUEST['beginDate'])? $_REQUEST['beginDate'] : '';
        $CostDateEnd = isset($_REQUEST['endDate'])? $_REQUEST['endDate'] : '';
        $dateRange = array("CostDateBegin" => $CostDateBegin,"CostDateEnd" => $CostDateEnd);
        $aliDao = new model_finance_expense_alibusinesstrip();

        // �����ڵ���Ali��SDK֮ǰ��ʵ����һ�±��ص�util,��������
//        $jsonUtilObj = new util_jsonUtil();
//        $esmexcelutilObj = new model_engineering_util_esmexcelutil();
//
//        $dataRows = $aliDao->getAliTripHotelOrder($userId,$dateRange);// �Ƶ��¼
//        $flightDataRows = $aliDao->getAliTripFlightOrder($userId,$dateRange);// ��Ʊ��¼
//        $trainDataRows = $aliDao->getAliTripTrainOrder($userId,$dateRange);// ��Ʊ��¼
//        if(!empty($flightDataRows)){// �ϲ���Ʊ��¼
//            foreach ($flightDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }
//        if(!empty($trainDataRows)){// �ϲ���Ʊ��¼
//            foreach ($trainDataRows as $row){
//                $dataRows[] = $row;
//            }
//        }
//        $dataRows = util_jsonUtil::iconvUTF2GBArr($dataRows);

        $dataRows = $aliDao->searchLocalAliDataForGrid_d($userId,$dateRange);
        set_time_limit(0);

        //�����ͷ
        $thArr = array('useNname' => '����', 'beginDate' => '��ʼ����','endDate' => '��������', 'category' => '���', 'cost' => '���','description' => '��ϸ����');
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $dataRows, '������������ͳ��');
    }

    /**
     * ����ڸñ������ķ����ڼ��ڰ������õ����Ѽ�¼���Ƿ�����뱨����һ���ķ�����
     */
    function c_checkAliTripCostRecord(){
        $type = isset($_REQUEST['type'])? $_REQUEST['type'] : '';

        switch ($type){
            case 'byBillNo':
                $billNo = isset($_REQUEST['billNo'])? $_REQUEST['billNo'] : '';

                $record = ($billNo != "")? $this->service->_db->get_one("select T.*,l.CostMan,l.CostDateBegin,l.CostDateEnd from cost_summary_list l left join (
select billno,GROUP_CONCAT(t.CostTypeID) as CostTypeIDs from (select * from cost_detail group by billno,CostTypeID)t group by t.billno
)T on T.Billno = l.billNo where l.billNo = '{$billNo}';") : array();
                $userId = isset($record['CostMan'])? $record['CostMan'] : '';
                $startDate = isset($record['CostDateBegin'])? $record['CostDateBegin'] : '';
                $endDate = isset($record['CostDateEnd'])? $record['CostDateEnd'] : '';
                $costTypeIds = isset($record['CostTypeIDs'])? $record['CostTypeIDs'] : '';
                break;
            default:
                $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
                $startDate = isset($_REQUEST['startDate'])? $_REQUEST['startDate'] : '';
                $endDate = isset($_REQUEST['endDate'])? $_REQUEST['endDate'] : '';
                $costTypeIds = isset($_REQUEST['costTypeIds'])? $_REQUEST['costTypeIds'] : '';
                break;
        }

        $matchCostType = $this->service->chkAliTripRecord($userId,$startDate,$endDate,$costTypeIds);

        $backArr = array(
            "msg" => "ok",
            "error" => "0",
            "result" => $matchCostType
        );
        echo util_jsonUtil::encode($backArr);
    }
    /* ====================== �����������ݲ�ѯ PMS 661��END�� ====================== */

	/**
	 * �ҵı�����ϸ��
	 */
	function c_myList() {
		$this->view('listmy');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;
		$service->setCompany(0); # �����б�,����Ҫ���й�˾����

		$_POST['costUser'] = $_SESSION['USER_ID'];
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��������ϸ-���ڷ��ñ�������ҳ��ķ���ͳ��
	 */
	function c_statistictList() {
        //    	$this->assign('userId',$_GET['userId']);
//    	$this->assign('areaId',$_GET['areaId']);
//    	$this->assign('year',date('Y'));
//
//    	$this->view('liststatistict');
        $this->assign('userId',(isset($_GET['userId'])? $_GET['userId'] : ''));
        $this->assign('areaId',(isset($_GET['areaId'])? $_GET['areaId'] : ''));
        $this->assign('year',(isset($_GET['year'])? $_GET['year'] : date('Y')));

        if( (isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId'])) && (isset($_GET['view_type']) && !empty($_GET['view_type'])) ){
            $this->assign('view_type',(isset($_GET['view_type'])? $_GET['view_type'] : ''));
            if($_GET['view_type'] == 'view_all'){
                $this->view('listallstatistict');
            }
        }
        else if((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId']))){
            $this->view('liststatistict');
        }
        else{
            $this->view('listallstatistict');
        }
	}
	
	/**
	 * ��ȡ��ҳ����ת��Json - ����ͳ��
	 */
	function c_statistictPageJson() {
		$service = $this->service;
		$service->_isSetCompany = 0;//�����ֹ�˾Ȩ��

        // ��ȡ����������۲�����Ϣ
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('SALEDEPT');
        if($matchConfigItem && is_array($matchConfigItem)){
            $CostBelongDeptIds = isset($matchConfigItem[0]['belongDeptIds'])? $matchConfigItem[0]['belongDeptIds'] : '';
            if($CostBelongDeptIds != ''){
                $_POST['CostBelongDeptIds'] = $CostBelongDeptIds;
            }
        }
        $_POST['Status'] = '���';

		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d();
        $arr = array();
        $arr['getSql'] = $service->listSql;
		//����в�ѯ���ݣ������
		if ($rows) {
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows($rows);

            if(isset($_POST['needCountCol']) && $_POST['needCountCol'] == 'true'){
                //�ܼ�������
                $objArr = $service->listBySqlId('count_all');
                if (is_array($objArr)) {
                    $rsArr = $objArr[0];
                    $rsArr['CostManName'] = 'ȫ���ϼ�';
                    $rsArr['id'] = 'noId';
                    $rows[] = $rsArr;
                }
            }
		}

		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
        $arr['sumSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}
	
	/**
	 * ���ż���б�
	 */
	function c_checkList() {
		//��Ⱦ��Ȩ��
		$checkLimit = isset($this->service->this_limit['�����']) ? $this->service->this_limit['�����'] : 0;
		$this->assign('checkLimit', $checkLimit);
		$this->view('listcheck');
	}

	/**
	 * ��ְ��Ա�鿴������
	 */
	function c_listForLeave() {
		$userAccount = isset($_GET['userAccount']) ? $_GET['userAccount'] : '';
		if (empty($userAccount)) {
			exit("�봫���û���Ϣ");
		} else {
			$this->assign('userAccount', $_GET['userAccount']);
			$this->view('list-forleave');
		}
	}

	/**
	 * ��ת��������Ŀ��Ա�������б�
	 */
	function c_listForEsmmember() {
		$userAccount = isset($_GET['userAccount']) ? $_GET['userAccount'] : '';
		if (empty($userAccount)) {
			exit("�봫���û���Ϣ");
		} else {
			$this->assign('userAccount', $_GET['userAccount']);
			$this->assign('projectNo', $_GET['projectCode']);
			$this->view('list-foresmmember');
		}
	}

	/************************** S ��Ŀ���ñ�����鲿�� *******************/
	/**
	 * ��Ŀ����б�
	 */
	function c_checkEsmList() {
		$this->view('listcheckesm');
	}

	/**
	 * ��Ŀ���json
	 */
	function c_checkEsmJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		# Ĭ��ָ���ı����
		$service->setComLocal(array(
			"c" => $service->tbl_name,
			"p" => 'oa_esm_project'
		));
		$rows = $service->page_d('select_projectlist');
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ������Ŀ����б�
	 */
	function c_checkProjectList() {
		$this->assign('projectId', $_GET['projectId']);
		$this->view('listcheckproject');
	}

	/************************** E ��Ŀ���ñ�����鲿�� *******************/

	/*************************** �б��� *****************************/

	/**
	 * ��ת����������������÷�̯��ϸ��ҳ��
	 */
	function c_toAdd() {
		//ģ����Ⱦ
		$modelTypeArr = $this->service->getModelType_d();

		//�����ѯ��ģ�壬���ȥ���������������ģ������ҳ��
		if ($modelTypeArr) {
			//��Ⱦģ��
			$this->assignFunc($modelTypeArr);

//            $deptArr = explode(',',unDeptFilter.$this->unDeptExtFilter);
            $deptArr = explode(',',$this->DenyFegsdeptId.$this->unDeptExtFilter);
			$comCode = '';
			if(in_array($_SESSION['DEPT_ID'],$deptArr)){
				//������Ϣ����
				$this->assign('deptTempName','');
				$this->assign('deptTempId','');
			}else{
				//������Ϣ����
				$this->assign('deptTempName',$_SESSION['DEPT_NAME']);
				$this->assign('deptTempId',$_SESSION['DEPT_ID']);
				if(!empty($_SESSION['DEPT_ID'])){
					$sql = "select comCode from department where DEPT_ID = ".$_SESSION['DEPT_ID'];
					$rs = $this->service->_db->getArray($sql);
					if(!empty($rs)){
						$comCode = $rs[0]['comCode'];
					}
				}
			}
			$this->assign('comCode',$comCode);
			$this->assign('comCodeDefault',$comCode);
			//������Ϣ����
			$this->assign('deptName',$_SESSION['DEPT_NAME']);
			$this->assign('deptId',$_SESSION['DEPT_ID']);
			$this->assign('thisDate', day_date);
			$this->assign('applyName', $_SESSION['USERNAME']);
			$this->assign('applyId', $_SESSION['USER_ID']);
			$this->assign('CostBelongComId', $_SESSION['USER_COM']);
			$this->assign('CostBelongCom', $_SESSION['USER_COM_NAME']);

			//��ȡ����
            $otherdatasDao = new model_common_otherdatas();
            $docUrl = $otherdatasDao->getDocUrl($this->bindId);
            $this->assign('downloadUrl', $docUrl);

			//�жϱ����˵ı������Ƿ�Ҫ���в��ż��
			$this->assign('needExpenseCheck', $this->service->needExpenseCheck_d());

			//�жϵ�ǰ�����Ƿ���Ҫʡ��
			$this->assign('deptIsNeedProvince', $this->service->deptIsNeedProvince_d($_SESSION['DEPT_ID']));

			//��ѡ�������������Լ���˾��Ȩ��
			$this->assign('allApply', $this->service->this_limit['���б�����']);
			$this->assign('allCompany', $this->service->this_limit['���й�˾']);
            $this->assign('allCompanyForSQ', $this->service->this_limit['��ǰ��˾�޸�Ȩ��']);

			//��ȡ��������۲���id
			$this->assign('saleDeptId', expenseSaleDeptId);

			//��ȡ����Ĺ��˲���id
//            $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//			$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
            $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);
			$unSltDeptFilterStr = $this->unSltDeptFilter;// PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
            $this->assign('unSltDeptFilter', $unSltDeptFilterStr);

            // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
            $feemansForXtsSales = $this->service->getFeemansForXtsSales();
            $this->assign('feemansForXtsSales', $feemansForXtsSales);

			$this->display('add');
		} else {
			$this->assign('userName', $_SESSION['USERNAME']);
			$this->assign('userId', $_SESSION['USER_ID']);
			$this->view('createtemplate');
		}
	}

	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		$rtObj = $this->service->add_d($object);
		if (!is_array($rtObj)) {
			msgRf($rtObj);
		}
		if ($rtObj['id']) {
			if ($object['thisAuditType'] == 'audit') {
				$rangeId = '';
				//������Ŀ
				if ($object['projectType'] == 'esm' && $object['projectId']) {
					$esmprojectDao = new model_engineering_project_esmproject();
					$rangeId = $esmprojectDao->getRangeId_d($object['projectId']);
					$rangeId = "&billArea=" . $rangeId;
				}else if($object['DetailType'] == '4' && $object['CostBelongDeptId']){//��ǰ����ѡ���˷��ù������ź󣬸��ݰ��´��еĹ������Ŵ���������
					$officeDao = new model_engineering_officeinfo_officeinfo();
					$rs = $officeDao->find(array('feeDeptId' => $object['CostBelongDeptId'],
							'module' => $object['module']),null,'managerCode');
					if(!empty($rs)){
						$rangeId = "&billArea=" . $rs['managerCode'];
					}
				}
				//�ж��Ƿ�������ڱ���������
				if ($rtObj['isLate'] == 1) {
					succ_show('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId=' . $rtObj['id'] . '&flowMoney=' . $object['Amount'] .
						'&billDept=' . $object['CostBelongDeptId'] . '&billCompany=' . $object['CostBelongComId'] . $rangeId);
				} else {
					succ_show('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId=' . $rtObj['id'] . '&flowMoney=' . $object['Amount'] .
						'&billDept=' . $object['CostBelongDeptId'] . '&billCompany=' . $object['CostBelongComId'] . $rangeId);
				}
			} elseif ($object['thisAuditType'] == 'check') {
				msgRf('�ύ�ɹ���');
			} else {
				msgRf('����ɹ���');
			}
		}
	}

	/**
	 * ��ת���༭����������÷�̯��ϸ��ҳ��
	 */
	function c_toEdit() {
		$this->assignFunc($_GET);
		$obj = $this->service->getInfo_d($_GET['id']);
		$this->assignFunc($obj);

//		$deptArr = explode(',',unDeptFilter.$this->unDeptExtFilter);
        $deptArr = explode(',',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        //���ù������Ź�˾��ʶ
		$comCode = '';
		if(in_array($_SESSION['DEPT_ID'],$deptArr)){
			//������Ϣ����
			$this->assign('deptTempName','');
			$this->assign('deptTempId','');
		}else{
			//������Ϣ����
			$this->assign('deptTempName',$obj['CostBelongDeptName']);
			$this->assign('deptTempId',$obj['CostBelongDeptId']);
			if(!empty($obj['CostBelongDeptId'])){
				$sql = "select comCode from department where DEPT_ID = ".$obj['CostBelongDeptId'];
				$rs = $this->service->_db->getArray($sql);
				if(!empty($rs)){
					$comCode = $rs[0]['comCode'];
				}
			}
		}
		$this->assign('comCode',$comCode);
		$this->assign('comCodeDefault',$comCode);
		//��ȡ����
		// $this->assign('downloadUrl', $this->service->getFile_d());
        $otherdatasDao = new model_common_otherdatas();
        $docUrl = $otherdatasDao->getDocUrl($this->bindId);
        $this->assign('downloadUrl', $docUrl);

		//�������{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

		//�жϱ����˵ı������Ƿ�Ҫ���в��ż��
		$this->assign('needExpenseCheck', $this->service->needExpenseCheck_d($obj['CostMan']));

		//�жϵ�ǰ�����Ƿ���Ҫʡ��
		$this->assign('deptIsNeedProvince', $this->service->deptIsNeedProvince_d($obj['CostBelongDeptId']));

		//��ȡ��������۲���id
		$this->assign('saleDeptId', expenseSaleDeptId);

		//��ȡ����Ĺ��˲���id
//        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//		$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);
        $unSltDeptFilterStr = $this->unSltDeptFilter;// PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
        $this->assign('unSltDeptFilter', $unSltDeptFilterStr);

        // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
        $feemansForXtsSales = $this->service->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

		//���������жϼ��ط���
		if ($obj['isPush']) {
			//ʵ�����鿴ҳ��
			$this->assignFunc($this->service->initEsmEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));

			if ($obj['DetailType'] == 4) {
				$this->view('edittrp');
			} else {
				$this->view('editesm');
			}
		} else {
			//��ѡ�������������Լ���˾��Ȩ��
			$this->assign('allApply', $this->service->this_limit['���б�����']);
			$this->assign('allCompany', $this->service->this_limit['���й�˾']);
            $this->assign('allCompanyForSQ', $this->service->this_limit['��ǰ��˾�޸�Ȩ��']);

			// �������
			$this->assign('module', $obj['module']);

			//ʵ�����鿴ҳ��
			$this->assignFunc($this->service->initTempEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));
			$this->display('edit');
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
		$rtObj = $this->service->edit_d($object);
		if (!is_array($rtObj)) {
			msgRf($rtObj);
		}
		if ($rtObj) {
			if ($object['thisAuditType'] == 'audit') {
				$rangeId = '';
				//������Ŀ
				if ($object['projectType'] == 'esm' && $object['projectId']) {
					$esmprojectDao = new model_engineering_project_esmproject();
					$rangeId = $esmprojectDao->getRangeId_d($object['projectId']);
					$rangeId = "&billArea=" . $rangeId;
				}
				//�ж��Ƿ�������ڱ���������
				if ($rtObj['isLate'] == 1) {
					succ_show('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId=' . $rtObj['id'] .
						'&flowMoney=' . $object['Amount'] . '&billDept=' . $object['CostBelongDeptId'] .
						'&billCompany=' . $object['CostBelongComId'] .
						$rangeId);
				} else {
					succ_show('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId=' . $rtObj['id'] .
						'&flowMoney=' . $object['Amount'] . '&billDept=' . $object['CostBelongDeptId'] .
						'&billCompany=' . $object['CostBelongComId'] .
						$rangeId);
				}
			} elseif ($object['thisAuditType'] == 'check') {
				msgRf('�ύ�ɹ���');
			} else {
				msgRf('����ɹ���');
			}
		}
	}

	/**
	 * ��ת���鿴����������÷�̯��ϸ��ҳ��
	 */
	function c_toView() {
		$obj = $this->service->getInfo_d($_GET['id']);
		$this->assignFunc($obj);
		//��Ⱦ��������
		$this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

		//�������{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

		//ʵ�����鿴ҳ��
		$expensedetailArr = $this->service->initTempView_d($obj['expensedetail']);
		$this->assignFunc($expensedetailArr);

		$this->view('view');
	}

	/**
	 *  ���ż�� - ajax�ύ����
	 */
	function c_ajaxHand() {
		echo $this->service->ajaxHand_d($_POST['id']) ? 1 : 0;
	}

	/**
	 *  ���ż�� -ajax��ص���
	 */
	function c_ajaxBack() {
		echo $this->service->ajaxBack_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * ����б� - �༭
	 */
	function c_toEditCheck() {
		$this->assignFunc($_GET);
		$obj = $this->service->getInfo_d($_GET['id']);
		$this->assignFunc($obj);

		$this->showDatadictsByName(array('CustomerTypeView' => 'KHLX'), $obj['CustomerType'], true);
		//$deptArr = explode(',',unDeptFilter.$this->unDeptExtFilter);
        $deptArr = explode(',',$this->DenyFegsdeptId.$this->unDeptExtFilter);
        //���ù������Ź�˾��ʶ
		$comCode = '';
		if(in_array($_SESSION['DEPT_ID'],$deptArr)){
			//������Ϣ����
			$this->assign('deptTempName','');
			$this->assign('deptTempId','');
		}else{
			//������Ϣ����
			$this->assign('deptTempName',$obj['CostBelongDeptName']);
			$this->assign('deptTempId',$obj['CostBelongDeptId']);
			if(!empty($obj['CostBelongDeptId'])){
				$sql = "select comCode from department where DEPT_ID = ".$obj['CostBelongDeptId'];
				$rs = $this->service->_db->getArray($sql);
				if(!empty($rs)){
					$comCode = $rs[0]['comCode'];
				}
			}
		}
		$this->assign('comCode',$comCode);
		$this->assign('comCodeDefault',$comCode);
		//��ȡ����
		// $this->assign('downloadUrl', $this->service->getFile_d());
        $otherdatasDao = new model_common_otherdatas();
        $docUrl = $otherdatasDao->getDocUrl($this->bindId);
        $this->assign('downloadUrl', $docUrl);

		//�������{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name));

		//�жϵ�ǰ�����Ƿ���Ҫʡ��
		$this->assign('deptIsNeedProvince', $this->service->deptIsNeedProvince_d($obj['CostBelongDeptId']));

		//��ȡ��������۲���id
		$this->assign('saleDeptId', expenseSaleDeptId);

		//��ȡ����Ĺ��˲���id
        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
		//$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);
        $unSltDeptFilterStr = $this->unSltDeptFilter;// PMS 68 ���ڷ��ñ��������÷�̯��������ѡ���⼸������
        $this->assign('unSltDeptFilter', $unSltDeptFilterStr);

		//���������жϼ��ط���
		if ($obj['isPush']) {
			//ʵ�����鿴ҳ��
			$this->assignFunc($this->service->initEsmEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));

			if ($obj['DetailType'] == 4) {
				$this->view('edittrpcheck');
			} else {
				$this->view('editesmcheck');
			}
		} else {
			//��ѡ�������������Լ���˾��Ȩ��
			$this->assign('allApply', $this->service->this_limit['���б�����']);
			$this->assign('allCompany', $this->service->this_limit['���й�˾']);
            $this->assign('allCompanyForSQ', $this->service->this_limit['��ǰ��˾�޸�Ȩ��']);

			// �������
			$this->assign('module', $obj['module']);

			//ʵ�����鿴ҳ��
			$this->assignFunc($this->service->initTempEdit_d($obj));
			$this->assignFunc($this->service->initCostshareTempEdit_d($obj));
			$this->display('editcheck');
		}
	}

	/**
	 * ���ż���б� - �����յ�
	 */
	function c_ajaxDeptRec() {
		echo $this->service->ajaxDeptRec_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * ���ż���б� - �ύ����
	 */
	function c_ajaxHandFinance() {
		echo $this->service->ajaxHandFinance_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * �ύ����ȷ��
	 */
	function c_handConfirm() {
		echo $this->service->handConfirm_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * ȷ�ϵ���
	 */
	function c_confirmCheck() {
		echo $this->service->confirmCheck_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * ���ϵ���
	 */
	function c_unconfirmCheck() {
		echo $this->service->unconfirmCheck_d($_POST['id']) ? 1 : 0;
	}

	/**
	 * ajax�����ֶ� - ���ֶ�
	 */
	function c_ajaxUpdate() {
		$myValue = util_jsonUtil::iconvUTF2GB($_POST['myValue']);//�޸ĵ��ֶ�����
		echo $this->service->update(array('id' => $_POST['id']), array($_POST['myKey'] => $myValue)) ? 1 : 0;
	}

	/*************************** ���ñ���¼�� - ���̲��� ***************/
	/**
	 * ���ñ���¼��
	 */
	function c_toEsmExpenseAdd() {
		$this->assign('thisDate', day_date);
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyId', $_SESSION['USER_ID']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('CostBelongCom', $_SESSION['USER_COM_NAME']);
		$this->assign('CostBelongComId', $_SESSION['USER_COM']);
		$this->assign('relDocType', $_GET['relDocType']);
		$this->assign('relDocId', $_GET['relDocId']);

		//��Ŀid
		$projectId = isset($_GET['projectId']) ? $_GET['projectId'] : die();
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : 0;
		$days = isset($_GET['days']) ? $_GET['days'] : '';
		$memberId = isset($_GET['memberId']) ? $_GET['memberId'] : $_SESSION['USER_ID'];

		//��ȡ������Ŀ��������
		$projectObj = $this->service->getEsmInfo_d($projectId);
		$this->assignFunc($projectObj);

		//���ڴ���
		$dayArr = $this->dayDeal($days);
		$this->assignFunc($dayArr);

		//��ȡ����
		// $this->assign('downloadUrl', $this->service->getFile_d());
        $otherdatasDao = new model_common_otherdatas();
        $docUrl = $otherdatasDao->getDocUrl($this->bindId);
        $this->assign('downloadUrl', $docUrl);

		// Դ�����ݻ�ȡ
		if ($relDocType) {
			$registerDao = new model_outsourcing_vehicle_register();
			$expenseCostTypeArr = $registerDao->getAllKindsFeeById_d($_GET['relDocId']);
			$expenseCostTypeArr = $expenseCostTypeArr['fee'];
		} else {
			$esmcostdetailDao = new model_engineering_cost_esmcostdetail();
			$expenseCostTypeArr = $esmcostdetailDao->getCostForExpense_d($projectId, $dayArr['dayPeriod'], $memberId);
		}

		//ʵ������������ҳ��
		$expensedetailArr = $this->service->initEsmAdd_d($expenseCostTypeArr, $relDocType);
		if ($expensedetailArr) {
			$this->assignFunc($expensedetailArr);

			//������Ŀ�ж� -- �����������Ŀ���������ǰ���ñ���
			if ($projectObj['contractType'] == 'GCXMYD-04') {
				$this->view('addtrp');
			} else {
				$this->view('addesm');
			}
		} else {
			msgRf('û�пɱ����ķ��ã���ѡ���������ڵķ��ý��б���');
		}
	}

	/**
	 * ���ڴ���
	 * @param $days
	 * @return array
	 */
	function dayDeal($days) {
		$dayArr = explode(',', $days);
		$dayRtArr = array();
		foreach ($dayArr as $key => $val) {
			if (empty($dayRtArr)) {
				$dayRtArr['beginDate'] = $val;
				$dayRtArr['endDate'] = $val;
				$dayRtArr['dayPeriod'] = $days;
			} else {
				$dayRtArr['beginDate'] = min($dayRtArr['beginDate'], $val);
				$dayRtArr['endDate'] = max($dayRtArr['endDate'], $val);
				$dayRtArr['dayPeriod'] = $days;
			}
		}
		$dayRtArr['days'] = count($dayArr);
		return $dayRtArr;
	}

	/*************************** ģ�� **************************/

	/**
	 * ������Ϣģ����Ⱦ
	 */
	function c_initTempAdd() {
		if ($_POST['modelType']) {
			echo util_jsonUtil::iconvGB2UTF($this->service->initTempAdd_d($_POST['modelType']));
		} else {
			echo "";
		}
	}

	/**
	 * ��̯��Ϣģ����Ⱦ
	 */
	function c_initCostshareTempAdd() {
		if ($_POST['modelType']) {
			echo util_jsonUtil::iconvGB2UTF($this->service->initCostshareTempAdd_d($_POST['modelType']));
		} else {
			echo "";
		}
	}

	/************************* �������Ͳ��� ************************/
	/**
	 * ��ȡ���ñ�
	 */
	function c_getCostType() {
		echo util_jsonUtil::iconvGB2UTF($this->service->getCostType_d());
	}

	/************************* ����˵���ĵ��ϴ� *******************/
	/**
	 * ����˵���ĵ��ϴ�
	 */
	function c_setTypeDesc() {
		$this->assign('file', $this->service->getFilesByObjId(1, true, 'expenseselect'));
		$this->view('settypedesc');
	}

	/**
	 * �����ɱ�����ת
	 */
	function c_toSubOldExpense() {
		$obj = $this->service->find(array('id' => $_GET['id']), null, 'ProjectNo');
		if ($obj['ProjectNo']) {
			succ_show("general/costmanage/reim/summary_check_list_pro.php?pro=" . $obj['ProjectNo']);
		} else {
			exit('��ת·������');
		}
	}

	/************************** ��ǰ�ۺ��Ż�ȡ ******************/
	/**
	 * ��ȡ��ǰ�ۺ���
	 */
	function c_getSaleDept() {
		echo util_jsonUtil::encode($this->service->getSaleDept_d($_GET['detailType']));
	}

	/**
	 * �жϲ����Ƿ���Ҫʡ����Ϣ
	 */
	function c_deptIsNeedProvince() {
		echo $this->service->deptIsNeedProvince_d($_POST['deptId']) ? 1 : 0;
	}

	/**
	 * ajax��ȡ��������Ϣ
	 */
	function c_ajaxGet() {
		$obj = $this->service->find(array(
			'id' => $_POST['id']
		));
		echo util_jsonUtil::encode($obj);
	}

    /****************************** ����¼��Ԥ���Ĳ��� ****************************/
    /**
     * ��ȡ����Ԥ��
     */
    function c_getWarning() {
        $k = $_POST['k'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $projectNos = $_POST['projectNos'];
        $projectIds = $_POST['projectIds'];
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;

        // ��ȡ��ǰ���ڵı�������
        $thisPeriodFee = $this->service->getPeriodFee_d($year, $month, $projectNos);

        // ��ȡ��ǰ���ڵı�������
        $prevPeriodFee = $this->service->getPeriodFee_d($prevYear, $prevMonth, $projectNos);

        echo json_encode(array(
            'thisPeriodFee' => $thisPeriodFee,
            'prevPeriodFee' => $prevPeriodFee,
            'changeRate' => $prevPeriodFee == 0 ? '--' : round(($thisPeriodFee - $prevPeriodFee)/$prevPeriodFee * 100, 2),
            'k' => $k,
            'projectNos' => $projectNos,
            'projectIds' => $projectIds
        ));
    }

    /**
     * ά������ģ��
     */
    function c_toModifyModel(){
        $this->view('toModifyModel');
    }
    function c_modifyModel(){
        $obj = $_POST['expenseModel'];
        $act = $obj['act'];
        unset($obj['act']);

        $obj['updateTime'] = date("Y-m-d H:i:s");
        $customtemplateDao = new model_finance_expense_customtemplate();
        $result = false;
        $optStr = "����";
        switch ($act){
            case 'add':
                $optStr = "����";
                $obj['userId'] = $_SESSION['USER_ID'];
                $obj['userName'] = $_SESSION['USER_NAME'];
                $result = $customtemplateDao->add_d($obj);
                break;
            case 'edit':
                $optStr = "�༭";
                $result = $customtemplateDao->edit_d($obj);
                break;
        }

        if($result){
            echo "<script>alert('".$optStr."�ɹ�!');parent.cleanPage();parent.loadList();parent.reloadParentModelList();</script>";
        }else{
            echo "<script>alert('".$optStr."ʧ��!'); window.history.back();</script>";
        }
    }

    /**
     * ��������ģ��
     */
    function c_toAddModel(){
        $this->view('toAddModel');
    }

    /**
     * �༭����ģ��
     */
    function c_toEditModel(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $customtemplateDao = new model_finance_expense_customtemplate();
        $row = $customtemplateDao->get_d($id);
        $this->assignFunc($row);

        $this->view('toEditModel');
    }

    /**
     * ɾ������ģ��
     */
    function c_deleteModel(){
        $id = isset($_REQUEST['modelId'])? $_REQUEST['modelId'] : '';
        if($id != ""){
            $customtemplateDao = new model_finance_expense_customtemplate();
            $result = $customtemplateDao->delete(array("id" => $id));
            echo ($result)? "ok" : "fail";
        }else{
            echo "fail";
        }
    }

    /**
     * ��ȡ���б������д��ڵ���Ŀʡ����Ϣ
     */
    function c_getAllProProvince(){
        $result = $this->service->getAllProProvince_d();
        $backArr['optsArr'] = array();
        foreach ($result as $row){
            if(isset($row['proProvinceId']) && $row['proProvinceId'] > 0){
                $backArr['optsArr'][$row['proProvinceId']] = $row['proProvince'];
            }
        }
        echo util_jsonUtil::encode($backArr);
    }
}