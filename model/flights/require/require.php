<?php
/**
 * @author Administrator
 * @Date 2013��7��11�� 20:30:47
 * @version 1.0
 * @description:��Ʊ���� Model��
 */
class model_flights_require_require extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_require";
		$this->sql_map = "flights/require/requireSql.php";
		parent :: __construct();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'cardType','tourAgency','districtType'
    );

	//��������
	function rtStatus_d($thisVal) {
        $returnVal = '';
        switch($thisVal){
            case '1' : $returnVal = '���ŷ���';break;
            case '2' : $returnVal = '������Ŀ����';break;
            case '3' : $returnVal = '�з���Ŀ����';break;
            case '4' : $returnVal = '��ǰ����';break;
            case '5' : $returnVal = '�ۺ����';break;
            case '10' : $returnVal = '����';break;
            case '11' : $returnVal = '����';break;
            case '12' : $returnVal = '����';break;
        }
        return $returnVal;
	}

    /**
     * �ж��ر�����Ϣ�ķ���
     */
    function deptNeedInfo_d($deptId){
        $rsInfo = array();
        include (WEB_TOR."includes/config.php");
        //���ŷ�����Ҫʡ�ݵĲ���
        $expenseNeedProvinceDept = isset($expenseNeedProvinceDept) ? $expenseNeedProvinceDept : null;
        $rsInfo['deptIsNeedProvince'] = in_array($deptId,array_keys($expenseNeedProvinceDept)) ? 1 : 0;

        //���ŷ�����Ҫ�ͻ����͵Ĳ���
        $expenseNeedCustomerDept = isset($expenseNeedCustomerDept) ? $expenseNeedCustomerDept : null;
        $rsInfo['deptIsNeedCustomerType'] = in_array($deptId,array_keys($expenseNeedCustomerDept)) ? 1 : 0;

        return $rsInfo;
    }

    //��дADD
    //$isSetExaStatus  �Ƿ���Ҫ������������״̬
    function add_d($object,$isSetExaStatus = true) {
		//����������޹ص�����
		$items = $object['items'];
		unset ($object['items']);
		try {
			$this->start_d();
			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			$codeDao = new model_common_codeRule();
			$object['requireNo'] = $codeDao->commonCode("��Ʊ����", $this->tbl_name, "DPXQ");
			$object['ticketMsg'] = 0;

			if($isSetExaStatus){
				$object['ExaStatus'] = WAITAUDIT;
			}

			//����һ����Ч����
			if($object['cardType'] == 'JPZJLX-01'){
				$object['validDate'] = '0000-00-00';
				$object['birthDate'] = '0000-00-00';
			}

			$newId = parent :: add_d($object, true);

			//ʵ�����ӱ�
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
	 * ��дedit_d
	 */
	function edit_d($object) {
		//�޳������޹���Ϣ
		$items = $object['items'];
		unset ($object['items']);
		try {
			$this->start_d();

			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			//����һ����Ч����
			if($object['cardType'] == 'JPZJLX-01'){
				$object['validDate'] = '0000-00-00';
				$object['birthDate'] = '0000-00-00';
			}

			//���ø���༭
			parent :: edit_d($object, true);

			//ʵ�����ӱ�
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

    //��ȡ��Ա��Ϣ
	function getPersonnelInfo_d() {
		$values = new model_hr_personnel_personnel;
		return $values->find(array (
            "userAccount" => $_SESSION['USER_ID']
        ));
	}

    //��ȡ������Ա
	function getRequiresuite_d($id) {
		$requiresuiteDao = new model_flights_require_requiresuite();
		//ʵ�����ӱ�
		return $requiresuiteDao->findAll(array (
            'mainId' => $id
        ));
	}

	//���¶�Ʊ״̬
	function updateMsgState_d($id,$ticketMsg = 1){
        try{
            $object = array('id' => $id,'ticketMsg' => $ticketMsg);
            parent::edit_d($object,true);
        }catch (Exception $e){
            throw $e;
        }
	}

	//�Ƿ���Ҫ����
	function isUserNeedAudit_d($userId,$deptId){
        //��Ա��Ϣ��ȡ
		$otherDataDao = new model_common_otherdatas();
		$userInfo = $otherDataDao->getUserAllInfo($userId,array('userLevel','MajorId','ViceManager'));
        $deptInfo = $otherDataDao->getDeptById_d($deptId);
        $myjorIdArr = explode(',',$deptInfo['MajorId']);
        $viceManagerArr = explode(',',$deptInfo['ViceManager']);
        //���۸������ж�
        $regionDao = new model_system_region_region();
        //����û��������쵼 ������ ���۸����ˣ�����Ҫ����
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
	 * �첽�ύ����
	 * @param $id
	 */
	function ajaxSubmit_d($id) {
		return $this->update(array('id' => $id), array('ExaStatus' => AUDITED, 'ExaDT' => day_date));
	}
}