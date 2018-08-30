<?php
/**
 * @author Administrator
 * @Date 2013年9月24日 星期二 16:07:02
 * @version 1.0
 * @description:工作量确认单明细 Model层
 */
 class model_outsourcing_workverify_verifyDetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_workverify_detail";
		$this->sql_map = "outsourcing/workverify/verifyDetailSql.php";
		parent::__construct ();
	}

	   /** 项目经理审核
     */
    function managerAudit_d($id,$managerAuditState,$managerAuditRemark){
		try{
			$this->start_d();

			$object = array(
				'id' => $id,
				'managerAuditState' => $managerAuditState,
				'managerAuditRemark' => $managerAuditRemark,
				'managerId' => $_SESSION['USER_ID'],
				'managerAuditDate' => date("Y-m-d"),
				'managerName' => $_SESSION['USERNAME']
			);
			$object = $this->addUpdateInfo($object);
			$this->updateById($object);
			$this->mailForServerMananger($id,1);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			echo $e->getMessage();
			return false;
		}
    }

    	   /** 项目经理审核
     */
    function managerAuditBack_d($object){
		try{
			$this->start_d();

			$object['managerAuditState'] = '1';
			$object['managerId'] = $_SESSION['USER_ID'];
			$object['managerAuditDate'] = date("Y-m-d");
			$object['managerName'] = $_SESSION['USERNAME'];
			$this->updateById($object);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			echo $e->getMessage();
			return false;
		}
    }

   /** 服务经理审核
     */
    function serverAudit_d($id,$serverAuditState,$serverAuditRemark){
		try{
			$this->start_d();

			$object = array(
				'id' => $id,
				'serverAuditState' => $serverAuditState,
				'serverAuditRemark' => $serverAuditRemark,
				'serverManagerId' => $_SESSION['USER_ID'],
				'serverAuditDate' => day_date,
				'serverManagerName' => $_SESSION['USERNAME']
			);
			$object = $this->addUpdateInfo($object);
			$this->updateById($object);
			$this->mailForServerMananger($id,2);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			echo $e->getMessage();
			return false;
		}
    }

       /** 服务经理审核打架回
     */
    function serverAuditBack_d($id,$managerAuditState,$serverAuditRemark){
		try{
			$this->start_d();

			$object = array(
				'id' => $id,
				'managerAuditState' => $managerAuditState,
				'serverAuditRemark' => $serverAuditRemark,
				'serverManagerId' => $_SESSION['USER_ID'],
				'serverAuditDate' => day_date,
				'serverManagerName' => $_SESSION['USERNAME']
			);
			$object = $this->addUpdateInfo($object);
			$this->updateById($object);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			echo $e->getMessage();
			return false;
		}
    }

       /** 服务总监审核
     */
    function areaAudit_d($id,$areaAuditState,$areaAuditRemark){
		try{
			$this->start_d();

			$object = array(
				'id' => $id,
				'areaAuditState' => $areaAuditState,
				'areaAuditRemark' => $areaAuditRemark,
				'areaManagerId' => $_SESSION['USER_ID'],
				'areaAuditDate' => day_date,
				'areaManager' => $_SESSION['USERNAME']
			);
			$object = $this->addUpdateInfo($object);
			$this->updateById($object);
			$parentId=$this->get_table_fields($this->tbl_name, "id='".$id."'", 'parentId');
			$suppVerifyId=$this->get_table_fields($this->tbl_name, "id='".$id."'", 'suppVerifyId');
			//是否已全部审批完成，是则更新确认单的状态为审批完成
			if($this->findCount("areaAuditState<>'1' and parentId=".$parentId." ")=="0"){
				$workVerifyDao=new model_outsourcing_workverify_workVerify();
		     	$workVerifyDao->updateById(array("id"=>$parentId,"status"=>"5"));
			}
			if($this->findCount("areaAuditState<>'1' and suppVerifyId=".$suppVerifyId." ")=="0"){
				$suppVerifyDao=new model_outsourcing_workverify_suppVerify();
		     	$suppVerifyDao->updateById(array("id"=>$suppVerifyId,"status"=>"5"));
			}
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			echo $e->getMessage();
			return false;
		}
    }

   /** 服务总监审核打回
     */
    function areaAuditBack_d($id,$serverAuditState,$areaAuditRemark){
		try{
			$this->start_d();

			$object = array(
				'id' => $id,
				'serverAuditState' => $serverAuditState,
				'areaAuditRemark' => $areaAuditRemark,
				'areaManagerId' => $_SESSION['USER_ID'],
				'areaAuditDate' => day_date,
				'areaManager' => $_SESSION['USERNAME']
			);
			$object = $this->addUpdateInfo($object);
			$this->updateById($object);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			echo $e->getMessage();
			return false;
		}
    }

   /**
	 * 根据ParentId获取详细雋
	 *
	 */
	 function getListByParentId($parentId){
		$this->searchArr = array ('parentId' => $parentId);
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }

   /**
	 * 统计项目经理未审批记录数
	 *
	 */
	 function countManagerAuditNo(){
		$this->searchArr = array ('esmManagerIdFind' => $_SESSION['USER_ID'],'managerAuditState'=>'0','parentState'=>'1');
		$rows= $this->listBySqlId ( "select_project" );
		if(is_array($rows)){
			return count($rows);
		}else{
			return '0';
		}
	 }

       /**
	 * 统计服务经理未审批记录数
	 *
	 */
	 function countServerAuditNo(){
	 	//获取所负责的省份
		$provinceDao=new model_engineering_officeinfo_range();
		$provinceStr=$provinceDao->getProvinces_d($_SESSION['USER_ID']);
		$this->searchArr = array ('provinceArr' => $provinceStr,'managerAuditState'=>'1','serverAuditState'=>'0');
		$rows= $this->listBySqlId ( "select_default" );
		if(is_array($rows)){
			return count($rows);
		}else{
			return '0';
		}
	 }

    /**
	 * 统计服务总监未审批记录数
	 *
	 */
	 function countAreaAuditNo(){
	 	//获取所负责的省份
		$officeinfoDao=new model_engineering_officeinfo_officeinfo();
		$officeIds=$officeinfoDao->getOfficeIds_d($_SESSION['USER_ID']);
		$this->searchArr = array ('officeIdArr' => $officeIds,'managerAuditState'=>'1','serverAuditState'=>'1','areaAuditState'=>'0');
		$rows= $this->listBySqlId ( "select_default" );
		if(is_array($rows)){
			return count($rows);
		}else{
			return '0';
		}
	 }
	 /**
	 * 获取项目id
	 */
	function getProjectIds_d($userId = null){
		$userId = empty($userId) ? $_SESSION['USER_ID'] : $userId;
		$this->searchArr = array('esmManagerIdFind' => $userId);
		$rs = $this->list_d("select_project");
		if(is_array($rs)){
			$idArr = array();
			foreach($rs as $val){
				array_push($idArr,$val['projectId']);
			}
			return implode($idArr,',');
		}else{
			return '';
		}
	}

	/**
	 * 项目经理审核 - 批量
	 */
	function auditAllForManager_d($object){
		try{
			$this->start_d();

			//查询要审核的日志
			$objArr = $this->findAll(array(
				'projectId' => $object['projectId'],
				'parentId' => $object['parentId'],
				'managerAuditState' => '0'
			),null,'id');


			//如果有查到日志，则循环进行审核
			if($objArr){
				foreach($objArr as $val){
					$object = array(
						'id' => $val['id'],
						'managerAuditState' => '1',
						'managerId' => $_SESSION['USER_ID'],
						'managerAuditDate' => day_date,
						'managerName' => $_SESSION['USERNAME']
					);
				$this->updateById($object);
				$this->mailForServerMananger($val['id'],1);
				}
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false();
		}
	}

		/**
	 * 服务经理审核 - 批量
	 */
	function auditAllForSever_d($object){
		try{
			$this->start_d();

			//查询要审核的日志
			$objArr = $this->findAll(array(
				'projectId' => $object['projectId'],
				'parentId' => $object['parentId'],
				'managerAuditState' => '1',
				'serverAuditState' => '0'
			),null,'id');


			//如果有查到日志，则循环进行审核
			if($objArr){
				foreach($objArr as $val){
					$object = array(
						'id' => $val['id'],
						'serverAuditState' => '1',
						'serverManagerId' => $_SESSION['USER_ID'],
						'serverAuditDate' => day_date,
						'serverManagerName' => $_SESSION['USERNAME']
					);
					$this->updateById($object);
					$this->mailForServerMananger($val['id'],2);
				}
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false();
		}
	}

	/**
	 * 服务总监审核 - 批量
	 */
	function auditAllForArea_d($object){
		try{
			$this->start_d();

			//查询要审核的日志
			$objArr = $this->findAll(array(
				'projectId' => $object['projectId'],
				'parentId' => $object['parentId'],
				'managerAuditState' => '1',
				'serverAuditState' => '1',
				'areaAuditState' => '0'
			),null,'id');


			//如果有查到日志，则循环进行审核
			if($objArr){

				$workVerifyDao=new model_outsourcing_workverify_workVerify();
				foreach($objArr as $val){
					$object = array(
						'id' => $val['id'],
						'areaAuditState' => '1',
						'areaManagerId' => $_SESSION['USER_ID'],
						'areaAuditDate' => day_date,
						'areaManager' => $_SESSION['USERNAME']
					);
					$this->updateById($object);
					//是否已全部审批完成，是则更新确认单的状态为审批完成
//					if($this->findCount("areaAuditState<>'1' and parentId=".$object['parentId']." ")=="0"){
//				     	$workVerifyDao->updateById(array("id"=>$object['parentId'],"status"=>"5"));
//					}
				}
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false();
		}
	}
	/**发送邮件给服务经理和服务总监
	 * $type 1:服务经理 2：服务总监*/
	function mailForServerMananger($id,$type){
		//获取离职申请信息
		$row=$this->get_d($id);
		if($type==1){
			//获取服务经理ID
			$sendUserId=$this->get_table_fields('oa_system_province_info', "id=".$row['provinceId'], 'esmManagerId');
		}else if($type==2){
			//获取服务总监ID
			$sendUserId=$this->get_table_fields('oa_esm_office_baseinfo', "id=".$row['officeId'], 'mainManagerId');
		}
		$emailDao = new model_common_mail();
		$addMsg="您好,".$row['userName']."的工作量已提交,请审核。";
		if(!empty($sendUserId)){
			$emailDao->mailClear($row['userName'].'的工作量审核', $sendUserId, $addMsg, null);
		}
	}
 }
?>