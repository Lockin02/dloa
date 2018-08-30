<?php
/**
 * @description: ��Ŀ��ͨ�ƻ�action
 * @date 2010-9-18 ����11:37:03
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_plan_rdplan extends controller_base_action {

	/**
	 * @desription ���캯��
	 * @date 2010-9-11 ����12:51:57
	 */
	function __construct() {
		$this->objName = "rdplan";
		$this->objPath = "rdproject_plan";
		$this->operArr = array ("planName"=> "�ƻ�����" ,"planBeginDate" => "�ƻ���ʼʱ��", "planEndDate" => "�ƻ�����ʱ��","appraiseWorkload" => "�ƻ�������" ); //ͳһע�����ֶΣ������ͬ�����в�ͬ�ļ���ֶΣ��ڸ��Է���������Ĵ�����
		parent::__construct ();
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription �ҵĽ��ȼƻ�
	 * @date 2010-9-25 ����09:53:19
	 */
	function c_rpPageMy() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list-my' );
	}

	/**
	 * ��Ŀ����-���ȼƻ�
	 */
	function  c_rpListAll(){
		$this->show->display( $this->objPath . '_' . $this->objName . '-list-all' ) ;
	}

	/**
	 * @desription ��Ӽƻ���תACTION
	 * @param tags
	 * @date 2010-9-25 ����05:09:06
	 */
	function c_toAdd() {
//		$this->service->filterFunc('����');
		$pnId = isset ( $_GET ['pnId'] ) ? $_GET ['pnId'] : "-1";
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : "" ;
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'myPlan';
		if($type == 'myPlan'){
			$this->show->assign('loca','add');
		}else{
			$this->show->assign('loca','addInAll');
		}
		$arr = $this->service->rpGetProjectInfo ( $pjId ,$pnId);
		$this->arrToShow ( $arr );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription ��Ӽƻ�-�ҵĽ��ȼƻ�
	 * @param tags
	 * @date 2010-9-25 ����08:28:41
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], 'true' );
		if ($id) {
			msg ( '��ӳɹ���','debug');
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "��Ӽƻ���" .  $_POST [$this->objName]['planName'] . "��";//��������
			$this->behindMethod ( $objArr );
		}else{
			msg ( '���ʧ�ܣ�');
		}
	}

	/**
	 * ��Ӽƻ�-��Ŀ����-���ȼƻ�
	 */
	function c_addInAll(){
		$id = $this->service->add_d ( $_POST [$this->objName],  'true'  );
		if ($id) {
			msg ( '��ӳɹ���' );
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "��Ӽƻ���" .  $_POST [$this->objName]['planName'] . "��";//��������
			$this->behindMethod ( $objArr );
		}else{
			msg ( '���ʧ�ܣ�');
		}
	}

	/**
	 * ��Ӽƻ�-- ͨ��ģ�嵼��
	 */
	function c_toImport(){
		if(empty($_GET['planId'])){
			$_GET['parentId'] = -1;
			$_GET['parentName'] = '��̱��ƻ�';
		}else{
			$_GET['parentId'] = $_GET['planId'];
			$_GET['parentName'] = $_GET['planName'];
		}
		foreach($_GET as $key=> $val){
			$this->show->assign($key ,$val);
		}
		$this->show->display($this->objPath . '_' . $this->objName . '-addByImport' );
	}

	/**
	 * ��Ӽƻ�-ͨ��ģ�嵼��
	 */
	function c_addByImport(){
		$id = $this->service->addByImport( $_POST[$this->objName] ) ;
		if($id){
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "����ƻ���" .  $_POST [$this->objName]['planName'] . "��";//��������
			$this->behindMethod ( $objArr );
			msg('����ɹ�');
		}else{
			msg ( '����ʧ�ܣ�');
		}
	}

	/**
	 * @desription ά���ƻ�
	 * @param tags
	 * @date 2010-9-26 ����10:04:52
	 */
	function c_rpMaintenanceRead () {
		$pnId = isset( $_GET['pnId'] )?$_GET['pnId']:exit;
		$arr = $this->service->rpArrById_d($pnId);
		$this->arrToShow($arr);
		$this->show->assign ( 'pnId', "&pjId=".$pnId );
		$this->show->display($this->objPath . '_' . $this->objName . '-Maintenance-Read');
	}

	/**
	 * �鿴�ƻ�
	 */
	function  c_view(){
		$pnId = isset ( $_GET ['pnId'] ) ? $_GET ['pnId'] : "-1";
		$rows = $this->service->getPlanInfoByEdit($_GET ['pnId']);
		//Ȩ�޿���
//		$rows = $this->filterField('�ֶ�����',$rows);
		foreach($rows as $key => $val){
			$this->show->assign($key,$val);
		}
		$this->show->assign ( 'pnId', $pnId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

	/**
	 * �༭�ƻ�
	 */
	function  c_toEdit(){
		$pnId = isset ( $_GET ['pnId'] ) ? $_GET ['pnId'] : "-1";
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'myPlan';
		if($type == 'myPlan'){
			$this->show->assign('loca','edit');
		}else{
			$this->show->assign('loca','editInAll');
		}
		$rows = $this->service->getPlanInfoByEdit ($pnId);
		$rows['file']=$this->service->getFilesByObjId($pnId);
		foreach($rows as $key => $val){
			$this->show->assign($key,$val);
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * ��д c_edit - �ҵĽ��ȼƻ��ı༭
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$this->beforeMethod( $object );//�������߱��ǰ��ȡ�޸�ǰҵ�������Ϣ
		if ($this->service->edit_d ( $object )) {
			$object ['operType_'] = "�޸���ϡ�" . $object ['planName'] . "��";//��������
			$this->behindMethod ($object);//��һ������ҵ����󣬵ڶ�����������Ĭ��Ϊ��
			msg ( '�༭�ɹ���');
		}else{
			msg ( '�༭ʧ�ܣ�');
		}
	}

	/**
	 * ��Ŀ����-���ȼƻ� �༭
	 */
	function c_editInAll(){
		$object = $_POST [$this->objName];
		$this->beforeMethod( $object );//�������߱��ǰ��ȡ�޸�ǰҵ�������Ϣ
		if ($this->service->edit_d ( $object )) {
			$object ['operType_'] = "�޸���ϡ�" . $object ['planName'] . "��";//��������
			$this->behindMethod ($object);//��һ������ҵ����󣬵ڶ�����������Ĭ��Ϊ��
			msg ( '�༭�ɹ���');
		}else{
			msg ( '�༭ʧ�ܣ�');
		}
	}

	/**
	 * ��ʾɾ���ƻ�ҳ��
	 */
	function c_delectPlan(){
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'myPlan';
		if($this->service->canDelete($_GET['pnId'])){
			if($type == 'myPlan'){
				showmsg('ȷ��ɾ����',"location.href='?model=rdproject_plan_rdplan&action=deleteInMy&id=" . $_GET ['pnId'] . "'", 'button' );
			}else{
				showmsg('ȷ��ɾ����',"location.href='?model=rdproject_plan_rdplan&action=deleteInAll&id=" . $_GET ['pnId'] . "'", 'button' );
			}
		}else{
			if($type == 'myPlan'){
				showmsg('������ռƻ����¼����ݣ��ٽ���ɾ��','self.parent.tb_remove();','button');
			}else{
				showmsg('������ռƻ����¼����ݣ��ٽ���ɾ��','self.parent.tb_remove();','button');
			}
		}
	}

	/**
	 * ɾ���ƻ�-��Ŀ����-���ȼƻ�
	 */
	function c_deleteInAll(){
		if ($this->service->deletes ( $_GET['id'] )) {
			msg ( 'ɾ���ɹ���');
		}else{
			msg ( 'ɾ��ʧ�ܣ�');
		}
	}

	/**
	 * ɾ���ƻ�-�ҵĽ��ȼƻ�
	 */
	function c_deleteInMy(){
		if ($this->service->deletes ( $_GET['id'] )) {
			msg ( 'ɾ���ɹ���');
		}else{
			msg ( 'ɾ��ʧ�ܣ�');
		}
	}

	/**
	 * �ƻ���Դʹ��͸��ͼ-���
	 */
	function c_planTable(){
		$rows = $this->service->getTheRows($_GET['pjId']);
		$this->show->assign( 'result' ,$this->service->planTable($rows) );
		$this->show->display( $this->objPath .'_' . $this->objName .'-planChartsView');
	}

	/**
	 * �ƻ���Դʹ��͸��ͼ-ͼ��
	 */
	function c_planCharts(){
		$rows = $this->service->getTheRows($_GET['pjId']);
		$this->show->assign( 'result' ,$this->service->planCharts($rows) );
		$this->show->display( $this->objPath .'_' . $this->objName .'-planChartsView');
	}

	/**
	 * �ƻ�ʵ�ʽ�չ͸��ͼ-ͼ��
	 */
	function c_planSchedule(){
		$rows = $this->service->getTheRows($_GET['pjId']);
		$this->show->assign( 'result' ,$this->service->planSchedule($rows) );
		$this->show->display( $this->objPath .'_' . $this->objName .'-planChartsView');
	}

	/**
	 * �����ƻ�
	 */
	function c_toIssue(){
		showmsg('ȷ�Ϸ�����',"location.href='?model=rdproject_plan_rdplan&action=issue&id=" . $_GET ['pnId'] . "'", 'button' );
	}

	/**
	 * ��������
	 */
	function c_issue(){
		if ($this->service->issue ( $_GET['id'] )) {
			msg ( '�����ɹ���');
		}else{
			msg ( '����ʧ�ܣ�');
		}
	}

	/**
	 * �رս���
	 */
	function c_toClose(){
		showmsg('ȷ�Ͻ��㣿',"location.href='?model=rdproject_plan_rdplan&action=closeAndBalance&id=" . $_GET ['pnId'] . "&planEndDate=". $_GET['planEndDate']. "'", 'button' );
	}

	/**
	 * �رս���
	 */
	function c_closeAndBalance(){
		if ($this->service->closeAndBalance ( $_GET['id'] ,$_GET['planEndDate'])) {
			msg ( '������ɣ�');
		}else{
			msg ( '����ʧ�ܣ�');
		}
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊajax����json����---------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription �ҵĽ��ȼƻ�-�ƻ�
	 * @date 2010-9-25 ����09:53:19
	 */
	function c_rpAjaxMyPlan() {
		//�����������Ŀ���ƣ���Ϊ����Ŀ��ȡ��һ����Ŀ�ƻ�
		if (!isset ( $_GET ['planName'] )) {
			$searchArrGroup = array ("parentId" => -1,"projectId" => $_GET ['parentId'] );
		} else {
			$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		}

		$searchArrGroup['loginId'] = $_SESSION['USER_ID'];

		$this->ajaxGroupAndPlan ( $searchArrGroup );
	}

	function c_rpAjaxAllPlan() {
		//�����������Ŀ���ƣ���Ϊ����Ŀ��ȡ��һ����Ŀ�ƻ�
		if (!isset ( $_GET ['planName'] )) {
			$searchArrGroup = array ("parentId" => -1,"projectId" => $_GET ['parentId'] );
		} else {
			$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		}

		$this->ajaxGroupAndPlan ( $searchArrGroup );
	}

	/**
	 * @desription �����ϼ�id��ȡ�¼��ļƻ��б�
	 * @param tags
	 * @date 2010-9-21 ����11:19:59
	 */
	function ajaxGroupAndPlan($searchArrGroup) {
		$service = $this->service;
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = $searchArrGroup;
		$service->asc = false;
		$service->groupBy = "c.id";
		$groupRows = $service->list_d ();
		//Ȩ�޿���
		$groupRows = $this->service->filterField('�ֶ�����',$groupRows,'list');

		if (is_array ( $groupRows )) {
			//����һ����g_��p_Ϊǰ׺��id������������Ŀ�����
			function createOIdFn($row) {
				if (  $row ['parentId'] ==-1) {
					$row ['oid'] = "a_" . $row ['id']; //��p-Ϊǰ׺�����ϼ�Ϊ��Ŀ
					$row ['oParentId'] = "p_" . $row ['projectId'];
				} else {
					$row ['oid'] = "a_" . $row ['id']; //��a-Ϊǰ׺�����ϼ�Ϊ�ƻ�
					$row ['oParentId'] = "a_" . $row ['parentId'];
				}
				return $row;
			}
			echo util_jsonUtil::encode ( array_map ( "createOIdFn", $groupRows ) );
		}else{
			echo "none";
		}
		//echo util_jsonUtil::encode ( $groupRows );
	}


	/**һ��ͨ�����б�pageJson
	 * add by zengzx    2011��8��22�� 09:26:19
	 */
	function c_pageJsonByOnekey() {
		$service = $this->service;
		$seachArr = $service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = $seachArr;
		$rows = $service->pageBySqlId ("select_plan");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		echo util_jsonUtil::encode ( $arr );
	}

}

?>
