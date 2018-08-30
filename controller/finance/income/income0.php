<?php
/**
 * 到款控制层类
 */
class controller_finance_income_income extends controller_base_action {

	function __construct() {
		$this->objName = "income";
		$this->objPath = "finance_income";
		parent::__construct ();
	}

	/**
	 * 重写page
	 */
	function c_page(){
		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);

		$this->display($thisObjCode.'-list');
	}

	/**
	 * 新增到款页面
	 */
	function c_toAdd() {
		//设置数据字典
		$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ) );
		$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ) );
		$this->assign('incomeDate',day_date);

		//获取默认发送人
		$rs = $this->service->getSendMen_d();
		$this->assignFunc($rs);

		//策略调用新增页面
		$this->assign('formType' ,$_GET['formType']);
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);

		$this->display($thisObjCode . '-add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		//策略调用新增页面
		$thisClass = $this->service->getClass($object['formType']);

		$id = $this->service->add_d ( $object,new $thisClass());
		if ($id) {
			msgRf( '添加成功！','?model=finance_income_income&action=toAdd&formType=YFLX-DKD');
		}else{
			msgRf ('添加失败！');
		}
	}

	/**
	 * 新增其他合同到款页面
	 */
	function c_toAddOther() {
		//设置数据字典
		$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ) );
		$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ) );
		$this->assign('incomeDate',day_date);

		//获取默认发送人
		$rs = $this->service->getSendMen_d();
		$this->assignFunc($rs);

		$this->display('income-other-add');
	}

	/**
	 * 新增其它合同到款
	 */
	function c_addOther(){
		$object = $_POST[$this->objName];

		$id = $this->service->addOther_d ( $object);
		if ($id) {
			msgRf( '添加成功！','?model=finance_income_income&action=toAddOther');
		}else{
			msgRf ('添加失败！');
		}
	}


    /**
     * 下推生成单据
     */
    function c_addByPush(){
        //URL权限控制
        $this->permCheck();

        //获取主从表数据
        $income = $this->service->getInfoAndDetail_d ( $_GET ['id'] );

        //获取从表数据
        //获取从表数据
        $incomeAllotRows = $income['incomeAllot'];
        unset($income['incomeAllot']);

        $this->assignFunc($income);

        //策略调用新增页面
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $this->assign('thisFormType',$_GET['formType']);

        //渲染从表数据
        $incomeStr = $this->service-> initAllot_d( $incomeAllotRows ,'push');
        $this->assign ( 'incomeAllot', $incomeStr[0] );
        $this->assign ( 'countNumb', $incomeStr[1] );

        $this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ),$income['incomeType'] );
        $this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ),$income['sectionType'] );
        $this->display($thisObjCode.'-addbypush');
    }

	/**
	 * 初始到款页面
	 */
	function c_init() {
        //URL权限控制
        $this->permCheck();
		$income = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($income);

		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($income['formType']);

		if (isset($_GET ['perm']) && $_GET ['perm'] == 'view') {
			$this->assign('incomeType',$this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionType',$this->getDataNameByCode($income['sectionType']));
			$this->display ( $thisObjCode. '-view' );
		} else {
			$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ),$income['incomeType'] );
			$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ),$income['sectionType'] );
			$this->display ( $thisObjCode. '-edit' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object) ){
			msgRf ( '编辑成功！' );
		}
	}

	/**
	 * 显示分配到款
	 */
	function c_toAllot(){
        //URL权限控制
        $this->permCheck();

		$incomeId = $_GET ['id'];
		$perm = isset($_GET['perm']) ? $_GET['perm'] : null;

		//获取到款单以及分配信息
		$income = $this->service->getInfoAndDetail_d( $incomeId );

		//返回对象编码
		$thisObjCode = $this->service->getBusinessCode($income['formType']);

		//获取从表数据
		$incomeAllotRows = $income['incomeAllot'];
		unset($income['incomeAllot']);

		$this->assignFunc($income);

		if ( $perm == 'view') {
			$this->assign('incomeType',$this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionType',$this->getDataNameByCode($income['sectionType']));

			//渲染从表数据
			$incomeStr = $this->service-> initAllot_d( $incomeAllotRows ,$perm );
			$this->assign ( 'incomeAllot', $incomeStr );
			$this->display ( $thisObjCode.'-viewallot' );
		} else {
			$this->assign('incomeTypeCN',$this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionTypeCN',$this->getDataNameByCode($income['sectionType']));
			$this->showDatadicts ( array ('incomeTypeList' => 'DKFS' ),$income['incomeType'] );
			$this->showDatadicts ( array ('sectionTypeList' => 'DKLX' ),$income['sectionType'] );
			//渲染从表数据
			$incomeStr = $this->service-> initAllot_d( $incomeAllotRows );
			$this->assign ( 'incomeAllot', $incomeStr[0] );
			$this->assign ( 'countNumb', $incomeStr[1] );
			$this->display ( $thisObjCode.'-editallot' );
		}
	}

	/**
	 * 到款分配
	 */
	function c_allot(){
		$rs = $this->service->allot_d($_POST[$this->objName]);
		if($rs){
			msgRf('分配成功');
		}else{
			msgRf('分配失败');
		}
	}

	/**
	 * 到款分配列表
	 */
	function c_allotList(){
		$this->display( 'allotlist' );
	}


	/**
	 * 获取分页数据转成Json--到款分配页面
	 */
	function c_allotPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST );
		$service->asc = true;
		$rows = $service->pageBySqlId('select_incomeAllot');
        //URL过滤
        $rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 到款管理列表
	 */
	 function c_manageList(){
	 	$this->display( 'managelist' );
	 }

	/**
	 * 获取分页数据转成Json--到款分配页面
	 */
	function c_manageJson() {
		$service = $this->service;
		$service->getParam ( $_POST );

		$service->asc = true;
		$rows = $service->pageBySqlId('select_income');
        //URL过滤
        $rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/************************ excel导入部分*****************************/

     /**
	 *跳转到excel上传页面
	 */
	function c_toExcel() {
		$this->display ('excel' );
	}

	/**
	 * 上传EXCEL
	 */
	function c_upExcel() {
		$resultArr = $this->service->addExecelData_d ($_POST['isCheck']);
		$title = '到款信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);

	}

	/**
	 * excel导出
	 */
	function c_toExcOut(){
		$service = $this->service;

		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->sort = 'c.incomeDate';
		$service->asc = false;
	    $rows = $service->list_d('select_excelout');
		return model_finance_common_financeExcelUtil::exportIncome ( $rows );
	}
}
?>