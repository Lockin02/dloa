<?php
/**
 * Class controller_contractTool_contractTool_mytable
 * ���˹�������
 */
class controller_contractTool_contractTool_mytable extends controller_base_action
{

    function __construct()
    {
        $this->objName = "contractTool";
        $this->objPath = "contractTool_contractTool";
        parent :: __construct();
    }

    /**
     * ��ҳ
     */
    function c_toMytable()
    {
    	set_time_limit(0);
        //ʵ�������� - ��ͬ
        $conDao = new model_contract_contract_contract();
        //ʵ�������� - ����
        $checkaccept = new model_contract_checkaccept_checkaccept();

/**************************************************************/
////��Ʊ�տ�����
//        $conDao->searchArr = array();
//        $conDao->initLimit();
//        $conDao->groupBy="r.id";
//        $conDao->pageBySqlId('select_financialTday');
//die();
/**************************************************************/
        //�ﱸ��ͬ����
        $conDao->searchArr = array();
        $conditions['finishStatus'] ="0";
        $conDao->buildContract_d($conditions);
        $data['numArr']['build'] = $conDao->count;

        //��ͬ��������
        $conDao->searchArr = array();
        $conditions['state'] ="2";
        $conDao->deliveryContract_d($conditions);
        $data['numArr']['delivery'] = $conDao->count;

        //����
        $checkaccept->searchArr['checkStatus'] = "δ����";
        $checkaccept->groupBy="c.contractCode";
        $checkaccept->initLimit();
        $checkaccept->page_d();
        $data['numArr']['wait'] = $checkaccept->count;

        //��Ʊ�տ�����
        $conDao->searchArr['mySearchCondition']= "sql: and (r.incomMoney <> r.money or r.invoiceMoney <> r.money) ";
        $conDao->initLimit();
        $conDao->groupBy="c.contractCode";
        $conDao->sort="c.id";
        $conDao->pageBySqlId('select_financialTday_new');
        $data['numArr']['invoice'] = $conDao->count;

        //��ͬ�ر�����
        $conDao->searchArr = array('states' => '3,7');
        $conDao->initLimit();
        $conDao->groupBy="c.id";
        $conDao->pageBysqlId ('select_buildList');
        $data['numArr']['close'] = $conDao->count;

        //��ͬ�ı�����
        $conDao->searchArr = array();
        $conDao->contractArchive_d();
        $data['numArr']['text'] = $conDao->count;

        $year = date('Y');
        $month = date('Y-m');
        $state = '2,3,4,7';
        $contractArr = $this->getContractNumArr($year, $month, $state,"ht");
        //ȫ����ǩ��ͬ��
        $data['numArr']['thisYear'] = $contractArr[0];
        //������ǩ��ͬ��
        $data['numArr']['thisMonth'] = $contractArr[1];

        $state = '4';
        $deliveryContractArr = $this->getContractNumArr($year, $month, $state,"jf");
        //ȫ������ɽ�����ͬ
        $data['numArr']['hasDelivery'] = $deliveryContractArr[0];
        //��������ɽ�����ͬ
        $data['numArr']['hasDeliveryM'] = $deliveryContractArr[1];

        $state = '2';
        $noDeliveryContractArr = $this->getContractNumArr($year, $month, $state,"jf");
        //ȫ��δ��ɽ�����ͬ
//        $data['numArr']['noDelivery'] = $noDeliveryContractArr[0];
        $data['numArr']['noDelivery'] = $noDeliveryContractArr[0];
        //����δ��ɽ�����ͬ
        $data['numArr']['noDeliveryM'] = $noDeliveryContractArr[1];

        //ȫ��Ӧ������ͬ
        $data['numArr']['shouldDelivery'] = $data['numArr']['hasDelivery'] + $data['numArr']['noDelivery'];
        //����Ӧ������ͬ
        $data['numArr']['shouldDeliveryM'] = $data['numArr']['hasDeliveryM'] + $data['numArr']['noDeliveryM'];

        $checkacceptWYS = $this->getCheckNumArr($year, $month, "δ����");
        $checkacceptYYS = $this->getCheckNumArr($year, $month, "������");

        //����δ������պ�ͬ
        $data['numArr']['noCheckM'] = $checkacceptWYS[1];
        //ȫ��δ������պ�ͬ
        $data['numArr']['noCheck'] = $checkacceptWYS[0];

        //������������պ�ͬ
        $data['numArr']['hasCheckM'] = $checkacceptYYS[1];
        //ȫ����������պ�ͬ
        $data['numArr']['hasCheck'] = $checkacceptYYS[0];

         //����Ӧ���պ�ͬ
        $data['numArr']['shouldcheckM'] = $data['numArr']['noCheckM'] + $data['numArr']['hasCheckM'];
        //ȫ��Ӧ���պ�ͬ
        $data['numArr']['shouldcheck'] = $data['numArr']['noCheck'] + $data['numArr']['hasCheck'];



        $sqlYear = "sql: and date_format(r.Tday,'%Y')='" . $year . "'";
        $sqlMonth = "sql: and date_format(r.Tday,'%Y-%m')='" . $month . "'";

        $invoiceYear = $this->getInvoiceNumArr($sqlYear);
        $invoiceMonth = $this->getInvoiceNumArr($sqlMonth);

        //ȫ��Ӧ��Ʊ�տ�
        $data['numArr']['shouldPayY'] = $invoiceYear[0];
        //ȫ������ɿ�Ʊ�տ�
        $data['numArr']['hasPayY'] = $invoiceYear[1];
        //ȫ��δ��ɿ�Ʊ�տ�
        $data['numArr']['noPayY'] = $data['numArr']['shouldPayY'] - $data['numArr']['hasPayY'];

        //����Ӧ��Ʊ�տ�
        $data['numArr']['shouldPayM'] = $invoiceMonth[0];
        //��������ɿ�Ʊ�տ�
        $data['numArr']['hasPayM'] = $invoiceMonth[1];
        //����δ��ɿ�Ʊ�տ�
        $data['numArr']['noPayM'] = $data['numArr']['shouldPayM'] - $data['numArr']['hasPayM'];


        $sqlYear = "sql: and date_format(c.ExaDTOne,'%Y')='" . $year . "'";
        $sqlMonth = "sql: and date_format(c.ExaDTOne,'%Y-%m')='" . $month . "'";

        $invoiceYGD = $this->getArchiveNumArr($year,$month,"1");
        $invoiceWGD = $this->getArchiveNumArr($year,$month,"0,2");
        //ȫ������ɹ鵵
        $data['numArr']['hasSignY'] = $invoiceYGD[0];
        //ȫ��δ��ɹ鵵
        $data['numArr']['noSignY'] = $invoiceWGD[0];
        //ȫ��Ӧ�鵵
        $data['numArr']['shouldSignY'] = $data['numArr']['hasSignY'] + $data['numArr']['noSignY'];

        //��������ɹ鵵
        $data['numArr']['hasSignM'] = $invoiceYGD[1];
        //����δ��ɹ鵵
        $data['numArr']['noSignM'] = $invoiceWGD[1];
        //����Ӧ�鵵
        $data['numArr']['shouldSignM'] = $data['numArr']['hasSignM'] + $data['numArr']['noSignM'];


        //��������ͳ��
//        $data = $this->todoTask($data);
        $limitInfo = $this->toGetAuthorize();
        $data = $this->setAuthorize($data, $limitInfo);
        $this->displayPT('mytable', $data);
    }

    /**
     * ��ȡȫ������º�ͬͳ��
     *
     */
    function getContractNumArr($year, $month, $state,$type=null)
    {
        $conDao = new model_contract_contract_contract();
        $conDao->searchArr = null;
//        if (isset($state)) {
//            $conDao->searchArr['states'] = $state;
//        }
        $conDao->searchArr['isTemp'] = 0;
//        $conDao->searchArr['ExaStatus'] = '���';
        $conDao->pageSize=1000000;//����ҳ����ʱ����д
        //Ȩ��
        $conDao->initLimit();

        //ȫ������
//        $conDao->searchArr['data'] = "sql: and date_format(c.ExaDTOne,'%Y')='" . $year . "'
//          and c.areaCode not in (22,23,24,25,27,10,119,19,67,68,69,70,71,72,73,74,28) ";
//        $contractDatas = $conDao->pageBysqlId ('select_default');
        $conditionsY['ExaYear'] = $year;
        if (isset($state)){
        	$conditionsY['states'] = $state;
        }
        //��������
//        $conDao->searchArr['data'] = "sql: and date_format(c.ExaDTOne,'%Y-%m')='" .$month . "'
//          and c.areaCode not in (22,23,24,25,27,10,119,19,67,68,69,70,71,72,73,74,28) ";
//        $contractDatasM = $conDao->pageBysqlId ('select_default');
        $conditionsM['ExaYearMonth'] = $month;
        if (isset($state)){
        	$conditionsM['states'] = $state;
        }
        switch($type){
        	case "ht" :
        	   $rowsYear = $conDao->buildContract_d($conditionsY,true);
                 $rowsMon =  $conDao->buildContract_d($conditionsM,true);
        	  break;
        	case "jf" :
        	   $rowsYear = $conDao->deliveryContract_d($conditionsY,true);
                 $rowsMon =  $conDao->deliveryContract_d($conditionsM,true);
        	  break;
        }

        $num[0] = $rowsYear ? count($rowsYear) : 0;
        $num[1] = $rowsMon ? count($rowsMon) : 0;
        return $num;
    }

    /**
     * ��ȡ��ͬ����ͳ��
     */
    function getCheckNumArr($year, $month,$checkStatus)
    {
        $checkaccept = new model_contract_checkaccept_checkaccept();
        $checkaccept->searchArr = null;
        $checkaccept->pageSize=1000000;//����ҳ����ʱ����д

        $conditionsM['ExaYearMonth'] = $month;
        $conditionsM['checkStatus'] = $checkStatus;
        $conditionsY['ExaYear'] = $year;
        $conditionsY['checkStatus'] = $checkStatus;

        $checkaccept->sort = "isFinish ASC,isOutDate DESC,c.checkDate";
        $checkaccept->asc = false;
        $checkaccept->groupBy = 'contractCode';
        $checkaccept->initLimit();

        $checkaccept->getParam($conditionsY);
        $y = $checkaccept->page_d('select_statistical');
        $checkaccept->getParam($conditionsM);
        $m = $checkaccept->page_d('select_statistical');


       $checkNum[0] = $y ? count($y) : 0;
       $checkNum[1] = $m ? count($m) : 0;
        return $checkNum;
    }

    /**
     * ��ȡ��Ʊ����ͳ��
     */
    function getInvoiceNumArr($sql)
    {
    	$conDao = new model_contract_contract_contract();
    	$sqlStr = "select count(r.id) as num from oa_contract_receiptplan r
					left join oa_contract_contract c on r.contractId=c.id
					where r.isTemp = '0' and c.ExaStatus='���' ".$sql ;

    	$finishsql = "sql: and r.incomMoney = r.money and r.invoiceMoney = r.money ";
//     	$notfinishsql = "  and (r.incomMoney <> r.money or r.invoiceMoney <> r.money) ";

        $conDao->pageSize=1000000;//����ҳ����ʱ����д

        $conDao->searchArr = array();
        $conDao->initLimit();
        $conDao->groupBy="c.contractCode";
        $conDao->searchArr['data'] = $sql;
        $all = $conDao->pageBySqlId('select_financialTday');

        $conDao->searchArr['ExaStatusSql'] = $finishsql;
        $end = $conDao->pageBySqlId('select_financialTday');


    	$result = array();
    	array_push($result, $all ? count($all) : 0);
    	array_push($result, $end ? count($end) : 0);
    	return $result;

    }


   	/**
   	 * ��ȡ�鵵
   	 * @param unknown $sql
   	 * @return multitype:
   	 */
    function getArchiveNumArr($year, $month,$state)
    {
    	$conDao = new model_contract_contract_contract();
    	$conDao->searchArr = null;
        $conDao->pageSize=1000000;//����ҳ����ʱ����д

	        $conditionsM['ExaYearMonth'] = $month;
			$conditionsM['signStatusArr'] = $state;
			$conditionsM['states'] = '1,2,3,4,5,6,7';
			$conditionsM['isTemp'] = '0';
			$conditionsM['ExaStatus'] = '���';

			$conditionsY['ExaYear'] = $year;
			$conditionsY['signStatusArr'] = $state;
			$conditionsY['states'] = '1,2,3,4,5,6,7';
			$conditionsY['isTemp'] = '0';
			$conditionsY['ExaStatus'] = '���';

        $conDao->initLimit();


		$conDao->getParam($conditionsY);
		$y = $conDao->pageBysqlId('select_buildList');

		$conDao->getParam($conditionsM);
		$m = $conDao->pageBysqlId('select_buildList');

    	$result = array();
    	$result[0] = $y ? count($y) : 0;
        $result[1] = $m ? count($m) : 0;

    	return $result;

    }


    /**
     * ��������ͳ��
     */
    function todoTask($data)
    {
        //�����պ�ͬͳ��
        $checkaccept = new model_contract_checkaccept_checkaccept();
        $checkaccept->searchArr = null;
        $checkaccept->searchArr['checkStatus'] = 'δ����';
        $checkacceptRows = $checkaccept->list_d();
        $data['numArr']['waitAcceptTask'] = $checkacceptRows ? count($checkacceptRows) : 0;

        //��Ʊ�տ�ͳ��
        $contract = new model_contract_contract_contract();
        $contract->searchArr = null;
        $contract->searchArr['isDel'] = 0;
        $contract->searchArr['isSellSql'] = "sql: and (r.Tday is null || r.Tday='0000-00-00')";
        $contractRows = $contract->list_d('select_financialTday');
        $data['numArr']['invoiceContractTask'] = $contractRows ? count($contractRows) : 0;

//        //��ͬ���鵵ͳ��
//        $contract->searchArr = null;
//        $contract->searchArr['states'] = "1,2,3,4,5,6,7";
//        $contract->searchArr['isTemp'] = "0";
//        $contract->searchArr['ExaStatus'] = "���";
//        $contract->searchArr['signStatusArr'] = "0,2";
//        $rows = $contract->list_d('select_gridinfo');
//        $data['numArr']['archiveContractTask'] = $rows ? count($rows) : 0;
        return $data;
    }



    /**
     * ��ȡ��ͬ����Ȩ��
     */
    function toGetAuthorize()
    {
        $userCode = $_SESSION['USER_ID'];
        $limitInfo = $this->service->get_table_fields('oa_contractTool_authorize', "userCode='" . $userCode . "'", 'limitInfo');
        return $limitInfo;
    }

    /**
     * ���ú�ͬ����Ȩ��
     */
    function setAuthorize($data, $limitArr)
    {
        $limitArr = explode(',', $limitArr);
        foreach ($limitArr as $k => $v) {
            $data[$v] = true;
        }
        return $data;
    }
}