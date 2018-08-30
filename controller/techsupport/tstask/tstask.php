<?php
/**
 * @author Show
 * @Date 2011年5月9日 星期一 19:44:55
 * @version 1.0
 * @description:服务项目表控制层
 */
class controller_techsupport_tstask_tstask extends controller_base_action {

	function __construct() {
		$this->objName = "tstask";
		$this->objPath = "techsupport_tstask";
		parent::__construct ();
	 }

	/*
	 * 跳转到服务项目表
	 */
    function c_page() {
      $this->display('list');
    }

    /**
     * 选择类型页面
     */
    function c_toSelect(){
    	$this->showDatadicts( array('formType' => 'FWXMLX') );
		$this->display( 'toadd' );
    }

    /**
     * 重写toadd
     */
    function c_toAdd(){
    	//策略调用新增页面
        $formType = isset($_GET['formType'])? $_GET['formType'] : $_GET['obj']['formType'];
		$this->assign('formType' ,$formType);
		$thisObjCode = $this->service->getBusinessCode($formType);

		$this->showDatadicts(array( 'needEat' => 'YANDN' ));
		$this->showDatadicts(array( 'needPresents' => 'YANDN' ));
		$this->assign('salesmanId' ,$_SESSION['USER_ID']);
		$this->assign('salesman' ,$_SESSION['USERNAME']);

		$this->display( $thisObjCode . '-add');
    }
     /**
      * 我的售前支持
      */
      function c_mybeforelist(){
         $this->display('mybeforelist');
      }
    /**
     * 外部申请用新增方法
     */
    function c_toAddWin(){
		//策略调用新增页面//策略调用新增页面
//		$this->assign('formType' ,$_GET['formType']);
		$this->permCheck($_GET['obj']['objId'],'projectmanagent_chance_chance');

		//获取上级信息
        $chanceDao = new model_projectmanagent_chance_chance();
        $chance = $chanceDao->find(array('id' => $_GET['obj']['objId'] ), null);
        $this->assignFunc($chance);


		$this->assignFunc($_GET['obj']);
		$thisObjCode = $this->service->getBusinessCode($_GET['obj']['formType']);
		$this->assign('salesmanId' ,$_SESSION['USER_ID']);
		$this->assign('salesman' ,$_SESSION['USERNAME']);
		$this->showDatadicts(array( 'needEat' => 'YANDN' ));
		$this->showDatadicts(array( 'needPresents' => 'YANDN' ));

		$this->display( $thisObjCode . '-addwin');
    }

    /**
     *  外部申请用add方法
     */
    function c_addWin(){
		$id = $this->service->add_d ( $_POST [$this->objName] );
		if ($id) {
			msgRf ( '添加成功' );
		}else{
			msgRf ( '添加失败');
		}
    }

    /**
     * 重写init
     */
    function c_init(){
        //URL权限控制
        $this->permCheck();
		$perm = isset($_GET['perm']) ? $_GET['perm'] : null ;
		$obj = $this->service->get_d ( $_GET ['id'] );
		//渲染主表数据
		$this->assignFunc($obj);

		$thisObjCode = $this->service->getBusinessCode($obj['formType']);

		if ($perm == 'view') {
			$this->assign( 'needEatCN' ,$this->getDataNameByCode( $obj['needEat']) );
			$this->assign( 'needPresentsCN' ,$this->getDataNameByCode( $obj['needPresents']) );
			$this->display ( $thisObjCode . '-view' );
		} else {
			$this->showDatadicts(array( 'needEat' => 'YANDN' ),$obj['needEat']);
			$this->showDatadicts(array( 'needPresents' => 'YANDN' ),$obj['needPresents']);
			$this->showDatadicts ( array ('payType' => 'DKFS' ),$obj['payType'] );
			$this->display ( $thisObjCode . '-edit' );
		}
    }

    /**
     * 详细申请信息(在对应业务中查看)
     */
    function c_listForObj(){
    	$this->assignFunc($_GET['obj']);
		$this->display( 'listforobj' );
    }

    /**
     * 填写服务记录
     */
    function c_handup(){
        //URL权限控制
        $this->permCheck();
		$obj = $this->service->get_d ( $_GET ['id'] );
		//渲染主表数据
		$this->assignFunc($obj);

		$thisObjCode = $this->service->getBusinessCode($obj['formType']);

		$this->assign('techniciansId',$_SESSION['USER_ID']);
		$this->assign('technicians',$_SESSION['USERNAME']);

		$this->assign( 'needEatCN' ,$this->getDataNameByCode( $obj['needEat']) );
		$this->assign( 'needPresentsCN' ,$this->getDataNameByCode( $obj['needEat']) );
		$this->display ( $thisObjCode . '-handup' );
    }

    /**
     * 提交
     */
    function c_pushUp(){
		if($this->service->update(array( 'id' => $_POST['id']),array('status' => 'XMZT-03'))){
			echo 1;
		}else{
			echo 0;
		}
    }

    /**
     * 撤销
     */
    function c_pushDown(){
		if($this->service->update(array( 'id' => $_POST['id']),array('status' => 'XMZT-01'))){
			echo 1;
		}else{
			echo 0;
		}
    }


/*************************************************************************************************/
   /**
    * 我的售前支持
    */
    function c_MyBeforeListPageJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('select_default');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

}
?>