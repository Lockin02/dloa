<?php
/**
 * @author Show
 * @Date 2011年12月16日 星期五 11:24:28
 * @version 1.0
 * @description:盖章记录控制层
 */
class controller_contract_stamp_stamp extends controller_base_action {

	function __construct() {
		$this->objName = "stamp";
		$this->objPath = "contract_stamp";
		parent::__construct ();
	}

	/*
	 * 跳转到盖章记录列表
	 */
    function c_page() {
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonForStampType() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_POST );

		$rs = $this->service->getStampTypeList_d();
		if(is_array($rs)){
			$service->searchArr['stampTypes'] = implode($rs,',');

			//$service->asc = false;
			$rows = $service->page_d ();
			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $rows );
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

     /**
	 * 盖章记录
	 */
	function c_listrecords(){
		$this->view ( 'listrecords' );
	}

	/**
	 * 跳转到新增盖章记录页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑盖章记录页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//设置盖章类型
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//盖章类型

		$this->view ( 'edit');
	}

    /**
     * 修改对象
     */
    function c_editWithBusiness() {
//      $this->permCheck (); //安全校验
        $object = $_POST [$this->objName];
        if ($this->service->editWithBusiness_d ( $object)) {
            msg ( '编辑成功！' );
        }
    }

	/**
	 * 跳转到查看盖章记录页面
	 */
	function c_toView() {
		$id = $_GET['id'];

		//设置盖章基本信息
		$obj = $this->service->get_d($id);

		//获取盖章源单信息并渲染
		$newClass = $this->service->getClass($obj['contractType']);
		$initObj = new $newClass();
		$rs = $this->service->initStamp_d($obj,$initObj);

		$contractTypeCN =  $this->getDataNameByCode ( $obj ['contractType']) ;
		//标题渲染
		$this->assign ( 'contractTypeCN',$contractTypeCN );

		//扩展页面加载
		$this->assignFunc($rs);
		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expandview');
		//确认页面加载
		$this->assignFunc($obj);

//		$this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );
		$this->assign ( 'contractTypeCN', $contractTypeCN );
		$this->assign( 'status' , $this->service->rtStampType_d($obj['status']));
		$this->display ( 'view' );
	}

	/**
	 * 盖章确认页面
	 */
	function c_toConfirmStamp(){
		$id = $_GET['id'];

		//设置盖章基本信息
		$obj = $this->service->get_d($id);

		//获取盖章源单信息并渲染
		$newClass = $this->service->getClass($obj['contractType']);
		$initObj = new $newClass();
		$rs = $this->service->initStamp_d($obj,$initObj);

		//标题渲染
		$this->assign ( 'contractTypeCN', $this->getDataNameByCode ( $obj ['contractType'] ) );

		//扩展页面加载
		$this->assignFunc($rs);
		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expand');

		//确认页面加载
		$this->assignFunc($obj);
		//数据字典渲染
//		$this->showDatadicts ( array ('stampType' => 'HTGZ' ), $obj ['stampType'] ,false);

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//盖章类型

		$this->display( 'confirmstamp');
	}

	/**
	 * 盖章确认 - 新
	 */
	function c_confirmStamp(){
     	if($this->service->confirmStamp_d ( $_POST[$this->objName] )){
			msgRf('确认成功');
     	}else{
     		msgRf('确认失败');
     	}
	}

    /**
     * 确认盖章操作 - 异步
     */
     function c_confirmedSealed(){
     	if($this->service->confirmedSealed_d ( $_POST['id'] )){
			msgRf('确认成功');
     	}else{
     		msgRf('确认失败');
     	}
     }

     /**
	 * 查看Tab中查看盖章记录
	 * 依据是从tab页传过来的合同id和合同类型
	 */
	function c_viewForContract(){
		$this->assignFunc($_GET);
        $this->assign('userId',$_SESSION['USER_ID']);
		$this->view ( 'listforcontract' );
	}
    /**
     * 页面显示
     */
    function c_toViewOnly(){
        $conditions = array(
            "id" => $_GET ['id']
        );
        $obj= $this->service->find($conditions);
        foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
        }
        $this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );
        $this->assign ( 'contractType', $this->getDataNameByCode ( $obj ['contractType'] ) );
        $this->assign ( 'status', $this->service->rtStampType_d ( $obj ['status'] ) );

        $this->view ( 'viewonly' );
    }

	/**
	 * 批量盖章
	 */
	function c_batchStamp(){
	 	$arr = $_POST['rowIds'];
	 	if($this->service->batchStamp_d( $arr )){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 	exit();
	}

	/**
	 * 检验对象是否已盖章
	 */
	function c_isStamped(){
		if($this->service->find(array('contractId' => $_POST['contractId'],'contractType' => $_POST['contractType']),'id')){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * 检查对象是否存在相同的章
	 */
	function c_checkRepeat(){
		$this->service->getParam($_POST);
		$rs = $this->service->list_d();
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * 关闭盖章功能
	 */
	function c_close(){
		$id = $_POST['id'];
		if($this->service->close_d($id)){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>