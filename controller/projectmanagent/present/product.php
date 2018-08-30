<?php
/**
 * @author Liub
 * @Date 2012��3��8�� 14:13:30
 * @version 1.0
 * @description:��ͬ ��Ʒ�嵥���Ʋ�
 */
class controller_projectmanagent_present_product extends controller_base_action
{

    function __construct()
    {
        $this->objName = "product";
        $this->objPath = "projectmanagent_present";
        parent::__construct ();
    }

    /*
     * ��ת����ͬ ��Ʒ�嵥�б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson()
    {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


        $service->asc = false;
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * ��Ʒ����iframe
     */
    function c_toProductIframe()
    {
        $this->view('productiframe');
    }

    /**
     * ѡ���Ʒ��Ϣ
     */
    function c_toSetProductInfo()
    {
        $this->assignFunc($_GET);
        $this->view('selectproductinfo');
    }

    /**
     * ���ݽ���
     */
    function c_toResolve()
    {
        $id = $_GET['id'];

        $this->service->resolve_d($id);
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJsonLimit()
    {
        $service = $this->service;

        //�ؼ���Ա��Ϣ��ȡ
        $createId = $_POST['createId'];
        $prinvipalId = $_POST['prinvipalId'];
        $areaPrincipalId = $_POST['areaPrincipalId'];
        unset($_POST['createId']);
        unset($_POST['prinvipalId']);
        unset($_POST['areaPrincipalId']);


        $service->getParam ( $_REQUEST );
        $rows = $service->list_d ();

        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);

        if ($createId != $_SESSION ['USER_ID'] && $prinvipalId != $_SESSION ['USER_ID'] && $areaPrincipalId != $_SESSION ['USER_ID'])
        {
            $rows = $this->service->filterWithoutField ( '��Ʒ���', $rows, 'list', array ('price', 'money' ), 'contract_contract_contract' );
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

    /**
     * ��ȡ���б����ʶ�ĺ�ͬ��Ʒ��Ϣ
     */
    function c_getChangeProductList(){
    	$service = $this->service;
    	$service->getParam ( $_REQUEST );
		$list=$this->service->getChangeProductList_d($_REQUEST['contractId'],$_POST['isTemp']);
//		echo "<pre>";
//		print_r($list);
		echo util_jsonUtil::encode ( $list );
    }

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($presentId){
		$this->searchArr['presentId'] = $presentId;
		$this->searchArr['isDel'] = '0';
		$this->asc = false;
		return $this->list_d();
	}
}
?>