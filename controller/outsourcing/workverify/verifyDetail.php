<?php
/**
 * @author Administrator
 * @Date 2013��9��24�� ���ڶ� 16:07:02
 * @version 1.0
 * @description:������ȷ�ϵ���ϸ���Ʋ�
 */
class controller_outsourcing_workverify_verifyDetail extends controller_base_action {

	function __construct() {
		$this->objName = "verifyDetail";
		$this->objPath = "outsourcing_workverify";
		parent::__construct ();
	 }

	/**
	 * ��ת��������ȷ�ϵ���ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������������ȷ�ϵ���ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭������ȷ�ϵ���ϸҳ��
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
	 * ��ת���鿴������ȷ�ϵ���ϸҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

        /**
	 * ��ת��������ȷ��(��Ŀ����)
	 */
	function c_toManagerAuditList() {
		$projectIds=$this->service->getProjectIds_d();
		if($projectIds==''){
			$projectIds='��';
		}
		$this->assign('projectIds',$projectIds);
      $this->view ( 'verify-manager' );
   }

   	//��Ŀ�������
	function c_managerAudit(){
        $id = $_POST['id'];
        $managerAuditState = $_POST['managerAuditState'];
        $managerAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['managerAuditRemark']);
        $rs = $this->service->managerAudit_d($id,$managerAuditState,$managerAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	  	//��Ŀ�������(����)
	function c_managerAuditBack(){
        $object['id'] = $_POST['id'];
         $object['beginDatePM'] = $_POST['beginDatePM'];
         $object['endDatePM'] = $_POST['endDatePM'];
         $object['feeDayPM'] = $_POST['feeDayPM'];
         $object['managerAuditRemark'] = util_jsonUtil::iconvUTF2GB($_POST['managerAuditRemark']);
        $rs = $this->service->managerAuditBack_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}


        /**
	 * ��ת��������ȷ��(������)
	 */
	function c_toServerAuditList() {
		//��ȡ�������ʡ��
		$provinceDao=new model_engineering_officeinfo_range();
		$provinceStr=$provinceDao->getProvinces_d($_SESSION['USER_ID']);
		if($provinceStr==''){
			$provinceStr='��';
		}
		$this->assign('provinceStr',$provinceStr);
     	 $this->view ( 'verify-server' );
   }

      	//���������
	function c_serverAudit(){
        $id = $_POST['id'];
        $serverAuditState = $_POST['serverAuditState'];
        $serverAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['serverAuditRemark']);
        $rs = $this->service->serverAudit_d($id,$serverAuditState,$serverAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	      	//��������
	function c_serverAuditBack(){
        $id = $_POST['id'];
        $managerAuditState = $_POST['managerAuditState'];
        $serverAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['serverAuditRemark']);
        $rs = $this->service->serverAuditBack_d($id,$managerAuditState,$serverAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	        /**
	 * ��ת��������ȷ��(�����ܼ�)
	 */
	function c_toAreaAuditList() {
		//��ȡ�������ʡ��
		$officeinfoDao=new model_engineering_officeinfo_officeinfo();
		$officeIds=$officeinfoDao->getOfficeIds_d($_SESSION['USER_ID']);
		if($officeIds==''){
			$officeIds='��';
		}
		$this->assign('officeIds',$officeIds);
      $this->view ( 'verify-area' );
   }

         	//�����ܼ����
	function c_areaAudit(){
        $id = $_POST['id'];
        $areaAuditState = $_POST['areaAuditState'];
        $areaAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['areaAuditRemark']);
        $rs = $this->service->areaAudit_d($id,$areaAuditState,$areaAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

 	//�����ܼ���
	function c_areaAuditBack(){
        $id = $_POST['id'];
        $serverAuditState = $_POST['serverAuditState'];
        $areaAuditRemark = util_jsonUtil::iconvUTF2GB($_POST['areaAuditRemark']);
        $rs = $this->service->areaAuditBack_d($id,$serverAuditState,$areaAuditRemark);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	/**
	 * ��Ŀͳ�������Ϣ����Ŀ����
	 */
	function c_auditJsonForManager(){
		$service = $this->service;
		$service->getParam ( $_POST );

		$service->groupBy = 'c.projectId,c.parentId';
		$rows = $service->list_d ('select_default');
		echo util_jsonUtil::encode ( $rows );
	}

		//��Ŀ������� -- ����Ŀ����
	function c_auditAllForManager(){
       	$object = $_POST;
        $rs = $this->service->auditAllForManager_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	//��������� -- ����Ŀ����
	function c_auditAllForSever(){
       	$object = $_POST;
        $rs = $this->service->auditAllForSever_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

		//�����ܼ���� -- ����Ŀ����
	function c_auditAllForArea(){
       	$object = $_POST;
        $rs = $this->service->auditAllForArea_d($object);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
	}

	/**
	 * ��ȡ�����Ӧ�̹��������������Խ����ݷ���json
	 */
	function c_suppVerifyListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ( 'select_suppVerify' );
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>