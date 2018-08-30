<?php
/**
 * @author Liub
 * @Date 2012年3月8日 14:13:30
 * @version 1.0
 * @description:合同 产品清单控制层
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
     * 跳转到合同 产品清单列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson()
    {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息


        $service->asc = false;
        $rows = $service->page_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 产品配置iframe
     */
    function c_toProductIframe()
    {
        $this->view('productiframe');
    }

    /**
     * 选择产品信息
     */
    function c_toSetProductInfo()
    {
        $this->assignFunc($_GET);
        $this->view('selectproductinfo');
    }

    /**
     * 数据解析
     */
    function c_toResolve()
    {
        $id = $_GET['id'];

        $this->service->resolve_d($id);
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJsonLimit()
    {
        $service = $this->service;

        //关键人员信息获取
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
            $rows = $this->service->filterWithoutField ( '产品金额', $rows, 'list', array ('price', 'money' ), 'contract_contract_contract' );
        }

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

    /**
     * 获取带有变更标识的合同产品信息
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
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($presentId){
		$this->searchArr['presentId'] = $presentId;
		$this->searchArr['isDel'] = '0';
		$this->asc = false;
		return $this->list_d();
	}
}
?>