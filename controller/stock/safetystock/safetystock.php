<?php
/**
 * @author huangzf
 * @Date 2012��8��20�� ����һ 20:43:04
 * @version 1.0
 * @description:��ȫ����б���Ʋ�
 */
class controller_stock_safetystock_safetystock extends controller_base_action {

	function __construct() {
		$this->objName = "safetystock";
		$this->objPath = "stock_safetystock";
		parent::__construct ();
	}

	/**
	 * ��ת����ȫ����б��б�
	 */
	function c_page() {
		$this->view( 'list' );
	}

	/**
	 * �б��޸�
	 */
	function c_pageJsonCount(){
		$service = $this->service;
        //���ز���Ȩ��
        $deptLimit = isset($this->service->this_limit['������']) && !empty($this->service->this_limit['������']) ?
            $this->service->this_limit['������'].','.$_SESSION['DEPT_ID'] : $_SESSION['DEPT_ID'];
        if(strpos($deptLimit,';;') === FALSE){
            $_REQUEST['manageDeptIds'] = $deptLimit;
        }
		$service->getParam ( $_REQUEST );
		$sql = $service->getCountSql_d();
		$rows = $service->pageBySql($sql);

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
	 * ��ת��������ȫ����б�ҳ��
	 */
	function c_toAdd() {
        $this->assign('manageDept',$_SESSION['DEPT_NAME']);
        $this->assign('manageDeptId',$_SESSION['DEPT_ID']);
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭��ȫ����б�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$lastPrice = $this->service->getLastPrice ( $val ['productId'] );
		$actNum = $this->service->getProActNum ( $val ['productId'] );
		$this->assign ( "actNum", $actNum );
		$this->assign ( "price", $lastPrice );
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴��ȫ����б�ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$lastPrice = $this->service->getLastPrice ( $val ['productId'] );
		$actNum = $this->service->getProActNum ( $val ['productId'] );
		$this->assign ( "actNum", $actNum );
		$this->assign ( "price", $lastPrice );
		$this->view ( 'view' );
	}

	/**
	 *
	 * ��ȡ�豸�������ϵĿ����Ʒ������
	 */
	function c_getProActNum() {
		echo $this->service->getProActNum ( $_POST ['productId'] );
	}

	/**
	 *
	 * ��ȡ��;����
	 */
	function c_getEqusOnway() {
		$orderEquDao = new model_purchase_contract_equipment ();
		echo $orderObj = $orderEquDao->getEqusOnway ( array ('productId' => $_POST ['productId'] ) );
	}

	/**
	 *
	 * ��ȡ������ⵥ��
	 */
	function c_getLastPrice() {
		echo $this->service->getLastPrice ( $_POST ['productId'] );
	}

	/**
	 *
	 * ��ת������ҳ��
	 */
	function c_toAnalyse() {
		$itemArr = $this->service->findAnalyseItem ();
		$this->assign ( "itemsList", $this->service->showAnlyseItemList ( $itemArr ) );
		$this->view ( "analyse" );
	}

	/**
	 *
	 * ����EXCEL
	 */
	function c_toExportExcel() {
        //���ز���Ȩ��
        $deptLimit = isset($this->service->this_limit['����Ȩ��']) ? $this->service->this_limit['����Ȩ��'] : $_SESSION['DEPT_ID'];
        if(strpos($deptLimit,';;') === FALSE){
            $this->service->searchArr = array('manageDeptIds' => $deptLimit);
        }
		$sql = $this->service->getCountSql_d();
		$dataArr = $this->service->listBySql($sql);
		$dao = new model_stock_productinfo_importProductUtil ();
		return $dao->exportSafeStockExcel ( $dataArr );
	}
}