<?php
/**
 * @author evan
 * @Date 2010年12月7日 9:19:54
 * @version 1.0
 * @description:项目任务书 oa_esm_mission控制层
 */
class controller_engineering_prjMissionStatement_esmmission extends controller_base_action {

	function __construct() {
		$this->objName = "esmmission";
		$this->objPath = "engineering_prjMissionStatement";
		parent::__construct ();
	 }

/*********************************************普通Action方法*******************************************************/

	/*
	 * 跳转到项目任务书 oa_esm_mission
	 */
    function c_missionList() {
      $this->display('list');
    }

  	/*
	 * @desription 从“执行中的服务合同”下达任务书
	 * @param tags
	 * @date 2010-12-7 上午10:24:00
	 */
	function c_issueMissionStatement () {

		$contractId = isset( $_GET['id'] )?$_GET['id'] : null;
		$this->permCheck($contractId,'engineering_serviceContract_serviceContract');
		$this->assign( 'contractId',$contractId );
		$this->display( 'mission-tab' );
	}

	/*
	 * @desription 下达任务书的操作页面
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 上午11:12:19
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
		$this->assign('missionStatus','已下达');
		$this->assign( 'status','未处理' );
		$this->display( 'add2' );
	}

	/*
	 * @desription 保存“项目任务书”的方法
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 上午11:22:08
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
			$service->updateField($updateCondiction,'status','未处理');
			$contractDao->updateField($updateCondiction2,'missionStatus','已下达');
//			msgGo( '下达任务书成功',$url );
			//保存成功后，关闭当前窗口，并调用opener方法，刷新父窗口。
			echo "<script>alert('保存成功');parent.close();parent.opener.show_page(1);</script>";
		}
	}


	/*
	 * @desription 保存完“项目任务书”后跳转到编辑页面
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 下午02:28:51
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
	 * @desription 编辑任务书的保存方法
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 下午02:58:45
	 */
	function c_editIssue () {
		$rows = isset( $_POST['esmmission'] )?$_POST['esmmission'] : null;
		$url = "?model=engineering_prjMissionStatement_esmmission&action=toEditIssue&contractId=" . $rows['contractId'];
		$id = $this->service->edit_d($rows,true);
		if(id){
			msg('编辑成功');
		}
	}

	/*
	 * @desription 跳转到处理任务书页面
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 下午04:07:41
	 */
	function c_toDealIssue () {
		$executor = $_SESSION['USER_ID'];
		$service = $this->service;
		$contractDao = new model_engineering_serviceContract_serviceContract();
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		//根据合同ID值找到任务书里的四个字段，并进行替换
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

		$this->showDatadicts( array('itemFactor' => 'GCXMXS') );		//项目系数
		$this->showDatadicts( array( 'priority' => 'GCXMYXJ') );		//优先级
		$this->showDatadicts( array( 'networks' => 'GCZYWL' ) );		//主要网络
		$this->showDatadicts( array( 'cycle' => 'GCCDQ' ) );			//周期的长短
		$this->showDatadicts( array( 'category' => 'GCXMXZ' ) );		//项目类别

		$this->assign( 'contractId',$contractId );
		$this->assign( 'executor',$executor );					//执行者
		$this->display( 'mission' );
	}

	/*
	 * @desription 处理任务书
	 * @param tags
	 * @author lin
	 * @date 2010-12-8 下午04:02:26
	 */
	function c_dealIssue () {
		$getArr = isset( $_POST['esmmission'] )?$_POST['esmmission']:null;
		//处理人及处理人ID
		$executor = isset( $_SESSION['USERNAME'] )?$_SESSION['USERNAME'] : null;
		$executorId = isset( $_SESSION['USER_ID'] )?$_SESSION['USER_ID'] : null;

		$getArr['executor'] = $executor;
		$getArr['executorId'] = $executorId;

		$rs = $this->service->dealIssue_d($getArr);
		if($rs){
			msg('处理完成');
		}else{
			msg('处理失败');
		}
	}



 }
?>