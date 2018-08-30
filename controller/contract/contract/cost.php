<?php
/**
 * @author Administrator
 * @Date 2014��2��25�� 16:23:44
 * @version 1.0
 * @description:��ͬ�ɱ�������Ϣ���Ʋ�
 */
class controller_contract_contract_cost extends controller_base_action {

	function __construct() {
		$this->objName = "cost";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/**
	 * ��ת����ͬ�ɱ�������Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ͬ�ɱ�������Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ͬ�ɱ�������Ϣҳ��
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
	 * ��ת���鿴��ͬ�ɱ�������Ϣҳ��
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
    * �ɱ�ȷ�� ��Ʒ���쵼����б�
    */
   function c_confirmCostAppList(){
   	  $this->view('confirmCostAppList');
   }


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //�ɱ�ȷ��Ȩ��
        $otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$costLimit = $sysLimit['�ɱ�ȷ�����'];
		if(strstr($costLimit, ';;')){//Ȩ�޸�Ϊ���ţ�����ȫ����Ԥ���Է���չ
       	   $rows = $service->page_d ();
        }else{
            $costLimitArr = explode(",",$costLimit);
            $costLimitStr = "";
		   foreach($costLimitArr as $k => $v){
			  $costLimitStr .= $v.",";
		   }
		   $costLimitStr = rtrim($costLimitStr, ',');
		   $service->searchArr['productLineArr'] = $costLimitStr;

			//$service->asc = false;
			$rows = $service->page_d ();
        }
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	 /**
	  * ����ɱ�ȷ���쵼��� ��ز���
	  */
	 function c_ajaxBack(){
		try {
//          echo $_POST ['id'];
			$this->service->ajaxBack_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
 }
?>