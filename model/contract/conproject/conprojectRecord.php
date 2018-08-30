<?php
/**
 * @author liub
 * @Date 2014��5��29�� 14:50:09
 * @version 1.0
 * @description:��ͬ��Ŀ�� Model��
 */
 class model_contract_conproject_conprojectRecord  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_project_record";
		$this->sql_map = "contract/conproject/conprojectRecordSql.php";
		parent::__construct ();

        // ʵ������Ŀ
        $this->conProjectDao = new model_contract_conproject_conproject();
    }

     // ��Ŀdao
     private $conProjectDao;

     // ��Ŀ����
     private $conProjectCache = array();

     /**
      * ��ȡ��Ŀ���� - ������������Ϣ
      */
     function getConProjectInfo_d() {
         return $this->conProjectDao->list_d();
     }

     /**
      * ��ȡ���汾ֵ
      */
     function getMaxVersion($isView=null){
     	 $sql = "select max(version) as maxNum from oa_contract_project_record";
     	 $arr = $this->_db->getArray($sql);
     	 if(!empty($arr[0]['maxNum'])){
     	 	if(substr($arr[0]['maxNum'],0,8) == date("Ymd") || $isView == '1'){
                return $arr[0]['maxNum'] + 1;
     	 	}else{
     	 		$r = date("Ymd") . "01";
     	 	    return $r;
     	 	}

     	 }else{
     	 	 $r = date("Ymd") . "01";
     	 	 return $r;
     	 }
     }

     /**
      * ��ȡ���°����İ汾
      * @param $storeYear
      * @param $storeMonth
      * @return mixed
      */
     function getVersion_d($storeYear, $storeMonth) {
         $sql = "SELECT version,isUse FROM " . $this->tbl_name ." WHERE storeYear = " . $storeYear .
             " AND storeMon = " . $storeMonth . " GROUP BY version ORDER BY version DESC";
         return $this->_db->getArray($sql);
     }

     /**
      * ��ȡ���汾����
      */
     function getVersionInfo_d() {
         $versionInfo = array();

         // ��ѯ��ǰ��߰汾
         $maxVersion = $this->find(null, 'version DESC', 'version');

         if ($maxVersion) {
             $today = date('Ymd');
             $versionDay = substr($maxVersion['version'], 0, 8);
             if ($today != $versionDay) {
                 $versionInfo['version'] = $today . '01';
             } else {
                 $versionInfo['version'] = intval($maxVersion['version']) + 1 ;
             }
             $versionInfo['maxVersion'] = $maxVersion['version'];
         } else {
             $versionInfo['version'] = date('Ymd') . '01';
             $versionInfo['maxVersion'] = 0;
         }
         // �ꡢ�·�
         $versionInfo['storeYear'] = date('Y');
         $versionInfo['storeMon'] = date('m');

         return $versionInfo;
     }

     /**
      * ��⵱ǰ�·��Ƿ��Ѿ������ð汾
      * @param $storeYearMonth
      * @return int
      */
     function checkIsUsing_d($storeYearMonth) {
         $storeYear = substr($storeYearMonth, 0, 4);
         $storeMonth = substr($storeYearMonth, -2);
         $sql = "SELECT id FROM " . $this->tbl_name ." WHERE storeYear = " . $storeYear .
             " AND storeMon = " . $storeMonth . " AND isUse = 1";
         return $this->_db->getArray($sql) ? 1 : 0;
     }

     /**
      * ���ð汾
      * �����°汾��Ϊָ�����µ����ð汾
      * @param $version
      * @param $storeYearMonth
      * @return int
      */
     function setUsing_d($version, $storeYearMonth) {
         $storeYear = substr($storeYearMonth, 0, 4);
         $storeMonth = substr($storeYearMonth, -2);

         $versionInfo = $this->getVersionInfo_d();
         if (!$versionInfo['maxVersion']) {
             return -1;
         }

         try {
             $this->start_d();

             // �ر����ð汾
             $this->update(array('isUse' => 1, 'storeYear' => $storeYear, 'storeMon' => $storeMonth),
                 array('isUse' => 0));

             // �������°汾
             $this->update(array('version' => $version),
                 array('isUse' => 1, 'storeYear' => $storeYear, 'storeMon' => $storeMonth));

             $this->commit_d();
             return 1;
         } catch (Exception $e) {
             $this->rollBack();
             return 0;
         }
     }

     /**
      * ������Ŀ����
      * @param $obj
      * @param $versionInfo
      * @param bool $isRealSave
      */
     function saveRecord_d($obj, $versionInfo, $isRealSave = false) {
         // ��ʽ������
         $esmDao = new model_engineering_project_esmproject();

         if(!empty($obj['contractId'])){//���ڹ�����ͬ,�����ȡ���ʵʱ����
             $esmArr = $esmDao->getProjectList_d(array($obj['contractId']));
             $conObj = $this->conProjectDao->getConPorjectNowInfoByCid($obj, $esmArr[$obj['esmProjectId']]);

             $obj['proportion'] = $conObj['proportion'];
             $obj['proportionTrue'] = $conObj['proportionTrue'];
             $obj['proMoney'] = $conObj['proMoney'];
             $obj['contractMoney'] = $conObj['contractMoney'];
             $obj['txaRate'] = $conObj['txaRate'];
             $obj['rateMoney'] = $conObj['rateMoney'];
             $obj['exgross'] = $conObj['exgross'];
             $obj['gross'] = $conObj['gross'];
             $obj['estimates'] = $conObj['estimates'];
             $obj['schedule'] = $conObj['schedule']; //����
             $obj['earnings'] = $conObj['earnings']; //����
             $obj['reserveEarnings'] = $conObj['earnings']*0.02; //Ԥ��Ӫ��

             $obj['deductMoney'] =  $conObj['deductMoney']; //�ۿ�
             $obj['badMoney'] =  $conObj['badMoney']; //����

             $obj['budget'] = $conObj['budget']; //Ԥ��
             $obj['cost'] = $conObj['cost']; //����
             $obj['planBeginDate'] = $conObj['planBeginDate'];
             $obj['planEndDate'] = $conObj['planEndDate'];
             $obj['actBeginDate'] = $conObj['actBeginDate'];
             $obj['actEndDate'] = $conObj['actEndDate'];

             $obj['grossTrue'] = $conObj['earnings'] - $conObj['cost'] - $obj['costAct']; //ë��
             $obj['exgrossTrue'] = bcdiv($conObj['earnings'] - $conObj['cost'] - $obj['costAct'], $conObj['earnings'], 2) * 100; //ë����
             $obj['module'] = $conObj['module']; //������
             $obj['moduleName'] = $conObj['moduleName']; //���
             $obj['officeId'] = $conObj['officeId']; //����id
             $obj['officeName'] = $conObj['officeName']; //����
         }else{
             $obj['grossTrue'] = $obj['earnings'] - $obj['cost']; //ë��
             $obj['exgrossTrue'] = bcdiv($obj['earnings'] - $obj['cost'], $obj['earnings'], 2) * 100; //ë����
             $obj['reserveEarnings'] = $obj['earnings']*0.02; //Ԥ��Ӫ��
         }

         // ���»�ȡ��Ӧ��ֵ, ����Ŀ��Ϣ�鿴ҳ��ͳһȡֵ��start��
         $viewData = $this->conProjectDao->getProView($obj['id']);
         $obj['rateMoney'] = $viewData['proMoneyRate'];// ˰����Ŀ���
         $obj['schedule'] = $viewData['schedule'];// ��Ŀ����
         $obj['objDeduct'] = $viewData['objDeduct'];// ��Ŀ�ۿ�
         $obj['objBad'] = $viewData['objBad'];// ��Ŀ����
         $obj['earnings'] = $viewData['revenue'];// ��ĿӪ��
         $obj['cost'] = $viewData['finalCost'];// ��Ŀ����
         $obj['exgrossTrue'] = $viewData['budgetExgross'];// ë����
         $obj['shipCost'] = $viewData['shipCost'];// ���ᷢ���ɱ�
         $obj['feeCostbx'] = $viewData['feeCostbx'];// ����֧���ɱ�
         $obj['otherCost'] = $viewData['otherCost'];// �����ɱ�
         // ���»�ȡ��Ӧ��ֵ, ����Ŀ��Ϣ�鿴ҳ��ͳһȡֵ��end��

         
         $obj['pid'] = $obj['id'];
         unset($obj['id']);

         $obj =array_merge($obj,$versionInfo);
         $obj['storeYear'] = date("Y");
         $obj['storeMon'] = date("m");
         $obj['isUse'] = "0";
         $obj['storeDate'] = date("Y-m-d H:i:s");;
         $obj['storeName'] = $_SESSION['USERNAME'];
         $obj['storeNameId'] = $_SESSION['USER_ID'];

         $this->conProjectCache[] = $obj;

         // ���ȷʵҪ���棬���������ݲ�����У��������cache
         if($isRealSave) {
             // do save
             $this->createBatch($this->conProjectCache);

             // clean cache
             $this->conProjectCache = array();
         }
     }

     /**
      * ���浥����Ŀ����
      * @param $obj
      * @param $versionInfo
      */
     function saveSingleRecord_d($obj, $versionInfo) {
         // ��ʽ������
         $esmDao = new model_engineering_project_esmproject();

         if(!empty($obj['contractId'])){//���ڹ�����ͬ,�����ȡ���ʵʱ����
             $esmArr = $esmDao->getProjectList_d(array($obj['contractId']));
             $conObj = $this->conProjectDao->getConPorjectNowInfoByCid($obj, $esmArr[$obj['esmProjectId']]);

             $obj['proportion'] = $conObj['proportion'];
             $obj['proportionTrue'] = $conObj['proportionTrue'];
             $obj['proMoney'] = $conObj['proMoney'];
             $obj['contractMoney'] = $conObj['contractMoney'];
             $obj['txaRate'] = $conObj['txaRate'];
             $obj['rateMoney'] = $conObj['rateMoney'];
             $obj['exgross'] = $conObj['exgross'];
             $obj['gross'] = $conObj['gross'];
             $obj['estimates'] = $conObj['estimates'];
             $obj['schedule'] = $conObj['schedule']; //����
             $obj['earnings'] = $conObj['earnings']; //����
             $obj['reserveEarnings'] = $conObj['earnings']*0.02; //Ԥ��Ӫ��

             $obj['deductMoney'] =  $conObj['deductMoney']; //�ۿ�
             $obj['badMoney'] =  $conObj['badMoney']; //����

             $obj['budget'] = $conObj['budget']; //Ԥ��
             $obj['cost'] = $conObj['cost']; //����
             $obj['planBeginDate'] = $conObj['planBeginDate'];
             $obj['planEndDate'] = $conObj['planEndDate'];
             $obj['actBeginDate'] = $conObj['actBeginDate'];
             $obj['actEndDate'] = $conObj['actEndDate'];

             $obj['grossTrue'] = $conObj['earnings'] - $conObj['cost'] - $obj['costAct']; //ë��
             $obj['exgrossTrue'] = bcdiv($conObj['earnings'] - $conObj['cost'] - $obj['costAct'], $conObj['earnings'], 2) * 100; //ë����
             $obj['module'] = $conObj['module']; //������
             $obj['moduleName'] = $conObj['moduleName']; //���
             $obj['officeId'] = $conObj['officeId']; //����id
             $obj['officeName'] = $conObj['officeName']; //����
         }else{
             $obj['grossTrue'] = $obj['earnings'] - $obj['cost']; //ë��
             $obj['exgrossTrue'] = bcdiv($obj['earnings'] - $obj['cost'], $obj['earnings'], 2) * 100; //ë����
             $obj['reserveEarnings'] = $obj['earnings']*0.02; //Ԥ��Ӫ��
         }

         // ���»�ȡ��Ӧ��ֵ, ����Ŀ��Ϣ�鿴ҳ��ͳһȡֵ��start��
         $viewData = $this->conProjectDao->getProView($obj['id']);
         $obj['rateMoney'] = $viewData['proMoneyRate'];// ˰����Ŀ���
         $obj['schedule'] = $viewData['schedule'];// ��Ŀ����
         $obj['objDeduct'] = $viewData['objDeduct'];// ��Ŀ�ۿ�
         $obj['objBad'] = $viewData['objBad'];// ��Ŀ����
         $obj['earnings'] = $viewData['revenue'];// ��ĿӪ��
         $obj['cost'] = $viewData['finalCost'];// ��Ŀ����
         $obj['exgrossTrue'] = $viewData['budgetExgross'];// ë����
         $obj['shipCost'] = $viewData['shipCost'];// ���ᷢ���ɱ�
         $obj['feeCostbx'] = $viewData['feeCostbx'];// ����֧���ɱ�
         $obj['otherCost'] = $viewData['otherCost'];// �����ɱ�
         // ���»�ȡ��Ӧ��ֵ, ����Ŀ��Ϣ�鿴ҳ��ͳһȡֵ��end��


         $obj['pid'] = $obj['id'];
         unset($obj['id']);

         $obj =array_merge($obj,$versionInfo);
         $obj['storeYear'] = date("Y");
         $obj['storeMon'] = date("m");
         $obj['isUse'] = "0";
         $obj['storeDate'] = date("Y-m-d H:i:s");;
         $obj['storeName'] = $_SESSION['USERNAME'];
         $obj['storeNameId'] = $_SESSION['USER_ID'];

         $this->add_d($obj);
     }
 }
?>