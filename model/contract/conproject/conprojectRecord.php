<?php
/**
 * @author liub
 * @Date 2014年5月29日 14:50:09
 * @version 1.0
 * @description:合同项目表 Model层
 */
 class model_contract_conproject_conprojectRecord  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_project_record";
		$this->sql_map = "contract/conproject/conprojectRecordSql.php";
		parent::__construct ();

        // 实例化项目
        $this->conProjectDao = new model_contract_conproject_conproject();
    }

     // 项目dao
     private $conProjectDao;

     // 项目数据
     private $conProjectCache = array();

     /**
      * 获取项目数据 - 不包含决算信息
      */
     function getConProjectInfo_d() {
         return $this->conProjectDao->list_d();
     }

     /**
      * 获取最大版本值
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
      * 获取年月包含的版本
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
      * 获取最大版本数组
      */
     function getVersionInfo_d() {
         $versionInfo = array();

         // 查询当前最高版本
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
         // 年、月份
         $versionInfo['storeYear'] = date('Y');
         $versionInfo['storeMon'] = date('m');

         return $versionInfo;
     }

     /**
      * 检测当前月份是否已经有在用版本
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
      * 启用版本
      * 将最新版本存为指定年月的启用版本
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

             // 关闭在用版本
             $this->update(array('isUse' => 1, 'storeYear' => $storeYear, 'storeMon' => $storeMonth),
                 array('isUse' => 0));

             // 启用最新版本
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
      * 保存项目数据
      * @param $obj
      * @param $versionInfo
      * @param bool $isRealSave
      */
     function saveRecord_d($obj, $versionInfo, $isRealSave = false) {
         // 格式化数据
         $esmDao = new model_engineering_project_esmproject();

         if(!empty($obj['contractId'])){//存在关联合同,才需获取相关实时数据
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
             $obj['schedule'] = $conObj['schedule']; //进度
             $obj['earnings'] = $conObj['earnings']; //收入
             $obj['reserveEarnings'] = $conObj['earnings']*0.02; //预留营收

             $obj['deductMoney'] =  $conObj['deductMoney']; //扣款
             $obj['badMoney'] =  $conObj['badMoney']; //坏账

             $obj['budget'] = $conObj['budget']; //预算
             $obj['cost'] = $conObj['cost']; //决算
             $obj['planBeginDate'] = $conObj['planBeginDate'];
             $obj['planEndDate'] = $conObj['planEndDate'];
             $obj['actBeginDate'] = $conObj['actBeginDate'];
             $obj['actEndDate'] = $conObj['actEndDate'];

             $obj['grossTrue'] = $conObj['earnings'] - $conObj['cost'] - $obj['costAct']; //毛利
             $obj['exgrossTrue'] = bcdiv($conObj['earnings'] - $conObj['cost'] - $obj['costAct'], $conObj['earnings'], 2) * 100; //毛利率
             $obj['module'] = $conObj['module']; //板块编码
             $obj['moduleName'] = $conObj['moduleName']; //板块
             $obj['officeId'] = $conObj['officeId']; //区域id
             $obj['officeName'] = $conObj['officeName']; //区域
         }else{
             $obj['grossTrue'] = $obj['earnings'] - $obj['cost']; //毛利
             $obj['exgrossTrue'] = bcdiv($obj['earnings'] - $obj['cost'], $obj['earnings'], 2) * 100; //毛利率
             $obj['reserveEarnings'] = $obj['earnings']*0.02; //预留营收
         }

         // 重新获取相应的值, 与项目信息查看页面统一取值（start）
         $viewData = $this->conProjectDao->getProView($obj['id']);
         $obj['rateMoney'] = $viewData['proMoneyRate'];// 税后项目金额
         $obj['schedule'] = $viewData['schedule'];// 项目进度
         $obj['objDeduct'] = $viewData['objDeduct'];// 项目扣款
         $obj['objBad'] = $viewData['objBad'];// 项目坏账
         $obj['earnings'] = $viewData['revenue'];// 项目营收
         $obj['cost'] = $viewData['finalCost'];// 项目决算
         $obj['exgrossTrue'] = $viewData['budgetExgross'];// 毛利率
         $obj['shipCost'] = $viewData['shipCost'];// 计提发货成本
         $obj['feeCostbx'] = $viewData['feeCostbx'];// 报销支付成本
         $obj['otherCost'] = $viewData['otherCost'];// 其他成本
         // 重新获取相应的值, 与项目信息查看页面统一取值（end）

         
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

         // 如果确实要保存，则将整个数据插入表中，并且清空cache
         if($isRealSave) {
             // do save
             $this->createBatch($this->conProjectCache);

             // clean cache
             $this->conProjectCache = array();
         }
     }

     /**
      * 保存单条项目数据
      * @param $obj
      * @param $versionInfo
      */
     function saveSingleRecord_d($obj, $versionInfo) {
         // 格式化数据
         $esmDao = new model_engineering_project_esmproject();

         if(!empty($obj['contractId'])){//存在关联合同,才需获取相关实时数据
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
             $obj['schedule'] = $conObj['schedule']; //进度
             $obj['earnings'] = $conObj['earnings']; //收入
             $obj['reserveEarnings'] = $conObj['earnings']*0.02; //预留营收

             $obj['deductMoney'] =  $conObj['deductMoney']; //扣款
             $obj['badMoney'] =  $conObj['badMoney']; //坏账

             $obj['budget'] = $conObj['budget']; //预算
             $obj['cost'] = $conObj['cost']; //决算
             $obj['planBeginDate'] = $conObj['planBeginDate'];
             $obj['planEndDate'] = $conObj['planEndDate'];
             $obj['actBeginDate'] = $conObj['actBeginDate'];
             $obj['actEndDate'] = $conObj['actEndDate'];

             $obj['grossTrue'] = $conObj['earnings'] - $conObj['cost'] - $obj['costAct']; //毛利
             $obj['exgrossTrue'] = bcdiv($conObj['earnings'] - $conObj['cost'] - $obj['costAct'], $conObj['earnings'], 2) * 100; //毛利率
             $obj['module'] = $conObj['module']; //板块编码
             $obj['moduleName'] = $conObj['moduleName']; //板块
             $obj['officeId'] = $conObj['officeId']; //区域id
             $obj['officeName'] = $conObj['officeName']; //区域
         }else{
             $obj['grossTrue'] = $obj['earnings'] - $obj['cost']; //毛利
             $obj['exgrossTrue'] = bcdiv($obj['earnings'] - $obj['cost'], $obj['earnings'], 2) * 100; //毛利率
             $obj['reserveEarnings'] = $obj['earnings']*0.02; //预留营收
         }

         // 重新获取相应的值, 与项目信息查看页面统一取值（start）
         $viewData = $this->conProjectDao->getProView($obj['id']);
         $obj['rateMoney'] = $viewData['proMoneyRate'];// 税后项目金额
         $obj['schedule'] = $viewData['schedule'];// 项目进度
         $obj['objDeduct'] = $viewData['objDeduct'];// 项目扣款
         $obj['objBad'] = $viewData['objBad'];// 项目坏账
         $obj['earnings'] = $viewData['revenue'];// 项目营收
         $obj['cost'] = $viewData['finalCost'];// 项目决算
         $obj['exgrossTrue'] = $viewData['budgetExgross'];// 毛利率
         $obj['shipCost'] = $viewData['shipCost'];// 计提发货成本
         $obj['feeCostbx'] = $viewData['feeCostbx'];// 报销支付成本
         $obj['otherCost'] = $viewData['otherCost'];// 其他成本
         // 重新获取相应的值, 与项目信息查看页面统一取值（end）


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