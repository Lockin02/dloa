<?php
/**
 * @author Administrator
 * @Date 2012-08-09 13:56:00
 * @version 1.0
 * @description:��ְ�����嵥���Ʋ�
 */
class controller_hr_leave_handover extends controller_base_action {

	function __construct() {
		$this->objName = "handover";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ�����嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * ��ְ�����嵥
     */
	function c_handoverlist(){
		$leaveId = $_GET['leaveId'];
		//�ж��Ƿ������̸��¼
		$sql = "select id  from oa_leave_handover where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$leaveDao = new model_hr_leave_leave();
		if(empty($flagArr[0]['id'])){
			//��ȡ����
			$obj = $leaveDao->get_d ( $_GET['leaveId'] );
			//��ȡ��ְԭ���������
			$obj['quitReson'] = str_replace('^nvsp',"��",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nbsp',"�� ",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nqsp',"�� ",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nqsd',"�� ",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nbss',"��",$obj['quitReson']);
			$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
			$obj['companyName'] = $info['companyName'];
			$obj['companyId'] = $info['companyId'];
			$obj['userNo'] = $info['userNo'];
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->assign("leaveId",$_GET['leaveId']);
			//������Ⱦ��ְ�����嵥��ϸ
			$handitemDao=new model_hr_leave_handitem();

			$handitemDao->searchArr=array("mainId"=>$_GET['leaveId']);
			$handitemDao->asc=false;
			$handitemArr=$handitemDao->listBySqlId();

			$fromworkInfo=array();
			foreach($handitemArr as $key =>$handitem){
				$tempObj=array("items"=>$handitem['handContent'],
					"recipientName"=>$handitem['recipientName'],
					"recipientId"=>$handitem['recipientId']);
				array_push($fromworkInfo,$tempObj);
			}
			$fromworkList = $this->service->fromworkInfo_d($fromworkInfo);
			$this->assign('fromworkList',$fromworkList);

			$this->view ('add' ,true);
		} else {
			$obj = $this->service->get_d ( $flagArr[0]['id'] );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			//��ȡ��ϵ��ʽ
			$obj = $leaveDao->get_d ( $_GET['leaveId'] );
			$this->assign ( 'mobile', $obj['mobile'] );
			$this->assign ( 'personEmail', $obj['personEmail'] );
			//������Ⱦ��ְ�����嵥��ϸ
			$Dao = new model_hr_leave_handoverlist();
			$Dao->asc=false;
			$Dao->sort='sort'; // ѡ��Ҫ������ֶ�
			$Dao->searchArr['handoverId'] = $flagArr[0]['id'];
			$fromworkInfo = $Dao->list_d ("select_default");
			$fromworkList = $this->service->fromworkInfo_print($fromworkInfo);
			$this->assign('fromworkList',$fromworkList);
			$this->view ('edit' ,true);
		}
	}

	/**
	 * ��ת��������ְ�����嵥ҳ��
	 */
	function c_toAdd() {
		$leaveDao = new model_hr_leave_leave();
		$obj = $leaveDao->get_d ( $_GET['leaveId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array ( 'quitTypeCode' => 'HRLZLX' ), $obj['quitTypeCode']);
		$this->assign("leaveId",$_GET['leaveId']);
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭��ְ�����嵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴��ְ�����嵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������Ⱦ��ְ�����嵥��ϸ
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $_GET['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴��ְ�����嵥ҳ��(ͨ����ְ���뵥)
	 */
	function c_toViewByApply() {
		$leaveId = $_GET['leaveId'];
		//�ж��Ƿ������̸��¼
		$sql = "select id  from oa_leave_handover where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$obj = $this->service->get_d (  $flagArr[0]['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������Ⱦ��ְ�����嵥��ϸ
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $flagArr[0]['id'] ;
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		$this->view ( 'view' );
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ְ�����嵥����ɹ�����ע����٣�';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * ��ְ�����嵥ȷ���б�
	 */
	function c_handoverAffirm(){
		$this->assign("userId" , $_SESSION['USER_ID']);
		$this->view('handoveraffirm');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_handoverAffirmJson() {
		$affirmUserId = $_GET['affirmUserId'];

		$service = $this->service;

		$ids = $service->affirmUserInfo($affirmUserId);

		$service->getParam ( $_REQUEST );
		$service->searchArr['ids'] = $ids;
		$rows = $service->page_d ("select_leave");
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$isDone[$key]=$rows[$key]['isDone'];
			}
			array_multisort($isDone,SORT_DESC,$rows);
		}
//		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ְ�����嵥ȷ��ҳ��
	 */
	function c_affirmList(){
		$obj = $this->service->get_d ($_GET['handoverId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('userId' ,$_SESSION['USER_ID']);
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $obj['leaveId'] );
		$this->assign('mobile' ,$leaveRow['mobile']);
		$this->assign('personEmail' ,$leaveRow['personEmail']);
		$this->assign('salaryEndDate' ,$leaveRow['salaryEndDate']);
		$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
		$this->assign('regionName' ,$this->service->get_table_fields('oa_hr_personnel',"userNo='$obj[userNo]'",'regionName'));
		if($sysLimit = $this->service->this_limit['�鿴��ְ����'] == 1) {
			$quitTypeNameHtm =<<<EOT
			<tr>
				<td class="form_text_left_three_new">��ְ����</td>
				<td class="form_text_right">
					{$obj['quitTypeName']}
				</td>
			</tr>
EOT;
			$this->assign('quitTypeNameHtm',$quitTypeNameHtm);
		} else {
			$this->assign('quitTypeNameHtm',"");
		}
		$this->view ('affirmList');
	}

	/**
	 * ��ְ�����嵥�鿴ҳ��
	 */
	function c_toViewAffirmList(){
		$obj = $this->service->get_d ($_GET['handoverId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('userId',$_SESSION['USER_ID']);
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $obj['leaveId'] );
		$this->assign('mobile',$leaveRow['mobile']);
		$this->assign('personEmail',$leaveRow['personEmail']);
		$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
		$this->assign('regionName',$this->service->get_table_fields('oa_hr_personnel','userNo='.$obj['userNo'],'regionName'));
		$this->view ('viewAffirmList');
	}

	/**
	 * ��ְ�嵥ȷ�ϲ���
	 */
	function c_affirmCon(){
		if ($this->service->affirmCon_d ( $_POST [$this->objName] )) {
			msg ( 'ȷ�ϳɹ���' );
		}
	}

	/**
	 * ajax��֤��ְ���ӵ��Ƿ�ȫ��ȷ�����
	 */
	function c_getLeaveInfo(){
		$handoverId = $_POST['handoverId'];
		$falg = $this->service->getLeaveInfo_d($handoverId);
		echo $falg;
	}

	/**
	 * ajax��֤��ְ���ӵ��Ƿ�ȫ��ȷ�����(����ȷ����)
	 */
	function c_isDone(){
		$handoverId = $_POST['handoverId'];
		$falg = $this->service->isDone_d($handoverId);
		echo $falg;
	}

	/**
	 * �����嵥ȷ����ɺ󣬷���ȷ�ϵ��ݲ���
	 */
	function c_startAffirm(){
		$rows = $_POST [$this->objName];
		if (!empty($rows['handoverId'])) {
			succ_show('controller/hr/leave/ewf_handover.php?actTo=ewfSelect&billId=' . $rows['handoverId'].'&billDept='.$rows['deptId']);
		}
	}

	/**
	 * Ա������ȷ�Ͻ����嵥
	 */
	function c_startAffirmPro(){
		$rows = $_POST [$this->objName];

		if ($this->service->startAffirmPro_d ( $_POST [$this->objName] )) {
			msg ( 'ȷ�ϳɹ���' );
		}
	}

	/**
	 * �嵥ȷ�ϲ鿴ҳ
	 */
	function c_toHandoverList(){
		$otherdatasDao=new model_common_otherdatas();
		$flag=$otherdatasDao->isFirstStep($_GET['id'],$this->service->tbl_name);
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������Ⱦ��ְ�����嵥��ϸ
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $_GET['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		if($flag){
			$this->view ('approval-edit');
		}else{
			$this->view ('view');
		}
	}

	/**
	 * �嵥ȷ�ϲ鿴ҳ
	 */
	function c_editHandover(){
		$leaveId = $_GET['leaveId'];
		//�ж��Ƿ������̸��¼
		$sql = "select id  from oa_leave_handover where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$obj = $this->service->get_d ( $flagArr[0]['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������Ⱦ��ְ�����嵥��ϸ
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $flagArr[0]['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		echo "<pre>";
		print_r($fromworkInfo);
		$fromworkList = $this->service->editHandoverList_d($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);

		$this->view ('audit-edit');
	}

	/**
	 * Ա������ȷ����ְ�����嵥
	 */
	function c_handoverProlist(){
		$obj = $this->service->getnfo_d ( $_GET['leaveId'] );
		//��ȡ��ְԭ���������
		$obj['quitReson'] = str_replace('^nvsp',"��",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nbsp',"�� ",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nqsp',"�� ",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nqsd',"�� ",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nbss',"�� ",$obj['quitReson']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������Ⱦ��ְ�����嵥��ϸ
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $obj['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		$this->assign('staffAffDT',date("Y-m-d"));
		$this->view ('handoverProlist');
	}

	/**
	 * ��ְ�����嵥���������ʼ�
	 */
	function c_dealApproval(){
		$rows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['Result']=="ok"&&is_array($rows)){
				$this->service->edit_d ( $rows);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ��ת����ְ�����嵥����ҳ��
	 */
	function c_toRestart() {
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $_GET['leaveId'] );
		//��ȡ��ְԭ���������
		$leaveRow['quitReson'] = str_replace('^nvsp',"��<br>",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbsp',"�� ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsp',"�� ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsd',"�� ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbss',"��<br> ",$leaveRow['quitReson']);
		//var_dump($leaveRow);exit();
		foreach ( $leaveRow as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$obj = $this->service->find ( array("leaveId" => $_GET['leaveId']) );
		$this->assign ( 'handoverId', $obj['id'] );

		$this->view ('restart');
	}

	/**
	 * ��ת���޸Ľ�����ҳ��
	 */
	function c_toAlterHand() {
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $_GET['leaveId'] );
		//��ȡ��ְԭ���������
		$leaveRow['quitReson'] = str_replace('^nvsp',"��<br>",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbsp',"�� ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsp',"�� ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsd',"�� ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbss',"��<br> ",$leaveRow['quitReson']);
		foreach ( $leaveRow as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$obj = $this->service->find ( array("leaveId" => $_GET['leaveId']) );
		$this->assign ( 'handoverId', $obj['id'] );

		$this->view ('alterHand');
	}

	/**
	 * ������ӡ��ְ�����嵥
	 */
	function c_printOrderAll(){
		//id��
		$ids = null;
		if(isset($_POST['leave'])) {
			$leaveDatas = $_POST['leave'];
			$ids = explode(',',$leaveDatas['ids']);
			if($leaveDatas['ids'] && $leaveDatas['idchecked']) {
				$idArr = array_merge($ids,$leaveDatas['idchecked']);
			} else if($leaveDatas['idchecked']) {
				$idArr = $leaveDatas['idchecked'];
			} else if($leaveDatas['ids']) {
				$idArr = $ids;
			}
		}
		$ids = implode(',',$idArr);
		$this->assign('allNumH',count($idArr));
		$this->view('printOrderHead');
		foreach($idArr as $key => $val) {
			//�ж��Ƿ������̸��¼
			$sql = "select id  from oa_leave_handover where leaveId=".$val."";
			$flagArr = $this->service->_db->getArray($sql);
			$leaveDao = new model_hr_leave_leave();
			$obj = $this->service->get_d ( $flagArr[0]['id'] );
			foreach ( $obj as $k => $v ) {
				$this->assign ( $k, $v );
			}
			//��ȡ��ϵ��ʽ
			$obj = $leaveDao->get_d ( $val );
			$this->assign ( 'mobile', $obj['mobile'] );
			$this->assign ( 'personEmail', $obj['personEmail'] );
			//������Ⱦ��ְ�����嵥��ϸ
			$Dao = new model_hr_leave_handoverlist();
			$Dao->asc=false;
			$Dao->sort='sort'; // ѡ��Ҫ������ֶ�
			$Dao->searchArr['handoverId'] = $flagArr[0]['id'];
			$fromworkInfo = $Dao->list_d ("select_default");
			$fromworkList = $this->service->fromworkInfo_print($fromworkInfo);
			$this->assign('fromworkList',$fromworkList);
			$this->assign('id',$val);
			$this->view('printOrderBody');
		}
		$this->assign('allNum',count($idArr));
		$this->assign('ids',$ids);
		$this->view('printOrderTail');
	}
 }
?>