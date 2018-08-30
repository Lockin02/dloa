<?php
/**
 * @author show
 * @Date 2013年12月5日 11:48:32
 * @version 1.0
 * @description:盖章使用事项配置控制层
 */
class controller_system_stamp_stampmatter extends controller_base_action {

	function __construct() {
		$this->objName = "stampmatter";
		$this->objPath = "system_stamp";
		parent :: __construct();
	}

	/**
	 * 跳转到盖章使用事项配置列表
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * 重写获取分页数据转成Json函数
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();

        // 获取对应的盖章类别名称
        $datadictDao = new model_system_datadict_datadict();
        $typeInfo = $datadictDao->getDataDictList_d('GZLB');//盖章类别

        foreach($rows as $k => $row){
            $rows[$k]['stamp_cName'] = $typeInfo[$row['stamp_cId']];
        }

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 * 跳转到新增盖章使用事项配置页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑盖章使用事项配置页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
        $stamp_cId_Option = $this->showDatadicts ( array ('categoryId' => 'GZLB'),$obj['stamp_cId'],true,null,true);
        $this->show->assign( 'stamp_cId_option', $stamp_cId_Option );

		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看盖章使用事项配置页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('needAudit',$this->service->getNeedAudit($obj['needAudit']));
		$this->assign('status',$this->service->getIsStatus($obj['status']));
		$this->view('view');
	}
	
	/**
	 * 表格方法
	 */
	function c_jsonSelect(){
		$service = $this->service;
		$rows = null;
	
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId();

        $datadictDao = new model_system_datadict_datadict();
        $typeInfo = $datadictDao->getDataDictList_d('GZLB');//盖章类别
        foreach($rows as $k => $row){
            $rows[$k]['stamp_cName'] = $typeInfo[$row['stamp_cId']];
        }

		$rows = $this->sconfig->md5Rows ( $rows );
	
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	
}