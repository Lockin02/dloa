<?php
/**
 * @author evan
 * @Date 2010��12��7�� 9:19:54
 * @version 1.0
 * @description:��Ŀ������ oa_esm_mission���Ʋ�
 */
class controller_engineering_prjMissionStatement_esmmission extends controller_base_action {

	function __construct() {
		$this->objName = "esmmission";
		$this->objPath = "engineering_prjMissionStatement";
		parent::__construct ();
	 }

/*********************************************��ͨAction����*******************************************************/

	/*
	 * ��ת����Ŀ������ oa_esm_mission
	 */
    function c_missionList() {
      $this->display('list');
    }

  	/*
	 * @desription �ӡ�ִ���еķ����ͬ���´�������
	 * @param tags
	 * @date 2010-12-7 ����10:24:00
	 */
	function c_issueMissionStatement () {

		$contractId = isset( $_GET['id'] )?$_GET['id'] : null;
		$this->permCheck($contractId,'engineering_serviceContract_serviceContract');
		$this->assign( 'contractId',$contractId );
		$this->display( 'mission-tab' );
	}

	/*
	 * @desription �´�������Ĳ���ҳ��
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 ����11:12:19
	 */
	function c_toIssueMission () {
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId'] : null;
		$issuePerson = $_SESSION['USERNAME'];
		$issuePersonId = $_SESSION['USER_ID'];
		$contractDao = new model_engineering_serviceContract_serviceContract();
		$contractSearch = array( 'id'=>$contractId );
		$contractArr = $contractDao->find($contractSearch,null,'id,orderName');
		$this->assign('name',$contractArr['name']);
		$this->assign('contractId',$contractArr['id']);
		$this->assign('startDate',$contractArr['signDate']);
		$this->assign('endDate',$contractArr['deliveryDate']);
		$this->assign('issueMissionPerson',$issuePerson);
		$this->assign('issueMissionPersonId',$issuePersonId);
		$this->assign('missionStatus','���´�');
		$this->assign( 'status','δ����' );
		$this->display( 'add2' );
	}

	/*
	 * @desription ���桰��Ŀ�����顱�ķ���
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 ����11:22:08
	 */
	function c_issueserviceContract () {
		$service = $this->service;

		$issuedArr = isset( $_POST['esmmission'] )?$_POST['esmmission'] : null;

		$contractDao = new model_engineering_serviceContract_serviceContract();
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		$condiction = array('id'=>$contractId);
		$flag = $contractDao->updateField($condiction,"transmit","1");
		$contractId = $issuedArr['contractId'];
		$condiction = array( 'id'=>$contractId );
		$updateCondiction = array('contractId'=>$contractId);
		$updateCondiction2 = array('id'=>$contractId);


		$issueTag = $service->addissue_d($issuedArr);
		if($issueTag){
			$service->updateField($updateCondiction,'status','δ����');
			$contractDao->updateField($updateCondiction2,'missionStatus','���´�');
//			msgGo( '�´�������ɹ�',$url );
			//����ɹ��󣬹رյ�ǰ���ڣ�������opener������ˢ�¸����ڡ�
			echo "<script>alert('����ɹ�');parent.close();parent.opener.show_page(1);</script>";
		}
	}


	/*
	 * @desription �����ꡰ��Ŀ�����顱����ת���༭ҳ��
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 ����02:28:51
	 */
	function c_toEditIssue () {
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId'] : null;
		$this->service->searchArr = array( 'contractId' => $contractId );
		$rows = $this->service->pageBySqlId();
		foreach($rows[0] as $key => $val){
			$this->assign( $key,$val );
		}
		$this->display('edit');
	}

	/*
	 * @desription �༭������ı��淽��
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 ����02:58:45
	 */
	function c_editIssue () {
		$rows = isset( $_POST['esmmission'] )?$_POST['esmmission'] : null;
		$url = "?model=engineering_prjMissionStatement_esmmission&action=toEditIssue&contractId=" . $rows['contractId'];
		$id = $this->service->edit_d($rows,true);
		if(id){
			msg('�༭�ɹ�');
		}
	}

	/*
	 * @desription ��ת������������ҳ��
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 ����04:07:41
	 */
	function c_toDealIssue () {
		$executor = $_SESSION['USER_ID'];
		$service = $this->service;
		$contractDao = new model_engineering_serviceContract_serviceContract();
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		//���ݺ�ͬIDֵ�ҵ�����������ĸ��ֶΣ��������滻
		$conSearch = array( 'contractId'=>$contractId);
		$conSearch2 = array( 'id'=>$contractId );
		$rows = $this->service->find($conSearch,null,'name,startDate,endDate,detailedDescription,requirements,personnelRequire,id,contractId,issueMissionPerson' );
		$conRows = $contractDao->find($conSearch2,null,'id');
		foreach($rows as $key => $val){
			$this->assign( $key,$val );
		}
		$this->assign('relatedContract',$rows['name']);
		$this->assign('missionId',$rows['id']);

		$this->assign('provice',$conRows['provice']);
		$this->assign('proviceId',$conRows['proviceId']);

		$this->showDatadicts( array('itemFactor' => 'GCXMXS') );		//��Ŀϵ��
		$this->showDatadicts( array( 'priority' => 'GCXMYXJ') );		//���ȼ�
		$this->showDatadicts( array( 'networks' => 'GCZYWL' ) );		//��Ҫ����
		$this->showDatadicts( array( 'cycle' => 'GCCDQ' ) );			//���ڵĳ���
		$this->showDatadicts( array( 'category' => 'GCXMXZ' ) );		//��Ŀ���

		$this->assign( 'contractId',$contractId );
		$this->assign( 'executor',$executor );					//ִ����
		$this->display( 'mission' );
	}

	/*
	 * @desription ����������
	 * @param tags
	 * @author lin
	 * @date 2010-12-8 ����04:02:26
	 */
	function c_dealIssue () {
		$getArr = isset( $_POST['esmmission'] )?$_POST['esmmission']:null;
		//�����˼�������ID
		$executor = isset( $_SESSION['USERNAME'] )?$_SESSION['USERNAME'] : null;
		$executorId = isset( $_SESSION['USER_ID'] )?$_SESSION['USER_ID'] : null;

		$getArr['executor'] = $executor;
		$getArr['executorId'] = $executorId;

		$rs = $this->service->dealIssue_d($getArr);
		if($rs){
			msg('�������');
		}else{
			msg('����ʧ��');
		}
	}



 }
?>