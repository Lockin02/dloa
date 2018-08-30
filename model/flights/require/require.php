<?php
/**
 * @author Administrator
 * @Date 2013年7月11日 20:30:47
 * @version 1.0
 * @description:订票需求 Model层
 */
class model_flights_require_require extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_require";
		$this->sql_map = "flights/require/requireSql.php";
		parent :: __construct();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'cardType','tourAgency','districtType'
    );

	//类型配置
	function rtStatus_d($thisVal) {
        $returnVal = '';
        switch($thisVal){
            case '1' : $returnVal = '部门费用';break;
            case '2' : $returnVal = '工程项目费用';break;
            case '3' : $returnVal = '研发项目费用';break;
            case '4' : $returnVal = '售前费用';break;
            case '5' : $returnVal = '售后费用';break;
            case '10' : $returnVal = '联程';break;
            case '11' : $returnVal = '往返';break;
            case '12' : $returnVal = '单程';break;
        }
        return $returnVal;
	}

    /**
     * 判断特别部门信息的方法
     */
    function deptNeedInfo_d($deptId){
        $rsInfo = array();
        include (WEB_TOR."includes/config.php");
        //部门费用需要省份的部门
        $expenseNeedProvinceDept = isset($expenseNeedProvinceDept) ? $expenseNeedProvinceDept : null;
        $rsInfo['deptIsNeedProvince'] = in_array($deptId,array_keys($expenseNeedProvinceDept)) ? 1 : 0;

        //部门费用需要客户类型的部门
        $expenseNeedCustomerDept = isset($expenseNeedCustomerDept) ? $expenseNeedCustomerDept : null;
        $rsInfo['deptIsNeedCustomerType'] = in_array($deptId,array_keys($expenseNeedCustomerDept)) ? 1 : 0;

        return $rsInfo;
    }

    //重写ADD
    //$isSetExaStatus  是否需要重新设置审批状态
    function add_d($object,$isSetExaStatus = true) {
		//提出与主表无关的内容
		$items = $object['items'];
		unset ($object['items']);
		try {
			$this->start_d();
			//数据字典处理
			$object = $this->processDatadict($object);

			$codeDao = new model_common_codeRule();
			$object['requireNo'] = $codeDao->commonCode("机票需求", $this->tbl_name, "DPXQ");
			$object['ticketMsg'] = 0;

			if($isSetExaStatus){
				$object['ExaStatus'] = WAITAUDIT;
			}

			//处理一下无效数据
			if($object['cardType'] == 'JPZJLX-01'){
				$object['validDate'] = '0000-00-00';
				$object['birthDate'] = '0000-00-00';
			}

			$newId = parent :: add_d($object, true);

			//实例化从表
			$requiresuite = new model_flights_require_requiresuite();
			$items = util_arrayUtil :: setArrayFn(array ('mainId' => $newId), $items , array('airName') );
			if ($items) {
				$requiresuite->saveDelBatch($items);
			}

			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit_d
	 */
	function edit_d($object) {
		//剔除主表无关信息
		$items = $object['items'];
		unset ($object['items']);
		try {
			$this->start_d();

			//数据字典处理
			$object = $this->processDatadict($object);

			//处理一下无效数据
			if($object['cardType'] == 'JPZJLX-01'){
				$object['validDate'] = '0000-00-00';
				$object['birthDate'] = '0000-00-00';
			}

			//调用父类编辑
			parent :: edit_d($object, true);

			//实例化从表
			$requiresuite = new model_flights_require_requiresuite();
			$items = util_arrayUtil :: setArrayFn(array ('mainId' => $object['id']), $items , array('airName') );
			if ($items) {
				$requiresuite->saveDelBatch($items);
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

    //获取人员信息
	function getPersonnelInfo_d() {
		$values = new model_hr_personnel_personnel;
		return $values->find(array (
            "userAccount" => $_SESSION['USER_ID']
        ));
	}

    //获取随行人员
	function getRequiresuite_d($id) {
		$requiresuiteDao = new model_flights_require_requiresuite();
		//实例化从表
		return $requiresuiteDao->findAll(array (
            'mainId' => $id
        ));
	}

	//更新订票状态
	function updateMsgState_d($id,$ticketMsg = 1){
        try{
            $object = array('id' => $id,'ticketMsg' => $ticketMsg);
            parent::edit_d($object,true);
        }catch (Exception $e){
            throw $e;
        }
	}

	//是否需要审批
	function isUserNeedAudit_d($userId,$deptId){
        //人员信息获取
		$otherDataDao = new model_common_otherdatas();
		$userInfo = $otherDataDao->getUserAllInfo($userId,array('userLevel','MajorId','ViceManager'));
        $deptInfo = $otherDataDao->getDeptById_d($deptId);
        $myjorIdArr = explode(',',$deptInfo['MajorId']);
        $viceManagerArr = explode(',',$deptInfo['ViceManager']);
        //销售负责人判定
        $regionDao = new model_system_region_region();
        //如果用户级别是领导 或者是 销售负责人，则不需要审批
		if(($userInfo && $userInfo['UserLevel'] < 3) &&(!in_array($deptId,array(202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224)))
		    || $regionDao->isAreaPrincipal_d($userId)
            || in_array($userId,$myjorIdArr)&&(!in_array($deptId,array(202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224)))
			|| in_array($userId,$viceManagerArr)){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 异步提交审批
	 * @param $id
	 */
	function ajaxSubmit_d($id) {
		return $this->update(array('id' => $id), array('ExaStatus' => AUDITED, 'ExaDT' => day_date));
	}
}