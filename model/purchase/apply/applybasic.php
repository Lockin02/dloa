<?php
/**
 * �ɹ��ƻ����뵥������Ϣmodel
 */

class model_purchase_apply_applybasic extends model_base{

	public static $pageArr=array();

	//״̬λ
	private $state;

	function __construct() {
		$this->tbl_name = "oa_purch_apply_basic";
		$this->sql_map = "purchase/apply/applybasicSql.php";
		parent :: __construct();
		$this->state = array(
			0 => array(
				"stateEName" => "save",
				"stateCName" => "����",
				"stateVal" => "0"
			),
			1 => array(
				"stateEName" => "approval",
				"stateCName" => "������",
				"stateVal" => "1"
			),
			2 => array(
				"stateEName" => "Locking",
				"stateCName" => "����",
				"stateVal" => "2"
			),
			3 => array(
				"stateEName" => "fightback",
				"stateCName" => "���",
				"stateVal" => "3"
			),
			7 => array(
				"stateEName" => "wite",
				"stateCName" => "��ִ��",
				"stateVal" => "7"
			),
			4 => array(
				"stateEName" => "execute",
				"stateCName" => "ִ����",
				"stateVal" => "4"
			),
			5 => array(
				"stateEName" => "end",
				"stateCName" => "���",
				"stateVal" => "5"
			),
			6 => array(
				"stateEName" => "close",
				"stateCName" => "�ر�",
				"stateVal" => "6"
			)
		);
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ͨ��value����״̬
	 */
	function stateToVal($stateVal){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateVal']== $stateVal ){
				$returnVal = $val['stateCName'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * ͨ��״̬����value
	 */
	function stateToSta($stateSta){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateEName']== $stateSta ){
				$returnVal = $val['stateVal'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ���ݲɹ����뵥id��ȡ �ɹ����뵥��Ϣ������Ʒ�嵥��Ϣ
	 */
	function getApplyInfoGrouByPro(){

	}

	/**
	 * ��д�����ɹ����뵥
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			$newId = parent::add_d ( $object,true );

			$taskDao = new model_purchase_task_equipment();
			foreach ($object["equment"] as $key => $val){
				$taskDao->updateAmountIssued($object["equment"][$key]["taskEquNumb"],$object["equment"][$key]["amountAll"]);

				//�����м��oa_purch_task_apply
				$this->query("delete from oa_purch_task_apply where applyNumb='".$object['applyNumb']."' and applyId='".$newId."' and taskNumb='".$val['taskNumb']."' and taskId='".$val['taskId']."' ");
				$insertCenter = $this->query(" insert into oa_purch_task_apply(taskNumb,taskId,applyId,applyNumb) values('".$val['taskNumb']."','".$val['taskId']."','".$newId."','".$object['applyNumb']."') ");

				$object["equment"][$key]["basicId"] =  $newId;
				$object["equment"][$key]["basicNumb"] =  $object["applyNumb"];
				$object["equment"][$key]["status"] =  "1";
				if($object["equment"][$key]['amountAll']=='0'){
					$object["equment"][$key] = null;
				}
			}
			$equipmentDao = new model_purchase_apply_applyequipment();
			$equipmentDao->createall($object["equment"]);
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ɹ����뵥-�豸�б�
	 */
	function pageList_d(){
		//$this->echoSelect();
		$rows = $this->pageBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_apply_applyequipment();
			$planDao = new model_purchase_plan_basic();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
//					"basicId" => $val['id'],
//					"deviceIsUse" => "1"
					"basicNumb" => $val['applyNumb'],
//					"deviceIsUse" => "1",
					//"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.id");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equ_list");

				foreach ($chiRows as $chdKey => $chdVal){
					//���زɹ���������
					$chiRows[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );

					if( !isset( $chdVal["amountAll"]) ||$chdVal["amountAll"]==""){
						$chiRows[$chdKey]["amountAll"] =  0;
					}

					if( !isset( $chdVal["amountIssued"]) ||$chdVal["amountIssued"]==""){
						$chiRows[$chdKey]["amountNotIssued"] =  $chiRows[$chdKey]["amountAll"];
					}else{
						$chiRows[$chdKey]["amountNotIssued"] =  $chiRows[$chdKey]["amountAll"] -  $chdVal["amountIssued"];
					}
				}
				$rows[$i]['stateCName'] = $this->stateToVal( $rows[$i]['state'] );

				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * δ�����б�
	 */
	function pageApprovalWait_d(){
		$searchArr = $this->__GET('searchArr');
		$searchArr["wfCode"] = $this->tbl_name;
		$searchArr["wfFlag"] = '0';
		$searchArr["wfTake"] = "1";
		$searchArr["wfUser"] = $_SESSION['USER_ID'];
		$searchArr["wfStatus"] = "ok";
		$searchArr["wfExamines"] = "1";
		$searchArr["wfEnter_user"] = "1";
		$searchArr["wfName"] = "�ɹ����뵥����";
		$this->__SET('searchArr', $searchArr);
		$this->__SET('groupBy', "w.task");
		$rows = $this->pageBySqlId("list_Approval");
		$i = 0;
		if($rows){
//			$equipment = new model_purchase_apply_applyequipment();
//			foreach($rows as $key => $val){
//				$equipment->resetParam();
//				$searchArr = array (
//					"stockApplyId" => $val['id']
//				);
//				$equipment->__SET('groupBy', "p.id");
//				$equipment->__SET('sort', "p.id");
//				$equipment->__SET('searchArr', $searchArr);
//				$chiRows = $equipment->listBySqlId("equipment_list");
//				$rows[$i]['childArr']=$chiRows;
//				++$i;
//			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * �������б�
	 */
	function pageApprovalAlready_d(){
		$searchArr = $this->__GET('searchArr');
		$searchArr["wfCode"] = $this->tbl_name;
		$searchArr["wfFlag"] = '1';
		$searchArr["wfUser"] = $_SESSION['USER_ID'];
		$searchArr["wfName"] = "�ɹ����뵥����";
		$searchArr["wfTake"] = "1";
		$searchArr["wfEnter_user"] = "1";
		$this->__SET('searchArr', $searchArr);
		$this->__SET('groupBy', "w.task");
		$rows = $this->pageBySqlId("list_Approval");
		$i = 0;
		if($rows){
//			$equipment = new model_purchase_apply_applyequipment();
//			foreach($rows as $key => $val){
//				$equipment->resetParam();
//				$searchArr = array (
//					"stockApplyId" => $val['id']
//				);
//				$equipment->__SET('groupBy', "p.id");
//				$equipment->__SET('sort', "p.id");
//				$equipment->__SET('searchArr', $searchArr);
//				$chiRows = $equipment->listBySqlId("equipment_list");
//				$rows[$i]['childArr']=$chiRows;
//				++$i;
//			}
			return $rows;
		}
		else {
			return false;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �鿴�豸�б�
	 */
	function readApply_d($id){
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_apply_applyequipment();
			$planDao = new model_purchase_plan_basic();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					//"basicId" => $val['id']
					"basicNumb" => $val['applyNumb'],
//					"deviceIsUse" => "1",
					//"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.productId");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equipment_list");
				foreach ($chiRows as $chdKey => $chdVal){
					//���زɹ���������
					$chiRows[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );
				}
				$rows[$i]['stateCName'] = $this->stateToVal( $rows[$i]['state'] );
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �޸��б�����
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����10:32:04
	 */
	function listEditApply_d($id){
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			$equipment = new model_purchase_apply_applyequipment();
			$planDao = new model_purchase_plan_basic();
			$searchChildArr = array(
				"basicNumb" => $rows['0']['applyNumb'],
//				"deviceIsUse" => '1',
				"statusArr" => $equipment->statusToSta('execution').",".$equipment->statusToSta('locking')
			);
			$equipment->__SET('searchArr', $searchChildArr);
			$childArr = $equipment->listBySqlId("equipment_list");
			foreach ($childArr as $chdKey => $chdVal){
				//���زɹ���������
				$childArr[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );
			}
			$rows['0']['childArr'] = $childArr;
			return $rows;
		}
		else {
			return false;
		}

	}

	/**
	 * @exclude �޸ı���
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 ����08:02:52
	 */
	function editSave_d($object,$oId){
//		echo "<pre>".$oId;
//		print_r($object);
		try {
			$this->start_d ();
			$taskEquDao = new model_purchase_task_equipment();
			$equipmentDao = new model_purchase_apply_applyequipment();
			$searchArr = array (
					"basicId" => $oId
				);
			$equipmentDao->delete($searchArr);
			$this->deletes_d($oId);

			$newId = parent::add_d ( $object,true );
			foreach ($object["equment"] as $key => $value){
				$taskEquDao->updateAmountIssued($value['taskEquNumb'],$value['amountAll'],$value['amountOld']);
				$object["equment"][$key]["basicId"] =  $newId;
				$object["equment"][$key]["basicNumb"] =  $object["applyNumb"];
			}
//			$this->query(" update oa_purch_apply_basic set state='5',ExaStatus='�ر�' where id='$oId' ");
			$equipmentDao->createall($object["equment"]);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			print_r($e);
			$this->rollBack ();
			return null;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �������
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����12:16:16
	 */
	function changeSave_d ($object,$oId,$productIds) {
//		echo "<pre>".$oId;
//		print_r($object);
		try {
			$this->start_d ();
			$changeDao = new model_purchase_apply_applychange();
			$taskEquDao = new model_purchase_task_equipment();
			$equipmentDao = new model_purchase_apply_applyequipment();

			//ԭ���뵥״̬��Ϊ����
			$updateArr = array (
				"id" => $oId,
				"state" => $this->stateToSta('Locking')
			);
			$this->updateById($updateArr);

			//����µĲɹ����뵥
			$object['applyVersionNumb'] = $object['applyVersionNumb']+1;
			$newId = parent::add_d ( $object );
			foreach ($object["equment"] as $key => $value){
				$object["equment"][$key]["basicId"] =  $newId;
				$object["equment"][$key]["basicNumb"] =  $object["applyNumb"];
				$object["equment"][$key]["basicVersionNumb"] = $object["equment"][$key]["basicVersionNumb"]+1;
			}
//			echo "<pre>";
//			print_r($object["equment"]);
			$equipmentDao->createall($object["equment"]);

			//��ӱ��������
			$object['change']['idNew'] = $newId;
			$id = $changeDao->add_d( $object['change'],true );
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			print_r($e);
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * @exclude ��ʾ����б�����
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����10:31:41
	 */
	function listChangeApply_d ( $id,$productIds ) {
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			$equipment = new model_purchase_apply_applyequipment();
			$planDao = new model_purchase_plan_basic();
			$searchChildArr = array(
				"basicNumb" => $rows['0']['applyNumb'],
//				"deviceIsUse" => '1',
				//"ids" => $productIds,
				"statusArr" => $equipment->statusToSta('execution').",".$equipment->statusToSta('locking')
			);
			$equipment->__SET('searchArr', $searchChildArr);
			$childArr = $equipment->listBySqlId("equipment_list");
			foreach ($childArr as $chdKey => $chdVal){
				//���زɹ���������
				$childArr[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );
			}
			$rows['0']['childArr'] = $childArr;
//			echo "<pre>";
//			print_r($rows);
			return $rows;
		}
		else {
			return false;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude ͨ��Id�鿴����
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����07:31:59
	 */
	function arrExa_d($id){
        $code = $this->__GET("tbl_name");
        //��������
//		$code = "equipment_borrow";
//		$id = "12";
        $sql="select  " .
        		"f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task " .
        	"from " .
        		"flow_step f , wf_task w  " .
        	"where  " .
        		"w.code='$code' and " .
        		"w.Pid='$id' and " .
        		"f.Wf_task_ID=w.task ";
		$arr = $this->findSql($sql);
		if($arr){
			foreach($arr as $key => $val){
				$childSql="select " .
						" p.Content, p.Result, p.Endtime ,p.ID ,p.Flag , u.USER_NAME as User  " .
					"from " .
						"flow_step_partent p left join user u on (p.User=u.USER_ID)" .
					"where " .
						"p.Wf_task_ID='".$val["task"]."' and " .
						"p.SmallID='".$val["SmallID"]."' ";
				$arr[$key]["childArr"] = $this->findSql($childSql);
				$strExp =  substr( $arr[$key]["User"] ,0,-1);
				if( strstr($strExp,"," ) ){
					$strExp = str_replace("," , "','" , $strExp);
				}
				$sqlUser = " select USER_NAME from user where USER_ID in ('".  $strExp  ."') ";
				$arrUser = $this->findSql($sqlUser);
				$userNames = "";
				foreach($arrUser as $userKey =>$userVal){
					$userNames .= $userVal["USER_NAME"].",";
				}
				$arr[$key]["User"] = $userNames;
			}
		}else{
			$arr=false;
		}
		return $arr;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �رղɹ����뵥
	 * @author ouyang
	 * @param $maintain �Ƿ�ά���ϼ��´�����
	 * @return
	 * @version 2010-8-10 ����04:30:12
	 */
	function close_d($numb,$maintain=true){
		//$numb = $this->findNumbById_d($id);
		if($maintain){
			$equDao = new model_purchase_apply_applyequipment();
			$equDao->close_d($numb);
		}
		$searchArr = array (
					"applyNumb" => $numb,
//					"isUse" => "1",
					"stateArr" => $this->stateToSta('save').",".
									$this->stateToSta('approval').",".
									$this->stateToSta('Locking').",".
									$this->stateToSta('fightback').",".
									$this->stateToSta('wite')
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
//		echo "<pre>";
//		print_r($rows);
		if( $rows && isset($rows['0']['applyNumb']) ){
			$condition = array(
					"applyNumb" => $rows['0']['applyNumb'],
//					"isUse" => "1"
				);
			$obj = array(
					"state" => $this->stateToSta('close'),
					"ExaStatus" => "�ر�",
					"dateFact" => date("Y-m-d")
				);
			parent::update ( $condition, $obj );
		}
		return true;

	}

	/**
	 * @exclude ��ɲɹ����뵥����������
	 * @author ouyang
	 * @param �ɹ����뵥Id
	 * @return 0:�쳣��1:���,2���������
	 * @version 2010-8-10 ����04:26:05
	 */
	function end_d ($id) {
		$numb = $this->findNumbById_d($id);
		$equDao = new model_purchase_apply_applyequipment();
		if( $equDao->endByBasicId_d($numb) ){
			$obj = array(
					"id" => $id,
					"state" => $this->stateToSta('end'),
					"dateFact" => date("Y-m-d")
			);
			if( parent::updateById($obj) ){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 2;
		}
	}

	/**
	 * @exclude ִ�вɹ����뵥
	 * @author ouyang
	 * @param �ɹ����뵥Id
	 * @return
	 * @version 2010-8-10 ����04:59:14
	 */
	function execute_d($id){
		$updateArr = array(
					"id" => $id,
					"state" => $this->stateToSta('execute'),
					"ExaStatus" => "ִ����"
				);
		$equDao = new model_purchase_apply_applyequipment();
		$equDao ->funByWayAmountAll($id);

		return $this->updateById($updateArr);
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude ͨ�����뵥Id�ұ��
	 * @author ouyang
	 * @param $id ���뵥Id
	 * @return ���뵥���
	 * @version 2010-8-10 ����07:06:51
	 */
	function findNumbById_d ($id) {
		$searchArr = array (
					"id" => $id
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			return $rows['0']['applyNumb'];
		}else{
			return false;
		}
	}

	/**
	 * @exclude ͨ���ɹ����뵥��Ų�ѯ״̬
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 ����11:46:07
	 */
	function getStateByNumb ($applyNumb) {
		$searchArr = array (
					"applyNumb" => $applyNumb,
//					"isUse" => "1"
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			return $rows['0']['state'];
		}else{
			return false;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @desription ɾ���ɹ����뵥
	 * @param tags
	 * @author ouyang
	 * @date 2010-9-13 ����09:34:27
	 * @version V1.0
	 */
	function del_d ($id) {
		try {
			$this->start_d ();
			$equDao = new model_purchase_apply_applyequipment();
			$equDao->del_d($id);
			$this->deletes($id);
			$this->commit_d ();
			return 1;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return null;
		}
	}

/*****************************************��ʾ�ָ���********************************************/


	/**
	 * @exclude �رղɹ����뵥
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:32:12
	 */
	function closeApplyById_d ($id) {
		try {
			$this->start_d ();
			$object = array(
				"id" => $id,
//				"isUse" => "0",
				"state" => $this->stateToSta('close')
			);
			$this->updateById($object);
			$equDao = new model_purchase_apply_applyequipment();
			$childSeach = array(
				"basicId" => $id
			);
			$childArr = array(
//				"deviceIsUse" => "0",
				"status" => $equDao->statusToSta('close')
			);
			$equDao->update($childSeach,$childArr);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return null;
		}
	}

	/**
	 * @exclude �����ɹ����뵥
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:32:31
	 */
	function executeApplyById_d ($id) {
		try {
			$this->start_d ();
			$object = array(
				"id" => $id,
//				"isUse" => "1",
				"state" => $this->stateToSta('wite')
			);
			$this->updateById($object);
			$equDao = new model_purchase_apply_applyequipment();
			$childSeach = array(
				"basicId" => $id
			);
			$childArr = array(
//				"deviceIsUse" => "1",
				"status" => $equDao->statusToSta('execution')
			);
			$equDao->update($childSeach,$childArr);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return null;
		}
	}

	/*
	 * ��ȡ���ж���������������
	 */
	 function getAmountAll($productId){
	 	$this->searchArr['productId'] = $productId;
	 	$this->sort='';
	 	$nowYear = date("Y");
	 	$this->searchArr['nearYear'] = $nowYear;
		$amountAll = $this->listBySqlId('select_amount');
		return $amountAll;
	 }

}
?>
