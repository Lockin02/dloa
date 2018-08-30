<?php
/**
 * @author Administrator
 * @Date 2013��9��24�� ���ڶ� 16:07:02
 * @version 1.0
 * @description:������ȷ�ϵ���ϸ Model��
 */
 class model_outsourcing_workverify_verifyDetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_workverify_detail";
		$this->sql_map = "outsourcing/workverify/verifyDetailSql.php";
		parent::__construct ();
	}

	   /** ��Ŀ�������
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

    	   /** ��Ŀ�������
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

   /** ���������
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

       /** ��������˴�ܻ�
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

       /** �����ܼ����
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
			//�Ƿ���ȫ��������ɣ��������ȷ�ϵ���״̬Ϊ�������
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

   /** �����ܼ���˴��
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
	 * ����ParentId��ȡ��ϸ�h
	 *
	 */
	 function getListByParentId($parentId){
		$this->searchArr = array ('parentId' => $parentId);
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }

   /**
	 * ͳ����Ŀ����δ������¼��
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
	 * ͳ�Ʒ�����δ������¼��
	 *
	 */
	 function countServerAuditNo(){
	 	//��ȡ�������ʡ��
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
	 * ͳ�Ʒ����ܼ�δ������¼��
	 *
	 */
	 function countAreaAuditNo(){
	 	//��ȡ�������ʡ��
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
	 * ��ȡ��Ŀid
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
	 * ��Ŀ������� - ����
	 */
	function auditAllForManager_d($object){
		try{
			$this->start_d();

			//��ѯҪ��˵���־
			$objArr = $this->findAll(array(
				'projectId' => $object['projectId'],
				'parentId' => $object['parentId'],
				'managerAuditState' => '0'
			),null,'id');


			//����в鵽��־����ѭ���������
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
	 * ��������� - ����
	 */
	function auditAllForSever_d($object){
		try{
			$this->start_d();

			//��ѯҪ��˵���־
			$objArr = $this->findAll(array(
				'projectId' => $object['projectId'],
				'parentId' => $object['parentId'],
				'managerAuditState' => '1',
				'serverAuditState' => '0'
			),null,'id');


			//����в鵽��־����ѭ���������
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
	 * �����ܼ���� - ����
	 */
	function auditAllForArea_d($object){
		try{
			$this->start_d();

			//��ѯҪ��˵���־
			$objArr = $this->findAll(array(
				'projectId' => $object['projectId'],
				'parentId' => $object['parentId'],
				'managerAuditState' => '1',
				'serverAuditState' => '1',
				'areaAuditState' => '0'
			),null,'id');


			//����в鵽��־����ѭ���������
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
					//�Ƿ���ȫ��������ɣ��������ȷ�ϵ���״̬Ϊ�������
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
	/**�����ʼ���������ͷ����ܼ�
	 * $type 1:������ 2�������ܼ�*/
	function mailForServerMananger($id,$type){
		//��ȡ��ְ������Ϣ
		$row=$this->get_d($id);
		if($type==1){
			//��ȡ������ID
			$sendUserId=$this->get_table_fields('oa_system_province_info', "id=".$row['provinceId'], 'esmManagerId');
		}else if($type==2){
			//��ȡ�����ܼ�ID
			$sendUserId=$this->get_table_fields('oa_esm_office_baseinfo', "id=".$row['officeId'], 'mainManagerId');
		}
		$emailDao = new model_common_mail();
		$addMsg="����,".$row['userName']."�Ĺ��������ύ,����ˡ�";
		if(!empty($sendUserId)){
			$emailDao->mailClear($row['userName'].'�Ĺ��������', $sendUserId, $addMsg, null);
		}
	}
 }
?>